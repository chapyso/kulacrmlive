<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('view_assigned_batches'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table ">
                        <div class="clearfix">
                            <a href="<?php echo base_url('food/listFood'); ?>">
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
                                    <div class="new__cards__auto card__box alert-info">
                                        <h4 class="text-center"><strong><?= lang('food_details'); ?> </strong></h4>
                                        <div class="col-xs-6">
                                            <table>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('food_name'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?= $foodById->fds_food_title ?></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('note'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?= $foodById->fds_description ?></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('purchase_unit'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?php $unit = $this->settings_model->getUnitById($foodById->fds_unit_id);
                                                                if ($unit) {
                                                                    echo  $unit->un_name;
                                                                }
                                                                ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-xs-6">
                                            <table>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('food_purchased'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?= $this->food_model->getFoodPurchaseWeightByFoodId($foodById->fds_id, 'fdpv_quantity'); ?></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('food_distributed'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?= $this->food_model->getFoodDistributedWeightByFoodId($foodById->fds_id, 'fddv_distributed_quantity'); ?></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('total_wasted'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?= $this->food_model->getFoodWastedByFoodId($foodById->fds_id, 'fdw_quantity'); ?></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('in_stock'); ?></strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?php
                                                                $stocked = $this->food_model->getFoodPurchaseWeightByFoodId($foodById->fds_id, 'fdpv_quantity');
                                                                $feed = $this->food_model->getFoodDistributedWeightByFoodId($foodById->fds_id, 'fddv_distributed_quantity');
                                                                $wastedFood = $this->food_model->getFoodWastedByFoodId($foodById->fds_id, 'fdw_quantity');
                                                                $stillInStock = $stocked - $feed - $wastedFood;
                                                                if ($stillInStock) {
                                                                    echo $stillInStock;
                                                                }
                                                                ?>
                                                        </p>
                                                    </td>
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
                                    <th><?= lang('shed'); ?> </th>
                                    <th><?= lang('batch'); ?> </th>
                                    <th><?= lang('everyday_demand'); ?> </th>
                                    <th><?= lang('description'); ?> </th>
                                    <th><?= lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serial = 0;
                                foreach ($shedsByFoodId as $fSheds) {
                                    $serial++;
                                ?>
                                    <tr class="">
                                        <td><?= $serial; ?></td>
                                        <td><?= $this->shed_model->getShedById($fSheds->fdv_assigned_shed_id)->sh_no ?>: <?= $this->shed_model->getShedById($fSheds->fdv_assigned_shed_id)->sh_title ?> </td>
                                        <td><?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($fSheds->fdv_assigned_shed_id, $fSheds->fdv_assigned_batch_id)->lshs_batch_id ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($fSheds->fdv_assigned_shed_id, $fSheds->fdv_assigned_batch_id)->lshs_batch_title ?> </td>
                                        <td><?= $fSheds->fdv_weight ?></td>
                                        <td><?= $fSheds->fdv_description; ?></td>
                                        <td>
                                            <button type="button" data-batch-id="<?= $fSheds->fdv_assigned_batch_id; ?>" data-id="<?= $fSheds->fdv_id; ?>" class="button button-warning editButton"><i class="fas fa-edit"></i> <?php echo lang('edit'); ?></button></i>
                                            <!-- conditional delete -->
                                            <?php
                                            $countDataIfAvailable = $this->settings_model->getCountRow('food_distributed_value', 'fddv_id', ['fddv_shed_id' => $fSheds->fdv_assigned_shed_id, 'fddv_batch_id' => $fSheds->fdv_assigned_batch_id, 'fddv_status' => 1]);
                                            if ($countDataIfAvailable  == 0) { ?>
                                                <form action="<?php echo base_url('food/deleteAssignedFoodValue'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?php echo lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                    <input type="hidden" name="fdv_id" value="<?= $fSheds->fdv_id; ?>">
                                                    <input type="hidden" name="fdv_fds_id" value="<?= $fSheds->fdv_fds_id; ?>">
                                                    <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                    <button type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                                </form>
                                            <?php } else { ?>
                                                <button type="button" class="button button-danger" title="<?= lang('this_item_used_another_places'); ?>" disabled><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                            <?php } ?>
                                            <!-- /.conditional delete -->

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







<!-- Edit Assigned Food -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-edit"></i> <?php echo lang('edit_food_history'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('food/updateAssignedFoodValue') ?>" method="post" id="editFoodForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('everyday_demand'); ?><span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="fdv_weight" id="" value='' placeholder="" required>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('shed'); ?><span class="text-danger">*</span></label>
                            <select name="fdv_assigned_shed_id" id="shed_id_edit" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                <option value="">Select shed</option>
                                <?php if (!empty($sheds)) foreach ($sheds as $shed) {
                                ?>
                                    <option value="<?php echo $shed->sh_id ?>"><?php echo $shed->sh_no ?>: <?php echo $shed->sh_title ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('assigned_batch'); ?><span class="text-danger">*</span></label>
                            <select name="fdv_assigned_batch_id" id="batch_id_edit" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                <option value=""><?= lang('please_select_batch'); ?> </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="fdv_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;" required></textarea>
                    </div>
                    <input type="hidden" name="fdv_id" id="food_value_id">
                    <input type="hidden" name="fdv_fds_id" id="fdv_fds_id">
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button" id="submitButton"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Add Payment To That Car -->

<!-- Javascript For Edit Trip -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Edit Assigned Batch
        $(".editButton").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            var batch_id = $(this).attr('data-batch-id');
            $('#editFoodForm').find('[name="fdv_assigned_shed_id"]').attr("batch_id_edit", batch_id).end()
            $.ajax({
                url: 'food/editFoodValueByJason?fdv_id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                // Populate the form fields with the data returned from server
                $('#editFoodForm').find('[name="fdv_id"]').val(response.foodValue.fdv_id).end()
                $('#editFoodForm').find('[name="fdv_fds_id"]').val(response.foodValue.fdv_fds_id).end()
                $('#editFoodForm').find('[name="fdv_food_title"]').val(response.foodValue.fdv_food_title).end()
                $('#editFoodForm').find('[name="fdv_weight"]').val(response.foodValue.fdv_weight).end()
                $('#editFoodForm').find('[name="fdv_assigned_shed_id"]').val(response.foodValue.fdv_assigned_shed_id).trigger('change').end()
                $('#editFoodForm').find('[name="fdv_description"]').val(response.foodValue.fdv_description).end()
                $('#myModal2').modal('show');
            });
        });

        // Cascading - Edit
        $('#shed_id_edit').on('change', function() {
            var shed_id = $(this).val();
            var batch_id = $('#editFoodForm').find('[name="fdv_assigned_shed_id"]').attr("batch_id_edit");
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
        });


        // Already Vaccine Assigned Alert
        $("#batch_id_edit").on('change', function() {
            var batch_id = $(this).val();
            var shed_id = $("#shed_id_edit").val();
            var fd_summary_id = $("#fdv_fds_id").val();
            var fd_value_id = $("#food_value_id").val();
            if (batch_id != '') {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('food/checkAssignedFoodBatchByAjax'); ?>',
                    data: {
                        batch_id: batch_id,
                        shed_id: shed_id,
                        fd_summary_id: fd_summary_id,
                    },
                    success: function(data) {
                        if (data != 0 && data != fd_value_id) {
                            $('#submitButton').attr('disabled', 'disabled');
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: '<?= lang('you_have_already_assign_this_shed_and_batch'); ?> '
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