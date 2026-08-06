<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="col-md-12">
            <!-- page start-->
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('view_vaccine_reports'); ?>
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
                    <div class="adv-table editable-table">
                        <div class="space15">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="new__cards__auto card__box">
                                        <div class="col-xs-12">
                                            <p class="text-center"><strong><?= lang('vaccine_details'); ?></strong></p>
                                            <table class="table">
                                                <tr>
                                                    <td><?= lang('vaccine_name'); ?></td>
                                                    <td><?= lang('total_vaccine_quantity'); ?> (<?php $unit = $this->settings_model->getUnitById($vaccineById->vcc_unit_id);
                                                                                                if ($unit) {
                                                                                                    echo  $unit->un_name;
                                                                                                } else {
                                                                                                    echo 0;
                                                                                                }
                                                                                                ?>)
                                                    </td>
                                                    <td><?= lang('distributed_quantity'); ?> (<?php $unit = $this->settings_model->getUnitById($vaccineById->vcc_unit_id);
                                                                                                if ($unit) {
                                                                                                    echo  $unit->un_name;
                                                                                                } else {
                                                                                                    echo 0;
                                                                                                }
                                                                                                ?>)
                                                    </td>
                                                    <td><?= lang('wasted_quantity'); ?> (<?php $unit = $this->settings_model->getUnitById($vaccineById->vcc_unit_id);
                                                                                            if ($unit) {
                                                                                                echo  $unit->un_name;
                                                                                            } else {
                                                                                                echo 0;
                                                                                            }
                                                                                            ?>)
                                                    </td>
                                                    <td><?= lang('in_stock'); ?> (<?php $unit = $this->settings_model->getUnitById($vaccineById->vcc_unit_id);
                                                                                    if ($unit) {
                                                                                        echo  $unit->un_name;
                                                                                    } else {
                                                                                        echo 0;
                                                                                    }
                                                                                    ?>)
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><?= $vaccineById->vcc_name ?></td>
                                                    <td><?= $this->vaccine_model->getVaccineWiseVaccinePurchaseQuantity($vaccineById->vcc_id, 'vpv_quantity'); ?></td>
                                                    <td><?= $this->vaccine_model->getVaccineWiseVaccineUsedQuantity($vaccineById->vcc_id, 'vds_vaccine_used_quantity'); ?></td>
                                                    <td><?= $this->vaccine_model->getVaccineWastedByVaccineId($vaccineById->vcc_id, 'vccw_quantity'); ?></td>
                                                    <td><?php
                                                        $stocked = $this->vaccine_model->getVaccineWiseVaccinePurchaseQuantity($vaccineById->vcc_id, 'vpv_quantity');
                                                        $feed = $this->vaccine_model->getVaccineWiseVaccineUsedQuantity($vaccineById->vcc_id, 'vds_vaccine_used_quantity');
                                                        $wastedVaccine = $this->vaccine_model->getVaccineWastedByVaccineId($vaccineById->vcc_id, 'vccw_quantity');
                                                        $stillInStock = $stocked - $feed - $wastedVaccine;
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
                        </div>

                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th><?= lang('serialNo'); ?></th>
                                    <th><?= lang('date'); ?></th>
                                    <th><?= lang('wasted_quantity'); ?>
                                        (<?php $unit = $this->settings_model->getUnitById($vaccineById->vcc_unit_id);
                                            if ($unit) {
                                                echo $unit->un_name;
                                            }
                                            ?>)</th>
                                    <th><?= lang('description'); ?></th>
                                    <th><?= lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serial = 0;
                                foreach ($wastedVaccineValues as $value) {
                                    $serial++;
                                ?>
                                    <tr>
                                        <td><?= $serial; ?></td>
                                        <td><?= date("$settings->date_format", strtotime($value->vccw_created_at)); ?></td>
                                        <td><?= $value->vccw_quantity; ?></td>
                                        <td><?= $value->vccw_description; ?></td>
                                        <td>
                                            <button type="button" data-vaccine-id="<?= $vaccineById->vcc_id; ?>" data-id="<?= $value->vccw_id; ?>" data-quantity="<?= $value->vccw_quantity; ?>" data-description="<?= $value->vccw_description; ?>" data-vaccine-title="<?= $vaccineById->vcc_name; ?>" data-stock="<?= $stillInStock; ?>" data-unit="<?php echo $unit->un_name; ?>" class="button button-warning editButton"><i class="fas fa-edit"></i> <?= lang('edit'); ?></button>
                                            <form action="<?php echo base_url('vaccine/deleteWastedVaccine'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item') ?>');">
                                                <input type="hidden" name="vccw_id" value="<?= $value->vccw_id; ?>">
                                                <input type="hidden" name="vccw_vcc_id" value="<?= $vaccineById->vcc_id; ?>">
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
                <h4 class="modal-title"><strong><i class="fas fa-edit"></i> <?= lang('edit_wasted_vaccine'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" id="" action="<?php echo base_url('') ?>vaccine/updateWastedVaccine" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <table class="table table-bordered">
                            <thead>
                                <th style="width: 50%;">
                                    <label for="exampleInputEmail1"><?= lang('vaccine_name'); ?>:</label>
                                    <span id="vaccineTitle"> </span>
                                </th>
                                <th style="width: 50%;">
                                    <label for="exampleInputEmail1"><?= lang('stock_quantity'); ?>:</label>
                                    <span id="vaccineStock"> </span>
                                </th>
                            </thead>
                        </table>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('wasted_quantity'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control input__number" name="vccw_quantity" id="vaccineQuantity" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('description'); ?></label>
                        <textarea name="vccw_description" class="form-control" id="vaccineDescription" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="vccw_id" value='' id="wastedVaccineId">
                    <input type="hidden" name="vccw_vcc_id" value='' id="vaccineId">
                    <section class="">
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
            var vaccine__title = $(this).attr('data-vaccine-title');
            var wasted_quantity = $(this).attr('data-quantity');
            var wasted_description = $(this).attr('data-description');
            var vaccine__stock = $(this).attr('data-stock');
            var vaccine__id = $(this).attr('data-vaccine-id');
            var vaccine__unit = $(this).attr('data-unit');
            $('#myModal').modal('show');
            $("#wastedVaccineId").val(iid);
            $("#vaccineTitle").text(vaccine__title);
            $("#vaccineQuantity").val(wasted_quantity);
            $("#vaccineDescription").val(wasted_description);
            $("#vaccineStock").text(vaccine__stock + ' (' + vaccine__unit + ')');
            $("#vaccineId").val(vaccine__id);

            $("#vaccineQuantity").on('keyup change paste', function() {
                var vaccineQuantity = $(this).val();
                var vaccineStockQuantity = parseFloat(vaccine__stock) + parseFloat(wasted_quantity);
                if (parseFloat(vaccineQuantity) > parseFloat(vaccineStockQuantity)) {
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('please_enter_a_valid_value'); ?>"
                    });
                    $('#vaccineQuantity').val(wasted_quantity);
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