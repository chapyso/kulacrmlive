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
        $this->CI = get_instance();
        $this->CI->config->load('kula_ai', TRUE, TRUE);
        $this->config = $this->CI->config->item('kula_ai') ?: array();
    }

    /**
     * Send prompt with structured context to active LLM Provider
     *
     * @param string $system_prompt  Domain & instructions
     * @param string $user_prompt    User question / request
     * @param array  $context_data   Structured JSON payload from safe tools
     * @param array  $chat_history   Recent conversation message history
     * @param array  $intent_info    Intent classification payload
     * @return array                 ['status' => bool, 'response' => string, 'provider' => string]
     */
    public function generate($system_prompt, $user_prompt, $context_data = array(), $chat_history = array(), $intent_info = array()) {
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
                'response' => $this->generate_offline_response($user_prompt, $context_data, $intent_info)
            );
        }

        switch ($default_provider) {
            case 'gemini':
                return $this->call_gemini($api_key, $provider_cfg, $system_prompt, $user_prompt, $context_data, $chat_history);
            case 'openai':
            case 'groq':
                return $this->call_openai_compatible($api_key, $provider_cfg, $system_prompt, $user_prompt, $context_data, $default_provider, $chat_history);
            case 'ollama':
                return $this->call_ollama($provider_cfg, $system_prompt, $user_prompt, $context_data, $chat_history);
            default:
                return array(
                    'status'   => true,
                    'provider' => 'KulaAI Rule Engine',
                    'response' => $this->generate_offline_response($user_prompt, $context_data, $intent_info)
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
    protected function call_gemini($api_key, $config, $system_prompt, $user_prompt, $context_data, $chat_history = array()) {
        $model = $config['model'] ?? 'gemini-1.5-flash';
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . urlencode($api_key);

        $contents = array();
        
        // Append previous chat history turns if provided
        if (!empty($chat_history) && is_array($chat_history)) {
            foreach (array_slice($chat_history, -8) as $msg) {
                $role = (isset($msg['role']) && $msg['role'] === 'user') ? 'user' : 'model';
                $text = is_array($msg) ? ($msg['content'] ?? ($msg['prompt'] ?? '')) : (string)$msg;
                if (!empty($text)) {
                    $contents[] = array(
                        'role'  => $role,
                        'parts' => array(array('text' => $text))
                    );
                }
            }
        }

        $context_str = !empty($context_data) ? "\n\nLIVE KULACRM DATA CONTEXT:\n" . json_encode($context_data, JSON_PRETTY_PRINT) : "";
        $current_text = $user_prompt . $context_str;

        // If no history, include system prompt in the first turn
        if (empty($contents)) {
            $contents[] = array(
                'role'  => 'user',
                'parts' => array(array('text' => $system_prompt . "\n\n" . $current_text))
            );
        } else {
            // Append current user turn
            $contents[] = array(
                'role'  => 'user',
                'parts' => array(array('text' => $current_text))
            );
        }

        $payload = array(
            'contents' => $contents,
            'systemInstruction' => array(
                'parts' => array(array('text' => $system_prompt))
            ),
            'generationConfig' => array(
                'temperature'     => (float)($this->config['kula_ai_temperature'] ?? 0.3),
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

        $error_msg = $res_json['error']['message'] ?? 'Unable to process request with Gemini API.';
        return array('status' => false, 'response' => "AI Provider Notice: " . $error_msg, 'provider' => 'gemini');
    }

    /**
     * Call OpenAI / Groq Compatible Chat API
     */
    protected function call_openai_compatible($api_key, $config, $system_prompt, $user_prompt, $context_data, $provider_name, $chat_history = array()) {
        $endpoint = $config['endpoint'] ?? 'https://api.openai.com/v1/chat/completions';
        $model = $config['model'] ?? ($provider_name === 'groq' ? 'llama-3.3-70b-versatile' : 'gpt-4o-mini');

        $context_str = !empty($context_data) ? "\n\nLIVE KULACRM DATA CONTEXT:\n" . json_encode($context_data, JSON_PRETTY_PRINT) : "";

        $messages = array(
            array('role' => 'system', 'content' => $system_prompt)
        );

        if (!empty($chat_history) && is_array($chat_history)) {
            foreach (array_slice($chat_history, -8) as $msg) {
                $role = (isset($msg['role']) && $msg['role'] === 'user') ? 'user' : 'assistant';
                $text = is_array($msg) ? ($msg['content'] ?? ($msg['prompt'] ?? '')) : (string)$msg;
                if (!empty($text)) {
                    $messages[] = array('role' => $role, 'content' => $text);
                }
            }
        }

        $messages[] = array('role' => 'user', 'content' => $user_prompt . $context_str);

        $payload = array(
            'model'       => $model,
            'messages'    => $messages,
            'temperature' => (float)($this->config['kula_ai_temperature'] ?? 0.3),
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
    protected function call_ollama($config, $system_prompt, $user_prompt, $context_data, $chat_history = array()) {
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
     * Intent-Aware Rule-Based Natural Language Generator (Graceful offline mode)
     */
    public function generate_offline_response($user_prompt, $context_data = array(), $intent_info = array()) {
        $intent = $intent_info['intent'] ?? 'UNKNOWN';
        $p = strtolower(trim($user_prompt));
        $p_clean = trim(preg_replace('/[^a-z\s]/', '', $p));

        // 1. Greetings (Intent or Pattern match)
        if ($intent === 'GREETING' || in_array($p_clean, array('hey', 'hello', 'hi', 'hey there', 'hello there', 'hi there', 'good morning', 'good afternoon', 'good evening', 'greetings', 'habari', 'jambo', 'mambo', 'oli otya', 'gyebale', 'ki kati'))) {
            $greetings = array(
                "Hello! 👋 How can I assist you with your farm operations today?",
                "Hey there! What would you like to review in KulaCRM today?",
                "Good day! I'm ready to help with your livestock, feeds, vaccines, or financial records.",
                "Hi! How can I assist you with your farm today?"
            );
            return $greetings[array_rand($greetings)];
        }

        // 2. Casual Chat & Polite Remarks (Intent or Pattern match)
        if ($intent === 'CASUAL_CONVERSATION' || preg_match('/(how are you|how do you do|whats up|thank|thanks|bye|goodbye|who are you|your name|oli otya|asante|webale)/i', $p)) {
            if (preg_match('/(how are you|how do you do|whats up)/i', $p)) {
                return "I'm doing well, thank you! How can I assist you with your farm today?";
            }
            if (preg_match('/(thank|thanks|webale|asante)/i', $p)) {
                return "You're very welcome! Let me know if you need any other insights for your farm.";
            }
            if (preg_match('/(bye|goodbye|see you|kwaheri)/i', $p)) {
                return "Goodbye! Have a great and productive day on the farm. 👋";
            }
            if (preg_match('/(who are you|your name)/i', $p)) {
                return "I am **KulaAI**, your natural conversational AI assistant deeply integrated with KulaCRM!";
            }
            return "Got it! Let me know what you'd like to check next in KulaCRM.";
        }

        // 3. Business Plan & Comprehensive Strategic Planning Requests
        if ($intent === 'BUSINESS_PLAN' || preg_match('/(business plan|layer plan|broiler plan|financial projections|roi projection|write a plan|create a plan)/i', $p)) {
            return "### 📊 Comprehensive Business Plan & Financial Projections\n"
                . "**Enterprise:** 1,000 Layer Poultry Farm  \n"
                . "**Timeframe:** 18-Month Production Cycle  \n"
                . "**Currency:** UGX (Ugandan Shillings)\n\n"
                . "---\n\n"
                . "### 1. Executive Summary\n"
                . "This business plan outlines the setup, operational strategy, feed budgeting, egg yield forecasts, and financial projections for a commercial **1,000 Point-of-Lay (POL) Layer Poultry Unit**. The enterprise aims to supply fresh table eggs to local wholesale distributors, institutions, hotels, and retail markets.\n\n"
                . "---\n\n"
                . "### 2. Capital Expenditure (CAPEX) — Initial Setup Costs\n"
                . "| Investment Item | Quantity / Specs | Estimated Unit Cost (UGX) | Total Amount (UGX) |\n"
                . "| :--- | :--- | :--- | :--- |\n"
                . "| **Poultry Structure / Housing** | Deep Litter / Battery Cage (1,000 Birds) | Lump sum | 12,000,000 |\n"
                . "| **Point-of-Lay Pullets (18-20 Wks)** | 1,000 Pullets | 28,000 / bird | 28,000,000 |\n"
                . "| **Feeding & Watering Equipment** | Feeders, Drinkers, Nests | Lump sum | 2,500,000 |\n"
                . "| **Biosecurity & Water System** | Footbaths, Water Tank, Hose | Lump sum | 1,500,000 |\n"
                . "| **Contingency Reserve** | 5% Operational Buffer | — | 2,200,000 |\n"
                . "| **TOTAL INITIAL CAPEX** | — | — | **UGX 46,200,000** |\n\n"
                . "---\n\n"
                . "### 3. Operational Expenditure (OPEX) — Monthly Running Costs\n"
                . "* **Feed Budget:** 1,000 birds × 110g/day = 110 kg/day = 3,300 kg/month (66 bags @ UGX 110,000/50kg bag) $\\rightarrow$ **UGX 7,260,000 / month**.\n"
                . "* **Veterinary & Medication:** Vaccines, Layer Premix, Vitamins $\\rightarrow$ **UGX 600,000 / month**.\n"
                . "* **Labor & Utilities:** 1 Farm Attendant + Electricity/Water $\\rightarrow$ **UGX 750,000 / month**.\n"
                . "* **TOTAL MONTHLY OPEX:** $\\approx$ **UGX 8,610,000 / month**.\n\n"
                . "---\n\n"
                . "### 4. Revenue Projections (Egg Yield & Manure Sales)\n"
                . "* **Average Laying Rate:** 85% Peak Production (850 eggs/day = 28.3 Trays/day).\n"
                . "* **Monthly Egg Production:** 28.3 Trays/day × 30 days = **850 Trays / month**.\n"
                . "* **Egg Revenue:** 850 Trays @ UGX 10,500 / Tray = **UGX 8,925,000 / month**.\n"
                . "* **Poultry Manure Sales:** 60 Bags/month @ UGX 5,000/bag = **UGX 300,000 / month**.\n"
                . "* **Cull Layer Hen Sales (End of Cycle - Wk 72):** 950 Hens @ UGX 15,000/hen = **UGX 14,250,000**.\n"
                . "* **TOTAL MONTHLY REVENUE:** $\\approx$ **UGX 9,225,000 / month**.\n\n"
                . "---\n\n"
                . "### 5. Financial Projections & Return on Investment (ROI)\n"
                . "* **Monthly Net Operating Margin:** `Revenue (9,225,000) - OPEX (8,610,000)` = **UGX 615,000 / month**.\n"
                . "* **Cumulative Laying Cycle Net Profit (14 Months Laying):** `14 × 615,000 + Cull Sales (14,250,000)` = **UGX 22,860,000**.\n"
                . "* **Break-Even Point:** Reached around Month 14-16 of continuous production.\n"
                . "* **Estimated ROI:** **49.4% over 18-month cycle**.\n\n"
                . "---\n\n"
                . "### 6. Key Operational Risk & Mitigation Protocols\n"
                . "1. **Feed Quality Risk:** Formulate feed using verified premixes and high-protein soybean meal to maintain >82% lay rate.\n"
                . "2. **Disease Risk:** Implement mandatory Newcastle (LaSota), Gumboro, and Fowl Pox vaccination schedule recorded inside KulaCRM.\n"
                . "3. **Biosecurity:** Maintain strict footbaths, restrict farm visitors, and disinfect housing between batches.";
        }

        // 4. System Help & Guidance
        if ($intent === 'SYSTEM_HELP' || preg_match('/(help|what can you do|how to use|features)/i', $p)) {
            return "I can help you inspect and manage your farm operations in KulaCRM! Here are a few things you can ask me:\n\n"
                . "- 🐄 **Livestock & Inventory:** *'How many goats do I have?'* or *'Which animals are sick?'*\n"
                . "- 💉 **Health & Vaccines:** *'Which vaccinations are due this week?'* or *'Tell me about Newcastle disease'*\n"
                . "- 🌾 **Feed & Supplies:** *'Which food stock will run out first?'*\n"
                . "- 💰 **Finances & Debtors:** *'How much did we spend this month?'* or *'Which clients owe us money?'*\n"
                . "- 📊 **Analysis & Strategy:** *'Write a business plan for 1,000 layers'* or *'Why did mortality increase?'*\n\n"
                . "What would you like to start with?";
        }

        // 5. Educational & General Knowledge Questions
        if ($intent === 'GENERAL_QUESTION' || preg_match('/^(what is|explain|define|how to calculate)/i', $p)) {
            if (strpos($p, 'roi') !== false || strpos($p, 'return on investment') !== false) {
                return "**Return on Investment (ROI)** measures the profitability of a business venture relative to its total initial cost.\n\n"
                    . "Formula: `ROI (%) = (Net Profit / Total Initial CAPEX) × 100`\n\n"
                    . "*Example:* If a poultry house costs UGX 20,000,000 to set up and generates UGX 8,000,000 net profit over its first cycle, your ROI is `(8,000,000 / 20,000,000) × 100 = 40%`." ;
            }
            if (strpos($p, 'newcastle') !== false) {
                return "Newcastle disease is a highly contagious viral disease affecting poultry (chickens, turkeys, ducks). Symptoms include respiratory distress (gasping, coughing), nervous signs (twisted neck, paralysis), greenish diarrhea, and sudden mortality.\n\n"
                    . "💡 **Prevention:** Regular vaccination (LaSota / ND-HB1 strain) and strict farm biosecurity are the most effective controls.\n\n"
                    . "*If you suspect an outbreak on your farm, I can also check your recent vaccination and mortality records in KulaCRM.*";
            }
            if (strpos($p, 'profit') !== false || strpos($p, 'revenue') !== false) {
                return "**Revenue** is the total money collected from farm sales (e.g. egg sales, livestock sales).\n\n"
                    . "**Profit** is what remains after subtracting all operational expenses (feed, labor, medication, utilities) from your revenue.\n\n"
                    . "Formula: `Net Profit = Total Revenue - Total Expenses`\n\n"
                    . "*If you'd like, I can calculate your current net profit directly from your KulaCRM financial records.*";
            }
            if (strpos($p, 'inflation') !== false) {
                return "Inflation is the gradual increase in prices over time, which reduces purchasing power. In livestock farming, inflation typically increases feed ingredient costs (maize, soy, premix), medication prices, and transport expenses.";
            }
        }

        // 5. Farm Data Queries (Livestock, Feed, Vaccines, Mortality, Finance, Debtors)
        if (!empty($context_data)) {
            $output = "";

            // Extract context blocks by tool name or legacy alias
            $fs = $context_data['get_farm_summary'] ?? ($context_data['farm_summary'] ?? null);
            $bs = $context_data['get_batch_summary'] ?? ($context_data['batch_summary'] ?? null);
            $fin = $context_data['get_financial_summary'] ?? ($context_data['financial_summary'] ?? null);
            $clients = $context_data['get_client_balances'] ?? ($context_data['client_balances'] ?? null);
            $vacs = $context_data['get_upcoming_vaccinations'] ?? ($context_data['upcoming_vaccinations'] ?? null);
            $inv = $context_data['get_inventory_forecast_data'] ?? ($context_data['inventory_forecast_data'] ?? null);

            // A. Client Balances / Debtors Query
            if (!empty($clients) && (strpos($p, 'owe') !== false || strpos($p, 'debt') !== false || strpos($p, 'client') !== false || strpos($p, 'balance') !== false)) {
                $output .= "According to your active KulaCRM client records:\n\n";
                foreach ($clients as $c) {
                    $name = $c['client_name'] ?? 'Client';
                    $phone = !empty($c['client_phone']) ? $c['client_phone'] : 'No phone listed';
                    $output .= "- **{$name}** (Contact: {$phone})\n";
                }
                if (!empty($fin)) {
                    $income = number_format($fin['total_income'] ?? 0);
                    $output .= "\nTotal cumulative sales recorded: **UGX {$income}**.";
                }
                return $output;
            }

            // B. Mortality & Death Queries
            if (!empty($bs) && ($intent === 'MORTALITY_QUERY' || strpos($p, 'death') !== false || strpos($p, 'died') !== false || strpos($p, 'mortality') !== false)) {
                if (is_array($bs) && !empty($bs)) {
                    // Sort batches by mortality rate descending
                    usort($bs, function($a, $b) {
                        return (float)str_replace('%', '', $b['mortality_rate'] ?? 0) <=> (float)str_replace('%', '', $a['mortality_rate'] ?? 0);
                    });

                    $output .= "Here is the current mortality breakdown across your active farm batches:\n\n";
                    $total_deaths = 0;
                    foreach ($bs as $b) {
                        $name = $b['shed_name'] ?? $b['batch_title'] ?? 'Batch';
                        $init = $b['initial_quantity'] ?? 0;
                        $deaths = $b['death_quantity'] ?? 0;
                        $total_deaths += $deaths;
                        $curr = $b['current_quantity'] ?? ($init - $deaths);
                        $rate = $b['mortality_rate'] ?? (($init > 0) ? round(($deaths / $init) * 100, 1) . '%' : '0%');
                        $output .= "- **{$name}**: {$deaths} deaths recorded ({$rate} mortality rate). Current stock: {$curr} animals.\n";
                    }
                    $output .= "\n**Total Deaths Recorded:** {$total_deaths} animals.";
                    return $output;
                }
            }

            // C. Farm Summary / Livestock Counts
            if (!empty($fs) && ($intent === 'FARM_DATA_QUERY' || $intent === 'LIVESTOCK_QUERY' || strpos($p, 'how many') !== false || strpos($p, 'animal') !== false)) {
                $total_ls = number_format($fs['total_livestock'] ?? 0);
                $sheds = $fs['total_sheds'] ?? 0;
                $batches = $fs['total_batches'] ?? 0;
                $output .= "According to your active KulaCRM records, your farm currently has **{$total_ls} active animals** across **{$sheds} sheds** and **{$batches} batches**.\n";
                if (isset($fs['total_sales']) && $fs['total_sales'] > 0) {
                    $output .= "Cumulative sales recorded to date total **UGX " . number_format($fs['total_sales']) . "**.";
                }
                return $output;
            }

            // D. Financial Summary
            if (!empty($fin) && ($intent === 'FINANCIAL_QUERY' || $intent === 'EXPENSE_QUERY' || $intent === 'SALES_QUERY' || strpos($p, 'spend') !== false || strpos($p, 'expense') !== false)) {
                $income = number_format($fin['total_income'] ?? $fin['revenue'] ?? 0);
                $expenses = number_format($fin['total_expenses'] ?? $fin['expenses'] ?? 0);
                $net = number_format(($fin['total_income'] ?? 0) - ($fin['total_expenses'] ?? 0));

                return "According to your recorded financial transactions in KulaCRM:\n\n"
                    . "- **Total Sales Revenue:** UGX {$income}\n"
                    . "- **Total Operating Expenses:** UGX {$expenses}\n"
                    . "- **Net Operational Balance:** UGX {$net}";
            }

            // E. Vaccination Routines
            if (!empty($vacs) && ($intent === 'VACCINATION_QUERY' || strpos($p, 'vaccin') !== false)) {
                if (is_array($vacs) && !empty($vacs)) {
                    $output .= "Here are your upcoming and recorded vaccination routines:\n\n";
                    foreach ($vacs as $v) {
                        $vac_name = $v['vac_name'] ?? 'Vaccination';
                        $shed = $v['shed_name'] ?? 'Shed';
                        $date = $v['vds_given_date'] ?? 'Scheduled';
                        $output .= "- **{$vac_name}** ({$shed}): Scheduled for {$date}\n";
                    }
                    return $output;
                } else {
                    return "No upcoming vaccinations recorded for this period in KulaCRM.";
                }
            }
        }

        // 6. Report Generation Requests
        if ($intent === 'REPORT_REQUEST' || strpos($p, 'report') !== false) {
            $output = "### 📋 KulaAI Executive Farm Performance Report\n"
                . "**Generated:** " . date('F j, Y \a\t H:i:s') . "  \n"
                . "**Scope:** Active Livestock, Health, & Financial Summary\n\n"
                . "---\n\n";

            $fs = $context_data['get_farm_summary'] ?? ($context_data['farm_summary'] ?? null);
            $fin = $context_data['get_financial_summary'] ?? ($context_data['financial_summary'] ?? null);

            if (!empty($fs)) {
                $output .= "### 1. Livestock & Production Summary\n"
                    . "- **Active Animals:** " . number_format($fs['total_livestock'] ?? 0) . "\n"
                    . "- **Active Sheds:** " . ($fs['total_sheds'] ?? 0) . "\n"
                    . "- **Active Batches:** " . ($fs['total_batches'] ?? 0) . "\n"
                    . "- **Overall Mortality Rate:** " . ($fs['mortality_rate'] ?? '0%') . "\n\n";
            }

            if (!empty($fin)) {
                $output .= "### 2. Financial Overview\n"
                    . "- **Total Revenue:** UGX " . number_format($fin['total_income'] ?? 0) . "\n"
                    . "- **Operating Expenses:** UGX " . number_format($fin['total_expenses'] ?? 0) . "\n"
                    . "- **Net Operational Margin:** UGX " . number_format(($fin['total_income'] ?? 0) - ($fin['total_expenses'] ?? 0)) . "\n\n";
            }

            $output .= "### 3. Key Operational Takeaways\n"
                . "1. Maintain strict vaccination schedules across active sheds to minimize mortality risks.\n"
                . "2. Monitor daily feed consumption rates against inventory stock levels to avoid feed shortages.\n";

            return $output;
        }

        // 6. Recommendations & Action Plans (ONLY when explicitly requested!)
        if ($intent === 'FARM_RECOMMENDATION' || $intent === 'FARM_ANALYSIS') {
            $output = "### 💡 KulaAI Executive Recommendation\n\n";
            $output .= "Based on your request regarding **" . htmlspecialchars($user_prompt) . "**, here is an actionable strategy:\n\n";
            $output .= "1. **Audit Production Records:** Review daily mortality logs and feed conversion rates under the Batch module.\n";
            $output .= "2. **Review Biosecurity Protocols:** Ensure footbaths, water treatment, and vaccination routines (Newcastle, Gumboro) are strictly maintained.\n";
            $output .= "3. **Optimize Expenses:** Track daily feed distribution to identify wastage patterns and reduce feed costs.\n";
            return $output;
        }

        // 7. Natural Fallback Response
        return "I am here to help you manage your farm in KulaCRM! Feel free to ask about your livestock counts, mortality rates, feed stocks, vaccination schedules, or agribusiness recommendations.";
    }
}
