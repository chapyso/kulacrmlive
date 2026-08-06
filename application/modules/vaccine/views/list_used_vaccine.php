<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('used_vaccine_list'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table ">
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
                        <div class="space15"></div>
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th><?= lang('serialNo'); ?></th>
                                    <th><?= lang('vaccine_issue_date'); ?> </th>
                                    <th><?= lang('used_date'); ?> </th>
                                    <th><?= lang('vaccine_name'); ?></th>
                                    <th><?= lang('shed'); ?> </th>
                                    <th><?= lang('batch'); ?> </th>
                                    <th><?= lang('vaccine_used_quantity'); ?> </th>
                                    <th><?= lang('stock'); ?> </th>
                                    <th><?= lang('dose_number'); ?> </th>
                                    <th><?= lang('note'); ?> </th>
                                    <th class="noprint"><?= lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serial = 0;
                                foreach ($usedVaccines as $vaccine) {
                                    $serial++;
                                ?>
                                    <tr class="">
                                        <td><?= $serial; ?></td>
                                        <td>
                                            <?php
                                            $vaccineDoseAssignInfo = $this->settings_model->getSingleData('vaccine_dose_assigned_quantity', ['vdq_id' => $vaccine->vds_vdq_id, 'vdq_status' => 1]);
                                            $livestockAssignSummaryData = $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($vaccine->vds_sh_id, $vaccine->vds_batch_id);
                                            $date = date("$settings->date_format", strtotime("$livestockAssignSummaryData->lshs_assign_date + $vaccineDoseAssignInfo->vdq_vaccination_date" . lang('day')));
                                            $vaccinationDate = date("$settings->date_format", strtotime($date));

                                            if (strtotime($vaccinationDate) == strtotime(date("d-m-Y"))) {
                                                echo "<span class='badge bg-danger-light'>" . $vaccinationDate . "</span>";
                                            } elseif (strtotime($vaccinationDate) > strtotime(date("d-m-Y"))) {
                                                echo "<span class='badge bg-warning-light'>" . $vaccinationDate . "</span>";
                                            } elseif (strtotime($vaccinationDate) < strtotime(date("d-m-Y"))) {
                                                echo "<span class='badge bg-info-light'>" . $vaccinationDate . "</span>";
                                            } else {
                                                echo $vaccinationDate;
                                            }

                                            ?>
                                        </td>
                                        <td><?php
                                            $vaccineUsedDate = date("$settings->date_format", strtotime($vaccine->vds_created_at));
                                            if (strtotime($vaccinationDate) == strtotime($vaccineUsedDate)) {
                                                echo "<span class='badge bg-success-light'>" . $vaccineUsedDate . "</span>";
                                            } elseif (strtotime($vaccinationDate) > strtotime($vaccineUsedDate)) {
                                                echo "<span class='badge bg-warning-light'>" . $vaccineUsedDate . "</span>";
                                            } elseif (strtotime($vaccinationDate) < strtotime($vaccineUsedDate)) {
                                                echo "<span class='badge bg-danger-light'>" . $vaccineUsedDate . "</span>";
                                            } else {
                                                $vaccineUsedDate;
                                            }
                                            ?></td>
                                        <td><?= $this->vaccine_model->getVaccineById($vaccine->vds_vcc_id)->vcc_name; ?></td>
                                        <td><strong><?= $this->shed_model->getShedById($vaccine->vds_sh_id)->sh_no; ?>:</strong> <?= $this->shed_model->getShedById($vaccine->vds_sh_id)->sh_title; ?></td>
                                        <td><strong><?= $vaccine->vds_batch_id; ?>: </strong><?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($vaccine->vds_sh_id, $vaccine->vds_batch_id)->lshs_batch_title ?></td>
                                        <td><?php echo $usedVaccineQuantity = $vaccine->vds_vaccine_used_quantity;
                                            echo " ";
                                            if ($usedVaccineQuantity) {
                                                $vaccinePurchaseUnit = $this->settings_model->getUnitById($this->vaccine_model->getVaccineById($vaccine->vds_vcc_id)->vcc_unit_id);
                                                if ($vaccinePurchaseUnit) {
                                                    echo $vaccinePurchaseUnit->un_name;
                                                }
                                            }


                                            ?></td>
                                        <td>
                                            <?php
                                            // Vaccine Stock Calculation
                                            $purchaseVaccineQuantity = $this->vaccine_model->getVaccineWiseVaccinePurchaseQuantity($vaccine->vds_vcc_id, 'vpv_quantity');
                                            $usedVaccineQuantity = $this->vaccine_model->getVaccineWiseVaccineUsedQuantity($vaccine->vds_vcc_id, 'vds_vaccine_used_quantity');
                                            $wastedVaccine = $this->vaccine_model->getVaccineWastedByVaccineId($vaccine->vds_vcc_id, 'vccw_quantity');
                                            $inStockVaccine =  $purchaseVaccineQuantity - ($usedVaccineQuantity + $wastedVaccine);
                                            echo $inStockVaccine . ' ' . $vaccinePurchaseUnit->un_name;
                                            ?>
                                        </td>
                                        <td><?php
                                            echo "<span class='button bg-success-light'>" . lang('dose') . ' ' . $vaccineDoseAssignInfo->vdq_dose_serial . "</span>";
                                            ?></td>
                                        <td><?= $vaccine->vds_description; ?></td>
                                        <!-- Next vaccination date -->
                                        <td class="noprint">
                                            <button type="button" class="button button-warning editButton" data-toggle="modal" data-id="<?= $vaccine->vds_id; ?>" data-name="<?= $this->vaccine_model->getVaccineById($vaccine->vds_vcc_id)->vcc_name ?>" data-used-quantity="<?= $vaccine->vds_vaccine_used_quantity; ?>" data-description="<?= $vaccine->vds_description; ?>" data-vaccine-stock="<?php echo $inStockVaccine; ?>" data-unit="<?php echo $vaccinePurchaseUnit->un_name; ?>"><i class="fas fa-edit"></i> <?= lang('edit'); ?></button>
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
<!-- ==================== Vaccine Used ==================== -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-edit"></i> <?= lang('update_used_vaccine'); ?> </strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('vaccine/updateUsedVaccine'); ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="exampleInputEmail1"><?= lang('vaccine_name'); ?> </label>
                                    <input type="text" class="form-control" name="" id="vaccineNamee" value='' placeholder="" readonly>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="exampleInputEmail1"><?= lang('stock_quantity'); ?></label>
                                    <input type="text" class="form-control" name="" id="vccUnit" placeholder="" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?= lang('vaccine_used_quantity'); ?><span class="text-danger">*</span></label>
                                <input type="text" class="form-control input__number" name="vds_vaccine_used_quantity" id="vccQuantity" value='' placeholder="Enter Quantity" required>
                            </div>
                            <td>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?= lang('note'); ?> </label>
                                    <textarea type="text" name="vds_description" class="form-control" id="vccDescription" value="" rows="5" cols="10" style="height: auto !important;" placeholder="Enter Note"></textarea>
                                </div>
                            </td>
                        </div>
                    </div>
                    <input type="hidden" name="vds_id" id="vds_id">
                    <section class="text-right">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- ==================== /.Vaccine Used ==================== -->
<!-- Javascript For Edit Trip -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Vaccine Dose Used Edit
        $(".editButton").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var quantity = $(this).attr('data-used-quantity');
            var description = $(this).attr('data-description');
            var vaccine_stock = $(this).attr('data-vaccine-stock');
            var vaccine_unit = $(this).attr('data-unit');
            $('#myModal').modal('show');
            $("#vds_id").val(iid);
            $("#vaccineNamee").val(name);
            $("#vccQuantity").val(quantity);
            $("#vccDescription").val(description);
            $("#vccUnit").val(vaccine_stock + ' ' + vaccine_unit);

            $("#vccQuantity").on('keyup change paste', function() {
                var usedStock = $(this).val();
                var totalStock = parseFloat(vaccine_stock) + parseFloat(quantity);

                var onKeyupAvailableStock = parseFloat(totalStock) - parseFloat(usedStock);

                $("#vccUnit").val(parseFloat(onKeyupAvailableStock) + ' ' + vaccine_unit);

                if (parseFloat(usedStock) > parseFloat(totalStock)) {
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('please_enter_a_valid_value'); ?>"
                    });
                    $("#vccQuantity").val(quantity);
                } else if (parseFloat(usedStock) == '') {
                    $("#vccUnit").val(parseFloat(totalStock) + ' ' + vaccine_unit);
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