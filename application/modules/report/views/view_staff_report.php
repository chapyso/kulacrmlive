<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <header class="panel-heading bg-info">
            <i class="fa-solid fa-chart-pie"></i> <?= lang('staff_reports'); ?>
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
                                        <th><?= lang('serialNo'); ?>.</th>
                                        <th><?= lang('name'); ?></th>
                                        <th><?= lang('total_paid_amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial = 0;
                                    $totalTodayPayment = 0;
                                    foreach ($staffs->result() as $list) {
                                        $serial++;
                                    ?>
                                        <tr>
                                            <td><?= $serial ?></td>
                                            <td> <?= $list->sf_name; ?></td>
                                            <td><?php $todayPayments = $this->report_model->getTodayStaffWisePayment(date("Y-m-d"), $list->sf_id, 'sfp_payment_amount');
                                                if ($todayPayments) {
                                                    echo $settings->currency . number_format_currency($todayPayments, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                $totalTodayPayment += $todayPayments;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2"><?= lang('grand_total'); ?>:</td>
                                        <td>
                                            <?php if ($totalTodayPayment) {
                                                echo $settings->currency . number_format_currency($totalTodayPayment, 2);
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
                                        <th><?= lang('total_paid_amount'); ?></th>
                                        <th><?= lang('last_payment_date'); ?></th>
                                        <th><?= lang('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial1 = 0;
                                    $grandTotalPayment = 0;
                                    foreach ($staffs->result() as $list1) {
                                        $serial1++;
                                    ?>
                                        <tr class="">
                                            <td><?= $serial1 ?></td>
                                            <td> <?= $list1->sf_name; ?></td>
                                            <td><?php $paymentsTotal = $this->payments_model->getStaffWiseTotalPaymentAndReceivedAmountSum($list1->sf_id, 'sfp_payment_amount');
                                                if ($paymentsTotal) {
                                                    echo $settings->currency . $paymentsTotal;
                                                } else {
                                                    echo 0;
                                                }
                                                $grandTotalPayment += $paymentsTotal;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $paymentDate = $this->payments_model->getLastPaymentDate($list1->sf_id, 'sfp_date');
                                                if ($paymentDate) {
                                                    echo $month = date('F Y', strtotime($paymentDate));
                                                } else {
                                                    echo "<span class='text-danger'>" . lang('no_payment_yet') . "</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo base_url('') ?>payments/viewStaffWisePayment?sf_id=<?= $list1->sf_id; ?>"><button type="button" class="badge btn-primary"><i class="fas fa-eye"></i> <?= lang('payment'); ?></button></i></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2"><?= lang('grand_total'); ?>:</td>
                                        <td>
                                            <?php if ($grandTotalPayment) {
                                                echo $settings->currency . number_format_currency($grandTotalPayment, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                        <td colspan="2"></td>
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
                                <form role="form" action="<?php echo base_url('report/viewStaffReport'); ?>" method="post" enctype="multipart/form-data">
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
                                                <th><?= lang('serialNo'); ?>SL.</th>
                                                <th><?php echo lang('name'); ?></th>
                                                <th><?= lang('total_paid_amount'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $serial2 = 0;
                                            $grandTotalPaymentYM = 0;
                                            foreach ($staffs->result() as $list2) {
                                                $serial2++;
                                            ?>
                                                <tr class="">
                                                    <td><?= $serial2 ?></td>
                                                    <td> <?= $list2->sf_name; ?></td>
                                                    <td><?php $paymentsTotalYM = $this->report_model->getYearMonthStaffWisePayment($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $list2->sf_id, 'sfp_payment_amount');
                                                        if ($paymentsTotalYM) {
                                                            echo $settings->currency . number_format_currency($paymentsTotalYM, 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        $grandTotalPaymentYM += $paymentsTotalYM;
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="2"><?= lang('grand_total'); ?>:</td>
                                                <td>
                                                    <?php if ($grandTotalPaymentYM) {
                                                        echo $settings->currency . number_format_currency($grandTotalPaymentYM, 2);
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
                                <form role="form" action="<?php echo base_url('report/viewStaffReport'); ?>" method="post" enctype="multipart/form-data">
                                    <div class="col-xs-2 date_text">
                                        <span class=""> <?= lang('start_date'); ?>:</span> <br>
                                        <span class=""> <?= $start_date_value; ?></span>
                                    </div>
                                    <div class="col-xs-2 date_text">
                                        <span class=""> <?= lang('end_date'); ?>:</span><br>
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
                                                <th><?= lang('serialNo'); ?>SL.</th>
                                                <th><?php echo lang('name'); ?></th>
                                                <th><?= lang('total_paid_amount'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $serial3 = 0;
                                            $grandTotalPaymentDate = 0;
                                            foreach ($staffs->result() as $list3) {
                                                $serial3++;
                                            ?>
                                                <tr class="">
                                                    <td><?= $serial3 ?></td>
                                                    <td> <?= $list3->sf_name; ?></td>
                                                    <td><?php $paymentsTotalDate = $this->report_model->getDateToDateStaffWisePayment($start_date_value_format, $end_date_value_format, $list3->sf_id, 'sfp_payment_amount');
                                                        if ($paymentsTotalDate) {
                                                            echo $settings->currency . number_format_currency($paymentsTotalDate, 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        $grandTotalPaymentDate += $paymentsTotalDate;
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="2"><?= lang('grand_total'); ?>:</td>
                                                <td>
                                                    <?php if ($grandTotalPaymentDate) {
                                                        echo $settings->currency . number_format_currency($grandTotalPaymentDate, 2);
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
                                        <th> <?= lang('total_receivable_amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $yearlyTotalPayment = 0;

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
                                        $staffPaymentAmount = $this->report_model->getYearMonthWisePayment($alpha_y, $alpha_m, 'sfp_payment_amount');
                                        if ($staffPaymentAmount) {
                                            echo $settings->currency . ' ' . number_format_currency($staffPaymentAmount, 2);
                                        }
                                        $yearlyTotalPayment += $staffPaymentAmount;
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                    ?>
                                    <tr class="bg-warning">
                                        <td><?= lang('total'); ?></td>
                                        <td>
                                            <?php
                                            if ($yearlyTotalPayment) {
                                                echo $settings->currency . number_format_currency($yearlyTotalPayment, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
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