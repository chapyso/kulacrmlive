<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel">

            <header class="panel-heading bg-info">
                <i class="fa-solid fa-chart-pie"></i> <?= lang('livestock_sale_reports'); ?>

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
                                    $todayTotalSaleQuantity = 0;
                                    $todayTotalSaleAmount = 0;
                                    foreach ($livestocks as $livestock) {
                                        $serial++;
                                    ?>
                                        <tr class="">
                                            <td> <?= $serial; ?></td>
                                            <td> <?php echo $livestock->ls_name; ?></td>
                                            <td><?php $todayLivestockSaleQuantity = $this->report_model->getTodaySaleQuantity(date('Y-m-d'), $livestock->ls_id, 'lssv_quantity');
                                                if ($todayLivestockSaleQuantity) {
                                                    echo  $todayLivestockSaleQuantity;
                                                } else {
                                                    echo 0;
                                                }
                                                $todayTotalSaleQuantity += $todayLivestockSaleQuantity;
                                                ?></td>
                                            <td><?php $todayLivestockSaleAmount = $this->report_model->getTodaySaleQuantity(date('Y-m-d'), $livestock->ls_id, 'lssv_total');
                                                if ($todayLivestockSaleAmount) {
                                                    echo $settings->currency .  number_format_currency($todayLivestockSaleAmount, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                $todayTotalSaleAmount += $todayLivestockSaleAmount;
                                                ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2">
                                            <div class="text-center">
                                                <p><?= lang('sale_quantity'); ?></p>
                                                <p> <?php
                                                    if ($todayTotalSaleQuantity) {
                                                        echo $todayTotalSaleQuantity;
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></p>
                                                <p><?= lang('sale_bill'); ?></p>
                                                <p><?php $totalSaleBill = $this->report_model->getCountRow('livestock_sale_summary', 'lsss_id', ['DATE(lsss_date)' => date("Y-m-d"), 'lsss_status' => 1]);
                                                    if ($totalSaleBill) {
                                                        echo $totalSaleBill;
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
                                            <p><?php
                                                if ($todayTotalSaleAmount) {
                                                    echo $settings->currency .  number_format_currency($todayTotalSaleAmount, 2);
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
                                        <th><?php echo lang('serialNo'); ?></th>
                                        <th><?php echo lang('livestock_id'); ?></th>
                                        <th><?php echo lang('total_purchased'); ?> (<?= $settings->unit ?>)</th>
                                        <th><?= lang('amount'); ?> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial1 = 0;
                                    $totalSaleQuantity = 0;
                                    $totalSaleAmount = 0;
                                    foreach ($livestocks as $livestock1) {
                                        $serial1++;
                                    ?>
                                        <tr>
                                            <td> <?= $serial1; ?></td>
                                            <td> <?php echo $livestock1->ls_name; ?></td>
                                            <td><?php $totalLivestockSaleQuantity = $this->report_model->getTotalSaleQuantity($livestock1->ls_id, 'lssv_quantity');
                                                if ($totalLivestockSaleQuantity) {
                                                    echo  $totalLivestockSaleQuantity;
                                                } else {
                                                    echo 0;
                                                }
                                                $totalSaleQuantity += $totalLivestockSaleQuantity;
                                                ?></td>
                                            <td><?php $totalLivestockSaleAmount = $this->report_model->getTotalSaleQuantity($livestock1->ls_id, 'lssv_total');
                                                if ($totalLivestockSaleAmount) {
                                                    echo $settings->currency .  number_format_currency($totalLivestockSaleAmount, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                $totalSaleAmount += $totalLivestockSaleAmount;
                                                ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2">
                                            <div class="text-center">
                                                <p><?= lang('sale_quantity'); ?> </p>
                                                <p> <?php
                                                    if ($totalSaleQuantity) {
                                                        echo $totalSaleQuantity;
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></p>
                                                <p><?= lang('sale_bill'); ?> </p>
                                                <p><?php $totalSaleBill = $this->report_model->getCountRow('livestock_sale_summary', 'lsss_id', ['lsss_status' => 1]);
                                                    if ($totalSaleBill) {
                                                        echo $totalSaleBill;
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
                                            <p><?php
                                                if ($totalSaleAmount) {
                                                    echo $settings->currency .  number_format_currency($totalSaleAmount, 2);
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
                            <div class="row filtering_heading_text ">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <h4><?= lang('month_wise_filtering'); ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="row purchase_report_filter">
                                <form role="form" action="<?php echo base_url('report/viewLivestockSaleReport'); ?>" method="post" enctype="multipart/form-data">
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
                                        <th><?php echo lang('serialNo'); ?></th>
                                        <th><?php echo lang('livestock_id'); ?></th>
                                        <th><?php echo lang('total_purchased'); ?> (<?= $settings->unit ?>)</th>
                                        <th><?= lang('amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial2 = 0;
                                    $totalSaleQuantityYM = 0;
                                    $totalSaleAmountYM = 0;
                                    foreach ($livestocks as $livestock2) {
                                        $serial2++;
                                    ?>
                                        <tr>
                                            <td> <?= $serial2; ?></td>
                                            <td> <?php echo $livestock2->ls_name; ?></td>
                                            <td><?php $totalLivestockSaleQuantityYM = $this->report_model->getYearMonthWiseSaleQuantity($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $livestock2->ls_id, 'lssv_quantity');
                                                if ($totalLivestockSaleQuantityYM) {
                                                    echo  $totalLivestockSaleQuantityYM;
                                                } else {
                                                    echo 0;
                                                }
                                                $totalSaleQuantityYM += $totalLivestockSaleQuantityYM;
                                                ?></td>
                                            <td><?php $totalLivestockSaleAmountYM = $this->report_model->getYearMonthWiseSaleQuantity($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $livestock2->ls_id, 'lssv_total');
                                                if ($totalLivestockSaleAmountYM) {
                                                    echo $settings->currency .  number_format_currency($totalLivestockSaleAmountYM, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                $totalSaleAmountYM += $totalLivestockSaleAmountYM;
                                                ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2">
                                            <div class="text-center">
                                                <p><?= lang('sale_quantity'); ?></p>
                                                <p> <?php
                                                    if ($totalSaleQuantityYM) {
                                                        echo $totalSaleQuantityYM;
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></p>
                                                <p><?= lang('sale_bill'); ?></p>
                                                <p><?php $totalSaleBillYM = $this->report_model->getCountRow('livestock_sale_summary', 'lsss_id', ['lsss_status' => 1, 'YEAR(lsss_date)' => $yearMonth_wise_filer_year, 'MONTH(lsss_date)' => $yearMonth_wise_filer_month]);
                                                    if ($totalSaleBillYM) {
                                                        echo $totalSaleBillYM;
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
                                            <p><?php
                                                if ($totalSaleAmountYM) {
                                                    echo $settings->currency .  number_format_currency($totalSaleAmountYM, 2);
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
                                <form role="form" action="<?php echo base_url('report/viewLivestockSaleReport'); ?>" method="post" enctype="multipart/form-data">
                                    <div class="col-xs-2">
                                        <span class=""> <?= lang('start_date'); ?>:</span> <br>
                                        <span class=""> <?= $start_date_value; ?></span>
                                    </div>
                                    <div class="col-xs-2">
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
                                        <th><?= lang('serialNo'); ?></th>
                                        <th><?= lang('livestock_id'); ?></th>
                                        <th><?= lang('total_purchased'); ?> (<?= $settings->unit ?>)</th>
                                        <th><?= lang('amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial3 = 0;
                                    $totalSaleQuantityDate = 0;
                                    $totalSaleAmountDate = 0;
                                    foreach ($livestocks as $livestock3) {
                                        $serial3++;
                                    ?>
                                        <tr>
                                            <td> <?= $serial3; ?></td>
                                            <td> <?php echo $livestock3->ls_name; ?></td>
                                            <td><?php $totalLivestockSaleQuantityDate = $this->report_model->getStartDateEndDateWiseSaleQuantity($start_date_value_format, $end_date_value_format, $livestock3->ls_id, 'lssv_quantity');
                                                if ($totalLivestockSaleQuantityDate) {
                                                    echo  $totalLivestockSaleQuantityDate;
                                                } else {
                                                    echo 0;
                                                }
                                                $totalSaleQuantityDate += $totalLivestockSaleQuantityDate;
                                                ?></td>
                                            <td><?php $totalLivestockSaleAmountDate = $this->report_model->getYearMonthWiseSaleQuantity($start_date_value_format, $end_date_value_format, $livestock3->ls_id, 'lssv_total');
                                                if ($totalLivestockSaleAmountDate) {
                                                    echo $settings->currency .  number_format_currency($totalLivestockSaleAmountDate, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                $totalSaleAmountDate += $totalLivestockSaleAmountDate;
                                                ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2">
                                            <div class="text-center">
                                                <p><?= lang('sale_quantity'); ?></p>
                                                <?php
                                                if ($totalSaleQuantityDate) {
                                                    echo $totalSaleQuantityDate;
                                                } else {
                                                    echo 0;
                                                }
                                                ?>
                                                <p><?= lang('sale_bill'); ?></p>
                                                <p><?php $totalSaleBillDate = $this->report_model->getCountRow('livestock_sale_summary', 'lsss_id', ['lsss_status' => 1, 'DATE(lsss_date)>=' => $start_date_value_format, 'DATE(lsss_date)<=' => $end_date_value_format]);
                                                    if ($totalSaleBillDate) {
                                                        echo $totalSaleBillDate;
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
                                            <p><?php
                                                if ($totalSaleAmountDate) {
                                                    echo $settings->currency .  number_format_currency($totalSaleAmountDate, 2);
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
                                        <div class="col-xs-2 date_text">
                                            <span class="filter_font"> <?= lang('year'); ?>:</span>
                                        </div>
                                        <div class="col-xs-2 date_text">
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
                                        <th> <?= lang('purchase_amount'); ?> (<?= $settings->currency; ?>)</th>
                                        <th> <?= lang('purchase_bill'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $yearlySaleQuantity = 0;
                                    $yearlySaleAmount = 0;
                                    $yearlySaleBill = 0;
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
                                        $quantity = $this->report_model->getYearWiseSaleQuantity($alpha_y, $alpha_m, 'lssv_quantity');
                                        if ($quantity) {
                                            echo $quantity;
                                        }
                                        $yearlySaleQuantity += $quantity;
                                        echo "</td>";

                                        // Amount
                                        echo "<td>";
                                        $amount = $this->report_model->getYearMonthWiseSaleAmount($alpha_y, $alpha_m, 'lsss_grand_total');
                                        if ($amount) {
                                            echo $settings->currency . number_format_currency($amount, 2);
                                        }
                                        $yearlySaleAmount += $amount;
                                        echo "</td>";
                                        // Bill
                                        echo "<td>";
                                        $totalBillCount = $this->report_model->getCountRow('livestock_sale_summary', 'lsss_id', ['lsss_status' => 1, 'YEAR(lsss_date)' => $alpha_y, 'MONTH(lsss_date)' => $alpha_m]);
                                        if ($totalBillCount) {
                                            echo $totalBillCount;
                                        }
                                        $yearlySaleBill += $totalBillCount;
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                    ?>

                                    <tr class=" bg-warning">
                                        <td><?= lang('total'); ?></td>
                                        <td><?php
                                            if ($yearlySaleQuantity) {
                                                echo $yearlySaleQuantity .  $settings->unit;
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                        <td><?php
                                            if ($yearlySaleAmount) {
                                                echo  $settings->currency . number_format_currency($yearlySaleAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                        <td>
                                            <?php
                                            if ($yearlySaleBill) {
                                                echo $yearlySaleBill;
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

                    <div class="row display_hide">
                        <div class="col-md-12">
                            <!-- ============== Shed and batch wise filtering ============== -->
                            <div class="row filtering_heading_text">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <h4><?= lang('shed_and_batch_filtering'); ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="row sale_report_filter">
                                <form role="form" action="<?php echo base_url('report/viewLivestockSaleReport'); ?>" method="post" enctype="multipart/form-data">
                                    <div class="col-xs-8">
                                        <div class="form-group col-xs-6">
                                            <label for="exampleInputEmail1"><?= lang('shed'); ?></label>
                                            <select name="shed_id" id="shed_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                                <option value=""><?= lang('please_select_shed'); ?></option>
                                                <?php if (!empty($sheds)) foreach ($sheds as $shed) {
                                                ?>
                                                    <option value="<?php echo $shed->sh_id ?>"><?php echo $shed->sh_no ?>: <?php echo $shed->sh_title ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label for="exampleInputEmail1"><?= lang('assigned_batch'); ?></label>
                                            <select name="batch_id" id="batch_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                                <option value=""><?= lang('please_select_batch'); ?></option>
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
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="shed_wise_report">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <?php
                                                if (!empty($shed_id) && !empty($batch_id)) { ?>
                                                    <table class="table">
                                                        <tr>
                                                            <td><?= lang('shed'); ?>:</td>
                                                            <td>
                                                                <?php echo $this->shed_model->getShedById($shed_id)->sh_no ?>: <?= $this->shed_model->getShedById($shed_id)->sh_title; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><?= lang('batch'); ?>:</td>
                                                            <td>
                                                                <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($shed_id, $batch_id)->lshs_batch_id ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($shed_id, $batch_id)->lshs_batch_title ?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                <?php  } ?>
                                            </div>
                                            <div class="col-xs-4">
                                                <h3><?= lang('quantity'); ?> (<?= $settings->unit; ?>) </h3>
                                                <span><?php $shedBatchQ = $this->report_model->getShedAndBatchWiseSale($shed_id, $batch_id, 'lssv_quantity');
                                                        if ($shedBatchQ) {
                                                            echo $shedBatchQ;
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?>
                                                </span>
                                            </div>
                                            <div class="col-xs-4">
                                                <h3><?= lang('amount'); ?> (<?php echo $settings->currency; ?>) </h3>
                                                <span><?php $shedBatchA = $this->report_model->getShedAndBatchWiseSale($shed_id, $batch_id, 'lssv_total');
                                                        if ($shedBatchA) {
                                                            echo $shedBatchA;
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
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





<!-- Javascript For Edit Trip -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#shed_id').on('change', function() {
            var iid = $(this).val();
            if (iid != '') {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('food/getBatchByShedId'); ?>',
                    data: {
                        shed_id: iid
                    },
                    success: function(data) {
                        $('#batch_id').html(data);
                    }
                });
            }
        });
    });
</script>