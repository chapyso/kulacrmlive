<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <header class="panel-heading bg-info">
            <i class="fa-solid fa-chart-pie"></i> <?= lang('expense_reports'); ?>
            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
        </header>
        <!-- page start-->
        <section class="panel">
            <div class="panel-body">
                <div class="adv-table editable-table">
                    <!-- Today Report -->
                    <div class="row">
                        <!-- ====================== Today Report ====================== -->
                        <div class="col-md-6">
                            <div class="today_report">
                                <h3><?= lang('today_reports'); ?></h3>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <td><?= lang('expense'); ?> </td>
                                                <td><?= lang('paid'); ?></td>
                                                <td><?= lang('due'); ?></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?php $todayExpenseAmount = $this->report_model->getTodayExpenseAmount(date('Y-m-d'));
                                                    if ($todayExpenseAmount) {
                                                        echo $settings->currency . number_format_currency($todayExpenseAmount, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></td>
                                                <td><?php $todayPaidAmount = $this->report_model->getTodayExpensePaidAmount(date('Y-m-d'));
                                                    if ($todayPaidAmount) {
                                                        echo $settings->currency . number_format_currency($todayPaidAmount, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></td>
                                                <td>
                                                    <?php $todayDueAmount = $todayExpenseAmount - $todayPaidAmount;
                                                    if ($todayDueAmount) {
                                                        echo $settings->currency . number_format_currency($todayDueAmount, 2);
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
                        <!-- ====================== Total Report ====================== -->
                        <div class="col-md-6">
                            <div class="today_report">
                                <h3><?= lang('total_reports'); ?></h3>
                            </div>
                            <table class="table table-striped table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <td><?= lang('expense'); ?> </td>
                                        <td><?= lang('paid'); ?></td>
                                        <td><?= lang('due'); ?></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php $totalExpense = $this->expense_model->getTotalSumOfExpense();
                                            if ($totalExpense) {
                                                echo $settings->currency . number_format_currency($totalExpense, 2);
                                            } else {
                                                echo 0;
                                            }

                                            ?></td>
                                        <td><?php $totalPaid = $this->expense_model->getTotalSumPaidAmount();
                                            if ($totalPaid) {
                                                echo $settings->currency . number_format_currency($totalPaid, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                        <td>
                                            <?php $totalDueAmount = $totalExpense - $totalPaid;
                                            if ($totalDueAmount) {
                                                echo $settings->currency . number_format_currency($totalDueAmount, 2);
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
                                <form role="form" action="<?php echo base_url('report/viewExpenseReport'); ?>" method="post" enctype="multipart/form-data">
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
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <td><?= lang('expense'); ?> </td>
                                                <td><?= lang('paid'); ?></td>
                                                <td><?= lang('due'); ?></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?php $expenseAmountYM = $this->report_model->getYearMonthExpenseAmount($yearMonth_wise_filer_year, $yearMonth_wise_filer_month);
                                                    if ($expenseAmountYM) {
                                                        echo $settings->currency . number_format_currency($expenseAmountYM, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></td>
                                                <td><?php $paidAmountYM = $this->report_model->getYearMonthExpensePaidAmount($yearMonth_wise_filer_year, $yearMonth_wise_filer_month);
                                                    if ($paidAmountYM) {
                                                        echo $settings->currency . number_format_currency($paidAmountYM, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></td>
                                                <td>
                                                    <?php $dueAmountYM = $expenseAmountYM - $paidAmountYM;
                                                    if ($dueAmountYM) {
                                                        echo $settings->currency . number_format_currency($dueAmountYM, 2);
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
                                <form role="form" action="<?php echo base_url('report/viewExpenseReport'); ?>" method="post" enctype="multipart/form-data">
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
                                                <td><?= lang('expense'); ?> </td>
                                                <td><?= lang('paid'); ?></td>
                                                <td><?= lang('due'); ?></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?php $expenseAmountDate = $this->report_model->getDateToDateExpenseAmount($start_date_value_format, $end_date_value_format);
                                                    if ($expenseAmountDate) {
                                                        echo $settings->currency . number_format_currency($expenseAmountDate, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></td>
                                                <td><?php $paidAmountDate = $this->report_model->getDateToDateExpensePaidAmount($start_date_value_format, $end_date_value_format);
                                                    if ($paidAmountDate) {
                                                        echo $settings->currency . number_format_currency($paidAmountDate, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></td>
                                                <td>
                                                    <?php $dueAmountDate = $expenseAmountDate - $paidAmountDate;
                                                    if ($dueAmountDate) {
                                                        echo $settings->currency . number_format_currency($dueAmountDate, 2);
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
                                        <h4><?= lang('yearly_expense_amount_filtering'); ?></h4>
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
                                        <th> <?= lang('due_amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $yearlyTotalExpenseAmount = 0;
                                    $yearlyTotalPaidAmount = 0;
                                    $yearlyTotalDueAmount = 0;

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
                                        $expenseAmountM = $this->report_model->getYearMonthExpenseAmount($alpha_y, $alpha_m);
                                        if ($expenseAmountM) {
                                            echo $settings->currency . number_format_currency($expenseAmountM, 2);
                                        }
                                        $yearlyTotalExpenseAmount += $expenseAmountM;
                                        echo "</td>";
                                        // Paid
                                        echo "<td>";
                                        $paidAmountM = $this->report_model->getYearMonthExpensePaidAmount($alpha_y, $alpha_m);
                                        if ($paidAmountM) {
                                            echo $settings->currency . number_format_currency($paidAmountM, 2);
                                        }
                                        $yearlyTotalPaidAmount += $paidAmountM;
                                        echo "</td>";
                                        // Due
                                        echo "<td>";
                                        $monthlyDue = $expenseAmountM - $paidAmountM;
                                        if ($monthlyDue) {
                                            echo $settings->currency . number_format_currency($monthlyDue, 2);
                                        }
                                        $yearlyTotalDueAmount += $monthlyDue;
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                    ?>

                                    <tr class="bg-warning">
                                        <td><?= lang('total'); ?></td>
                                        <td><?php
                                            if ($yearlyTotalExpenseAmount) {
                                                echo $settings->currency . number_format_currency($yearlyTotalExpenseAmount, 2);
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
                                        <td>
                                            <?php
                                            if ($yearlyTotalDueAmount) {
                                                echo $settings->currency . number_format_currency($yearlyTotalDueAmount, 2);
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