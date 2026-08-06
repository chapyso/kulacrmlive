<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        
        <!-- 2026 MODERN PAGE HEADER & ACTIONS BAR -->
        <div class="kula-hero-card" style="margin-bottom: 20px; padding: 20px 28px;">
            <div class="hero-content-left">
                <h1 class="hero-title" style="font-size: 22px;">
                    <i class="fa-solid fa-wheat-awn" style="color: #059669; margin-right: 8px;"></i>
                    <?= lang('food_lists'); ?>
                </h1>
                <p class="hero-subtitle">Manage feed categories, stock quantities, and nutritional allocations</p>
            </div>

            <div class="hero-actions-right" style="gap: 8px; flex-wrap: wrap;">
                <a data-toggle="modal" href="#myModal" class="hero-export-btn" style="background: #047857; text-decoration: none;">
                    <i class="fa-solid fa-plus-circle"></i> <?= lang('add_new_food'); ?>
                </a>

                <a href="<?php echo base_url('food/listFoodStock'); ?>" class="hero-date-pill" style="text-decoration: none; padding: 10px 18px;">
                    <i class="fa-solid fa-eye" style="color: #0284c7;"></i>
                    <span><?= lang('view_stock'); ?></span>
                </a>
            </div>
        </div>

        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <div class="panel-body">
                    <input type="hidden" id="productUnitQuantityAlert" value="<?php echo $this->report_model->getCountRow('unit', 'un_id', ['un_status' => 1]); ?>">
                    <div class="adv-table editable-table ">
                        <div class="clearfix">
                                </div>
                            </a>
                            <a data-toggle="modal" href="<?php echo base_url('settings/listUnit') ?>">
                                <div class="btn-group">
                                    <button id="" class="button bg-success" title="<?= lang('product'); ?> <?= lang('unit_setup'); ?>">
                                        <i class="fa fa-balance-scale" aria-hidden="true"></i> <?= lang('product'); ?> <?= lang('unit_setup'); ?>
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
                                    <th><?= lang('food_name'); ?></th>
                                    <th><?= lang('purchase_unit'); ?></th>
                                    <th><?= lang('assigned_batch'); ?></th>
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
                                        <td><?php $unit = $this->settings_model->getUnitById($food->fds_unit_id);
                                            if ($unit) {
                                                echo  $unit->un_name;
                                            }
                                            ?></td>
                                        <td><?= $this->food_model->getFoodAssignedShedCount($food->fds_id) ?></td>
                                        <td>
                                            <button type="button" data-id="<?= $food->fds_id; ?>" class="button button-warning editButton"><i class="fas fa-edit"></i> <?php echo lang('edit'); ?></button></i>
                                            <!-- conditional delete -->
                                            <?php
                                            $countDataIfAvailableInFoodAssign = $this->settings_model->getCountRow('food_value', 'fdv_id', ['fdv_fds_id' => $food->fds_id, 'fdv_status' => 1]);
                                            if ($countDataIfAvailableInFoodAssign  == 0) { ?>
                                                <form action="<?php echo base_url('food/deleteFoodSummary'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                    <input type="hidden" name="fds_id" value="<?= $food->fds_id; ?>">
                                                    <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                    <button type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                                </form>
                                            <?php } else { ?>
                                                <button type="button" class="button button-danger" title="<?= lang('this_item_used_another_places'); ?>" disabled><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                            <?php } ?>
                                            <!-- /.conditional delete -->

                                            <button type="button" data-id="<?= $food->fds_id; ?>" data-name="<?= $food->fds_food_title; ?> (<?php $unit = $this->settings_model->getUnitById($food->fds_unit_id);
                                                                                                                                            if ($unit) {
                                                                                                                                                echo  $unit->un_name;
                                                                                                                                            } ?>)" class="button button-success assignShedAndBatch" data-toggle="modal"><i class="fa fa-plus-circle"></i> <?= lang('assign_batch'); ?> </button>
                                            <a href="<?php echo base_url('') ?>food/viewAssignedFood?fds_id=<?= $food->fds_id; ?>"><button type="button" class="button button-info"><i class="fa fa-eye"></i> <?= lang('food_details'); ?></button></i></a>
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
<!-- Insert Food -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('add_new_food'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('food/insertFood') ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('food_name'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="fds_food_title" id="" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('food_purchase_and_distributed_unit'); ?><span class="text-danger">*</span></label>
                        <select name="fds_unit_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                            <option value=""><?= lang('please_select_unit'); ?></option>
                            <?php foreach ($units->result() as $unit) { ?>
                                <option value="<?= $unit->un_id ?>"><?= $unit->un_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="fds_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="fd_id">
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.Insert Food -->

<!-- Edit Food -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-edit"></i> <?= lang('edit_food'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('food/updateFood') ?>" method="post" id="editFoodForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('food_name'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="fds_food_title" id="" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('food_purchase_and_distributed_unit'); ?><span class="text-danger">*</span></label>
                        <select name="fds_unit_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                            <option value=""><?= lang('please_select_unit'); ?></option>
                            <?php foreach ($units->result() as $unit) { ?>
                                <option value="<?= $unit->un_id ?>"><?= $unit->un_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="fds_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;" required></textarea>
                    </div>
                    <input type="hidden" name="fds_id">
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.Edit Food  -->

<!-- Food Batch Assign -->
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('assign_food_to_batch'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('food/insertAssignFood') ?>" method="post" enctype="multipart/form-data">
                    <table class="table bg-info-light">
                        <tr>
                            <td><?= lang('food_name'); ?>:</td>
                            <td id="fdv_name"></td>
                        </tr>
                    </table>
                    <div class="form-group">
                        <label for="exampleInputEmail1"> <?= lang('everyday_demand'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control input__number" name="fdv_weight" id="" value='' placeholder="" required>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('shed'); ?><span class="text-danger">*</span></label>
                            <select name="fdv_assigned_shed_id" id="shed_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                <option value=""><?= lang('please_select_shed'); ?></option>
                                <?php if (!empty($sheds)) foreach ($sheds as $shed) {
                                ?>
                                    <option value="<?php echo $shed->sh_id ?>"><?php echo $shed->sh_no ?>: <?php echo $shed->sh_title ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('assigned_batch'); ?><span class="text-danger">*</span></label>
                            <select name="fdv_assigned_batch_id" id="batch_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                <option value=""><?= lang('please_select_batch'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="fdv_description" class="form-control" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="fdv_fds_id" id="fd_summary_id">
                    <section>
                        <button type="submit" name="submit" class="button button-info submit_button" id="submitButton"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.Food Batch Assign -->

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
                                <p><?= lang('food_popup_message_one'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('food_popup_message_two'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('food_popup_message_three'); ?></p>
                            </li>
                        </ol>
                    </div>
                    <div class="col-xs-6">
                        <img class="img-square information__modal__image" src="<?php echo base_url('uploads/information/food_list.jpg'); ?>" alt="No img">
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
        // Edit food
        $(".editButton").click(function(e) {
            e.preventDefault(e);
            var iid = $(this).attr('data-id');
            $.ajax({
                url: 'food/editFoodSummaryByJason?fds_id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                // Populate the form fields with the data returned from server
                $('#editFoodForm').find('[name="fds_id"]').val(response.foodSummary.fds_id).end()
                $('#editFoodForm').find('[name="fds_food_title"]').val(response.foodSummary.fds_food_title).end()
                $('#editFoodForm').find('[name="fds_unit_id"]').val(response.foodSummary.fds_unit_id).trigger('change').end()
                $('#editFoodForm').find('[name="fds_description"]').val(response.foodSummary.fds_description).end()
                $('#myModal2').modal('show');
            });

        });

        /* ================ Food Assign to batch =============== */
        // Assign Another Batch
        $(".assignShedAndBatch").click(function(e) {
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            $('#myModal3').modal('show');
            $('#fd_summary_id').val(id);
            $('#fdv_name').text(name);


            // Already Vaccine Assigned Alert
            $("#batch_id").on('change', function() {
                var batch_id = $(this).val();
                var shed_id = $("#shed_id").val();
                var fd_summary_id = $("#fd_summary_id").val();
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
                            if (data != 0) {
                                $('#submitButton').attr('disabled', 'disabled');
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: '<?= lang('you_have_already_assign_this_shed_and_batch'); ?>'
                                });
                            } else {
                                $('#submitButton').removeAttr('disabled');
                            }
                        }
                    });
                }
            });




        });

        // Cascading - Add
        $('#shed_id').on('change', function() {
            var iid = $(this).val();
            if (iid != '') {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('food/getActiveBatchByShedId'); ?>',
                    data: {
                        shed_id: iid
                    },
                    success: function(data) {
                        $('#batch_id').html(data);
                    }
                });
            }
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
                toastr.warning('<?= lang('no_product_unit_found'); ?>');
            }
        }
        productUnitQuantityAlert();
        <?php if ($this->session->flashdata('success')): ?>
        toastr.success('<?= html_escape($this->session->flashdata('success')); ?>');
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
        toastr.error('<?= html_escape($this->session->flashdata('error')); ?>');
        <?php endif; ?>
    });
</script>