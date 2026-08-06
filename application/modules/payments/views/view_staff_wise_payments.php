<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel">
            <header class="panel-heading">
                <i class="fas fa-stream"></i> <?= lang('staff_payment_report'); ?>
            </header>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="clearfix">
                        <a href="<?php echo base_url('payments/listStaffPayments'); ?>">
                            <div class="btn-group">
                                <button class="button button-primary">
                                    <i class="fa-solid fa-circle-arrow-left"></i>
                                </button>
                            </div>
                        </a>
                        <button data-id="<?= $staffById->sf_id ?>" data-title="<?= $staffById->sf_name ?>" class="button button-success addStaffPayment">
                            <i class="fas fa-plus-circle"></i> <?= lang('add_payment'); ?>
                        </button>
                        <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                    </div>
                    <div class="space15">

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="new__cards__auto card__box">
                                    <div class="col-xs-4">
                                        <div class="text-center">
                                            <p><strong><?= lang('basic_information'); ?></strong></p>
                                        </div>
                                        <table class="table">
                                            <tr>
                                                <td width="40%">
                                                    <?php if ($staffById->sf_img_url) { ?>
                                                        <img class="img-square" style="height: 140px; width: 140px; border-radius: 5px;" src="<?php echo $staffById->sf_img_url; ?>" alt="No img">
                                                    <?php } else { ?>
                                                        <img class="img-square" style="height: 140px; width: 140px; border-radius: 5px;" src="<?php echo base_url('uploads/avatar/alter-image.png'); ?>" alt="No img">
                                                    <?php } ?>
                                                </td>
                                                <td width="60%">
                                                    <p><strong><?= lang('staff'); ?>:</strong> <?= $staffById->sf_name; ?></p>
                                                    <p><strong><?= lang('email'); ?>:</strong> <?= $staffById->sf_email; ?></p>
                                                    <p><strong><?= lang('phone'); ?>:</strong> <?= $staffById->sf_phone; ?></p>
                                                    <p><strong><?= lang('address'); ?>:</strong> <?= $staffById->sf_address; ?></p>
                                                    <p><strong><?= lang('note'); ?>:</strong> <?= $staffById->sf_description; ?></p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="row">
                                            <div class="text-center">
                                                <ul class="social__icon">
                                                    <li class="icon">
                                                        <span><i class="fas fa-money-bill"></i></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="text-center">
                                                <p><?= lang('total_payment_amount'); ?></p>
                                                <h3> <?php $paymentsTotal = $this->payments_model->getStaffWiseTotalPaymentAndReceivedAmountSum($staffById->sf_id, 'sfp_payment_amount');
                                                        if ($paymentsTotal) {
                                                            echo $settings->currency . number_format_currency($paymentsTotal, 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="row">
                                            <div class="text-center">
                                                <ul class="social__icon">
                                                    <li class="icon">
                                                        <span><i class="fas fa-money-bill-alt"></i></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="text-center">
                                                <p><?= lang('payment_count'); ?></p>
                                                <h3> <?php $paymentsCount = $this->payments_model->getStaffWisePaymentAndReceivedCountRow($staffById->sf_id, 'sfp_payment_amount');
                                                        if ($paymentsCount) {
                                                            echo $paymentsCount;
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table table-striped table-hover table-bordered" id="editable-sample">
                    <thead>
                        <tr>
                            <th><?php echo lang('serialNo'); ?></th>
                            <th><?= lang('payment_date'); ?></th>
                            <th><?= lang('paid_amount'); ?></th>
                            <th><?= lang('paid_by'); ?></th>
                            <th><?= lang('cheque_no'); ?></th>
                            <th><?= lang('reference'); ?></th>
                            <th><?= lang('description'); ?></th>
                            <th><?php echo lang('options'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $serial = 0;
                        foreach ($staffPayments as $list) {
                            $serial++;
                        ?>
                            <tr>
                                <td><?= $serial; ?></td>
                                <td><?= date("$settings->date_format", strtotime($list->sfp_date)); ?></td>
                                <td><?= $settings->currency . number_format_currency($list->sfp_payment_amount, 2); ?></td>
                                <td><?= $list->sfp_paid_by ?></td>
                                <td><?= $list->sfp_cheque_no ?></td>
                                <td><?= $list->sfp_reference ?></td>
                                <td><?= $list->sfp_description ?></td>
                                <td>
                                    <button type="button" class="button button-warning editStaffPayment" data-toggle="modal" data-name="<?php echo $staffById->sf_name; ?>" data-payment-id="<?php echo $list->sfp_id; ?>" data-staff-id="<?php echo $list->sfp_sf_id; ?>" data-paid-amount="<?php echo $list->sfp_payment_amount; ?>" data-received-amount="<?php echo $list->sfp_received_amount; ?>" data-paid_by="<?php echo $list->sfp_paid_by; ?>" data-cheque_no="<?php echo $list->sfp_cheque_no; ?>" data-reference="<?php echo $list->sfp_reference; ?>" data-date="<?php echo date("$settings->date_format", strtotime($list->sfp_date)); ?>" data-description="<?php echo $list->sfp_description; ?>"><i class="fa fa-edit"></i> <?= lang('edit'); ?></button>
                                    <form action="<?php echo base_url('payments/deleteStaffPayments'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                        <input type="hidden" name="sfp_id" value="<?php echo $list->sfp_id; ?>">
                                        <input type="hidden" name="sfp_sf_id" value="<?php echo $list->sfp_sf_id; ?>">
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
    </section>
</section>
<!--main content end-->
<!--footer start-->



<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('add_client_payment'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('payments/insertStaffPayment') ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('name'); ?></label>
                        <input type="text" class="form-control" id="sf_title" value='' placeholder="" readonly>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('payment_amount'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control input__number" name="sfp_payment_amount" id="" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"> <?php echo lang('paid_by'); ?><span class="text-danger">*</span></label>
                        <select id="paid_by" class="form-control m-bot15" name="sfp_paid_by" value='' required>
                            <option value="cash"><?php echo lang('cash'); ?> </option>
                            <option value="cheque"><?php echo lang('cheque'); ?></option>
                            <option value="other"><?php echo lang('other'); ?> </option>
                        </select>
                    </div>
                    <div class="form-group" id="cheque_no" style="display: none;">
                        <label for="exampleInputEmail1"> <?php echo lang('cheque_no'); ?></label>
                        <input type="text" class="form-control" name="sfp_cheque_no" id="" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('reference'); ?></label>
                        <input type="text" class="form-control" name="sfp_reference" id="" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('date'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-inline default-date-picker" name="sfp_date" id="" value='<?php echo get_current_date(); ?>' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="sfp_description" class="form-control" id="" rows="5" placeholder="Enter description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="sfp_sf_id" id="staff_id" value=''>
                    <section class=" text-right">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Edit -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('edit_payment'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('payments/updateStaffPayment') ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">

                    <div class="form-group ">
                        <label for="exampleInputEmail1"><?= lang('name'); ?></label>
                        <input type="text" class="form-control" name="" id="staff_name" placeholder="" readonly>
                    </div>
                    <div class="form-group ">
                        <label for="exampleInputEmail1"><?= lang('payment_amount'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control input__number" name="sfp_payment_amount" id="paid_amount" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"> <?php echo lang('paid_by'); ?></label>
                        <select id="paid_by_edit" class="form-control m-bot15" name="sfp_paid_by" value='' required>
                            <option value="cash"><?php echo lang('cash'); ?> </option>
                            <option value="cheque"><?php echo lang('cheque'); ?></option>
                            <option value="other"><?php echo lang('other'); ?> </option>
                        </select>
                    </div>
                    <div class="form-group" id="cheque_no_edit" style="display: none;">
                        <label for="exampleInputEmail1"> <?php echo lang('cheque_no'); ?></label>
                        <input type="text" class="form-control" name="sfp_cheque_no" id="cheque_number" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('reference'); ?></label>
                        <input type="text" class="form-control" name="sfp_reference" id="reference" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('date'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-inline datepicker" name="sfp_date" id="date" value='<?php echo get_current_date(); ?>' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="sfp_description" class="form-control" id="description" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="sfp_id" id="payment_id" value=''>
                    <input type="hidden" name="sfp_sf_id" id="staff_iid" value=''>
                    <section class="text-right">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<!-- Javascript For Edit Trip -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#paid_by').on('change', function() {
            if ($(this).val() === "cheque") {
                $("#cheque_no").delay(500).fadeIn(100);
            } else {
                $("#cheque_no").hide()
            }
        });

        $(".addStaffPayment").click(function(e) {
            var iid = $(this).attr('data-id');
            var title = $(this).attr('data-title');
            $('#myModal').modal('show');
            $('#staff_id').val(iid);
            $('#sf_title').val(title);
        });

        /* ============= Edit ============= */
        $('#paid_by_edit').on('change', function() {
            if ($(this).val() === "cheque") {
                $("#cheque_no_edit").delay(500).fadeIn(100);
            } else {
                $("#cheque_no_edit").hide()
            }
        });

        $(".editStaffPayment").click(function(e) {
            var payment_id = $(this).attr('data-payment-id');
            var staff_id = $(this).attr('data-staff-id');
            var staff_name = $(this).attr('data-name');
            var paid_amount = $(this).attr('data-paid-amount');
            var paid_by = $(this).attr('data-paid_by');
            var cheque_no = $(this).attr('data-cheque_no');
            var reference = $(this).attr('data-reference');
            var date = $(this).attr('data-date');
            var description = $(this).attr('data-description');
            $('#myModal2').modal('show');
            $('#payment_id').val(payment_id);
            $('#staff_iid').val(staff_id);
            $('#staff_name').val(staff_name);
            $('#paid_amount').val(paid_amount);
            $('#paid_by_edit').val(paid_by).trigger('change');
            $('#cheque_number').val(cheque_no);
            $('#reference').val(reference);
            $('#date').val(date);
            $('#description').val(description);
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