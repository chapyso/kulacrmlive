<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Settings_model extends MY_Model
{
    public static $default_units = array(
        'Piece',
        'Unit',
        'Kilogram',
        'Gram',
        'Metric Ton',
        'Pound',
        'Ounce',
        'Liter',
        'Milliliter',
        'Cubic Meter',
        'Gallon',
        'Quart',
        'Bag',
        'Sack',
        'Bale',
        'Box',
        'Carton',
        'Pack',
        'Packet',
        'Sachet',
        'Bottle',
        'Vial',
        'Ampoule',
        'Tube',
        'Syringe',
        'Dose',
        'Tablet',
        'Capsule',
        'Tray',
        'Dozen',
        'Crate',
        'Bucket',
        'Drum',
        'Jerrycan',
        'Container',
        'Scoop',
        'Wheelbarrow',
        'Roll',
        'Bundle',
        'Coil',
        'Meter',
        'Foot',
        'Pair',
        'Head',
        'Bird',
        'Chick',
        'Egg',
        'Carcass',
        'Hide',
        'Skin',
        'Fleece',
        'Hour',
        'Day',
        'Week',
        'Month'
    );

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->ensure_tenant_id_column();
        $this->ensure_dark_img_url_column();
        $this->ensure_favicon_url_column();
        $this->ensure_default_units();
    }

    private function ensure_tenant_id_column()
    {
        if ($this->db->table_exists('settings')) {
            if (!$this->db->field_exists('tenant_id', 'settings')) {
                $this->load->dbforge();
                $fields = array(
                    'tenant_id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'default' => 1,
                        'after' => 'id'
                    )
                );
                $this->dbforge->add_column('settings', $fields);
                $this->db->where('id', 1)->update('settings', array('tenant_id' => 1));
            }
        }
    }

    private function ensure_dark_img_url_column()
    {
        if ($this->db->table_exists('settings')) {
            if (!$this->db->field_exists('dark_img_url', 'settings')) {
                $this->load->dbforge();
                $fields = array(
                    'dark_img_url' => array(
                        'type' => 'VARCHAR',
                        'constraint' => 255,
                        'null' => TRUE,
                        'default' => 'uploads/dark_logo.png',
                        'after' => 'img_url'
                    )
                );
                $this->dbforge->add_column('settings', $fields);
            }
        }
    }

    private function ensure_favicon_url_column()
    {
        if ($this->db->table_exists('settings')) {
            if (!$this->db->field_exists('favicon_url', 'settings')) {
                $this->load->dbforge();
                $fields = array(
                    'favicon_url' => array(
                        'type' => 'VARCHAR',
                        'constraint' => 255,
                        'null' => TRUE,
                        'default' => 'uploads/logo.png',
                        'after' => 'dark_img_url'
                    )
                );
                $this->dbforge->add_column('settings', $fields);
            }
        }
    }

    public function getTenantId()
    {
        $tenant_id = $this->get_tenant_id();
        return !empty($tenant_id) ? (int)$tenant_id : 1;
    }

    function getSettings()
    {
        $tenant_id = $this->get_tenant_id();
        $effective_tenant_id = !empty($tenant_id) ? (int)$tenant_id : 1;
        
        if ($this->db->field_exists('tenant_id', 'settings')) {
            $settings = $this->db->get_where('settings', array('tenant_id' => $effective_tenant_id))->row();
            
            if (!$settings) {
                // Clone default/superadmin settings for this tenant
                $default = $this->db->get_where('settings', array('tenant_id' => 1))->row_array();
                if (!$default) {
                    $default = $this->db->get('settings')->row_array();
                }
                
                if ($default) {
                    unset($default['id']);
                    $default['tenant_id'] = $effective_tenant_id;
                    $this->db->insert('settings', $default);
                    $settings = $this->db->get_where('settings', array('tenant_id' => $effective_tenant_id))->row();
                } else {
                    $settings = $this->db->get('settings')->row();
                }
            }
            return $settings;
        }

        $query = $this->db->get('settings');
        return $query->row();
    }

    function updateSettings($id, $data)
    {
        $tenant_id = $this->get_tenant_id();
        $effective_tenant_id = !empty($tenant_id) ? (int)$tenant_id : 1;

        if ($this->db->field_exists('tenant_id', 'settings')) {
            $existing = $this->db->get_where('settings', array('tenant_id' => $effective_tenant_id))->row();
            if ($existing) {
                $this->db->where('tenant_id', $effective_tenant_id);
                $this->db->update('settings', $data);
                return;
            } else {
                $data['tenant_id'] = $effective_tenant_id;
                $this->db->insert('settings', $data);
                return;
            }
        }

        $this->db->where('id', $id);
        $this->db->update('settings', $data);
    }

    function getCountRow($table, $column, $condition)
    {
        $this->scope_tenant($table);
        $this->db->where($condition);
        $this->db->select("COUNT($column) AS total");
        $query = $this->db->count_all_results($table);
        return $query;
    }

    function getData($table, $data)
    {
        $this->scope_tenant($table);
        $this->db->where($data);
        $query = $this->db->get($table);
        return $query;
    }

    function getSingleData($table, $condition)
    {
        $this->scope_tenant($table);
        $this->db->where($condition);
        $query = $this->db->get($table);
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    /* ============================================== Product Unit ==================================================== */
    public function insertData($table, $data)
    {
        $data = $this->prepare_tenant_data($table, $data);
        $this->db->insert($table, $data);
        $returnValue = $this->db->insert_id();
        return $returnValue;
    }

    public function updateData($table, $index, $identifier, $data)
    {
        $this->scope_tenant($table);
        $this->db->where($index, $identifier);
        $this->db->update($table, $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function ensure_default_units($target_tenant_id = null)
    {
        if (!$this->db->table_exists('unit')) {
            return;
        }

        $tenants_to_check = array();
        if ($target_tenant_id !== null) {
            $tenants_to_check[] = (int)$target_tenant_id;
        } else {
            $current_tenant = $this->get_tenant_id();
            if (!empty($current_tenant)) {
                $tenants_to_check[] = (int)$current_tenant;
            }
            $tenants_to_check[] = 1;
            if ($this->db->table_exists('tenants')) {
                $tenant_rows = $this->db->select('id')->get('tenants')->result_array();
                foreach ($tenant_rows as $row) {
                    $tenants_to_check[] = (int)$row['id'];
                }
            }
            $tenants_to_check = array_unique(array_filter($tenants_to_check));
        }

        $now = date('Y-m-d H:i:s');

        foreach ($tenants_to_check as $tid) {
            $existing = $this->db->select('un_name')
                ->where('tenant_id', $tid)
                ->get('unit')
                ->result_array();

            $existing_names = array();
            foreach ($existing as $ex) {
                $existing_names[strtolower(trim($ex['un_name']))] = true;
            }

            $to_insert = array();
            foreach (self::$default_units as $unit_name) {
                if (!isset($existing_names[strtolower(trim($unit_name))])) {
                    $to_insert[] = array(
                        'un_name' => $unit_name,
                        'un_description' => '',
                        'un_status' => 1,
                        'un_created_at' => $now,
                        'un_created_by' => 1,
                        'tenant_id' => $tid
                    );
                }
            }

            if (!empty($to_insert)) {
                $this->db->insert_batch('unit', $to_insert);
            }
        }
    }

    function getUnit()
    {
        $this->ensure_default_units();
        $this->scope_tenant('unit');
        $this->db->where('un_status', 1);
        $query = $this->db->get('unit');
        return $query;
    }

    function getUnitById($ct_id)
    {
        $this->scope_tenant('unit');
        $this->db->where('un_id', $ct_id);
        $query = $this->db->get('unit');
        return $query->row();
    }
}
