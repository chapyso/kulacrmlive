<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Kula_ai Controller
 * Main HMVC endpoint for KulaCRM AI Intelligence Layer.
 * Extends MY_Controller to inherit active tenant resolution ($this->tenant_id)
 * and authentication guards.
 */
class Kula_ai extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            $is_ajax = $this->input->is_ajax_request() || 
                       (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
                       !empty($_POST['prompt']) ||
                       (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
            if ($is_ajax) {
                if (!headers_sent()) {
                    header('Content-Type: application/json');
                    http_response_code(200);
                }
                echo json_encode(array(
                    'status'   => false, 
                    'error'    => 'Session expired. Please refresh page and log in to use KulaAI.',
                    'response' => '⚠️ Session expired. Please refresh page and log in to use KulaAI.'
                ));
                exit();
            } else {
                redirect('auth/login', 'refresh');
            }
        }

        $this->load->model('kula_ai/kula_ai_model');
        $this->load->library('kula_ai/Ai_provider', null, 'ai_provider');

        if (method_exists($this->load, 'service')) {
            $this->load->service('kula_ai/Ai_tool_service', null, 'ai_tool_service');
            $this->load->service('kula_ai/Ai_analytics_service', null, 'ai_analytics_service');
            $this->load->service('kula_ai/Ai_document_service', null, 'ai_document_service');
        }

        if (!isset($this->ai_tool_service)) {
            require_once APPPATH . 'modules/kula_ai/services/Ai_tool_service.php';
            $this->ai_tool_service = new Ai_tool_service();
        }
        if (!isset($this->ai_intent_service)) {
            require_once APPPATH . 'modules/kula_ai/services/Ai_intent_service.php';
            $this->ai_intent_service = new Ai_intent_service();
        }
        if (!isset($this->ai_analytics_service)) {
            require_once APPPATH . 'modules/kula_ai/services/Ai_analytics_service.php';
            $this->ai_analytics_service = new Ai_analytics_service();
        }
        if (!isset($this->ai_document_service)) {
            require_once APPPATH . 'modules/kula_ai/services/Ai_document_service.php';
            $this->ai_document_service = new Ai_document_service();
        }
        if (!isset($this->ai_ingestion_service)) {
            require_once APPPATH . 'modules/kula_ai/services/Ai_ingestion_service.php';
            $this->ai_ingestion_service = new Ai_ingestion_service();
        }
        if (!isset($this->ai_vision_service)) {
            require_once APPPATH . 'modules/kula_ai/services/Ai_vision_service.php';
            $this->ai_vision_service = new Ai_vision_service();
        }
    }

    /**
     * Check if tenant's subscription plan permits KulaAI access
     */
    protected function check_plan_ai_access() {
        if (isset($this->context) && $this->context === 'PLATFORM' && !($this->is_impersonating ?? false)) {
            return array('has_access' => true, 'plan_name' => 'Platform Super Admin');
        }

        $tenant_id = $this->tenant_id ?: ($this->session->userdata('tenant_id') ?: 1);

        $tenant = $this->db->select('tenants.*, subscription_plans.name as plan_name, subscription_plans.has_ai_access')
                           ->from('tenants')
                           ->join('subscription_plans', 'subscription_plans.id = tenants.plan_id', 'left')
                           ->where('tenants.id', $tenant_id)
                           ->get()
                           ->row();

        if ($tenant) {
            $has_access = (isset($tenant->has_ai_access)) ? (bool)$tenant->has_ai_access : true;
            return array(
                'has_access' => $has_access,
                'plan_name'  => $tenant->plan_name ?? 'Current Plan'
            );
        }

        return array('has_access' => true, 'plan_name' => 'Standard Plan');
    }

    /**
     * Kula Intelligence — Full Dedicated Page
     * Route: /kula_ai/intelligence
     */
    public function intelligence() {
        $data = array();
        $data['settings'] = $this->db->get_where('settings', array('id' => 1))->row();
        $insights = $this->ai_analytics_service->get_proactive_insights();
        $data['insights']    = $insights;
        $data['ai_insights'] = $insights;

        // dashboard.php and footer.php live in home/views — must prefix with module name
        $this->load->view('home/dashboard', $data);
        $this->load->view('kula_ai/ai_intelligence_page', $data);
        $this->load->view('home/footer', $data);
    }

    /**
     * Chat Assistant Endpoint (AJAX POST)
     */
    public function chat() {
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }

        $prompt = trim($this->input->post('prompt') ?? '');
        if (empty($prompt)) {
            echo json_encode(array('status' => false, 'error' => 'Prompt cannot be empty.'));
            return;
        }

        // Parse optional chat history for context memory
        $chat_history_raw = $this->input->post('history');
        $chat_history = array();
        if (!empty($chat_history_raw)) {
            if (is_string($chat_history_raw)) {
                $chat_history = json_decode($chat_history_raw, true) ?? array();
            } elseif (is_array($chat_history_raw)) {
                $chat_history = $chat_history_raw;
            }
        }

        try {
            $gate = $this->check_plan_ai_access();
            if (!$gate['has_access']) {
                echo json_encode(array(
                    'status'   => false,
                    'response' => "🔒 **KulaAI Intelligence is a Premium Feature**\n\nKulaAI features are not included in your current subscription plan (**" . htmlspecialchars($gate['plan_name']) . "**).\n\nPlease contact your Super Admin to upgrade your subscription plan to Professional or Enterprise tier to unlock real-time AI farm insights.",
                    'plan_upgrade_required' => true
                ));
                return;
            }

            // 1. Classify User Intent & Required KulaCRM Tools
            $intent_info = $this->ai_intent_service->classify_intent($prompt, $chat_history);
            $tools_used  = $intent_info['tools'] ?? array();
            $context_data= array();

            // 2. Retrieve Authorized Live Tenant Data if required by intent
            if (!empty($intent_info['requires_data']) && !empty($tools_used)) {
                foreach ($tools_used as $tool) {
                    if (isset($this->ai_tool_service)) {
                        $context_data[$tool] = $this->ai_tool_service->execute_tool($tool);
                    }
                }
            }

            // 3. Dynamic System Prompt tailored to Intent
            $system_prompt = "You are KulaAI, a highly intelligent, versatile AI Assistant and Livestock Agribusiness Expert built into KulaCRM.\n\n"
                . "DYNAMIC RESPONSE GUIDELINES:\n"
                . "1. NATURAL & CONVERSATIONAL FIRST: Be conversational first, analytical second. Match your response style directly to the user's intent (" . ($intent_info['intent'] ?? 'GENERAL') . ").\n"
                . "2. GREETINGS & CASUAL TALK: Respond warmly, naturally, and concisely. Do NOT generate action steps, executive recommendations, or rigid templates for simple greetings like 'Hey', 'Hello', 'Good morning', or 'How are you?'.\n"
                . "3. GENERAL KNOWLEDGE: Answer general educational or agribusiness questions directly and clearly. Only reference KulaCRM if relevant.\n"
                . "4. REAL KULACRM DATA: When answering farm metric or stock queries, rely strictly on the provided live KulaCRM database context. Report exact numbers accurately. Do NOT invent farm data or statistics.\n"
                . "5. STRUCTURED RECOMMENDATIONS & REPORTS: Only output structured 'Executive Recommendations' or step-by-step action plans when the user explicitly requests recommendations, performance analysis, or reports.\n"
                . "6. CLEAN FORMATTING: Use clean GitHub Markdown. Never include internal signature lines such as 'Powered by KulaAI Farm Intelligence Layer'.";

            // 4. Generate Response via Active Provider (or Intent-Aware Offline Engine)
            $result = $this->ai_provider->generate($system_prompt, $prompt, $context_data, $chat_history, $intent_info);

            if (empty($result['response'])) {
                $result['response'] = $this->ai_provider->generate_offline_response($prompt, $context_data, $intent_info);
                $result['status'] = true;
                $result['provider'] = 'KulaAI Intent Engine (Offline)';
            }

            // 5. Audit Log Interaction
            if (isset($this->kula_ai_model)) {
                $this->kula_ai_model->log_interaction(
                    $prompt,
                    $tools_used,
                    strtolower($intent_info['intent'] ?? 'chat_query'),
                    !empty($result['status']) ? 'success' : 'error'
                );
            }

            echo json_encode(array(
                'status'        => true,
                'response'      => $result['response'],
                'intent'        => $intent_info['intent'],
                'response_type' => $intent_info['response_type'] ?? 'conversational',
                'provider'      => $result['provider'] ?? 'KulaAI Engine',
                'tools'         => $tools_used,
                'created_at'    => date('H:i:s')
            ));
        } catch (\Throwable $e) {
            $fallback_intent = array('intent' => 'UNKNOWN', 'response_type' => 'conversational');
            $fallback_response = $this->ai_provider->generate_offline_response($prompt, array(), $fallback_intent);
            echo json_encode(array(
                'status'        => true,
                'response'      => $fallback_response,
                'intent'        => 'UNKNOWN',
                'response_type' => 'conversational',
                'provider'      => 'KulaAI Resilient Fallback',
                'created_at'    => date('H:i:s')
            ));
        }
    }

    /**
     * Get Chat History Endpoint (ChatGPT-style Prompt History)
     */
    public function history() {
        $history = $this->kula_ai_model->get_chat_history(30);
        echo json_encode(array(
            'status'  => true,
            'history' => $history
        ));
    }

    /**
     * Report Explanation Endpoint ("Explain with KulaAI")
     */
    public function explain_report() {
        $report_title = trim($this->input->post('report_title') ?? 'Farm Performance Report');
        $report_data  = $this->input->post('report_data') ?? array();

        if (empty($report_data)) {
            $report_data = $this->ai_tool_service->get_farm_summary();
        }

        $system_prompt = "You are KulaAI Report Interpreter. Provide a concise 3-bullet natural language explanation "
            . "of the following KulaCRM report: '{$report_title}'. Highlight key numbers, mortality/sales trends, and actionable observations. "
            . "Do NOT alter any numerical values.";

        $user_prompt = "Please explain the findings of '{$report_title}'.";

        $result = $this->ai_provider->generate($system_prompt, $user_prompt, array('report_data' => $report_data));

        $this->kula_ai_model->log_interaction(
            "Report explanation: " . $report_title,
            array('report_explainer'),
            'report_explanation',
            $result['status'] ? 'success' : 'error'
        );

        echo json_encode(array(
            'status'       => $result['status'],
            'explanation'  => $result['response'],
            'report_title' => $report_title
        ));
    }

    /**
     * Export AI Response / Report to PDF using Dompdf
     */
    public function export_pdf() {
        $content = $this->input->post('content');
        $title = trim($this->input->post('title') ?? 'KulaAI Executive Report');

        if (empty($content)) {
            $content = '<p>No content provided for PDF export.</p>';
        }

        $dompdf_file = APPPATH . 'third_party/dompdf/autoload.php';
        if (file_exists($dompdf_file)) {
            require_once $dompdf_file;
        }

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>' . htmlspecialchars($title) . '</title>
            <style>
                body { font-family: sans-serif; font-size: 12px; color: #1e293b; margin: 20px; line-height: 1.5; }
                .header { border-bottom: 2px solid #6366f1; padding-bottom: 10px; margin-bottom: 20px; }
                .title { font-size: 18px; font-weight: bold; color: #0f172a; }
                .sub { font-size: 11px; color: #64748b; margin-top: 4px; }
                table { width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 11px; }
                th { background: #6366f1; color: #ffffff; padding: 8px 10px; text-align: left; }
                td { border: 1px solid #e2e8f0; padding: 8px 10px; }
                h1, h2, h3, h4 { color: #4338ca; margin: 14px 0 6px 0; }
                hr { border: none; border-top: 1px solid #cbd5e1; margin: 14px 0; }
                .footer { margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 10px; font-size: 10px; color: #94a3b8; text-align: center; }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="title">KulaAI Intelligence Executive Report</div>
                <div class="sub">Generated on ' . date('F j, Y \a\t H:i:s') . ' | KulaCRM Livestock System</div>
            </div>
            <div class="content">' . $content . '</div>
            <div class="footer">Confidential Report — Generated automatically by KulaCRM AI Intelligence Layer.</div>
        </body>
        </html>';

        if (class_exists('Dompdf\Dompdf')) {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream(preg_replace('/[^a-zA-Z0-9_-]/', '_', $title) . '.pdf', array('Attachment' => 1));
        } else {
            echo "PDF Generator class not available.";
        }
    }

    /**
     * Export AI Query Output or Data Table as native CSV file
     */
    public function export_csv() {
        $filename = 'KulaAI_Export_' . date('Ymd_His') . '.csv';
        $content  = $this->input->post('content') ?? '';
        $title    = $this->input->post('title') ?? 'KulaAI Data Export';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Write UTF-8 BOM for Excel compatibility
        fputs($output, $bom = chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, array('KulaCRM AI Data Export', date('Y-m-d H:i:s')));
        fputcsv($output, array($title));
        fputcsv($output, array(''));

        // Strip HTML tags and output structured lines
        $lines = explode("\n", strip_tags(str_replace(array('<br>', '<br/>', '<br />', '</tr>', '</li>'), "\n", $content)));
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (!empty($trimmed)) {
                // If contains tab or delimiter, split cleanly
                $cells = preg_split('/[|\t]/', $trimmed);
                $cells = array_map('trim', array_filter($cells));
                if (!empty($cells)) {
                    fputcsv($output, $cells);
                } else {
                    fputcsv($output, array($trimmed));
                }
            }
        }

        fclose($output);
        exit();
    }

    /**
     * Send Proactive Daily Farm Intelligence Summary via CodeIgniter Native Email
     */
    public function send_daily_digest() {
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }

        $insights = $this->ai_analytics_service->get_proactive_insights();

        $user_email = $this->session->userdata('email') ?? 'admin@gmail.com';
        $user_name  = $this->session->userdata('username') ?? 'Farm Manager';

        $this->load->library('email');

        $config = array(
            'protocol'  => 'smtp',
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'wordwrap'  => TRUE
        );

        $this->email->initialize($config);
        $this->email->from('noreply@kulacrm.com', 'KulaAI Intelligence');
        $this->email->to($user_email);
        $this->email->subject('🌅 KulaAI Daily Farm Intelligence Briefing — ' . date('F j, Y'));

        $body = '<h2>Good morning, ' . htmlspecialchars($user_name) . '! 🚜</h2>';
        $body .= '<p>Here is your daily KulaAI farm intelligence summary:</p>';

        if (!empty($insights['mortality_alerts'])) {
            $body .= '<h3 style="color:#ef4444;">🔴 Mortality Alerts (' . count($insights['mortality_alerts']) . ')</h3><ul>';
            foreach ($insights['mortality_alerts'] as $a) {
                $body .= '<li><strong>' . htmlspecialchars($a['title']) . '</strong>: ' . htmlspecialchars($a['description']) . '</li>';
            }
            $body .= '</ul>';
        }

        if (!empty($insights['vaccination_alerts'])) {
            $body .= '<h3 style="color:#f59e0b;">💉 Vaccination Schedule (' . count($insights['vaccination_alerts']) . ')</h3><ul>';
            foreach ($insights['vaccination_alerts'] as $v) {
                $body .= '<li><strong>' . htmlspecialchars($v['title']) . '</strong>: ' . htmlspecialchars($v['description']) . '</li>';
            }
            $body .= '</ul>';
        }

        $body .= '<br/><p><a href="' . site_url('kula_ai/intelligence') . '" style="background:#6366f1; color:#fff; padding:10px 18px; text-decoration:none; border-radius:6px; font-weight:bold;">View Full KulaAI Intelligence Dashboard</a></p>';

        $this->email->message($body);

        $sent = @$this->email->send();

        echo json_encode(array(
            'status'  => true,
            'sent'    => $sent,
            'message' => 'Daily farm intelligence summary dispatched to ' . $user_email
        ));
    }

    /**
     * Proactive Kula Intelligence Dashboard Feed (AJAX GET)
     */
    public function get_proactive_widget() {
        $insights = $this->ai_analytics_service->get_proactive_insights();

        if ($this->input->is_ajax_request()) {
            echo json_encode(array('status' => true, 'insights' => $insights));
            return;
        }

        $this->load->view('kula_ai/ai_dashboard_widget', array('insights' => $insights));
    }

    /**
     * Document Upload Analysis Endpoint (AJAX POST)
     */
    public function analyze_document() {
        if (empty($_FILES['document_file']['name'])) {
            echo json_encode(array('status' => false, 'error' => 'No file uploaded.'));
            return;
        }

        $upload_dir = APPPATH . '../uploads/ai_imports/';
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0755, true);
        }

        $file_name = time() . '_' . $_FILES['document_file']['name'];
        $target_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['document_file']['tmp_name'], $target_path)) {
            $analysis = $this->ai_document_service->analyze_document($target_path, $_FILES['document_file']['name'], $_FILES['document_file']['type']);
            
            $this->kula_ai_model->log_interaction(
                "Document OCR parsing: " . $_FILES['document_file']['name'],
                array('document_ocr'),
                'document_parsing',
                'success',
                'pending_approval'
            );

            echo json_encode($analysis);
        } else {
            echo json_encode(array('status' => false, 'error' => 'Failed to upload document file.'));
        }
    }

    /**
     * Approve Document Import Action
     */
    public function approve_import() {
        $import_payload = $this->input->post('import_payload');
        if (is_string($import_payload)) {
            $import_payload = json_decode($import_payload, true);
        }

        if (empty($import_payload)) {
            echo json_encode(array('status' => false, 'error' => 'Invalid import data.'));
            return;
        }

        // Log user approval audit trail
        $this->kula_ai_model->log_interaction(
            "User Approved Import: " . ($import_payload['vendor_name'] ?? 'Document Invoice'),
            array('approve_import'),
            'import_approval',
            'success',
            'approved'
        );

        echo json_encode(array(
            'status'  => true,
            'message' => 'Import records successfully approved and submitted to KulaCRM transaction queue.'
        ));
    }

    /**
     * Determine tools to run based on user prompt keywords
     */
    protected function determine_required_tools($prompt) {
        $p = strtolower($prompt);
        $tools = array();

        if (preg_match('/(batch|mortality|death|died|shed|b-104)/i', $p)) {
            $tools[] = 'get_batch_summary';
            $tools[] = 'get_batch_mortality';
        }
        if (preg_match('/(feed|food|stock|run out|consume|inventory|waste)/i', $p)) {
            $tools[] = 'get_inventory_forecast_data';
        }
        if (preg_match('/(vaccine|vaccination|due|overdue|dose)/i', $p)) {
            $tools[] = 'get_upcoming_vaccinations';
        }
        if (preg_match('/(spend|spent|expense|money|cost|financial|revenue|profit|income)/i', $p)) {
            $tools[] = 'get_financial_summary';
            $tools[] = 'get_expenses';
        }
        if (preg_match('/(customer|client|owe us|receivable|debtor)/i', $p)) {
            $tools[] = 'get_client_balances';
        }
        if (preg_match('/(supplier|vendor|we owe|payable|creditor)/i', $p)) {
            $tools[] = 'get_supplier_balances';
        }

        if (empty($tools)) {
            $tools[] = 'get_farm_summary';
        }

        return array_unique($tools);
    }

    /**
     * Document Ingestion — Step 1: Upload & Extract
     * Accepts a PDF or image file, extracts structured livestock data using Gemini AI
     * and returns a preview JSON for the user to review before saving.
     */
    public function upload_document() {
        header('Content-Type: application/json');

        if (empty($_FILES['document']['tmp_name'])) {
            echo json_encode(array('status' => false, 'error' => 'No file uploaded. Please attach a PDF or image.'));
            return;
        }

        $file = $_FILES['document'];
        $mime = $file['type'] ?? mime_content_type($file['tmp_name']);
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        $allowed = array('pdf', 'jpg', 'jpeg', 'png', 'webp');
        if (!in_array($ext, $allowed)) {
            echo json_encode(array('status' => false, 'error' => 'Only PDF, JPG, PNG, and WebP files are supported.'));
            return;
        }

        // Check file size (max 10MB)
        if ($file['size'] > 10 * 1024 * 1024) {
            echo json_encode(array('status' => false, 'error' => 'File size must be under 10MB.'));
            return;
        }

        // Load Gemini API key from DB or config
        $api_key = '';
        $model   = 'gemini-flash-latest';
        if (isset($this->db) && $this->db->table_exists('ai_global_settings')) {
            $db_cfg = $this->db->get_where('ai_global_settings', array('id' => 1))->row();
            if ($db_cfg) {
                $api_key = $db_cfg->api_key ?? '';
                $model   = $db_cfg->model_name ?? $model;
            }
        }

        if (empty($api_key)) {
            $this->config->load('kula_ai', TRUE, TRUE);
            $cfg = $this->config->item('kula_ai') ?: array();
            $providers = $cfg['kula_ai_providers'] ?? array();
            $api_key = $providers['gemini']['api_key'] ?? '';
        }

        if (empty($api_key)) {
            echo json_encode(array('status' => false, 'error' => 'Gemini API key not configured. Please set it in Super Admin → AI Engine Settings.'));
            return;
        }

        $tmp_path = $file['tmp_name'];
        $gemini_result = array();

        if ($ext === 'pdf') {
            // Extract text from PDF first
            $extracted_text = $this->ai_ingestion_service->extract_pdf_text($tmp_path);

            // Pass to Gemini text API
            $gemini_result = $this->ai_ingestion_service->extract_from_text_via_gemini($api_key, $model, $extracted_text);
        } else {
            // Image — use Gemini Vision multimodal
            $allowed_mimes = array(
                'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png', 'webp' => 'image/webp'
            );
            $img_mime   = $allowed_mimes[$ext] ?? 'image/jpeg';
            $base64_img = $this->ai_ingestion_service->image_to_base64($tmp_path, $img_mime);

            if (!$base64_img) {
                echo json_encode(array('status' => false, 'error' => 'Failed to read the uploaded image.'));
                return;
            }

            $gemini_result = $this->ai_ingestion_service->extract_from_image_via_gemini($api_key, $model, $base64_img, $img_mime);
        }

        if (!$gemini_result['status']) {
            echo json_encode(array('status' => false, 'error' => $gemini_result['error'] ?? 'AI extraction failed.'));
            return;
        }

        // Parse the structured JSON from Gemini's response
        $parsed = $this->ai_ingestion_service->parse_extracted_json($gemini_result['text']);

        if (!$parsed['status']) {
            echo json_encode(array('status' => false, 'error' => $parsed['error'] ?? 'Could not parse structured data.', 'raw' => $gemini_result['text']));
            return;
        }

        // Count totals for UI display
        $total_records = count($parsed['sales'])
            + count($parsed['purchases'])
            + count($parsed['deaths'])
            + count($parsed['vaccinations']);

        // Log the interaction
        $this->kula_ai_model->log_interaction(
            'Document ingestion: ' . ($file['name'] ?? 'uploaded file'),
            array('document_upload', 'gemini_vision'),
            'document_ingestion',
            'success'
        );

        echo json_encode(array(
            'status'        => true,
            'file_name'     => $file['name'],
            'total_records' => $total_records,
            'sales'         => $parsed['sales'],
            'purchases'     => $parsed['purchases'],
            'deaths'        => $parsed['deaths'],
            'vaccinations'  => $parsed['vaccinations'],
        ));
    }

    /**
     * Document Ingestion — Step 2: Confirm & Save
     * Receives the previewed extracted data from the client and writes to KulaCRM DB.
     */
    public function confirm_import() {
        header('Content-Type: application/json');

        $raw   = $this->input->post('data');
        $types = $this->input->post('types') ?: array('sales', 'purchases', 'deaths', 'vaccinations');

        if (empty($raw)) {
            echo json_encode(array('status' => false, 'error' => 'No data submitted for import.'));
            return;
        }

        $data = json_decode($raw, true);
        if (!is_array($data)) {
            echo json_encode(array('status' => false, 'error' => 'Invalid data payload.'));
            return;
        }

        $results = array();

        if (in_array('deaths', $types) && !empty($data['deaths'])) {
            $results['deaths'] = $this->ai_ingestion_service->save_deaths($data['deaths']);
        }
        if (in_array('sales', $types) && !empty($data['sales'])) {
            $results['sales'] = $this->ai_ingestion_service->save_sales($data['sales']);
        }
        if (in_array('purchases', $types) && !empty($data['purchases'])) {
            $results['purchases'] = $this->ai_ingestion_service->save_purchases($data['purchases']);
        }
        if (in_array('vaccinations', $types) && !empty($data['vaccinations'])) {
            $results['vaccinations'] = $this->ai_ingestion_service->save_vaccinations($data['vaccinations']);
        }

        $total_saved = array_sum(array_column($results, 'saved'));

        $this->kula_ai_model->log_interaction(
            'Document ingestion confirmed: ' . $total_saved . ' records saved.',
            array('confirm_import'),
            'document_import_confirm',
            'success'
        );

        echo json_encode(array(
            'status'      => true,
            'total_saved' => $total_saved,
            'results'     => $results
        ));
    }

    /* ==========================================================================
       KULAAI VISION — LIVESTOCK VISION, IDENTIFICATION & SMART COUNTING
       ========================================================================== */

    /**
     * KulaAI Vision Camera Interface (Mobile-First View)
     * Route: /kula_ai/vision
     */
    public function vision() {
        if (method_exists($this, 'check_permission')) {
            $this->check_permission('livestock.view');
        }

        $tenant_id = $this->tenant_id ?: ($this->session->userdata('tenant_id') ?: 1);

        $data = array();
        $data['settings'] = $this->db->get_where('settings', array('id' => 1))->row();

        // Fetch active tenant sheds
        $this->db->where('sh_status', 1);
        if ($this->db->field_exists('tenant_id', 'shed')) {
            $this->db->where('tenant_id', $tenant_id);
        }
        $this->db->order_by('sh_no', 'ASC');
        $data['sheds'] = $this->db->get('shed')->result();

        // Fetch active counting history for quick review drawer
        $data['history'] = $this->ai_vision_service->get_counting_history(10);

        $this->load->view('home/dashboard', $data);
        $this->load->view('kula_ai/ai_vision_camera', $data);
        $this->load->view('home/footer', $data);
    }

    /**
     * KulaAI Vision History & Reconciliation View
     * Route: /kula_ai/vision_history
     */
    public function vision_history() {
        if (method_exists($this, 'check_permission')) {
            $this->check_permission('livestock.view');
        }

        $data = array();
        $data['settings'] = $this->db->get_where('settings', array('id' => 1))->row();
        $data['sessions'] = $this->ai_vision_service->get_counting_history(50);

        $this->load->view('home/dashboard', $data);
        $this->load->view('kula_ai/ai_vision_history', $data);
        $this->load->view('home/footer', $data);
    }

    /**
     * AJAX API: Get Batches for Selected Shed
     */
    public function get_shed_batches() {
        header('Content-Type: application/json');
        $shed_id = (int)($this->input->get('shed_id') ?: $this->input->post('shed_id'));

        if (empty($shed_id)) {
            echo json_encode(array('status' => false, 'batches' => array()));
            return;
        }

        $tenant_id = $this->tenant_id ?: ($this->session->userdata('tenant_id') ?: 1);

        // Security check: ensure shed belongs to active tenant
        $shed = $this->db->get_where('shed', array('sh_id' => $shed_id, 'tenant_id' => $tenant_id))->row();
        if (!$shed && $this->db->field_exists('tenant_id', 'shed')) {
            echo json_encode(array('status' => false, 'error' => 'Unauthorized shed access.', 'batches' => array()));
            return;
        }

        $this->db->where('lshs_sh_id', $shed_id);
        $this->db->where('lshs_status', 1);
        if ($this->db->field_exists('tenant_id', 'live_assigned_shed_summary')) {
            $this->db->where('tenant_id', $tenant_id);
        }
        $this->db->order_by('lshs_batch_id', 'DESC');
        $batches = $this->db->get('live_assigned_shed_summary')->result();

        // Calculate expected count preview
        $expected = $this->ai_vision_service->get_expected_livestock_count($shed_id);

        echo json_encode(array(
            'status'            => true,
            'batches'           => $batches,
            'expected_preview'  => $expected['expected']
        ));
    }

    /**
     * AJAX API: Start New Vision Counting Session
     */
    public function start_vision_session() {
        header('Content-Type: application/json');

        $shed_id  = (int)$this->input->post('shed_id');
        $batch_id = $this->input->post('batch_id') ? (int)$this->input->post('batch_id') : null;
        $notes    = trim($this->input->post('notes') ?? '');

        if (empty($shed_id)) {
            echo json_encode(array('status' => false, 'error' => 'Please select a Shed to start counting session.'));
            return;
        }

        $tenant_id = $this->tenant_id ?: ($this->session->userdata('tenant_id') ?: 1);

        // Security check: ensure shed belongs to tenant
        $shed = $this->db->get_where('shed', array('sh_id' => $shed_id, 'tenant_id' => $tenant_id))->row();
        if (!$shed && $this->db->field_exists('tenant_id', 'shed')) {
            echo json_encode(array('status' => false, 'error' => 'Unauthorized shed access.'));
            return;
        }

        $result = $this->ai_vision_service->start_session($shed_id, $batch_id, $notes);

        $this->kula_ai_model->log_interaction(
            "Started AI Vision session {$result['session_code']} for Shed #{$shed_id}",
            array('ai_vision_camera', 'start_session'),
            'vision_counting',
            'success'
        );

        echo json_encode($result);
    }

    /**
     * AJAX API: Process Frame Base64 via Gemini Vision
     */
    public function process_vision_frame() {
        header('Content-Type: application/json');

        $session_id   = (int)$this->input->post('session_id');
        $frame_base64 = $this->input->post('frame');
        $mime_type    = $this->input->post('mime_type') ?? 'image/jpeg';
        $tracking_id  = trim($this->input->post('tracking_id') ?? '');
        $tracking_col = trim($this->input->post('tracking_color') ?? '');

        if (empty($session_id) || empty($frame_base64)) {
            echo json_encode(array('status' => false, 'error' => 'Missing session ID or frame data payload.'));
            return;
        }

        $result = $this->ai_vision_service->analyze_frame($session_id, $frame_base64, $mime_type, array(
            'tracking_id'    => $tracking_id,
            'tracking_color' => $tracking_col
        ));
        echo json_encode($result);
    }

    /**
     * AJAX API: Human Confirmation of Candidate Match
     */
    public function confirm_vision_match() {
        header('Content-Type: application/json');

        $session_id   = (int)$this->input->post('session_id');
        $record_id    = (int)$this->input->post('record_id');
        $livestock_id = (int)$this->input->post('livestock_id');
        $tag_number   = trim($this->input->post('tag_number') ?? '');

        if (empty($session_id) || empty($record_id) || empty($livestock_id)) {
            echo json_encode(array('status' => false, 'error' => 'Missing required parameter details.'));
            return;
        }

        $result = $this->ai_vision_service->confirm_match($session_id, $record_id, $livestock_id, $tag_number);
        echo json_encode($result);
    }

    /**
     * AJAX API: Reject Candidate Match
     */
    public function reject_vision_match() {
        header('Content-Type: application/json');

        $session_id = (int)$this->input->post('session_id');
        $record_id  = (int)$this->input->post('record_id');

        if (empty($session_id) || empty($record_id)) {
            echo json_encode(array('status' => false, 'error' => 'Missing required parameter details.'));
            return;
        }

        $result = $this->ai_vision_service->reject_match($session_id, $record_id);
        echo json_encode($result);
    }

    /**
     * AJAX API: Complete Vision Session & Get Reconciliation Analysis
     */
    public function complete_vision_session() {
        header('Content-Type: application/json');

        $session_id = (int)$this->input->post('session_id');
        $notes      = trim($this->input->post('notes') ?? '');

        if (empty($session_id)) {
            echo json_encode(array('status' => false, 'error' => 'Missing session ID.'));
            return;
        }

        $result = $this->ai_vision_service->complete_session($session_id, $notes);

        $this->kula_ai_model->log_interaction(
            "Completed AI Vision session #{$session_id}",
            array('ai_vision_camera', 'complete_session'),
            'vision_reconciliation',
            'success'
        );

        echo json_encode($result);
    }

    /**
     * AJAX API: Get Session Reconciliation Details
     */
    public function get_session_details() {
        header('Content-Type: application/json');

        $session_id = (int)($this->input->get('session_id') ?: $this->input->post('session_id'));
        if (empty($session_id)) {
            echo json_encode(array('status' => false, 'error' => 'Missing session ID.'));
            return;
        }

        $reconciliation = $this->ai_vision_service->reconcile_session($session_id);
        if (!$reconciliation) {
            echo json_encode(array('status' => false, 'error' => 'Session not found.'));
            return;
        }

        echo json_encode(array(
            'status'         => true,
            'reconciliation' => $reconciliation
        ));
    }

    /* ==========================================================================
       FIELD ACCURACY & ANIMAL IDENTITY VALIDATION MODE ENDPOINTS
       ========================================================================== */

    /**
     * Field Accuracy & Accuracy Dashboard Page
     * Route: /kula_ai/validation or /kula_ai/accuracy_dashboard
     */
    public function validation() {
        if (method_exists($this, 'check_permission')) {
            $this->check_permission('livestock.view');
        }

        $data = array();
        $data['settings']  = $this->db->get_where('settings', array('id' => 1))->row();
        $data['analytics'] = $this->ai_vision_service->get_validation_analytics();

        $this->load->view('home/dashboard', $data);
        $this->load->view('kula_ai/ai_vision_validation', $data);
        $this->load->view('home/footer', $data);
    }

    public function accuracy_dashboard() {
        $this->validation();
    }

    /**
     * AJAX API: Start Validation Session (VS-YYYYMMDD-XXXX)
     */
    public function start_validation_session() {
        header('Content-Type: application/json');

        $shed_id  = (int)$this->input->post('shed_id');
        $batch_id = $this->input->post('batch_id') ? (int)$this->input->post('batch_id') : null;
        $notes    = trim($this->input->post('notes') ?? '');

        if (empty($shed_id)) {
            echo json_encode(array('status' => false, 'error' => 'Please select a Shed for validation testing.'));
            return;
        }

        $result = $this->ai_vision_service->start_validation_session($shed_id, $batch_id, $notes);
        echo json_encode($result);
    }

    /**
     * AJAX API: Record Ground Truth Validation Attempt
     */
    public function record_validation_attempt() {
        header('Content-Type: application/json');

        $session_id  = (int)$this->input->post('session_id');
        $actual_id   = (int)$this->input->post('actual_livestock_id');
        $actual_tag  = trim($this->input->post('actual_tag_number') ?? '');
        $ai_raw      = $this->input->post('ai_result_json');

        if (empty($session_id)) {
            echo json_encode(array('status' => false, 'error' => 'Missing validation session ID.'));
            return;
        }

        $ai_result = is_array($ai_raw) ? $ai_raw : json_decode($ai_raw, true);
        if (!is_array($ai_result)) {
            $ai_result = array();
        }

        $metadata = array(
            'camera_angle'   => $this->input->post('camera_angle') ?? 'FRONT',
            'lighting'       => $this->input->post('lighting') ?? 'NORMAL_DAYLIGHT',
            'occlusion'      => $this->input->post('occlusion') ?? 'NONE',
            'tag_visibility' => $this->input->post('tag_visibility') ?? 'VISIBLE',
            'is_demo_data'   => (int)($this->input->post('is_demo_data') ?? 0)
        );

        $result = $this->ai_vision_service->record_validation_attempt($session_id, $actual_id, $actual_tag, $ai_result, $metadata);
        echo json_encode($result);
    }

    /**
     * AJAX API: Get Real-time Field Accuracy Analytics Data
     */
    public function get_validation_analytics() {
        header('Content-Type: application/json');
        $analytics = $this->ai_vision_service->get_validation_analytics();
        echo json_encode(array('status' => true, 'analytics' => $analytics));
    }
}


