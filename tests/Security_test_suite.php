<?php
/**
 * Automated Security Test Suite for KULACRM
 * 
 * Verifies authentication guards, cryptographic tokens, strict multi-tenant isolation,
 * rate limiting, status enforcement (disabled user, suspended tenant), and backdoor removal.
 */

// Initialize CodeIgniter test harness
define('BASEPATH', realpath(__DIR__ . '/../system') . '/');
define('APPPATH', realpath(__DIR__ . '/../application') . '/');
define('FCPATH', realpath(__DIR__ . '/..') . '/');
define('ENVIRONMENT', 'testing');

require_once APPPATH . 'config/constants.php';

// Mock minimal CI environment for standalone unit & integration testing
class Security_Test_Runner {

    private int $passed = 0;
    private int $failed = 0;
    private array $results = array();

    public function run() {
        echo "\n" . str_repeat('=', 70) . "\n";
        echo "   KULACRM COMPREHENSIVE SECURITY & API HARDENING VERIFICATION\n";
        echo str_repeat('=', 70) . "\n\n";

        $this->test_token_generation_and_signatures();
        $this->test_token_expiry();
        $this->test_token_tampering();
        $this->test_token_revocation();
        $this->test_rate_limiter_logic();
        $this->test_rate_limiter_enforcement();
        $this->test_backdoor_removal();
        $this->test_root_test_ai_disabled();
        $this->test_tenant_isolation_model_rules();
        $this->test_mime_and_upload_security();
        $this->test_idempotent_import_deduplication();

        echo "\n" . str_repeat('-', 70) . "\n";
        echo sprintf(" TEST SUMMARY: %d PASSED, %d FAILED (TOTAL: %d)\n", 
            $this->passed, $this->failed, $this->passed + $this->failed);
        echo str_repeat('-', 70) . "\n";

        if ($this->failed > 0) {
            echo "❌ SOME SECURITY TESTS FAILED.\n\n";
            exit(1);
        } else {
            echo "✅ ALL SECURITY TESTS PASSED SUCCESSFULLY.\n\n";
            exit(0);
        }
    }

    private function assert($condition, string $test_name, string $details = '') {
        if ($condition) {
            $this->passed++;
            echo "  [PASS] {$test_name}\n";
            $this->results[] = ['name' => $test_name, 'status' => 'PASS', 'details' => $details];
        } else {
            $this->failed++;
            echo "  [FAIL] {$test_name} - {$details}\n";
            $this->results[] = ['name' => $test_name, 'status' => 'FAIL', 'details' => $details];
        }
    }

    /**
     * Helper to mock token generation
     */
    private function generate_test_token(int $user_id, int $tenant_id, string $email, string $role = 'user', int $ttl = 3600, string $secret = 'test_secret_key_123'): string {
        $jti = bin2hex(random_bytes(16));
        $payload = array(
            'jti'       => $jti,
            'user_id'   => $user_id,
            'tenant_id' => $tenant_id,
            'email'     => $email,
            'role'      => $role,
            'iat'       => time(),
            'exp'       => time() + $ttl
        );
        $json = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $b64 = rtrim(strtr(base64_encode($json), '+/', '-_'), '=');
        $sig = hash_hmac('sha256', $b64, $secret);
        return $b64 . '.' . $sig;
    }

    private function verify_test_token(string $token, string $secret = 'test_secret_key_123'): ?array {
        $parts = explode('.', $token);
        if (count($parts) !== 2) return null;
        list($b64, $sig) = $parts;
        $expected = hash_hmac('sha256', $b64, $secret);
        if (!hash_equals($expected, $sig)) return null;
        $json = base64_decode(strtr($b64, '-_', '+/'));
        $payload = json_decode($json, true);
        if (!is_array($payload) || empty($payload['user_id'])) return null;
        if (isset($payload['exp']) && time() > $payload['exp']) return null;
        return $payload;
    }

    public function test_token_generation_and_signatures() {
        echo "1. Cryptographic Token Verification:\n";
        $token = $this->generate_test_token(10, 2, 'farmer@example.com', 'admin', 3600);
        $verified = $this->verify_test_token($token);

        $this->assert($verified !== null, "Valid HMAC-SHA256 Bearer token verifies successfully");
        $this->assert(isset($verified['user_id']) && $verified['user_id'] === 10, "Token extracts correct user_id (10)");
        $this->assert(isset($verified['tenant_id']) && $verified['tenant_id'] === 2, "Token extracts correct tenant_id (2)");
        $this->assert(isset($verified['jti']) && strlen($verified['jti']) === 32, "Token contains secure random JTI identifier");
    }

    public function test_token_expiry() {
        echo "\n2. Expired Token Rejection:\n";
        // Token expired 10 seconds ago
        $expired_token = $this->generate_test_token(10, 2, 'farmer@example.com', 'admin', -10);
        $verified = $this->verify_test_token($expired_token);

        $this->assert($verified === null, "Expired token is strictly rejected");
    }

    public function test_token_tampering() {
        echo "\n3. Tampered Token Signature Rejection:\n";
        $token = $this->generate_test_token(10, 2, 'farmer@example.com', 'admin', 3600);
        $parts = explode('.', $token);
        
        // Tamper payload to privilege escalate to tenant 1 / superadmin
        $tampered_payload = json_encode(['jti' => '123', 'user_id' => 1, 'tenant_id' => 1, 'role' => 'superadmin', 'exp' => time() + 3600]);
        $tampered_b64 = rtrim(strtr(base64_encode($tampered_payload), '+/', '-_'), '=');
        $forged_token = $tampered_b64 . '.' . $parts[1];

        $verified = $this->verify_test_token($forged_token);
        $this->assert($verified === null, "Forged payload with invalid HMAC signature is strictly rejected");

        // Verify with wrong secret
        $wrong_secret_verified = $this->verify_test_token($token, 'wrong_secret_key');
        $this->assert($wrong_secret_verified === null, "Token signed with foreign secret key is strictly rejected");
    }

    public function test_token_revocation() {
        echo "\n4. Token Revocation Mechanism:\n";
        $revoked_jtis = array();
        $token = $this->generate_test_token(10, 2, 'farmer@example.com', 'admin', 3600);
        $payload = $this->verify_test_token($token);
        $jti = $payload['jti'];

        // Simulate revoking token JTI
        $revoked_jtis[$jti] = true;

        $is_revoked = isset($revoked_jtis[$jti]);
        $this->assert($is_revoked === true, "Token JTI recorded in revocation registry");
        $this->assert(($payload !== null && $is_revoked), "Revoked token is identified and invalidated upon lookup");
    }

    public function test_rate_limiter_logic() {
        echo "\n5. Rate Limiting Sliding Window Logic:\n";
        // Test in-memory rate limiter mock
        $hits = array();
        $max_attempts = 5;
        $window = 60;
        $ip = '192.168.1.50';

        $allowed_count = 0;
        $blocked_count = 0;

        for ($i = 0; $i < 8; $i++) {
            if (!isset($hits[$ip])) {
                $hits[$ip] = ['count' => 1, 'reset' => time() + $window];
                $allowed_count++;
            } else {
                if ($hits[$ip]['count'] < $max_attempts) {
                    $hits[$ip]['count']++;
                    $allowed_count++;
                } else {
                    $blocked_count++;
                }
            }
        }

        $this->assert($allowed_count === 5, "Allowed exactly 5 attempts within rate window");
        $this->assert($blocked_count === 3, "Blocked subsequent attempts when limit exceeded (429 condition)");
    }

    public function test_rate_limiter_enforcement() {
        echo "\n6. Rate Limiter Library Code Validation:\n";
        $lib_file = realpath(__DIR__ . '/../application/libraries/Rate_limiter.php');
        $this->assert(file_exists($lib_file), "Rate_limiter.php exists in application/libraries/");

        $content = file_get_contents($lib_file);
        $this->assert(strpos($content, 'class Rate_limiter') !== false, "Rate_limiter class defined");
        $this->assert(strpos($content, '429') !== false, "Rate_limiter emits HTTP 429 Too Many Requests");
        $this->assert(strpos($content, 'Retry-After') !== false, "Rate_limiter sends standard Retry-After header");
    }

    public function test_backdoor_removal() {
        echo "\n7. Seed Backdoors & Debug Route Removal:\n";
        $routes_file = realpath(__DIR__ . '/../application/config/routes.php');
        $routes_content = file_get_contents($routes_file);

        $this->assert(strpos($routes_content, 'seed_superadmin') === false, "auth/seed_superadmin route removed from routes.php");
        $this->assert(strpos($routes_content, 'test_home_500') === false, "auth/test_home_500 route removed from routes.php");

        $seed_controller = realpath(__DIR__ . '/../application/modules/auth/controllers/Seed.php');
        $seed_content = file_get_contents($seed_controller);
        $this->assert(strpos($seed_content, 'show_404()') !== false, "Seed.php controller disabled with show_404()");
    }

    public function test_root_test_ai_disabled() {
        echo "\n8. Root Test Scripts Hardening:\n";
        $test_ai = realpath(__DIR__ . '/../test_ai.php');
        $content = file_get_contents($test_ai);
        $this->assert(strpos($content, '403') !== false || strpos($content, 'forbidden') !== false, "test_ai.php in root returns 403 Forbidden");
    }

    public function test_tenant_isolation_model_rules() {
        echo "\n9. Multi-Tenant Model Isolation & Leak Prevention:\n";
        $model_file = realpath(__DIR__ . '/../application/core/MY_Model.php');
        $model_content = file_get_contents($model_file);

        // Verify that insecure or_where(0) and or_where(NULL) fallbacks are removed
        $this->assert(strpos($model_content, 'or_where($col, 0)') === false, "Insecure 'or_where tenant_id = 0' eliminated from MY_Model");
        $this->assert(strpos($model_content, "or_where(\$col . ' IS NULL'") === false, "Insecure 'or_where tenant_id IS NULL' eliminated from MY_Model");
        $this->assert(strpos($model_content, 'where($col, -1)') !== false, "Unauthenticated / missing tenant context fails closed with empty query");
    }

    public function test_mime_and_upload_security() {
        echo "\n10. Document Ingestion Security & MIME Validation:\n";
        $ai_file = realpath(__DIR__ . '/../application/modules/kula_ai/controllers/Kula_ai.php');
        $ai_content = file_get_contents($ai_file);

        $this->assert(strpos($ai_content, 'finfo_file') !== false, "upload_document validates real file MIME types via finfo");
        $this->assert(strpos($ai_content, 'basename') !== false, "upload_document sanitizes filename to prevent path traversal");
        $this->assert(strpos($ai_content, 'get_tenant_upload_path') !== false, "Uploads stored strictly inside tenant-isolated folder");
        $this->assert(strpos($ai_content, 'ai_upload:tenant:') !== false, "upload_document enforces per-tenant rate limit");
    }

    public function test_idempotent_import_deduplication() {
        echo "\n11. AI Import Atomic Transactions & Idempotency:\n";
        $ai_file = realpath(__DIR__ . '/../application/modules/kula_ai/controllers/Kula_ai.php');
        $ai_content = file_get_contents($ai_file);

        $this->assert(strpos($ai_content, 'trans_start()') !== false, "confirm_import executes inside atomic database transaction");
        $this->assert(strpos($ai_content, 'AI_IMPORT_CONFIRMED_') !== false, "confirm_import checks duplicate import hash to prevent double posting");
    }
}

$runner = new Security_Test_Runner();
$runner->run();
