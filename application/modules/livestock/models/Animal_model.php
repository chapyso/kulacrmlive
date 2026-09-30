<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Individual (named) animals. Each purchased livestock line (livestock_purchase_value)
 * can be expanded into one record per head so production can be traced to an animal.
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
        $this->load->dbforge();
        if (!$this->db->table_exists('livestock_animal')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `livestock_animal` (
                `an_id` int(11) NOT NULL AUTO_INCREMENT,
                `tenant_id` int(11) NOT NULL DEFAULT 1,
                `an_purs_id` int(11) NOT NULL DEFAULT 0,
                `an_purv_id` int(11) NOT NULL DEFAULT 0,
                `an_ls_id` int(11) NOT NULL DEFAULT 0,
                `an_lst_id` int(11) NOT NULL DEFAULT 0,
                `an_name` varchar(100) NOT NULL DEFAULT '',
                `an_status` int(11) NOT NULL DEFAULT 1,
                `an_created_by` int(11) NOT NULL DEFAULT 0,
                `an_created_at` datetime NOT NULL DEFAULT current_timestamp(),
                `an_updated_by` int(11) NOT NULL DEFAULT 0,
                `an_updated_at` datetime NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`an_id`),
                KEY `idx_an_tenant_purv` (`tenant_id`, `an_purv_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        }
        if ($this->db->table_exists('product_stock') && !$this->db->field_exists('prs_animal_id', 'product_stock')) {
            $this->dbforge->add_column('product_stock', array(
                'prs_animal_id' => array('type' => 'INT', 'constraint' => 11, 'default' => 0, 'null' => FALSE)
            ));
        }
    }

    /**
     * Make sure a purchase line has one animal row per head (whole numbers only).
     * Existing rows (and their names) are never touched; rows are only added.
     */
    public function syncPurchaseLine($purv)
    {
        $count = (int) floor((float) $purv->purv_quantity);
        $this->scope_tenant('livestock_animal');
        $this->db->where('an_purv_id', $purv->purv_id);
        $this->db->where('an_status', 1);
        $existing = $this->db->count_all_results('livestock_animal');
        if ($existing >= $count) {
            return;
        }
        $ls = $this->db->get_where('livestock', array('ls_id' => $purv->purv_ls_id))->row();
        $base = $ls ? $ls->ls_name : 'Animal';
        $user = $this->ion_auth->user()->row();
        for ($n = $existing + 1; $n <= $count; $n++) {
            $this->insertData('livestock_animal', array(
                'an_purs_id' => $purv->purv_purs_id,
                'an_purv_id' => $purv->purv_id,
                'an_ls_id' => $purv->purv_ls_id,
                'an_lst_id' => $purv->purv_lst_id,
                'an_name' => $base . ' #' . $n,
                'an_status' => 1,
                'an_created_at' => get_current_time(),
                'an_created_by' => $user ? $user->user_id : 0
            ));
        }
    }

    public function getAnimalsByPurchaseValueId($purv_id)
    {
        $this->scope_tenant('livestock_animal');
        $this->db->where('an_purv_id', $purv_id);
        $this->db->where('an_status', 1);
        $this->db->order_by('an_id', 'asc');
        return $this->db->get('livestock_animal')->result();
    }

    public function getAnimalById($an_id)
    {
        $this->scope_tenant('livestock_animal');
        $this->db->where('an_id', $an_id);
        return $this->db->get('livestock_animal')->row();
    }

    /** All active animals with livestock / variant labels, for production dropdowns. */
    public function getActiveAnimals()
    {
        $this->scope_tenant('livestock_animal');
        $this->db->select('livestock_animal.*, livestock.ls_name, livestock_type.lst_title');
        $this->db->join('livestock', 'livestock.ls_id = livestock_animal.an_ls_id', 'left');
        $this->db->join('livestock_type', 'livestock_type.lst_id = livestock_animal.an_lst_id', 'left');
        $this->db->where('livestock_animal.an_status', 1);
        $this->db->order_by('livestock.ls_name', 'asc');
        $this->db->order_by('livestock_animal.an_name', 'asc');
        return $this->db->get('livestock_animal')->result();
    }

    /** Verifies an animal id belongs to the current tenant; returns 0 when blank/invalid. */
    public function validAnimalId($an_id)
    {
        $an_id = (int) $an_id;
        return ($an_id > 0 && $this->getAnimalById($an_id)) ? $an_id : 0;
    }

    public function archiveByPurchaseSummaryId($purs_id)
    {
        $this->scope_tenant('livestock_animal');
        $this->db->where('an_purs_id', $purs_id);
        $this->db->update('livestock_animal', array('an_status' => 0));
    }

    /**
     * After a purchase is edited its lines get new ids. Re-attach the named animals to the
     * matching new line (same livestock + variant) so names survive; extras are archived.
     */
    public function relinkPurchase($purs_id)
    {
        $this->scope_tenant('livestock_animal');
        $this->db->where('an_purs_id', $purs_id);
        $this->db->where('an_status', 1);
        $this->db->order_by('an_id', 'asc');
        $animals = $this->db->get('livestock_animal')->result();
        if (empty($animals)) {
            return;
        }
        $this->scope_tenant('livestock_purchase_value');
        $this->db->where('purv_purs_id', $purs_id);
        $this->db->where('purv_status', 1);
        $lines = $this->db->get('livestock_purchase_value')->result();
        $pool = array();
        foreach ($animals as $a) {
            $pool[$a->an_ls_id . '-' . $a->an_lst_id][] = $a;
        }
        foreach ($lines as $line) {
            $key = $line->purv_ls_id . '-' . $line->purv_lst_id;
            $cap = (int) floor((float) $line->purv_quantity);
            while ($cap > 0 && !empty($pool[$key])) {
                $a = array_shift($pool[$key]);
                $this->updateData('livestock_animal', 'an_id', $a->an_id, array('an_purv_id' => $line->purv_id));
                $cap--;
            }
        }
        foreach ($pool as $left) {
            foreach ($left as $a) {
                $this->updateData('livestock_animal', 'an_id', $a->an_id, array('an_status' => 0));
            }
        }
    }
}
