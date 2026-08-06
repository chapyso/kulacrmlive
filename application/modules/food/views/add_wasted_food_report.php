<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="col-md-12">
            <!-- page start-->
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('food_supplied_reports'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table ">
                        <div class="clearfix">
                            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="new__cards__auto card__box">
                                    <div class="col-xs-12">
                                        <p class="text-center"><strong><?= lang('food_details'); ?></strong></p>
                                        <table class="table">
                                            <tr>
                                                <td><?= lang('food_name'); ?></td>
                                                <td><?= lang('total_food_quantity'); ?>
                                                    (<?php $unit = $this->settings_model->getUnitById($foodById->fds_unit_id);
                                                        if ($unit) {
                                                            echo  $unit->un_name;
                                                        } ?>)
                                                </td>
                                                <td><?= lang('distributed_quantity'); ?> (<?php if ($unit) {
                                                                                                echo  $unit->un_name;
                                                                                            } ?>)</td>
                                                <td><?= lang('wasted_quantity'); ?> (<?php if ($unit) {
                                                                                            echo  $unit->un_name;
                                                                                        } ?>)</td>
                                                <td><?= lang('in_stock'); ?> (<?php if ($unit) {
                                                                                    echo  $unit->un_name;
                                                                                } ?>)</td>
                                            </tr>
                                            <tr>
                                                <td><?= $foodById->fds_food_title ?></td>
                                                <td><?= $this->food_model->getFoodPurchaseWeightByFoodId($foodById->fds_id, 'fdpv_quantity'); ?></td>
                                                <td><?= $this->food_model->getFoodDistributedWeightByFoodId($foodById->fds_id, 'fddv_distributed_quantity'); ?></td>
                                                <td><?= $this->food_model->getFoodWastedByFoodId($foodById->fds_id, 'fdw_quantity'); ?></td>
                                                <td><?php
                                                    $stocked = $this->food_model->getFoodPurchaseWeightByFoodId($foodById->fds_id, 'fdpv_quantity');
                                                    $feed = $this->food_model->getFoodDistributedWeightByFoodId($foodById->fds_id, 'fddv_distributed_quantity');
                                                    $wastedFood = $this->food_model->getFoodWastedByFoodId($foodById->fds_id, 'fdw_quantity');
                                                    $stillInStock = $stocked - $feed - $wastedFood;
                                                    if ($stillInStock) {
                                                        echo $stillInStock;
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th><?= lang('serialNo'); ?></th>
                                    <th><?= lang('date'); ?></th>
                                    <th><?= lang('wasted_quantity'); ?></th>
                                    <th><?= lang('description'); ?></th>
                                    <th><?= lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serial = 0;
                                foreach ($wastedFoodValues as $value) {
                                    $serial++;
                                ?>
                                    <tr>
                                        <td><?= $serial; ?></td>
                                        <td><?= date("$settings->date_format", strtotime($value->fdw_created_at)); ?></td>
                                        <td><?= $value->fdw_quantity; ?></td>
                                        <td><?= $value->fdw_description; ?></td>
                                        <td>
                                            <button type="button" data-food-id="<?= $foodById->fds_id; ?>" data-id="<?= $value->fdw_id; ?>" data-quantity="<?= $value->fdw_quantity; ?>" data-description="<?= $value->fdw_description; ?>" data-food-title="<?= $foodById->fds_food_title; ?>" data-stock="<?= $stillInStock; ?>" data-food-unit="<?php if ($unit) {
                                                                                                                                                                                                                                                                                                                                                        echo $unit->un_name;
                                                                                                                                                                                                                                                                                                                                                    } ?>" class="button button-warning editButton"><i class="fas fa-edit"></i> <?= lang('edit'); ?></button>
                                            <form action="<?php echo base_url('food/deleteWastedFood'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                <input type="hidden" name="fdw_id" value="<?= $value->fdw_id; ?>">
                                                <input type="hidden" name="fdw_fd_id" value="<?= $foodById->fds_id; ?>">
                                                <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                <button type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?= lang('delete'); ?></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </section>
            <!-- page end-->
        </div>
    </section>
</section>
<!--main content end-->
<!--footer start-->


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fas fa-edit"></i> <?= lang('edit_wasted_food'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" id="" action="<?php echo base_url('') ?>food/updateWastedFood" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <table class="table table-bordered">
                            <thead>
                                <th style="width: 50%;">
                                    <label for="exampleInputEmail1"><?= lang('food_name'); ?>:</label>
                                    <span id="foodTitle"> </span>
                                </th>
                                <th style="width: 50%;">
                                    <label for="exampleInputEmail1"><?= lang('stock_quantity'); ?>:</label>
                                    <span id="foodStock"> </span> <span id="foodUnit"></span>
                                </th>
                            </thead>
                        </table>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('wasted_quantity'); ?></label>
                        <input type="text" class="form-control input__number" name="fdw_quantity" id="foodQuantity" value='' placeholder="Enter Quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('description'); ?></label>
                        <textarea name="fdw_description" class="form-control" id="foodDescription" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="fdw_id" value='' id="wastedFoodId">
                    <input type="hidden" name="fdw_fd_id" value='' id="foodId">
                    <section class="text-right">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".editButton").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            var food__title = $(this).attr('data-food-title');
            var wasted_quantity = $(this).attr('data-quantity');
            var wasted_description = $(this).attr('data-description');
            var food__stock = $(this).attr('data-stock');
            var food__id = $(this).attr('data-food-id');
            var food__unit = $(this).attr('data-food-unit');
            $('#myModal').modal('show');
            $("#wastedFoodId").val(iid);
            $("#foodTitle").text(food__title);
            $("#foodQuantity").val(wasted_quantity);
            $("#foodDescription").val(wasted_description);
            $("#foodStock").text(food__stock);
            $("#foodId").val(food__id);
            $("#foodUnit").text(food__unit);

            // Food distributed alert
            $("#foodQuantity").keyup(function() {
                var wastedQuantityEdit = $(this).val();
                if (parseFloat(wastedQuantityEdit) > parseFloat(food__stock)) {
                    Swal.fire({
                        icon: "warning",
                        title: "Warning",
                        text: "<?= lang('you_dont_have_enough_food_in_stock'); ?> <?= lang('please_enter_a_valid_value'); ?>"
                    });
                    $("#foodQuantity").val(wasted_quantity);
                }
            });

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