<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel col-md-6 invoicePaperSize">
            <div class="panel-body" id="createPdfPrintBody">


                <?php $productSoldDiscountAmount = $this->session->userdata('productSoldDiscountAmount'); ?>

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
                <!-- Invoice to -->
                <div class="details" class="clearfix_invoice">
                    <div class="client">
                        <div class="to"><?= lang('invoice_capital'); ?> <?= lang('to'); ?>:</div>
                        <p>
                        <h2 class="name"><?= html_escape($this->client_model->getClientById($productSaleById->prss_c_id)->c_name); ?></h2>
                        </p>
                        <p class="address"><?= html_escape($this->client_model->getClientById($productSaleById->prss_c_id)->c_address);  ?></p>
                        <p class="address"><?= html_escape($this->client_model->getClientById($productSaleById->prss_c_id)->c_phone);  ?></p>
                        <p class="email"><a href="mailto:<?= html_escape($this->client_model->getClientById($productSaleById->prss_c_id)->c_email);  ?>"><?= html_escape($this->client_model->getClientById($productSaleById->prss_c_id)->c_email);  ?></a></p>
                    </div>
                    <div class="invoice">
                        <h1>#<?= lang('invoice_capital'); ?></h1>
                        <div class="date"><?= lang('date_of_invoice'); ?>: <?= date("$settings->date_format", strtotime($productSaleById->prss_date));  ?></div>
                        <div class="date"><?= lang('invoice_type'); ?>: <?= lang('product_sale'); ?></div>
                        <div class="date">
                            <!-- Payment Status -->
                            <span><strong><?= lang('payment_status'); ?>:</strong></span>
                            <?php
                            $receivableTotalAmount = $productSaleById->prss_grand_total;
                            $receivedTotalAmount = $this->payments_model->getSumClientAndSaleWiseTotalReceivedAmount($productSaleById->prss_c_id, $productSaleById->prss_id);

                            if ($receivableTotalAmount == $receivedTotalAmount) {
                                echo "<span class='badge btn-success'>" . lang('paid') . "</span>";
                            } elseif ($receivableTotalAmount > $receivedTotalAmount) {
                                echo "<span class='badge btn-danger'>" . lang('pending') . "</span>";
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div>
                            <h4></h4>
                        </div>
                        <div class="adv-table editable-table ">
                            <div class="space15">
                            </div>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><?= lang('serialNo'); ?></th>
                                        <th><?= lang('product_name'); ?></th>
                                        <th class="text-right"><?= lang('unit_price'); ?> </th>
                                        <th class="text-right"><?= lang('quantity'); ?> </th>
                                        <?php if ($productSoldDiscountAmount) { ?>
                                            <th class="text-right"><?= lang('discount'); ?> </th>
                                        <?php } ?>
                                        <th class="text-right"><?= lang('total'); ?> </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $serial = 0;
                                    foreach ($productsSaleSummaryId as $summary_id) {
                                        $serial++;
                                    ?>
                                        <tr>
                                            <td><?= $serial ?></td>
                                            <td><?= $this->product_model->getProductById($summary_id->prsv_product_id)->pr_name; ?>
                                                <!-- For Client invoice average calculation -->
                                                <?php
                                                $totalAmount = $this->sale_model->getProductWiseTotalValue($summary_id->prsv_product_id, $summary_id->prsv_prss_id,  'prsv_total');
                                                $totalQuantity = $this->sale_model->getProductWiseTotalValue($summary_id->prsv_product_id, $summary_id->prsv_prss_id,  'prsv_quantity');
                                                $totalDiscount = $this->sale_model->getProductWiseTotalValue($summary_id->prsv_product_id, $summary_id->prsv_prss_id,  'prsv_discount');
                                                $unitPrice = ($totalAmount + $totalDiscount) / $totalQuantity;
                                                ?>
                                            </td>
                                            <td class="text-right"><?= $settings->currency; ?> <?= number_format($unitPrice, 2, '.', ','); ?></td>
                                            <td class="text-right"> <?= $totalQuantity; ?>
                                                <?php $unitId = $this->product_model->getProductById($summary_id->prsv_product_id)->pr_unit_id;
                                                $unitData =  $this->settings_model->getUnitById($unitId);
                                                if ($unitData) {
                                                    echo $unitData->un_name;
                                                }
                                                ?>
                                            </td>
                                            <?php $productSoldDiscountAmount += $totalDiscount;
                                            $this->session->set_userdata('productSoldDiscountAmount', $productSoldDiscountAmount); ?>

                                            <?php if ($productSoldDiscountAmount) { ?>
                                                <td class="text-right"><?= $settings->currency; ?><?= number_format($totalDiscount, 2, '.', ','); ?></td>
                                            <?php } ?>
                                            <td class="text-right"><?= $settings->currency; ?><?= number_format($totalAmount, 2, '.', ','); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-7">
                        <div class="invoice-btn print-button-position ">
                            <a class="button button-info btn-lg invoice_button" onclick="javascript:window.print();"><i class="fa fa-print"></i> <?php echo lang('print'); ?> </a>
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
                                    <p><?= $settings->currency; ?><?= number_format($productSaleById->prss_sub_total, 2, '.', ','); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p> <strong><?php echo lang('purs_grand_total'); ?> :</strong></p>
                                </td>
                                <td>
                                    <p><?= $settings->currency; ?><?= number_format($productSaleById->prss_grand_total, 2, '.', ','); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
        </section>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<!--footer start-->