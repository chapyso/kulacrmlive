<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notification_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Insert a new notification record
     */
    public function create_notification($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('notifications', $data);
        return $this->db->insert_id();
    }

    /**
     * Get count of unread notifications for tenant
     */
    public function get_unread_count($tenant_id = 1)
    {
        $this->db->where('tenant_id', $tenant_id);
        $this->db->where('is_read', 0);
        return $this->db->count_all_results('notifications');
    }

    /**
     * Get recent notifications
     */
    public function get_recent_notifications($tenant_id = 1, $limit = 10)
    {
        $this->db->where('tenant_id', $tenant_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('notifications')->result();
    }

    /**
     * Mark single notification as read
     */
    public function mark_as_read($id, $tenant_id = 1)
    {
        $this->db->where('id', $id);
        $this->db->where('tenant_id', $tenant_id);
        return $this->db->update('notifications', array('is_read' => 1));
    }

    /**
     * Mark all notifications as read for tenant
     */
    public function mark_all_as_read($tenant_id = 1)
    {
        $this->db->where('tenant_id', $tenant_id);
        return $this->db->update('notifications', array('is_read' => 1));
    }

    /**
     * Comprehensive Automated Farm Alert Engine
     * Monitors: Animal Deaths, Vaccine Treatments, Low Feed Stock, Births/Reproduction, & Due Invoices
     */
    public function auto_generate_farm_alerts($tenant_id = 1)
    {
        $today = date('Y-m-d');

        // ----------------------------------------------------
        // 1. ANIMAL DEATHS / MORTALITY ALERTS (livestock_death_quantity)
        // ----------------------------------------------------
        if ($this->db->table_exists('livestock_death_quantity')) {
            $deaths = $this->db->get_where('livestock_death_quantity', array(
                'tenant_id' => $tenant_id,
                'ld_status' => 1,
                'ld_death_date' => $today
            ))->result();

            foreach ($deaths as $death) {
                $reason = !empty($death->ld_death_reason) ? " (Reason: {$death->ld_death_reason})" : "";
                $title = "Animal Mortality Alert";
                $msg = "{$death->ld_death_quantity} livestock death(s) recorded today{$reason}. Check shed health.";

                $exists = $this->db->where('tenant_id', $tenant_id)
                                   ->where('type', 'mortality')
                                   ->where('title', $title)
                                   ->where('DATE(created_at)', $today)
                                   ->get('notifications')->num_rows();

                if (!$exists) {
                    $this->create_notification(array(
                        'tenant_id' => $tenant_id,
                        'type' => 'mortality',
                        'title' => $title,
                        'message' => $msg,
                        'icon' => 'fa-skull-crossbones',
                        'icon_bg' => '#fef2f2',
                        'icon_color' => '#ef4444',
                        'link' => base_url('shed/listDeath')
                    ));
                }
            }
        }

        // ----------------------------------------------------
        // 2. VACCINE TREATMENTS & DOSES (vaccine_used / vaccination)
        // ----------------------------------------------------
        if ($this->db->table_exists('vaccine_used')) {
            $vaccines = $this->db->get_where('vaccine_used', array('tenant_id' => $tenant_id))->result();
            foreach ($vaccines as $vax) {
                if (isset($vax->vcu_next_date) && !empty($vax->vcu_next_date)) {
                    $next_date = date('Y-m-d', strtotime($vax->vcu_next_date));
                    $diff_days = (strtotime($next_date) - strtotime($today)) / (60 * 60 * 24);

                    if ($diff_days >= 0 && $diff_days <= 3) {
                        $title = "Vaccine Treatment Due Soon";
                        $msg = "Scheduled vaccination due on " . date('M d, Y', strtotime($next_date)) . ". Administer required dose.";

                        $exists = $this->db->where('tenant_id', $tenant_id)
                                           ->where('type', 'vaccine')
                                           ->where('DATE(created_at)', $today)
                                           ->get('notifications')->num_rows();

                        if (!$exists) {
                            $this->create_notification(array(
                                'tenant_id' => $tenant_id,
                                'type' => 'vaccine',
                                'title' => $title,
                                'message' => $msg,
                                'icon' => 'fa-syringe',
                                'icon_bg' => '#eff6ff',
                                'icon_color' => '#2563eb',
                                'link' => base_url('vaccine/listVaccinatedShed')
                            ));
                        }
                    }
                }
            }
        }

        // ----------------------------------------------------
        // 3. LIVESTOCK REPRODUCTION / BIRTH ALERTS (livestock_reproduction)
        // ----------------------------------------------------
        if ($this->db->table_exists('livestock_reproduction')) {
            $births = $this->db->get_where('livestock_reproduction', array(
                'tenant_id' => $tenant_id,
                'lrp_status' => 1,
                'lrp_birth_date' => $today
            ))->result();

            foreach ($births as $birth) {
                $title = "New Animal Birth Recorded";
                $msg = "{$birth->lrp_birth_quantity} new livestock birth(s) added to farm stock today.";

                $exists = $this->db->where('tenant_id', $tenant_id)
                                   ->where('type', 'reproduction')
                                   ->where('title', $title)
                                   ->where('DATE(created_at)', $today)
                                   ->get('notifications')->num_rows();

                if (!$exists) {
                    $this->create_notification(array(
                        'tenant_id' => $tenant_id,
                        'type' => 'reproduction',
                        'title' => $title,
                        'message' => $msg,
                        'icon' => 'fa-cow',
                        'icon_bg' => '#ecfdf5',
                        'icon_color' => '#059669',
                        'link' => base_url('product/listLivestockReproduction')
                    ));
                }
            }
        }

        // ----------------------------------------------------
        // 4. LOW FEED STOCK ALERTS (food_summary)
        // ----------------------------------------------------
        if ($this->db->table_exists('food_summary')) {
            $foods = $this->db->get_where('food_summary', array('tenant_id' => $tenant_id, 'fds_status' => 1))->result();
            foreach ($foods as $food) {
                $food_name = !empty($food->fds_food_title) ? $food->fds_food_title : 'Feed Item';
                $title = "Low Feed Stock Alert";
                $msg = "Feed item '{$food_name}' requires replenishment.";

                $exists = $this->db->where('tenant_id', $tenant_id)
                                   ->where('type', 'food_stock')
                                   ->where('title', $title)
                                   ->where('DATE(created_at)', $today)
                                   ->get('notifications')->num_rows();

                if (!$exists) {
                    $this->create_notification(array(
                        'tenant_id' => $tenant_id,
                        'type' => 'food_stock',
                        'title' => $title,
                        'message' => $msg,
                        'icon' => 'fa-wheat-awn',
                        'icon_bg' => '#fffbeb',
                        'icon_color' => '#d97706',
                        'link' => base_url('food/listFood')
                    ));
                }
            }
        }

        // ----------------------------------------------------
        // 5. UNPAID / DUE CLIENT SALE INVOICES (livestock_sale_summary)
        // ----------------------------------------------------
        if ($this->db->table_exists('livestock_sale_summary')) {
            $sales = $this->db->get_where('livestock_sale_summary', array(
                'tenant_id' => $tenant_id,
                'lsss_status' => 1,
                'lsss_date' => $today
            ))->result();

            foreach ($sales as $sale) {
                $title = "New Livestock Sale Registered";
                $msg = "Sale Invoice #{$sale->lsss_id} total amount " . number_format($sale->lsss_grand_total, 2) . " logged.";

                $exists = $this->db->where('tenant_id', $tenant_id)
                                   ->where('type', 'sale')
                                   ->where('title', $title)
                                   ->where('DATE(created_at)', $today)
                                   ->get('notifications')->num_rows();

                if (!$exists) {
                    $this->create_notification(array(
                        'tenant_id' => $tenant_id,
                        'type' => 'sale',
                        'title' => $title,
                        'message' => $msg,
                        'icon' => 'fa-file-invoice-dollar',
                        'icon_bg' => '#faf5ff',
                        'icon_color' => '#9333ea',
                        'link' => base_url('sale/listSale')
                    ));
                }
            }
        }
    }
}
