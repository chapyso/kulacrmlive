<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ai_planner_service
 * Provider-agnostic tool planning: asks the configured LLM which registered tools are needed
 * for a prompt. Works with any language the model understands. Returns null when the model is
 * unavailable (offline / no key) or returns something unusable, so callers can fall back to
 * the rule-based intent classifier.
 */
class Ai_planner_service {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    /**
     * @param string $prompt       User message
     * @param array  $registry     Tool registry (name => definition)
     * @param object $ai_provider  Ai_provider instance
     * @param array  $chat_history Recent server-side history
     * @return array|null  array('tools' => string[], 'needs_data' => bool) or null
     */
    public function plan($prompt, array $registry, $ai_provider, array $chat_history = array()) {
        $catalog = '';
        foreach ($registry as $name => $def) {
            $catalog .= "- {$name}: {$def['description']}\n";
        }

        $system = "You are a routing component. Decide which read-only business data tools are needed to answer the user's latest message.\n"
            . "Available tools:\n{$catalog}\n"
            . "Rules:\n"
            . "- Choose ONLY from the tool names above; choose none for greetings, small talk, general knowledge, math, or writing tasks.\n"
            . "- Use the conversation history to resolve follow-up questions.\n"
            . "- Reply with ONLY compact JSON, no prose: {\"tools\":[\"tool_name\"],\"needs_data\":true}";

        try {
            $result = $ai_provider->generate($system, $prompt, array(), $chat_history, array('intent' => 'TOOL_PLANNING'));
        } catch (\Throwable $e) {
            log_message('error', 'KulaAI planner failed: ' . $e->getMessage());
            return null;
        }

        $provider = $result['provider'] ?? '';
        if (empty($result['response']) || stripos($provider, 'offline') !== false || stripos($provider, 'rule engine') !== false) {
            return null;
        }

        if (!preg_match('/\{.*\}/s', $result['response'], $m)) {
            return null;
        }
        $decoded = json_decode($m[0], true);
        if (!is_array($decoded) || !isset($decoded['tools']) || !is_array($decoded['tools'])) {
            return null;
        }

        $tools = array();
        foreach ($decoded['tools'] as $t) {
            if (is_string($t) && isset($registry[$t])) {
                $tools[$t] = $t;
            }
        }
        return array('tools' => array_values($tools), 'needs_data' => !empty($tools));
    }
}
