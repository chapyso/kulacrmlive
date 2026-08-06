<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        
        <!-- 2026 MODERN PAGE HEADER & ACTIONS BAR -->
        <div class="kula-hero-card" style="margin-bottom: 20px; padding: 20px 28px;">
            <div class="hero-content-left">
                <h1 class="hero-title" style="font-size: 22px;">
                    <i class="fa-solid fa-syringe" style="color: #059669; margin-right: 8px;"></i>
                    <?= lang('vaccine_lists'); ?>
                </h1>
                <p class="hero-subtitle">Track animal vaccination schedules, inventory routes, and health treatments</p>
            </div>

            <div class="hero-actions-right" style="gap: 8px; flex-wrap: wrap;">
                <a data-toggle="modal" href="#myModal" class="hero-export-btn" style="background: #047857; text-decoration: none;">
                    <i class="fa-solid fa-plus-circle"></i> <?php echo lang('add_new_vaccine'); ?>
                </a>

                <a href="<?php echo base_url('vaccine/listVaccinatedShed'); ?>" class="hero-date-pill" style="text-decoration: none; padding: 10px 18px;">
                    <i class="fa-solid fa-house-chimney-medical" style="color: #0284c7;"></i>
                    <span><?= lang('vaccination_schedule'); ?></span>
                </a>
            </div>
        </div>
                            <a data-toggle="modal" href="<?php echo base_url('vaccine/listVaccinatedShed') ?>">
                                <div class="btn-group">
                                    <button id="" class="button button-secondary">
                                        <i class="fa-solid fa-house-chimney-medical"></i> <?= lang('vaccination_schedule'); ?>
                                    </button>
                                </div>
                            </a>
                            <a data-toggle="modal" href="<?php echo base_url('vaccine/listVaccineRoute') ?>">
                                <div class="btn-group">
                                    <button id="" class="button button-primary">
                                        <i class="fa-solid fa-spray-can"></i> <?= lang('vaccine'); ?> <?= lang('routing'); ?>
                                    </button>
                                </div>
                            </a>
                            <a data-toggle="modal" href="<?php echo base_url('settings/listUnit') ?>">
                                <div class="btn-group">
                                    <button id="" class="button bg-success" title="<?= lang('product'); ?> <?= lang('unit_setup'); ?>">
                                        <i class="fa fa-balance-scale" aria-hidden="true"></i> <?= lang('product'); ?> <?= lang('unit_setup'); ?>
                                    </button>
                                </div>
                            </a>
                            <a data-toggle="modal" href="<?php echo base_url('vaccine/listUsedVaccine') ?>">
                                <div class="btn-group">
                                    <button id="" class="button button-info" title="<?= lang('view_used_vaccine_list_with_details'); ?>">
                                        <i class="fas fa-eye"></i> <?= lang('view_used_vaccine'); ?>
                                    </button>
                                </div>
                            </a>
                            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>

                            <a data-toggle="modal" href="#informationPopup" class="button button-purple export"><i class="fa-solid fa-circle-question"></i> <?= lang('information'); ?></a>
                        </div>
                        <div class="space15">
                            <!-- Flash Message -->
                            <?php
                            $message = $this->session->flashdata('error');
                            if (!empty($message)) {
                            ?>
                                <div class="error_flash_message"><i class="fa fa-check"></i> <?php echo $message; ?></div>
                            <?php } ?>
                            <?php echo validation_errors(); ?>

                        </div>
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th><?= lang('serialNo'); ?></th>
                                    <th><?= lang('vaccine_id'); ?></th>
                                    <th><?= lang('vaccine_name'); ?></th>
                                    <th><?= lang('vaccine'); ?> <?= lang('unit'); ?></th>
                                    <th><?= lang('total_purchased'); ?></th>
                                    <th><?= lang('total_used'); ?></th>
                                    <th><?= lang('wasted'); ?></th>
                                    <th><?= lang('in_stock'); ?></th>
                                    <th><?= lang('assigned_batch_quantity'); ?></th>
                                    <th><?= lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serial = 0;
                                foreach ($vaccines as $vaccine) {
                                    $serial++;
                                ?>
                                    <tr class="">
                                        <td><?= $serial; ?></td>
                                        <td><?php
                                            $vaccinePurchaseUnit = $this->settings_model->getUnitById($this->vaccine_model->getVaccineById($vaccine->vcc_id)->vcc_unit_id);
                                            echo $vaccine->vcc_vaccine_id; ?>
                                        </td>
                                        <td><?= $vaccine->vcc_name; ?></td>
                                        <td><?php if ($vaccinePurchaseUnit) {
                                                echo $vaccinePurchaseUnit->un_name;
                                            } ?></td>
                                        <td><?php echo $purchaseVaccineQuantity = $this->vaccine_model->getVaccineWiseVaccinePurchaseQuantity($vaccine->vcc_id, 'vpv_quantity');
                                            echo " ";
                                            if ($purchaseVaccineQuantity) {
                                                if ($vaccinePurchaseUnit) {
                                                    echo $vaccinePurchaseUnit->un_name;
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $usedVaccineQuantity = $this->vaccine_model->getVaccineWiseVaccineUsedQuantity($vaccine->vcc_id, 'vds_vaccine_used_quantity');
                                            echo " ";
                                            if ($usedVaccineQuantity) {
                                                if ($vaccinePurchaseUnit) {
                                                    echo $vaccinePurchaseUnit->un_name;
                                                }
                                            }
                                            ?></td>
                                        <td><?php echo $wastedVaccine = $this->vaccine_model->getVaccineWastedByVaccineId($vaccine->vcc_id, 'vccw_quantity');
                                            echo " ";
                                            if ($wastedVaccine) {
                                                if ($vaccinePurchaseUnit) {
                                                    echo $vaccinePurchaseUnit->un_name;
                                                }
                                            }
                                            ?></td>
                                        <td><?php echo $inStockVaccine =  $purchaseVaccineQuantity - ($usedVaccineQuantity + $wastedVaccine);
                                            echo " ";
                                            if ($inStockVaccine) {
                                                if ($vaccinePurchaseUnit) {
                                                    echo $vaccinePurchaseUnit->un_name;
                                                }
                                            }
                                            ?></td>
                                        <td><?= $this->vaccine_model->getAssignedVaccineQuantityByVaccineId($vaccine->vcc_id); ?></td>
                                        <!-- Next vaccination date -->
                                        <td>
                                            <button type="button" class="button button-warning editButton" title="Update only vaccine name, unit and note." data-toggle="modal" data-id="<?= $vaccine->vcc_id; ?>" data-unit-id="<?= $vaccine->vcc_unit_id; ?>" data-name="<?= $vaccine->vcc_name; ?>" data-description="<?= $vaccine->vcc_description; ?>"><i class="fa fa-edit"></i> <?= lang('edit'); ?></button>
                                            <!-- conditional delete -->
                                            <?php
                                            $countDataIfAvailableInAssignVaccine = $this->settings_model->getCountRow('vaccination', 'vccn_id', ['vccn_vcc_id' => $vaccine->vcc_id, 'vccn_status' => 1]);
                                            $countDataIfAvailableInPurchaseVaccine = $this->settings_model->getCountRow('vaccine_purchase_value', 'vpv_id', ['vpv_vcc_id' => $vaccine->vcc_id, 'vpv_status' => 1]);
                                            if ($countDataIfAvailableInAssignVaccine  == 0) { ?>
                                                <form action="<?php echo base_url('vaccine/deleteVaccine'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                    <input type="hidden" name="vcc_id" value="<?= $vaccine->vcc_id; ?>">
                                                    <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                    <button type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                                </form>
                                            <?php } else { ?>
                                                <button type="button" class="button button-danger" title="<?= lang('this_item_used_another_places'); ?>" disabled><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                            <?php } ?>
                                            <!-- /.conditional delete -->

                                            <button type="button" data-id="<?= $vaccine->vcc_id; ?>" data-name="<?= $vaccine->vcc_name; ?>" class="button button-success vaccineDoseAssign"><i class="fa fa-plus-circle"></i> <?= lang('vaccine_assign'); ?></button>
                                            <a class="" href="<?php echo base_url('') ?>vaccine/viewVaccinationList?vcc_id=<?= $vaccine->vcc_id; ?>"><button type="button" class="button button-info"><i class="fa fa-eye"></i> <?= lang('vaccine_details'); ?></button></i></a>
                                            <?php if ($purchaseVaccineQuantity > 0) { ?>
                                                <button title="Reject wasted Vaccine from stock" type="button" data-id="<?= $vaccine->vcc_id; ?>" data-title="<?= $vaccine->vcc_name; ?>" data-stock-quantity="<?= $inStockVaccine; ?>" data-unit="<?php echo $vaccinePurchaseUnit->un_name; ?>" class="button button-secondary addWastedVaccine"><i class="fa fa-plus-circle"></i> <?= lang('wasted_vaccine'); ?></button>
                                                <a href="<?php echo base_url('') ?>vaccine/addWastedVaccineReport?vcc_id=<?= $vaccine->vcc_id; ?>"><button type="button" class="button button-info"><i class="fas fa-eye"></i> <?= lang('view_vaccine_reports'); ?></button></a>
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
<!-- ==================== Add Vaccine ==================== -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?php echo lang('add_new_vaccine'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('vaccine/insertVaccine'); ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo lang('vaccine_name'); ?><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="vcc_name" id="" value='' placeholder="Enter Title" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?= lang('vaccine_purchase_and_distribute_unit'); ?><span class="text-danger">*</span></label>
                                <select name="vcc_unit_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_unit'); ?></option>
                                    <?php foreach ($units->result() as $unit) { ?>
                                        <option value="<?= $unit->un_id ?>"><?= $unit->un_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <td>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?= lang('note'); ?></label>
                                    <textarea type="text" name="vcc_description" class="form-control" id="" value="" rows="5" cols="10" style="height: auto !important;" placeholder="Enter Note"></textarea>
                                </div>
                            </td>
                        </div>
                    </div>

                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- ==================== /.Add Vaccine  ==================== -->

<!-- ==================== Edit Vaccine ==================== -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('edit_vaccine'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('vaccine/insertVaccine'); ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo lang('vaccine_name'); ?><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="vcc_name" id="vccName" value='' placeholder="Enter Title" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?= lang('vaccine_purchase_and_distribute_unit'); ?><span class="text-danger">*</span></label>
                                <select name="vcc_unit_id" id="vaccineUnitId" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_unit'); ?></option>
                                    <?php foreach ($units->result() as $unit) { ?>
                                        <option value="<?= $unit->un_id ?>"><?= $unit->un_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <td>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?= lang('note'); ?></label>
                                    <textarea type="text" name="vcc_description" class="form-control" id="vccDescription" value="" rows="5" cols="10" style="height: auto !important;" placeholder="Enter Note"></textarea>
                                </div>
                            </td>
                        </div>
                    </div>
                    <input type="hidden" name="vcc_id" id="vccId">
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- ==================== /.Edit Vaccine ==================== -->

<!-- ==================== Add Doses ==================== -->
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width: 900px; margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('vaccine_dose_assign'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('vaccine/insertVaccination'); ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group col-sm-4">
                                <label for="exampleInputEmail1"><?php echo lang('vaccine_name'); ?></label>
                                <input type="text" class="form-control" name="" id="vaccine_Name" value='' readonly>
                            </div>

                            <div class="form-group col-sm-4">
                                <label for="exampleInputEmail1"><?php echo lang('ls_name_variant_modal'); ?></label><span class="text-danger">*</span>
                                <select name="vccn_ls_id" id="pur_ls_id" class="form-control js-example-basic-single " placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_livestock'); ?></option>
                                    <?php if ($livestocks) {
                                        foreach ($livestocks as $livestock) { ?>
                                            <option value="<?php echo $livestock->ls_id; ?>"> <?php echo $livestock->ls_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="exampleInputEmail1"><?= lang('livestock_variant'); ?></label><span class="text-danger">*</span>
                                <select name="vccn_lst_id" class="form-control js-example-basic-single" id="livestock_type" placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_variant'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h4 class="text-center"><?= lang('vaccine_doses'); ?></h4>

                    <div class="row">
                        <div class="col-md-12">
                            <!-- Multiple Doses Note -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="bg-warning-light text-center">
                                            <strong class="text-danger"> <?= lang('note'); ?></strong>: <?= lang('for_multiple_doses_note'); ?>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                            <table class="table" id="customFields">
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?= lang('vaccination_days'); ?></label><span class="text-danger">*</span> <i class="fa fa-info-circle" aria-hidden="true" title="Write down how many days after assign the livestock to shed you want to vaccinate."></i>
                                            <input type="number" name="vdq_vaccination_date[]" class="form-control code input__number" data-check_serial="1" value="" placeholder="Enter Day" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo lang('vaccine_route'); ?><span class="text-danger">*</span></label>
                                            <select name="vdq_vaccine_route[]" class="form-control js-example-basic-single code" placeholder="" style="width: 100%;" required>
                                                <option value=""><?= lang('please_select_route'); ?></option>
                                                <?php if (!empty($vaccineRoutes))
                                                    foreach ($vaccineRoutes->result() as $route) { ?>
                                                    <option value="<?php echo $route->vccr_id; ?>"><?php echo $route->vccr_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?= lang('description'); ?></label>
                                            <input type="text" name="vdq_description[]" class="form-control code" id="" value="" placeholder="Description">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <div class="text-right">
                                <a href="javascript:void(0);" class="addCF button button-primary" title="Click here to add more vaccine dose."><i class="fas fa-plus-circle"></i> Add more dose</a>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="vaccine_id" id="vaccine_id">
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button" id="submitButton"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- ==================== /.Add Doses ==================== -->

<!-- ==================== Add Wasted Vaccine ==================== -->
<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('add_wasted_vaccine'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" id="" action="<?php echo base_url('') ?>vaccine/insertWastedVaccine" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <table class="table table-bordered">
                            <thead>
                                <th style="width: 50%;">
                                    <label for="exampleInputEmail1"><?= lang('vaccine_name'); ?>:</label>
                                    <span id="vaccineTitleW"> </span>
                                </th>
                                <th style="width: 50%;">
                                    <label for="exampleInputEmail1"><?= lang('stock_quantity'); ?>:</label>
                                    <span id="vaccineQuantityW"> </span>
                                </th>
                            </thead>
                        </table>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('wasted_quantity'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control input__number distributed_quantity_sum" name="vccw_quantity" id="wastedVaccineQuantity" value='' placeholder="Enter Wasted Quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('description'); ?></label>
                        <textarea name="vccw_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="vccw_vcc_id" value='' id="vaccineIdW">
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- ==================== Add Wasted Vaccine ==================== -->

<!-- ==================== Information Popup ==================== -->
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
                                <p><?= lang('vaccine_popup_message_one'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('vaccine_popup_message_two'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('vaccine_popup_message_three'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('vaccine_popup_message_four'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('vaccine_popup_message_five'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('vaccine_popup_message_six'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('vaccine_popup_message_seven'); ?></p>
                            </li>
                        </ol>
                    </div>
                    <div class="col-xs-6">
                        <img class="img-square information__modal__image" src="<?php echo base_url('uploads/information/vaccine_list.jpg'); ?>" alt="No img">
                    </div>
                </div>
                <section class="text-right">
                    <button type="button" class="button button-purple" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> <?= lang('close'); ?></button>
                </section>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- ==================== Information Popup ==================== -->

<!-- Javascript For Edit Trip -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Vaccine Edit
        $(".editButton").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var unit_id = $(this).attr('data-unit-id');
            var description = $(this).attr('data-description');
            $('#myModal2').modal('show');
            $("#vccId").val(iid);
            $("#vccName").val(name);
            $("#vaccineUnitId").val(unit_id).trigger('change');
            $("#vccDescription").val(description);
        });

        // Vaccine Dose Assign
        $(".vaccineDoseAssign").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            var vaccineName = $(this).attr('data-name');
            $('#myModal3').modal('show');
            $("#vaccine_id").val(iid);
            $("#vaccine_Name").val(vaccineName);

            $("select#pur_ls_id")[0].selectedIndex = 0;
            $('#pur_ls_id').trigger('change');

            // Already Vaccine Assigned Alert
            $("#livestock_type").on('change', function() {
                var lst_id = $(this).val();
                var ls_id = $("#pur_ls_id").val();
                var vcc_id = $("#vaccine_id").val();
                if (lst_id != '') {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('vaccine/getAssignedVaccineByLivestockAndTypeWise'); ?>',
                        data: {
                            lst_id: lst_id,
                            ls_id: ls_id,
                            vcc_id: vcc_id,
                        },
                        success: function(data) {
                            if (data != 0) {
                                $('#submitButton').attr('disabled', 'disabled');
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: '<?= lang('you_have_already_assign_this_vaccine_in_this_variant'); ?>'
                                });
                                $("select#livestock_type")[0].selectedIndex = 0;
                                $('#livestock_type').trigger('change');
                            } else {
                                $('#submitButton').removeAttr('disabled');
                            }
                        }
                    });
                }
            });


        });

        // Cascading Dropdown
        $("#pur_ls_id").on('change', function() {
            var iid = $(this).val();
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('purchase/getLivestockTypeCascadingDropdown'); ?>',
                data: {
                    livestock_id: iid,
                },
                success: function(data) {
                    $("#livestock_type").html(data);
                }
            });
        });

        /* ==================== Multiple Dropdown for Insert Dose ==================== */
        let serial_no = 1;
        $(".addCF").click(function() {
            serial_no++;
            $("#customFields").append(`<tr valign="top"> 
            <td>
                <div class="form-group" id="">
                    <input type="number" name="vdq_vaccination_date[]" class="form-control input__number" data-check_serial="${serial_no}" value="" placeholder="Enter Day" required>
                </div>
            </td>
            <td>
                <select name="vdq_vaccine_route[]" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                            <option value=""><?= lang('please_select_route'); ?></option>
                            <?php if (!empty($vaccineRoutes))
                                foreach ($vaccineRoutes->result() as $route) { ?>
                                <option value="<?php echo $route->vccr_id; ?>"><?php echo $route->vccr_name; ?></option>
                            <?php } ?>
                </select>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" name="vdq_description[]" class="form-control" id="" value="">
                </div>
                </td>
            <td><a href="javascript:void(0);" class="remCF button button-danger" title="Delete this dose"><i class="fas fa-trash" aria-hidden="true"></i></a></td></tr>`);
            // Remove White  Space
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


            serialWiseDoseDataCheck();



        });

        serialWiseDoseDataCheck();

        function serialWiseDoseDataCheck() {
            $(".input__number").on('blur', function(e) {
                let serial_no = parseInt($(this).data("check_serial"));
                let current_value = parseInt($(this).val());

                let remove_status = 0;

                $(".input__number").each(function() {
                    let value_of_less_than = parseInt($(this).val());
                    if (parseInt($(this).data("check_serial")) < serial_no) {
                        if (value_of_less_than >= current_value) {
                            remove_status = 1;
                        } else {
                            remove_status = 0;
                        }
                    } else if (parseInt($(this).data("check_serial")) > serial_no) {
                        if (value_of_less_than <= current_value) {
                            remove_status = 1;
                        } else {
                            remove_status = 0;
                        }
                    }
                });


                if (remove_status == 1) {
                    $(this).val("");
                }
                console.log(remove_status);
            });

        }






        $("#customFields").on('click', '.remCF', function() {
            $(this).parent().parent().remove();
        });





        /* ==================== /.Multiple Dropdown for Insert Dose ==================== */


        /* ==================== Add Wasted Vaccine Modal ==================== */
        $(".addWastedVaccine").click(function(e) {
            var iid = $(this).attr('data-id');
            var title = $(this).attr('data-title');
            var stock_quantity = $(this).attr('data-stock-quantity');
            var vaccine_unit = $(this).attr('data-unit');
            $('#myModal4').modal('show');
            $('#wastedVaccineQuantity').val('');
            $('#vaccineIdW').val(iid);
            $('#vaccineTitleW').text(title);
            $('#vaccineQuantityW').text(stock_quantity + ' (' + vaccine_unit + ')');

            $("#wastedVaccineQuantity").on('keyup change paste', function() {
                var wastedVaccineQuantity = $(this).val();
                if (parseFloat(wastedVaccineQuantity) > parseFloat(stock_quantity)) {
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('please_enter_a_valid_value'); ?>"
                    });
                    $('#wastedVaccineQuantity').val('');
                }
            });

        });
        /* ==================== /.Add Wasted Vaccine Modal ==================== */

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
            'extendedTimeOut': '5000',
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

        // shed quantity alert
        function vaccineRoutingQuantityAlert() {
            var vaccineRoutingQuantityAlert = $("#vaccineRoutingQuantityAlert").val();

            if (vaccineRoutingQuantityAlert == 0) {
                toastr.warning('<?= lang('no_vaccine_route_found'); ?>');
            }
        }
        vaccineRoutingQuantityAlert();
        <?php if ($this->session->flashdata('success')): ?>
        toastr.success('<?= html_escape($this->session->flashdata('success')); ?>');
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
        toastr.error('<?= html_escape($this->session->flashdata('error')); ?>');
        <?php endif; ?>
    });
</script>