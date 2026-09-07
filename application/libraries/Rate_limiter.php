<?php
declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Rate Limiter Library
 * Provides sliding/fixed window rate limiting backed by MySQL / SQLite database table.
 */
class Rate_limiter {

    protected CI_Controller $CI;
    protected string $table = 'rate_limits';

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->ensure_table();
    }

    /**
     * Ensure rate limits table exists
     */
    private function ensure_table(): void {
        if (!$this->CI->db->table_exists($this->table)) {
            $this->CI->load->dbforge();
            $this->CI->dbforge->add_field(array(
                'id' => array('type' => 'BIGINT', 'constraint' => 20, 'unsigned' => TRUE, 'auto_increment' => TRUE),
                'rate_key' => array('type' => 'VARCHAR', 'constraint' => 191),
                'hits' => array('type' => 'INT', 'constraint' => 11, 'default' => 1),
                'reset_at' => array('type' => 'INT', 'constraint' => 11),
                'created_at' => array('type' => 'DATETIME', 'null' => TRUE)
            ));
            $this->CI->dbforge->add_key('id', TRUE);
            $this->CI->dbforge->add_key('rate_key');
            $this->CI->dbforge->add_key('reset_at');
            $this->CI->dbforge->create_table($this->table, TRUE);
        }
    }

    /**
     * Check if a key has exceeded maximum attempts in the given window seconds
     *
     * @param string $key Identifier (e.g. "login:ip:127.0.0.1", "api:tenant:1")
     * @param int $max_attempts Maximum allowed requests
     * @param int $window_seconds Time window in seconds
     * @return array ['allowed' => bool, 'remaining' => int, 'retry_after' => int]
     */
    public function check(string $key, int $max_attempts = 60, int $window_seconds = 60): array {
        $now = time();
        $hash_key = hash('sha256', $key);

        // Clean up expired records occasionally (1 in 50 requests)
        if (mt_rand(1, 50) === 1) {
            $this->CI->db->where('reset_at <', $now)->delete($this->table);
        }

        $row = $this->CI->db->get_where($this->table, array('rate_key' => $hash_key))->row();

        if ($row) {
            if ($row->reset_at <= $now) {
                // Window expired, reset counter
                $this->CI->db->where('id', $row->id)->update($this->table, array(
                    'hits' => 1,
                    'reset_at' => $now + $window_seconds
                ));
                return array(
                    'allowed' => true,
                    'remaining' => $max_attempts - 1,
                    'retry_after' => 0
                );
            }

            if ($row->hits >= $max_attempts) {
                // Limit exceeded
                $retry_after = (int)($row->reset_at - $now);
                return array(
                    'allowed' => false,
                    'remaining' => 0,
                    'retry_after' => $retry_after > 0 ? $retry_after : 1
                );
            }

            // Increment hit counter
            $this->CI->db->where('id', $row->id)->set('hits', 'hits + 1', FALSE)->update($this->table);
            return array(
                'allowed' => true,
                'remaining' => max(0, $max_attempts - ((int)$row->hits + 1)),
                'retry_after' => 0
            );
        } else {
            // First hit for this key
            $this->CI->db->insert($this->table, array(
                'rate_key' => $hash_key,
                'hits' => 1,
                'reset_at' => $now + $window_seconds,
                'created_at' => date('Y-m-d H:i:s')
            ));
            return array(
                'allowed' => true,
                'remaining' => $max_attempts - 1,
                'retry_after' => 0
            );
        }
    }

    /**
     * Enforce rate limit and exit with 429 JSON response if exceeded
     */
    public function enforce(string $key, int $max_attempts = 60, int $window_seconds = 60): void {
        $result = $this->check($key, $max_attempts, $window_seconds);
        
        if (!$result['allowed']) {
            if (!headers_sent()) {
                header('Retry-After: ' . $result['retry_after']);
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(429);
            }
            echo json_encode(array(
                'status' => 'error',
                'code' => 429,
                'message' => 'Too Many Requests. Rate limit exceeded.',
                'retry_after_seconds' => $result['retry_after']
            ), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        }
    }
}
