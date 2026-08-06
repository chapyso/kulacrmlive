<?php
declare(strict_types=1);

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mobile App REST API Controller
 * 
 * Provides JSON API endpoints for Mobile Applications (iOS, Android, React Native, Flutter)
 * with strict PHP 8 typing and security checks.
 */
class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }

        $this->load->database();
        $this->load->library('ion_auth');
    }

    /**
     * Mobile Authentication Login Endpoint
     */
    public function login(): void {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $identity = (string)($input['identity'] ?? '');
        $password = (string)($input['password'] ?? '');

        if (empty($identity) || empty($password)) {
            $this->output_json([
                'status' => 'error',
                'message' => 'Identity and password are required'
            ], 400);
            return;
        }

        if ($this->ion_auth->login($identity, $password, false)) {
            $user = $this->ion_auth->user()->row();
            $this->output_json([
                'status' => 'success',
                'message' => 'Mobile authentication successful',
                'user' => [
                    'id' => (int)$user->id,
                    'username' => (string)$user->username,
                    'email' => (string)$user->email,
                    'first_name' => (string)$user->first_name,
                    'last_name' => (string)$user->last_name,
                ],
                'token' => base64_encode($user->email . ':' . time())
            ]);
        } else {
            $this->output_json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }
    }

    /**
     * Mobile Farm Overview Dashboard Metrics
     */
    public function dashboard(): void {
        $total_livestock = (int)$this->db->where('ls_status', 1)->count_all_results('livestock');
        $total_sheds = (int)$this->db->where('sh_status', 1)->count_all_results('shed');
        $total_clients = (int)$this->db->where('c_status', 1)->count_all_results('client');
        $total_suppliers = (int)$this->db->where('s_status', 1)->count_all_results('supplier');

        $this->output_json([
            'status' => 'success',
            'data' => [
                'total_livestock' => $total_livestock,
                'total_sheds' => $total_sheds,
                'total_clients' => $total_clients,
                'total_suppliers' => $total_suppliers,
            ]
        ]);
    }

    /**
     * Mobile Livestock Directory API
     */
    public function livestock(): void {
        $query = $this->db->select('ls_id, ls_name, ls_description, ls_status')
                          ->get('livestock');
        $animals = $query->result_array();

        $this->output_json([
            'status' => 'success',
            'count' => count($animals),
            'livestock' => $animals
        ]);
    }

    /**
     * Mobile Sheds Directory API
     */
    public function sheds(): void {
        $query = $this->db->select('sh_id, sh_title, sh_no, sh_description, sh_status')
                          ->get('shed');
        $sheds = $query->result_array();

        $this->output_json([
            'status' => 'success',
            'count' => count($sheds),
            'sheds' => $sheds
        ]);
    }

    /**
     * Mobile Sales Invoices API
     */
    public function sales(): void {
        $query = $this->db->select('id, reference, gross_total, amount_received, balance, payment_status, date')
                          ->get('sale');
        $sales = $query->result_array();

        $this->output_json([
            'status' => 'success',
            'count' => count($sales),
            'sales' => $sales
        ]);
    }

    /**
     * Helper method to output JSON responses with HTTP status codes
     */
    private function output_json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
