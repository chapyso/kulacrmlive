<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <style>
            .product-page-card {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 16px;
                box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04);
                margin-bottom: 30px;
                overflow: hidden;
                transition: all 0.25s ease;
            }
            .product-page-card:hover {
                box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
            }
            .product-page-card .panel-heading {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
                padding: 18px 24px !important;
                border-bottom: 1px solid #e2e8f0 !important;
                font-weight: 700 !important;
                font-size: 17px !important;
                color: #0f172a !important;
                display: flex !important;
                align-items: center !important;
                gap: 10px !important;
                margin-bottom: 0 !important;
            }
            .product-page-card .panel-heading i {
                color: #10b981;
                font-size: 19px;
            }
            .product-page-card .panel-body {
                padding: 24px !important;
            }
            .product-btn-group-inline {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                align-items: center;
            }
            .product-btn-group-inline .button, .product-btn-group-inline button {
                display: inline-flex !important;
                align-items: center !important;
                gap: 6px !important;
                padding: 7px 14px !important;
                font-size: 13px !important;
                font-weight: 600 !important;
                border-radius: 8px !important;
                line-height: 1.4 !important;
                white-space: nowrap !important;
            }
            .adv-table table {
                width: 100% !important;
            }
            .table-responsive-custom {
                width: 100%;
                overflow-x: auto;
                border-radius: 12px;
                border: 1px solid #e2e8f0;
            }
        </style>

        <!-- Section 1: Assigned Product Lists (Full Width 100% Line 1) -->
        <div class="col-md-12 col-xs-12">
            <section class="panel product-page-card">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('assigned_product_list'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table">
                        <div class="clearfix" style="margin-bottom: 20px;">
                            <a href="<?php echo base_url('product/listProductCategory'); ?>" style="text-decoration:none; margin-right: 6px;">
                                <div class="btn-group">
                                    <button class="button button-info">
                                        <i class="fa-solid fa-circle-arrow-up"></i> <?= lang('product'); ?> <?= lang('category'); ?> <?= lang('list'); ?>
                                    </button>
                                </div>
                            </a>
                            <a href="<?php echo base_url('sale/listProductSale'); ?>" style="text-decoration:none; margin-right: 6px;">
                                <div class="btn-group">
                                    <button class="button button-success">
                                        <i class="fa-solid fa-circle-arrow-up"></i> <?= lang('product_sale'); ?> <?= lang('list'); ?>
                                    </button>
                                </div>
                            </a>
                            <button class="export" onclick="javascript:window.print();" style="margin-right: 6px;"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                            <a data-toggle="modal" href="#informationPopup" class="button button-purple export" style="text-decoration:none;"><i class="fa-solid fa-circle-question"></i> <?= lang('information'); ?></a>
                        </div>
                        <div class="table-responsive-custom">
                            <table class="table table-striped table-hover table-bordered" id="editable-sample">
                                <thead>
                                    <tr>
                                        <th><?= lang('serialNo'); ?></th>
                                        <th><?= lang('product_name'); ?></th>
                                        <th><?= lang('shed'); ?></th>
                                        <th><?= lang('batch'); ?></th>
                                        <th><?= lang('in_stock'); ?></th>
                                        <th style="min-width: 280px;"><?= lang('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial = 0;
                                    foreach ($assignedProducts as $value) {
                                        $serial++;
                                    ?>
                                        <tr>
                                            <td><?= $serial ?></td>
                                            <td><strong><?= $this->product_model->getProductById($value->pra_pr_id)->pr_name; ?></strong></td>
                                            <td><?= $this->shed_model->getShedById($value->pra_shed_id)->sh_no ?>: <?= $this->shed_model->getShedById($value->pra_shed_id)->sh_title ?> </td>
                                            <td><?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($value->pra_shed_id, $value->pra_batch_id)->lshs_batch_id ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($value->pra_shed_id, $value->pra_batch_id)->lshs_batch_title ?> </td>
                                            <td>
                                                <?php $totalProduction = $this->product_model->getTotalProductionQuantityByShedAndBatchId($value->pra_shed_id, $value->pra_batch_id);
                                                $totalWaste = $this->product_model->getTotalProductWastedQuantityByProductAssignId($value->pra_id);
                                                $totalSold = $this->sale_model->getProductAssignedWiseProductSaleValue($value->pra_id, 'prsv_quantity');
                                                $inStockProduction = $totalProduction - $totalWaste - $totalSold;
                                                if ($inStockProduction) {
                                                    echo '<span class="label label-success" style="font-size:12px; padding: 4px 8px;">' . $inStockProduction . ' ';
                                                    $productInfo = $this->product_model->getProductById($value->pra_pr_id);
                                                    $unit = $this->settings_model->getUnitById($productInfo->pr_unit_id);
                                                    if ($unit) {
                                                        echo $unit->un_name;
                                                    }
                                                    echo '</span>';
                                                } else {
                                                    echo '<span class="label label-default" style="font-size:12px; padding: 4px 8px;">0</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="product-btn-group-inline">
                                                    <?php
                                                    // Complete/Incomplete Status 
                                                    $batchActiveInactiveStatusInfo = $this->settings_model->getSingleData('live_assigned_shed_summary', ['lshs_sh_id' => $value->pra_shed_id, 'lshs_batch_id' =>  $value->pra_batch_id, 'lshs_status' => 1])->lshs_active_status; ?>

                                                    <button <?php echo ($batchActiveInactiveStatusInfo == 1) ? "disabled" : ""; ?> type="button" class="button button-primary addProductToStock" data-toggle="modal" data-id="<?= $value->pra_id; ?>" data-product-id="<?= $value->pra_pr_id; ?>" data-product-name="<?= $this->product_model->getProductById($value->pra_pr_id)->pr_name; ?>" data-category-name=" <?= $this->product_model->getProductCategoryById($this->product_model->getProductById($value->pra_pr_id)->pr_prc_id)->prc_name; ?>" data-unit="<?php echo $this->settings_model->getUnitById($this->product_model->getProductById($value->pra_pr_id)->pr_unit_id)->un_name; ?>" data-shed-value="<?= $this->shed_model->getShedById($value->pra_shed_id)->sh_no ?>: <?= $this->shed_model->getShedById($value->pra_shed_id)->sh_title ?>" data-batch-value="<?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($value->pra_shed_id, $value->pra_batch_id)->lshs_batch_id ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($value->pra_shed_id, $value->pra_batch_id)->lshs_batch_title ?>" data-shed-id="<?= $value->pra_shed_id ?>" data-batch-id="<?= $value->pra_batch_id ?>"><i class="fas fa-plus-circle"></i> <?= lang('add_stock'); ?></button>

                                                    <!-- conditional delete -->
                                                    <?php
                                                    $countDataIfAvailableInSaleModule = $this->settings_model->getCountRow('product_sale_value', 'prsv_id', ['prsv_pra_id' =>  $value->pra_id, 'prsv_status' => 1]);
                                                    if ($countDataIfAvailableInSaleModule  == 0) { ?>
                                                        <form action="<?php echo base_url('product/deleteProductAssign'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('all_product_delete_warning'); ?>');">
                                                            <input type="hidden" name="pra_id" value="<?= $value->pra_id; ?>">
                                                            <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                            <button type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                                        </form>
                                                    <?php } else { ?>
                                                        <button type="button" class="button button-danger" title="<?= lang('this_item_used_another_places'); ?>" disabled><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                                    <?php } ?>

                                                    <a href="<?php echo base_url('') ?>product/viewAssignedShedAndBatchWiseProduction?pra_id=<?= $value->pra_id; ?>"><button type="button" class="button button-info"><i class="fas fa-eye"></i> <?= lang('view_stock'); ?></button></a>
                                                    <?php if ($inStockProduction) { ?>
                                                        <button <?php echo ($batchActiveInactiveStatusInfo == 1) ? "disabled" : ""; ?> type="button" class="button button-warning addWastedProduct" data-toggle="modal" data-stock-quantity="<?= $inStockProduction; ?>" data-id="<?= $value->pra_id; ?>" data-product-id="<?= $value->pra_pr_id; ?>" data-product-name="<?= $this->product_model->getProductById($value->pra_pr_id)->pr_name; ?>" data-category-name=" <?= $this->product_model->getProductCategoryById($this->product_model->getProductById($value->pra_pr_id)->pr_prc_id)->prc_name; ?>" data-unit="<?php echo $this->settings_model->getUnitById($this->product_model->getProductById($value->pra_pr_id)->pr_unit_id)->un_name; ?>" data-shed-value="<?= $this->shed_model->getShedById($value->pra_shed_id)->sh_no ?>: <?= $this->shed_model->getShedById($value->pra_shed_id)->sh_title ?>" data-batch-value="<?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($value->pra_shed_id, $value->pra_batch_id)->lshs_batch_id ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($value->pra_shed_id, $value->pra_batch_id)->lshs_batch_title ?>" data-shed-id="<?= $value->pra_shed_id ?>" data-batch-id="<?= $value->pra_batch_id ?>"><i class="fa fa-plus-circle"></i> <?= lang('waste'); ?></button>
                                                    <?php } else { ?>
                                                        <button type="button" class="button button-warning" title="There is no production in stock." disabled><i class="fas fa-plus-circle"></i> <?= lang('waste'); ?></button>
                                                    <?php } ?>
                                                    <a href="<?php echo base_url('') ?>product/viewWastedShedAndBatchWiseProduction?pra_id=<?= $value->pra_id; ?>"><button type="button" class="button button-info"><i class="fas fa-eye"></i> <?= lang('view_waste'); ?></button></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Section 2: Product List (Full Width 100% Line 2 Below Section 1) -->
        <div class="col-md-12 col-xs-12">
            <section class="panel product-page-card">
                <header class="panel-heading">
                    <i class="fas fa-cubes"></i> <?= lang('product_lists'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table">
                        <div class="clearfix button__margin" style="margin-bottom: 20px;">
                            <a data-toggle="modal" href="#myModal" style="text-decoration:none; margin-right: 6px;">
                                <div class="btn-group">
                                    <button class="button button-primary">
                                        <i class="fas fa-plus-circle"></i> <?php echo lang('add_new_product'); ?>
                                    </button>
                                </div>
                            </a>
                            <a data-toggle="modal" href="<?php echo base_url('sale/addNewProductSale') ?>" style="text-decoration:none; margin-right: 6px;">
                                <div class="btn-group">
                                    <button class="button button-success">
                                        <i class="fas fa-plus-circle"></i> <?= lang('product_sale'); ?>
                                    </button>
                                </div>
                            </a>
                            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                        </div>
                        <div class="table-responsive-custom">
                            <table class="table table-striped table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th><?= lang('serialNo'); ?>.</th>
                                        <th><?= lang('product_name'); ?></th>
                                        <th><?= lang('category'); ?></th>
                                        <th><?= lang('product_unit'); ?></th>
                                        <th style="min-width: 320px;"><?php echo lang('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial = 0;
                                    foreach ($products->result() as $product) {
                                        $serial++;
                                    ?>
                                        <tr class="">
                                            <td><?= $serial ?></td>
                                            <td><strong><?= $product->pr_name; ?></strong></td>
                                            <td><span class="label label-info" style="font-size:12px; padding: 4px 8px;"><?= $this->product_model->getProductCategoryById($product->pr_prc_id)->prc_name; ?></span></td>
                                            <td>
                                                <?php echo $this->settings_model->getUnitById($product->pr_unit_id)->un_name; ?>
                                            </td>
                                            <td>
                                                <div class="product-btn-group-inline">
                                                    <button type="button" class="button button-warning editButton" data-toggle="modal" data-id="<?= $product->pr_id; ?>"><i class="fas fa-edit"></i> <?php echo lang('edit'); ?></button>

                                                    <!-- conditional delete -->
                                                    <?php
                                                    $countDataIfAvailableProductAssign = $this->settings_model->getCountRow('product_assign', 'pra_id', ['pra_pr_id' => $product->pr_id, 'pra_status' => 1]);
                                                    if ($countDataIfAvailableProductAssign  == 0) { ?>
                                                        <form action="<?php echo base_url('product/deleteProduct'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                            <input type="hidden" name="pr_id" value="<?= $product->pr_id; ?>">
                                                            <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                            <button type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                                        </form>
                                                    <?php } else { ?>
                                                        <button type="button" class="button button-danger" title="<?= lang('this_item_used_another_places'); ?>" disabled><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                                    <?php } ?>

                                                    <button type="button" class="button button-primary addProductAssign" data-toggle="modal" data-id="<?= $product->pr_id; ?>" data-product-name="<?= $product->pr_name; ?>" data-category-name=" <?= $this->product_model->getProductCategoryById($product->pr_prc_id)->prc_name; ?>" data-unit="<?php echo $this->settings_model->getUnitById($product->pr_unit_id)->un_name; ?>"><i class="fa fa-plus-circle"></i> <?= lang('assign_product'); ?></button>
                                                    <a href="<?php echo base_url('') ?>product/viewProductWiseProduction?pr_id=<?= $product->pr_id; ?>"><button type="button" class="button button-info"><i class="fa fa-eye"></i> <?= lang('details'); ?></button></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->

<input type="hidden" id="productUnitQuantityAlert" value="<?php echo $this->report_model->getCountRow('unit', 'un_id', ['un_status' => 1]); ?>">
<input type="hidden" id="productCategoryAlert" value="<?php echo $this->report_model->getCountRow('product_category', 'prc_id', ['prc_status' => 1]); ?>">


<!--footer start-->
<!-- Add New product -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?php echo lang('add_new_product'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" action="<?php echo base_url('product/insertProduct'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('name'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="pr_name" id="" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('product_category'); ?><span class="text-danger">*</span></label>
                        <select name="pr_prc_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                            <option value=""><?= lang('please_select_category'); ?></option>
                            <?php if (!empty($productCategories)) foreach ($productCategories->result() as $categories) {
                            ?>
                                <option value="<?php echo $categories->prc_id ?>"><?php echo $categories->prc_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('product_unit'); ?><span class="text-danger">*</span></label>
                        <select name="pr_unit_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                            <option value=""><?= lang('please_select_unit'); ?></option>
                            <?php foreach ($units->result() as $unit) { ?>
                                <option value="<?= $unit->un_id ?>"><?= $unit->un_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('approx_selling_price_per_quantity'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control input__number" name="pr_selling_price" id="" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="pr_description" class="form-control" id="" rows="5" placeholder="Enter description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="pr_id" value=''>

                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Edit product -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-edit"></i> <?php echo lang('edit_product'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" id="productEditForm" action="<?php echo base_url('product/updateProduct'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('name'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="pr_name" id="" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('product_category'); ?><span class="text-danger">*</span></label>
                        <select name="pr_prc_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                            <option value=""><?= lang('please_select_category'); ?></option>
                            <?php if (!empty($productCategories)) foreach ($productCategories->result() as $types) {
                            ?>
                                <option value="<?php echo $types->prc_id ?>"><?php echo $types->prc_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('product_unit'); ?><span class="text-danger">*</span></label>
                        <select name="pr_unit_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                            <option value=""><?= lang('please_select_unit'); ?></option>
                            <?php foreach ($units->result() as $unit) { ?>
                                <option value="<?= $unit->un_id ?>"><?= $unit->un_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('approx_selling_price_per_quantity'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control input__number" name="pr_selling_price" id="" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="pr_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="pr_id" value=''>
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Product assign -->
<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('product_assign'); ?> </strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" action="<?php echo base_url('product/insertProductAssign'); ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('product_name'); ?></label>
                            <input type="text" class="form-control" name="" id="productNameAssign" value='' placeholder="" readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('category'); ?> </label>
                            <input type="text" class="form-control" name="" id="categoryNameAssign" value='' placeholder="" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('shed'); ?><span class="text-danger">*</span></label>
                            <select name="pra_shed_id" id="shed_id1" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                <option value=""><?= lang('please_select_shed'); ?></option>
                                <?php if (!empty($sheds)) foreach ($sheds as $shed) {
                                ?>
                                    <option value="<?php echo $shed->sh_id ?>"><?php echo $shed->sh_no ?>: <?php echo $shed->sh_title ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('batch'); ?><span class="text-danger">*</span></label>
                            <select name="pra_batch_id" id="batch_id1" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                <option value=""><?= lang('please_select_batch'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="pra_description" class="form-control" id="" rows="5" placeholder="Enter description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="pra_pr_id" value='' id="productIdAssign">
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button" id="submitButton"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Add product to stock -->
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fas fa-plus-circle"></i> <?= lang('add_product_to_stock'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" action="<?php echo base_url('product/insertProductionToStock'); ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('product_name'); ?></label>
                            <input type="text" class="form-control" name="" id="productName" value='' placeholder="" readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('category'); ?></label>
                            <input type="text" class="form-control" name="" id="categoryName" value='' placeholder="" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('shed'); ?></label>
                            <input type="text" class="form-control" name="" id="shedValue" value='' placeholder="" readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('batch'); ?></label>
                            <input type="text" class="form-control" name="" id="batchValue" value='' placeholder="" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('production_quantity'); ?><span class="text-danger">*</span></label>
                            <input type="text" class="form-control input__number" name="prs_production_quantity" id="" value='' placeholder="" required>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('production_unit'); ?></label>
                            <input type="text" class="form-control" name="" id="productUnit" value='' placeholder="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('production_date'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker" name="prs_date" id="" value='<?= get_current_date(); ?>' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="prs_description" class="form-control" id="" rows="5" placeholder="Enter description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="prs_pr_id" value='' id="productId">
                    <input type="hidden" name="prs_pra_id" value='' id="productAssignId">
                    <input type="hidden" name="prs_shed_id" value='' id="shedId">
                    <input type="hidden" name="prs_batch_id" value='' id="batchId">
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Add Wasted Product to stock -->
<div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('add_wasted_production'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" action="<?php echo base_url('product/insertProductionWasted'); ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('product_name'); ?></label>
                            <input type="text" class="form-control" name="" id="productNameW" value='' placeholder="" readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('category'); ?></label>
                            <input type="text" class="form-control" name="" id="categoryNameW" value='' placeholder="" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('shed'); ?></label>
                            <input type="text" class="form-control" name="" id="shedValueW" value='' placeholder="" readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('batch'); ?></label>
                            <input type="text" class="form-control" name="" id="batchValueW" value='' placeholder="" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('product_in_stock'); ?></label>
                            <input type="text" class="form-control" name="" id="productInStockW" value='' placeholder="" readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('wasted_quantity'); ?><span class="text-danger">*</span></label>
                            <input type="text" class="form-control input__number" name="prw_wasted_quantity" id="wastedQuantity" value='' placeholder="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('production_date'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker" name="prw_date" id="" value='<?= get_current_date(); ?>' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="prw_description" class="form-control" id="" rows="5" placeholder="Enter description" style="height: auto !important;" required></textarea>
                    </div>
                    <input type="hidden" name="prw_pr_id" value='' id="productIdW">
                    <input type="hidden" name="prw_pra_id" value='' id="productAssignIdW">
                    <input type="hidden" name="prw_shed_id" value='' id="shedIdW">
                    <input type="hidden" name="prw_batch_id" value='' id="batchIdW">
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>



<!-- Information popup -->
<div class="modal fade" id="informationPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-center">
        <div class="modal-content">
            <div class="modal-header bg-purple">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><strong><i class="fa-solid fa-circle-info"></i> <?php echo lang('basic_information'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <div class="row">
                    <div class="col-xs-6">
                        <ol class="information__modal__ol">
                            <li>
                                <p><?= lang('production_popup_message_one'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('production_popup_message_two'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('production_popup_message_three'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('production_popup_message_four'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('production_popup_message_five'); ?></p>
                            </li>
                        </ol>
                    </div>
                    <div class="col-xs-6">
                        <img class="img-square information__modal__image" src="<?php echo base_url('uploads/information/Assigned_Product_Lists.jpg'); ?>" alt="No img">
                    </div>
                </div>
                <section class="text-right">
                    <button type="button" class="button button-purple" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> <?= lang('close'); ?></button>
                </section>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Information popup -->



<!-- Javascript For Edit product -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Edit Product
        $(".editButton").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            var date = $(this).attr('data-date');
            var batch_id = $(this).attr('data-batch-id');
            $('#productEditForm').find('[name="pr_shed_id"]').attr("batch_id_edit", batch_id).end()
            $.ajax({
                url: 'product/editProductByJason?pr_id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                // Populate the form fields with the data returned from server
                $('#productEditForm').find('[name="pr_id"]').val(response.products.pr_id).end()
                $('#productEditForm').find('[name="pr_prc_id"]').val(response.products.pr_prc_id).trigger('change').end()
                $('#productEditForm').find('[name="pr_name"]').val(response.products.pr_name).end()
                $('#productEditForm').find('[name="pr_unit_id"]').val(response.products.pr_unit_id).trigger('change').end()
                $('#productEditForm').find('[name="pr_selling_price"]').val(response.products.pr_selling_price).end()
                $('#productEditForm').find('[name="pr_description"]').val(response.products.pr_description).end()
                $('#productEditForm').find('[name="pr_date"]').val(date).end()
                $('#myModal2').modal('show');
            });
        });

        // Add Product to stock
        $(".addProductToStock").click(function(e) {
            e.preventDefault(e);
            var iid = $(this).attr('data-id');
            var product_id = $(this).attr('data-product-id');
            var product_name = $(this).attr('data-product-name');
            var category_name = $(this).attr('data-category-name');
            var shed_id = $(this).attr('data-shed-id');
            var batch_id = $(this).attr('data-batch-id');
            var shed_value = $(this).attr('data-shed-value');
            var batch_value = $(this).attr('data-batch-value');
            var unit = $(this).attr('data-unit');
            $('#myModal3').modal('show');
            $("#productAssignId").val(iid);
            $("#productId").val(product_id);
            $("#productName").val(product_name);
            $("#categoryName").val(category_name);
            $("#shedId").val(shed_id);
            $("#batchId").val(batch_id);
            $("#shedValue").val(shed_value);
            $("#batchValue").val(batch_value);
            $("#productUnit").val(unit);
        });

        // Add Product Assign
        $(".addProductAssign").click(function(e) {
            e.preventDefault(e);
            var iid = $(this).attr('data-id');
            var product_name = $(this).attr('data-product-name');
            var category_name = $(this).attr('data-category-name');
            var unit = $(this).attr('data-unit');
            $('#myModal4').modal('show');
            $("#productIdAssign").val(iid);
            $("#productNameAssign").val(product_name);
            $("#categoryNameAssign").val(category_name);
            $("#productUnitAssign").val(unit);
        });

        // Cascading - Add
        $('#shed_id1').on('change', function() {
            var iid = $(this).val();
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('food/getActiveBatchByShedId'); ?>',
                data: {
                    shed_id: iid
                },
                success: function(data) {
                    $('#batch_id1').html(data);
                }
            });
        });

        // Already Vaccine Assigned Alert
        $("#batch_id1").on('change', function() {
            var batch_id = $(this).val();
            var shed_id = $("#shed_id1").val();
            var product_id = $("#productIdAssign").val();
            if (batch_id != '') {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('product/checkAssignedProductBatchByAjax'); ?>',
                    data: {
                        batch_id: batch_id,
                        shed_id: shed_id,
                        product_id: product_id,
                    },
                    success: function(data) {
                        if (data != 0 && data != product_id) {
                            $('#submitButton').attr('disabled', 'disabled');
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: '<?= lang('you_have_already_assign_this_product'); ?> '
                            });
                        } else {
                            $('#submitButton').removeAttr('disabled');
                        }
                    }
                });
            }
        });

        //Add Wasted Production Modal
        $(".addWastedProduct").click(function(e) {
            e.preventDefault(e);
            var iid = $(this).attr('data-id');
            var product_id = $(this).attr('data-product-id');
            var product_name = $(this).attr('data-product-name');
            var category_name = $(this).attr('data-category-name');
            var shed_id = $(this).attr('data-shed-id');
            var batch_id = $(this).attr('data-batch-id');
            var shed_value = $(this).attr('data-shed-value');
            var batch_value = $(this).attr('data-batch-value');
            var stock_quantity = $(this).attr('data-stock-quantity');
            var unit = $(this).attr('data-unit');
            $('#myModal5').modal('show');
            $("#productAssignIdW").val(iid);
            $("#productIdW").val(product_id);
            $("#productNameW").val(product_name);
            $("#categoryNameW").val(category_name);
            $("#shedIdW").val(shed_id);
            $("#batchIdW").val(batch_id);
            $("#shedValueW").val(shed_value);
            $("#batchValueW").val(batch_value);
            $("#productInStockW").val(stock_quantity + ' ' + unit);

            $("#wastedQuantity").keyup(function() {
                var wastedQuantity = $(this).val();
                if (parseFloat(wastedQuantity) > parseFloat(stock_quantity)) {
                    $("#wastedQuantity").val('');
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('please_enter_a_valid_value'); ?>"
                    });
                }
            });

        });

    });
</script>

<script>
    $(document).ready(function() {
        // toastr custom css
        toastr.options = {
            'closeButton': true,
            'debug': false,
            'newestOnTop': false,
            'progressBar': true,
            'positionClass': 'toast-bottom-right',
            'preventDuplicates': false,
            'showDuration': '1000',
            'hideDuration': '1000',
            'timeOut': '10000',
            'extendedTimeOut': '1000',
            'showEasing': 'swing',
            'hideEasing': 'linear',
            'showMethod': 'fadeIn',
            'hideMethod': 'fadeOut',
        }
        // shed quantity alert
        function productUnitQuantityAlert() {
            var productUnitQuantityAlert = $("#productUnitQuantityAlert").val();

            if (productUnitQuantityAlert == 0) {
                toastr.warning('No product unit found. Please go to setting->unit module and create unit.');
            }
        }
        productUnitQuantityAlert();

        // shed quantity alert
        function productCategoryAlert() {
            var productCategoryAlert = $("#productCategoryAlert").val();

            if (productCategoryAlert == 0) {
                toastr.warning('No product category found. Please go to product category module and create new category.');
            }
        }
        productCategoryAlert();
        <?php if ($this->session->flashdata('success')): ?>
        toastr.success('<?= html_escape($this->session->flashdata('success')); ?>');
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
        toastr.error('<?= html_escape($this->session->flashdata('error')); ?>');
        <?php endif; ?>
    });
</script>