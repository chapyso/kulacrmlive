<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * Action Token & API Bearer Token Security Helper
 */

/**
 * Returns a per-session one-time-style nonce that protects destructive POST
 * endpoints against CSRF.
 */
function action_token()
{
    $CI =& get_instance();
    $token = $CI->session->userdata('action_token');
    if (!$token) {
        if (function_exists('random_bytes')) {
            $token = bin2hex(random_bytes(32));
        } else {
            $token = bin2hex(openssl_random_pseudo_bytes(32));
        }
        $CI->session->set_userdata('action_token', $token);
    }
    return $token;
}

/**
 * Verify action token against session or X-CSRF-Token header
 */
function verify_action_token()
{
    $CI =& get_instance();
    $expected = $CI->session->userdata('action_token');
    if (!$expected) {
        return FALSE;
    }

    $got = $CI->input->post('action_token');
    if (!$got) {
        $headers = array_change_key_case(getallheaders() ?: [], CASE_LOWER);
        $got = $headers['x-csrf-token'] ?? $headers['x-action-token'] ?? null;
    }

    if (!$got) {
        return FALSE;
    }

    return hash_equals($expected, (string)$got);
}

/**
 * Generate a signed, revocable API Bearer token for REST & Mobile clients
 */
function generate_api_token(int $user_id, int $tenant_id, string $email, string $role = 'user', int $ttl_seconds = 2592000): string
{
    $CI =& get_instance();
    $secret = $CI->config->item('encryption_key');
    $jti = bin2hex(random_bytes(16));

    $payload = array(
        'jti'       => $jti,
        'user_id'   => $user_id,
        'tenant_id' => $tenant_id,
        'email'     => $email,
        'role'      => $role,
        'iat'       => time(),
        'exp'       => time() + $ttl_seconds
    );

    $json_payload = json_encode($payload, JSON_UNESCAPED_SLASHES);
    $b64_payload  = rtrim(strtr(base64_encode($json_payload), '+/', '-_'), '=');
    $signature    = hash_hmac('sha256', $b64_payload, $secret);

    return $b64_payload . '.' . $signature;
}

/**
 * Verify an API Bearer token, check active user/tenant status and revocation
 * Returns payload array on success, or null on failure.
 */
function verify_api_token(string $token): ?array
{
    $CI =& get_instance();
    $CI->load->database();
    $secret = $CI->config->item('encryption_key');

    $parts = explode('.', $token);
    if (count($parts) !== 2) {
        return null;
    }

    list($b64_payload, $signature) = $parts;
    $expected_sig = hash_hmac('sha256', $b64_payload, $secret);

    if (!hash_equals($expected_sig, $signature)) {
        return null;
    }

    $json_payload = base64_decode(strtr($b64_payload, '-_', '+/'));
    $payload = json_decode($json_payload, true);

    if (!is_array($payload) || empty($payload['user_id']) || !isset($payload['tenant_id'])) {
        return null;
    }

    // Expiry verification
    if (isset($payload['exp']) && time() > $payload['exp']) {
        return null;
    }

    // Check Token Revocation Table if exists
    if (!empty($payload['jti']) && $CI->db->table_exists('revoked_tokens')) {
        $revoked = $CI->db->get_where('revoked_tokens', array('jti' => $payload['jti']))->row();
        if ($revoked) {
            return null; // Revoked token
        }
    }

    // Verify user is active in DB
    $user = $CI->db->select('id, active, tenant_id')->get_where('users', array('id' => (int)$payload['user_id']))->row();
    if (!$user || (int)$user->active !== 1) {
        return null; // Disabled or deleted user
    }

    // Verify tenant status is active (if not platform super admin)
    $tenant_id = (int)$payload['tenant_id'];
    if ($tenant_id > 0 && $CI->db->table_exists('tenants')) {
        $tenant = $CI->db->select('id, status')->get_where('tenants', array('id' => $tenant_id))->row();
        if (!$tenant || $tenant->status !== 'active') {
            return null; // Suspended or inactive tenant
        }
    }

    return $payload;
}

/**
 * Revoke a token by JTI or user
 */
function revoke_api_token(string $jti, int $user_id, int $tenant_id = 0): bool
{
    $CI =& get_instance();
    $CI->load->database();

    if (!$CI->db->table_exists('revoked_tokens')) {
        $CI->load->dbforge();
        $CI->dbforge->add_field(array(
            'id' => array('type' => 'BIGINT', 'constraint' => 20, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'jti' => array('type' => 'VARCHAR', 'constraint' => 64),
            'user_id' => array('type' => 'INT', 'constraint' => 11),
            'tenant_id' => array('type' => 'INT', 'constraint' => 11, 'default' => 0),
            'revoked_at' => array('type' => 'DATETIME', 'null' => TRUE)
        ));
        $CI->dbforge->add_key('id', TRUE);
        $CI->dbforge->add_key('jti');
        $CI->dbforge->create_table('revoked_tokens', TRUE);
    }

    return $CI->db->insert('revoked_tokens', array(
        'jti' => $jti,
        'user_id' => $user_id,
        'tenant_id' => $tenant_id,
        'revoked_at' => date('Y-m-d H:i:s')
    ));
}
