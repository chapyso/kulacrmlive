<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('sale_wise_payments_list'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table ">
                        <div class="clearfix">
                            <a href="<?php echo base_url("payments/viewClientWisePayment?c_id=$saleInformationById->prss_c_id"); ?>">
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
                                    <div class="col-xs-3">
                                        <div class="text-center">
                                            <p><strong><?= lang('basic_information'); ?></strong></p>
                                        </div>
                                        <table class="table">
                                            <?php $clientById = $this->settings_model->getSingleData('client', ['c_id' => $saleInformationById->prss_c_id, 'c_status' => 1]); ?>
                                            <tr>
                                                <td width="40%">
                                                    <?php if ($clientById->c_img_url) { ?>
                                                        <img class="img-square" style="height: 140px; width: 140px; border-radius: 5px;" src="<?php echo $clientById->c_img_url; ?>" alt="No img">
                                                    <?php } else { ?>
                                                        <img class="img-square" style="height: 140px; width: 140px; border-radius: 5px;" src="<?php echo base_url('uploads/avatar/alter-image.png'); ?>" alt="No img">
                                                    <?php } ?>
                                                </td>
                                                <td width="60%">
                                                    <p><strong><?= lang('client'); ?>:</strong> <?= $clientById->c_name; ?></p>
                                                    <p><strong><?= lang('email'); ?>:</strong> <?= $clientById->c_email; ?></p>
                                                    <p><strong><?= lang('phone'); ?>:</strong> <?= $clientById->c_phone; ?></p>
                                                    <p><strong><?= lang('address'); ?>:</strong> <?= $clientById->c_address; ?></p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-xs-3">
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
                                                <p><?= lang('total_receivable_amount'); ?></p>
                                                <h3> <?php $saleTotal = $saleInformationById->prss_grand_total;
                                                        if ($saleTotal) {
                                                            echo "$settings->currency" . number_format($saleTotal, 2, ".", ",");
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
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
                                                <p><?= lang('total_received_amount'); ?></p>
                                                <h3> <?php $clientReceivedAmount = $this->payments_model->getSumClientAndProductSaleWiseTotalReceivedAmount($clientById->c_id, $saleInformationById->prss_id);
                                                        if ($clientReceivedAmount) {
                                                            echo "$settings->currency " .  number_format($clientReceivedAmount, 2, ".", ",");
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="row">
                                            <div class="text-center">
                                                <ul class="social__icon">
                                                    <li class="icon">
                                                        <span><i class="fas fa-money-bill-wave"></i></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="text-center">
                                                <p><?= lang('total_due_amount'); ?></p>
                                                <h3><?php
                                                    $totalDueAmount = $saleTotal - $clientReceivedAmount;
                                                    if ($totalDueAmount) {
                                                        echo "$settings->currency " . number_format($totalDueAmount, 2, ".", ",");
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

                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th><?= lang('serialNo'); ?></th>
                                    <th><?= lang('date'); ?></th>
                                    <th><?= lang('received_amount'); ?></th>
                                    <th><?= lang('paid_by'); ?></th>
                                    <th><?= lang('cheque'); ?></th>
                                    <th><?= lang('reference'); ?></th>
                                    <th><?= lang('note'); ?></th>
                                    <th><?= lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $serial = 0;
                                foreach ($paymentsProductsBySaleId as $list) {
                                    $serial++;
                                ?>
                                    <tr class="">
                                        <td> <?= $serial ?></td>
                                        <td> <?= date("$settings->date_format", strtotime($list->cp_date)); ?></td>
                                        <td> <?= $list->cp_received_amount ?></td>
                                        <td> <?= $list->cp_paid_by ?></td>
                                        <td> <?= $list->cp_cheque_no ?></td>
                                        <td> <?= $list->cp_reference ?></td>
                                        <td> <?= $list->cp_description ?></td>
                                        <td>
                                            <button type="button" class="button button-warning editClientProductPayment" data-toggle="modal" data-payment-id="<?php echo $list->cp_id; ?>" data-client-id="<?php echo $list->cp_c_id; ?>" data-sale-id="<?php echo $list->cp_prss_id; ?>" data-received-amount="<?php echo $list->cp_received_amount; ?>" data-paid_by="<?php echo $list->cp_paid_by; ?>" data-cheque_no="<?php echo $list->cp_cheque_no; ?>" data-reference="<?php echo $list->cp_reference; ?>" data-date="<?php echo date("$settings->date_format", strtotime($list->cp_date)); ?>" data-description="<?php echo $list->cp_description; ?>" data-client-name="<?php echo $clientById->c_name; ?>" data-total-due="<?php echo $totalDueAmount; ?>"><i class="fas fa-edit"></i> <?= lang('edit'); ?></button>
                                            <form action="<?php echo base_url('payments/deleteClientPayments'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>')">
                                                <input type="hidden" name="cp_prss_id" value="<?php echo $list->cp_prss_id; ?>">
                                                <input type="hidden" name="cp_id" value="<?php echo $list->cp_id; ?>">
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
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<!--footer start-->



<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('edit_payment'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('payments/updateClientPayment') ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('name'); ?></label>
                            <input type="text" class="form-control" id="client_name" placeholder="" readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('due_amount'); ?></label>
                            <input type="text" class="form-control" id="due_amount_product" placeholder="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('received_amount'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control input__number" name="cp_received_amount" id="received_amount" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"> <?php echo lang('paid_by'); ?><span class="text-danger">*</span></label>
                        <select id="paid_by" class="form-control m-bot15" name="cp_paid_by" value='' required>
                            <option value="cash"><?php echo lang('cash'); ?> </option>
                            <option value="cheque"><?php echo lang('cheque'); ?></option>
                            <option value="other"><?php echo lang('other'); ?> </option>
                        </select>
                    </div>
                    <div class="form-group" id="cheque_no" style="display: none;">
                        <label for="exampleInputEmail1"> <?php echo lang('cheque_no'); ?></label>
                        <input type="text" class="form-control" name="cp_cheque_no" id="cheque_number" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('reference'); ?></label>
                        <input type="text" class="form-control" name="cp_reference" id="reference" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('date'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-inline datepicker" name="cp_date" id="date" value='<?php echo get_current_date(); ?>' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="cp_description" class="form-control" id="description" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="cp_id" id="payment_id" value=''>
                    <input type="hidden" name="cp_c_id" id="client_id" value=''>
                    <input type="hidden" name="cp_prss_id" id="sale_id" value=''>
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

        $(".editClientProductPayment").click(function(e) {
            var payment_id = $(this).attr('data-payment-id');
            var client_id = $(this).attr('data-client-id');
            var sale_id = $(this).attr('data-sale-id');
            var received_amount = $(this).attr('data-received-amount');
            var paid_by = $(this).attr('data-paid_by');
            var cheque_no = $(this).attr('data-cheque_no');
            var reference = $(this).attr('data-reference');
            var date = $(this).attr('data-date');
            var description = $(this).attr('data-description');
            var client_name = $(this).attr('data-client-name');
            var due_amount = $(this).attr('data-total-due');
            $('#myModal2').modal('show');
            $('#payment_id').val(payment_id);
            $('#client_id').val(client_id);
            $('#sale_id').val(sale_id);
            $('#received_amount').val(received_amount);
            $('#paid_by').val(paid_by).trigger('change');
            $('#cheque_number').val(cheque_no);
            $('#reference').val(reference);
            $('#date').val(date);
            $('#description').val(description);
            $('#client_name').val(client_name);
            $('#due_amount_product').val("<?php echo $settings->currency; ?>" + due_amount);

            $("#received_amount").on('keyup change paste', function(e) {
                var payableAmountProduct = $(this).val();
                var totalDueAmount = parseFloat(received_amount) + parseFloat(due_amount);
                if (parseFloat(totalDueAmount) < parseFloat(payableAmountProduct)) {
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('please_enter_a_valid_value'); ?>"
                    });
                    $("#received_amount").val(received_amount);
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