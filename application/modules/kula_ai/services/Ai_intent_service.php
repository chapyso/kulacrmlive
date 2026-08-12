<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ai_intent_service
 * Intent Classification & Context Memory Engine for KulaAI.
 * Evaluates incoming user prompts and conversation history to determine:
 * 1. Intent Category (GREETING, CASUAL_CONVERSATION, FARM_DATA_QUERY, FARM_RECOMMENDATION, etc.)
 * 2. Required KulaCRM Data Tools (if any)
 * 3. Appropriate Response Format (conversational, data_answer, recommendation, analysis, report)
 * 4. Relevant Entities & Context (livestock type, shed, date range, follow-up parameters)
 */
class Ai_intent_service {

    protected $CI;

    // Intent Constants
    const INTENT_GREETING             = 'GREETING';
    const INTENT_CASUAL_CONVERSATION  = 'CASUAL_CONVERSATION';
    const INTENT_GENERAL_QUESTION     = 'GENERAL_QUESTION';
    const INTENT_FARM_DATA_QUERY      = 'FARM_DATA_QUERY';
    const INTENT_LIVESTOCK_QUERY      = 'LIVESTOCK_QUERY';
    const INTENT_ANIMAL_HEALTH_QUERY  = 'ANIMAL_HEALTH_QUERY';
    const INTENT_VACCINATION_QUERY    = 'VACCINATION_QUERY';
    const INTENT_BREEDING_QUERY       = 'BREEDING_QUERY';
    const INTENT_FEED_QUERY           = 'FEED_QUERY';
    const INTENT_MORTALITY_QUERY      = 'MORTALITY_QUERY';
    const INTENT_FINANCIAL_QUERY      = 'FINANCIAL_QUERY';
    const INTENT_SALES_QUERY          = 'SALES_QUERY';
    const INTENT_EXPENSE_QUERY        = 'EXPENSE_QUERY';
    const INTENT_INVENTORY_QUERY      = 'INVENTORY_QUERY';
    const INTENT_REPORT_REQUEST       = 'REPORT_REQUEST';
    const INTENT_FARM_ANALYSIS        = 'FARM_ANALYSIS';
    const INTENT_FARM_RECOMMENDATION  = 'FARM_RECOMMENDATION';
    const INTENT_DOCUMENT_ANALYSIS    = 'DOCUMENT_ANALYSIS';
    const INTENT_SYSTEM_HELP          = 'SYSTEM_HELP';
    const INTENT_ACTION_REQUEST       = 'ACTION_REQUEST';
    const INTENT_FOLLOW_UP            = 'FOLLOW_UP';
    const INTENT_UNKNOWN              = 'UNKNOWN';

    public function __construct() {
        $this->CI = get_instance();
    }

    /**
     * Analyze user prompt + history to produce structured Intent Object
     *
     * @param string $prompt           User input text
     * @param array  $chat_history     Array of previous messages [{role:'user'|'ai', content:''}]
     * @return array                   Parsed intent payload
     */
    public function classify_intent($prompt, $chat_history = array()) {
        $clean_prompt = trim($prompt);
        $p = strtolower($clean_prompt);

        // 1. Check for Greetings
        if ($this->is_greeting($p)) {
            return array(
                'intent'          => self::INTENT_GREETING,
                'response_type'   => 'conversational',
                'requires_data'   => false,
                'tools'           => array(),
                'entities'        => array()
            );
        }

        // 2. Check for Casual Conversation & Polite Small Talk
        if ($this->is_casual_chat($p)) {
            return array(
                'intent'          => self::INTENT_CASUAL_CONVERSATION,
                'response_type'   => 'conversational',
                'requires_data'   => false,
                'tools'           => array(),
                'entities'        => array()
            );
        }

        // 3. Check for System Help & Bot Guidance
        if (preg_match('/^(what can you do|how do you work|help me|what are your features|commands|menu|options)$/i', $p) || strpos($p, 'what can you help') !== false) {
            return array(
                'intent'          => self::INTENT_SYSTEM_HELP,
                'response_type'   => 'informational',
                'requires_data'   => false,
                'tools'           => array(),
                'entities'        => array()
            );
        }

        // 4. Check for Contextual Follow-Up Questions (e.g. "How many are female?", "What about last month?")
        $context_entities = $this->extract_context_from_history($chat_history);
        if (!empty($context_entities) && $this->is_follow_up($p)) {
            $inherited_intent = $this->resolve_follow_up_intent($p, $context_entities);
            return array(
                'intent'          => self::INTENT_FOLLOW_UP,
                'sub_intent'      => $inherited_intent['intent'],
                'response_type'   => 'data_answer',
                'requires_data'   => true,
                'tools'           => $inherited_intent['tools'],
                'entities'        => array_merge($context_entities, $inherited_intent['entities'])
            );
        }

        // Extract entities (livestock types, gender, dates, metrics)
        $entities = $this->extract_entities($p);

        // 5. Explicit Recommendation / Action Plan Requests
        if (preg_match('/(recommend|recommendation|advise|strategy|how to reduce|how can i reduce|how to optimize|how can i optimize|action plan|steps to)/i', $p)) {
            $tools = $this->determine_tools_from_entities($entities, $p);
            return array(
                'intent'          => self::INTENT_FARM_RECOMMENDATION,
                'response_type'   => 'recommendation',
                'requires_data'   => !empty($tools),
                'tools'           => $tools,
                'entities'        => $entities
            );
        }

        // 6. Explicit Farm Performance Analysis Requests
        if (preg_match('/(analyze|analysis|performance|audit|why did|why are|root cause|investigate|trend)/i', $p)) {
            $tools = $this->determine_tools_from_entities($entities, $p);
            return array(
                'intent'          => self::INTENT_FARM_ANALYSIS,
                'response_type'   => 'analysis',
                'requires_data'   => true,
                'tools'           => !empty($tools) ? $tools : array('get_farm_summary', 'get_batch_summary'),
                'entities'        => $entities
            );
        }

        // 7. Formal Report Generation Requests
        if (preg_match('/(generate|create|build|download|export) (a |the )?(farm |monthly |weekly |annual |production |financial )?report/i', $p)) {
            return array(
                'intent'          => self::INTENT_REPORT_REQUEST,
                'response_type'   => 'report',
                'requires_data'   => true,
                'tools'           => array('get_farm_summary', 'get_batch_summary', 'get_financial_summary'),
                'entities'        => $entities
            );
        }

        // 8. Actionable Daily Task Planning Requests ("What should I do today?")
        if (preg_match('/(what should i do|today\'s tasks|daily routine|prioritize today|what to do today|schedule for today)/i', $p)) {
            return array(
                'intent'          => self::INTENT_ACTION_REQUEST,
                'response_type'   => 'conversational',
                'requires_data'   => true,
                'tools'           => array('get_upcoming_vaccinations', 'get_inventory_forecast_data', 'get_batch_summary'),
                'entities'        => $entities
            );
        }

        // 9. Specific Domain Queries
        // Animal Mortality
        if (preg_match('/(mortality|death|died|dead|die|dying|kill|killed)/i', $p)) {
            return array(
                'intent'          => self::INTENT_MORTALITY_QUERY,
                'response_type'   => 'data_answer',
                'requires_data'   => true,
                'tools'           => array('get_batch_summary', 'get_batch_mortality'),
                'entities'        => $entities
            );
        }

        // Animal Health & Symptoms
        if (preg_match('/(sick|disease|illness|symptom|newcastle|gumboro|pox|diarrhea|cough|fever|infection|treatment|medicine|veterinary)/i', $p)) {
            return array(
                'intent'          => self::INTENT_ANIMAL_HEALTH_QUERY,
                'response_type'   => (strpos($p, 'my ') !== false || strpos($p, 'our ') !== false) ? 'data_answer' : 'informational',
                'requires_data'   => (strpos($p, 'my ') !== false || strpos($p, 'our ') !== false || strpos($p, 'farm') !== false),
                'tools'           => array('get_batch_summary', 'get_upcoming_vaccinations'),
                'entities'        => $entities
            );
        }

        // Vaccinations
        if (preg_match('/(vaccin|vac|dose|injection|schedule|overdue)/i', $p)) {
            return array(
                'intent'          => self::INTENT_VACCINATION_QUERY,
                'response_type'   => 'data_answer',
                'requires_data'   => true,
                'tools'           => array('get_upcoming_vaccinations'),
                'entities'        => $entities
            );
        }

        // Breeding & Pregnancy
        if (preg_match('/(pregnant|preg|breed|in-calf|gestation|mating|birth|kid|lamb|calve|kidding)/i', $p)) {
            return array(
                'intent'          => self::INTENT_BREEDING_QUERY,
                'response_type'   => 'data_answer',
                'requires_data'   => true,
                'tools'           => array('get_farm_summary'),
                'entities'        => $entities
            );
        }

        // Feed & Inventory
        if (preg_match('/(feed|food|mash|pellet|silage|napier|consumption|stock|inventory|run out|deplete)/i', $p)) {
            return array(
                'intent'          => self::INTENT_FEED_QUERY,
                'response_type'   => 'data_answer',
                'requires_data'   => true,
                'tools'           => array('get_inventory_forecast_data'),
                'entities'        => $entities
            );
        }

        // Financial, Expenses & Sales
        if (preg_match('/(spend|spent|expense|cost|financial|revenue|profit|income|earned|cash|money|balance|owe|debt|debtor|creditor)/i', $p)) {
            $intent = self::INTENT_FINANCIAL_QUERY;
            if (preg_match('/(expense|spend|spent|cost)/i', $p)) $intent = self::INTENT_EXPENSE_QUERY;
            if (preg_match('/(sale|revenue|income|earned)/i', $p)) $intent = self::INTENT_SALES_QUERY;

            $tools = array('get_financial_summary', 'get_expenses');
            if (preg_match('/(owe|debt|debtor|client)/i', $p)) $tools[] = 'get_client_balances';
            if (preg_match('/(creditor|supplier|vendor)/i', $p)) $tools[] = 'get_supplier_balances';

            return array(
                'intent'          => $intent,
                'response_type'   => 'data_answer',
                'requires_data'   => true,
                'tools'           => $tools,
                'entities'        => $entities
            );
        }

        // Livestock Count & Inventory Queries
        if (preg_match('/(how many|count|number of|total|list|show me|do i have|do we have) (animal|goat|chicken|poultry|cow|cattle|pig|sheep|bird|stock|batch|shed)/i', $p) ||
            preg_match('/^(how many|total) (goats|chickens|cows|pigs|sheep|animals|birds|batches|sheds)\??$/i', $p)) {
            return array(
                'intent'          => self::INTENT_FARM_DATA_QUERY,
                'response_type'   => 'data_answer',
                'requires_data'   => true,
                'tools'           => array('get_farm_summary', 'get_batch_summary'),
                'entities'        => $entities
            );
        }

        // 10. General Agribusiness or Educational Question
        if (preg_match('/^(what is|what are|explain|define|how does|why is|difference between)/i', $p)) {
            return array(
                'intent'          => self::INTENT_GENERAL_QUESTION,
                'response_type'   => 'informational',
                'requires_data'   => false,
                'tools'           => array(),
                'entities'        => $entities
            );
        }

        // Default: Open-ended Assistant Query
        return array(
            'intent'          => self::INTENT_UNKNOWN,
            'response_type'   => 'conversational',
            'requires_data'   => false,
            'tools'           => array(),
            'entities'        => $entities
        );
    }

    /**
     * Check if prompt is a simple greeting (English, Luganda, Swahili)
     */
    protected function is_greeting($p) {
        $greetings = array(
            'hey', 'hello', 'hi', 'hey there', 'hello there', 'hi there',
            'good morning', 'good afternoon', 'good evening', 'greetings',
            'habari', 'jambo', 'mambo', 'habari gani', 'oli otya', 'gyebale',
            'ki kati', 'kikati', 'webale', 'slm', 'salam'
        );
        $p_strip = trim(preg_replace('/[^a-z\s]/', '', $p));
        return in_array($p_strip, $greetings);
    }

    /**
     * Check if prompt is casual conversation or polite remark (English, Luganda, Swahili)
     */
    protected function is_casual_chat($p) {
        $casual = array(
            'how are you', 'how are you doing', 'how do you do', 'whats up', "what's up",
            'thank you', 'thanks', 'thanks a lot', 'thank you very much', 'thx', 'webale nnyo', 'asante', 'asante sana',
            'okay', 'ok', 'cool', 'awesome', 'great', 'perfect', 'got it', 'understood', 'kale', 'sawa',
            'who are you', 'what is your name', 'nice to meet you', 'bye', 'goodbye', 'see you', 'kwaheri'
        );
        $p_strip = trim(preg_replace('/[^a-z\s\']/ ', '', $p));
        foreach ($casual as $phrase) {
            if ($p_strip === $phrase || strpos($p_strip, $phrase) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if prompt is a follow-up query relying on prior message context
     */
    protected function is_follow_up($p) {
        $follow_up_patterns = array(
            '/^(how many (of those|of them) (are|were))/i',
            '/^(how many are|which ones are|how much of that)/i',
            '/^(what about|and what about|how about)/i',
            '/^(how many female|how many male|how many pregnant|how many died)/i',
            '/^(last month\??|this month\??|yesterday\??|today\??)$/i',
            '/^(ziri mmeka|zimeka|pacha gani)\??$/i'
        );
        foreach ($follow_up_patterns as $pattern) {
            if (preg_match($pattern, $p)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Extract context entities from previous conversation history
     */
    protected function extract_context_from_history($history) {
        if (empty($history) || !is_array($history)) {
            return array();
        }

        $context = array();
        // Traverse recent messages backwards
        $recent = array_slice($history, -6);
        foreach (array_reverse($recent) as $msg) {
            $txt = is_array($msg) ? ($msg['content'] ?? ($msg['prompt'] ?? '')) : (string)$msg;
            $extracted = $this->extract_entities(strtolower($txt));
            if (!empty($extracted['species']) && empty($context['species'])) {
                $context['species'] = $extracted['species'];
            }
            if (!empty($extracted['shed']) && empty($context['shed'])) {
                $context['shed'] = $extracted['shed'];
            }
            if (!empty($extracted['topic']) && empty($context['topic'])) {
                $context['topic'] = $extracted['topic'];
            }
        }
        return $context;
    }

    /**
     * Resolve intent for follow-up message using context entities
     */
    protected function resolve_follow_up_intent($p, $context) {
        $tools = array('get_farm_summary', 'get_batch_summary');
        if (!empty($context['topic']) && $context['topic'] === 'finance') {
            $tools = array('get_financial_summary', 'get_expenses');
        }

        return array(
            'intent'   => self::INTENT_FARM_DATA_QUERY,
            'tools'    => $tools,
            'entities' => $context
        );
    }

    /**
     * Extract key domain entities from input (English, Luganda, Swahili)
     */
    public function extract_entities($p) {
        $entities = array();

        // Species / Animal Types (English / Luganda / Swahili)
        if (preg_match('/(goat|goats|caprine|embuzi|mbuzi)/i', $p)) $entities['species'] = 'Goats';
        elseif (preg_match('/(chicken|chickens|poultry|hen|hens|broiler|layer|bird|birds|enkoko|kuku)/i', $p)) $entities['species'] = 'Poultry';
        elseif (preg_match('/(cow|cows|cattle|bovine|bull|heifer|calf|calves|ente|ng\'ombe|ngombe)/i', $p)) $entities['species'] = 'Cattle';
        elseif (preg_match('/(pig|pigs|swine|sow|boar|piglet|embizzi|empizi|nguruwe)/i', $p)) $entities['species'] = 'Pigs';
        elseif (preg_match('/(sheep|lamb|lambs|ram|ewe|endiga|kondoo)/i', $p)) $entities['species'] = 'Sheep';

        // Gender / Reproductive State
        if (preg_match('/(female|females|doe|nanny|hen|cow|sow|ewe|enkazi|jike)/i', $p)) $entities['gender'] = 'Female';
        elseif (preg_match('/(male|males|buck|billy|rooster|cock|bull|boar|ram|ensekurume|dume)/i', $p)) $entities['gender'] = 'Male';
        if (preg_match('/(pregnant|in-calf|gestating|eliko olubuto|mimba)/i', $p)) $entities['state'] = 'Pregnant';

        // Timeframe
        if (strpos($p, 'this month') !== false || strpos($p, 'omwezi guno') !== false || strpos($p, 'mwezi huu') !== false) $entities['timeframe'] = 'this_month';
        elseif (strpos($p, 'last month') !== false || strpos($p, 'omwezi oguwedde') !== false || strpos($p, 'mwezi uliopita') !== false) $entities['timeframe'] = 'last_month';
        elseif (strpos($p, 'this week') !== false || strpos($p, 'awiki eno') !== false || strpos($p, 'wiki hii') !== false) $entities['timeframe'] = 'this_week';
        elseif (strpos($p, 'today') !== false || strpos($p, 'leero') !== false || strpos($p, 'leo') !== false) $entities['timeframe'] = 'today';

        // Financial Topic
        if (preg_match('/(expense|spend|spent|cost|feed cost|ssente|ensimbi|pesa|gharama)/i', $p)) $entities['topic'] = 'finance';

        return $entities;
    }

    /**
     * Map extracted entities to required KulaCRM data tools
     */
    protected function determine_tools_from_entities($entities, $p) {
        $tools = array();
        if (isset($entities['topic']) && $entities['topic'] === 'finance') {
            $tools[] = 'get_financial_summary';
            $tools[] = 'get_expenses';
        }
        if (preg_match('/(mortality|death|died|dead)/i', $p)) {
            $tools[] = 'get_batch_summary';
            $tools[] = 'get_batch_mortality';
        }
        if (preg_match('/(feed|food|inventory|stock)/i', $p)) {
            $tools[] = 'get_inventory_forecast_data';
        }
        if (preg_match('/(vaccin|dose|schedule)/i', $p)) {
            $tools[] = 'get_upcoming_vaccinations';
        }
        if (empty($tools)) {
            $tools[] = 'get_farm_summary';
            $tools[] = 'get_batch_summary';
        }
        return array_unique($tools);
    }
}
