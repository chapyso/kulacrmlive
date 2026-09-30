<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Animal registry: one row per physical animal (name, tag, mother, shed/batch).
 * Herd size still comes from purchases/births/sales/deaths; this table lets the farm
 * name and track each head, and is what production and KulaAI Vision attach to.
 */
class Animal_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->ensure_schema();
    }

    private function ensure_schema()
    {
        if (!$this->db->table_exists('livestock_animal')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `livestock_animal` (
                `an_id` int(11) NOT NULL AUTO_INCREMENT,
                `tenant_id` int(11) NOT NULL DEFAULT 1,
                `an_ls_id` int(11) NOT NULL DEFAULT 0,
                `an_lst_id` int(11) NOT NULL DEFAULT 0,
                `an_name` varchar(100) NOT NULL DEFAULT '',
                `an_tag` varchar(50) NOT NULL DEFAULT '',
                `an_sex` varchar(1) NOT NULL DEFAULT '',
                `an_mother_id` int(11) NOT NULL DEFAULT 0,
                `an_shed_id` int(11) NOT NULL DEFAULT 0,
                `an_batch_id` int(11) NOT NULL DEFAULT 0,
                `an_origin` varchar(20) NOT NULL DEFAULT 'registered',
                `an_lrp_id` int(11) NOT NULL DEFAULT 0,
                `an_last_seen_at` datetime DEFAULT NULL,
                `an_status` int(11) NOT NULL DEFAULT 1,
                `an_created_by` int(11) NOT NULL DEFAULT 0,
                `an_created_at` datetime NOT NULL DEFAULT current_timestamp(),
                `an_updated_by` int(11) NOT NULL DEFAULT 0,
                `an_updated_at` datetime NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`an_id`),
                KEY `idx_an_tenant` (`tenant_id`, `an_status`),
                KEY `idx_an_tag` (`tenant_id`, `an_tag`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        } else {
            // Table created by an earlier revision: add whatever is missing
            $cols = array(
                'an_tag' => "varchar(50) NOT NULL DEFAULT ''",
                'an_sex' => "varchar(1) NOT NULL DEFAULT ''",
                'an_mother_id' => "int(11) NOT NULL DEFAULT 0",
                'an_shed_id' => "int(11) NOT NULL DEFAULT 0",
                'an_batch_id' => "int(11) NOT NULL DEFAULT 0",
                'an_origin' => "varchar(20) NOT NULL DEFAULT 'registered'",
                'an_lrp_id' => "int(11) NOT NULL DEFAULT 0",
                'an_last_seen_at' => "datetime DEFAULT NULL",
            );
            foreach ($cols as $col => $def) {
                if (!$this->db->field_exists($col, 'livestock_animal')) {
                    $this->db->query("ALTER TABLE `livestock_animal` ADD COLUMN `$col` $def");
                }
            }
        }
        if ($this->db->table_exists('product_stock') && !$this->db->field_exists('prs_animal_id', 'product_stock')) {
            $this->db->query("ALTER TABLE `product_stock` ADD COLUMN `prs_animal_id` int(11) NOT NULL DEFAULT 0");
        }
    }

    private function actor()
    {
        $u = $this->ion_auth->user()->row();
        return $u ? (int) $u->user_id : 0;
    }

    /* ============================ Herd numbers ============================ */

    private function groupedSum($table, $lsCol, $lstCol, $qtyCol, $conds, $joins = array())
    {
        $this->scope_tenant($table);
        foreach ($joins as $j) {
            $this->db->join($j[0], $j[1]);
        }
        foreach ($conds as $k => $v) {
            $this->db->where($k, $v);
        }
        $this->db->select("$lsCol AS ls, $lstCol AS lst, SUM($qtyCol) AS qty", false);
        $this->db->group_by(array($lsCol, $lstCol));
        $out = array();
        foreach ($this->db->get($table)->result() as $r) {
            $out[(int) $r->ls . '-' . (int) $r->lst] = (float) $r->qty;
        }
        return $out;
    }

    /**
     * One row per livestock + variant: herd size (purchased + born - sold - dead),
     * how many have a record, how many are named, and how many are still to name.
     */
    public function getHerdSummary()
    {
        $bought = $this->groupedSum('livestock_purchase_value', 'purv_ls_id', 'purv_lst_id', 'purv_quantity',
            array('purv_status' => 1, 'livestock_purchase_summary.purs_status' => 1),
            array(array('livestock_purchase_summary', 'livestock_purchase_summary.purs_id = livestock_purchase_value.purv_purs_id')));
        $born = $this->groupedSum('livestock_reproduction', 'lrp_ls_id', 'lrp_lst_id', 'lrp_birth_quantity', array('lrp_status' => 1));
        $sold = $this->groupedSum('livestock_sale_value', 'lssv_ls_id', 'lssv_lst_id', 'lssv_quantity',
            array('lssv_status' => 1, 'livestock_sale_summary.lsss_status' => 1),
            array(array('livestock_sale_summary', 'livestock_sale_summary.lsss_id = livestock_sale_value.lssv_lsss_id')));
        $dead = $this->groupedSum('livestock_death_quantity', 'ld_purv_ls_id', 'ld_purv_lst_id', 'ld_death_quantity', array('ld_status' => 1));

        $this->scope_tenant('livestock_animal');
        $this->db->select("an_ls_id AS ls, an_lst_id AS lst, COUNT(*) AS registered, SUM(an_name <> '') AS named", false);
        $this->db->where('an_status', 1);
        $this->db->group_by(array('an_ls_id', 'an_lst_id'));
        $reg = array();
        foreach ($this->db->get('livestock_animal')->result() as $r) {
            $reg[(int) $r->ls . '-' . (int) $r->lst] = array((int) $r->registered, (int) $r->named);
        }

        $keys = array_unique(array_merge(array_keys($bought), array_keys($born), array_keys($reg)));
        $rows = array();
        foreach ($keys as $k) {
            list($ls, $lst) = array_map('intval', explode('-', $k));
            $stock = (int) max(0, floor(($bought[$k] ?? 0) + ($born[$k] ?? 0) - ($sold[$k] ?? 0) - ($dead[$k] ?? 0)));
            $registered = $reg[$k][0] ?? 0;
            $named = $reg[$k][1] ?? 0;
            $lsRow = $this->db->get_where('livestock', array('ls_id' => $ls))->row();
            $lstRow = $lst ? $this->db->get_where('livestock_type', array('lst_id' => $lst))->row() : null;
            $rows[] = (object) array(
                'ls_id' => $ls, 'lst_id' => $lst,
                'ls_name' => $lsRow ? $lsRow->ls_name : 'Livestock #' . $ls,
                'lst_title' => $lstRow ? $lstRow->lst_title : '',
                'stock' => $stock,
                'registered' => $registered,
                'named' => $named,
                'unregistered' => max(0, $stock - $registered),
                'not_named' => max($stock, $registered) - $named,
            );
        }
        usort($rows, function ($a, $b) {
            return strcmp($a->ls_name . $a->lst_title, $b->ls_name . $b->lst_title);
        });
        return $rows;
    }

    /* ============================ Registry ============================ */

    private function nextTagNumber()
    {
        $this->scope_tenant('livestock_animal');
        $this->db->select('an_tag');
        $this->db->where("an_tag REGEXP '^[0-9]+$'", null, false);
        $max = 0;
        foreach ($this->db->get('livestock_animal')->result() as $r) {
            $max = max($max, (int) $r->an_tag);
        }
        return $max + 1;
    }

    /** Create $count blank records (auto tag 001, 002...) for a livestock + variant. Returns number created. */
    public function generate($ls_id, $lst_id, $count, $shed_id = 0, $batch_id = 0)
    {
        $count = (int) $count;
        if ($count < 1) {
            return 0;
        }
        $count = min($count, 1000);
        $next = $this->nextTagNumber();
        $this->db->trans_start();
        for ($i = 0; $i < $count; $i++) {
            $this->insertData('livestock_animal', array(
                'an_ls_id' => (int) $ls_id, 'an_lst_id' => (int) $lst_id,
                'an_tag' => str_pad((string) ($next + $i), 3, '0', STR_PAD_LEFT),
                'an_shed_id' => (int) $shed_id, 'an_batch_id' => (int) $batch_id,
                'an_origin' => 'registered', 'an_status' => 1,
                'an_created_at' => get_current_time(), 'an_created_by' => $this->actor(),
            ));
        }
        $this->db->trans_complete();
        return $count;
    }

    /** Calves recorded through Reproduction: blank records linked to the mother, shed and batch. */
    public function createBorn($lrp_id, $ls_id, $lst_id, $count, $mother_id, $shed_id, $batch_id)
    {
        $count = min((int) floor((float) $count), 1000);
        if ($mother_id) {
            $mom = $this->getAnimalById($mother_id);
            $mother_id = ($mom && (int) $mom->an_ls_id === (int) $ls_id) ? (int) $mother_id : 0;
        }
        $next = $this->nextTagNumber();
        for ($i = 0; $i < $count; $i++) {
            $this->insertData('livestock_animal', array(
                'an_ls_id' => (int) $ls_id, 'an_lst_id' => (int) $lst_id,
                'an_tag' => str_pad((string) ($next + $i), 3, '0', STR_PAD_LEFT),
                'an_mother_id' => (int) $mother_id,
                'an_shed_id' => (int) $shed_id, 'an_batch_id' => (int) $batch_id,
                'an_origin' => 'born', 'an_lrp_id' => (int) $lrp_id, 'an_status' => 1,
                'an_created_at' => get_current_time(), 'an_created_by' => $this->actor(),
            ));
        }
    }

    public function archiveByReproductionId($lrp_id)
    {
        $this->scope_tenant('livestock_animal');
        $this->db->where('an_lrp_id', (int) $lrp_id);
        $this->db->update('livestock_animal', array('an_status' => 0, 'an_updated_at' => get_current_time()));
    }

    /**
     * Filtered page of animals. $f: ls_id, lst_id, shed_id, batch_id, state (all|named|unnamed), q
     */
    public function getAnimals($f, $limit = 100, $offset = 0, &$total = null)
    {
        $apply = function ($m) use ($f) {
            $m->scope_tenant('livestock_animal');
            $m->db->where('livestock_animal.an_status', 1);
            foreach (array('ls_id' => 'an_ls_id', 'lst_id' => 'an_lst_id', 'shed_id' => 'an_shed_id', 'batch_id' => 'an_batch_id') as $k => $col) {
                if (!empty($f[$k])) {
                    $m->db->where("livestock_animal.$col", (int) $f[$k]);
                }
            }
            if (($f['state'] ?? '') === 'unnamed') {
                $m->db->where("livestock_animal.an_name = ''", null, false);
            } elseif (($f['state'] ?? '') === 'named') {
                $m->db->where("livestock_animal.an_name <> ''", null, false);
            }
            if (!empty($f['q'])) {
                $m->db->group_start()->like('livestock_animal.an_name', $f['q'])->or_like('livestock_animal.an_tag', $f['q'])->group_end();
            }
        };
        $apply($this);
        $total = $this->db->count_all_results('livestock_animal');

        $apply($this);
        $this->db->select('livestock_animal.*, livestock.ls_name, livestock_type.lst_title, mom.an_name AS mother_name, mom.an_tag AS mother_tag');
        $this->db->join('livestock', 'livestock.ls_id = livestock_animal.an_ls_id', 'left');
        $this->db->join('livestock_type', 'livestock_type.lst_id = livestock_animal.an_lst_id', 'left');
        $this->db->join('livestock_animal mom', 'mom.an_id = livestock_animal.an_mother_id', 'left');
        $this->db->order_by('livestock_animal.an_ls_id, livestock_animal.an_tag, livestock_animal.an_id');
        $this->db->limit($limit, $offset);
        return $this->db->get('livestock_animal')->result();
    }

    public function getAnimalById($an_id)
    {
        $this->scope_tenant('livestock_animal');
        $this->db->where('an_id', (int) $an_id);
        return $this->db->get('livestock_animal')->row();
    }

    /** All active animals with labels: dropdowns for production, mothers, vision. */
    public function getActiveAnimals()
    {
        $this->scope_tenant('livestock_animal');
        $this->db->select('livestock_animal.*, livestock.ls_name, livestock_type.lst_title');
        $this->db->join('livestock', 'livestock.ls_id = livestock_animal.an_ls_id', 'left');
        $this->db->join('livestock_type', 'livestock_type.lst_id = livestock_animal.an_lst_id', 'left');
        $this->db->where('livestock_animal.an_status', 1);
        $this->db->order_by('livestock.ls_name, livestock_animal.an_tag, livestock_animal.an_name');
        return $this->db->get('livestock_animal')->result();
    }

    /** "Lisa [001]" / "Lisa" / "[001]" style label used everywhere an animal is picked. */
    public static function label($a)
    {
        $n = trim($a->an_name);
        $t = trim($a->an_tag);
        if ($n !== '' && $t !== '') {
            return $n . ' [' . $t . ']';
        }
        return $n !== '' ? $n : ($t !== '' ? '[' . $t . ']' : 'Animal #' . $a->an_id);
    }

    public function validAnimalId($an_id)
    {
        $an_id = (int) $an_id;
        return ($an_id > 0 && $this->getAnimalById($an_id)) ? $an_id : 0;
    }

    /** Resolve free text ("Lisa [001]", "001" or "Lisa") to an animal id, 0 when blank/unknown. */
    public function resolveAnimalText($text)
    {
        $text = trim((string) $text);
        if ($text === '') {
            return 0;
        }
        if (preg_match('/\[([^\]]+)\]\s*$/', $text, $m)) {
            $a = $this->findByTag($m[1]);
            if ($a) {
                return (int) $a->an_id;
            }
        }
        $a = $this->findByTag($text);
        if ($a) {
            return (int) $a->an_id;
        }
        $this->scope_tenant('livestock_animal');
        $this->db->where('an_status', 1)->where('an_name', $text)->limit(2);
        $rows = $this->db->get('livestock_animal')->result();
        return count($rows) === 1 ? (int) $rows[0]->an_id : 0;
    }

    public function findByTag($tag, $exclude_id = 0)
    {
        $tag = trim((string) $tag);
        if ($tag === '') {
            return null;
        }
        $this->scope_tenant('livestock_animal');
        $this->db->where('an_status', 1)->where('an_tag', $tag);
        if ($exclude_id) {
            $this->db->where('an_id !=', (int) $exclude_id);
        }
        return $this->db->get('livestock_animal')->row();
    }

    /**
     * Save one row from the roster. Returns null when saved, otherwise an error string.
     * A mother must be a different animal of the same livestock kind.
     */
    public function saveRow($an_id, $name, $tag, $sex, $mother_text, $batch_key)
    {
        $animal = $this->getAnimalById($an_id);
        if (!$animal || (int) $animal->an_status !== 1) {
            return 'Animal not found';
        }
        $name = mb_substr(trim(strip_tags($name)), 0, 100);
        $tag = mb_substr(trim(strip_tags($tag)), 0, 50);
        if ($tag !== '' && $this->findByTag($tag, $an_id)) {
            return "Tag '$tag' is already used by another animal";
        }
        $mother_id = $this->resolveAnimalText($mother_text);
        if ($mother_id) {
            $mom = $this->getAnimalById($mother_id);
            if ($mother_id === (int) $an_id || !$mom || (int) $mom->an_ls_id !== (int) $animal->an_ls_id) {
                return ($name !== '' ? $name : $tag) . ': mother must be a different animal of the same livestock';
            }
        }
        $shed = 0;
        $batch = 0;
        if (preg_match('/^(\d+):(\d+)$/', (string) $batch_key, $m)) {
            $shed = (int) $m[1];
            $batch = (int) $m[2];
        }
        $this->updateData('livestock_animal', 'an_id', (int) $an_id, array(
            'an_name' => $name, 'an_tag' => $tag,
            'an_sex' => in_array($sex, array('F', 'M'), true) ? $sex : '',
            'an_mother_id' => $mother_id, 'an_shed_id' => $shed, 'an_batch_id' => $batch,
            'an_updated_at' => get_current_time(), 'an_updated_by' => $this->actor(),
        ));
        return null;
    }

    public function archive($an_id)
    {
        $this->updateData('livestock_animal', 'an_id', (int) $an_id, array(
            'an_status' => 0, 'an_updated_at' => get_current_time(), 'an_updated_by' => $this->actor(),
        ));
    }

    /** Shed/batch pairs that exist, for the batch pickers. */
    public function getBatchOptions()
    {
        $this->scope_tenant('live_assigned_shed_summary');
        $this->db->select('lshs_sh_id, lshs_batch_id, lshs_batch_title, shed.sh_title, shed.sh_no');
        $this->db->join('shed', 'shed.sh_id = live_assigned_shed_summary.lshs_sh_id', 'left');
        $this->db->where('lshs_status', 1);
        $this->db->order_by('shed.sh_no, lshs_batch_id');
        $out = array();
        foreach ($this->db->get('live_assigned_shed_summary')->result() as $r) {
            $key = (int) $r->lshs_sh_id . ':' . (int) $r->lshs_batch_id;
            $out[$key] = trim(($r->sh_title ?: 'Shed ' . $r->lshs_sh_id) . ' / ' . ($r->lshs_batch_title ?: 'Batch ' . $r->lshs_batch_id));
        }
        return $out;
    }

    /* ======================= KulaAI Vision hooks ======================= */

    public function markSeen($an_id)
    {
        $this->updateData('livestock_animal', 'an_id', (int) $an_id, array('an_last_seen_at' => get_current_time()));
    }

    /** Registered animals expected in a shed (+ optional batch). */
    public function getByShedBatch($shed_id, $batch_id = 0)
    {
        $this->scope_tenant('livestock_animal');
        $this->db->where('an_status', 1)->where('an_shed_id', (int) $shed_id);
        if (!empty($batch_id)) {
            $this->db->where('an_batch_id', (int) $batch_id);
        }
        $this->db->order_by('an_tag, an_id');
        return $this->db->get('livestock_animal')->result();
    }
}
