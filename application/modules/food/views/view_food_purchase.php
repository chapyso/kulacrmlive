<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="col-md-6">
            <!-- page start-->
            <section class="panel col-md-6 invoicePaperSize">
                <div class="panel-body" id="createPdfPrintBody">

                    <header class="clearfix_invoice">
                        <div class="logo">
                            <img class="img-circle" style="height: 70px; width: 70px;" src="<?php echo $settings->img_url; ?>">
                        </div>
                        <div class="company">
                            <p>
                            <h2 class="name"><?php echo $settings->system_vendor; ?></h2>
                            </p>
                            <p><strong><?= $settings->title ?></strong></p>
                            <p><?= $settings->address ?></p>
                            <p><?= $settings->phone ?></p>
                            <p><a href="mailto:<?= $settings->email ?>"><?= $settings->email ?></a></p>
                        </div>
                    </header>
                    <hr>
                    <div class="details" class="clearfix_invoice">
                        <div class="client">
                            <div class="to"><strong><?= lang('invoice_capital'); ?> <?= lang('to'); ?> :</strong></div>
                            <p>
                            <h2 class="name"> <?= $settings->title; ?> </h2>
                            </p>
                            <p class="address"><?= $settings->address; ?></p>
                            <p class="address"> <?= $settings->phone; ?> </p>
                            <p class="email"><?= $settings->email ?></a></p>
                        </div>
                        <div class="supplier">
                            <div class="to"><strong><?php echo lang('payment_to'); ?> :</strong></div>
                            <p>
                            <h2 class="name"><?= $this->supplier_model->getData('supplier', 's_id',  $foodPurchaseById->fdps_s_id)->row()->s_name; ?> </h2>
                            </p>
                            <p class="address"><?= $this->supplier_model->getData('supplier', 's_id',  $foodPurchaseById->fdps_s_id)->row()->s_address; ?></p>
                            <p class="address"><?= $this->supplier_model->getData('supplier', 's_id',  $foodPurchaseById->fdps_s_id)->row()->s_phone; ?> </p>
                            <p class="email"><?= $this->supplier_model->getData('supplier', 's_id',  $foodPurchaseById->fdps_s_id)->row()->s_email;  ?></p>
                        </div>

                        <div class="invoice">
                            <h1>#<?= lang('invoice_capital'); ?></h1>
                            <div class="date"><?= lang('date_of_invoice'); ?>: <?= date("$settings->date_format", strtotime($foodPurchaseById->fdps_date));  ?></div>
                            <div class="date"><?= lang('invoice_type'); ?>: <?= lang('food'); ?> <?= lang('purchase'); ?></div>
                            <div class="date">
                                <!-- Payment Status -->
                                <span><strong><?= lang('payment_status'); ?> :</strong></span>
                                <?php

                                $receivableTotalAmount = $foodPurchaseById->fdps_grand_total;
                                $receivedTotalAmount = $this->payments_model->getSumSupplierAndFoodPurchaseWiseTotalPaidAmount($foodPurchaseById->fdps_s_id, $foodPurchaseById->fdps_id);

                                if ($receivableTotalAmount == $receivedTotalAmount) {
                                    echo "<span class='badge btn-success'>" . lang('paid') . "</span>";
                                } elseif ($receivableTotalAmount > $receivedTotalAmount) {
                                    echo "<span class='badge btn-danger'>" . lang('pending') . "</span>";
                                } ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="adv-table editable-table ">
                                <div class="space15">
                                </div>
                                <table class="table table-striped table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th><?php echo lang('serialNo'); ?></th>
                                            <th><?= lang('food_name'); ?> </th>
                                            <th class="text-right"><?= lang('price'); ?> </th>
                                            <th class="text-right"><?= lang('quantity'); ?> </th>
                                            <th class="text-right"><?= lang('discount'); ?> </th>
                                            <th class="text-right"><?= lang('total'); ?> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $serial = 0;
                                        foreach ($foodPurchaseValueBySummaryById as $summary_id) {
                                            $serial++;
                                        ?>
                                            <tr class="">
                                                <td><?= $serial ?></td>
                                                <td><?php $foodInfo = $this->food_model->getFoodSummaryById($summary_id->fdpv_fd_id);
                                                    echo $foodInfo->fds_food_title;
                                                    ?></td>
                                                <td class="text-right"> <?= $settings->currency . number_format_currency($summary_id->fdpv_unit_price, 2); ?></td>
                                                <td class="text-right"> <?= $summary_id->fdpv_quantity; ?>
                                                    <?php $unit = $this->settings_model->getUnitById($foodInfo->fds_unit_id);
                                                    if ($unit) {
                                                        echo  $unit->un_name;
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-right"> <?= $settings->currency . number_format_currency($summary_id->fdpv_discount, 2); ?></td>
                                                <td class="text-right"> <?= $settings->currency . number_format_currency($summary_id->fdpv_total, 2); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-7">
                            <div class="invoice-btn print-button-position">
                                <a class="button button-info btn-lg invoice_button" onclick="javascript:window.print();"><i class="fas fa-print"></i> <?php echo lang('print'); ?> </a>
                                <button id="exportButton" class="button button-primary"><i class="fa-solid fa-file-pdf"></i> <?php echo lang('pdf'); ?></button>
                            </div>
                        </div>
                        <div class="col-xs-5">
                            <table class="table text-right">
                                <tr>
                                    <td>
                                        <p> <strong><?php echo lang('sub_total'); ?> :</strong></p>
                                    </td>
                                    <td>
                                        <p><?= $settings->currency; ?><?= number_format($foodPurchaseById->fdps_sub_total, 2, '.', ','); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p> <strong><?php echo lang('purs_grand_total'); ?> :</strong></p>
                                    </td>
                                    <td>
                                        <p><?= $settings->currency; ?><?= number_format($foodPurchaseById->fdps_grand_total, 2, '.', ','); ?></p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
</section>
<!--main content end-->
<!--footer start-->