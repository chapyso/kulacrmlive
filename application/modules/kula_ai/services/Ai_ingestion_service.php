<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ai_ingestion_service
 * Handles AI-powered document ingestion: PDF/image upload, text extraction,
 * structured data parsing via Gemini Vision API, and KulaCRM database insertion.
 */
class Ai_ingestion_service {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    /**
     * Extract text from an uploaded PDF file using basic text extraction
     */
    public function extract_pdf_text($file_path) {
        // Try pdfparser if available
        $text = '';

        // Simple raw PDF text extraction (works for text-based PDFs)
        $content = @file_get_contents($file_path);
        if ($content) {
            // Extract readable text from PDF binary stream
            $text = '';
            // Find text between BT and ET markers (PDF text operators)
            preg_match_all('/BT(.*?)ET/s', $content, $matches);
            foreach ($matches[1] as $block) {
                // Extract strings from Tj and TJ operators
                preg_match_all('/\((.*?)\)\s*Tj/s', $block, $str_matches);
                foreach ($str_matches[1] as $str) {
                    $text .= $str . ' ';
                }
                preg_match_all('/\[(.*?)\]\s*TJ/s', $block, $arr_matches);
                foreach ($arr_matches[1] as $arr) {
                    preg_match_all('/\((.*?)\)/', $arr, $inner);
                    foreach ($inner[1] as $s) {
                        $text .= $s . ' ';
                    }
                }
            }
            // Also try extracting plain strings
            if (strlen(trim($text)) < 50) {
                preg_match_all('/\(([\x20-\x7e]{3,})\)/', $content, $all_strings);
                $candidates = array_filter($all_strings[1], function($s) {
                    return strlen($s) > 3 && preg_match('/[a-zA-Z0-9]/', $s);
                });
                $text = implode(' ', array_slice($candidates, 0, 300));
            }
        }

        return trim($text);
    }

    /**
     * Convert image file to base64 for Gemini Vision
     */
    public function image_to_base64($file_path, $mime_type) {
        $data = @file_get_contents($file_path);
        if (!$data) return null;
        return base64_encode($data);
    }

    /**
     * Call Gemini Vision API with an image to extract structured livestock data
     */
    public function extract_from_image_via_gemini($api_key, $model, $base64_image, $mime_type) {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . urlencode($api_key);

        $extraction_prompt = $this->build_extraction_prompt('');

        $payload = array(
            'contents' => array(
                array(
                    'parts' => array(
                        array('text' => $extraction_prompt),
                        array(
                            'inlineData' => array(
                                'mimeType' => $mime_type,
                                'data'     => $base64_image
                            )
                        )
                    )
                )
            ),
            'generationConfig' => array(
                'temperature'     => 0.1,
                'maxOutputTokens' => 3000
            )
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return array('status' => false, 'error' => 'Gemini Vision API connection error: ' . $err);
        }

        $res = json_decode($response, true);
        $text = $res['candidates'][0]['content']['parts'][0]['text'] ?? null;
        if ($text) {
            return array('status' => true, 'text' => $text);
        }
        $err_msg = $res['error']['message'] ?? 'Gemini Vision API returned no content.';
        return array('status' => false, 'error' => $err_msg);
    }

    /**
     * Call Gemini text API to extract structured data from PDF text
     */
    public function extract_from_text_via_gemini($api_key, $model, $text_content) {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . urlencode($api_key);

        $prompt = $this->build_extraction_prompt($text_content);

        $payload = array(
            'contents' => array(
                array(
                    'parts' => array(
                        array('text' => $prompt)
                    )
                )
            ),
            'generationConfig' => array(
                'temperature'     => 0.1,
                'maxOutputTokens' => 3000
            )
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return array('status' => false, 'error' => 'Gemini API error: ' . $err);
        }

        $res = json_decode($response, true);
        $text = $res['candidates'][0]['content']['parts'][0]['text'] ?? null;
        if ($text) {
            return array('status' => true, 'text' => $text);
        }
        $err_msg = $res['error']['message'] ?? 'Gemini returned no content.';
        return array('status' => false, 'error' => $err_msg);
    }

    /**
     * Build the universal livestock data extraction prompt
     */
    private function build_extraction_prompt($text_content = '') {
        $doc_section = !empty($text_content)
            ? "DOCUMENT CONTENT:\n{$text_content}\n\n"
            : "Analyze the provided document image/PDF carefully.\n\n";

        return $doc_section .
            "You are a livestock farm data extraction AI for KulaCRM. " .
            "Extract ALL livestock farm data from this document and return ONLY a valid JSON object. " .
            "Do NOT include any explanation, markdown, or text outside the JSON object.\n\n" .
            "Required JSON structure:\n" .
            "{\n" .
            "  \"sales\": [\n" .
            "    {\"date\": \"YYYY-MM-DD\", \"batch_name\": \"...\", \"shed_name\": \"...\", \"livestock_type\": \"...\", \"quantity\": 0, \"unit_price\": 0, \"total_amount\": 0, \"client_name\": \"...\"}\n" .
            "  ],\n" .
            "  \"purchases\": [\n" .
            "    {\"date\": \"YYYY-MM-DD\", \"livestock_type\": \"...\", \"quantity\": 0, \"unit_price\": 0, \"total_amount\": 0, \"supplier_name\": \"...\"}\n" .
            "  ],\n" .
            "  \"deaths\": [\n" .
            "    {\"date\": \"YYYY-MM-DD\", \"batch_name\": \"...\", \"shed_name\": \"...\", \"quantity\": 0, \"reason\": \"...\"}\n" .
            "  ],\n" .
            "  \"vaccinations\": [\n" .
            "    {\"date\": \"YYYY-MM-DD\", \"batch_name\": \"...\", \"shed_name\": \"...\", \"vaccine_name\": \"...\", \"quantity\": 0, \"notes\": \"...\"}\n" .
            "  ]\n" .
            "}\n\n" .
            "Rules:\n" .
            "- Use null for unknown fields (never omit them)\n" .
            "- All dates in YYYY-MM-DD format. If year is missing, use current year (" . date('Y') . ")\n" .
            "- quantities and amounts must be numbers (not strings)\n" .
            "- Return empty arrays [] if no data found for a category\n" .
            "- ONLY return the JSON object, nothing else";
    }

    /**
     * Parse the AI's raw JSON response
     */
    public function parse_extracted_json($raw_text) {
        // Strip markdown code fences if present
        $clean = preg_replace('/^```(?:json)?\s*/i', '', trim($raw_text));
        $clean = preg_replace('/\s*```$/', '', $clean);
        $clean = trim($clean);

        $data = json_decode($clean, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Try to find JSON object in the response
            preg_match('/\{.*\}/s', $clean, $m);
            if (!empty($m[0])) {
                $data = json_decode($m[0], true);
            }
        }

        if (!is_array($data)) {
            return array('status' => false, 'error' => 'Could not parse structured data from document.');
        }

        return array(
            'status'       => true,
            'sales'        => $data['sales']        ?? array(),
            'purchases'    => $data['purchases']    ?? array(),
            'deaths'       => $data['deaths']       ?? array(),
            'vaccinations' => $data['vaccinations'] ?? array(),
        );
    }

    /**
     * Get tenant_id safely
     */
    private function get_tenant_id() {
        $CI =& $this->CI;
        if (isset($CI->tenant_id) && !empty($CI->tenant_id)) return (int)$CI->tenant_id;
        if (isset($CI->session) && $CI->session->userdata('tenant_id')) return (int)$CI->session->userdata('tenant_id');
        return 1;
    }

    /**
     * Get logged in user ID safely
     */
    private function get_user_id() {
        $CI =& $this->CI;
        return ($CI->ion_auth && $CI->ion_auth->logged_in()) ? $CI->ion_auth->get_user_id() : null;
    }

    /**
     * Look up shed ID by name (case-insensitive, tenant-scoped)
     */
    public function find_shed_id($shed_name) {
        if (empty($shed_name)) return null;
        $this->CI->db->select('sh_id');
        $this->CI->db->from('shed');
        $this->CI->db->where('tenant_id', $this->get_tenant_id());
        $this->CI->db->where('sh_status', 1);
        $this->CI->db->like('sh_title', $shed_name);
        $row = $this->CI->db->get()->row();
        return $row ? $row->sh_id : null;
    }

    /**
     * Look up batch ID by name (case-insensitive, tenant-scoped)
     */
    public function find_batch_id($batch_name, $shed_id = null) {
        if (empty($batch_name)) return null;
        $this->CI->db->select('lshs_id, lshs_sh_id, lshs_batch_id');
        $this->CI->db->from('livestock_shed_history');
        $this->CI->db->where('tenant_id', $this->get_tenant_id());
        $this->CI->db->like('lshs_batch_id', $batch_name);
        if ($shed_id) $this->CI->db->where('lshs_sh_id', $shed_id);
        $row = $this->CI->db->get()->row();
        return $row ? $row->lshs_id : null;
    }

    /**
     * Save AI-extracted deaths to livestock_death_quantity table
     */
    public function save_deaths($deaths_array) {
        $saved = 0;
        $skipped = 0;
        $tenant_id = $this->get_tenant_id();
        $user_id   = $this->get_user_id();

        foreach ($deaths_array as $d) {
            $quantity = (int)($d['quantity'] ?? 0);
            $date     = $d['date'] ?? date('Y-m-d');
            $reason   = $d['reason'] ?? 'Extracted from uploaded document';

            if ($quantity <= 0) { $skipped++; continue; }

            // Try to find shed and batch IDs
            $sh_id   = $this->find_shed_id($d['shed_name'] ?? '');
            $lshs_id = $this->find_batch_id($d['batch_name'] ?? '', $sh_id);

            $row = array(
                'ld_lshs_id'       => $lshs_id,
                'ld_purv_ls_id'    => null,
                'ld_purv_lst_id'   => null,
                'ld_death_quantity'=> $quantity,
                'ld_sh_id'         => $sh_id,
                'ld_batch_id'      => $lshs_id,
                'ld_death_date'    => date('Y-m-d', strtotime($date)),
                'ld_death_reason'  => $reason,
                'ld_status'        => 1,
                'ld_created_at'    => date('Y-m-d H:i:s'),
                'ld_created_by'    => $user_id,
                'tenant_id'        => $tenant_id
            );

            $this->CI->db->insert('livestock_death_quantity', $row);
            $saved++;
        }

        return array('saved' => $saved, 'skipped' => $skipped);
    }

    /**
     * Save AI-extracted sales to livestock_sale_summary table
     */
    public function save_sales($sales_array) {
        $saved = 0;
        $skipped = 0;
        $tenant_id = $this->get_tenant_id();
        $user_id   = $this->get_user_id();

        foreach ($sales_array as $s) {
            $quantity = (int)($s['quantity'] ?? 0);
            $total    = (float)($s['total_amount'] ?? 0);
            $date     = $s['date'] ?? date('Y-m-d');

            if ($quantity <= 0 && $total <= 0) { $skipped++; continue; }

            $summary = array(
                'lsss_date'           => date('Y-m-d', strtotime($date)),
                'lsss_grand_discount' => 0,
                'lsss_grand_total'    => $total,
                'lsss_status'         => 1,
                'lsss_created_at'     => date('Y-m-d H:i:s'),
                'lsss_created_by'     => $user_id,
                'tenant_id'           => $tenant_id,
                'lsss_note'           => 'Imported via KulaAI Document Ingestion — ' . ($s['client_name'] ?? 'Unknown Client')
            );

            // Only insert if sub_total column might not exist (graceful)
            if ($this->CI->db->field_exists('lsss_sub_total', 'livestock_sale_summary')) {
                $summary['lsss_sub_total'] = $total;
            }

            $this->CI->db->insert('livestock_sale_summary', $summary);
            $summary_id = $this->CI->db->insert_id();

            if ($summary_id && $quantity > 0) {
                $unit_price = $quantity > 0 ? round($total / $quantity, 2) : 0;
                $value = array(
                    'lssv_lsss_id'    => $summary_id,
                    'lssv_quantity'   => $quantity,
                    'lssv_unit_price' => (float)($s['unit_price'] ?? $unit_price),
                    'lssv_status'     => 1,
                    'tenant_id'       => $tenant_id
                );
                $this->CI->db->insert('livestock_sale_value', $value);
            }
            $saved++;
        }

        return array('saved' => $saved, 'skipped' => $skipped);
    }

    /**
     * Save AI-extracted purchases to livestock_purchase_summary table
     */
    public function save_purchases($purchases_array) {
        $saved = 0;
        $skipped = 0;
        $tenant_id = $this->get_tenant_id();
        $user_id   = $this->get_user_id();

        foreach ($purchases_array as $p) {
            $quantity = (int)($p['quantity'] ?? 0);
            $total    = (float)($p['total_amount'] ?? 0);
            $date     = $p['date'] ?? date('Y-m-d');

            if ($quantity <= 0 && $total <= 0) { $skipped++; continue; }

            $summary = array(
                'purs_date'       => date('Y-m-d', strtotime($date)),
                'purs_status'     => 1,
                'purs_created_at' => date('Y-m-d H:i:s'),
                'purs_created_by' => $user_id,
                'tenant_id'       => $tenant_id,
                'purs_note'       => 'Imported via KulaAI — ' . ($p['supplier_name'] ?? 'Unknown Supplier')
            );

            $this->CI->db->insert('livestock_purchase_summary', $summary);
            $summary_id = $this->CI->db->insert_id();

            if ($summary_id && $quantity > 0) {
                $unit_price = $quantity > 0 ? round($total / $quantity, 2) : 0;
                $value = array(
                    'purv_purs_id'   => $summary_id,
                    'purv_quantity'  => $quantity,
                    'purv_price'     => (float)($p['unit_price'] ?? $unit_price),
                    'purv_status'    => 1,
                    'tenant_id'      => $tenant_id
                );
                $this->CI->db->insert('livestock_purchase_value', $value);
            }
            $saved++;
        }

        return array('saved' => $saved, 'skipped' => $skipped);
    }

    /**
     * Save AI-extracted vaccinations to vaccination table
     */
    public function save_vaccinations($vaccinations_array) {
        $saved = 0;
        $skipped = 0;
        $tenant_id = $this->get_tenant_id();
        $user_id   = $this->get_user_id();

        foreach ($vaccinations_array as $v) {
            $vaccine_name = trim($v['vaccine_name'] ?? '');
            $date         = $v['date'] ?? date('Y-m-d');

            if (empty($vaccine_name)) { $skipped++; continue; }

            // Find or create vaccine record
            $this->CI->db->select('vcc_id');
            $this->CI->db->from('vaccine');
            $this->CI->db->where('tenant_id', $tenant_id);
            $this->CI->db->like('vcc_name', $vaccine_name);
            $existing = $this->CI->db->get()->row();

            $vcc_id = $existing ? $existing->vcc_id : null;
            if (!$vcc_id) {
                // Auto-create vaccine entry
                $sh_id = $this->find_shed_id($v['shed_name'] ?? '');
                $this->CI->db->insert('vaccine', array(
                    'vcc_name'       => $vaccine_name,
                    'vcc_status'     => 1,
                    'vcc_created_at' => date('Y-m-d H:i:s'),
                    'tenant_id'      => $tenant_id,
                    'shed'           => $sh_id
                ));
                $vcc_id = $this->CI->db->insert_id();
            }

            if (!$vcc_id) { $skipped++; continue; }

            // Find shed and batch
            $sh_id   = $this->find_shed_id($v['shed_name'] ?? '');
            $lshs_id = $this->find_batch_id($v['batch_name'] ?? '', $sh_id);

            $vac_row = array(
                'vccn_vcc_id'    => $vcc_id,
                'vccn_ls_id'     => null,
                'vccn_lst_id'    => null,
                'vccn_sh_id'     => $sh_id,
                'vccn_date'      => date('Y-m-d', strtotime($date)),
                'vccn_status'    => 1,
                'vccn_created_at'=> date('Y-m-d H:i:s'),
                'vccn_created_by'=> $user_id,
                'tenant_id'      => $tenant_id
            );

            // Only add optional columns if they exist
            if ($this->CI->db->field_exists('vccn_notes', 'vaccination')) {
                $vac_row['vccn_notes'] = $v['notes'] ?? 'Imported via KulaAI Document Ingestion';
            }

            $this->CI->db->insert('vaccination', $vac_row);
            $saved++;
        }

        return array('saved' => $saved, 'skipped' => $skipped);
    }
}
