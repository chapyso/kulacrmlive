<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ai_provider Library
 * Multi-provider LLM Abstraction Layer (Gemini, OpenAI, Groq, Ollama)
 * Operates purely server-side. API keys are NEVER sent to client browsers.
 */
class Ai_provider {

    protected $CI;
    protected $config;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->config->load('kula_ai', TRUE, TRUE);
        $this->config = $this->CI->config->item('kula_ai') ?: array();
    }

    /**
     * Send prompt with structured context to active LLM Provider
     *
     * @param string $system_prompt  Domain & instructions
     * @param string $user_prompt    User question / request
     * @param array  $context_data   Structured JSON payload from safe tools
     * @return array                 ['status' => bool, 'response' => string, 'provider' => string]
     */
    public function generate($system_prompt, $user_prompt, $context_data = array()) {
        $default_provider = $this->config['kula_ai_default_provider'] ?? 'gemini';
        $providers = $this->config['kula_ai_providers'] ?? array();
        
        $provider_cfg = $providers[$default_provider] ?? null;

        // If active provider has no API key, check if fallback key is in environment or DB settings
        $api_key = $provider_cfg['api_key'] ?? '';

        // Fetch DB global settings configured by Super Admin
        if (isset($this->CI->db) && $this->CI->db->table_exists('ai_global_settings')) {
            $db_settings = $this->CI->db->get_where('ai_global_settings', array('id' => 1))->row();
            if ($db_settings) {
                if (!empty($db_settings->default_provider)) {
                    $default_provider = $db_settings->default_provider;
                }
                if (!empty($db_settings->api_key)) {
                    $api_key = $db_settings->api_key;
                }
                if (!empty($db_settings->model_name) && isset($providers[$default_provider])) {
                    $provider_cfg = $providers[$default_provider];
                    $provider_cfg['model'] = $db_settings->model_name;
                }
            }
        }

        if (empty($api_key) && $default_provider !== 'ollama') {
            // Attempt to look for environment variables
            if ($default_provider === 'gemini') {
                $api_key = getenv('GEMINI_API_KEY') ?: getenv('PALM_API_KEY') ?: '';
            } elseif ($default_provider === 'openai') {
                $api_key = getenv('OPENAI_API_KEY') ?: '';
            } elseif ($default_provider === 'groq') {
                $api_key = getenv('GROQ_API_KEY') ?: '';
            }
        }

        // If no API key is available, use standard rule-based natural language generator (graceful offline mode)
        if (empty($api_key) && $default_provider !== 'ollama') {
            return array(
                'status'   => true,
                'provider' => 'KulaAI Rule Engine (Offline)',
                'response' => $this->generate_offline_response($user_prompt, $context_data)
            );
        }

        switch ($default_provider) {
            case 'gemini':
                return $this->call_gemini($api_key, $provider_cfg, $system_prompt, $user_prompt, $context_data);
            case 'openai':
            case 'groq':
                return $this->call_openai_compatible($api_key, $provider_cfg, $system_prompt, $user_prompt, $context_data, $default_provider);
            case 'ollama':
                return $this->call_ollama($provider_cfg, $system_prompt, $user_prompt, $context_data);
            default:
                return array(
                    'status'   => true,
                    'provider' => 'KulaAI Rule Engine',
                    'response' => $this->generate_offline_response($user_prompt, $context_data)
                );
        }
    }

    /**
     * Send Image Frame + Context to Gemini Multimodal API for Livestock Vision Analysis
     * Returns structured JSON object with animal detection, ear tags, visual features & confidence.
     *
     * @param string $system_prompt  Vision system instructions & schema
     * @param string $image_base64   Base64 encoded frame image (JPEG / WebP / PNG)
     * @param string $mime_type      Image MIME type (image/jpeg, image/webp, image/png)
     * @param array  $context_data   Livestock context (shed animals, expected tags, active session)
     * @return array                 ['status' => bool, 'response' => string, 'provider' => string]
     */
    public function generate_vision($system_prompt, $image_base64, $mime_type = 'image/jpeg', $context_data = array()) {
        $default_provider = 'gemini';
        $providers = $this->config['kula_ai_providers'] ?? array();
        $provider_cfg = $providers[$default_provider] ?? array('model' => 'gemini-1.5-flash');

        $api_key = $provider_cfg['api_key'] ?? '';

        if (isset($this->CI->db) && $this->CI->db->table_exists('ai_global_settings')) {
            $db_settings = $this->CI->db->get_where('ai_global_settings', array('id' => 1))->row();
            if ($db_settings) {
                if (!empty($db_settings->api_key)) {
                    $api_key = $db_settings->api_key;
                }
                if (!empty($db_settings->model_name)) {
                    $provider_cfg['model'] = $db_settings->model_name;
                }
            }
        }

        if (empty($api_key)) {
            $api_key = getenv('GEMINI_API_KEY') ?: getenv('PALM_API_KEY') ?: '';
        }

        // Clean base64 string if data URL prefix exists
        if (strpos($image_base64, 'data:') === 0) {
            if (preg_match('/^data:(image\/[a-zA-Z]+);base64,/', $image_base64, $m)) {
                $mime_type = $m[1];
            }
            $image_base64 = preg_replace('/^data:image\/[a-zA-Z]+;base64,/', '', $image_base64);
        }

        if (empty($api_key)) {
            return array(
                'status'   => false,
                'provider' => 'KulaAI Vision',
                'response' => 'Gemini API Key is not configured. Please set your API key in Super Admin -> AI Engine Settings.'
            );
        }

        $model = !empty($provider_cfg['model']) ? $provider_cfg['model'] : 'gemini-1.5-flash';
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . urlencode($api_key);

        $context_str = !empty($context_data) ? "\n\nKULACRM EXPECTED LIVESTOCK REFORMATTED CONTEXT:\n" . json_encode($context_data, JSON_PRETTY_PRINT) : "";
        $prompt_text = $system_prompt . $context_str;

        $payload = array(
            'contents' => array(
                array(
                    'parts' => array(
                        array('text' => $prompt_text),
                        array(
                            'inlineData' => array(
                                'mimeType' => $mime_type,
                                'data'     => $image_base64
                            )
                        )
                    )
                )
            ),
            'generationConfig' => array(
                'temperature'       => 0.1, // High precision, deterministic for OCR & visual detection
                'maxOutputTokens'   => 1500,
                'responseMimeType'  => 'application/json'
            )
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 25);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return array('status' => false, 'response' => "Gemini Vision Connection error: " . $err, 'provider' => 'gemini');
        }

        $res_json = json_decode($response, true);
        if (isset($res_json['candidates'][0]['content']['parts'][0]['text'])) {
            $raw_text = trim($res_json['candidates'][0]['content']['parts'][0]['text']);
            return array(
                'status'   => true,
                'provider' => 'gemini (' . $model . ')',
                'response' => $raw_text
            );
        }

        $error_msg = $res_json['error']['message'] ?? 'Unable to process vision frame with Gemini API.';
        return array('status' => false, 'response' => "AI Vision Notice: " . $error_msg, 'provider' => 'gemini');
    }

    /**
     * Call Google Gemini API
     */
    protected function call_gemini($api_key, $config, $system_prompt, $user_prompt, $context_data) {
        $model = $config['model'] ?? 'gemini-1.5-flash';
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . urlencode($api_key);

        $context_str = !empty($context_data) ? "\n\nLIVE KULACRM DATA CONTEXT:\n" . json_encode($context_data, JSON_PRETTY_PRINT) : "";
        $combined_prompt = $system_prompt . "\n\n" . $user_prompt . $context_str;

        $payload = array(
            'contents' => array(
                array(
                    'parts' => array(
                        array('text' => $combined_prompt)
                    )
                )
            ),
            'generationConfig' => array(
                'temperature'     => (float)($this->config['kula_ai_temperature'] ?? 0.2),
                'maxOutputTokens' => (int)($this->config['kula_ai_max_tokens'] ?? 2000)
            )
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return array('status' => false, 'response' => "AI Connection error: " . $err, 'provider' => 'gemini');
        }

        $res_json = json_decode($response, true);
        if (isset($res_json['candidates'][0]['content']['parts'][0]['text'])) {
            return array(
                'status'   => true,
                'provider' => 'gemini (' . $model . ')',
                'response' => trim($res_json['candidates'][0]['content']['parts'][0]['text'])
            );
        }

        // Handle error message from Gemini API
        $error_msg = $res_json['error']['message'] ?? 'Unable to process request with Gemini API.';
        return array('status' => false, 'response' => "AI Provider Notice: " . $error_msg, 'provider' => 'gemini');
    }

    /**
     * Call OpenAI / Groq Compatible Chat API
     */
    protected function call_openai_compatible($api_key, $config, $system_prompt, $user_prompt, $context_data, $provider_name) {
        $endpoint = $config['endpoint'] ?? 'https://api.openai.com/v1/chat/completions';
        $model = $config['model'] ?? ($provider_name === 'groq' ? 'llama-3.3-70b-versatile' : 'gpt-4o-mini');

        $context_str = !empty($context_data) ? "\n\nLIVE KULACRM DATA CONTEXT:\n" . json_encode($context_data, JSON_PRETTY_PRINT) : "";

        $messages = array(
            array('role' => 'system', 'content' => $system_prompt),
            array('role' => 'user', 'content' => $user_prompt . $context_str)
        );

        $payload = array(
            'model'       => $model,
            'messages'    => $messages,
            'temperature' => (float)($this->config['kula_ai_temperature'] ?? 0.2),
            'max_tokens'  => (int)($this->config['kula_ai_max_tokens'] ?? 2000)
        );

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return array('status' => false, 'response' => "AI Connection error: " . $err, 'provider' => $provider_name);
        }

        $res_json = json_decode($response, true);
        if (isset($res_json['choices'][0]['message']['content'])) {
            return array(
                'status'   => true,
                'provider' => $provider_name . ' (' . $model . ')',
                'response' => trim($res_json['choices'][0]['message']['content'])
            );
        }

        $error_msg = $res_json['error']['message'] ?? 'API response error.';
        return array('status' => false, 'response' => "AI Provider Notice: " . $error_msg, 'provider' => $provider_name);
    }

    /**
     * Call Local Ollama instance
     */
    protected function call_ollama($config, $system_prompt, $user_prompt, $context_data) {
        $endpoint = $config['base_url'] ?? 'http://localhost:11434/api/generate';
        $model = $config['model'] ?? 'llama3';

        $context_str = !empty($context_data) ? "\n\nLIVE KULACRM DATA CONTEXT:\n" . json_encode($context_data, JSON_PRETTY_PRINT) : "";
        $prompt = $system_prompt . "\n\n" . $user_prompt . $context_str;

        $payload = array(
            'model'  => $model,
            'prompt' => $prompt,
            'stream' => false
        );

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return array('status' => false, 'response' => "Ollama service unavailable: " . $err, 'provider' => 'ollama');
        }

        $res_json = json_decode($response, true);
        if (isset($res_json['response'])) {
            return array(
                'status'   => true,
                'provider' => 'ollama (' . $model . ')',
                'response' => trim($res_json['response'])
            );
        }

        return array('status' => false, 'response' => 'Ollama returned invalid response payload.', 'provider' => 'ollama');
    }

    /**
     * Rule-based natural language generator (Graceful fallback when API key is offline)
     */
    public function generate_offline_response($user_prompt, $context_data) {
        $output = "Based on current live KulaCRM data:\n\n";

        if (empty($context_data)) {
            return "I couldn't retrieve specific records for this request from KulaCRM.";
        }

        if (isset($context_data['farm_summary'])) {
            $fs = $context_data['farm_summary'];
            $output .= "### 📊 Farm Overview\n";
            $output .= "- **Total Active Livestock:** " . number_format($fs['total_livestock'] ?? 0) . " animals\n";
            $output .= "- **Active Sheds:** " . ($fs['total_sheds'] ?? 0) . "\n";
            $output .= "- **Active Batches:** " . ($fs['total_batches'] ?? 0) . "\n";
            if (isset($fs['total_sales'])) {
                $output .= "- **Total Sales Value:** $" . number_format($fs['total_sales'], 2) . "\n";
            }
            $output .= "\n";
        }

        if (isset($context_data['batch_summary'])) {
            $bs = $context_data['batch_summary'];
            $output .= "### 📦 Batch Performance Details\n";
            if (is_array($bs)) {
                foreach ($bs as $b) {
                    $b_name = $b['shed_name'] ?? $b['batch_id'] ?? 'Batch';
                    $total = $b['total_quantity'] ?? $b['quantity'] ?? 0;
                    $deaths = $b['death_quantity'] ?? 0;
                    $rate = ($total > 0) ? round(($deaths / $total) * 100, 2) : 0;
                    $output .= "- **{$b_name}**: Initial: {$total} | Current: " . ($total - $deaths) . " | Mortality: {$deaths} ({$rate}%)\n";
                }
            }
            $output .= "\n";
        }

        if (isset($context_data['financial_summary'])) {
            $fin = $context_data['financial_summary'];
            $output .= "### 💰 Financial Overview\n";
            $output .= "- **Total Revenue:** $" . number_format($fin['total_income'] ?? $fin['revenue'] ?? 0, 2) . "\n";
            $output .= "- **Total Expenses:** $" . number_format($fin['total_expenses'] ?? $fin['expenses'] ?? 0, 2) . "\n";
            $output .= "- **Net Balance:** $" . number_format(($fin['total_income'] ?? 0) - ($fin['total_expenses'] ?? 0), 2) . "\n";
            $output .= "\n";
        }

        if (isset($context_data['receivables'])) {
            $rec = $context_data['receivables'];
            $output .= "### 🧾 Outstanding Client Balances\n";
            if (is_array($rec)) {
                foreach ($rec as $c) {
                    $name = $c['client_name'] ?? $c['name'] ?? 'Client';
                    $due = $c['due_amount'] ?? $c['balance'] ?? 0;
                    $output .= "- **{$name}**: $" . number_format($due, 2) . " due\n";
                }
            }
            $output .= "\n";
        }

        if (isset($context_data['inventory_forecast'])) {
            $inv = $context_data['inventory_forecast'];
            $output .= "### 🌾 Feed & Inventory Alert\n";
            if (is_array($inv)) {
                foreach ($inv as $i) {
                    $item = $i['item_name'] ?? 'Item';
                    $days = $i['estimated_days_left'] ?? 'N/A';
                    $output .= "- **{$item}**: Current Stock: " . ($i['current_stock'] ?? 0) . " | Est. Days Remaining: {$days} days\n";
                }
            }
            $output .= "\n";
        }

        $output .= "*Source: Current KulaCRM Live Database Records*";
        return $output;
    }
}
