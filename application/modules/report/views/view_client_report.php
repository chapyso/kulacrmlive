<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <header class="panel-heading  bg-info">
            <i class="fa-solid fa-chart-pie"></i> <?= lang('client_reports'); ?>
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
                                        <th><?php echo lang('serialNo'); ?></th>
                                        <th><?= lang('client'); ?></th>
                                        <th><?= lang('total_receivable_amount'); ?></th>
                                        <th><?= lang('received'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial = 0;
                                    $todayTotalReceivableAmount = 0;
                                    $todayTotalReceivedAmount = 0;
                                    foreach ($clients->result() as $list) {
                                        $serial++;
                                    ?>
                                        <tr class="">
                                            <td> <?= $serial ?></td>
                                            <td> <?= $list->c_name; ?></td>
                                            <!-- Receivable -->
                                            <td> <?php $todaySaleTotal = $this->report_model->getTodayClientWiseLivestockSaleAmount(date("Y-m-d"), $list->c_id) + $this->report_model->getTodayClientWiseProductSaleAmount(date("Y-m-d"), $list->c_id);
                                                    if ($todaySaleTotal) {
                                                        echo $settings->currency . ' ' . number_format_currency($todaySaleTotal, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    $todayTotalReceivableAmount += $todaySaleTotal;
                                                    ?>
                                            </td>
                                            <td>
                                                <!-- received -->
                                                <?php $todayReceivedAmount = $this->report_model->getSumClientWiseTotalReceivedAndPaymentAmount(date("Y-m-d"), $list->c_id, 'cp_received_amount');
                                                if ($todayReceivedAmount) {
                                                    echo $settings->currency . ' ' . number_format_currency($todayReceivedAmount, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                $todayTotalReceivedAmount += $todayReceivedAmount;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2"><?= lang('grand_total'); ?>: </td>
                                        <td>
                                            <?php
                                            if ($todayTotalReceivableAmount) {
                                                echo $settings->currency . ' ' . number_format_currency($todayTotalReceivableAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($todayTotalReceivedAmount) {
                                                echo $settings->currency . ' ' . number_format_currency($todayTotalReceivedAmount, 2);
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
                                        <th><?php echo lang('serialNo'); ?></th>
                                        <th><?= lang('client'); ?></th>
                                        <th><?= lang('total_receivable_amount'); ?></th>
                                        <th><?= lang('received'); ?></th>
                                        <th><?= lang('due'); ?></th>
                                        <th><?php echo lang('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $serial1 = 0;
                                    $grandTotalReceivableAmount = 0;
                                    $grandTotalReceivedAmount = 0;
                                    foreach ($clients->result() as $list1) {
                                        $serial1++;
                                    ?>
                                        <tr class="">
                                            <td> <?= $serial1 ?></td>
                                            <td> <?= $list1->c_name; ?></td>
                                            <!-- Receivable -->
                                            <td> <?php $saleTotal = $this->sale_model->getSumClientWiseTotalSaleAmount($list1->c_id) + $this->sale_model->getSumClientWiseTotalProductSaleAmount($list1->c_id);
                                                    if ($saleTotal) {
                                                        echo $settings->currency . ' ' . number_format_currency($saleTotal, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    $grandTotalReceivableAmount += $saleTotal;
                                                    ?>
                                            </td>
                                            <td>
                                                <!-- received -->
                                                <?php $receivedAmount = $this->payments_model->getSumClientWiseTotalReceivedAndPaymentAmount($list1->c_id, 'cp_received_amount');
                                                if ($receivedAmount) {
                                                    echo $settings->currency . ' ' . number_format_currency($receivedAmount, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                $grandTotalReceivedAmount += $receivedAmount;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $grandTotalDue = $saleTotal - $receivedAmount;
                                                if ($grandTotalDue) {
                                                    echo $settings->currency . ' ' . number_format_currency($grandTotalDue, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo base_url(''); ?>payments/viewClientWisePayment?c_id=<?= $list1->c_id; ?>"><button type="button" class="button button-primary"><i class="fas fa-eye"></i> <?= lang('ledger'); ?></button></i></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2"><?= lang('grand_total'); ?>: </td>
                                        <td>
                                            <?php
                                            if ($grandTotalReceivableAmount) {
                                                echo $settings->currency .  number_format_currency($grandTotalReceivableAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($grandTotalReceivedAmount) {
                                                echo $settings->currency .  number_format_currency($grandTotalReceivedAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php $grandTotalDue = $grandTotalReceivableAmount - $grandTotalReceivedAmount;
                                            if ($grandTotalDue) {
                                                echo $settings->currency . number_format_currency($grandTotalDue, 2);
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
                                <form role="form" action="<?php echo base_url('report/viewClientReport'); ?>" method="post" enctype="multipart/form-data">
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
                            <!-- Month View -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('serialNo'); ?></th>
                                                <th><?= lang('client'); ?></th>
                                                <th><?= lang('total_receivable_amount'); ?></th>
                                                <th><?= lang('received'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $serial2 = 0;
                                            $grandTotalReceivableAmountYM = 0;
                                            $grandTotalReceivedAmountYM = 0;
                                            foreach ($clients->result() as $list2) {
                                                $serial2++;
                                            ?>
                                                <tr class="">
                                                    <td> <?= $serial2 ?></td>
                                                    <td> <?= $list2->c_name; ?></td>
                                                    <!-- Receivable -->
                                                    <td> <?php $saleTotalYM = $this->report_model->getYearMonthClientWiseLivestockSaleAmount($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $list2->c_id) + $this->report_model->getYearMonthClientWiseProductSaleAmount($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $list2->c_id);
                                                            if ($saleTotalYM) {
                                                                echo $settings->currency . ' ' . number_format_currency($saleTotalYM, 2);
                                                            } else {
                                                                echo 0;
                                                            }
                                                            $grandTotalReceivableAmountYM += $saleTotalYM;
                                                            ?>
                                                    </td>
                                                    <td>
                                                        <!-- received -->
                                                        <?php $receivedAmountYM = $this->report_model->getYearMonthClientWiseTotalReceivedAndPaymentAmount($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $list2->c_id, 'cp_received_amount');
                                                        if ($receivedAmountYM) {
                                                            echo $settings->currency . number_format_currency($receivedAmountYM, 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        $grandTotalReceivedAmountYM += $receivedAmountYM;
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="2"><?= lang('grand_total'); ?>: </td>
                                                <td>
                                                    <?php
                                                    if ($grandTotalReceivableAmountYM) {
                                                        echo $settings->currency . number_format_currency($grandTotalReceivableAmountYM, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($grandTotalReceivedAmountYM) {
                                                        echo $settings->currency . number_format_currency($grandTotalReceivedAmountYM, 2);
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
                                <form role="form" action="<?php echo base_url('report/viewClientReport'); ?>" method="post" enctype="multipart/form-data">
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
                                                <th><?= lang('serialNo'); ?></th>
                                                <th><?= lang('client'); ?></th>
                                                <th><?= lang('total_receivable_amount'); ?></th>
                                                <th><?= lang('received'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $serial3 = 0;
                                            $grandTotalReceivableAmountDate = 0;
                                            $grandTotalReceivedAmountDate = 0;
                                            foreach ($clients->result() as $list3) {
                                                $serial3++;
                                            ?>
                                                <tr class="">
                                                    <td> <?= $serial3 ?></td>
                                                    <td> <?= $list3->c_name; ?></td>
                                                    <!-- Receivable -->
                                                    <td> <?php $saleTotalDate = $this->report_model->getDateToDateClientWiseLivestockSaleAmount($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $list3->c_id) + $this->report_model->getYearMonthClientWiseProductSaleAmount($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $list3->c_id);
                                                            if ($saleTotalDate) {
                                                                echo $settings->currency .  number_format_currency($saleTotalDate, 2);
                                                            } else {
                                                                echo 0;
                                                            }
                                                            $grandTotalReceivableAmountDate += $saleTotalDate;
                                                            ?>
                                                    </td>
                                                    <td>
                                                        <!-- received -->
                                                        <?php $receivedAmountDate = $this->report_model->getDateToDateClientWiseTotalReceivedAndPaymentAmount($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $list3->c_id, 'cp_received_amount');
                                                        if ($receivedAmountDate) {
                                                            echo $settings->currency .  number_format_currency($receivedAmountDate, 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        $grandTotalReceivedAmountDate += $receivedAmountDate;
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="2"><?= lang('grand_total'); ?>: </td>
                                                <td>
                                                    <?php
                                                    if ($grandTotalReceivableAmountDate) {
                                                        echo $settings->currency .  number_format_currency($grandTotalReceivableAmountDate, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($grandTotalReceivedAmountDate) {
                                                        echo $settings->currency .  number_format_currency($grandTotalReceivedAmountDate, 2);
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

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row filtering_heading_text">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <h4><?= lang('yearly_livestock_product_amount_filtering'); ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="space15">
                                <div class="row sale_report_filter">
                                    <!-- Yearly Search -->
                                    <form role="form" action="<?php echo base_url('report/viewClientReport'); ?>" method="post" enctype="multipart/form-data">
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
                                        <th> <?= lang('receivable_amount'); ?></th>
                                        <th> <?= lang('received_amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $yearlyTotalSaleAmount = 0;
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

                                        // Sale Amount
                                        echo "<td>";
                                        $livestockSaleAmount = $this->report_model->getYearMonthWiseLivestockSaleAmount($alpha_y, $alpha_m);
                                        $productSaleAmount = $this->report_model->getYearMonthWiseProductSaleAmount($alpha_y, $alpha_m);
                                        $monthWiseTotalAmount = $livestockSaleAmount + $productSaleAmount;
                                        if ($monthWiseTotalAmount) {
                                            echo $settings->currency . ' ' . number_format_currency($monthWiseTotalAmount, 2);
                                        }
                                        $yearlyTotalSaleAmount += $monthWiseTotalAmount;
                                        echo "</td>";
                                        // Paid
                                        echo "<td>";
                                        $monthWiseTotalPaidAmount = $this->report_model->getYearMonthWiseTotalReceivedAndPaymentAmount($alpha_y, $alpha_m, 'cp_received_amount');
                                        if ($monthWiseTotalPaidAmount) {
                                            echo $settings->currency . ' ' . number_format_currency($monthWiseTotalPaidAmount, 2);
                                        }
                                        $yearlyTotalPaidAmount += $monthWiseTotalPaidAmount;
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                    ?>

                                    <tr class="bg-warning">
                                        <td><?= lang('total'); ?> </td>
                                        <td><?php
                                            if ($yearlyTotalSaleAmount) {
                                                echo $settings->currency . ' ' . number_format_currency($yearlyTotalSaleAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                        <td>
                                            <?php
                                            if ($yearlyTotalPaidAmount) {
                                                echo $settings->currency . ' ' . number_format_currency($yearlyTotalPaidAmount, 2);
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