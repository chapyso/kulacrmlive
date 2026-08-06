<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel">
            <header class="panel-heading">
                <i class="fas fa-book"></i> <?= lang('client'); ?> <?= lang('ledger'); ?>
            </header>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="clearfix noprint">
                        <a href="<?php echo base_url('payments/listClientPayments'); ?>">
                            <div class="btn-group">
                                <button class="button button-primary">
                                    <i class="fa-solid fa-circle-arrow-left"></i>
                                </button>
                            </div>
                        </a>
                        <a href="<?php echo base_url('client/listClient'); ?>">
                            <div class="btn-group">
                                <button class="button button-info">
                                    <i class="fa-solid fa-circle-arrow-up"></i> <?= lang('client'); ?> <?= lang('list'); ?>
                                </button>
                            </div>
                        </a>
                        <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                    </div>
                    <div class="space15">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="new__cards__auto card__box">
                                    <div class="col-xs-3">
                                        <div class="text-center">
                                            <p><strong><?= lang('basic_information'); ?></strong></p>
                                        </div>
                                        <table class="table">
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
                                                    <p><strong><?= lang('note'); ?>:</strong> <?= $clientById->c_description; ?></p>
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
                                                <h3> <?php $saleTotal = $this->sale_model->getSumClientWiseTotalSaleAmount($clientById->c_id) + $this->sale_model->getSumClientWiseTotalProductSaleAmount($clientById->c_id);
                                                        if ($saleTotal) {
                                                            echo "$settings->currency " . number_format($saleTotal, 2, ".", ",");
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
                                                <h3> <?php $clientReceivedAmount = $this->payments_model->getSumClientWiseTotalReceivedAndPaymentAmount($clientById->c_id, 'cp_received_amount');
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
                    </div>

                    <table class="table table-hover table-bordered" id="editable-sample">
                        <thead>
                            <tr>
                                <th><?= lang('sale_date'); ?></th>
                                <th><?= lang('sale_type'); ?></th>
                                <th><?= lang('total_receivable_amount'); ?></th>
                                <th><?= lang('received_amount'); ?></th>
                                <th><?= lang('due_amount'); ?></th>
                                <th><?= lang('payment_status'); ?></th>
                                <th><?= lang('payment_count'); ?></th>
                                <th><?= lang('options'); ?></th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $dates1 = array();
                            $dates2 = array();
                            foreach ($saleByClientId as $list) {
                                $dates1[] = $list->lsss_date;
                            }
                            foreach ($saleProductsByClientId as $productValue) {
                                $dates2[] = $productValue->prss_date;
                            }

                            $mergeDate = array_merge($dates1, $dates2);
                            $uniqueDate = array_unique($mergeDate);
                            asort($uniqueDate);

                            ?>


                            <!-- =================== Livestock payment =================== -->
                            <?php
                            foreach ($uniqueDate as $key => $value) {
                                foreach ($saleByClientId as $list) {
                                    if ($list->lsss_date == $value) {
                            ?>
                                        <tr class="bg-warning-light">
                                            <td> <?= date("$settings->date_format", strtotime($list->lsss_date)); ?></td>
                                            <td> <?= lang('livestock'); ?></td>
                                            <td> <?= $settings->currency . number_format_currency($list->lsss_grand_total, 2); ?></td>
                                            <td> <?php $totalReceivedAmount = $this->payments_model->getSumClientAndSaleWiseTotalReceivedAmount($clientById->c_id, $list->lsss_id);
                                                    if ($totalReceivedAmount) {
                                                        echo $settings->currency . number_format_currency($totalReceivedAmount, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></td>
                                            <td>
                                                <?php
                                                $receivableTotalAmount = $list->lsss_grand_total;
                                                $receivedTotalAmount = $this->payments_model->getSumClientAndSaleWiseTotalReceivedAmount($clientById->c_id, $list->lsss_id);
                                                $totalDueAmountLivestock = $receivableTotalAmount - $receivedTotalAmount;
                                                echo $settings->currency . number_format_currency($totalDueAmountLivestock, 2);
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($receivableTotalAmount == $receivedTotalAmount) {
                                                    echo "<span class='badge btn-success'>" . lang('paid') . "</span>";
                                                } elseif ($receivableTotalAmount > $receivedTotalAmount) {
                                                    echo "<span class='badge btn-danger'>" . lang('pending') . "</span>";
                                                } else {
                                                    echo "<span class='badge btn-warning'>" . lang('over_received') . "</span>";
                                                } ?>
                                            </td>
                                            <td>
                                                <?php echo $this->payments_model->getClientSaleWisePaymentCountRow($clientById->c_id, $list->lsss_id); ?>
                                            </td>
                                            <td>
                                                <?php if ($receivableTotalAmount == $totalReceivedAmount) { ?>
                                                    <button type="button" class="button button-primary disabled"><i class="fas fa-plus-circle"></i> <?= lang('add_payment'); ?></button>
                                                <?php } else { ?>
                                                    <button type="button" class="button button-primary addClientPayment" data-toggle="modal" data-id="<?php echo $clientById->c_id; ?>" data-name="<?php echo $clientById->c_name; ?>" data-sale-id="<?php echo $list->lsss_id; ?>" data-total-due-amount-livestock="<?php echo $totalDueAmountLivestock; ?>"><i class="fas fa-plus-circle"></i> <?= lang('add_payment'); ?></button>
                                                <?php } ?>
                                                <a class="" href="<?php echo base_url('') ?>payments/viewClientSaleWisePayments?cp_lsss_id=<?php echo $list->lsss_id; ?>"><button type="button" class="button button-success"><i class="fas fa-eye"></i> <?= lang('view_payments'); ?></button></i></a>
                                                <a href="<?php echo base_url('') ?>sale/viewLivestockSale?lsss_id=<?php echo $list->lsss_id; ?>"><button type="button" class="button button-info"><i class="fas fa-file-invoice"></i> <?= lang('invoice'); ?></button></a>
                                            </td>
                                        </tr>
                                <?php }
                                } ?>



                                <!-- =================== Product payment =================== -->
                                <?php
                                foreach ($saleProductsByClientId as $productValue) {
                                    if ($productValue->prss_date == $value) {
                                ?>
                                        <tr class="bg-primary-light">
                                            <td> <?= date("$settings->date_format", strtotime($productValue->prss_date)); ?></td>
                                            <td> <?= lang('product'); ?></td>
                                            <td> <?= $settings->currency . number_format_currency($productValue->prss_grand_total, 2); ?></td>
                                            <td> <?php $totalReceivedAmountProduct = $this->payments_model->getSumClientAndProductSaleWiseTotalReceivedAmount($clientById->c_id, $productValue->prss_id);
                                                    if ($totalReceivedAmountProduct) {
                                                        echo $settings->currency . number_format_currency($totalReceivedAmountProduct, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></td>
                                            <td>
                                                <?php
                                                $receivableTotalAmountProduct = $productValue->prss_grand_total;
                                                $receivedTotalAmountProduct = $this->payments_model->getSumClientAndProductSaleWiseTotalReceivedAmount($clientById->c_id, $productValue->prss_id);
                                                $totalDueAmountProduct = $receivableTotalAmountProduct - $receivedTotalAmountProduct;
                                                echo $settings->currency . number_format_currency($totalDueAmountProduct, 2);
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($receivableTotalAmountProduct == $receivedTotalAmountProduct) {
                                                    echo "<span class='badge btn-success'>" . lang('paid') . "</span>";
                                                } elseif ($receivableTotalAmountProduct > $receivedTotalAmountProduct) {
                                                    echo "<span class='badge btn-danger'>" . lang('pending') . "</span>";
                                                } else {
                                                    echo "<span class='badge btn-warning'>" . lang('over_received') . "</span>";
                                                } ?>
                                            </td>
                                            <td>
                                                <?php echo $this->payments_model->getClientProductSaleWisePaymentCountRow($clientById->c_id, $productValue->prss_id); ?>
                                            </td>
                                            <td>
                                                <?php if ($receivableTotalAmountProduct == $totalReceivedAmountProduct) { ?>
                                                    <button type="button" class="button button-primary disabled"><i class="fas fa-plus-circle"></i> <?= lang('add_payment'); ?></button>
                                                <?php } else { ?>
                                                    <button type="button" class="button button-primary addClientFoodPayment" data-toggle="modal" data-id="<?php echo $clientById->c_id; ?>" data-name="<?php echo $clientById->c_name; ?>" data-product-sale-id="<?php echo $productValue->prss_id ?>" data-total-due-amount-product="<?php echo $totalDueAmountProduct; ?>"><i class="fas fa-plus-circle"></i> <?= lang('add_payment'); ?></button>
                                                <?php } ?>
                                                <a class="" href="<?php echo base_url('') ?>payments/viewClientProductSaleWisePayments?cp_prss_id=<?php echo $productValue->prss_id; ?>"><button type="button" class="button button-success"><i class="fas fa-eye"></i> <?= lang('view_payments'); ?></button></i></a>
                                                <a href="<?php echo base_url('') ?>sale/viewProductSale?prss_id=<?php echo $productValue->prss_id; ?>"><button type="button" class="button button-info"><i class="fas fa-file-invoice"></i> <?= lang('invoice'); ?></button></a>
                                            </td>
                                        </tr>
                            <?php }
                                }
                            } ?>
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


<!-- Livestock Sale Payment -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('add_client_payment'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('payments/insertClientPayment') ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('name'); ?></label>
                            <input type="text" class="form-control" id="c_name" placeholder="" readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('due_amount'); ?></label>
                            <input type="text" class="form-control" id="total_due_amount_livestock" placeholder="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('received'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control input__number" name="cp_received_amount" id="receivableAmount" value='' placeholder="" required>
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
                        <input type="text" class="form-control" name="cp_cheque_no" id="" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('reference'); ?></label>
                        <input type="text" class="form-control" name="cp_reference" id="" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('date'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-inline datepicker" name="cp_date" id="" value='<?php echo get_current_date(); ?>' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="cp_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="cp_c_id" id="c_id" value=''>
                    <input type="hidden" name="cp_lsss_id" id="sale_id" value=''>
                    <section class=" text-right">
                        <button type="submit" name="submit" class="button button-info submit_button" id="submitButton"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Product Sale Payment -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('add_client_payment'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('payments/insertClientPayment') ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('name'); ?></label>
                            <input type="text" class="form-control" id="product_c_name" placeholder="" readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('due_amount'); ?></label>
                            <input type="text" class="form-control" id="total_due_amount_product" placeholder="" readonly>
                        </div>
                    </div>
                    <!-- <div class="row"> -->
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('received'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control input__number" name="cp_received_amount" id="receivableAmountProduct" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"> <?php echo lang('paid_by'); ?><span class="text-danger">*</span></label>
                        <select id="product_paid_by" class="form-control m-bot15" name="cp_paid_by" value='' required>
                            <option value="cash"><?php echo lang('cash'); ?> </option>
                            <option value="cheque"><?php echo lang('cheque'); ?></option>
                            <option value="other"><?php echo lang('other'); ?> </option>
                        </select>
                    </div>
                    <div class="form-group" id="product_cheque_no" style="display: none;">
                        <label for="exampleInputEmail1"> <?php echo lang('cheque_no'); ?></label>
                        <input type="text" class="form-control" name="cp_cheque_no" id="" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('reference'); ?></label>
                        <input type="text" class="form-control" name="cp_reference" id="" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('date'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-inline datepicker" name="cp_date" id="" value='<?php echo get_current_date(); ?>' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="cp_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="cp_c_id" id="product_c_id" value=''>
                    <input type="hidden" name="cp_prss_id" id="product_sale_id" value=''>
                    <section class=" text-right">
                        <button type="submit" name="submit" class="button button-info submit_button" id="submitButtonProduct"><?php echo lang('submit'); ?></button>
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

        $(".addClientPayment").click(function(e) {
            var iid = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var sale_id = $(this).attr('data-sale-id');
            var total_due_amount = $(this).attr('data-total-due-amount-livestock');
            $("#receivableAmount").val('');
            $('#myModal').modal('show');
            $('#c_id').val(iid);
            $('#c_name').val(name);
            $('#sale_id').val(sale_id);
            $('#total_due_amount_livestock').val("<?php echo $settings->currency; ?>" + total_due_amount);
            $("#receivableAmount").on('keyup change paste', function(e) {
                var payableAmount = $(this).val();
                if (parseFloat(total_due_amount) < parseFloat(payableAmount)) {
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('please_enter_a_valid_value'); ?>"
                    });
                    $('#submitButton').attr('disabled', 'disabled');
                    $("#receivableAmount").val('');
                } else {
                    $('#submitButton').removeAttr('disabled');
                }
            });
        });


        // For Products Payments
        $('#product_paid_by').on('change', function() {
            if ($(this).val() === "cheque") {
                $("#product_cheque_no").delay(500).fadeIn(100);
            } else {
                $("#product_cheque_no").hide()
            }
        });

        $(".addClientFoodPayment").click(function(e) {
            var iid = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var sale_id = $(this).attr('data-product-sale-id');
            var total_due_amount = $(this).attr('data-total-due-amount-product');
            $("#receivableAmountProduct").val('');
            $('#myModal2').modal('show');
            $('#product_c_id').val(iid);
            $('#product_c_name').val(name);
            $('#product_sale_id').val(sale_id);
            $('#total_due_amount_product').val("<?php echo $settings->currency; ?>" + total_due_amount);
            $("#receivableAmountProduct").on('keyup change paste', function(e) {
                var payableAmount = $(this).val();
                if (parseFloat(total_due_amount) < parseFloat(payableAmount)) {
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('please_enter_a_valid_value'); ?>"
                    });
                    $('#submitButtonProduct').attr('disabled', 'disabled');
                    $("#receivableAmountProduct").val('');
                } else {
                    $('#submitButtonProduct').removeAttr('disabled');
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