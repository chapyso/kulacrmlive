<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ai_vision_service Service
 * KulaAI Vision Livestock Identification & Smart Counting Engine
 * Multi-tenant, server-side intelligence layer operating strictly on top of KulaCRM database.
 */
class Ai_vision_service {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->database();
        if (!isset($this->CI->ai_provider)) {
            $this->CI->load->library('kula_ai/Ai_provider', null, 'ai_provider');
        }
    }

    /**
     * Resolve Active Tenant ID securely
     */
    protected function get_tenant_id() {
        if (method_exists($this->CI, 'get_tenant_id')) {
            return $this->CI->get_tenant_id();
        }
        return $this->CI->session->userdata('tenant_id') ?: 1;
    }

    /**
     * Calculate Expected Available Livestock for a Shed & Optional Batch from KulaCRM
     */
    public function get_expected_livestock_count($shed_id, $batch_id = null) {
        $tenant_id = $this->get_tenant_id();

        // 1. Assigned Quantity in Shed Summary
        $this->CI->db->select('SUM(lshs_assign_total_quantity) AS assigned');
        $this->CI->db->where('lshs_sh_id', $shed_id);
        $this->CI->db->where('lshs_status', 1);
        $this->CI->db->where('tenant_id', $tenant_id);
        if (!empty($batch_id)) {
            $this->CI->db->where('lshs_batch_id', $batch_id);
        }
        $assigned_row = $this->CI->db->get('live_assigned_shed_summary')->row();
        $assigned = (int)($assigned_row->assigned ?? 0);

        if ($assigned === 0) {
            // Check fallback in live_assigned_shed table
            $this->CI->db->select('SUM(lsh_assign_total_quantity) AS assigned');
            $this->CI->db->where('lsh_sh_id', $shed_id);
            $this->CI->db->where('lsh_status', 1);
            $this->CI->db->where('tenant_id', $tenant_id);
            if (!empty($batch_id)) {
                $this->CI->db->where('lsh_batch_id', $batch_id);
            }
            $lsh_row = $this->CI->db->get('live_assigned_shed')->row();
            $assigned = (int)($lsh_row->assigned ?? 0);
        }

        // 2. Mortality Quantity
        $this->CI->db->select('SUM(ld_death_quantity) AS deaths');
        $this->CI->db->where('ld_sh_id', $shed_id);
        $this->CI->db->where('ld_status', 1);
        $this->CI->db->where('tenant_id', $tenant_id);
        if (!empty($batch_id)) {
            $this->CI->db->where('ld_batch_id', $batch_id);
        }
        $death_row = $this->CI->db->get('livestock_death_quantity')->row();
        $deaths = (int)($death_row->deaths ?? 0);

        // 3. Transfer Out Quantity
        $this->CI->db->select('SUM(ltr_transfer_quantity) AS transfers');
        $this->CI->db->where('ltr_sh_id', $shed_id);
        $this->CI->db->where('ltr_status', 1);
        $this->CI->db->where('tenant_id', $tenant_id);
        $transfer_row = $this->CI->db->get('livestock_transfer_quantity')->row();
        $transfers = (int)($transfer_row->transfers ?? 0);

        // Available Expected Count
        $expected = max(0, $assigned - $deaths - $transfers);
        return array(
            'assigned'  => $assigned,
            'deaths'    => $deaths,
            'transfers' => $transfers,
            'expected'  => $expected
        );
    }

    /**
     * Start a New KulaAI Vision Counting Session
     */
    public function start_session($shed_id, $batch_id = null, $notes = '') {
        $tenant_id = $this->get_tenant_id();
        $user_id   = $this->CI->session->userdata('user_id') ?: 1;

        $calc = $this->get_expected_livestock_count($shed_id, $batch_id);
        $session_code = 'CS-' . date('Ymd') . '-' . sprintf('%04d', rand(1, 9999));

        $data = array(
            'tenant_id'          => $tenant_id,
            'session_code'       => $session_code,
            'user_id'            => $user_id,
            'shed_id'            => (int)$shed_id,
            'batch_id'           => !empty($batch_id) ? (int)$batch_id : null,
            'start_time'         => date('Y-m-d H:i:s'),
            'status'             => 'in_progress',
            'expected_count'     => $calc['expected'],
            'confirmed_count'    => 0,
            'unknown_count'      => 0,
            'needs_review_count' => 0,
            'difference_count'   => $calc['expected'],
            'notes'              => $notes,
            'created_at'         => date('Y-m-d H:i:s')
        );

        $this->CI->db->insert('ai_vision_counting_sessions', $data);
        $session_id = $this->CI->db->insert_id();

        return array(
            'status'             => true,
            'session_id'         => $session_id,
            'session_code'       => $session_code,
            'expected_count'     => $calc['expected'],
            'assigned_quantity'  => $calc['assigned'],
            'recorded_deaths'    => $calc['deaths'],
            'recorded_transfers' => $calc['transfers']
        );
    }

    /**
     * Get Active Counting Session Details
     */
    public function get_session($session_id) {
        $tenant_id = $this->get_tenant_id();
        $this->CI->db->where('id', $session_id);
        $this->CI->db->where('tenant_id', $tenant_id);
        $session = $this->CI->db->get('ai_vision_counting_sessions')->row();

        if (!$session) {
            return null;
        }

        // Fetch Shed Name & Batch Code
        $shed = $this->CI->db->get_where('shed', array('sh_id' => $session->shed_id, 'tenant_id' => $tenant_id))->row();
        $session->shed_name = $shed ? ($shed->sh_title ?? 'Shed #' . $shed->sh_no) : 'Shed #' . $session->shed_id;

        $batch = null;
        if (!empty($session->batch_id)) {
            $batch = $this->CI->db->get_where('live_assigned_shed', array('lsh_batch_id' => $session->batch_id, 'tenant_id' => $tenant_id))->row();
        }
        $session->batch_code = $batch ? ($batch->lsh_batch_id ?? 'Batch ' . $session->batch_id) : ($session->batch_id ? 'Batch ' . $session->batch_id : 'All Batches');

        return $session;
    }

    /**
     * Analyze Device Camera Frame using Gemini Multimodal Vision & Reconcile with KulaCRM Records
     */
    public function analyze_frame($session_id, $image_base64, $mime_type = 'image/jpeg') {
        $session = $this->get_session($session_id);
        if (!$session) {
            return array('status' => false, 'error' => 'Invalid or expired counting session.');
        }

        $tenant_id = $this->get_tenant_id();

        // Retrieve list of already counted tags/IDs in this session to enforce persistent unique counting
        $this->CI->db->select('livestock_id, tag_number');
        $this->CI->db->where('session_id', $session_id);
        $this->CI->db->where('tenant_id', $tenant_id);
        $this->CI->db->where('identification_status', 'confirmed');
        $this->CI->db->where('is_counted', 1);
        $counted_records = $this->CI->db->get('ai_vision_session_records')->result();

        $counted_tags = array_filter(array_column($counted_records, 'tag_number'));
        $counted_ids  = array_filter(array_column($counted_records, 'livestock_id'));

        // Retrieve KulaCRM livestock records registered in this shed/batch for candidate matching
        $this->CI->db->select('livestock.ls_id, livestock.ls_name, livestock_type.lst_title as variant_name');
        $this->CI->db->from('livestock');
        $this->CI->db->join('livestock_type', 'livestock_type.lst_id = livestock.ls_lst_type_id', 'left');
        $this->CI->db->where('livestock.tenant_id', $tenant_id);
        $this->CI->db->where('livestock.ls_status', 1);
        $this->CI->db->limit(100);
        $expected_records = $this->CI->db->get()->result_array();

        $context_payload = array(
            'session_code'         => $session->session_code,
            'shed_id'              => $session->shed_id,
            'shed_name'            => $session->shed_name,
            'selected_batch'       => $session->batch_code,
            'already_counted_tags' => array_values($counted_tags),
            'expected_livestock'   => $expected_records
        );

        $system_prompt = "You are KulaAI Vision, a high-precision computer vision model for livestock identification & smart counting.\n"
            . "ANALYZE THE SUBMITTED CAMERA FRAME AND RETURN STRICT JSON WITH THIS EXACT SCHEMA:\n"
            . "{\n"
            . '  "animal_detected": true|false,' . "\n"
            . '  "animal_type": "goat|cattle|poultry|pig|sheep|unknown",' . "\n"
            . '  "ear_tag_detected": true|false,' . "\n"
            . '  "ear_tag": "TAG_NUMBER or null",' . "\n"
            . '  "ear_tag_readable": true|false,' . "\n"
            . '  "candidate_livestock_id": int|null,' . "\n"
            . '  "candidate_matches": [ {"livestock_id": int, "tag_number": "string", "variant": "string", "confidence": float} ],' . "\n"
            . '  "visual_features": { "coat_color": "string", "markings": "string", "size_estimate": "small|medium|large", "breed_variant": "string" },' . "\n"
            . '  "identification_status": "confirmed|needs_review|unknown",' . "\n"
            . '  "confidence_level": float_0_to_100,' . "\n"
            . '  "requires_human_confirmation": true|false,' . "\n"
            . '  "batch_mismatch_detected": true|false,' . "\n"
            . '  "detected_batch_id": "string or null"' . "\n"
            . "}\n\n"
            . "RULES FOR ACCURACY & INTEGRITY:\n"
            . "1. EAR TAG OCR: If an ear tag is visible, read it carefully and normalize alphanumeric text (e.g., 'KLA-G-0184'). If unreadable, do NOT invent numbers. Set ear_tag_readable=false.\n"
            . "2. CANDIDATE MATCHING: Cross-reference ear tag and visual traits against KulaCRM expected livestock list.\n"
            . "3. CONFIDENCE THRESHOLDS:\n"
            . "   - Confidence >= 85% -> identification_status = 'confirmed', requires_human_confirmation = false.\n"
            . "   - Confidence 50%..84% -> identification_status = 'needs_review', requires_human_confirmation = true.\n"
            . "   - Confidence < 50% -> identification_status = 'unknown'.\n"
            . "4. AVOID FALSE MATCHES: NEVER fabricate identification. If ambiguous, mark as 'needs_review' or 'unknown'.";

        $vision_res = $this->CI->ai_provider->generate_vision($system_prompt, $image_base64, $mime_type, $context_payload);

        if (!$vision_res['status']) {
            return array(
                'status'   => false,
                'error'    => $vision_res['response'] ?? 'Vision API connection error.',
                'provider' => $vision_res['provider'] ?? 'gemini'
            );
        }

        // Parse structured JSON response
        $parsed = json_decode($vision_res['response'], true);
        if (!is_array($parsed)) {
            // Attempt clean JSON extraction if wrapped in markdown
            $clean_json = preg_replace('/^```json\s*|\s*```$/i', '', trim($vision_res['response']));
            $parsed = json_decode($clean_json, true);
        }

        if (!is_array($parsed) || !isset($parsed['animal_detected'])) {
            return array(
                'status' => false,
                'error'  => 'Invalid AI vision analysis response schema.',
                'raw'    => $vision_res['response']
            );
        }

        if (!$parsed['animal_detected']) {
            return array(
                'status'           => true,
                'animal_detected'  => false,
                'message'          => 'No animal detected in camera frame. Point camera directly at livestock.',
                'current_counts'   => array(
                    'confirmed'    => $session->confirmed_count,
                    'needs_review' => $session->needs_review_count,
                    'unknown'      => $session->unknown_count,
                    'expected'     => $session->expected_count
                )
            );
        }

        // Check persistent double counting suppression
        $detected_tag = !empty($parsed['ear_tag']) ? trim($parsed['ear_tag']) : null;
        $detected_id  = !empty($parsed['candidate_livestock_id']) ? (int)$parsed['candidate_livestock_id'] : null;
        $already_counted = false;

        // Atomic DB Check & Lock to prevent Race Conditions under concurrent frame requests
        $this->CI->db->trans_start();

        if (!empty($detected_tag)) {
            $existing_lock = $this->CI->db->get_where('ai_vision_session_records', array(
                'session_id'            => $session_id,
                'tenant_id'             => $tenant_id,
                'tag_number'            => $detected_tag,
                'identification_status' => 'confirmed'
            ))->row();
            if ($existing_lock) { $already_counted = true; }
        }
        if (!empty($detected_id) && !$already_counted) {
            $existing_id_lock = $this->CI->db->get_where('ai_vision_session_records', array(
                'session_id'            => $session_id,
                'tenant_id'             => $tenant_id,
                'livestock_id'          => $detected_id,
                'identification_status' => 'confirmed'
            ))->row();
            if ($existing_id_lock) { $already_counted = true; }
        }

        if ($already_counted) {
            $this->CI->db->trans_complete();
            return array(
                'status'               => true,
                'animal_detected'      => true,
                'already_counted'      => true,
                'identification_status'=> 'already_counted',
                'tag_number'           => $detected_tag,
                'livestock_id'         => $detected_id,
                'confidence'           => $parsed['confidence_level'] ?? 90,
                'visual_features'      => $parsed['visual_features'] ?? array(),
                'message'              => "Animal (" . ($detected_tag ?: "ID #{$detected_id}") . ") ALREADY COUNTED in this session. Count not incremented.",
                'current_counts'       => array(
                    'confirmed'        => $session->confirmed_count,
                    'needs_review'     => $session->needs_review_count,
                    'unknown'          => $session->unknown_count,
                    'expected'         => $session->expected_count
                )
            );
        }

        // Record persistent detection event in DB
        $id_status  = $parsed['identification_status'] ?? 'unknown';
        $confidence = (float)($parsed['confidence_level'] ?? 50.0);
        $track_id   = $parsed['tracking_id'] ?? null;
        $track_col  = $parsed['tracking_color'] ?? null;

        $record_data = array(
            'tenant_id'              => $tenant_id,
            'session_id'            => $session_id,
            'livestock_id'          => $detected_id,
            'tracking_id'           => $track_id,
            'tracking_color'        => $track_col,
            'tag_number'            => $detected_tag,
            'variant_id'            => null,
            'identification_method' => !empty($parsed['ear_tag_detected']) ? 'ear_tag' : 'visual_features',
            'identification_status' => $id_status,
            'confidence'            => $confidence,
            'candidate_matches_json'=> json_encode($parsed['candidate_matches'] ?? array()),
            'visual_features_json'  => json_encode($parsed['visual_features'] ?? array()),
            'first_detected_at'     => date('Y-m-d H:i:s'),
            'last_detected_at'      => date('Y-m-d H:i:s'),
            'is_counted'            => ($id_status === 'confirmed') ? 1 : 0,
            'review_status'         => ($id_status === 'confirmed') ? 'approved' : 'pending',
            'created_at'            => date('Y-m-d H:i:s')
        );

        $this->CI->db->insert('ai_vision_session_records', $record_data);
        $record_id = $this->CI->db->insert_id();

        // Update Session Tallies
        if ($id_status === 'confirmed') {
            $this->CI->db->set('confirmed_count', 'confirmed_count+1', FALSE);
        } elseif ($id_status === 'needs_review') {
            $this->CI->db->set('needs_review_count', 'needs_review_count+1', FALSE);
        } else {
            $this->CI->db->set('unknown_count', 'unknown_count+1', FALSE);
        }

        $this->CI->db->set('difference_count', 'expected_count - confirmed_count', FALSE);
        $this->CI->db->where('id', $session_id);
        $this->CI->db->where('tenant_id', $tenant_id);
        $this->CI->db->update('ai_vision_counting_sessions');

        $this->CI->db->trans_complete();

        // Refetch updated session stats
        $updated_session = $this->get_session($session_id);

        return array(
            'status'                     => true,
            'record_id'                  => $record_id,
            'animal_detected'            => true,
            'already_counted'            => false,
            'identification_status'      => $id_status,
            'tag_number'                 => $detected_tag,
            'livestock_id'               => $detected_id,
            'candidate_matches'          => $parsed['candidate_matches'] ?? array(),
            'visual_features'            => $parsed['visual_features'] ?? array(),
            'confidence'                 => $confidence,
            'requires_human_confirmation'=> (bool)($parsed['requires_human_confirmation'] ?? false),
            'batch_mismatch'             => (bool)($parsed['batch_mismatch_detected'] ?? false),
            'current_counts'             => array(
                'confirmed'              => $updated_session->confirmed_count,
                'needs_review'           => $updated_session->needs_review_count,
                'unknown'                => $updated_session->unknown_count,
                'expected'               => $updated_session->expected_count,
                'difference'             => $updated_session->difference_count
            )
        );
    }

    /**
     * User Action: Human Confirmation of Candidate Match
     */
    public function confirm_match($session_id, $record_id, $livestock_id, $tag_number = null) {
        $tenant_id = $this->get_tenant_id();

        $record = $this->CI->db->get_where('ai_vision_session_records', array('id' => $record_id, 'tenant_id' => $tenant_id))->row();
        if (!$record) {
            return array('status' => false, 'error' => 'Record not found.');
        }

        // Check if livestock already counted in session
        $existing = $this->CI->db->get_where('ai_vision_session_records', array(
            'session_id'            => $session_id,
            'tenant_id'             => $tenant_id,
            'livestock_id'          => $livestock_id,
            'identification_status' => 'confirmed',
            'is_counted'            => 1
        ))->row();

        if ($existing && $existing->id != $record_id) {
            return array('status' => false, 'error' => 'This livestock record has ALREADY been confirmed and counted in this session.');
        }

        $update_data = array(
            'livestock_id'          => (int)$livestock_id,
            'tag_number'            => !empty($tag_number) ? $tag_number : $record->tag_number,
            'identification_method' => 'human_confirmed',
            'identification_status' => 'confirmed',
            'confidence'            => 100.00,
            'is_counted'            => 1,
            'review_status'         => 'approved',
            'last_detected_at'      => date('Y-m-d H:i:s')
        );

        $this->CI->db->where('id', $record_id);
        $this->CI->db->where('tenant_id', $tenant_id);
        $this->CI->db->update('ai_vision_session_records', $update_data);

        // Adjust Session Tallies
        if ($record->identification_status === 'needs_review') {
            $this->CI->db->set('needs_review_count', 'GREATEST(0, needs_review_count-1)', FALSE);
        } elseif ($record->identification_status === 'unknown') {
            $this->CI->db->set('unknown_count', 'GREATEST(0, unknown_count-1)', FALSE);
        }

        $this->CI->db->set('confirmed_count', 'confirmed_count+1', FALSE);
        $this->CI->db->set('difference_count', 'expected_count - confirmed_count', FALSE);
        $this->CI->db->where('id', $session_id);
        $this->CI->db->where('tenant_id', $tenant_id);
        $this->CI->db->update('ai_vision_counting_sessions');

        return array('status' => true, 'message' => 'Animal identity confirmed and counted.');
    }

    /**
     * User Action: Reject Candidate Match
     */
    public function reject_match($session_id, $record_id) {
        $tenant_id = $this->get_tenant_id();

        $record = $this->CI->db->get_where('ai_vision_session_records', array('id' => $record_id, 'tenant_id' => $tenant_id))->row();
        if (!$record) {
            return array('status' => false, 'error' => 'Record not found.');
        }

        $update_data = array(
            'identification_status' => 'rejected',
            'is_counted'            => 0,
            'review_status'         => 'rejected'
        );

        $this->CI->db->where('id', $record_id);
        $this->CI->db->where('tenant_id', $tenant_id);
        $this->CI->db->update('ai_vision_session_records', $update_data);

        if ($record->identification_status === 'needs_review') {
            $this->CI->db->set('needs_review_count', 'GREATEST(0, needs_review_count-1)', FALSE);
        }

        $this->CI->db->where('id', $session_id);
        $this->CI->db->where('tenant_id', $tenant_id);
        $this->CI->db->update('ai_vision_counting_sessions');

        return array('status' => true, 'message' => 'Candidate match rejected.');
    }

    /**
     * Complete Counting Session & Generate Physical vs KulaCRM Reconciliation
     */
    public function complete_session($session_id, $notes = '') {
        $tenant_id = $this->get_tenant_id();
        $session = $this->get_session($session_id);

        if (!$session) {
            return array('status' => false, 'error' => 'Counting session not found.');
        }

        $diff = $session->expected_count - $session->confirmed_count;

        $update_data = array(
            'end_time'         => date('Y-m-d H:i:s'),
            'status'           => 'completed',
            'difference_count' => $diff,
            'notes'            => $notes
        );

        $this->CI->db->where('id', $session_id);
        $this->CI->db->where('tenant_id', $tenant_id);
        $this->CI->db->update('ai_vision_counting_sessions', $update_data);

        $reconciliation = $this->reconcile_session($session_id);

        return array(
            'status'         => true,
            'session_id'     => $session_id,
            'session_code'   => $session->session_code,
            'reconciliation' => $reconciliation
        );
    }

    /**
     * Detailed KulaCRM Reconciliation Engine
     * Compares AI Physical Count vs KulaCRM Expected & investigates sales, deaths, and transfers.
     */
    public function reconcile_session($session_id) {
        $session = $this->get_session($session_id);
        if (!$session) {
            return null;
        }

        $tenant_id = $this->get_tenant_id();

        // 1. Fetch Session Detected Records
        $this->CI->db->where('session_id', $session_id);
        $this->CI->db->where('tenant_id', $tenant_id);
        $records = $this->CI->db->get('ai_vision_session_records')->result();

        // 2. Fetch Recorded Deaths in KulaCRM for Shed/Batch
        $this->CI->db->where('ld_sh_id', $session->shed_id);
        $this->CI->db->where('ld_status', 1);
        $this->CI->db->where('tenant_id', $tenant_id);
        if (!empty($session->batch_id)) {
            $this->CI->db->where('ld_batch_id', $session->batch_id);
        }
        $deaths = $this->CI->db->get('livestock_death_quantity')->result();
        $total_deaths = array_sum(array_column($deaths, 'ld_death_quantity'));

        // 3. Fetch Recorded Transfers in KulaCRM
        $this->CI->db->where('ltr_sh_id', $session->shed_id);
        $this->CI->db->where('ltr_status', 1);
        $this->CI->db->where('tenant_id', $tenant_id);
        $transfers = $this->CI->db->get('livestock_transfer_quantity')->result();
        $total_transfers = array_sum(array_column($transfers, 'ltr_transfer_quantity'));

        // 4. Calculate Discrepancy Breakdown
        $expected  = (int)$session->expected_count;
        $confirmed = (int)$session->confirmed_count;
        $diff      = $expected - $confirmed;

        $explanation = array();
        if ($diff === 0) {
            $summary_text = "✅ **PERFECT RECONCILIATION**: Physical AI Vision count exactly matches KulaCRM expected records (" . $confirmed . " animals).";
        } elseif ($diff > 0) {
            $summary_text = "⚠️ **COUNT DISCREPANCY DETECTED**: Physical count confirmed " . $confirmed . " unique animals, which is " . $diff . " fewer than KulaCRM expected (" . $expected . ").";
            if ($total_deaths > 0) {
                $explanation[] = "KulaCRM death records document " . $total_deaths . " animal mortality events in this shed.";
            }
            if ($total_transfers > 0) {
                $explanation[] = "KulaCRM transfer records document " . $total_transfers . " animals transferred to another shed.";
            }
            $unaccounted = max(0, $diff - $total_deaths - $total_transfers);
            if ($unaccounted > 0) {
                $explanation[] = $unaccounted . " animal(s) remain unconfirmed/unidentified in this counting session.";
            }
        } else {
            $surplus = abs($diff);
            $summary_text = "ℹ️ **SURPLUS DETECTED**: AI Vision confirmed " . $confirmed . " animals, which exceeds KulaCRM expected record (" . $expected . ") by " . $surplus . " animals. Potential unassigned livestock or batch mismatch.";
        }

        return array(
            'session_code'    => $session->session_code,
            'shed_name'       => $session->shed_name,
            'batch_code'      => $session->batch_code,
            'expected_count'  => $expected,
            'confirmed_count' => $confirmed,
            'unknown_count'   => (int)$session->unknown_count,
            'needs_review'    => (int)$session->needs_review_count,
            'difference'      => $diff,
            'recorded_deaths' => $total_deaths,
            'recorded_transfers' => $total_transfers,
            'summary_text'    => $summary_text,
            'explanations'    => $explanation,
            'records'         => $records
        );
    }

    /**
     * Get Past Counting Sessions History for Audit & Reporting
     */
    public function get_counting_history($limit = 30) {
        $tenant_id = $this->get_tenant_id();

        $this->CI->db->select('ai_vision_counting_sessions.*, shed.sh_title, shed.sh_no');
        $this->CI->db->from('ai_vision_counting_sessions');
        $this->CI->db->join('shed', 'shed.sh_id = ai_vision_counting_sessions.shed_id', 'left');
        $this->CI->db->where('ai_vision_counting_sessions.tenant_id', $tenant_id);
        $this->CI->db->order_by('ai_vision_counting_sessions.id', 'DESC');
        $this->CI->db->limit($limit);
        $sessions = $this->CI->db->get()->result();

        foreach ($sessions as $s) {
            $s->shed_name = !empty($s->sh_title) ? $s->sh_title : 'Shed #' . ($s->sh_no ?? $s->shed_id);
            $s->batch_code = !empty($s->batch_id) ? 'Batch ' . $s->batch_id : 'All Batches';
        }

        return $sessions;
    }

    /* ==========================================================================
       FIELD ACCURACY & ANIMAL IDENTITY VALIDATION MODE LAYER
       ========================================================================== */

    /**
     * Start a New Validation Session (VS-YYYYMMDD-XXXX)
     */
    public function start_validation_session($shed_id, $batch_id = null, $notes = '') {
        $tenant_id = $this->get_tenant_id();
        $user_id   = $this->CI->session->userdata('user_id') ?: 1;

        $session_code = 'VS-' . date('Ymd') . '-' . sprintf('%04d', rand(1, 9999));

        $data = array(
            'tenant_id'          => $tenant_id,
            'session_code'       => $session_code,
            'user_id'            => $user_id,
            'shed_id'            => (int)$shed_id,
            'batch_id'           => !empty($batch_id) ? (int)$batch_id : null,
            'start_time'         => date('Y-m-d H:i:s'),
            'status'             => 'in_progress',
            'total_tested'       => 0,
            'correct_count'      => 0,
            'incorrect_count'    => 0,
            'unknown_count'      => 0,
            'needs_review_count' => 0,
            'accuracy_pct'       => 0.00,
            'false_match_pct'    => 0.00,
            'unknown_pct'        => 0.00,
            'sample_status'      => 'INSUFFICIENT DATA',
            'notes'              => $notes,
            'created_at'         => date('Y-m-d H:i:s')
        );

        $this->CI->db->insert('ai_vision_validation_sessions', $data);
        $session_id = $this->CI->db->insert_id();

        return array(
            'status'       => true,
            'session_id'   => $session_id,
            'session_code' => $session_code
        );
    }

    /**
     * Record Validation Attempt comparing AI Prediction against Ground Truth
     */
    public function record_validation_attempt($session_id, $actual_id, $actual_tag, $ai_result, $metadata = array()) {
        $tenant_id = $this->get_tenant_id();

        // 1. Verify Ground Truth Comparison (NOT self-evaluated by AI)
        $ai_id   = !empty($ai_result['candidate_livestock_id']) ? (int)$ai_result['candidate_livestock_id'] : null;
        $ai_tag  = !empty($ai_result['ear_tag']) ? trim($ai_result['ear_tag']) : null;
        $status  = $ai_result['identification_status'] ?? 'unknown';

        $verdict = 'UNKNOWN';
        if ($status === 'unknown' || (empty($ai_id) && empty($ai_tag))) {
            $verdict = 'UNKNOWN';
        } elseif (!empty($actual_id) && $actual_id == $ai_id) {
            $verdict = 'CORRECT';
        } elseif (!empty($actual_tag) && !empty($ai_tag) && strtolower($actual_tag) === strtolower($ai_tag)) {
            $verdict = 'CORRECT';
        } elseif ($status === 'needs_review') {
            $verdict = 'REQUIRES_REVIEW';
        } else {
            $verdict = 'INCORRECT'; // False Match
        }

        // 2. Check if this is a repeat test on the same animal
        $this->CI->db->where('validation_session_id', $session_id);
        $this->CI->db->where('actual_livestock_id', $actual_id);
        $this->CI->db->where('tenant_id', $tenant_id);
        $previous_attempts = $this->CI->db->count_all_results('ai_vision_validation_records');
        $is_repeat = ($previous_attempts > 0) ? 1 : 0;

        $record_data = array(
            'tenant_id'             => $tenant_id,
            'validation_session_id' => $session_id,
            'actual_livestock_id'   => !empty($actual_id) ? (int)$actual_id : null,
            'actual_tag_number'     => !empty($actual_tag) ? $actual_tag : null,
            'ai_livestock_id'       => $ai_id,
            'ai_tag_number'         => $ai_tag,
            'identification_method' => !empty($ai_result['ear_tag_detected']) ? 'ear_tag' : 'visual_features',
            'camera_angle'          => $metadata['camera_angle'] ?? 'FRONT',
            'lighting'              => $metadata['lighting'] ?? 'NORMAL_DAYLIGHT',
            'occlusion'             => $metadata['occlusion'] ?? 'NONE',
            'tag_visibility'        => $metadata['tag_visibility'] ?? 'VISIBLE',
            'ai_confidence'         => (float)($ai_result['confidence_level'] ?? 0.0),
            'is_repeat_test'        => $is_repeat,
            'verification_result'   => $verdict,
            'is_demo_data'          => !empty($metadata['is_demo_data']) ? 1 : 0,
            'raw_ai_response_json'  => json_encode($ai_result),
            'visual_features_json'  => json_encode($ai_result['visual_features'] ?? array()),
            'created_at'            => date('Y-m-d H:i:s')
        );

        $this->CI->db->insert('ai_vision_validation_records', $record_data);

        // Update Session Tallies & Accuracy Metrics
        $this->CI->db->set('total_tested', 'total_tested+1', FALSE);
        if ($verdict === 'CORRECT') {
            $this->CI->db->set('correct_count', 'correct_count+1', FALSE);
        } elseif ($verdict === 'INCORRECT') {
            $this->CI->db->set('incorrect_count', 'incorrect_count+1', FALSE);
        } elseif ($verdict === 'UNKNOWN') {
            $this->CI->db->set('unknown_count', 'unknown_count+1', FALSE);
        } else {
            $this->CI->db->set('needs_review_count', 'needs_review_count+1', FALSE);
        }

        $this->CI->db->where('id', $session_id);
        $this->CI->db->where('tenant_id', $tenant_id);
        $this->CI->db->update('ai_vision_validation_sessions');

        // Recalculate Session Pct Rates
        $session = $this->CI->db->get_where('ai_vision_validation_sessions', array('id' => $session_id, 'tenant_id' => $tenant_id))->row();
        if ($session && $session->total_tested > 0) {
            $total = (int)$session->total_tested;
            $acc   = round(($session->correct_count / $total) * 100, 2);
            $false = round(($session->incorrect_count / $total) * 100, 2);
            $unk   = round(($session->unknown_count / $total) * 100, 2);

            $sample_status = ($total < 20) ? 'INSUFFICIENT DATA' : (($total < 100) ? 'PRELIMINARY' : 'FIELD VALIDATED');

            $this->CI->db->where('id', $session_id);
            $this->CI->db->update('ai_vision_validation_sessions', array(
                'accuracy_pct'    => $acc,
                'false_match_pct' => $false,
                'unknown_pct'     => $unk,
                'sample_status'   => $sample_status
            ));
        }

        return array(
            'status'              => true,
            'verification_result' => $verdict,
            'is_repeat_test'      => (bool)$is_repeat,
            'ai_confidence'       => $ai_result['confidence_level'] ?? 0
        );
    }

    /**
     * Get Complete KulaAI Vision Field Accuracy Analytics & Breakdown
     */
    public function get_validation_analytics($tenant_id = null) {
        $tenant_id = $tenant_id ?: $this->get_tenant_id();

        // 1. Overall Aggregates
        $this->CI->db->select('COUNT(*) as total, 
            SUM(CASE WHEN verification_result = "CORRECT" THEN 1 ELSE 0 END) as correct,
            SUM(CASE WHEN verification_result = "INCORRECT" THEN 1 ELSE 0 END) as incorrect,
            SUM(CASE WHEN verification_result = "UNKNOWN" THEN 1 ELSE 0 END) as unknown,
            SUM(CASE WHEN verification_result = "REQUIRES_REVIEW" THEN 1 ELSE 0 END) as needs_review,
            SUM(CASE WHEN is_repeat_test = 1 AND verification_result = "CORRECT" THEN 1 ELSE 0 END) as repeat_correct,
            SUM(CASE WHEN is_repeat_test = 1 THEN 1 ELSE 0 END) as repeat_total');
        $this->CI->db->where('tenant_id', $tenant_id);
        $totals = $this->CI->db->get('ai_vision_validation_records')->row();

        $total_tests = (int)($totals->total ?? 0);
        $correct     = (int)($totals->correct ?? 0);
        $incorrect   = (int)($totals->incorrect ?? 0);
        $unknown     = (int)($totals->unknown ?? 0);
        $review      = (int)($totals->needs_review ?? 0);
        $rep_total   = (int)($totals->repeat_total ?? 0);
        $rep_correct = (int)($totals->repeat_correct ?? 0);

        $accuracy_rate    = ($total_tests > 0) ? round(($correct / $total_tests) * 100, 1) : 0;
        $false_match_rate = ($total_tests > 0) ? round(($incorrect / $total_tests) * 100, 1) : 0;
        $unknown_rate     = ($total_tests > 0) ? round(($unknown / $total_tests) * 100, 1) : 0;
        $review_rate      = ($total_tests > 0) ? round(($review / $total_tests) * 100, 1) : 0;
        $repeat_accuracy  = ($rep_total > 0)   ? round(($rep_correct / $rep_total) * 100, 1) : 0;

        $sample_status = ($total_tests < 20) ? 'INSUFFICIENT DATA' : (($total_tests < 100) ? 'PRELIMINARY' : 'FIELD VALIDATED');

        // 2. Accuracy by Identification Method (Ear Tag vs Visual Features vs Context)
        $this->CI->db->select('identification_method, COUNT(*) as total,
            SUM(CASE WHEN verification_result = "CORRECT" THEN 1 ELSE 0 END) as correct,
            SUM(CASE WHEN verification_result = "INCORRECT" THEN 1 ELSE 0 END) as incorrect,
            SUM(CASE WHEN verification_result = "UNKNOWN" THEN 1 ELSE 0 END) as unknown');
        $this->CI->db->where('tenant_id', $tenant_id);
        $this->CI->db->group_by('identification_method');
        $method_records = $this->CI->db->get('ai_vision_validation_records')->result_array();

        // 3. Accuracy by Camera Angle
        $this->CI->db->select('camera_angle, COUNT(*) as total,
            SUM(CASE WHEN verification_result = "CORRECT" THEN 1 ELSE 0 END) as correct');
        $this->CI->db->where('tenant_id', $tenant_id);
        $this->CI->db->group_by('camera_angle');
        $angle_records = $this->CI->db->get('ai_vision_validation_records')->result_array();

        // 4. Accuracy by Lighting Condition
        $this->CI->db->select('lighting, COUNT(*) as total,
            SUM(CASE WHEN verification_result = "CORRECT" THEN 1 ELSE 0 END) as correct');
        $this->CI->db->where('tenant_id', $tenant_id);
        $this->CI->db->group_by('lighting');
        $lighting_records = $this->CI->db->get('ai_vision_validation_records')->result_array();

        // 5. Problematic Animals List (<70% Accuracy)
        $problematic_animals = $this->get_problematic_animals($tenant_id);

        return array(
            'sample_status'           => $sample_status,
            'total_tests'             => $total_tests,
            'correct_count'           => $correct,
            'incorrect_count'         => $incorrect,
            'unknown_count'           => $unknown,
            'needs_review_count'      => $review,
            'accuracy_rate'           => $accuracy_rate,
            'false_match_rate'        => $false_match_rate,
            'unknown_rate'            => $unknown_rate,
            'review_rate'             => $review_rate,
            'repeat_accuracy'         => $repeat_accuracy,
            'method_breakdown'        => $method_records,
            'angle_breakdown'         => $angle_records,
            'lighting_breakdown'      => $lighting_records,
            'problematic_animals'     => $problematic_animals
        );
    }

    /**
     * Identify Animals Requiring Attention (< 70% accuracy across attempts)
     */
    public function get_problematic_animals($tenant_id = null, $limit = 10) {
        $tenant_id = $tenant_id ?: $this->get_tenant_id();

        $this->CI->db->select('vr.actual_livestock_id, vr.actual_tag_number, COUNT(*) as attempts,
            SUM(CASE WHEN vr.verification_result = "CORRECT" THEN 1 ELSE 0 END) as correct,
            SUM(CASE WHEN vr.verification_result = "INCORRECT" THEN 1 ELSE 0 END) as incorrect,
            SUM(CASE WHEN vr.verification_result = "UNKNOWN" THEN 1 ELSE 0 END) as unknown');
        $this->CI->db->from('ai_vision_validation_records vr');
        $this->CI->db->where('vr.tenant_id', $tenant_id);
        $this->CI->db->where('vr.actual_livestock_id IS NOT NULL');
        $this->CI->db->group_by('vr.actual_livestock_id');
        $this->CI->db->having('attempts >= 3');
        $this->CI->db->order_by('(correct / attempts)', 'ASC');
        $this->CI->db->limit($limit);
        $rows = $this->CI->db->get()->result_array();

        $problematic = array();
        foreach ($rows as $r) {
            $attempts = (int)$r['attempts'];
            $correct  = (int)$r['correct'];
            $acc      = ($attempts > 0) ? round(($correct / $attempts) * 100, 1) : 0;

            if ($acc < 75.0) {
                $problematic[] = array(
                    'livestock_id' => $r['actual_livestock_id'],
                    'tag_number'   => $r['actual_tag_number'] ?: 'ID #' . $r['actual_livestock_id'],
                    'attempts'     => $attempts,
                    'correct'      => $correct,
                    'incorrect'    => (int)$r['incorrect'],
                    'accuracy_pct' => $acc . '%',
                    'recommendation' => ($r['incorrect'] > $r['unknown']) ? 'Frequently confused with another animal. Update ear tag or record distinct visual markers.' : 'High unreadable rate. Clean or replace ear tag.'
                );
            }
        }

        return $problematic;
    }
}

