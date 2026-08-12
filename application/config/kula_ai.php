<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| KulaAI Intelligence Layer Configuration
|--------------------------------------------------------------------------
*/

$config['kula_ai_enabled'] = TRUE;
$config['kula_ai_default_provider'] = 'gemini'; // Options: 'gemini', 'openai', 'groq', 'ollama'

// Provider Configurations
$config['kula_ai_providers'] = [
    'gemini' => [
        'api_key' => getenv('GEMINI_API_KEY') ?: getenv('PALM_API_KEY') ?: '',
        'model'   => 'gemini-flash-latest',
        'endpoint'=> 'https://generativelanguage.googleapis.com/v1beta/models/'
    ],
    'openai' => [
        'api_key' => getenv('OPENAI_API_KEY') ?: '',
        'model'   => 'gpt-4o-mini',
        'endpoint'=> 'https://api.openai.com/v1/chat/completions'
    ],
    'groq' => [
        'api_key' => getenv('GROQ_API_KEY') ?: '',
        'model'   => 'llama-3.3-70b-versatile',
        'endpoint'=> 'https://api.groq.com/openai/v1/chat/completions'
    ],
    'ollama' => [
        'base_url'=> 'http://localhost:11434/api/chat',
        'model'   => 'llama3.2'
    ]
];

// Operational & Safety Parameters
$config['kula_ai_max_tokens'] = 2000;
$config['kula_ai_temperature'] = 0.2; // Low temperature for factual precision
$config['kula_ai_enable_audit_log'] = TRUE;
$config['kula_ai_allow_fallback_summary'] = TRUE; // Graceful statistical fallback if provider API key is unconfigured
