<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * Returns a per-session one-time-style nonce that protects destructive POST
 * endpoints against CSRF. The token persists for the session; pair every
 * sensitive form with a hidden field whose value is action_token() and verify
 * it in the controller via verify_action_token() before mutating data.
 */
function action_token()
{
    $CI =& get_instance();
    $token = $CI->session->userdata('action_token');
    if (!$token) {
        if (function_exists('random_bytes')) {
            $token = bin2hex(random_bytes(16));
        } else {
            $token = bin2hex(openssl_random_pseudo_bytes(16));
        }
        $CI->session->set_userdata('action_token', $token);
    }
    return $token;
}

function verify_action_token()
{
    $CI =& get_instance();
    $expected = $CI->session->userdata('action_token');
    $got = $CI->input->post('action_token');
    if (!$expected || !$got) {
        return FALSE;
    }
    return hash_equals($expected, $got);
}

/**
 * Generate a signed API Bearer token for Mobile / REST clients
 */
function generate_api_token(int $user_id, int $tenant_id, string $email, string $role = 'user', int $ttl_seconds = 2592000): string
{
    $CI =& get_instance();
    $secret = $CI->config->item('encryption_key') ?: 'kulacrm_api_secret_v1_key_2026';
    
    $payload = array(
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
 * Verify an API Bearer token and return payload array or null if invalid
 */
function verify_api_token(string $token): ?array
{
    $CI =& get_instance();
    $secret = $CI->config->item('encryption_key') ?: 'kulacrm_api_secret_v1_key_2026';

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

    if (isset($payload['exp']) && time() > $payload['exp']) {
        return null; // Expired token
    }

    return $payload;
}

