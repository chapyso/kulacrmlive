<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <header class="panel-heading bg-info">
            <i class="fa-solid fa-chart-pie"></i> <?= lang('supplier_reports'); ?>
            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
        </header>
        <!-- page start-->
        <section class="panel">
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
                                        <th><?= lang('serialNo'); ?></th>
                                        <th><?= lang('name'); ?></th>
                                        <th><?= lang('purchase_payable_amount'); ?></th>
                                        <th><?= lang('paid'); ?> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial = 0;
                                    $todayPurchasePaymentAmount = 0;
                                    foreach ($suppliers->result() as $supplier) {
                                        $serial++;
                                    ?>
                                        <tr class="">
                                            <td><?= $serial ?></td>
                                            <td> <?= $supplier->s_name; ?></td>
                                            <td>
                                                <?php
                                                $livePurchaseToday = $this->report_model->getTodaySupplierWiseLivestockPurchaseAmount(date("Y-m-d"), $supplier->s_id);
                                                $foodPurchaseToday = $this->report_model->getTodaySupplierWiseFoodPurchaseAmount(date("Y-m-d"), $supplier->s_id);
                                                $vaccinePurchaseToday = $this->report_model->getTodaySupplierWiseVaccinePurchaseAmount(date("Y-m-d"), $supplier->s_id);
                                                $purchaseTotalToday = $livePurchaseToday + $foodPurchaseToday + $vaccinePurchaseToday;
                                                if ($purchaseTotalToday) {
                                                    echo $settings->currency . number_format_currency($purchaseTotalToday, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php $supplierPaidAmountToday = $this->report_model->getTodaySupplierWiseTotalPaidAmount(date("Y-m-d"), $supplier->s_id);
                                                if ($supplierPaidAmountToday) {
                                                    echo $settings->currency . number_format_currency($supplierPaidAmountToday, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                $todayPurchasePaymentAmount += $supplierPaidAmountToday;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2"><?= lang('grand_total'); ?>: </td>
                                        <td>
                                            <?php $grandTotalPurAmount = $this->report_model->getTodayLivestockPurchasedAmount(date("Y-m-d")) + $this->report_model->getTodayTotalFoodPurchaseAmount(date("Y-m-d"), 'fdps_grand_total') + $this->report_model->getTodayTotalVaccinePurchase(date("Y-m-d"), 'vps_grand_total');
                                            if ($grandTotalPurAmount) {
                                                echo $settings->currency .   number_format_currency($grandTotalPurAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($todayPurchasePaymentAmount) {
                                                echo $settings->currency .  number_format_currency($todayPurchasePaymentAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?>
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
                                        <th><?= lang('serialNo'); ?>.</th>
                                        <th><?= lang('name'); ?></th>
                                        <th><?= lang('purchase_payable_amount'); ?></th>
                                        <th><?= lang('paid'); ?> </th>
                                        <th><?= lang('due'); ?> </th>
                                        <th><?= lang('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial1 = 0;
                                    foreach ($suppliers->result() as $supplier1) {
                                        $serial1++;
                                    ?>
                                        <tr class="">
                                            <td><?= $serial1 ?></td>
                                            <td> <?= $supplier1->s_name; ?></td>
                                            <td>
                                                <?php
                                                $livePurchase = $this->purchase_model->getSumSupplierWiseTotalPurchaseAmount($supplier1->s_id);
                                                $foodPurchase = $this->food_model->getSumFoodSupplierWiseTotalPurchaseAmount($supplier1->s_id);
                                                $vaccinePurchase = $this->vaccine_model->getSumVaccineSupplierWiseTotalPurchaseAmount($supplier1->s_id);
                                                $purchaseTotal = $livePurchase + $foodPurchase + $vaccinePurchase;
                                                if ($purchaseTotal) {
                                                    echo $settings->currency . number_format_currency($purchaseTotal, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php $supplierPaidAmount = $this->payments_model->getSumSupplierWiseTotalPaidAmount($supplier1->s_id);
                                                if ($supplierPaidAmount) {
                                                    echo $settings->currency . number_format_currency($supplierPaidAmount, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $totalDueAmount = $purchaseTotal - $supplierPaidAmount;
                                                if ($totalDueAmount) {
                                                    echo $settings->currency . number_format_currency($totalDueAmount, 2);
                                                } else {
                                                    echo 0;
                                                }

                                                ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo base_url(''); ?>payments/viewSupplierWisePayment?id=<?= $supplier->s_id; ?>"><button type="button" class="button button-primary"><i class="fas fa-eye"></i> <?= lang('ledger') ?></button></i></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2"><?= lang('grand_total'); ?>: </td>
                                        <td>
                                            <?php $grandTotalPurAmount = $this->purchase_model->getTotalLivestockPurchasedAmount() + $this->food_model->getTotalFoodPurchaseWeight('fdpv_total') + $this->vaccine_model->getTotalVaccinePurchaseQuantity('vpv_total');
                                            if ($grandTotalPurAmount) {
                                                echo $settings->currency .   number_format_currency($grandTotalPurAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php $grandTotalSupplierPaidAmount = $this->payments_model->getSumSupplierTotalPaidAmount();
                                            if ($grandTotalSupplierPaidAmount) {
                                                echo $settings->currency . number_format_currency($grandTotalSupplierPaidAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $grandTotalDueAmount = $grandTotalPurAmount - $grandTotalSupplierPaidAmount;
                                            if ($grandTotalDueAmount) {
                                                echo $settings->currency . number_format_currency($grandTotalDueAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.Today Report -->
                    <hr>
                    <div class="row">
                        <!-- ====================== Year And Month Wise Report ====================== -->
                        <div class="col-md-6">
                            <!-- Date to date Search -->
                            <div class="row filtering_heading_text">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <h4><?= lang('month_wise_filtering'); ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="row sale_report_filter">
                                <!-- Yearly Search -->
                                <form role="form" action="<?php echo base_url('report/viewSupplierReport'); ?>" method="post" enctype="multipart/form-data">
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
                            <!-- Date to Date Report View -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th><?= lang('serialNo'); ?>.</th>
                                                <th><?= lang('name'); ?></th>
                                                <th><?= lang('purchase_payable_amount'); ?></th>
                                                <th><?= lang('paid'); ?> </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $serial2 = 0;
                                            $yearMonthTotalPurchasePayableAmount = 0;
                                            $yearMonthTotalPaidAmount = 0;
                                            foreach ($suppliers->result() as $supplier2) {
                                                $serial2++;
                                            ?>
                                                <tr class="">
                                                    <td><?= $serial2 ?></td>
                                                    <td> <?= $supplier2->s_name; ?></td>
                                                    <td>
                                                        <?php
                                                        $livePurchaseYM = $this->report_model->getYearMonthSupplierWiseLivestockPurchaseAmount($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $supplier2->s_id);
                                                        $foodPurchaseYM = $this->report_model->getYearMonthSupplierWiseFoodPurchaseAmount($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $supplier2->s_id);
                                                        $vaccinePurchaseYM = $this->report_model->getYearMonthSupplierWiseVaccinePurchaseAmount($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $supplier2->s_id);
                                                        $purchaseTotalYM = $livePurchaseYM + $foodPurchaseYM + $vaccinePurchaseYM;
                                                        if ($purchaseTotalYM) {
                                                            echo $settings->currency . number_format_currency($purchaseTotalYM, 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        // Sum Total
                                                        $yearMonthTotalPurchasePayableAmount += $purchaseTotalYM;

                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php $supplierPaidAmountYM = $this->report_model->getYearMonthSupplierWiseTotalPaidAmount($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $supplier2->s_id);
                                                        if ($supplierPaidAmountYM) {
                                                            echo $settings->currency . number_format_currency($supplierPaidAmountYM, 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        // Sum Total
                                                        $yearMonthTotalPaidAmount += $supplierPaidAmountYM;
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="2"><?= lang('grand_total'); ?>: </td>
                                                <td>
                                                    <?php
                                                    if ($yearMonthTotalPurchasePayableAmount) {
                                                        echo $settings->currency . number_format_currency($yearMonthTotalPurchasePayableAmount, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if ($yearMonthTotalPaidAmount) {
                                                        echo $settings->currency . number_format_currency($yearMonthTotalPaidAmount, 2);
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
                            <!-- /.Date to Date Report View -->
                        </div>
                        <div class="col-md-6">
                            <!-- ====================== Date to Date Report ====================== -->
                            <div class="row filtering_heading_text">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <h4><?= lang('date_to_date_filtering'); ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="row sale_report_filter">
                                <form role="form" action="<?php echo base_url('report/viewSupplierReport'); ?>" method="post" enctype="multipart/form-data">
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
                            <!-- Date to date Wise View -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th><?= lang('serialNo'); ?>.</th>
                                                <th><?= lang('name'); ?></th>
                                                <th><?= lang('purchase_payable_amount'); ?></th>
                                                <th><?= lang('paid'); ?> </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $serial3 = 0;
                                            $dateToDateTotalPurchasePayableAmount = 0;
                                            $dateToDateTotalPaidAmount = 0;
                                            foreach ($suppliers->result() as $supplier3) {
                                                $serial3++;
                                            ?>
                                                <tr class="">
                                                    <td><?= $serial3 ?></td>
                                                    <td> <?= $supplier3->s_name; ?></td>
                                                    <td>
                                                        <?php
                                                        $livePurchaseDate = $this->report_model->getDateToDateSupplierWiseLivestockPurchaseAmount($start_date_value_format, $end_date_value_format, $supplier3->s_id);
                                                        $foodPurchaseDate = $this->report_model->getDateToDateSupplierWiseFoodPurchaseAmount($start_date_value_format, $end_date_value_format, $supplier3->s_id);
                                                        $vaccinePurchaseDate = $this->report_model->getDateToDateSupplierWiseVaccinePurchaseAmount($start_date_value_format, $end_date_value_format, $supplier3->s_id);
                                                        $purchaseTotalDate = $livePurchaseDate + $foodPurchaseDate + $vaccinePurchaseDate;
                                                        if ($purchaseTotalDate) {
                                                            echo $settings->currency . number_format_currency($purchaseTotalDate, 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        // Sum Total
                                                        $dateToDateTotalPurchasePayableAmount += $purchaseTotalDate;

                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php $supplierPaidAmountDate = $this->report_model->getDateToDateSupplierWiseTotalPaidAmount($start_date_value_format, $end_date_value_format, $supplier3->s_id);
                                                        if ($supplierPaidAmountDate) {
                                                            echo $settings->currency . number_format_currency($supplierPaidAmountDate, 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        // Sum Total
                                                        $dateToDateTotalPaidAmount += $supplierPaidAmountDate;
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="2"><?= lang('grand_total'); ?>: </td>
                                                <td>
                                                    <?php
                                                    if ($dateToDateTotalPurchasePayableAmount) {
                                                        echo $settings->currency . number_format_currency($dateToDateTotalPurchasePayableAmount, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if ($dateToDateTotalPaidAmount) {
                                                        echo $settings->currency . number_format_currency($dateToDateTotalPaidAmount, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.Date to Date Report View -->
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row filtering_heading_text">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <h4><?= lang('yearly_purchase_amount_filtering'); ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="space15">
                                <div class="row sale_report_filter">
                                    <!-- Yearly Search -->
                                    <form role="form" action="<?php echo base_url('report/viewProductStockReport'); ?>" method="post" enctype="multipart/form-data">
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
                                        <th> <?= lang('purchase_amount'); ?></th>
                                        <th> <?= lang('paid_amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $yearlyTotalPurchaseAmount = 0;
                                    $yearlyTotalPaidAmount = 0;

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

                                        // Purchase Amount
                                        echo "<td>";
                                        $livestockPurchaseAmount = $this->report_model->getYearMonthWiseLivestockPurchaseAmount($alpha_y, $alpha_m);
                                        $foodPurchaseAmount = $this->report_model->getYearMonthWiseFoodPurchaseAmount($alpha_y, $alpha_m);
                                        $vaccinePurchaseAmount = $this->report_model->getYearMonthWiseVaccinePurchaseAmount($alpha_y, $alpha_m);
                                        $monthWiseTotalAmount = $livestockPurchaseAmount + $foodPurchaseAmount + $vaccinePurchaseAmount;
                                        if ($monthWiseTotalAmount) {
                                            echo $settings->currency . number_format_currency($monthWiseTotalAmount, 2);
                                        }
                                        $yearlyTotalPurchaseAmount += $monthWiseTotalAmount;
                                        echo "</td>";
                                        // Paid
                                        echo "<td>";
                                        $monthWiseTotalPaidAmount = $this->report_model->getYearMonthWiseTotalPaidAmount($alpha_y, $alpha_m);
                                        if ($monthWiseTotalPaidAmount) {
                                            echo $settings->currency . number_format_currency($monthWiseTotalPaidAmount, 2);
                                        }
                                        $yearlyTotalPaidAmount += $monthWiseTotalPaidAmount;
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                    ?>

                                    <tr class="bg-warning">
                                        <td><?= lang('total'); ?></td>
                                        <td><?php
                                            if ($yearlyTotalPurchaseAmount) {
                                                echo $settings->currency . number_format_currency($yearlyTotalPurchaseAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                        <td>
                                            <?php
                                            if ($yearlyTotalPaidAmount) {
                                                echo $settings->currency . number_format_currency($yearlyTotalPaidAmount, 2);
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