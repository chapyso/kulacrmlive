<?php
declare(strict_types=1);

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mobile App REST API Controller (Legacy Endpoint Compatibility Layer)
 * 
 * Hardened with cryptographic Bearer token authentication, rate limiting,
 * and strict multi-tenant data isolation.
 */
class Api extends CI_Controller {

    protected ?int $user_id = null;
    protected int $tenant_id = 0;
    protected ?string $user_email = null;
    protected ?string $user_role = null;

    public function __construct() {
        parent::__construct();

        // Security headers & CORS
        $this->enforce_cors_and_headers();

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }

        $this->load->database();
        $this->load->library('ion_auth');
        $this->load->library('Rate_limiter', null, 'rate_limiter');
        $this->load->helper('action_token');
    }

    /**
     * Enforce strict CORS and security headers
     */
    private function enforce_cors_and_headers(): void {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key, X-Requested-With');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
    }

    /**
     * Authenticate Request Token & Populate Tenant Context
     */
    protected function authenticate(): void {
        $token = $this->extract_bearer_token();

        if (empty($token)) {
            $this->output_json([
                'status'  => 'error',
                'code'    => 401,
                'message' => 'Missing Authorization header or Bearer token'
            ], 401);
        }

        $payload = verify_api_token($token);

        if (!$payload) {
            $this->output_json([
                'status'  => 'error',
                'code'    => 401,
                'message' => 'Invalid, expired, or revoked API token'
            ], 401);
        }

        $this->user_id    = (int)$payload['user_id'];
        $this->tenant_id  = (int)$payload['tenant_id'];
        $this->user_email = (string)$payload['email'];
        $this->user_role  = (string)($payload['role'] ?? 'user');

        // Apply rate limit per tenant
        $this->rate_limiter->enforce('legacy_api:tenant:' . $this->tenant_id, 120, 60);
    }

    /**
     * Extract token from Authorization header or X-API-Key (Reject URL parameters)
     */
    private function extract_bearer_token(): ?string {
        $headers = array_change_key_case(getallheaders() ?: [], CASE_LOWER);
        
        if (isset($headers['authorization'])) {
            $auth = trim($headers['authorization']);
            if (preg_match('/Bearer\s+(.*)$/i', $auth, $matches)) {
                return trim($matches[1]);
            }
        }

        if (isset($headers['x-api-key'])) {
            return trim($headers['x-api-key']);
        }

        return null;
    }

    /**
     * Mobile Authentication Login Endpoint
     */
    public function login(): void {
        // Rate limit login attempts by IP
        $ip = $this->input->ip_address();
        $this->rate_limiter->enforce('login:ip:' . $ip, 5, 60);

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $identity = trim((string)($input['identity'] ?? ''));
        $password = (string)($input['password'] ?? '');

        if (empty($identity) || empty($password)) {
            $this->output_json([
                'status'  => 'error',
                'code'    => 400,
                'message' => 'Identity and password are required'
            ], 400);
        }

        if ($this->ion_auth->login($identity, $password, false)) {
            $user = $this->ion_auth->user()->row();

            if ((int)$user->active !== 1) {
                $this->output_json([
                    'status'  => 'error',
                    'code'    => 403,
                    'message' => 'User account is deactivated'
                ], 403);
            }

            $tenant_id = isset($user->tenant_id) ? (int)$user->tenant_id : 1;

            // Check tenant status
            if ($tenant_id > 0 && $this->db->table_exists('tenants')) {
                $tenant = $this->db->get_where('tenants', array('id' => $tenant_id))->row();
                if ($tenant && $tenant->status !== 'active') {
                    $this->output_json([
                        'status'  => 'error',
                        'code'    => 403,
                        'message' => 'Tenant organization account is suspended'
                    ], 403);
                }
            }

            $groups = $this->ion_auth->get_users_groups($user->id)->result();
            $role = !empty($groups) ? $groups[0]->name : 'members';

            $token = generate_api_token((int)$user->id, $tenant_id, (string)$user->email, $role);

            $this->output_json([
                'status'  => 'success',
                'code'    => 200,
                'message' => 'Mobile authentication successful',
                'token'   => $token,
                'user'    => [
                    'id'         => (int)$user->id,
                    'username'   => (string)$user->username,
                    'email'      => (string)$user->email,
                    'first_name' => (string)$user->first_name,
                    'last_name'  => (string)$user->last_name,
                    'tenant_id'  => $tenant_id,
                    'role'       => $role
                ]
            ]);
        } else {
            $this->output_json([
                'status'  => 'error',
                'code'    => 401,
                'message' => 'Invalid credentials'
            ], 401);
        }
    }

    /**
     * Mobile Farm Overview Dashboard Metrics
     */
    public function dashboard(): void {
        $this->authenticate();

        $total_livestock = (int)$this->db->where('ls_status', 1)->where('tenant_id', $this->tenant_id)->count_all_results('livestock');
        $total_sheds     = (int)$this->db->where('sh_status', 1)->where('tenant_id', $this->tenant_id)->count_all_results('shed');
        $total_clients   = (int)$this->db->where('c_status', 1)->where('tenant_id', $this->tenant_id)->count_all_results('client');
        $total_suppliers = (int)$this->db->where('s_status', 1)->where('tenant_id', $this->tenant_id)->count_all_results('supplier');

        $this->output_json([
            'status' => 'success',
            'code'   => 200,
            'data'   => [
                'total_livestock' => $total_livestock,
                'total_sheds'     => $total_sheds,
                'total_clients'   => $total_clients,
                'total_suppliers' => $total_suppliers,
            ]
        ]);
    }

    /**
     * Mobile Livestock Directory API
     */
    public function livestock(): void {
        $this->authenticate();

        $animals = $this->db->select('ls_id, ls_name, ls_description, ls_status')
                            ->where('tenant_id', $this->tenant_id)
                            ->where('ls_status', 1)
                            ->order_by('ls_id', 'DESC')
                            ->get('livestock')->result_array();

        $this->output_json([
            'status'    => 'success',
            'code'      => 200,
            'count'     => count($animals),
            'livestock' => $animals
        ]);
    }

    /**
     * Mobile Sheds Directory API
     */
    public function sheds(): void {
        $this->authenticate();

        $sheds = $this->db->select('sh_id, sh_title, sh_no, sh_description, sh_status')
                          ->where('tenant_id', $this->tenant_id)
                          ->where('sh_status', 1)
                          ->order_by('sh_no', 'ASC')
                          ->get('shed')->result_array();

        $this->output_json([
            'status' => 'success',
            'code'   => 200,
            'count'  => count($sheds),
            'sheds'  => $sheds
        ]);
    }

    /**
     * Mobile Sales Invoices API
     */
    public function sales(): void {
        $this->authenticate();

        $sales = $this->db->select('id, reference, client_id, sale_grand_total, sale_status, created_at')
                          ->where('tenant_id', $this->tenant_id)
                          ->order_by('id', 'DESC')
                          ->limit(100)
                          ->get('sale')->result_array();

        $this->output_json([
            'status' => 'success',
            'code'   => 200,
            'count'  => count($sales),
            'sales'  => $sales
        ]);
    }

    /**
     * Output standardized JSON response
     */
    private function output_json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
