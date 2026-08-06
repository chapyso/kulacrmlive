<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('list_client_payment'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table ">
                        <div class="clearfix">
                            <a href="<?php echo base_url('client/listClient'); ?>">
                                <div class="btn-group">
                                    <button class="button button-primary">
                                        <i class="fa-solid fa-circle-arrow-up"></i> <?= lang('client'); ?> <?= lang('list'); ?>
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
                                            <p><?= lang('total_receivable_amount'); ?></p>
                                            <h3><?php $totalSaleAmount = $this->sale_model->getTotalLivestockSaleAmount() + $this->sale_model->getTotalProductSaleAmount();
                                                if ($totalSaleAmount) {
                                                    echo $settings->currency  . number_format($totalSaleAmount, 2, ".", ",");
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
                                            <p><?= lang('total_received_amount'); ?> </p>
                                            <h3><?php $totalClientPaidAmount = $this->payments_model->getSumClientTotalPaidAmount();
                                                if ($totalClientPaidAmount) {
                                                    echo $settings->currency . number_format($totalClientPaidAmount, 2, ".", ",");
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
                                                $totalDueAmount = $totalSaleAmount - $totalClientPaidAmount;
                                                if ($totalDueAmount) {
                                                    echo $settings->currency . number_format($totalDueAmount, 2, ".", ",");
                                                } else {
                                                    echo 0;
                                                }
                                                ?>
                                            </h3>
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
                                    <th><?= lang('serialNo'); ?></th>
                                    <th><?= lang('client'); ?></th>
                                    <th><?= lang('total_receivable_amount'); ?></th>
                                    <th><?= lang('received'); ?></th>
                                    <th><?= lang('due'); ?></th>
                                    <th><?= lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $serial = 0;
                                foreach ($clients->result() as $list) {
                                    $serial++;
                                ?>
                                    <tr class="">
                                        <td> <?= $serial ?></td>
                                        <td> <?= html_escape($list->c_name); ?></td>
                                        <!-- Receivable -->
                                        <td> <?php $saleTotal = $this->sale_model->getSumClientWiseTotalSaleAmount($list->c_id) + $this->sale_model->getSumClientWiseTotalProductSaleAmount($list->c_id);
                                                if ($saleTotal) {
                                                    echo $settings->currency . number_format_currency($saleTotal, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?>
                                        </td>
                                        <td>
                                            <!-- paid -->
                                            <?php $clientPaidAmount = $this->payments_model->getSumClientWiseTotalReceivedAndPaymentAmount($list->c_id, 'cp_received_amount');
                                            if ($clientPaidAmount) {
                                                echo $settings->currency . number_format_currency($clientPaidAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php $saleTotalDue = $this->sale_model->getSumClientWiseTotalSaleAmount($list->c_id) + $this->sale_model->getSumClientWiseTotalProductSaleAmount($list->c_id);
                                            $clientPaidAmountDue = $this->payments_model->getSumClientWiseTotalReceivedAndPaymentAmount($list->c_id, 'cp_received_amount');
                                            echo $settings->currency . number_format_currency($saleTotalDue - $clientPaidAmountDue, 2);
                                            ?>
                                        </td>
                                        <td>
                                            <a class="" href="<?php echo base_url('') ?>payments/viewClientWisePayment?c_id=<?php echo $list->c_id; ?>"><button type="button" class="button button-info"><i class="fas fa-eye"></i> <?= lang('view_report'); ?></button></i></a>
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