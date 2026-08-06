<!--sidebar end-->
<?php $filter_from = isset($filter_from) ? $filter_from : ''; $filter_to = isset($filter_to) ? $filter_to : ''; ?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        
        <!-- 2026 MODERN PAGE HEADER & ACTIONS BAR -->
        <div class="kula-hero-card" style="margin-bottom: 20px; padding: 20px 28px;">
            <div class="hero-content-left">
                <h1 class="hero-title" style="font-size: 22px;">
                    <i class="fa-solid fa-coins" style="color: #059669; margin-right: 8px;"></i>
                    <?= lang('livestock_sale_list'); ?>
                </h1>
                <p class="hero-subtitle">Manage animal sales, client billing invoices, and revenue records</p>
            </div>

            <div class="hero-actions-right" style="gap: 8px; flex-wrap: wrap;">
                <a href="<?php echo base_url('sale/addNewSale'); ?>" class="hero-export-btn" style="background: #047857; text-decoration: none;">
                    <i class="fa-solid fa-plus-circle"></i> <?php echo lang('add_new_sale'); ?>
                </a>

                <?php $csv_qs = http_build_query(array_filter(array('from' => $filter_from, 'to' => $filter_to))); ?>
                <a href="<?php echo base_url('sale/exportCSV') . ($csv_qs ? '?' . $csv_qs : ''); ?>" class="hero-date-pill" style="text-decoration: none; padding: 10px 18px;">
                    <i class="fa-solid fa-file-csv" style="color: #0284c7;"></i>
                    <span>Export CSV</span>
                </a>

                <button class="hero-date-pill" onclick="javascript:window.print();" style="border: 1px solid #e2e8f0; background: #fff;">
                    <i class="fa-solid fa-print" style="color: #475569;"></i>
                    <span><?php echo lang('print'); ?></span>
                </button>
            </div>
        </div>
                            <?php $csv_qs = http_build_query(array_filter(array('from' => $filter_from, 'to' => $filter_to))); ?>
                            <a class="button button-info" href="<?php echo base_url('sale/exportCSV') . ($csv_qs ? '?' . $csv_qs : ''); ?>" style="margin-left:6px;"><i class="fas fa-file-csv"></i> Export CSV</a>
                            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                            <?php $this->load->view('_partials/date_range_filter', array(
                                'action_url' => base_url('sale/listSale'),
                                'clear_url'  => base_url('sale/listSale'),
                                'from'       => $filter_from,
                                'to'         => $filter_to,
                            )); ?>
                        </div>
                        <div class="space15">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="new__cards card__box">
                                        <div class="col-xs-8">
                                            <p><?= lang('total_livestock'); ?> (<?= $settings->unit; ?>)</p>
                                            <h3><?php $totalAssignedQuantity = $this->purchase_model->getTotalLivestockAssignedToShedQuantity();
                                                if ($totalAssignedQuantity) {
                                                    echo $totalAssignedQuantity;
                                                } else {
                                                    echo 0;
                                                }
                                                ?></h3>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="text-right">
                                                <ul class="social__icon">
                                                    <li class="icon">
                                                        <span><i class="fa-solid fa-store"></i></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="new__cards card__box">
                                        <div class="col-xs-8">
                                            <p><?= lang('total_sold_quantity'); ?> (<?= $settings->unit; ?>): <strong><?php $totalSaleQuantity = $this->sale_model->getTotalSaleLivestockQuantity();
                                                                                                                        if ($totalSaleQuantity) {
                                                                                                                            echo $totalSaleQuantity;
                                                                                                                        } else {
                                                                                                                            echo 0;
                                                                                                                        }
                                                                                                                        ?></strong></p>
                                            <p><?= lang('total_death'); ?> (<?= $settings->unit; ?>): <strong><?php $totalDeathQuantity = $this->shed_model->getTotalDeathLivestock();
                                                                                                                if ($totalDeathQuantity) {
                                                                                                                    echo $totalDeathQuantity;
                                                                                                                } else {
                                                                                                                    echo 0;
                                                                                                                }
                                                                                                                ?></strong></p>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="text-right">
                                                <ul class="social__icon">
                                                    <li class="icon">
                                                        <span><i class="fa-solid fa-cart-shopping"></i></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="new__cards card__box">
                                        <div class="col-xs-8">
                                            <p><?= lang('stock_in_shed'); ?> (<?= $settings->unit; ?>)</p>
                                            <h3><?php
                                                $stockQuantity = $totalAssignedQuantity - ($totalSaleQuantity + $totalDeathQuantity);
                                                if ($stockQuantity) {
                                                    echo $stockQuantity;
                                                } else {
                                                    echo 0;
                                                }
                                                ?></h3>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="text-right">
                                                <ul class="social__icon">
                                                    <li class="icon">
                                                        <span><i class="fa-solid fa-store-slash"></i></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="new__cards card__box">
                                        <div class="col-xs-8">
                                            <p><?= lang('total_sold_amount'); ?></p>
                                            <h3><?php $totalSaleAmount = $this->sale_model->getTotalLivestockSaleAmount();
                                                if ($totalSaleAmount) {
                                                    echo $settings->currency . number_format($totalSaleAmount, 2, ".", ",");
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
                                                        <span><i class="fa-solid fa-money-bill"></i></span>
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
                                    <th> <?php echo lang('serialNo'); ?> </th>
                                    <th> <?= lang('sold'); ?> <?= lang('date'); ?> </th>
                                    <th> <?= lang('client'); ?> </th>
                                    <th> <?= lang('total_livestock_sold'); ?> (<?= $settings->unit ?>)</th>
                                    <th> <?= lang('total_sold_amount'); ?> (<?php echo lang('amount'); ?>)</th>
                                    <th class="option_th"><?php echo lang('options'); ?> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serial = 0;
                                foreach ($getLivestockSales as $sales) {
                                    $serial++;
                                ?>
                                    <tr class="">
                                        <td> <?= $serial; ?> </td>
                                        <td> <?= date("$settings->date_format", strtotime($sales->lsss_date)); ?> </td>
                                        <td> <?= html_escape($this->client_model->getClientById($sales->lsss_c_id)->c_name); ?> </td>
                                        <td> <?= $this->sale_model->getTotalLivestockSumBySaleSummaryId($sales->lsss_id) ?> </td>
                                        <td> <?= $settings->currency; ?><?= number_format($sales->lsss_grand_total, 2, '.', ','); ?> </td>
                                        <td class="option" style="width: 30%;">
                                            <?php if ($this->ion_auth->in_group('admin')) { ?>
                                                <a class="button button-info" href="<?php echo base_url('') ?>sale/viewLivestockSale?lsss_id=<?php echo $sales->lsss_id; ?>"><i class="fa-solid fa-file-invoice"></i> <?= lang('invoice_capital'); ?></a>
                                                <a class="button button-warning" href="<?php echo base_url('') ?>sale/editLivestockSale?lsss_id=<?php echo $sales->lsss_id; ?>"><i class="fas fa-edit"></i> <?php echo lang('edit'); ?></a>
                                                <form action="<?php echo base_url('sale/deleteLivestockSale'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>')">
                                                    <input type="hidden" name="lsss_id" value="<?php echo $sales->lsss_id; ?>">
                                                    <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                    <button type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                                </form>
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


<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script>
    $(document).ready(function() {
        $(".success_flash_message").delay(3000).fadeOut(100);
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