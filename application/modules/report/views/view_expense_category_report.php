<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <header class="panel-heading bg-info">
            <i class="fa-solid fa-chart-pie"></i> <?= lang('expense_category_reports'); ?>
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
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th><?= lang('serialNo'); ?>.</th>
                                                <th><?= lang('category'); ?></th>
                                                <th><?= lang('total_expense_amount'); ?></th>
                                                <th><?= lang('total_paid_amount'); ?></th>
                                                <th><?= lang('total_due_amount'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $serial = 0;
                                            $todayGrandTotalCategoryExpense = 0;
                                            $todayGrandTotalCategoryPaid = 0;
                                            $todayGrandTotalCategoryDue = 0;
                                            foreach ($expenseCategories->result() as $list) {
                                                $serial++;
                                            ?>
                                                <tr class="">
                                                    <td><?= $serial; ?></td>
                                                    <td><?= $list->exc_name; ?></td>
                                                    <td><?php $todayCategoryExpenseAmount = $this->report_model->getTodayCategoryWiseExpenseAmount(date("Y-m-d"), $list->exc_id);
                                                        if ($todayCategoryExpenseAmount) {
                                                            echo $settings->currency . number_format_currency($todayCategoryExpenseAmount, 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        $todayGrandTotalCategoryExpense += $todayCategoryExpenseAmount;
                                                        ?></td>
                                                    <td><?php $todayCategoryPaidAmount = $this->report_model->getTodayCategoryWiseExpensePaidAmount(date("Y-m-d"), $list->exc_id);
                                                        if ($todayCategoryPaidAmount) {
                                                            echo $settings->currency . number_format_currency($todayCategoryPaidAmount, 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        $todayGrandTotalCategoryPaid += $todayCategoryPaidAmount;
                                                        ?></td>
                                                    <td>
                                                        <?php $todayCategoryDueAmount = $todayCategoryExpenseAmount - $todayCategoryPaidAmount;
                                                        if ($todayCategoryDueAmount) {
                                                            echo $settings->currency . number_format_currency($todayCategoryDueAmount, 2);
                                                        } else {
                                                            echo 0;
                                                        } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="2"><?= lang('grand_total'); ?>:</td>
                                                <td>
                                                    <?php if ($todayGrandTotalCategoryExpense) {
                                                        echo $settings->currency . number_format_currency($todayGrandTotalCategoryExpense, 2);
                                                    } else {
                                                        echo 0;
                                                    } ?>
                                                </td>
                                                <td>
                                                    <?php if ($todayGrandTotalCategoryPaid) {
                                                        echo $settings->currency . number_format_currency($todayGrandTotalCategoryPaid, 2);
                                                    } else {
                                                        echo 0;
                                                    } ?>
                                                </td>
                                                <td>
                                                    <?php echo $settings->currency . number_format_currency($todayGrandTotalCategoryExpense - $todayGrandTotalCategoryPaid, 2); ?>
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
                                        <th><?= lang('serialNo'); ?>.</th>
                                        <th><?= lang('category'); ?></th>
                                        <th><?= lang('total_expense_amount'); ?></th>
                                        <th><?= lang('total_paid_amount'); ?></th>
                                        <th><?= lang('total_due_amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial1 = 0;
                                    $totalGrandTotalCategoryExpense = 0;
                                    $totalGrandTotalCategoryPaid = 0;
                                    $totalGrandTotalCategoryDue = 0;
                                    foreach ($expenseCategories->result() as $list1) {
                                        $serial1++;
                                    ?>
                                        <tr class="">
                                            <td><?= $serial1; ?></td>
                                            <td><?= $list1->exc_name; ?></td>
                                            <td><?php $totalCategoryExpenseAmount = $this->report_model->getTotalCategoryWiseExpenseAmount($list1->exc_id);
                                                if ($totalCategoryExpenseAmount) {
                                                    echo $settings->currency . number_format_currency($totalCategoryExpenseAmount, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                $totalGrandTotalCategoryExpense += $totalCategoryExpenseAmount;
                                                ?></td>
                                            <td><?php $totalCategoryPaidAmount = $this->report_model->getTotalCategoryWiseExpensePaidAmount($list1->exc_id);
                                                if ($totalCategoryPaidAmount) {
                                                    echo $settings->currency . number_format_currency($totalCategoryPaidAmount, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                $totalGrandTotalCategoryPaid += $totalCategoryPaidAmount;
                                                ?></td>
                                            <td>
                                                <?php $totalCategoryDueAmount = $totalCategoryExpenseAmount - $totalCategoryPaidAmount;
                                                if ($totalCategoryDueAmount) {
                                                    echo $settings->currency . number_format_currency($totalCategoryDueAmount, 2);
                                                } else {
                                                    echo 0;
                                                } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2"><?= lang('grand_total'); ?>:</td>
                                        <td>
                                            <?php if ($totalGrandTotalCategoryExpense) {
                                                echo $settings->currency . number_format_currency($totalGrandTotalCategoryExpense, 2);
                                            } else {
                                                echo 0;
                                            } ?>
                                        </td>
                                        <td>
                                            <?php if ($totalGrandTotalCategoryPaid) {
                                                echo $settings->currency . number_format_currency($totalGrandTotalCategoryPaid, 2);
                                            } else {
                                                echo 0;
                                            } ?>
                                        </td>
                                        <td>
                                            <?php echo $settings->currency . number_format_currency($totalGrandTotalCategoryExpense - $totalGrandTotalCategoryPaid, 2); ?>
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
                                <form role="form" action="<?php echo base_url('report/viewExpenseCategoryReport'); ?>" method="post" enctype="multipart/form-data">
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
                                                <th><?= lang('serialNo'); ?>.</th>
                                                <th><?= lang('category'); ?></th>
                                                <th><?= lang('total_expense_amount'); ?></th>
                                                <th><?= lang('total_paid_amount'); ?></th>
                                                <th><?= lang('total_due_amount'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $serial2 = 0;
                                            $totalGrandTotalCategoryExpenseYM = 0;
                                            $totalGrandTotalCategoryPaidYM = 0;
                                            foreach ($expenseCategories->result() as $list2) {
                                                $serial2++;
                                            ?>
                                                <tr class="">
                                                    <td><?= $serial2; ?></td>
                                                    <td><?= $list2->exc_name; ?></td>
                                                    <td><?php $totalCategoryExpenseAmountYM = $this->report_model->getYearMonthCategoryWiseExpenseAmount($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $list2->exc_id);
                                                        if ($totalCategoryExpenseAmountYM) {
                                                            echo $settings->currency . number_format_currency($totalCategoryExpenseAmountYM, 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        $totalGrandTotalCategoryExpenseYM += $totalCategoryExpenseAmountYM;
                                                        ?></td>
                                                    <td><?php $totalCategoryPaidAmountYM = $this->report_model->getYearMonthCategoryWiseExpensePaidAmount($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $list2->exc_id);
                                                        if ($totalCategoryPaidAmountYM) {
                                                            echo $settings->currency . number_format_currency($totalCategoryPaidAmountYM, 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        $totalGrandTotalCategoryPaidYM += $totalCategoryPaidAmountYM;
                                                        ?></td>
                                                    <td>
                                                        <?php $totalCategoryDueAmountYM = $totalCategoryExpenseAmountYM - $totalCategoryPaidAmountYM;
                                                        if ($totalCategoryDueAmountYM) {
                                                            echo $settings->currency . number_format_currency($totalCategoryDueAmountYM, 2);
                                                        } else {
                                                            echo 0;
                                                        } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="2"><?= lang('grand_total'); ?>:</td>
                                                <td>
                                                    <?php if ($totalGrandTotalCategoryExpenseYM) {
                                                        echo $settings->currency . number_format_currency($totalGrandTotalCategoryExpenseYM, 2);
                                                    } else {
                                                        echo 0;
                                                    } ?>
                                                </td>
                                                <td>
                                                    <?php if ($totalGrandTotalCategoryPaidYM) {
                                                        echo $settings->currency . number_format_currency($totalGrandTotalCategoryPaidYM, 2);
                                                    } else {
                                                        echo 0;
                                                    } ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $totalGrandTotalCategoryDueYM = $totalGrandTotalCategoryExpenseYM - $totalGrandTotalCategoryPaidYM;
                                                    if ($totalGrandTotalCategoryDueYM) {
                                                        echo $settings->currency . number_format_currency($totalGrandTotalCategoryDueYM, 2);
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
                                <form role="form" action="<?php echo base_url('report/viewExpenseCategoryReport'); ?>" method="post" enctype="multipart/form-data">
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
                                                <th><?= lang('category'); ?></th>
                                                <th><?= lang('total_expense_amount'); ?></th>
                                                <th><?= lang('total_paid_amount'); ?></th>
                                                <th><?= lang('total_due_amount'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $serial3 = 0;
                                            $totalGrandTotalCategoryExpenseDate = 0;
                                            $totalGrandTotalCategoryPaidDate = 0;
                                            foreach ($expenseCategories->result() as $list3) {
                                                $serial3++;
                                            ?>
                                                <tr class="">
                                                    <td><?= $serial3; ?></td>
                                                    <td><?= $list3->exc_name; ?></td>
                                                    <td><?php $totalCategoryExpenseAmountDate = $this->report_model->getDateToDateCategoryWiseExpenseAmount($start_date_value_format, $end_date_value_format, $list3->exc_id);
                                                        if ($totalCategoryExpenseAmountDate) {
                                                            echo $settings->currency . number_format_currency($totalCategoryExpenseAmountDate, 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        $totalGrandTotalCategoryExpenseDate += $totalCategoryExpenseAmountDate;
                                                        ?></td>
                                                    <td><?php $totalCategoryPaidAmountDate = $this->report_model->getDateToDateCategoryWiseExpensePaidAmount($start_date_value_format, $end_date_value_format, $list3->exc_id);
                                                        if ($totalCategoryPaidAmountDate) {
                                                            echo $settings->currency . number_format_currency($totalCategoryPaidAmountDate, 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        $totalGrandTotalCategoryPaidDate += $totalCategoryPaidAmountDate;
                                                        ?></td>
                                                    <td>
                                                        <?php $totalCategoryDueAmountDate = $totalCategoryExpenseAmountDate - $totalCategoryPaidAmountDate;
                                                        if ($totalCategoryDueAmountDate) {
                                                            echo $settings->currency . number_format_currency($totalCategoryDueAmountDate, 2);
                                                        } else {
                                                            echo 0;
                                                        } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="2"><?= lang('grand_total'); ?>:</td>
                                                <td>
                                                    <?php if ($totalGrandTotalCategoryExpenseDate) {
                                                        echo $settings->currency . number_format_currency($totalGrandTotalCategoryExpenseDate, 2);
                                                    } else {
                                                        echo 0;
                                                    } ?>
                                                </td>
                                                <td>
                                                    <?php if ($totalGrandTotalCategoryPaidDate) {
                                                        echo $settings->currency . number_format_currency($totalGrandTotalCategoryPaidDate, 2);
                                                    } else {
                                                        echo 0;
                                                    } ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $totalGrandTotalCategoryDueDate = $totalGrandTotalCategoryExpenseDate - $totalGrandTotalCategoryPaidDate;
                                                    if ($totalGrandTotalCategoryDueDate) {
                                                        echo $settings->currency . number_format_currency($totalGrandTotalCategoryDueDate, 2);
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
                                    <form role="form" action="<?php echo base_url('report/viewExpenseCategoryReport'); ?>" method="post" enctype="multipart/form-data">
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