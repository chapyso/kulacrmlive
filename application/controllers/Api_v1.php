<?php
declare(strict_types=1);

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Enterprise Public REST API v1 Controller
 * 
 * Multi-tenant isolated, Bearer-token authenticated REST API endpoints
 * supporting Mobile (iOS/Android/Flutter/React Native) and Third-Party integrations.
 */
class Api_v1 extends CI_Controller {

    protected ?int $user_id = null;
    protected int $tenant_id = 0;
    protected ?string $user_email = null;
    protected ?string $user_role = null;

    public function __construct() {
        parent::__construct();

        // Enable CORS for API clients
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key, X-Requested-With');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }

        $this->load->database();
        $this->load->library('ion_auth');
        $this->load->helper('action_token');
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
                'message' => 'Invalid or expired API token'
            ], 401);
        }

        $this->user_id    = (int)$payload['user_id'];
        $this->tenant_id  = (int)$payload['tenant_id'];
        $this->user_email = (string)$payload['email'];
        $this->user_role  = (string)($payload['role'] ?? 'user');
    }

    /**
     * Extract token from Authorization header or X-API-Key
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

        $token_get = $this->input->get('token');
        if (!empty($token_get)) {
            return (string)$token_get;
        }

        return null;
    }

    /**
     * POST /api/v1/auth/login
     */
    public function login(): void {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $identity = (string)($input['identity'] ?? '');
        $password = (string)($input['password'] ?? '');

        if (empty($identity) || empty($password)) {
            $this->output_json([
                'status'  => 'error',
                'code'    => 400,
                'message' => 'Identity and password parameters are required'
            ], 400);
        }

        if ($this->ion_auth->login($identity, $password, false)) {
            $user = $this->ion_auth->user()->row();
            $tenant_id = isset($user->tenant_id) ? (int)$user->tenant_id : 1;
            
            $groups = $this->ion_auth->get_users_groups($user->id)->result();
            $role = !empty($groups) ? $groups[0]->name : 'members';

            $token = generate_api_token((int)$user->id, $tenant_id, (string)$user->email, $role);

            $this->output_json([
                'status'  => 'success',
                'code'    => 200,
                'message' => 'Authentication successful',
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
                'message' => 'Invalid email/username or password'
            ], 401);
        }
    }

    /**
     * GET /api/v1/auth/me
     */
    public function me(): void {
        $this->authenticate();

        $user = $this->db->get_where('users', array('id' => $this->user_id))->row();

        if (!$user) {
            $this->output_json([
                'status'  => 'error',
                'code'    => 404,
                'message' => 'User record not found'
            ], 404);
        }

        $tenant = $this->db->get_where('tenants', array('id' => $this->tenant_id))->row();

        $this->output_json([
            'status' => 'success',
            'code'   => 200,
            'data'   => [
                'user' => [
                    'id'         => (int)$user->id,
                    'username'   => (string)$user->username,
                    'email'      => (string)$user->email,
                    'first_name' => (string)$user->first_name,
                    'last_name'  => (string)$user->last_name,
                    'phone'      => (string)($user->phone ?? '')
                ],
                'tenant' => $tenant ? [
                    'id'     => (int)$tenant->id,
                    'name'   => (string)$tenant->name,
                    'slug'   => (string)$tenant->slug,
                    'status' => (string)$tenant->status
                ] : null
            ]
        ]);
    }

    /**
     * GET /api/v1/dashboard
     */
    public function dashboard(): void {
        $this->authenticate();

        $total_livestock = (int)$this->db->where('ls_status', 1)->where('tenant_id', $this->tenant_id)->count_all_results('livestock');
        $total_sheds     = (int)$this->db->where('sh_status', 1)->where('tenant_id', $this->tenant_id)->count_all_results('shed');
        $total_clients   = (int)$this->db->where('c_status', 1)->where('tenant_id', $this->tenant_id)->count_all_results('client');
        $total_suppliers = (int)$this->db->where('s_status', 1)->where('tenant_id', $this->tenant_id)->count_all_results('supplier');

        $sales = $this->db->query("SELECT SUM(sale_grand_total) as rev FROM sale WHERE sale_status = 1 AND tenant_id = ?", array($this->tenant_id))->row();
        $expenses = $this->db->query("SELECT SUM(amount) as exp FROM expense WHERE ex_status = 1 AND tenant_id = ?", array($this->tenant_id))->row();

        $revenue      = $sales ? (float)$sales->rev : 0.0;
        $total_exp    = $expenses ? (float)$expenses->exp : 0.0;
        $net_profit   = $revenue - $total_exp;

        $this->output_json([
            'status' => 'success',
            'code'   => 200,
            'data'   => [
                'livestock_count' => $total_livestock,
                'sheds_count'     => $total_sheds,
                'clients_count'   => $total_clients,
                'suppliers_count' => $total_suppliers,
                'total_revenue'   => $revenue,
                'total_expenses'  => $total_exp,
                'net_profit'      => $net_profit
            ]
        ]);
    }

    /**
     * GET/POST /api/v1/livestock
     */
    public function livestock(): void {
        $this->authenticate();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            $name = trim((string)($input['name'] ?? ''));
            $desc = trim((string)($input['description'] ?? ''));

            if (empty($name)) {
                $this->output_json(['status' => 'error', 'code' => 400, 'message' => 'Livestock name is required'], 400);
            }

            $data = array(
                'ls_name'        => $name,
                'ls_description' => $desc,
                'ls_status'      => 1,
                'tenant_id'      => $this->tenant_id,
                'created_at'     => date('Y-m-d H:i:s')
            );
            $this->db->insert('livestock', $data);
            $new_id = $this->db->insert_id();

            $this->output_json([
                'status'  => 'success',
                'code'    => 201,
                'message' => 'Livestock recorded successfully',
                'data'    => array_merge(['ls_id' => $new_id], $data)
            ], 201);
        }

        $animals = $this->db->select('ls_id, ls_name, ls_description, ls_status, created_at')
                            ->where('tenant_id', $this->tenant_id)
                            ->get('livestock')->result_array();

        $this->output_json([
            'status' => 'success',
            'code'   => 200,
            'meta'   => ['count' => count($animals)],
            'data'   => $animals
        ]);
    }

    /**
     * GET /api/v1/sheds
     */
    public function sheds(): void {
        $this->authenticate();

        $sheds = $this->db->select('sh_id, sh_title, sh_no, sh_description, sh_status')
                          ->where('tenant_id', $this->tenant_id)
                          ->get('shed')->result_array();

        $this->output_json([
            'status' => 'success',
            'code'   => 200,
            'meta'   => ['count' => count($sheds)],
            'data'   => $sheds
        ]);
    }

    /**
     * GET /api/v1/vaccines
     */
    public function vaccines(): void {
        $this->authenticate();

        $vaccines = $this->db->select('vccn_id, vccn_name, vccn_description, vccn_status')
                             ->where('tenant_id', $this->tenant_id)
                             ->get('vaccine')->result_array();

        $this->output_json([
            'status' => 'success',
            'code'   => 200,
            'meta'   => ['count' => count($vaccines)],
            'data'   => $vaccines
        ]);
    }

    /**
     * GET/POST /api/v1/sales
     */
    public function sales(): void {
        $this->authenticate();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            $client_id = (int)($input['client_id'] ?? 0);
            $grand_total = (float)($input['grand_total'] ?? 0.0);

            if ($grand_total <= 0) {
                $this->output_json(['status' => 'error', 'code' => 400, 'message' => 'Valid grand total is required'], 400);
            }

            $reference = 'INV-' . strtoupper(substr(md5((string)microtime()), 0, 8));
            $data = array(
                'tenant_id'        => $this->tenant_id,
                'client_id'        => $client_id,
                'reference'        => $reference,
                'sale_grand_total' => $grand_total,
                'sale_status'      => 1,
                'created_at'       => date('Y-m-d H:i:s')
            );
            $this->db->insert('sale', $data);
            $sale_id = $this->db->insert_id();

            $this->output_json([
                'status'  => 'success',
                'code'    => 201,
                'message' => 'Sale invoice created',
                'data'    => array_merge(['id' => $sale_id], $data)
            ], 201);
        }

        $sales = $this->db->select('id, reference, client_id, sale_grand_total, sale_status, created_at')
                          ->where('tenant_id', $this->tenant_id)
                          ->order_by('id', 'DESC')
                          ->get('sale')->result_array();

        $this->output_json([
            'status' => 'success',
            'code'   => 200,
            'meta'   => ['count' => count($sales)],
            'data'   => $sales
        ]);
    }

    /**
     * GET/POST /api/v1/expenses
     */
    public function expenses(): void {
        $this->authenticate();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            $amount = (float)($input['amount'] ?? 0.0);
            $purpose = trim((string)($input['purpose'] ?? ''));

            if ($amount <= 0 || empty($purpose)) {
                $this->output_json(['status' => 'error', 'code' => 400, 'message' => 'Amount and purpose are required'], 400);
            }

            $data = array(
                'tenant_id'  => $this->tenant_id,
                'amount'     => $amount,
                'ex_purpose' => $purpose,
                'ex_status'  => 1,
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('expense', $data);
            $ex_id = $this->db->insert_id();

            $this->output_json([
                'status'  => 'success',
                'code'    => 201,
                'message' => 'Expense recorded',
                'data'    => array_merge(['ex_id' => $ex_id], $data)
            ], 201);
        }

        $expenses = $this->db->select('ex_id, amount, ex_purpose, ex_status, created_at')
                             ->where('tenant_id', $this->tenant_id)
                             ->order_by('ex_id', 'DESC')
                             ->get('expense')->result_array();

        $this->output_json([
            'status' => 'success',
            'code'   => 200,
            'meta'   => ['count' => count($expenses)],
            'data'   => $expenses
        ]);
    }

    /**
     * GET /api/v1/clients
     */
    public function clients(): void {
        $this->authenticate();

        $clients = $this->db->select('c_id, c_name, c_email, c_phone, c_status')
                           ->where('tenant_id', $this->tenant_id)
                           ->get('client')->result_array();

        $this->output_json([
            'status' => 'success',
            'code'   => 200,
            'meta'   => ['count' => count($clients)],
            'data'   => $clients
        ]);
    }

    /**
     * GET /api/v1/suppliers
     */
    public function suppliers(): void {
        $this->authenticate();

        $suppliers = $this->db->select('s_id, s_name, s_email, s_phone, s_status')
                             ->where('tenant_id', $this->tenant_id)
                             ->get('supplier')->result_array();

        $this->output_json([
            'status' => 'success',
            'code'   => 200,
            'meta'   => ['count' => count($suppliers)],
            'data'   => $suppliers
        ]);
    }

    /**
     * GET /api/v1/reports/summary
     */
    public function reports_summary(): void {
        $this->authenticate();

        $sales_count    = (int)$this->db->where('tenant_id', $this->tenant_id)->where('sale_status', 1)->count_all_results('sale');
        $expenses_count = (int)$this->db->where('tenant_id', $this->tenant_id)->where('ex_status', 1)->count_all_results('expense');

        $this->output_json([
            'status' => 'success',
            'code'   => 200,
            'data'   => [
                'tenant_id'      => $this->tenant_id,
                'sales_count'    => $sales_count,
                'expenses_count' => $expenses_count,
                'generated_at'   => date('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * GET /api/v1/users
     */
    public function users(): void {
        $this->authenticate();
        $this->load->model('Tenant_user_model');
        $users = $this->Tenant_user_model->getTenantUsers($this->tenant_id);

        $this->output_json([
            'status' => 'success',
            'code'   => 200,
            'meta'   => ['count' => count($users)],
            'data'   => $users
        ]);
    }

    /**
     * GET /api/v1/roles
     */
    public function roles(): void {
        $this->authenticate();
        $this->load->model('Rbac_model');
        $roles = $this->Rbac_model->getRoles($this->tenant_id);

        $this->output_json([
            'status' => 'success',
            'code'   => 200,
            'meta'   => ['count' => count($roles)],
            'data'   => $roles
        ]);
    }

    /**
     * GET /api/v1/permissions
     */
    public function permissions(): void {
        $this->authenticate();
        $this->load->model('Rbac_model');
        $grouped = $this->Rbac_model->getAllPermissionsGrouped();

        $this->output_json([
            'status' => 'success',
            'code'   => 200,
            'data'   => $grouped
        ]);
    }

    /**
     * GET /api/v1/departments
     */
    public function departments(): void {
        $this->authenticate();
        $this->load->model('Department_model');
        $departments = $this->Department_model->getDepartments($this->tenant_id);

        $this->output_json([
            'status' => 'success',
            'code'   => 200,
            'meta'   => ['count' => count($departments)],
            'data'   => $departments
        ]);
    }


    /**
     * Output standardized JSON response with proper HTTP status
     */
    protected function output_json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
