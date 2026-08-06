<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel">
            <header class="panel-heading bg-info">
                <i class="fa-solid fa-chart-pie"></i> <?= lang('purchase_reports'); ?>
                <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
            </header>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <!-- Today Report -->
                    <div class="row">
                        <!-- ====================== Today Report ====================== -->
                        <div class="col-md-6">
                            <div class="today_report">
                                <h3><?= lang('today_reports'); ?></h3>
                            </div>
                            <table class="table table-striped table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('serialNo'); ?></th>
                                        <th><?php echo lang('livestock_id'); ?></th>
                                        <th><?php echo lang('total_purchased'); ?> (<?= $settings->unit ?>)</th>
                                        <th><?= lang('amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial = 0;
                                    foreach ($livestocks as $livestock) {
                                        $serial++;
                                    ?>
                                        <tr class="">
                                            <td> <?= $serial; ?></td>
                                            <td> <?php echo $livestock->ls_name; ?></td>
                                            <td><?php $todayLivestockPurchasedQuantity = $this->report_model->getTodayLivestockPurchaseQuantityByLivestockId(date('Y-m-d'), $livestock->ls_id, 'purv_quantity');
                                                if ($todayLivestockPurchasedQuantity) {
                                                    echo  $todayLivestockPurchasedQuantity;
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td><?php $todayLivestockPurchasedAmount = $this->report_model->getTodayLivestockPurchaseQuantityByLivestockId(date('Y-m-d'), $livestock->ls_id, 'purv_total');
                                                if ($todayLivestockPurchasedAmount) {
                                                    echo $settings->currency . number_format_currency($todayLivestockPurchasedAmount, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2">
                                            <div class="text-center">
                                                <p><?= lang('purchase_quantity'); ?></p>
                                                <p> <?php $SoldSubTotalQuantity = $this->report_model->getTodayPurchaseSubTotal(date('Y-m-d'), 'purv_quantity');
                                                    if ($SoldSubTotalQuantity) {
                                                        echo $SoldSubTotalQuantity;
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></p>
                                                <p><?= lang('purchase_bill'); ?></p>
                                                <p><?php $totalPurchaseBill = $this->report_model->getTodayPurchaseBill(date('Y-m-d'), 'purs_id');
                                                    if ($totalPurchaseBill) {
                                                        echo $totalPurchaseBill;
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></p>
                                            </div>
                                        </td>
                                        <td>
                                            <p><?= lang('purs_grand_total'); ?>:</p>
                                        </td>
                                        <td>
                                            <p><?php $SoldAmountToday = $this->report_model->getTodayPurchase(date('Y-m-d'), 'purs_grand_total');
                                                if ($SoldAmountToday) {
                                                    echo $settings->currency . number_format_currency($SoldAmountToday, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- ====================== Total Report ====================== -->
                        <div class="col-md-6">
                            <div class="today_report">
                                <h3><?= lang('total_reports'); ?></h3>
                            </div>
                            <table class="table table-striped table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th><?= lang('serialNo'); ?></th>
                                        <th><?= lang('livestock_id'); ?></th>
                                        <th><?= lang('total_purchased'); ?> (<?= $settings->unit ?>)</th>
                                        <th><?= lang('amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial1 = 0;
                                    foreach ($livestocks as $livestock1) {
                                        $serial1++;
                                    ?>
                                        <tr>
                                            <td> <?= $serial1; ?></td>
                                            <td> <?php echo $livestock1->ls_name; ?></td>
                                            <td><?php $totalLivestockPurchasedQuantity = $this->livestock_model->getLivestockPurchaseQuantityByLivestockId($livestock1->ls_id, 'purv_quantity');
                                                if ($totalLivestockPurchasedQuantity) {
                                                    echo  $totalLivestockPurchasedQuantity;
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td><?php $totalLivestockPurchasedAmount = $this->livestock_model->getLivestockPurchaseQuantityByLivestockId($livestock1->ls_id, 'purv_total');
                                                if ($totalLivestockPurchasedAmount) {
                                                    echo $settings->currency . ' ' . number_format_currency($totalLivestockPurchasedAmount, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2">
                                            <div class="text-center">
                                                <p><?= lang('purchase_quantity'); ?></p>
                                                <p><?php $totalPurchaseBill = $this->report_model->getTotalPurchaseSubTotal('purv_quantity');
                                                    if ($totalPurchaseBill) {
                                                        echo $totalPurchaseBill;
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></p>
                                                <p><?= lang('purchase_bill'); ?></p>
                                                <p><?php $totalPurchaseBill = $this->report_model->getTotalPurchaseBill('purs_id');
                                                    if ($totalPurchaseBill) {
                                                        echo $totalPurchaseBill;
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></p>
                                            </div>
                                        </td>
                                        <td>
                                            <p><?= lang('purs_grand_total'); ?>:</p>
                                        </td>
                                        <td>
                                            <p><?php $SoldAmountTotal = $this->report_model->getTotalPurchase('purs_grand_total');
                                                if ($SoldAmountTotal) {
                                                    echo $settings->currency . ' ' . number_format_currency($SoldAmountTotal, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- ====================== Month Wise Filtering ====================== -->
                            <div class="row filtering_heading_text">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <h4><?= lang('month_wise_filtering'); ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="row purchase_report_filter">
                                <form role="form" action="<?php echo base_url('report/viewLivestockPurchaseReport'); ?>" method="post" enctype="multipart/form-data">
                                    <div class="col-xs-3 date_text">
                                        <span class=""> <?= lang('month'); ?>:</span> <span class=""> <?php if ($yearMonth_wise_filer_month) {
                                                                                                            echo number_to_month_name($yearMonth_wise_filer_month) . $yearMonth_wise_filer_year;
                                                                                                        } ?></span>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group col-xs-6">
                                            <select name="year" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                                <option value=""><?= lang('please_select_year'); ?> </option>
                                                <?php foreach (get_all_year() as  $years) { ?>
                                                    <option value="<?= $years ?>"><?= $years ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <select name="month" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                                <option value=""><?= lang('please_select_month'); ?> </option>
                                                <?php foreach (get_all_month() as $key => $value) { ?>
                                                    <option value="<?= $key ?>"><?= $value ?></option>
                                                <?php   } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <section class="text-right">
                                            <button type="submit" name="submit" class="button button-info submit_button"><i class="fa fa-search"></i> <?= lang('search'); ?></button>
                                        </section>
                                    </div>
                                </form>
                            </div>

                            <table class="table table-striped table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th><?= lang('serialNo'); ?></th>
                                        <th><?= lang('livestock_id'); ?></th>
                                        <th><?= lang('total_purchased'); ?> (<?= $settings->unit ?>)</th>
                                        <th><?= lang('amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial = 0;
                                    foreach ($livestocks as $livestock) {
                                        $serial++;
                                    ?>
                                        <tr class="">
                                            <td> <?= $serial; ?></td>
                                            <td> <?php echo $livestock->ls_name; ?></td>
                                            <td><?php $LivestockPurchasedQuantityYM = $this->report_model->getYearMonthWiseLivestockPurchaseQuantityByLivestockId($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $livestock->ls_id, 'purv_quantity');
                                                if ($LivestockPurchasedQuantityYM) {
                                                    echo  $LivestockPurchasedQuantityYM;
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td><?php $LivestockPurchasedAmountYM = $this->report_model->getYearMonthWiseLivestockPurchaseQuantityByLivestockId($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $livestock->ls_id, 'purv_total');
                                                if ($LivestockPurchasedAmountYM) {
                                                    echo $settings->currency . ' ' . number_format_currency($LivestockPurchasedAmountYM, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2">
                                            <div class="text-center">
                                                <p><?= lang('purchase_quantity'); ?> (<?= $settings->unit; ?>)</p>
                                                <p><?php $QuantityYM = $this->report_model->getYearMonthWisePurchase($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, 'purv_quantity');
                                                    if ($QuantityYM) {
                                                        echo $QuantityYM;
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></p>
                                                <p><?= lang('purchase_bill'); ?></p>
                                                <p><?php $purchaseBillYM = $this->report_model->getYearMonthWisePurchaseBill($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, 'purs_id');
                                                    if ($purchaseBillYM) {
                                                        echo $purchaseBillYM;
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></p>
                                            </div>
                                        </td>
                                        <td>
                                            <p><?= lang('purs_grand_total'); ?>:</p>
                                        </td>
                                        <td>
                                            <p><?php $SoldAmountYM = $this->report_model->getYearMonthWisePurchase($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, 'purs_grand_total');
                                                if ($SoldAmountYM) {
                                                    echo $settings->currency . ' ' . number_format_currency($SoldAmountYM, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>


                        <!-- ====================== Date to Date And Month Wise Report ====================== -->
                        <div class="col-md-6">
                            <!-- Date to date Search -->
                            <div class="row filtering_heading_text">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <h4><?= lang('date_to_date_filtering'); ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="row purchase_report_filter">
                                <!-- Yearly Search -->
                                <form role="form" action="<?php echo base_url('report/viewLivestockPurchaseReport'); ?>" method="post" enctype="multipart/form-data">
                                    <div class="col-xs-2 date_text">
                                        <span class=""> <?= lang('start_date'); ?>:</span> <br>
                                        <span class=""> <?= $start_date_value; ?></span>
                                    </div>
                                    <div class="col-xs-2 date_text">
                                        <span class=""><?= lang('end_date'); ?>:</span><br>
                                        <span class=""> <?= $end_date_value; ?></span>
                                    </div>
                                    <div class="col-xs-5">
                                        <div class="form-group col-xs-6">
                                            <input type="text" class="form-control datepicker" name="start_date" value='' placeholder="Select start date" autocomplete="off" required>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <input type="text" class="form-control datepicker" name="end_date" value='' placeholder="select end date" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <section class="text-right">
                                            <button type="submit" name="submit" class="button button-info submit_button"><i class="fa fa-search"></i> <?= lang('search'); ?></button>
                                        </section>
                                    </div>
                                </form>
                            </div>
                            <table class="table table-striped table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('serialNo'); ?></th>
                                        <th><?php echo lang('livestock_id'); ?></th>
                                        <th><?php echo lang('total_purchased'); ?> (<?= $settings->unit ?>)</th>
                                        <th><?= lang('amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial = 0;
                                    foreach ($livestocks as $livestock) {
                                        $serial++;
                                    ?>
                                        <tr class="">
                                            <td> <?= $serial; ?></td>
                                            <td> <?php echo $livestock->ls_name; ?></td>
                                            <td><?php $LivestockPurchasedQuantityDate = $this->report_model->getStartDateEndDateWisePurchaseByLivestockId($start_date_value_format, $end_date_value_format, $livestock->ls_id, 'purv_quantity');
                                                if ($LivestockPurchasedQuantityDate) {
                                                    echo  $LivestockPurchasedQuantityDate;
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td><?php $LivestockPurchasedAmountDate = $this->report_model->getStartDateEndDateWisePurchaseByLivestockId($start_date_value_format, $end_date_value_format, $livestock->ls_id, 'purv_total');
                                                if ($LivestockPurchasedAmountDate) {
                                                    echo $settings->currency . ' ' . number_format_currency($LivestockPurchasedAmountDate, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2">
                                            <div class="text-center">
                                                <p><?= lang('purchase_quantity'); ?> (<?= $settings->unit; ?>)</p>
                                                <p><?php $QuantityDate = $this->report_model->getStartDateEndDateWisePurchase($start_date_value_format, $end_date_value_format, 'purv_quantity');
                                                    if ($QuantityDate) {
                                                        echo $QuantityDate;
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></p>
                                                <p>
                                                    <?= lang('purchase_bill'); ?></p>
                                                <p><?php $purchaseBillDate = $this->report_model->getStartDateEndDatePurchaseBill($start_date_value_format, $end_date_value_format, 'purs_id');
                                                    if ($purchaseBillDate) {
                                                        echo $purchaseBillDate;
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></p>
                                            </div>
                                        </td>
                                        <td>
                                            <p><?= lang('purs_grand_total'); ?>:</p>
                                        </td>
                                        <td>
                                            <p><?php $SoldAmountYM = $this->report_model->getStartDateEndDateWisePurchase($start_date_value_format, $end_date_value_format, 'purs_grand_total');
                                                if ($SoldAmountYM) {
                                                    echo $settings->currency . ' ' . number_format_currency($SoldAmountYM, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <hr>
                    <!-- ====================== Year And Month Wise Report ====================== -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row filtering_heading_text">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <h4><?= lang('year_wise_filtering'); ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="space15">
                                <div class="row purchase_report_filter">
                                    <!-- Yearly Search -->
                                    <form role="form" action="<?php echo base_url('report/viewLivestockPurchaseReport'); ?>" method="post" enctype="multipart/form-data">
                                        <div class="col-xs-2">
                                            <span class="filter_font"> <?= lang('year'); ?>:</span>
                                        </div>
                                        <div class="col-xs-2">
                                            <span class="filter_font"><?php echo $year_value; ?></span>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <select name="year_value" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                                    <option value=""><?= lang('please_select_year'); ?> </option>
                                                    <?php foreach (get_all_year() as  $years) { ?>
                                                        <option value="<?= $years ?>" <?php if ($year_value) if ($year_value == $years) {
                                                                                            echo "selected";
                                                                                        } ?>><?= $years ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <section class="text-right">
                                                <button type="submit" name="submit" class="button button-info submit_button"><i class="fa fa-search"></i> <?= lang('search'); ?></button>
                                            </section>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <table class="table table-striped table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th> <?= lang('month'); ?></th>
                                        <th> <?= lang('purchase_quantity'); ?> (<?= $settings->unit; ?>)</th>
                                        <th> <?= lang('purchase_amount'); ?> </th>
                                        <th> <?= lang('purchase_bill'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $start = $month = strtotime($year_value . '-01-01');
                                    $end = strtotime($year_value . '-12-31');
                                    while ($month < $end) {
                                        echo "<tr>";
                                        echo "<td>";
                                        echo $date = date('F', $month), PHP_EOL;
                                        $date = date('F Y', $month);
                                        $month = strtotime("+1 month", $month);
                                        $alpha_y = date('Y', strtotime($date));
                                        $alpha_m = date('m', strtotime($date));
                                        echo "</td>";

                                        // Quantity
                                        echo "<td>";
                                        $quantity = $this->report_model->getYearMonthWisePurchase($alpha_y, $alpha_m, 'purv_quantity');
                                        if ($quantity) {
                                            echo $quantity;
                                        }
                                        echo "</td>";

                                        // Amount
                                        echo "<td>";
                                        $amount = $this->report_model->getYearMonthWisePurchase($alpha_y, $alpha_m, 'purv_total');
                                        if ($amount) {
                                            echo $settings->currency . number_format_currency($amount, 2);
                                        }
                                        echo "</td>";
                                        // Bill
                                        echo "<td>";
                                        $totalBillCount = $this->report_model->getYearMonthWisePurchaseBill($alpha_y, $alpha_m, 'purs_id');
                                        if ($totalBillCount) {
                                            echo $totalBillCount;
                                        }
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                    ?>

                                    <tr class="bg-primary-light">
                                        <td><?= lang('total'); ?></td>
                                        <td><?php
                                            $YearQuantity = $this->report_model->getYearWisePurchase($alpha_y, 'purv_quantity');
                                            if ($YearQuantity) {
                                                echo $YearQuantity;
                                                echo "&nbsp";
                                                echo $settings->unit;
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                        <td><?php
                                            $YearAmount = $this->report_model->getYearWisePurchase($alpha_y, 'purv_total');
                                            if ($YearAmount) {
                                                echo $settings->currency . number_format_currency($YearAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                        <td>
                                            <?php
                                            $YearBill = $this->report_model->getYearWisePurchaseBill($alpha_y, 'purs_id');
                                            if ($YearBill) {
                                                echo $YearBill;
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<!--footer start-->