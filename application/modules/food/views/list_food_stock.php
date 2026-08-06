<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('food_stock_and_supplier_to_shed'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table ">
                        <div class="clearfix">
                            <a data-toggle="modal" href="<?php echo base_url('food/addFoodPurchase'); ?>">
                                <div class="btn-group">
                                    <button class="button button-primary">
                                        <i class="fas fa-plus-circle"></i> <?= lang('add_new_purchase'); ?>
                                    </button>
                                </div>
                            </a>
                            <a data-toggle="modal" href="<?php echo base_url('report/viewFoodStockReport'); ?>">
                                <div class="btn-group">
                                    <button class="button button-info">
                                        <i class="fas fa-eye"></i> <?= lang('stock_report'); ?>
                                    </button>
                                </div>
                            </a>
                            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                            <a data-toggle="modal" href="#informationPopup" class="button button-purple export"><i class="fa-solid fa-circle-question"></i> <?= lang('information'); ?></a>
                        </div>
                        <div class="space15">
                        </div>
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th><?= lang('serialNo'); ?></th>
                                    <th><?= lang('food_name'); ?> </th>
                                    <th><?= lang('assigned_food_and_batch'); ?> </th>
                                    <th><?= lang('total_purchased'); ?> </th>
                                    <th><?= lang('distribute'); ?> </th>
                                    <th><?= lang('total_wasted'); ?> </th>
                                    <th><?= lang('in_stock'); ?> </th>
                                    <th><?= lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $serial = 0;
                                foreach ($foods as $food) {
                                    $serial++;
                                ?>
                                    <tr class="">
                                        <td> <?= $serial ?></td>
                                        <td> <?= $food->fds_food_title ?></td>
                                        <td>
                                            <a href="<?php echo base_url('') ?>food/viewAssignedFood?fds_id=<?= $food->fds_id; ?>" title="View Assigned Batch">
                                                <div class="cell__div">
                                                    <?= $this->food_model->getFoodAssignedShedCount($food->fds_id); ?>
                                                </div>
                                            </a>
                                        </td>
                                        <td><?php $purchaseFood = $this->food_model->getFoodPurchaseWeightByFoodId($food->fds_id, 'fdpv_quantity');
                                            if ($purchaseFood) {
                                                $unit = $this->settings_model->getUnitById($food->fds_unit_id);
                                                if ($unit) {
                                                    echo $purchaseFood . ' ' . $unit->un_name;
                                                }
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                        <td><?php $feed = $this->food_model->getFoodDistributedWeightByFoodId($food->fds_id, 'fddv_distributed_quantity');
                                            if ($feed) {
                                                echo $feed . ' ' . $unit->un_name;
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                        <td><?php $wastedFood = $this->food_model->getFoodWastedByFoodId($food->fds_id, 'fdw_quantity');
                                            if ($wastedFood) {
                                                echo $wastedFood . ' ' . $unit->un_name;
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                        <td><?php
                                            $stillInStock = $purchaseFood - $feed - $wastedFood;
                                            if ($stillInStock) {
                                                echo $stillInStock . ' ' . $unit->un_name;
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                        <td>
                                            <?php if ($stillInStock > 0) { ?>
                                                <button title="Distribute Food" type="button" data-id="<?= $food->fds_id; ?>" data-title="<?= $food->fds_food_title; ?>" data-stock-quantity="<?= $stillInStock; ?>" data-food-unit="<?= $unit->un_name; ?>" class="button button-primary distributeFoodToBatch"><i class="fas fa-plus-circle"></i> <?= lang('distribute_food'); ?> </button>
                                            <?php } else { ?>
                                                <button title="Distribute Food" type="button" class="button bg-danger-light" disabled><i class="fa fa-frown-o" aria-hidden="true"></i> <?= lang('no_food_in_stock'); ?> </button>
                                            <?php } ?>
                                            <?php if ($feed > 0) { ?>
                                                <a href="<?php echo base_url('') ?>food/addFoodWiseDistributedReport?fds_id=<?= $food->fds_id; ?>"><button title="Distributed Food Report" type="button" class="button button-info"><i class="fas fa-eye"></i> <?= lang('distributed_reports'); ?> </button></a>
                                            <?php } ?>
                                            <!-- <br> -->

                                            <?php if ($stillInStock > 0) { ?>
                                                <button title="Reject wasted food from stock" type="button" data-id="<?= $food->fds_id; ?>" data-title="<?= $food->fds_food_title; ?>" data-stock-quantity="<?= $stillInStock; ?>" data-food-unit="<?= $unit->un_name; ?>" class="button button-secondary addWastedFood"><i class="fa fa-plus-circle"></i> <?= lang('wasted_food'); ?> </button>
                                                <?php if ($wastedFood > 0) { ?>
                                                    <a href="<?php echo base_url('') ?>food/addWastedFoodReport?fds_id=<?= $food->fds_id; ?>"><button type="button" class="button button-success"><i class="fas fa-eye"></i> <?= lang('wasted_reports'); ?> </button></a>
                                                <?php } ?>
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
<!--footer start-->

<input type="hidden" id="foodQuantityAlert" value="<?php echo $this->report_model->getCountRow('food_summary', 'fds_id', ['fds_status' => 1]); ?>">


<!-- Distribute Food -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width: 1000px; margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('food_distributed'); ?> </strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('food/insertFoodDistributedQuantity') ?>" method="post" enctype="multipart/form-data">
                    <table class="table">
                        <tr class="bg-info-light">
                            <td><?= lang('food_name'); ?> </td>
                            <td><?= lang('stock_quantity'); ?> </td>
                        </tr>
                        <tr class="bg-info-light">
                            <td id="foodTitle"></td>
                            <td id="foodStockQuantity"></td>
                        </tr>
                    </table>
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th> <?= lang('shed'); ?> </th>
                                <th> <?= lang('batch'); ?> </th>
                                <th> <?= lang('everyday_need'); ?> </th>
                                <th> <?= lang('distribute'); ?><span class="text-danger">*</span></th>
                                <th> <?= lang('note'); ?> </th>
                                <th> <?= lang('dont_want_to_feed_now'); ?> </th>
                            </tr>
                        </thead>
                        <tbody class="viewP" id="disableSubmitButton">
                        </tbody>
                    </table>
                    <div id="invalidInfo" class="text-center"></div>
                    <div class="row">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1"> <?= lang('distributed_date'); ?><span class="text-danger">*</span></label>
                                <input type="text" class="form-control datepicker" name="fdd_date" id="exampleInputEmail1" value='<?= get_current_date(); ?>' placeholder="" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="fdd_fd_id" id="food_id">
                    <section class="text-right">
                        <button type="submit" name="submit" class="button button-info submit_button" id="submitButton"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.Distribute Food -->

<!-- Assign to next step type -->
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('add_wasted_food'); ?> </strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" id="" action="<?php echo base_url('') ?>food/insertWastedFood" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <table class="table table-bordered">
                            <thead>
                                <th style="width: 50%;">
                                    <label for="exampleInputEmail1"><?= lang('food_name'); ?> :</label>
                                    <span id="fdTitleW"> </span>
                                </th>
                                <th style="width: 50%;">
                                    <label for="exampleInputEmail1"><?= lang('stock_quantity'); ?> :</label>
                                    <span id="fdQuantityW"> </span>
                                </th>
                            </thead>
                        </table>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('wasted_quantity'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control input__number food_wasted_quantity" name="fdw_quantity" id="" value='' placeholder="Enter Wasted Quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('description'); ?> </label>
                        <textarea name="fdw_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="fdw_fd_id" value='' id="foodIdW">
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.Assign to next step type -->

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
                                <p><?= lang('food_distribute_popup_message_one'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('food_distribute_popup_message_two'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('food_distribute_popup_message_three'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('food_distribute_popup_message_four'); ?></p>
                            </li>
                        </ol>
                    </div>
                    <div class="col-xs-6">
                        <img class="img-square information__modal__image" src="<?php echo base_url('uploads/information/food_distribute.jpg'); ?>" alt="No img">
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


<!-- Javascript For Edit Trip -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".distributeFoodToBatch").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            var food_title = $(this).attr('data-title');
            var food_stock_quantity = $(this).attr('data-stock-quantity');
            var food_unit = $(this).attr('data-food-unit');
            $('.viewP').html("");
            $('#food_id').val(iid);
            $('#foodTitle').text(food_title);
            $('#foodStockQuantity').text(food_stock_quantity + ' ' + food_unit);

            $('#submitButton').removeAttr('disabled');
            $('#invalidInfo').html('');
            $.ajax({
                url: 'food/getFoodAssignedBatchByFoodSummaryIdByJason?fdv_fds_id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                var assigned_values = response.foodValues;
                var shedNo = response.shedNo;
                var shedTitle = response.shedTitle;
                var batchTitle = response.batchTitle;
                var array = '';
                $.each(assigned_values, function(key, value) {
                    array += `
                            <tr>
                                <td>${shedNo[key] +': '+ shedTitle[key]} <input type="hidden" name="fdd_shed_id[]" value='${value.fdv_assigned_shed_id}'></td>
                                <td>${value.fdv_assigned_batch_id +': '+ batchTitle[key]} <input type="hidden" name="fdd_batch_id[]" value='${value.fdv_assigned_batch_id}'></td>
                                <td>${value.fdv_weight} <input type="hidden" name="fdd_need_quantity[]" value='${value.fdv_weight}'></td>
                                <td><input type="text" name="fdd_distributed_quantity[]" class="form-control distributed_quantity_sum input__number" required></td>
                                <td><input type="text" name="fdd_description[]" class="form-control"></td>
                                <td><button type="button" class="button button-danger remove"><i class="fas fa-trash"></i> <?= lang('remove'); ?></button></td>
                            </tr>
                            `;
                    // remove Row
                    $('table').on('click', 'tr .remove', function(e) {
                        e.preventDefault();
                        $(this).parents('tr').remove();
                        buttonEnabler();
                    });

                    // Button Disable if there is no row
                    function buttonEnabler() {
                        var rowCount = $('#disableSubmitButton tr').length;
                        if (rowCount < 1) {
                            $('#submitButton').attr('disabled', 'disabled');
                            $('#invalidInfo').html('<p class="text-danger"><?= lang('invalid_information'); ?> </p>');
                        } else {
                            $('#submitButton').removeAttr('disabled');
                            $('#invalidInfo').html('');
                        }
                    }


                });
                $(".viewP").append(array);
                $('#myModal2').modal('show');

                // Food distributed alert
                $(".distributed_quantity_sum").keyup(function() {
                    var totalStock = $("#foodStockQuantity").text();
                    var sum = 0;
                    $(".distributed_quantity_sum").each(function() {
                        sum += Number($(this).val());
                    });
                    if (parseFloat(sum) > parseFloat(totalStock)) {
                        Swal.fire({
                            icon: "warning",
                            title: "Warning",
                            text: "<?= lang('you_dont_have_enough_food_in_stock'); ?> <?= lang('please_enter_a_valid_value'); ?>"
                        });
                        $(".distributed_quantity_sum").val('');
                    }
                });

                // Remove White Space 
                $(".input__number").on('keyup change paste keypress', function(e) {
                    var data, i;
                    data = document.querySelectorAll(".input__number"); //HTML DOM querySelector() Method
                    for (i = 0; i < data.length; i++) {
                        data[i].value = data[i].value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                    }
                });
                $(".input__number").on('drop', function(e) {
                    $(this).prop("readonly", true)
                });
                $(".input__number").on('click, keyup', function(e) {
                    $(this).prop("readonly", false)
                });


            });
        });


        // Add Wasted Food Modal
        $(".addWastedFood").click(function(e) {
            var iid = $(this).attr('data-id');
            var title = $(this).attr('data-title');
            var stock_quantity = $(this).attr('data-stock-quantity');
            var food_unit = $(this).attr('data-food-unit');
            $('#myModal3').modal('show');
            $('#foodIdW').val(iid);
            $('#fdTitleW').text(title);
            $('#fdQuantityW').text(stock_quantity + ' ' + food_unit);

            // Food distributed alert
            $(".food_wasted_quantity").keyup(function() {
                var wastedQuantity = $(this).val();
                if (parseFloat(wastedQuantity) > parseFloat(stock_quantity)) {
                    Swal.fire({
                        icon: "warning",
                        title: "Warning",
                        text: "<?= lang('you_dont_have_enough_food_in_stock'); ?> <?= lang('please_enter_a_valid_value'); ?>"
                    });
                    $(".food_wasted_quantity").val('');
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

        // Supplier quantity alert
        function foodQuantityAlert() {
            var foodQuantityAlert = $("#foodQuantityAlert").val();

            if (foodQuantityAlert == 0) {
                toastr.warning('<?= lang('no_food_found'); ?>');
            }
        }
        foodQuantityAlert();
        <?php if ($this->session->flashdata('success')): ?>
        toastr.success('<?= html_escape($this->session->flashdata('success')); ?>');
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
        toastr.error('<?= html_escape($this->session->flashdata('error')); ?>');
        <?php endif; ?>
    });
</script>