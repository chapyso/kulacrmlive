<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="col-md-6">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('production_details'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table ">
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
                                        <h4 class="text-center"><strong><?= lang('production_details'); ?> </strong></h4>
                                        <div class="col-xs-6">
                                            <table>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('product_name'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?= $productById->pr_name ?></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('production_unit'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?php $unit = $this->settings_model->getUnitById($productById->pr_unit_id);
                                                                if ($unit) {
                                                                    echo $unit->un_name;
                                                                }
                                                                ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('product_category'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?php echo $productCategory = $this->product_model->getProductCategoryById($productById->pr_prc_id)->prc_name; ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('approx_selling_price_per_quantity'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?php echo $settings->currency . number_format_currency($productById->pr_selling_price, '2'); ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('note'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?= $productById->pr_description ?></p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-xs-6">
                                            <table>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('total_production'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?php echo $totalProduction = $this->product_model->getTotalProductionQuantityByProductId($productById->pr_id);
                                                                if ($totalProduction) {
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
                                                        <p><strong><?= lang('total_sold'); ?> </strong></p>
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
                                                        <p><strong><?= lang('total_wasted'); ?> </strong></p>
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
                                                        <p><strong><?= lang('production_in_stock'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?php
                                                                echo $productInStock = $totalProduction - $totalSoldProduction - $totalWastedProduction;
                                                                if ($productInStock) {
                                                                    if ($unit) {
                                                                        echo ' ' . $unit->un_name;
                                                                    }
                                                                }

                                                                ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th><?= lang('serialNo'); ?></th>
                                    <th><?= lang('production_date'); ?> </th>
                                    <th><?= lang('shed'); ?> </th>
                                    <th><?= lang('batch'); ?> </th>
                                    <th><?= lang('production_quantity'); ?> </th>
                                    <th><?= lang('note'); ?> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serial = 0;
                                foreach ($stockProductions as $productions) {
                                    $serial++;
                                ?>
                                    <tr class="">
                                        <td><?= $serial ?></td>
                                        <td><?= date("$settings->date_format", strtotime($productions->prs_date)); ?></td>
                                        <td><?= $this->shed_model->getShedById($productions->prs_shed_id)->sh_no ?>: <?= $this->shed_model->getShedById($productions->prs_shed_id)->sh_title ?> </td>
                                        <td><?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($productions->prs_shed_id, $productions->prs_batch_id)->lshs_batch_id ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($productions->prs_shed_id, $productions->prs_batch_id)->lshs_batch_title ?> </td>
                                        <td> <?= $productions->prs_production_quantity; ?></td>
                                        <td> <?= $productions->prs_description; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>



        <!-- Assigned Product -->
        <div class="col-md-6">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('assigned_product_list'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table ">
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th><?= lang('serialNo'); ?></th>
                                    <th><?= lang('product_name'); ?> </th>
                                    <th><?= lang('shed'); ?> </th>
                                    <th><?= lang('batch'); ?> </th>
                                    <th><?= lang('note'); ?> </th>
                                    <th><?= lang('in_stock'); ?> </th>
                                    <th><?= lang('options'); ?> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serials = 0;
                                foreach ($assignedProductsByProduct as $list) {
                                    $serials++;
                                ?>
                                    <tr class="">
                                        <td><?= $serials ?></td>
                                        <td><?= $this->product_model->getProductById($list->pra_pr_id)->pr_name; ?></td>
                                        <td><?= $this->shed_model->getShedById($list->pra_shed_id)->sh_no ?>: <?= $this->shed_model->getShedById($list->pra_shed_id)->sh_title ?> </td>
                                        <td><?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($list->pra_shed_id, $list->pra_batch_id)->lshs_batch_id ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($list->pra_shed_id, $list->pra_batch_id)->lshs_batch_title ?> </td>
                                        <td><?= $list->pra_description; ?></td>
                                        <td>
                                            <?php $totalProduction = $this->product_model->getTotalProductionQuantityByShedAndBatchId($list->pra_shed_id, $list->pra_batch_id);
                                            $totalWaste = $this->product_model->getTotalProductWastedQuantityByProductAssignId($list->pra_id);
                                            $totalSold = $this->sale_model->getProductAssignedWiseProductSaleValue($list->pra_id, 'prsv_quantity');
                                            $inStockProduction = $totalProduction - $totalWaste - $totalSold;
                                            if ($inStockProduction) {
                                                echo $inStockProduction;
                                            }
                                            ?>
                                            <?php
                                            if ($inStockProduction) {
                                                $productInfo = $this->product_model->getProductById($list->pra_pr_id);
                                                $unit = $this->settings_model->getUnitById($productInfo->pr_unit_id);
                                                if ($unit) {
                                                    echo  $unit->un_name;
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td>

                                            <!-- conditional delete -->
                                            <?php
                                            $batchActiveInactiveStatusInfo = $this->settings_model->getSingleData('live_assigned_shed_summary', ['lshs_sh_id' => $list->pra_shed_id, 'lshs_batch_id' => $list->pra_batch_id, 'lshs_status' => 1])->lshs_active_status;
                                            $countDataIfAvailableInSale = $this->settings_model->getCountRow('product_sale_value', 'prsv_id', ['prsv_pra_id' => $list->pra_id, 'prsv_status' => 1]);
                                            if ($countDataIfAvailableInSale == 0) { ?>
                                                <button <?php echo ($batchActiveInactiveStatusInfo == 1) ? "disabled" : ""; ?> type="button" class="button button-warning editButton" data-toggle="modal" data-id="<?= $list->pra_id; ?>" data-description="<?= $list->pra_description; ?>" data-product-id="<?= $list->pra_pr_id; ?>" data-shed-id="<?= $list->pra_shed_id ?>" data-batch-id="<?= $list->pra_batch_id ?>" data-product-name="<?= $this->product_model->getProductById($list->pra_pr_id)->pr_name; ?>" data-category-name="<?= $productCategory; ?>" data-unit="<?= $unit->un_name; ?>"><i class="fas fa-edit"></i> <?= lang('edit'); ?> </button>
                                            <?php } else { ?>
                                                <button type="button" class="button button-warning" disabled><i class="fas fa-edit"></i> <?= lang('edit'); ?> </button>
                                            <?php } ?>
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



<!-- Product assign -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('product_assign'); ?> </strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" action="<?php echo base_url('product/updateProductAssign'); ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('product_name'); ?> </label>
                            <input type="text" class="form-control" name="" id="productName" value='' placeholder="" readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('category'); ?> </label>
                            <input type="text" class="form-control" name="" id="categoryName" value='' placeholder="" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('shed'); ?><span class="text-danger">*</span></label>
                            <select name="pra_shed_id" id="shed_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                <option value=""><?= lang('please_select_shed'); ?> </option>
                                <?php if (!empty($sheds)) foreach ($sheds as $shed) {
                                ?>
                                    <option value="<?php echo $shed->sh_id ?>"><?php echo $shed->sh_no ?>: <?php echo $shed->sh_title ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('batch'); ?><span class="text-danger">*</span></label>
                            <select name="pra_batch_id" id="batch_id_edit" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                <option value=""><?= lang('please_select_batch'); ?> </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="pra_description" class="form-control" id="description" rows="5" placeholder="Enter description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="pra_id" value='' id="productAssignId">
                    <input type="hidden" name="pra_pr_id" value='' id="productId">
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button" id="submitButton" onclick="return confirm('Warning: All data will be updated under this item. Are you sure you want to update this item?');"><?php echo lang('edit_button'); ?></button>
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

        // Add Product to stock
        $(".editButton").click(function(e) {
            e.preventDefault(e);
            var iid = $(this).attr('data-id');
            var product_id = $(this).attr('data-product-id');
            var product_name = $(this).attr('data-product-name');
            var category_name = $(this).attr('data-category-name');
            var shed_id = $(this).attr('data-shed-id');
            var batch_id = $(this).attr('data-batch-id');
            var description = $(this).attr('data-description');
            var unit = $(this).attr('data-unit');
            $('#myModal').modal('show');
            $("#productAssignId").val(iid);
            $("#productId").val(product_id);
            $("#productName").val(product_name);
            $("#categoryName").val(category_name);
            $("#shed_id").attr('batch_id_edit', batch_id);
            $("#shed_id").val(shed_id).trigger('change');
            $("#description").val(description);
            $("#productUnit").val(unit);
        });

        // Cascading - Edit
        $('#shed_id').on('change', function() {
            var shed_id = $(this).val();
            var batch_id = $(this).attr("batch_id_edit");
            if (shed_id != '') {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('food/getActiveBatchByShedId'); ?>',
                    data: {
                        shed_id: shed_id,
                        batch_id: batch_id
                    },
                    success: function(data) {
                        $('#batch_id_edit').html(data);
                    }
                });
            }
        });

        // Already Vaccine Assigned Alert
        $("#batch_id_edit").on('change', function() {
            var batch_id = $(this).val();
            var shed_id = $("#shed_id").val();
            var product_id = $("#productId").val();
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