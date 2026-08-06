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
                            <h2 class="name"><?= $this->supplier_model->getData('supplier', 's_id', $livestock_purchase_by_id->purs_supp_id)->row()->s_name; ?> </h2>
                            </p>
                            <p class="address"><?= $this->supplier_model->getData('supplier', 's_id', $livestock_purchase_by_id->purs_supp_id)->row()->s_address; ?></p>
                            <p class="address"><?= $this->supplier_model->getData('supplier', 's_id', $livestock_purchase_by_id->purs_supp_id)->row()->s_phone; ?> </p>
                            <p class="email"><?= $this->supplier_model->getData('supplier', 's_id', $livestock_purchase_by_id->purs_supp_id)->row()->s_email;  ?></p>
                        </div>

                        <div class="invoice">
                            <h1>#<?= lang('invoice_capital'); ?></h1>
                            <div class="date"><?= lang('date_of_invoice'); ?>: <?= date("$settings->date_format", strtotime($livestock_purchase_by_id->purs_date));  ?></div>
                            <div class="date"><?= lang('invoice_type'); ?>: <?= lang('livestock'); ?> <?= lang('purchase'); ?></div>
                            <div class="date">
                                <!-- Payment Status -->
                                <span><strong><?php echo lang('payment_status'); ?>:</strong></span>
                                <?php
                                $PayableTotalAmount = $livestock_purchase_by_id->purs_grand_total;
                                $paidTotalAmount = $this->payments_model->getSumSupplierAndPurchaseWiseTotalPaidAmount($livestock_purchase_by_id->purs_supp_id, $livestock_purchase_by_id->purs_id);

                                if ($PayableTotalAmount == $paidTotalAmount) {
                                    echo "<span class='badge btn-success'>" . lang('paid') . "</span>";
                                } elseif ($PayableTotalAmount > $paidTotalAmount) {
                                    echo "<span class='badge btn-danger'>" . lang('pending') . "</span>";
                                } elseif ($PayableTotalAmount < $paidTotalAmount) {
                                    echo "<span class='badge btn-warning'>" . lang('over_payment') . "</span>";
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
                                            <th><?php echo lang('livestock_name'); ?></th>
                                            <th><?php echo lang('livestock_variant'); ?></th>
                                            <th class="text-right"><?php echo lang('unit_price'); ?> </th>
                                            <th class="text-right"><?php echo lang('quantity'); ?> </th>
                                            <th class="text-right"><?php echo lang('discount'); ?> </th>
                                            <th class="text-right"><?php echo lang('total'); ?> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $serial = 0;
                                        foreach ($LivestockSummaryId as $summary_id) {
                                            $serial++;
                                        ?>
                                            <tr>
                                                <td><?= $serial ?></td>
                                                <td><?= $this->livestock_model->getLivestockById($summary_id->purv_ls_id)->ls_name; ?></td>
                                                <td><?= $this->livestock_model->getLivestockTypeById($summary_id->purv_lst_id)->lst_title; ?></td>
                                                <td class="text-right"><?= $settings->currency; ?><?= number_format($summary_id->purv_unit_price, 2, '.', ','); ?></td>
                                                <td class="text-right"><?= $summary_id->purv_quantity; ?></td>
                                                <td class="text-right"><?= $settings->currency; ?><?= number_format($summary_id->purv_discount, 2, '.', ','); ?></td>
                                                <td class="text-right"><?= $settings->currency; ?><?= number_format($summary_id->purv_total, 2, '.', ','); ?></td>
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
                                <a class="button button-primary btn-lg invoice_button" href="<?php echo base_url('purchase/downloadPurchasePdf?purs_id=' . $livestock_purchase_by_id->purs_id); ?>"><i class="fa-solid fa-file-pdf"></i> <?php echo lang('pdf'); ?></a>
                            </div>
                        </div>
                        <div class="col-xs-5">
                            <table class="table text-right">
                                <tr>
                                    <td>
                                        <p> <strong><?php echo lang('sub_total'); ?> :</strong></p>
                                    </td>
                                    <td>
                                        <p><?= $settings->currency; ?><?= number_format($livestock_purchase_by_id->purs_sub_total, 2, '.', ','); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p> <strong><?php echo lang('purs_grand_total'); ?> :</strong></p>
                                    </td>
                                    <td>
                                        <p><?= $settings->currency; ?><?= number_format($livestock_purchase_by_id->purs_grand_total, 2, '.', ','); ?></p>
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