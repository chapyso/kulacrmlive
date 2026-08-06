<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('reproduction_list'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table ">
                        <div class="clearfix">
                            <a data-toggle="modal" href="#myModal">
                                <div class="btn-group">
                                    <button id="" class="button button-primary">
                                        <i class="fas fa-plus-circle"></i> <?= lang('add_new_reproduction'); ?>
                                    </button>
                                </div>
                            </a>
                            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>

                            <a data-toggle="modal" href="#informationPopup" class="button button-purple export"><i class="fa-solid fa-circle-question"></i> <?= lang('information'); ?></a>
                        </div>
                        <div class="space15"></div>
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th><?= lang('serialNo'); ?></th>
                                    <th><?= lang('reproduction'); ?> <?= lang('date'); ?></th>
                                    <th><?= lang('livestock'); ?></th>
                                    <th><?= lang('livestock_variant'); ?></th>
                                    <th><?= lang('reproduction'); ?> <?= lang('shed'); ?></th>
                                    <th><?= lang('reproduction'); ?> <?= lang('batch'); ?></th>
                                    <th><?= lang('reproduction'); ?> <?= lang('quantity'); ?> (<?= $settings->unit; ?>)</th>
                                    <th><?= lang('options'); ?></th>
                                </tr>
                            </thead>
                            <?php $serial = 0;
                            foreach ($reproductions as $list) {
                                $serial++;
                            ?>
                                <tr class="">
                                    <td><?= $serial; ?></td>
                                    <td><?= date("$settings->date_format", strtotime($list->lrp_birth_date)); ?></td>
                                    <td><?= $this->livestock_model->getLivestockById($list->lrp_ls_id)->ls_name; ?></td>
                                    <td><?= $this->livestock_model->getLivestockTypeById($list->lrp_lst_id)->lst_title; ?></td>
                                    <td><?= $this->shed_model->getShedById($list->lrp_sh_id)->sh_no; ?>: <?= $this->shed_model->getShedById($list->lrp_sh_id)->sh_title; ?></td>
                                    <td><?= $list->lrp_batch_id; ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($list->lrp_sh_id, $list->lrp_batch_id)->lshs_batch_title; ?></td>
                                    <td><?= $list->lrp_birth_quantity; ?></td>
                                    <td>
                                        <!-- Find Data In different module -->
                                        <?php
                                        $livestockAssignedInfo = $this->report_model->getSingleData('live_assigned_shed_summary', ['lshs_reproduction_id' => $list->lrp_id, 'lshs_type' => 2, 'lshs_status' => 1]);

                                        $countDataIfAvailableInFood = $this->settings_model->getCountRow('food_value', 'fdv_id', ['fdv_assigned_shed_id' => $livestockAssignedInfo->lshs_sh_id, 'fdv_assigned_batch_id' => $livestockAssignedInfo->lshs_batch_id, 'fdv_status' => 1]);
                                        $countDataIfAvailableInLivestockSale = $this->settings_model->getCountRow('livestock_sale_value', 'lssv_id', ['lssv_shed_id' => $livestockAssignedInfo->lshs_sh_id, 'lssv_batch_id' => $livestockAssignedInfo->lshs_batch_id, 'lssv_status' => 1]);
                                        $countDataIfAvailableInProduct = $this->settings_model->getCountRow('product_assign', 'pra_id', ['pra_shed_id' => $livestockAssignedInfo->lshs_sh_id, 'pra_batch_id' => $livestockAssignedInfo->lshs_batch_id, 'pra_status' => 1]);
                                        $countDataIfAvailableInDeath = $this->settings_model->getCountRow('livestock_death_quantity', 'ld_id', ['ld_sh_id' => $livestockAssignedInfo->lshs_sh_id, 'ld_batch_id' => $livestockAssignedInfo->lshs_batch_id, 'ld_status' => 1]);
                                        ?>
                                        <!-- Find Data In different module -->

                                        <button <?php echo ($countDataIfAvailableInFood  == 0 && $countDataIfAvailableInLivestockSale == 0 && $countDataIfAvailableInProduct == 0 && $countDataIfAvailableInDeath == 0) ? true : "disabled"; ?> title="<?= lang('this_item_used_another_places'); ?>" type="button" class="button button-warning editButton" data-toggle="modal" data-id="<?php echo $list->lrp_id; ?>" data-ls-id="<?php echo $list->lrp_ls_id; ?>" data-lst-id="<?php echo $list->lrp_lst_id; ?>" data-batch-id="<?php echo $list->lrp_batch_id; ?>" data-shed-id="<?php echo $list->lrp_sh_id; ?>" data-description="<?php echo $list->lrp_description; ?>" data-birth-date="<?php echo date("$settings->date_format", strtotime($list->lrp_birth_date)); ?>" data-approx_selling_price="<?php echo $list->lrp_approx_selling_price; ?>" data-description="<?php echo $list->lrp_description; ?>"><i class="fas fa-edit"></i> <?php echo lang('edit'); ?></button>
                                        <!-- conditional delete -->
                                        <?php if ($countDataIfAvailableInFood  == 0 && $countDataIfAvailableInLivestockSale == 0 && $countDataIfAvailableInProduct == 0 && $countDataIfAvailableInDeath == 0) { ?>
                                            <form action="<?php echo base_url('product/deleteLivestockReproduction'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                <input type="hidden" name="lrp_id" value="<?php echo $list->lrp_id; ?>">
                                                <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                <button type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                            </form>
                                        <?php } else { ?>
                                            <button type="button" class="button button-danger" title="<?= lang('this_item_used_another_places'); ?>" disabled><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                        <?php } ?>
                                        <!-- /.conditional delete -->
                                        <a href="<?php echo base_url(''); ?>product/viewLivestockReproduction?lrp_id=<?php echo $list->lrp_id; ?>"><button type="button" class="button button-info"><i class="fas fa-eye"></i> <?php echo lang('view'); ?></button></i></a>
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('add_reproduction'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" action="<?php echo base_url('product/insertLivestockReproduction'); ?>" method="post" enctype="multipart/form-data">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border"><?= lang('birth_from'); ?>:</legend>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="exampleInputEmail1"><?= lang('shed'); ?> <span class="text-danger">*</span></label>
                                <select name="lrp_sh_id" id="lss_shed_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_shed'); ?></option>
                                    <?php if (!empty($sheds)) foreach ($sheds as $shed) {
                                    ?>
                                        <option value="<?php echo $shed->sh_id ?>"><?php echo $shed->sh_no ?>: <?php echo $shed->sh_title ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="exampleInputEmail1"><?= lang('batch'); ?><span class="text-danger">*</span></label>
                                <select name="lrp_batch_id" id="lss_batch_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_batch'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="exampleInputEmail1"><?php echo lang('ls_name_variant_modal'); ?><span class="text-danger">*</span></label>
                                <select name="lrp_ls_id" id="lss_ls_id" class="form-control js-example-basic-single " placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_livestock'); ?></option>
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="exampleInputEmail1"><?php echo lang('ls_type_title'); ?><span class="text-danger">*</span></label>
                                <select name="lrp_lst_id" class="form-control js-example-basic-single" id="livestock_type" placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_variant'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="exampleInputEmail1"><?= lang('birth_quantity'); ?><span class="text-danger">*</span></label>
                                <input type="text" class="form-control input__number" name="lrp_birth_quantity" id="" value='' placeholder="" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="exampleInputEmail1"><?= lang('birth_date'); ?><span class="text-danger">*</span></label>
                                <input type="text" class="form-control datepicker" name="lrp_birth_date" id="" value='<?= get_current_date(); ?>' placeholder="" required>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend><?= lang('assign_to'); ?>:</legend>
                        <!-- New Batch -->
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="shed_id1"><?php echo lang('sheds'); ?><span class="text-danger">*</span></label>
                                <select name="lrp_assign_sh_id" id="shed_id1" class="form-control js-example-basic-single " placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_shed'); ?></option>
                                    <?php if (!empty($sheds)) foreach ($sheds as $shed) { ?>
                                        <option value="<?php echo $shed->sh_id; ?>"> <?php echo $shed->sh_no; ?>. <?php echo $shed->sh_title; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for=""><?= lang('new_batch_title'); ?><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="lrp_batch_title" id="uniqueBatchTitle" value='' placeholder="" required readonly>
                                <input type="hidden" class="form-control" name="lsh_batch_number" id="lsh_batch_number" value='' required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="exampleInputEmail1"><?= lang('approx_selling_price_per_quantity'); ?><span class="text-danger">*</span></label>
                                <input type="text" class="form-control input__number" name="lrp_approx_selling_price" id="" value='' placeholder="" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="exampleInputEmail1"><?= lang('assign_date'); ?><span class="text-danger">*</span></label>
                                <input type="text" class="form-control datepicker" name="lrp_assign_date" id="" value='<?= get_current_date(); ?>' placeholder="" required>
                            </div>
                        </div>
                        <div class="bg-primary-light vaccination__date__warning"><strong class="text-danger"><?= lang('note'); ?>: </strong><?= lang('all_vaccination_dates_will_be_added_under_this_date'); ?></div>
                        <!-- /.New Batch -->
                    </fieldset>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="lrp_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="id" value=''>
                    <input type="hidden" name="sft_id" value=''>
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Edit Reproduction -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('update_reproduction'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" id="reproductionEditForm" action="<?php echo base_url('product/insertLivestockReproduction'); ?>" method="post" enctype="multipart/form-data">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border"><?= lang('birth_from'); ?>:</legend>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="exampleInputEmail1"><?= lang('shed'); ?></label>
                                <select name="lrp_sh_id" id="shed_id_edit" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_shed'); ?></option>
                                    <?php if (!empty($sheds)) foreach ($sheds as $shed) {
                                    ?>
                                        <option value="<?php echo $shed->sh_id ?>"><?php echo $shed->sh_no ?>: <?php echo $shed->sh_title ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="exampleInputEmail1"><?= lang('assigned_batch'); ?></label>
                                <select name="lrp_batch_id" id="batch_id_edit" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_batch'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="exampleInputEmail1"><?= lang('livestock_shed_wise_pcs'); ?><span class="text-danger">*</span></label>
                                <select name="lrp_ls_id" id="lrp_ls_id_edit" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_livestock'); ?></option>
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="exampleInputEmail1"><?= lang('livestock_variant'); ?><span class="text-danger">*</span></label>
                                <select name="lrp_lst_id" class="form-control js-example-basic-single" id="livestock_type_edit" placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_variant'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="exampleInputEmail1"><?= lang('birth_quantity'); ?><span class="text-danger">*</span></label>
                                <input type="text" class="form-control input__number" name="lrp_birth_quantity" id="" value='' placeholder="" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="exampleInputEmail1"><?= lang('birth_date'); ?><span class="text-danger">*</span></label>
                                <input type="text" class="form-control datepicker" name="lrp_birth_date" id="birthDate" value='' placeholder="" required>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend><?= lang('assign_to'); ?>:</legend>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="shed_id"><?= lang('shed'); ?><span class="text-danger">*</span></label>
                                <select name="lrp_assign_sh_id" id="assign_to_shed_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_shed'); ?></option>
                                    <?php if (!empty($sheds)) foreach ($sheds as $values) { ?>
                                        <option value="<?php echo $values->sh_id; ?>"> <?php echo $values->sh_no; ?>. <?php echo $values->sh_title; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="shed_id"><?= lang('batch'); ?><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="lrp_batch_title" id="uniqueBatchTitleEdit" value='' placeholder="" required readonly>
                                <input type="hidden" class="form-control" name="lsh_batch_number" id="lsh_batch_number_edit" value='' required>
                            </div>
                        </div>
                    </fieldset>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('approx_selling_price_per_quantity'); ?><span class="text-danger">*</span></label>
                            <input type="text" class="form-control input__number" name="lrp_approx_selling_price" id="approxSellingPrice" value='' placeholder="" required>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('assign_date'); ?><span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" name="lrp_assign_date" id="date" value='' placeholder="" required>
                        </div>
                    </div>
                    <div class="bg-primary-light vaccination__date__warning"><strong class="text-danger"><?= lang('note'); ?>: </strong><?= lang('all_vaccination_dates_will_be_added_under_this_date'); ?></div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="lrp_description" class="form-control" id="description" rows="5" placeholder="Enter Description" style="height: auto !important;" required></textarea>
                    </div>
                    <input type="hidden" name="lrp_id" value='' id='lrp_id'>
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
                                <p><?= lang('reproduction_popup_message_one'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('reproduction_popup_message_two'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('reproduction_popup_message_three'); ?></p>
                            </li>
                        </ol>
                    </div>
                    <div class="col-xs-6">
                        <img class="img-square information__modal__image" src="<?php echo base_url('uploads/information/Reproduction_add.jpg'); ?>" alt="No img">
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

<!-- Javascript For Edit staff -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Cascading - Add
        $('#lss_shed_id').on('change', function() {
            var iid = $(this).val();
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('food/getActiveBatchByShedId'); ?>',
                data: {
                    shed_id: iid
                },
                success: function(data) {
                    $('#lss_batch_id').html(data);
                }
            });
        });

        $('#lss_batch_id').on('change', function() {
            var batch_id = $(this).val();
            var shed_id = $("#lss_shed_id option:selected").val();
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('purchase/getLivestockByShedAndBatchCascadingDropdown'); ?>',
                data: {
                    shed_id: shed_id,
                    batch_id: batch_id
                },
                dataType: 'json',
                success: function(data) {
                    $('#lss_ls_id').html(data.livestock);

                    $('#livestock_type').html(data.livestock_type);
                }
            });
        });

        // Create New Batch Number Under Shed Wise
        $("#shed_id1").on('change', function() {
            var iid = $(this).val();
            var words = $('#shed_id1 option:selected').text().split(' ');
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('purchase/getLastBatchNumberShedWiseByAjax'); ?>',
                data: {
                    shed_id: iid,
                },
                success: function(data) {
                    $("#lsh_batch_number").val(data);
                    $("#newBatchNumberUnderShed").html(data);

                    // Auto title create for batch
                    var text = '';
                    $.each(words, function() {
                        text += this.substring(0, 1);
                        newText = text.substring(1);
                    });
                    var uniqueTextForBatchTitle = newText + "-" + data;
                    $("#uniqueBatchTitle").val(uniqueTextForBatchTitle)

                }
            });
        });



        // On change shed deselect live and type
        $('#lss_shed_id').on('change', function() {
            // get the selected index
            var index = $('#lss_ls_id')[0].selectedIndex;
            $("#lss_ls_id option:eq(" + index + ")").remove();
            $("#lss_ls_id").append(`<option value=""><?= lang('empty'); ?></option>`);

            var index2 = $('#livestock_type')[0].selectedIndex;
            $("#livestock_type option:eq(" + index2 + ")").remove();
            $("#livestock_type").append(`<option value=""><?= lang('empty'); ?></option>`);
        });


        /* ==================== Edit ==================== */
        $(".editButton").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            var ls_id = $(this).attr('data-ls-id');
            var lst_id = $(this).attr('data-lst-id');
            var batch_id = $(this).attr('data-batch-id');
            var shed_id = $(this).attr('data-shed-id');
            var approx_selling_price = $(this).attr('data-approx_selling_price');
            var birthDate = $(this).attr('data-birth-date');
            var description = $(this).attr('data-description');
            $("#lrp_id").val(iid);
            $("#lst_id").val(lst_id).trigger('change');
            $("#shed_id_edit").attr('lrp_batchId', batch_id);
            $("#shed_id_edit").val(shed_id).trigger('change');
            $("#approxSellingPrice").val(approx_selling_price);
            $("#birthDate").val(birthDate);
            $("#description").val(description);
            $('#myModal2').modal('show');
            $.ajax({
                url: 'product/editLivestockReproductionAssignToShedByAjax?lrp_id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                // Populate the form fields with the data returned from server 
                $('#reproductionEditForm').find('[name="lrp_batch_title"]').val(response.valueFromAssignedShedSummary.lshs_batch_title).end()
                $('#reproductionEditForm').find('[name="lsh_batch_number"]').val(response.valueFromAssignedShedSummary.lshs_batch_id).end()
                $('#reproductionEditForm').find('[name="lrp_assign_sh_id"]').val(response.valueFromAssignedShedSummary.lshs_sh_id).trigger('change').end()
                $('#reproductionEditForm').find('[name="lrp_birth_quantity"]').val(response.valueFromAssignedShedSummary.lshs_assign_total_quantity).end()
                $('#reproductionEditForm').find('[name="lrp_assign_date"]').val(response.valueFromAssignedShedSummary.lshs_assign_date).end()





                // Create New Batch Number Under Shed Wise
                $("#assign_to_shed_id").on('change', function() {
                    var iid = $(this).val();
                    var words = $('#assign_to_shed_id option:selected').text().split(' ');
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('purchase/getLastBatchNumberShedWiseByAjax'); ?>',
                        data: {
                            shed_id: iid,
                        },
                        success: function(data) {
                            $("#lsh_batch_number_edit").val(data);
                            // Auto title create for batch
                            var text = '';
                            $.each(words, function() {
                                text += this.substring(0, 1);
                                newText = text.substring(1);
                            });
                            var uniqueTextForBatchTitle = newText + "-" + data;
                            $("#uniqueBatchTitleEdit").val(uniqueTextForBatchTitle);
                        }
                    });
                });


            });

            // get live and type
            var shed_id = shed_id
            var batch_id = batch_id
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('purchase/getLivestockByShedAndBatchCascadingDropdown'); ?>',
                data: {
                    shed_id: shed_id,
                    batch_id: batch_id
                },
                dataType: 'json',
                success: function(data) {
                    $('#lrp_ls_id_edit').html(data.livestock);
                    $('#livestock_type_edit').html(data.livestock_type);
                }
            });

            // Select Batch
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

            $('#shed_id_edit').on('change', function() {
                var iid = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('food/getActiveBatchByShedId'); ?>',
                    data: {
                        shed_id: iid
                    },
                    success: function(data) {
                        $('#batch_id_edit').html(data);
                    }
                });
            });

            // Birth to edit
            $('#batch_id_edit').on('change', function() {
                var shed_id = $("#shed_id_edit option:selected").val();
                var batch_id = $("#shed_id_edit").attr('lrp_batchId');
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('purchase/getLivestockByShedAndBatchCascadingDropdown'); ?>',
                    data: {
                        shed_id: shed_id,
                        batch_id: batch_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#lrp_ls_id_edit').html(data.livestock);
                        $('#livestock_type_edit').html(data.livestock_type);
                    }
                });
            });

            // On change shed deselect live and type
            $('#shed_id_edit').on('change', function() {
                // get the selected index
                var index = $('#lrp_ls_id_edit')[0].selectedIndex;
                $(`#lrp_ls_id_edit option:eq(${index})`).remove();
                $('#lrp_ls_id_edit').append(`<option value=""><?= lang('empty'); ?></option>`);

                var index2 = $('#livestock_type_edit')[0].selectedIndex;
                $(`#livestock_type_edit option:eq(${index2})`).remove();
                $('#livestock_type_edit').append(`<option value=""><?= lang('empty'); ?></option>`);
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