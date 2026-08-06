<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-md-6">
                <!-- page start-->
                <section class="panel col-md-6 invoicePaperSize">
                    <div class="panel-body" id="createPdfPrintBody">

                        <?php $livestockSoldDiscountAmount = $this->session->userdata('livestockSoldDiscountAmount'); ?>

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
                                <div class="to"><?= lang('invoice_capital'); ?> <?= lang('to'); ?>:</div>
                                <p>
                                <h2 class="name"><?= html_escape($this->client_model->getClientById($livestock_sale_by_id->lsss_c_id)->c_name); ?></h2>
                                </p>
                                <p class="address"><?= html_escape($this->client_model->getClientById($livestock_sale_by_id->lsss_c_id)->c_address);  ?></p>
                                <p class="address"><?= html_escape($this->client_model->getClientById($livestock_sale_by_id->lsss_c_id)->c_phone);  ?></p>
                                <p class="email"><?= html_escape($this->client_model->getClientById($livestock_sale_by_id->lsss_c_id)->c_email);  ?></p>
                            </div>
                            <div class="invoice">
                                <h1>#<?= lang('invoice_capital'); ?></h1>
                                <div class="date"><?= lang('date_of_invoice'); ?>: <?= date("$settings->date_format", strtotime($livestock_sale_by_id->lsss_date));  ?></div>
                                <div class="date"><?= lang('invoice_type'); ?>: <?= lang('livestock_sale'); ?></div>
                                <div class="date">
                                    <!-- Payment Status -->
                                    <span><strong><?= lang('payment_status'); ?>:</strong></span>
                                    <?php
                                    $receivableTotalAmount = $livestock_sale_by_id->lsss_grand_total;
                                    $receivedTotalAmount = $this->payments_model->getSumClientAndSaleWiseTotalReceivedAmount($livestock_sale_by_id->lsss_c_id, $livestock_sale_by_id->lsss_id);

                                    if ($receivableTotalAmount == $receivedTotalAmount) {
                                        echo "<span class='badge btn-success'>" . lang('paid') . "</span>";
                                    } elseif ($receivableTotalAmount > $receivedTotalAmount) {
                                        echo "<span class='badge btn-danger'>" . lang('pending') . "</span>";
                                    } elseif ($receivableTotalAmount < $receivedTotalAmount) {
                                        echo "<span class='badge btn-warning'>" . lang('over_received') . "</span>";
                                    } ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="adv-table editable-table">
                                    <div class="space15">
                                    </div>
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th> <?php echo lang('serialNo'); ?></th>
                                                <th> <?php echo lang('livestock_name'); ?></th>
                                                <th> <?php echo lang('livestock_variant'); ?></th>
                                                <th class="text-right"><?php echo lang('unit_price'); ?> </th>
                                                <th class="text-right"><?php echo lang('quantity'); ?> </th>
                                                <?php if ($livestockSoldDiscountAmount) { ?>
                                                    <th class="text-right"><?php echo lang('discount'); ?> </th>
                                                <?php } ?>
                                                <th class="text-right"><?php echo lang('total'); ?> </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $serial = 0;
                                            foreach ($LivestockSaleSummaryId as $summary_id) {
                                                $serial++;
                                            ?>
                                                <tr>
                                                    <td><?= $serial ?></td>
                                                    <td><?= $this->livestock_model->getLivestockById($summary_id->lssv_ls_id)->ls_name; ?></td>
                                                    <td><?= $this->livestock_model->getLivestockTypeById($summary_id->lssv_lst_id)->lst_title; ?></td>
                                                    <td class="text-right"><?= $settings->currency; ?><?= number_format($summary_id->lssv_unit_price, 2, '.', ','); ?></td>
                                                    <td class="text-right"><?= $summary_id->lssv_quantity; ?></td>

                                                    <?php $livestockSoldDiscountAmount += $summary_id->lssv_discount;
                                                    $this->session->set_userdata('livestockSoldDiscountAmount', $livestockSoldDiscountAmount); ?>

                                                    <?php if ($livestockSoldDiscountAmount) { ?>
                                                        <td class="text-right">
                                                            <?php echo $settings->currency . number_format($summary_id->lssv_discount, 2, '.', ','); ?>
                                                        </td>
                                                    <?php } ?>
                                                    <td class="text-right"><?= $settings->currency; ?><?= number_format($summary_id->lssv_total, 2, '.', ','); ?></td>
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
                                    <a class="button button-primary btn-lg invoice_button" href="<?php echo base_url('sale/downloadLivestockSalePdf?lsss_id=' . $livestockSaleById->lsss_id); ?>"><i class="fa-solid fa-file-pdf"></i> <?php echo lang('pdf'); ?></a>
                                </div>
                            </div>
                            <div class="col-xs-5">
                                <table class="table text-right">
                                    <tr>
                                        <td>
                                            <p> <strong><?php echo lang('sub_total'); ?> :</strong></p>
                                        </td>
                                        <td>
                                            <p><?= $settings->currency; ?><?= number_format($livestock_sale_by_id->lsss_sub_total, 2, '.', ','); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p> <strong><?php echo lang('purs_grand_total'); ?> :</strong></p>
                                        </td>
                                        <td>
                                            <p><?= $settings->currency; ?><?= number_format($livestock_sale_by_id->lsss_grand_total, 2, '.', ','); ?></p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- page end-->
            </div>
        </div>

    </section>
</section>
<!--main content end-->
<!--footer start-->