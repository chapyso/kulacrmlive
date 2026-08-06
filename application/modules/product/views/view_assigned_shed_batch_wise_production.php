<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('production_stock_details'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table">
                        <div class="clearfix">
                            <a href="<?php echo base_url('product/listProduct'); ?>">
                                <div class="btn-group">
                                    <button class="button button-primary">
                                        <i class="fa-solid fa-circle-arrow-left"></i>
                                    </button>
                                </div>
                            </a>
                            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                        </div>
                        <div class="space15">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="new__cards__auto card__box alert-success">
                                        <div class="col-xs-12">
                                            <h4 class="text-center"><strong><?= lang('production_details'); ?></strong></h4>
                                            <div class="col-xs-4">
                                                <table>
                                                    <tr>
                                                        <td class="text-center" colspan="2">
                                                            <h5><strong><?= lang('product_information'); ?></strong></h5>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__padding">
                                                            <p><strong><?= lang('product_name'); ?></strong></p>
                                                        </td>
                                                        <td class="table__padding">
                                                            <p>: <?= $productById->pr_name ?></p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__padding">
                                                            <p><strong><?= lang('product_category'); ?></strong></p>
                                                        </td>
                                                        <td class="table__padding">
                                                            <p>: <?php echo $productCategory = $this->product_model->getProductCategoryById($productById->pr_prc_id)->prc_name; ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__padding">
                                                            <p><strong><?= lang('shed'); ?></strong></p>
                                                        </td>
                                                        <td class="table__padding">
                                                            <p>: <?= $this->shed_model->getShedById($productAssignedById->pra_shed_id)->sh_no ?>: <?= $this->shed_model->getShedById($productAssignedById->pra_shed_id)->sh_title ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__padding">
                                                            <p><strong><?= lang('batch'); ?></strong></p>
                                                        </td>
                                                        <td class="table__padding">
                                                            <p>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($productAssignedById->pra_shed_id, $productAssignedById->pra_batch_id)->lshs_batch_id ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($productAssignedById->pra_shed_id, $productAssignedById->pra_batch_id)->lshs_batch_title ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__padding">
                                                            <p><strong><?= lang('production_unit'); ?></strong></p>
                                                        </td>
                                                        <td class="table__padding">
                                                            <p>: <?php $unit = $this->settings_model->getUnitById($productById->pr_unit_id);
                                                                    if ($unit) {
                                                                        echo  $unit->un_name;
                                                                    }
                                                                    ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__padding">
                                                            <p><strong><?= lang('approx_selling_price_per_quantity'); ?></strong></p>
                                                        </td>
                                                        <td class="table__padding">
                                                            <p>: <?php echo $settings->currency . number_format_currency($productById->pr_selling_price, 2); ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__padding">
                                                            <p><strong><?= lang('note'); ?></strong></p>
                                                        </td>
                                                        <td class="table__padding">
                                                            <p>: <?= $productById->pr_description ?></p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <!-- Product Wise Production -->
                                            <div class="col-xs-4">
                                                <table>
                                                    <tr>
                                                        <td class="text-center" colspan="2">
                                                            <h5><strong><?= lang('product_wise_stock_information'); ?></strong></h5>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__padding">
                                                            <p><strong><?= lang('total_production'); ?></strong></p>
                                                        </td>
                                                        <td class="table__padding">
                                                            <p>: <?php echo $totalProduction = $this->product_model->getTotalProductionQuantityByProductId($productById->pr_id);
                                                                    $unit = $this->settings_model->getUnitById($productById->pr_unit_id);
                                                                    if ($totalProduction) {
                                                                        if ($unit) {
                                                                            echo  ' ' . $unit->un_name;
                                                                        }
                                                                    }
                                                                    ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__padding">
                                                            <p><strong><?= lang('total_sold'); ?></strong></p>
                                                        </td>
                                                        <td class="table__padding">
                                                            <p>: <?php echo $totalSoldProduction = $this->sale_model->getProductSoldQuantityByProductId($productById->pr_id, 'prsv_quantity');
                                                                    if ($totalSoldProduction) {
                                                                        if ($unit) {
                                                                            echo  ' ' . $unit->un_name;
                                                                        }
                                                                    }
                                                                    ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__padding">
                                                            <p><strong><?= lang('total_wasted'); ?></strong></p>
                                                        </td>
                                                        <td class="table__padding">
                                                            <p>: <?php echo $totalWastedProduction = $this->product_model->getTotalProductWastedQuantityByProductId($productById->pr_id);
                                                                    if ($totalWastedProduction) {
                                                                        if ($unit) {
                                                                            echo  ' ' . $unit->un_name;
                                                                        }
                                                                    }
                                                                    ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__padding">
                                                            <p><strong><?= lang('production_in_stock'); ?></strong></p>
                                                        </td>
                                                        <td class="table__padding">
                                                            <p>: <?php
                                                                    echo $totalStockProduction = $totalProduction - $totalSoldProduction - $totalWastedProduction;
                                                                    if ($totalStockProduction) {
                                                                        if ($unit) {
                                                                            echo  ' ' . $unit->un_name;
                                                                        }
                                                                    }
                                                                    ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <!-- Shed and Batch Wise Production -->
                                            <div class="col-xs-4">
                                                <table>
                                                    <tr>
                                                        <td class="text-center" colspan="2">
                                                            <h5><strong><?= lang('shed_and_batch_wise_stock_information'); ?></strong></h5>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__padding">
                                                            <p><strong><?= lang('total_production'); ?></strong></p>
                                                        </td>
                                                        <td class="table__padding">
                                                            <p>: <?php echo $totalProductionSB = $this->product_model->getTotalProductionQuantityByShedAndBatchId($productAssignedById->pra_shed_id, $productAssignedById->pra_batch_id);
                                                                    if ($totalProductionSB) {
                                                                        if ($unit) {
                                                                            echo ' ' . $unit->un_name;
                                                                        }
                                                                    }
                                                                    ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__padding">
                                                            <p><strong><?= lang('total_sold'); ?></strong></p>
                                                        </td>
                                                        <td class="table__padding">
                                                            <p>: <?php echo $totalSoldSB = $this->sale_model->getProductAssignedWiseProductSaleValue($productAssignedById->pra_id, 'prsv_quantity');
                                                                    if ($totalSoldSB) {
                                                                        if ($unit) {
                                                                            echo ' ' . $unit->un_name;
                                                                        }
                                                                    }
                                                                    ?>
                                                                <?php
                                                                ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__padding">
                                                            <p><strong><?= lang('total_wasted'); ?></strong></p>
                                                        </td>
                                                        <td class="table__padding">
                                                            <p>: <?php echo $totalWasteSB = $this->product_model->getTotalProductWastedQuantityByProductAssignId($productAssignedById->pra_id);
                                                                    if ($totalWasteSB) {
                                                                        if ($unit) {
                                                                            echo  ' ' . $unit->un_name;
                                                                        }
                                                                    }
                                                                    ?>

                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__padding">
                                                            <p><strong><?= lang('production_in_stock'); ?></strong></p>
                                                        </td>
                                                        <td class="table__padding">
                                                            <p>: <?php
                                                                    echo $SbWiseStockProduction = $totalProductionSB - $totalSoldSB - $totalWasteSB;
                                                                    if ($SbWiseStockProduction) {
                                                                        if ($unit) {
                                                                            echo  ' ' . $unit->un_name;
                                                                        }
                                                                    }
                                                                    ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <p><strong><?= lang('batch'); ?> <?= lang('status'); ?></strong></p>
                                                        </td>
                                                        <td>:
                                                            <!-- Complete/Incomplete Status -->
                                                            <?php $batchActiveInactiveStatusInfo = $this->settings_model->getSingleData('live_assigned_shed_summary', ['lshs_sh_id' => $productAssignedById->pra_shed_id, 'lshs_batch_id' => $productAssignedById->pra_batch_id, 'lshs_status' => 1])->lshs_active_status;
                                                            if ($batchActiveInactiveStatusInfo == 0) { ?>
                                                                <button class="button bg-primary-light"><i class="fa-solid fa-person-running"></i> <?= lang('running'); ?></button>
                                                            <?php } else { ?>
                                                                <button class="button bg-success-light"><i class="fa-solid fa-circle-check"></i> <?= lang('completed'); ?></button>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th><?= lang('serialNo'); ?>.</th>
                                    <th><?= lang('production_date'); ?></th>
                                    <th><?= lang('shed'); ?></th>
                                    <th><?= lang('batch'); ?></th>
                                    <th><?= lang('production_quantity'); ?></th>
                                    <th><?= lang('note'); ?></th>
                                    <th><?php echo lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serial = 0;
                                foreach ($stockProductions as $productions) {
                                    $serial++;
                                ?>
                                    <tr>
                                        <td><?= $serial ?></td>
                                        <td><?= date("$settings->date_format", strtotime($productions->prs_date)); ?></td>
                                        <td><?= $this->shed_model->getShedById($productions->prs_shed_id)->sh_no ?>: <?= $this->shed_model->getShedById($productions->prs_shed_id)->sh_title ?> </td>
                                        <td><?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($productions->prs_shed_id, $productions->prs_batch_id)->lshs_batch_id ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($productions->prs_shed_id, $productions->prs_batch_id)->lshs_batch_title ?> </td>
                                        <td> <?= $productions->prs_production_quantity; ?></td>
                                        <td> <?= $productions->prs_description; ?></td>
                                        <td>
                                            <?php
                                            // Complete/Incomplete Status 
                                            $batchActiveInactiveStatusInfo = $this->settings_model->getSingleData('live_assigned_shed_summary', ['lshs_sh_id' => $productions->prs_shed_id, 'lshs_batch_id' => $productions->prs_batch_id, 'lshs_status' => 1])->lshs_active_status; ?>

                                            <button <?php echo ($batchActiveInactiveStatusInfo == 1) ? "disabled" : ""; ?> type="button" class="button button-warning editButton" data-toggle="modal" data-id="<?= $productions->prs_id; ?>" data-product-assign-id="<?= $productions->prs_pra_id ?>" data-product-name="<?= $productById->pr_name ?>" data-category-name="<?= $productCategory; ?>" data-date="<?= date("$settings->date_format", strtotime($productions->prs_date)); ?>" data-shed-id="<?= $productions->prs_shed_id; ?>" data-batch-id="<?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($productions->prs_shed_id, $productions->prs_batch_id)->lshs_batch_id ?>" data-quantity="<?= $productions->prs_production_quantity; ?>" data-description="<?= $productions->prs_description; ?>" data-shed-value="<?= $this->shed_model->getShedById($productions->prs_shed_id)->sh_no ?>: <?= $this->shed_model->getShedById($productions->prs_shed_id)->sh_title ?>" data-batch-value="<?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($productions->prs_shed_id, $productions->prs_batch_id)->lshs_batch_id ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($productions->prs_shed_id, $productions->prs_batch_id)->lshs_batch_title ?>"><i class="fas fa-edit"></i> <?php echo lang('edit'); ?></button>
                                            <form action="<?php echo base_url('product/deleteProductStock'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item') ?>');">
                                                <input type="hidden" name="prs_id" value="<?= $productions->prs_id; ?>">
                                                <input type="hidden" name="prs_pra_id" value="<?= $productions->prs_pra_id; ?>">
                                                <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                <button <?php echo ($batchActiveInactiveStatusInfo == 1) ? "disabled" : ''; ?> type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->

<!-- Edit production -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-edit"></i> <?= lang('edit_production_from_stock'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" id="productionEditForm" action="<?php echo base_url('product/updateProductionToStock'); ?>" method="post" enctype="multipart/form-data">
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
                            <input type="text" class="form-control input__number" name="prs_production_quantity" id="productionQuantity" value='' placeholder="" required>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('production_date'); ?><span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" name="prs_date" id="productionDate" value='' placeholder="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="prs_description" class="form-control" id="description" rows="5" placeholder="Enter description" style="height: auto !important;" required></textarea>
                    </div>
                    <input type="hidden" name="prs_pra_id" value='' id="productAssignId">
                    <input type="hidden" name="prs_id" value='' id="productionId">
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>



<!-- Javascript For Edit product -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Edit Product
        $(".editButton").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            var product_assign_id = $(this).attr('data-product-assign-id');
            var product_name = $(this).attr('data-product-name');
            var category_name = $(this).attr('data-category-name');
            var shed_id = $(this).attr('data-shed-id');
            var batch_id = $(this).attr('data-batch-id');
            var shed_value = $(this).attr('data-shed-value');
            var batch_value = $(this).attr('data-batch-value');
            var quantity = $(this).attr('data-quantity');
            var date = $(this).attr('data-date');
            var description = $(this).attr('data-description');
            $('#myModal').modal('show');
            $("#productionId").val(iid);
            $("#productAssignId").val(product_assign_id);
            $("#productName").val(product_name);
            $("#categoryName").val(category_name);
            $("#shedId").val(shed_id);
            $("#batchId").val(batch_id);
            $("#shedValue").val(shed_value);
            $("#batchValue").val(batch_value);
            $('#productionQuantity').val(quantity);
            $('#productionDate').val(date);
            $('#description').val(description);
        });

    });
</script>
<script>
    $(document).ready(function() {
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
        <?php if ($this->session->flashdata('success')): ?>
        toastr.success('<?= html_escape($this->session->flashdata('success')); ?>');
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
        toastr.error('<?= html_escape($this->session->flashdata('error')); ?>');
        <?php endif; ?>
    });
</script>