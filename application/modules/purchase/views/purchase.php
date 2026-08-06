<!--sidebar end-->
<?php $filter_from = isset($filter_from) ? $filter_from : ''; $filter_to = isset($filter_to) ? $filter_to : ''; ?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        
        <!-- 2026 MODERN PAGE HEADER & ACTIONS BAR -->
        <div class="kula-hero-card" style="margin-bottom: 20px; padding: 20px 28px;">
            <div class="hero-content-left">
                <h1 class="hero-title" style="font-size: 22px;">
                    <i class="fa-solid fa-cart-shopping" style="color: #059669; margin-right: 8px;"></i>
                    <?php echo lang('livestock_purchase_list'); ?>
                </h1>
                <p class="hero-subtitle">Record and manage animal procurement, supplier invoices, and shed assignments</p>
            </div>

            <div class="hero-actions-right" style="gap: 8px; flex-wrap: wrap;">
                <a href="<?php echo base_url('purchase/addNewPurchase'); ?>" class="hero-export-btn" style="background: #047857; text-decoration: none;">
                    <i class="fa-solid fa-plus-circle"></i> <?php echo lang('new_purchase'); ?>
                </a>

                <a href="<?php echo base_url('purchase/livestockAssignToShed'); ?>" class="hero-date-pill" style="text-decoration: none; padding: 10px 18px;">
                    <i class="fa-solid fa-circle-arrow-right" style="color: #0284c7;"></i>
                    <span><?php echo lang('assign_to_shed'); ?></span>
                </a>
            </div>
        </div>
                                <?php $csv_qs = http_build_query(array_filter(array('from' => $filter_from, 'to' => $filter_to))); ?>
                                <a class="button button-info" href="<?php echo base_url('purchase/exportCSV') . ($csv_qs ? '?' . $csv_qs : ''); ?>" style="margin-left:6px;"><i class="fas fa-file-csv"></i> Export CSV</a>


                                <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>

                                <a data-toggle="modal" href="#informationPopup" class="button button-purple export"><i class="fa-solid fa-circle-question"></i> <?= lang('information'); ?></a>
                                <?php $this->load->view('_partials/date_range_filter', array(
                                    'action_url' => base_url('purchase'),
                                    'clear_url'  => base_url('purchase'),
                                    'from'       => $filter_from,
                                    'to'         => $filter_to,
                                )); ?>
                            </div>
                            <div class="space15">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="new__cards card__box">
                                            <div class="col-xs-9">
                                                <?php
                                                $totalPurchaseQuantity = $this->purchase_model->getTotalPurchasedLivestockQuantity();
                                                $livestockReproductionQuantity = $this->report_model->getSumData('live_assigned_shed_summary', 'lshs_assign_total_quantity', ['lshs_type' => 2, 'lshs_status' => 1]);
                                                ?>
                                                <?php if ($livestockReproductionQuantity) { ?>
                                                    <p><?php echo lang('total_purchased'); ?>: <strong><?php echo $totalPurchaseQuantity; ?></strong></p>
                                                    <p><?php echo lang('reproduction'); ?>: <strong><?php echo $livestockReproductionQuantity; ?></strong></p>
                                                <?php } else { ?>
                                                    <p><?php echo lang('total_purchased'); ?> (<?= $settings->unit; ?>)</p>
                                                    <h3><?php echo $totalPurchaseQuantity; ?></h3>
                                                <?php } ?>
                                            </div>
                                            <div class="col-xs-3">
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
                                                <p><?php echo lang('total_assigned_to_shed'); ?> (<?= $settings->unit; ?>)</p>

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
                                                <p><?php echo lang('out_of_shed'); ?> (<?= $settings->unit; ?>)</p>
                                                <h3><?php
                                                    $outOfShed = ($totalPurchaseQuantity + $livestockReproductionQuantity) - $totalAssignedQuantity;
                                                    if ($outOfShed) {
                                                        echo $outOfShed;
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
                                                <p><?php echo lang('total_purchased_amount'); ?></p>

                                                <h3><?php $totalPurAmount = $this->purchase_model->getTotalLivestockPurchasedAmount();
                                                    if ($totalPurAmount) {
                                                        echo "$settings->currency" . number_format($totalPurAmount, 2, ".", ",");
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></h3>

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
                                        <th> <?php echo lang('purchase'); ?> <?php echo lang('bill'); ?> </th>
                                        <th> <?php echo lang('purchase'); ?> <?php echo lang('date'); ?> </th>
                                        <th> <?php echo lang('supplier'); ?></th>
                                        <th> <?php echo lang('total_purchased'); ?> (<?= $settings->unit; ?>)</th>
                                        <th class="option_th"><?php echo lang('options'); ?> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial = 0;
                                    foreach ($livestock_purchases as $livestock_purchase) {
                                        $serial++;
                                    ?>
                                        <tr>
                                            <td> <?= $serial; ?> </td>
                                            <td> <?= $livestock_purchase->purs_bill_no; ?> </td>
                                            <td> <?= date("$settings->date_format", strtotime($livestock_purchase->purs_date)); ?> </td>
                                            <td><?= $this->supplier_model->getData('supplier', 's_id', $livestock_purchase->purs_supp_id)->row()->s_name; ?></td>
                                            <td><?php echo $totalSumByPurchaseSummaryId = $this->purchase_model->getTotalLivestockSumByPurchaseSummaryId($livestock_purchase->purs_id); ?></td>
                                            <td class="option" style="width: 30%;">
                                                <?php if ($this->ion_auth->in_group('admin')) { ?>
                                                    <a class="button button-info" href="<?php echo base_url('') ?>purchase/viewLivestockPurchase?purs_id=<?php echo $livestock_purchase->purs_id; ?>"><i class="fa-solid fa-file-invoice"></i> <?php echo lang('invoice_capital'); ?></a>

                                                    <!-- conditional delete -->
                                                    <?php
                                                    $countDataIfAvailableInAssign = $this->settings_model->getCountRow('live_assigned_shed', 'lsh_id', ['lsh_purs_id' => $livestock_purchase->purs_id, 'lsh_status' => 1]);
                                                    if ($countDataIfAvailableInAssign == 0) { ?>
                                                        <a class="button button-warning" href="<?php echo base_url('') ?>purchase/editLivestockPurchase?purs_id=<?php echo $livestock_purchase->purs_id; ?>"><i class="fas fa-edit"></i> <?php echo lang('edit'); ?></a>
                                                        <form action="<?php echo base_url('purchase/deleteLivestockPurchase'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item') ?>');">
                                                            <input type="hidden" name="purs_id" value="<?php echo $livestock_purchase->purs_id; ?>">
                                                            <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                            <button type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                                        </form>
                                                    <?php } ?>
                                                    <!-- /.conditional delete -->

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
        </div>
    </section>
</section>
<!--main content end-->
<!--footer start-->




<!-- Information Popup-->
<div class="modal fade" id="informationPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-center">
        <div class="modal-content">
            <div class="modal-header bg-purple">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa-solid fa-circle-info"></i> <?php echo lang('basic_information'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <div class="row">
                    <div class="col-xs-6">
                        <ol class="information__modal__ol">
                            <li>
                                <p><?= lang('livestock_purchase_popup_message_one'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('livestock_purchase_popup_message_two'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('livestock_purchase_popup_message_three'); ?></p>
                            </li>
                        </ol>
                    </div>
                    <div class="col-xs-6">
                        <img class="img-square information__modal__image" src="<?php echo base_url('uploads/information/livestock_purchase.jpg'); ?>" alt="No img">
                    </div>
                </div>
                <section class="text-right">
                    <button type="button" class="button button-purple" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> <?= lang('close'); ?></button>
                </section>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.Information Popup-->
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