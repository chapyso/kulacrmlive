<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('view_vaccination_assign'); ?>
                </header>
                <div class="panel-body">
                    <div class="clearfix">
                        <a href="<?php echo base_url('vaccine/vaccine'); ?>">
                            <div class="btn-group">
                                <button class="button button-primary">
                                    <i class="fa-solid fa-circle-arrow-left"></i>
                                </button>
                            </div>
                        </a>
                        <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                    </div>
                    <div class="adv-table editable-table ">
                        <div class="space15">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="new__cards__auto card__box">
                                        <div class="col-xs-12">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2" class="text-center">
                                                            <strong><?= lang('vaccine_details'); ?> </strong>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="table__column_width"><strong><?= lang('vaccine_id'); ?></strong></td>
                                                        <td class="table__column_width"><?= $vaccineById->vcc_vaccine_id; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__column_width"><strong><?= lang('vaccine_name'); ?></strong></td>
                                                        <td class="table__column_width"><?= $vaccineById->vcc_name; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__column_width"><strong><?= lang('vaccine'); ?> <?= lang('created_at'); ?></strong></td>
                                                        <td class="table__column_width"><?= date('d-m-Y', strtotime($vaccineById->vcc_created_at)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__column_width"><strong><?= lang('note'); ?></strong></td>
                                                        <td class="table__column_width"><?= $vaccineById->vcc_description; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table__column_width"><strong><?= lang('vaccine'); ?> <?= lang('unit'); ?></strong></td>
                                                        <td class="table__column_width">
                                                            <?php
                                                            $vaccinePurchaseUnit = $this->settings_model->getUnitById($this->vaccine_model->getVaccineById($vaccineById->vcc_id)->vcc_unit_id);
                                                            if ($vaccinePurchaseUnit) {
                                                                echo $vaccinePurchaseUnit->un_name;
                                                            } ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                    <th><?php echo lang('serialNo'); ?></th>
                                    <th><?php echo lang('livestock_name'); ?></th>
                                    <th><?php echo lang('livestock_type'); ?></th>
                                    <th><?php echo lang('vaccine_dose'); ?></th>
                                    <th><?php echo lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serial = 0;
                                foreach ($vaccinationsByVaccineId as $vaccination) {
                                    $serial++;
                                ?>
                                    <tr class="">
                                        <td><?= $serial; ?></td>
                                        <td><?= $this->livestock_model->getLivestockById($vaccination->vccn_ls_id)->ls_name; ?></td>
                                        <td><?php $livestock_variant = $this->livestock_model->getLivestockTypeById($vaccination->vccn_lst_id);
                                            if ($livestock_variant) {
                                                echo $livestock_variant->lst_title;
                                            } else {
                                                echo "<span class='text-danger'>" . lang('no_data_found') . "</span>";
                                            }
                                            ?></td>
                                        <td>
                                            <?php
                                            echo $this->vaccine_model->getVaccinationDoseQuantityByVaccinationId($vaccination->vccn_id);
                                            ?>
                                        </td>
                                        <td>
                                            <?php $countDataIfAvailableInVaccineDose = $this->settings_model->getCountRow('vaccine_dose_status', 'vds_id', ['vds_vccn_id' => $vaccination->vccn_id, 'vds_status' => 1]); ?>

                                            <button type="button" data-vaccine-name="<?= $vaccineById->vcc_name; ?>" data-vaccination-id="<?= $vaccination->vccn_id; ?>" data-ls-id="<?= $vaccination->vccn_ls_id; ?>" data-lst-id="<?= $vaccination->vccn_lst_id; ?>" data-vaccine-id="<?= $vaccineById->vcc_id; ?>" class="button button-warning editButton" data-toggle="modal"><i class=" fas fa-edit"></i> <?= lang('edit'); ?></button>
                                            <form action="<?php echo base_url('vaccine/deleteVaccinationDoses'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                <input type="hidden" name="vccn_id" value="<?= $vaccination->vccn_id; ?>">
                                                <input type="hidden" name="vccn_vcc_id" value="<?= $vaccination->vccn_vcc_id; ?>">
                                                <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                <button <?php echo ($countDataIfAvailableInVaccineDose == 0) ? '' : "disabled"; ?> type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                            </form>
                                            <button type="button" data-vaccine-id="<?= $vaccineById->vcc_vaccine_id  ?>" data-vaccination-id="<?= $vaccination->vccn_id; ?>" data-id="<?= $vaccineById->vcc_id; ?>" data-vaccine-name="<?= $vaccineById->vcc_name; ?>" class="button button-info viewDosesPopup"><i class="fas fa-eye"></i> <?= lang('view_doses'); ?></button>
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

<!-- ==================== View Vaccine Doses ==================== -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width: 1000px; margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><i class="fa fa-eye"></i> <?= lang('view_doses'); ?> </h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table bg-info-light">
                            <tr>
                                <td><strong><?= lang('vaccine_name'); ?>:</strong> </td>
                                <td id="vaccineName"></td>
                            </tr>
                            <tr>
                                <td><strong><?= lang('vaccine_id'); ?>:</strong> </td>
                                <td id="vaccineId"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <table class="table table-striped table-hover table-bordered" id="editable-sample">
                    <thead>
                        <tr>
                            <th> <?= lang('serialNo'); ?> </th>
                            <th> <?= lang('vaccination_day_after'); ?> </th>
                            <th> <?= lang('vaccination_routes'); ?> </th>
                            <th> <?= lang('description'); ?> </th>
                        </tr>
                    </thead>
                    <tbody class="viewP">
                    </tbody>
                </table>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="bg-primary-light">
                                <strong class="text-danger"> <?= lang('note'); ?></strong>: <?= lang('vaccination_doses_view_modal_message'); ?>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- ==================== /.View Vaccine Doses ==================== -->

<!-- ==================== Edit Vaccine Doses ==================== -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width: 1000px; margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('edit_vaccine_with_doses'); ?> </strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" id="vaccinationEditForm" action="<?php echo base_url('vaccine/insertVaccination'); ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group col-sm-4">
                                <label for="exampleInputEmail1"><?php echo lang('vaccine_name'); ?></label>
                                <input type="text" class="form-control" name="" id="vaccine_name" value='' readonly>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="exampleInputEmail1"><?php echo lang('ls_name_variant_modal'); ?></label><span class="text-danger">*</span>
                                <select name="vccn_ls_id" id="pur_ls_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_livestock'); ?> </option>
                                    <?php if ($livestocks) {
                                        foreach ($livestocks as $livestock) { ?>
                                            <option value="<?php echo $livestock->ls_id; ?>"> <?php echo $livestock->ls_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="exampleInputEmail1"><?= lang('livestock_variant'); ?> </label><span class="text-danger">*</span>
                                <select name="vccn_lst_id" class="form-control js-example-basic-single" id="livestock_type" placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_variant'); ?> </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h4 class="text-center"><?= lang('vaccine_doses'); ?> </h4>

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
                            <table class="table" id="customFieldsEdit">
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?= lang('vaccination_days'); ?> </label> <i class="fa fa-info-circle" aria-hidden="true" title="<?= lang('Write_down_how_many_days_after_assign_the_livestock_to_shed_you_want_to_vaccinate'); ?> "></i>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo lang('vaccine_route'); ?></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?= lang('description'); ?> </label>
                                        </div>
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                                <tbody class="editViewDoses" id="disableSubmitButton">

                                </tbody>
                            </table>
                            <div id="invalidInfo" class="text-center"></div>

                            <div class="text-right">
                                <a href="javascript:void(0);" class="addCFEdit button button-primary" title="Click here to add more vaccine dose."><i class="fa fa-plus-circle"></i> <?= lang('add_more_dose'); ?> </a>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="vccn_id" id="vaccination_id">
                    <input type="hidden" name="vcc_id" id="vaccine_iid">
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button" id="submitButton"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- ==================== /.Edit New Vaccine Doses ==================== -->




<!-- Javascript For Edit Trip -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {

        /* ==================== View Vaccine Doses in popup ==================== */
        $(".viewDosesPopup").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            var name = $(this).attr('data-vaccine-name');
            var vaccine_id = $(this).attr('data-vaccine-id');
            var vaccination_id = $(this).attr('data-vaccination-id');
            $('.viewP').html("");
            $("#vaccineName").text(name);
            $("#vaccineId").text(vaccine_id);
            $.ajax({
                url: 'vaccine/getVaccineDosesByJason?vdq_vccn_id=' + vaccination_id,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                var all_doses = response.dosesById;
                $.each(all_doses, function(key, value) {

                    $.ajax({
                        url: 'vaccine/getVaccineRouteNamesByJason?vccr_id=' + value.vdq_vaccine_route,
                        method: 'GET',
                        data: '',
                        dataType: 'json',
                    }).success(function(data) {
                        var all_names = data.routeNamesById;
                        $.each(all_names, function(keys, valueNames) {

                            var $tr = $('<tr>').append(
                                $('<td>').text(key + 1),
                                $('<td>').text(value.vdq_vaccination_date),
                                $('<td>').text(valueNames.vccr_name),
                                $('<td>').text(value.vdq_description)
                            );
                            $(".viewP").append($tr);
                        });
                        $('#myModal').modal('show');
                    });
                });
            });
        });
        /* ==================== /.View Vaccine Doses in popup ==================== */


        /* ==================== Edit Assigned Vaccine ==================== */
        $(".editButton").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-vaccination-id');
            var vaccineId = $(this).attr('data-vaccine-id');
            var livestockId = $(this).attr('data-ls-id');
            var LivestockTypeId = $(this).attr('data-lst-id');
            var vaccineName = $(this).attr('data-vaccine-name');
            $('.editViewDoses').html("");
            $("#vaccine_iid").val(vaccineId);
            $("#vaccination_id").val(iid);
            $('#vaccinationEditForm').find('[name="vccn_ls_id"]').attr("livestock_type_id", LivestockTypeId)
            $("#pur_ls_id").val(livestockId).trigger('change');
            $("#vaccine_name").val(vaccineName);
            $.ajax({
                url: 'vaccine/getVaccineDosesByJason?vdq_vccn_id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                var all_doses = response.dosesById;
                var array = '';
                $.each(all_doses, function(key, value) {
                    array += `
                        <tr>
                            <td>
                            <input type="number" name="vdq_vaccination_date[]" class="form-control input__number2" value='${value.vdq_vaccination_date}' required>
                            </td>
                            <td>
                            <select name="vdq_vaccine_route[]" class="form-control js-example-basic-single code selectedValue" data-id='${value.vdq_vaccine_route}' style="width: 100%;" required>
                                <option value=""><?= lang('please_select_route'); ?></option>
                                <?php if (!empty($vaccineRoutes))
                                    foreach ($vaccineRoutes->result() as $route) {
                                ?>
                                        <option value="<?php echo $route->vccr_id; ?>"><?php echo $route->vccr_name; ?></option>
                                <?php } ?>
                            </select>
                            </td>
                            <td>
                            <input type="text" name="vdq_description[]" class="form-control" value='${value.vdq_description}'></td>
                            <td><a href="javascript:void(0);" class="remCFEdit2 button button-danger"><i class="fas fa-trash" aria-hidden="true"></i></a></td>
                        </tr>`;
                    // Remove White  Space
                    $(".input__number2").on('keyup change paste keypress', function(e) {
                        var data, i;
                        data = document.querySelectorAll(".input__number2"); //HTML DOM querySelector() Method
                        for (i = 0; i < data.length; i++) {
                            data[i].value = data[i].value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                        }
                    });
                    $(".input__number2").on('drop', function(e) {
                        $(this).prop("readonly", true)
                    });
                    $(".input__number2").on('click, keyup', function(e) {
                        $(this).prop("readonly", false)
                    });
                });

                $(".editViewDoses").append(array);
                $('#myModal2').modal('show');

                // For old field
                $("#customFieldsEdit").on('click', '.remCFEdit2', function() {
                    $(this).parent().parent().remove();
                    buttonEnabler1();
                });

                // Selected dropdown Value
                $(".selectedValue").each(function() {
                    $(this).val($(this).attr("data-id"));
                });

                // Button Disable if there is no row
                function buttonEnabler1() {
                    var rowCount = $('#disableSubmitButton tr').length;
                    if (rowCount < 1) {
                        $('#submitButton').attr('disabled', 'disabled');
                        $('#invalidInfo').html('<p class="text-danger"><?= lang('invalid_information'); ?></p>');
                    } else {
                        $('#submitButton').removeAttr('disabled');
                        $('#invalidInfo').html('');
                    }
                }

            });
            /* ==================== Multiple Dropdown for edit Dose ==================== */
            $(".addCFEdit").click(function() {
                buttonEnabler2();
                $("#customFieldsEdit").append(`<tr valign="top" id="newId"> 
            <td>
                <div class="form-group">
                    <input type="number" name="vdq_vaccination_date[]" class="form-control input__number1" value="" placeholder="Enter Day" required>
                </div>
            </td>
            <td>
            <select name="vdq_vaccine_route[]" class="form-control js-example-basic-single code" placeholder="" style="width: 100%;" required>
                        <option value=""><?= lang('please_select_route'); ?> </option>
                        <?php if (!empty($vaccineRoutes))
                            foreach ($vaccineRoutes->result() as $route) { ?>
                                <option value="<?php echo $route->vccr_id; ?>"><?php echo $route->vccr_name; ?></option>
                        <?php } ?>
                    </select>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" name="vdq_description[]" class="form-control code" id="" value="" placeholder="">
                </div>
                </td>
            <td><a href="javascript:void(0);" class="remCF button button-danger" title="<?= lang('delete_this_dose'); ?>"><i class="fas fa-trash" aria-hidden="true"></i></a></td></tr>`);

                // Remove White  Space
                $(".input__number1").on('keyup change paste', function(e) {
                    var data, i;
                    data = document.querySelectorAll(".input__number1"); //HTML DOM querySelector() Method
                    for (i = 0; i < data.length; i++) {
                        data[i].value = data[i].value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                    }
                });
            });
            // For new field
            $("#customFieldsEdit").on('click', '.remCF', function() {
                $(this).parent().parent().remove();
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

            function buttonEnabler2() {
                var rowCount = $('#disableSubmitButton tr').length;
                var countingRow = parseFloat(rowCount) + 1;
                if (countingRow > 0) {
                    $('#submitButton').removeAttr('disabled');
                    $('#invalidInfo').html('');
                }
            }


            // Already Vaccine Assigned Alert
            $("#livestock_type").on('change', function() {
                var lst_id = $(this).val();
                var ls_id = $("#pur_ls_id").val();
                var vcc_id = $("#vaccine_iid").val();
                var vccn_id = $("#vaccination_id").val();
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
                            if (data != 0 && data != vccn_id) {
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
        /* ==================== /.Multiple Dropdown for edit Dose ==================== */


        // Cascading Dropdown for livestock and variant
        $("#pur_ls_id").on('change', function() {
            var iid = $(this).val();
            var typeId = $('#vaccinationEditForm').find('[name="vccn_ls_id"]').attr("livestock_type_id");
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('purchase/getLivestockTypeCascadingDropdown'); ?>',
                data: {
                    livestock_id: iid,
                    livestock_type_id: typeId,
                },
                success: function(data) {
                    $("#livestock_type").html(data);
                }
            });
        });


    });
</script>