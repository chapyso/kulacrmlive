<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('supplier_payment_list'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table">
                        <div class="clearfix">
                            <a href="<?php echo base_url('supplier/listSupplier'); ?>">
                                <div class="btn-group">
                                    <button class="button button-primary">
                                        <i class="fa-solid fa-circle-arrow-up"></i> <?= lang('supplier'); ?> <?= lang('list'); ?>
                                    </button>
                                </div>
                            </a>
                            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                        </div>
                        <div class="space15">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="new__cards card__box">
                                        <div class="col-xs-8">
                                            <p><?= lang('total_payable_amount'); ?></p>
                                            <h3><?php $totalPurAmount = $this->purchase_model->getTotalLivestockPurchasedAmount() + $this->food_model->getTotalFoodPurchaseWeight('fdpv_total') + $this->vaccine_model->getTotalVaccinePurchaseQuantity('vpv_total');
                                                if ($totalPurAmount) {
                                                    echo $settings->currency . number_format($totalPurAmount, 2, ".", ",");
                                                } else {
                                                    echo 0;
                                                }
                                                ?></h3>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="text-right">
                                                <ul class="social__icon">
                                                    <li class="icon">
                                                        <i class="fa-solid fa-money-bill"></i>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="new__cards card__box">
                                        <div class="col-xs-8">
                                            <p><?= lang('total_paid_amount'); ?> </p>
                                            <h3><?php $totalSupplierPaidAmount = $this->payments_model->getSumSupplierTotalPaidAmount();
                                                if ($totalSupplierPaidAmount) {
                                                    echo $settings->currency . number_format($totalSupplierPaidAmount, 2, ".", ",");
                                                } else {
                                                    echo 0;
                                                }
                                                ?></h3>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="text-right">
                                                <ul class="social__icon">
                                                    <li class="icon">
                                                        <i class="fa-solid fa-money-bill-1"></i>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="new__cards card__box">
                                        <div class="col-xs-8">
                                            <p><?= lang('total_due_amount'); ?></p>
                                            <h3><?php
                                                $totalDueAmount = $totalPurAmount - $totalSupplierPaidAmount;
                                                if ($totalDueAmount) {
                                                    echo $settings->currency . number_format($totalDueAmount, 2, ".", ",");
                                                } else {
                                                    echo 0;
                                                }
                                                ?></h3>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="text-right">
                                                <ul class="social__icon">
                                                    <li class="icon">
                                                        <i class="fa-solid fa-money-bill-1-wave"></i>
                                                    </li>
                                                </ul>
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
                                    <th><?= lang('supplier'); ?> </th>
                                    <th><?= lang('total_payable_amount'); ?></th>
                                    <th><?= lang('paid'); ?></th>
                                    <th><?= lang('due'); ?></th>
                                    <th><?php echo lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $serial = 0;
                                foreach ($suppliers->result() as $list) {
                                    $serial++;
                                ?>
                                    <tr class="">
                                        <td> <?= $serial ?></td>
                                        <td> <?= $list->s_name; ?></td>
                                        <td> <?php
                                                $livePurchase = $this->purchase_model->getSumSupplierWiseTotalPurchaseAmount($list->s_id);
                                                $foodPurchase = $this->food_model->getSumFoodSupplierWiseTotalPurchaseAmount($list->s_id);
                                                $vaccinePurchase = $this->vaccine_model->getSumVaccineSupplierWiseTotalPurchaseAmount($list->s_id);
                                                $purchaseTotal = $livePurchase + $foodPurchase + $vaccinePurchase;
                                                if ($purchaseTotal) {
                                                    echo $settings->currency . number_format_currency($purchaseTotal, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?>
                                        </td>
                                        <td>
                                            <?php $supplierPaidAmount = $this->payments_model->getSumSupplierWiseTotalPaidAmount($list->s_id);
                                            if ($supplierPaidAmount) {
                                                echo $settings->currency . number_format_currency($supplierPaidAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php $livePurchaseAmount = $this->purchase_model->getSumSupplierWiseTotalPurchaseAmount($list->s_id);
                                            $foodPurchaseAmount = $this->food_model->getSumFoodSupplierWiseTotalPurchaseAmount($list->s_id);
                                            $vaccinePurchaseAmount = $this->vaccine_model->getSumVaccineSupplierWiseTotalPurchaseAmount($list->s_id);
                                            $supplierPaidAmountDue = $this->payments_model->getSumSupplierWiseTotalPaidAmount($list->s_id);

                                            $totalDueAmountSupplierWise = (($livePurchaseAmount + $foodPurchaseAmount + $vaccinePurchaseAmount) - $supplierPaidAmountDue);
                                            if ($totalDueAmountSupplierWise) {
                                                echo $settings->currency . number_format_currency($totalDueAmountSupplierWise, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a class="" href="<?php echo base_url('') ?>payments/viewSupplierWisePayment?id=<?php echo $list->s_id; ?>"><button type="button" class="button button-info"><i class="fas fa-eye"></i> <?= lang('payment_report'); ?></button></i></a>
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
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
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