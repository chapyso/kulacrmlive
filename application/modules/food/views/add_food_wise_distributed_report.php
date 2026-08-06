<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('food_distributed_reports'); ?>
                </header>

                <div class="panel-body">
                    <div class="adv-table editable-table ">
                        <div class="clearfix">
                            <a href="<?php echo base_url('food/listFoodStock'); ?>">
                                <div class="btn-group">
                                    <button class="button button-primary">
                                        <i class="fa-solid fa-circle-arrow-left"></i>
                                    </button>
                                </div>
                            </a>
                            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="new__cards__auto card__box">
                                    <div class="col-xs-12">
                                        <h4 class="text-center"><strong><?= lang('food_details'); ?> </strong></h4>
                                        <table class="table">
                                            <tr>
                                                <td><?= lang('food_name '); ?> </td>
                                                <td><?= lang('total_food_quantity'); ?> (<?php $unit = $this->settings_model->getUnitById($foodById->fds_unit_id);
                                                                                            if ($unit) {
                                                                                                echo  $unit->un_name;
                                                                                            } ?>)</td>
                                                <td><?= lang('distributed_quantity'); ?> (<?php
                                                                                            if ($unit) {
                                                                                                echo  $unit->un_name;
                                                                                            } ?>)</td>
                                                <td><?= lang('wasted_quantity'); ?> (<?php
                                                                                        if ($unit) {
                                                                                            echo  $unit->un_name;
                                                                                        } ?>)</td>
                                                <td><?= lang('in_stock'); ?> (<?php
                                                                                if ($unit) {
                                                                                    echo  $unit->un_name;
                                                                                } ?>)</td>
                                            </tr>
                                            <tr>
                                                <td><?= $foodById->fds_food_title ?></td>
                                                <td><?= $this->food_model->getFoodPurchaseWeightByFoodId($foodById->fds_id, 'fdpv_quantity'); ?></td>
                                                <td><?= $this->food_model->getFoodDistributedWeightByFoodId($foodById->fds_id, 'fddv_distributed_quantity'); ?></td>
                                                <td><?= $this->food_model->getFoodWastedByFoodId($foodById->fds_id, 'fdw_quantity'); ?></td>
                                                <td>
                                                    <?php
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
                                    <th><?= lang('options'); ?> </th>
                                    <th><?= lang('date'); ?> </th>
                                    <th><?= lang('shed'); ?> </th>
                                    <th><?= lang('batch'); ?> </th>
                                    <th><?= lang('to_be_delivered'); ?> (<?php
                                                                            if ($unit) {
                                                                                echo  $unit->un_name;
                                                                            } ?>)</th>
                                    <th><?= lang('distributed_quantity'); ?> (<?php
                                                                                if ($unit) {
                                                                                    echo  $unit->un_name;
                                                                                } ?>)</th>
                                    <th><?= lang('description'); ?> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $serial = 0;
                                foreach ($foodDistributedSummary as $summary) {
                                    $serial++;
                                ?>
                                    <!-- Summary -->
                                    <tr class="table__rows">
                                        <td rowspan="<?php echo $this->food_model->getCountFoodDistributedValueByFoodSummaryId($summary->fdds_id) + 1; ?>" class="table__cells"><?= $serial ?></td>
                                        <td rowspan="<?php echo $this->food_model->getCountFoodDistributedValueByFoodSummaryId($summary->fdds_id) + 1; ?>" class="table__cells">
                                            <button type="button" data-id="<?= $summary->fdds_id; ?>" data-food-id="<?= $foodById->fds_id; ?>" data-food-title="<?= $foodById->fds_food_title; ?>" data-stock="<?= $stillInStock ?>" data-date="<?= date("$settings->date_format", strtotime($summary->fdds_date)); ?>" data-food-unit="<?= $unit->un_name; ?>" class="button button-warning editButton"><i class="fas fa-edit"></i> <?= lang('edit'); ?> </button>
                                            <form action="<?php echo base_url('food/deleteFoodDistributedQuantity'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                <input type="hidden" name="fdds_id" value="<?= $summary->fdds_id; ?>">
                                                <input type="hidden" name="fds_id" value="<?= $foodById->fds_id; ?>">
                                                <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                <button type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?= lang('delete'); ?></button>
                                            </form>
                                        </td>
                                        <td rowspan="<?php echo $this->food_model->getCountFoodDistributedValueByFoodSummaryId($summary->fdds_id) + 1; ?>" class="table__cells"><?= date("$settings->date_format", strtotime($summary->fdds_date)); ?></td>
                                        <?php
                                        $foodDistributedValues = $this->food_model->getFoodDistributedValuesByFoodDistributedSummaryId($summary->fdds_id);
                                        foreach ($foodDistributedValues as $values) {
                                        ?>
                                            <!-- Value -->
                                    <tr>
                                        <td><?= $this->shed_model->getShedById($values->fddv_shed_id)->sh_no ?>: <?= $this->shed_model->getShedById($values->fddv_shed_id)->sh_title ?> </td>
                                        <td><?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($values->fddv_shed_id, $values->fddv_batch_id)->lshs_batch_id ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($values->fddv_shed_id, $values->fddv_batch_id)->lshs_batch_title ?> </td>
                                        <td><?= $values->fddv_need_quantity ?></td>
                                        <td><?= $values->fddv_distributed_quantity ?></td>
                                        <td><?= $values->fddv_description ?></td>
                                    </tr>
                                <?php } ?>
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





<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width: 1000px; margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-edit"></i> <?= lang('edit_distributed_food'); ?> </strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('food/updateFoodDistributedQuantity') ?>" method="post" id="editFoodForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="report__card bg-info-light">
                                <p class="text-center"><strong><?= lang('food_details'); ?></strong></p>
                                <div class="col-xs-4">
                                    <p><?= lang('food_name'); ?>:</p>
                                    <p id="foodTitle"></p>
                                </div>
                                <div class="col-xs-4">
                                    <p><?= lang('stock_quantity'); ?> (<span id="foodUnit"></span>):</p>
                                    <p id="stillInStock"></p>
                                </div>
                                <div class="col-xs-4">
                                    <p><?= lang('distributed_date'); ?>:</p>
                                    <p id="foodDate"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="table table-striped table-hover table-bordered" id="editable-sample">
                        <thead>
                            <tr>
                                <th> <?= lang('shed'); ?> </th>
                                <th> <?= lang('batch'); ?> </th>
                                <th> <?= lang('everyday_need'); ?></th>
                                <th> <?= lang('distribute'); ?> </th>
                                <th> <?= lang('note'); ?> </th>
                                <th> <?= lang('dont_want_to_feed_now'); ?></th>
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
                                <label for="exampleInputEmail1"> <?= lang('distributed_date'); ?></label>
                                <input type="text" class="form-control datePicker" name="fdds_date" id="foodDateVal" value='' placeholder="" autocomplete="off" required>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="fdds_id" id="fdd_summary_id">
                    <input type="hidden" name="fds_id" id="food__id">
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button" id="submitButton"><?php echo lang('edit_button'); ?></button>
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
            var food__id = $(this).attr('data-food-id');
            var food__stock = $(this).attr('data-stock');
            var food__date = $(this).attr('data-date');
            var food__unit = $(this).attr('data-food-unit');
            $("#foodTitle").text(food__title);
            $("#fdd_summary_id").val(iid);
            $("#food__id").val(food__id);
            $("#stillInStock").text(food__stock);
            $("#foodDate").text(food__date);
            $("#foodDateVal").val(food__date);
            $("#foodUnit").text(food__unit);
            $('.viewP').html("");
            $('#submitButton').removeAttr('disabled');
            $('#invalidInfo').html('');
            $.ajax({
                url: 'food/getFoodDistributedQuantityValuesByJason?fddv_fdds_id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                var all_values = response.editDistributedFoodValues;
                var shedNo = response.shedNo;
                var shedTitle = response.shedTitle;
                var batchTitle = response.batchTitle;

                $.each(all_values, function(keys, value) {

                    var $tr = $('<tr>').append(
                        $('<td>').html(`<input type="hidden" name="fddv_shed_id[]" class="form-control" value=" ${value.fddv_shed_id}">${shedNo[keys] +': '+ shedTitle[keys]}`),
                        $('<td>').html(`<input type="hidden" name="fddv_batch_id[]" class="form-control" value=" ${value.fddv_batch_id}">${value.fddv_batch_id +': '+ batchTitle[keys]}`),
                        $('<td>').html(`<input type="hidden" name="fddv_need_quantity[]" class="form-control" value=" ${value.fddv_need_quantity} ">${value.fddv_need_quantity}`),
                        $('<td>').html(`<input type="text" name="fddv_distributed_quantity[]" class="form-control distributed_quantity_sum input__number" value="${value.fddv_distributed_quantity}" required><input type="hidden" name="fddv_id[]" class="form-control" value="${value.fddv_id}"><input type="hidden" class="form-control total_distributed_quantity" value="${value.fddv_distributed_quantity}">`),
                        $('<td>').html(`<input type="text" name="fddv_description[]" class="form-control" value=" ${value.fddv_description} ">`),
                        $('<td>').html(`<button type="button" class="button button-danger btn-sm remove"><i class="fas fa-trash"></i> <?= lang('remove'); ?></button>`)
                    );
                    $(".viewP").append($tr);

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
                            $('#invalidInfo').html('<p class="text-danger"><?= lang('invalid_information'); ?></p>');
                        } else {
                            $('#submitButton').removeAttr('disabled');
                            $('#invalidInfo').html('');
                        }
                    }



                    // Food distributed alert
                    $(".distributed_quantity_sum").keyup(function() {

                        var totalStock = $("#stillInStock").text();

                        var distributedSum = 0;
                        $(".total_distributed_quantity").each(function() {
                            distributedSum += Number($(this).val());

                        });

                        var finalTotalStock = parseFloat(distributedSum) + parseFloat(totalStock);
                        console.log(finalTotalStock);

                        var sum = 0;
                        $(".distributed_quantity_sum").each(function() {
                            sum += Number($(this).val());
                        });
                        if (parseFloat(sum) > parseFloat(finalTotalStock)) {
                            Swal.fire({
                                icon: "warning",
                                title: "Warning",
                                text: "<?= lang('you_dont_have_enough_food_in_stock'); ?> <?= lang('please_enter_a_valid_value'); ?>"
                            });
                            $('#submitButton').attr('disabled', 'disabled');
                        } else {
                            $('#submitButton').removeAttr('disabled');
                        }

                    });


                });
                $('#myModal').modal('show');



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


        $('.datePicker').datepicker({
            format: "<?php if ($settings->date_format == 'd-m-Y') {
                            echo 'dd-mm-yy';
                        } else {
                            echo 'mm-dd-yy';
                        } ?>"
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