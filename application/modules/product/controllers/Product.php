<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('product_model');
        $this->load->model('shed/shed_model');
        $this->load->model('sale/sale_model');
        $this->load->model('purchase/purchase_model');
        $this->load->model('report/report_model');
        $this->load->model('food/food_model');
        $this->load->model('livestock/livestock_model');
        $this->load->library('upload');
        $this->load->model('settings/settings_model');
        $settings = $this->settings_model->getSettings();
        $language = (!empty($settings) && !empty($settings->language)) ? $settings->language : 'english';
        $this->lang->load('system_syntax', $language);
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group(array('admin'))) {
            redirect('home/permission');
        }
    }

    /* =========================================== product =========================================== */
    public function listProduct()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['units'] = $this->settings_model->getUnit();
        $data['products'] = $this->product_model->getProduct();
        $data['productCategories'] = $this->product_model->getProductCategory();
        $data['sheds'] = $this->shed_model->getShed();
        $data['batches'] = $this->shed_model->getBatch();
        $data['assignedProducts'] = $this->product_model->getProductAssign();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_products', $data);
        $this->load->view('home/footer'); // just the header file
    }

    public function insertProduct()
    {
        $name = $this->input->post('pr_name');
        $pr_unit_id = $this->input->post('pr_unit_id');
        $pr_selling_price = $this->input->post('pr_selling_price');
        $description = $this->input->post('pr_description');
        $pr_prc_id = $this->input->post('pr_prc_id');
        $data = array(
            'pr_prc_id' => $pr_prc_id,
            'pr_name' => $name,
            'pr_unit_id' => $pr_unit_id,
            'pr_selling_price' => $pr_selling_price,
            'pr_description' => $description,
            'pr_status' => 1,
            'pr_created_at' => get_current_time(),
            'pr_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->insertData('product', $data);
        $this->session->set_flashdata('success', 'Product added successfully.');
        redirect('product/listProduct');
    }

    public function updateProduct()
    {
        $pr_id = $this->input->post('pr_id');
        $name = $this->input->post('pr_name');
        $pr_unit_id = $this->input->post('pr_unit_id');
        $pr_selling_price = $this->input->post('pr_selling_price');
        $description = $this->input->post('pr_description');
        $pr_prc_id = $this->input->post('pr_prc_id');
        $updateData = array(
            'pr_prc_id' => $pr_prc_id,
            'pr_name' => $name,
            'pr_unit_id' => $pr_unit_id,
            'pr_selling_price' => $pr_selling_price,
            'pr_description' => $description,
            'pr_updated_at' => get_current_time(),
            'pr_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->updateData('product', 'pr_id', $pr_id, $updateData);
        $this->session->set_flashdata('success', 'Product updated successfully.');
        redirect('product/listProduct');
    }

    function deleteProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $this->db->trans_start();
        $pr_id = $this->input->post('pr_id');
        $data = array(
            'pr_status' => 0,
            'pr_updated_at' => get_current_time(),
            'pr_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->updateData('product', 'pr_id', $pr_id, $data);

        // Delete product stock when delete product
        $deleteProductStock = array(
            'prs_status' => 0,
            'prs_updated_at' => get_current_time(),
            'prs_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->updateData('product_stock', 'prs_pr_id', $pr_id, $deleteProductStock);

        // Delete assigned product when delete product
        $data = array(
            'pra_status' => 0,
            'pra_updated_at' => get_current_time(),
            'pra_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->updateData('product_assign', 'pra_pr_id', $pr_id, $data);
        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Product deleted successfully.');
        redirect('product/listProduct');
    }

    function editProductByJason()
    {
        $id = $this->input->get('pr_id');
        $data['products'] = $this->product_model->getProductById($id);
        echo json_encode($data);
    }

    /* =========================================== product Assign =========================================== */
    public function insertProductAssign()
    {
        $pra_pr_id = $this->input->post('pra_pr_id');
        $pra_shed_id = $this->input->post('pra_shed_id');
        $pra_batch_id = $this->input->post('pra_batch_id');
        $pra_description = $this->input->post('pra_description');
        $productAssignData = array(
            'pra_pr_id' => $pra_pr_id,
            'pra_shed_id' => $pra_shed_id,
            'pra_batch_id' => $pra_batch_id,
            'pra_description' => $pra_description,
            'pra_status' => 1,
            'pra_created_at' => get_current_time(),
            'pra_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->insertData('product_assign', $productAssignData);
        $this->session->set_flashdata('success', 'Product assignment added successfully.');
        redirect('product/listProduct');
    }

    public function updateProductAssign()
    {
        $this->db->trans_start();
        $pra_id = $this->input->post('pra_id');
        $pra_pr_id = $this->input->post('pra_pr_id');
        $pra_shed_id = $this->input->post('pra_shed_id');
        $pra_batch_id = $this->input->post('pra_batch_id');
        $pra_description = $this->input->post('pra_description');
        $updateAssignedData = array(
            'pra_shed_id' => $pra_shed_id,
            'pra_batch_id' => $pra_batch_id,
            'pra_description' => $pra_description,
            'pra_updated_at' => get_current_time(),
            'pra_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->updateData('product_assign', 'pra_id', $pra_id, $updateAssignedData);

        // Update product stock when delete product
        $updateProductStock = array(
            'prs_shed_id' => $pra_shed_id,
            'prs_batch_id' => $pra_batch_id,
            'prs_updated_at' => get_current_time(),
            'prs_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->updateData('product_stock', 'prs_pra_id', $pra_id, $updateProductStock);

        $updateWastedData = array(
            'prw_shed_id' => $pra_shed_id,
            'prw_batch_id' => $pra_batch_id,
            'prw_updated_at' => get_current_time(),
            'prw_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->updateData('product_wasted', 'prw_pra_id', $pra_id, $updateWastedData);
        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Product assignment updated successfully.');
        redirect("product/viewProductWiseProduction?pr_id=$pra_pr_id");
    }

    function deleteProductAssign()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $this->db->trans_start();
        $pra_id = $this->input->post('pra_id');
        $data = array(
            'pra_status' => 0,
            'pra_updated_at' => get_current_time(),
            'pra_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->updateData('product_assign', 'pra_id', $pra_id, $data);

        // Delete product stock when delete product
        $deleteProductStock = array(
            'prs_status' => 0,
            'prs_updated_at' => get_current_time(),
            'prs_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->updateData('product_stock', 'prs_pra_id', $pra_id, $deleteProductStock);
        // Delete waste when delete product
        $deleteWastedData = array(
            'prw_status' => 0,
            'prw_updated_at' => get_current_time(),
            'prw_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->updateData('product_wasted', 'prw_pra_id', $pra_id, $deleteWastedData);
        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Product assignment deleted successfully.');
        redirect('product/listProduct');
    }

    public function viewProductWiseProduction()
    {
        $pr_id = $this->input->get('pr_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['productById'] = $this->product_model->getProductById($pr_id);
        $data['stockProductions'] = $this->product_model->getStockProductionByProductId($pr_id);
        $data['assignedProductsByProduct'] = $this->product_model->getAssignedProductsByProductId($pr_id);
        $data['units'] = $this->settings_model->getUnit();
        $data['sheds'] = $this->shed_model->getShed();
        $data['batches'] = $this->shed_model->getBatch();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_product_wise_production', $data);
        $this->load->view('home/footer'); // just the header file
    }


    //  Check Assigned if already have  
    function checkAssignedProductBatchByAjax()
    {
        $batch_id = $this->input->post('batch_id');
        $shed_id = $this->input->post('shed_id');
        $product_id = $this->input->post('product_id');
        if ($product_id && $shed_id && $batch_id) {
            $returnValue = $this->product_model->getAssignedProductBatchByAjax($product_id, $shed_id, $batch_id);
        }
        if ($returnValue) {
            echo $returnValue->pra_id;
        } else {
            echo 0;
        }
    }

    /* =========================================== product Stock =========================================== */

    public function insertProductionToStock()
    {
        $prs_pr_id = $this->input->post('prs_pr_id');
        $prs_pra_id = $this->input->post('prs_pra_id');
        $prs_shed_id = $this->input->post('prs_shed_id');
        $prs_batch_id = $this->input->post('prs_batch_id');
        $prs_production_quantity = $this->input->post('prs_production_quantity');
        $prs_description = $this->input->post('prs_description');
        $date = $this->input->post('prs_date');
        $prs_date = date("Y-m-d", strtotime($date));
        $data = array(
            'prs_pr_id' => $prs_pr_id,
            'prs_pra_id' => $prs_pra_id,
            'prs_shed_id' => $prs_shed_id,
            'prs_batch_id' => $prs_batch_id,
            'prs_production_quantity' => $prs_production_quantity,
            'prs_description' => $prs_description,
            'prs_date' => $prs_date,
            'prs_status' => 1,
            'prs_created_at' => get_current_time(),
            'prs_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->insertData('product_stock', $data);
        $this->session->set_flashdata('success', 'Production added to stock successfully.');
        redirect('product/listProduct');
    }


    public function updateProductionToStock()
    {
        $prs_id = $this->input->post('prs_id');
        $prs_pra_id = $this->input->post('prs_pra_id');
        $prs_production_quantity = $this->input->post('prs_production_quantity');
        $prs_description = $this->input->post('prs_description');
        $date = $this->input->post('prs_date');
        $prs_date = date("Y-m-d", strtotime($date));
        $updateData = array(
            'prs_production_quantity' => $prs_production_quantity,
            'prs_description' => $prs_description,
            'prs_date' => $prs_date,
            'prs_updated_at' => get_current_time(),
            'prs_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->updateData('product_stock', 'prs_id', $prs_id, $updateData);
        $this->session->set_flashdata('success', 'Production stock updated successfully.');
        redirect("product/viewAssignedShedAndBatchWiseProduction?pra_id=$prs_pra_id");
    }

    public function viewAssignedShedAndBatchWiseProduction()
    {
        $pra_id = $this->input->get('pra_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['stockProductions'] = $this->product_model->getStockProductionByProductAssignedId($pra_id);

        $data['productAssignedById'] = $this->product_model->getProductAssignById($pra_id);
        $data['productById'] = $this->product_model->getProductById($data['productAssignedById']->pra_pr_id);

        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_assigned_shed_batch_wise_production', $data);
        $this->load->view('home/footer'); // just the header file
    }

    function deleteProductStock()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $prs_id = $this->input->post('prs_id');
        $prs_pra_id = $this->input->post('prs_pra_id');
        $data = array(
            'prs_status' => 0,
            'prs_updated_at' => get_current_time(),
            'prs_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->updateData('product_stock', 'prs_id', $prs_id, $data);
        $this->session->set_flashdata('success', 'Production stock deleted successfully.');
        redirect("product/viewAssignedShedAndBatchWiseProduction?pra_id=$prs_pra_id");
    }

    /* =========================================== product Wasted =========================================== */

    public function insertProductionWasted()
    {
        $prw_pr_id = $this->input->post('prw_pr_id');
        $prw_pra_id = $this->input->post('prw_pra_id');
        $prw_shed_id = $this->input->post('prw_shed_id');
        $prw_batch_id = $this->input->post('prw_batch_id');
        $prw_wasted_quantity = $this->input->post('prw_wasted_quantity');
        $prw_description = $this->input->post('prw_description');
        $date = $this->input->post('prw_date');
        $prw_date = date("Y-m-d", strtotime($date));
        $data = array(
            'prw_pr_id' => $prw_pr_id,
            'prw_pra_id' => $prw_pra_id,
            'prw_shed_id' => $prw_shed_id,
            'prw_batch_id' => $prw_batch_id,
            'prw_wasted_quantity' => $prw_wasted_quantity,
            'prw_description' => $prw_description,
            'prw_date' => $prw_date,
            'prw_status' => 1,
            'prw_created_at' => get_current_time(),
            'prw_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->insertData('product_wasted', $data);
        $this->session->set_flashdata('success', 'Wasted production recorded successfully.');
        redirect('product/listProduct');
    }


    public function updateProductionWasted()
    {
        $prw_id = $this->input->post('prw_id');
        $prw_pra_id = $this->input->post('prw_pra_id');
        $prw_wasted_quantity = $this->input->post('prw_wasted_quantity');
        $prw_description = $this->input->post('prw_description');
        $date = $this->input->post('prw_date');
        $prw_date = date("Y-m-d", strtotime($date));
        $updateData = array(
            'prw_wasted_quantity' => $prw_wasted_quantity,
            'prw_description' => $prw_description,
            'prw_date' => $prw_date,
            'prw_updated_at' => get_current_time(),
            'prw_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->updateData('product_wasted', 'prw_id', $prw_id, $updateData);
        $this->session->set_flashdata('success', 'Wasted production updated successfully.');
        redirect("product/viewWastedShedAndBatchWiseProduction?pra_id=$prw_pra_id");
    }

    function deleteProductWasted()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $prw_id = $this->input->post('prw_id');
        $prw_pra_id = $this->input->post('prw_pra_id');
        $data = array(
            'prw_status' => 0,
            'prw_updated_at' => get_current_time(),
            'prw_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->updateData('product_wasted', 'prw_id', $prw_id, $data);
        $this->session->set_flashdata('success', 'Wasted production deleted successfully.');
        redirect("product/viewWastedShedAndBatchWiseProduction?pra_id=$prw_pra_id");
    }

    public function viewWastedShedAndBatchWiseProduction()
    {
        $pra_id = $this->input->get('pra_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['wastedProductions'] = $this->product_model->getWastedProductionByProductAssignedId($pra_id);

        $data['productAssignedById'] = $this->product_model->getProductAssignById($pra_id);
        $data['productById'] = $this->product_model->getProductById($data['productAssignedById']->pra_pr_id);

        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_wasted_shed_batch_wise_production', $data);
        $this->load->view('home/footer'); // just the header file
    }


    /* =========================================== product Category =========================================== */
    public function listProductCategory()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['productCategories'] = $this->product_model->getProductCategory();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_product_category', $data);
        $this->load->view('home/footer'); // just the header file
    }

    public function insertProductCategory()
    {
        $prc_id = $this->input->post('prc_id');
        $name = $this->input->post('prc_name');
        $description = $this->input->post('prc_description');
        if (empty($prc_id)) {
            $data = array(
                'prc_name' => $name,
                'prc_description' => $description,
                'prc_status' => 1,
                'prc_created_at' => get_current_time(),
                'prc_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->product_model->insertData('product_category', $data);
            $this->session->set_flashdata('success', 'Product category added successfully.');
            redirect('product/listProductCategory');
        } else {
            $updateData = array(
                'prc_name' => $name,
                'prc_description' => $description,
                'prc_updated_at' => get_current_time(),
                'prc_updated_by' => $this->ion_auth->user()->row()->user_id
            );
            if (!empty($prc_id)) {
                $this->product_model->updateData('product_category', 'prc_id', $prc_id, $updateData);
            }
            $this->session->set_flashdata('success', 'Product category updated successfully.');
            redirect('product/listProductCategory');
        }
    }

    function editProductCategoryByJason()
    {
        $id = $this->input->get('prc_id');
        $data['categories'] = $this->product_model->getProductCategoryById($id);
        echo json_encode($data);
    }

    function deleteProductCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $prc_id = $this->input->post('prc_id');
        $data = array(
            'prc_status' => 0,
            'prc_updated_at' => get_current_time(),
            'prc_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->updateData('product_category', 'prc_id', $prc_id, $data);
        $this->session->set_flashdata('success', 'Product category deleted successfully.');
        redirect('product/listProductCategory');
    }







    /* =========================================== Livestock Reproduction =========================================== */
    public function listLivestockReproduction()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['batches'] = $this->shed_model->getBatch();
        $data['sheds'] = $this->shed_model->getShed();
        $data['livestocks'] = $this->livestock_model->getLivestock();
        $data['livestock_types'] = $this->livestock_model->getLivestockType();
        $data['reproductions'] = $this->product_model->getLivestockReproduction();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_livestock_reproduction', $data);
        $this->load->view('home/footer'); // just the header file
    }

    public function insertLivestockReproduction()
    {
        $this->db->trans_start();
        // Reproduction Data
        $lrp_id = $this->input->post('lrp_id');
        $lrp_batch_id = $this->input->post('lrp_batch_id');
        $lrp_sh_id = $this->input->post('lrp_sh_id');
        $lrp_ls_id = $this->input->post('lrp_ls_id');
        $lrp_lst_id = $this->input->post('lrp_lst_id');
        $lrp_birth_quantity = $this->input->post('lrp_birth_quantity');
        $lrp_approx_selling_price = $this->input->post('lrp_approx_selling_price');
        $date = $this->input->post('lrp_birth_date');
        $lrp_birth_date = date("Y-m-d", strtotime($date));
        $lrp_description = $this->input->post('lrp_description');

        // Assign Data
        $lrp_assign_sh_id = $this->input->post('lrp_assign_sh_id');
        $lrp_assign_batch_id = $this->input->post('lsh_batch_number');
        $lrp_batch_title = $this->input->post('lrp_batch_title');
        $lrp_description = $this->input->post('lrp_description');
        $date1 = $this->input->post('lrp_assign_date');
        $lrp_birth_date = date("Y-m-d", strtotime($date1));

        if (empty($lrp_id)) {
            $data = array(
                'lrp_batch_id' => $lrp_batch_id,
                'lrp_sh_id' => $lrp_sh_id,
                'lrp_ls_id' => $lrp_ls_id,
                'lrp_lst_id' => $lrp_lst_id,
                'lrp_birth_quantity' => $lrp_birth_quantity,
                'lrp_approx_selling_price' => $lrp_approx_selling_price,
                'lrp_birth_date' => $lrp_birth_date,
                'lrp_description' => $lrp_description,
                'lrp_status' => 1,
                'lrp_created_at' => get_current_time(),
                'lrp_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $livestockReproductionId = $this->product_model->insertData('livestock_reproduction', $data);

            // Livestock Assigned Shed Table

            $assignToShedSummaryData = array(
                'lshs_reproduction_id' => $livestockReproductionId,
                'lshs_batch_id' => $lrp_assign_batch_id,
                'lshs_batch_title' => $lrp_batch_title,
                'lshs_sh_id' => $lrp_assign_sh_id,
                'lshs_assign_total_quantity' => $lrp_birth_quantity,
                'lshs_description' => $lrp_description,
                'lshs_assign_date' => $lrp_birth_date,
                'lshs_status' => 1,
                'lshs_reproduction_status' => 1,
                'lshs_type' => 2,
                'lshs_created_at' => get_current_time(),
                'lshs_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $assignSummaryId = $this->product_model->insertData('live_assigned_shed_summary', $assignToShedSummaryData);
            // Value Data
            $assignToShedValueData = array(
                'lsh_lshs_id' => $assignSummaryId,
                'lsh_reproduction_id' => $livestockReproductionId,
                'lsh_batch_id' => $lrp_assign_batch_id,
                'lsh_sh_id' => $lrp_assign_sh_id,
                'lsh_purv_ls_id' => $lrp_ls_id,
                'lsh_purv_lst_id' => $lrp_lst_id,
                'lsh_assign_quantity' => $lrp_birth_quantity,
                'lsh_status' => 1,
                'lsh_created_at' => get_current_time(),
                'lsh_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->product_model->insertData('live_assigned_shed', $assignToShedValueData);
            $this->db->trans_complete();
            $this->session->set_flashdata('success', 'Livestock reproduction added successfully.');
            redirect('product/listLivestockReproduction');
        } else {
            // Update Reproduction Table
            $updateReproductionData = array(
                'lrp_batch_id' => $lrp_batch_id,
                'lrp_sh_id' => $lrp_sh_id,
                'lrp_ls_id' => $lrp_ls_id,
                'lrp_lst_id' => $lrp_lst_id,
                'lrp_birth_quantity' => $lrp_birth_quantity,
                'lrp_birth_date' => $lrp_birth_date,
                'lrp_approx_selling_price' => $lrp_approx_selling_price,
                'lrp_updated_at' => get_current_time(),
                'lrp_updated_by' => $this->ion_auth->user()->row()->user_id
            );
            if (!empty($lrp_id)) {
                $this->product_model->updateData('livestock_reproduction', 'lrp_id', $lrp_id, $updateReproductionData);
            }

            // Update Reproduction Livestock Assigned Shed Table
            // Summary Table
            $updateAssignToShedSummaryData = array(
                'lshs_batch_id' => $lrp_assign_batch_id,
                'lshs_batch_title' => $lrp_batch_title,
                'lshs_sh_id' => $lrp_assign_sh_id,
                'lshs_assign_total_quantity' => $lrp_birth_quantity,
                'lshs_description' => $lrp_description,
                'lshs_assign_date' => $lrp_birth_date,
                'lshs_status' => 1,
                'lshs_reproduction_status' => 1,
                'lshs_type' => 2,
                'lshs_updated_at' => get_current_time(),
                'lshs_updated_by' => $this->ion_auth->user()->row()->user_id
            );
            if (!empty($lrp_id)) {
                $this->product_model->updateData('live_assigned_shed_summary', 'lshs_reproduction_id', $lrp_id, $updateAssignToShedSummaryData);
            }
            // Value Data
            $updateAssignToShedData = array(
                'lsh_batch_id' => $lrp_batch_id,
                'lsh_sh_id' => $lrp_sh_id,
                'lsh_purv_ls_id' => $lrp_ls_id,
                'lsh_purv_lst_id' => $lrp_lst_id,
                'lsh_assign_quantity' => $lrp_birth_quantity,
                'lsh_updated_at' => get_current_time(),
                'lsh_updated_by' => $this->ion_auth->user()->row()->user_id
            );
            if (!empty($lrp_id)) {
                $this->product_model->updateData('live_assigned_shed', 'lsh_reproduction_id', $lrp_id, $updateAssignToShedData);
            }
            $this->db->trans_complete();
            $this->session->set_flashdata('success', 'Livestock reproduction updated successfully.');
            redirect('product/listLivestockReproduction');
        }
    }

    function deleteLivestockReproduction()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $this->db->trans_start();
        $lrp_id = $this->input->post('lrp_id');
        // 
        $deleteReproductionData = array(
            'lrp_status' => 0,
            'lrp_updated_at' => get_current_time(),
            'lrp_updated_by' => $this->ion_auth->user()->row()->user_id
        );

        $this->product_model->updateData('livestock_reproduction', 'lrp_id', $lrp_id, $deleteReproductionData);

        // From Assign Table
        // Summary Table
        $deleteAssignSummaryToShedData = array(
            'lshs_reproduction_status' => 0,
            'lshs_status' => 0,
            'lshs_updated_at' => get_current_time(),
            'lshs_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->updateData('live_assigned_shed_summary', 'lshs_reproduction_id', $lrp_id, $deleteAssignSummaryToShedData);

        // Value Table
        $deleteAssignValueToShedData = array(
            'lsh_reproduction_status' => 0,
            'lsh_status' => 0,
            'lsh_updated_at' => get_current_time(),
            'lsh_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->product_model->updateData('live_assigned_shed', 'lsh_reproduction_id', $lrp_id, $deleteAssignValueToShedData);

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Livestock reproduction deleted successfully.');
        redirect('product/listLivestockReproduction');
    }

    // Get livestock 
    function editLivestockReproductionAssignToShedByAjax()
    {
        $lrp_id = $this->input->get('lrp_id');
        $data['valueFromAssignedShed'] = $this->product_model->getLivestockAssignedDataByReproductionId($lrp_id);
        $data['valueFromAssignedShedSummary'] = $this->product_model->getLivestockAssignedSummaryDataByReproductionId($lrp_id);
        echo json_encode($data);
    }

    public function viewLivestockReproduction()
    {
        $lrp_id = $this->input->get('lrp_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['reproductionById'] = $this->product_model->getLivestockReproductionById($lrp_id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_livestock_reproduction', $data);
        $this->load->view('home/footer'); // just the header file
    }




    // End
}

/* End of file product.php */
/* Location: ./application/modules/product/controllers/product.php */
