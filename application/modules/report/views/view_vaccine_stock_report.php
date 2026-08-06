<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <header class="panel-heading bg-info">
            <i class="fa-solid fa-chart-pie"></i> <?= lang('vaccine_reports'); ?>
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
                                        <th><?= lang('vaccine_name'); ?></th>
                                        <th><?= lang('purchase_amount'); ?></th>
                                        <th><?= lang('total_purchased'); ?> </th>
                                        <th><?= lang('total_used'); ?> </th>
                                        <th><?= lang('total_wasted'); ?> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial = 0;
                                    foreach ($vaccines as $vaccine) {
                                        $serial++;
                                    ?>
                                        <tr class="">
                                            <td><?= $serial; ?></td>
                                            <td><?= $vaccine->vcc_name; ?></td>
                                            <td><?php $purchaseAmountToday = $this->report_model->getTodayVaccinePurchase(date('Y-m-d'), $vaccine->vcc_id, 'vpv_total');
                                                if ($purchaseAmountToday) {
                                                    echo $settings->currency . ' ' . $purchaseAmountToday;
                                                }
                                                ?></td>
                                            <td><?php $purchaseVaccineQuantityToday = $this->report_model->getTodayVaccinePurchase(date('Y-m-d'), $vaccine->vcc_id, 'vpv_quantity');
                                                $unit = $this->settings_model->getUnitById($this->vaccine_model->getVaccineById($vaccine->vcc_id)->vcc_unit_id);
                                                if ($purchaseVaccineQuantityToday) {
                                                    if ($unit) {
                                                        echo $purchaseVaccineQuantityToday . ' ' . $unit->un_name;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $usedVaccineQuantityToday = $this->report_model->getTodayVaccineUsed(date('Y-m-d'), $vaccine->vcc_id, 'vds_vaccine_used_quantity');
                                                if ($usedVaccineQuantityToday) {
                                                    if ($usedVaccineQuantityToday) {
                                                        if ($unit) {
                                                            echo ' ' . $unit->un_name;
                                                        }
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $wastedVaccineToday = $this->report_model->getTodayVaccineWaste(date('Y-m-d'), $vaccine->vcc_id, 'vccw_quantity');
                                                if ($wastedVaccineToday) {
                                                    if ($wastedVaccineToday) {
                                                        if ($unit) {
                                                            echo ' ' . $unit->un_name;
                                                        }
                                                    }
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
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
                                        <th><?= lang('vaccine_name'); ?></th>
                                        <th><?= lang('purchase_amount'); ?></th>
                                        <th><?= lang('total_purchased'); ?> </th>
                                        <th><?= lang('total_used'); ?> </th>
                                        <th><?= lang('total_wasted'); ?> </th>
                                        <th><?= lang('in_stock'); ?> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial1 = 0;
                                    foreach ($vaccines as $vaccine1) {
                                        $serial1++;
                                    ?>
                                        <tr class="">
                                            <td><?= $serial1; ?></td>
                                            <td><?= $vaccine1->vcc_name; ?></td>
                                            <td><?php $purchaseAmount = $this->vaccine_model->getVaccineWiseVaccinePurchaseQuantity($vaccine1->vcc_id, 'vpv_total');
                                                if ($purchaseAmount) {
                                                    echo $settings->currency . number_format_currency($purchaseAmount, 2);
                                                }
                                                ?></td>
                                            <td><?php echo $purchaseVaccineQuantity = $this->vaccine_model->getVaccineWiseVaccinePurchaseQuantity($vaccine1->vcc_id, 'vpv_quantity');
                                                $unit = $this->settings_model->getUnitById($this->vaccine_model->getVaccineById($vaccine1->vcc_id)->vcc_unit_id);
                                                if ($purchaseVaccineQuantity) {
                                                    if ($unit) {
                                                        echo ' ' . $unit->un_name;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $usedVaccineQuantity = $this->vaccine_model->getVaccineWiseVaccineUsedQuantity($vaccine1->vcc_id, 'vds_vaccine_used_quantity');
                                                if ($usedVaccineQuantity) {
                                                    if ($usedVaccineQuantity) {
                                                        if ($unit) {
                                                            echo ' ' . $unit->un_name;
                                                        }
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $wastedVaccine = $this->vaccine_model->getVaccineWastedByVaccineId($vaccine1->vcc_id, 'vccw_quantity');
                                                if ($wastedVaccine) {
                                                    if ($wastedVaccine) {
                                                        if ($unit) {
                                                            echo ' ' . $unit->un_name;
                                                        }
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $inStockVaccine = $purchaseVaccineQuantity - $usedVaccineQuantity - $wastedVaccine;
                                                if ($inStockVaccine) {
                                                    if ($inStockVaccine) {
                                                        if ($unit) {
                                                            echo ' ' . $unit->un_name;
                                                        }
                                                    }
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.Today Report -->

                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- ====================== Month Wise Report ====================== -->
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
                                <form role="form" action="<?php echo base_url('report/viewVaccineStockReport'); ?>" method="post" enctype="multipart/form-data">
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
                                                <th><?= lang('vaccine_name'); ?></th>
                                                <th><?= lang('purchase_amount'); ?></th>
                                                <th><?= lang('total_purchased'); ?> </th>
                                                <th><?= lang('total_used'); ?> </th>
                                                <th><?= lang('total_wasted'); ?> </th>
                                                <th><?= lang('in_stock'); ?> </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $serial3 = 0;
                                            $purchaseAmountMonthTotal = 0;
                                            foreach ($vaccines as $vaccine3) {
                                                $serial3++;
                                            ?>
                                                <tr class="">
                                                    <td><?= $serial3; ?></td>
                                                    <td><?= $vaccine3->vcc_name; ?></td>
                                                    <td><?php $purchaseAmountMonth = $this->report_model->getMonthWiseVaccinePurchaseQuantity($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $vaccine3->vcc_id, 'vpv_total');
                                                        if ($purchaseAmountMonth) {
                                                            echo $settings->currency . number_format_currency($purchaseAmountMonth, 2);
                                                        }
                                                        $purchaseAmountMonthTotal += $purchaseAmountMonth;
                                                        ?></td>
                                                    <td><?php echo $purchaseVaccineQuantityMonth = $this->report_model->getMonthWiseVaccinePurchaseQuantity($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $vaccine3->vcc_id, 'vpv_quantity');
                                                        $unit = $this->settings_model->getUnitById($this->vaccine_model->getVaccineById($vaccine3->vcc_id)->vcc_unit_id);
                                                        if ($purchaseVaccineQuantityMonth) {
                                                            if ($unit) {
                                                                echo ' ' . $unit->un_name;
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $usedVaccineQuantityMonth = $this->report_model->getMonthWiseVaccineUsed($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $vaccine3->vcc_id, 'vds_vaccine_used_quantity');
                                                        if ($usedVaccineQuantityMonth) {
                                                            if ($usedVaccineQuantityMonth) {
                                                                if ($unit) {
                                                                    echo ' ' . $unit->un_name;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $wastedVaccineMonth = $this->report_model->getMonthWiseVaccineWaste($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $vaccine3->vcc_id, 'vccw_quantity');
                                                        if ($wastedVaccineMonth) {
                                                            if ($wastedVaccineMonth) {
                                                                if ($unit) {
                                                                    echo ' ' . $unit->un_name;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $inStockVaccineMonth = $purchaseVaccineQuantityMonth - $usedVaccineQuantityMonth - $wastedVaccineMonth;
                                                        if ($inStockVaccineMonth) {
                                                            if ($inStockVaccineMonth) {
                                                                if ($unit) {
                                                                    echo ' ' . $unit->un_name;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="2"><?= lang('total'); ?>:</td>
                                                <td> <?php
                                                        if ($purchaseAmountMonthTotal) {
                                                            echo $settings->currency . number_format_currency($purchaseAmountMonthTotal, 2);
                                                        }
                                                        ?></td>
                                                <td colspan="4"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.Date to Date Report View -->
                        </div>
                        <div class="col-md-6">

                            <!-- ====================== Date WIse Report ====================== -->
                            <div class="row filtering_heading_text">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <h4><?= lang('date_to_date_filtering'); ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="row sale_report_filter">
                                <form role="form" action="<?php echo base_url('report/viewVaccineStockReport'); ?>" method="post" enctype="multipart/form-data">
                                    <div class="col-xs-2 date_text">
                                        <span class=""> <?= lang('start_date'); ?>:</span> <br>
                                        <span class=""> <?= $start_date_value; ?></span>
                                    </div>
                                    <div class="col-xs-2 date_text">
                                        <span class=""><?= lang('end_date'); ?>:</span><br>
                                        <span class=""> <?= $end_date_value; ?></span>
                                    </div>
                                    <div class="col-xs-5 ">
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
                            <!-- Date Wise View -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th><?= lang('serialNo'); ?>.</th>
                                                <th><?= lang('vaccine_name'); ?></th>
                                                <th><?= lang('purchase_amount'); ?></th>
                                                <th><?= lang('total_purchased'); ?> </th>
                                                <th><?= lang('total_used'); ?> </th>
                                                <th><?= lang('total_wasted'); ?> </th>
                                                <th><?= lang('in_stock'); ?> </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $serial2 = 0;
                                            $purchaseAmountTotal = 0;
                                            foreach ($vaccines as $vaccine2) {
                                                $serial2++;
                                            ?>
                                                <tr class="">
                                                    <td><?= $serial2; ?></td>
                                                    <td><?= $vaccine2->vcc_name; ?></td>
                                                    <td><?php $purchaseAmountDate = $this->report_model->getStartDateEndDateWiseVaccinePurchaseAmount($start_date_value_format, $end_date_value_format, $vaccine2->vcc_id, 'vpv_total');
                                                        if ($purchaseAmountDate) {
                                                            echo $settings->currency . number_format_currency($purchaseAmountDate, 2);
                                                        }
                                                        $purchaseAmountTotal += $purchaseAmountDate;
                                                        ?></td>
                                                    <td><?php echo $purchaseVaccineQuantityDate = $this->report_model->getStartDateEndDateWiseVaccinePurchaseQuantity($start_date_value_format, $end_date_value_format, $vaccine2->vcc_id, 'vpv_quantity');
                                                        $unit = $this->settings_model->getUnitById($this->vaccine_model->getVaccineById($vaccine2->vcc_id)->vcc_unit_id);
                                                        if ($purchaseVaccineQuantityDate) {
                                                            if ($unit) {
                                                                echo ' ' . $unit->un_name;
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $usedVaccineQuantityDate = $this->report_model->getStartDateEndDateWiseVaccineUsed($start_date_value_format, $end_date_value_format, $vaccine2->vcc_id, 'vds_vaccine_used_quantity');
                                                        if ($usedVaccineQuantityDate) {
                                                            if ($unit) {
                                                                echo ' ' . $unit->un_name;
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $wastedVaccineDate = $this->report_model->getStartDateEndDateWiseVaccineWaste($start_date_value_format, $end_date_value_format, $vaccine2->vcc_id, 'vccw_quantity');
                                                        if ($wastedVaccineDate) {
                                                            if ($unit) {
                                                                echo ' ' . $unit->un_name;
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $inStockVaccineDate = $purchaseVaccineQuantityDate - $usedVaccineQuantityDate - $wastedVaccineDate;
                                                        if ($inStockVaccineDate) {
                                                            if ($unit) {
                                                                echo ' ' . $unit->un_name;
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="2"><?= lang('total'); ?>:</td>
                                                <td> <?php
                                                        if ($purchaseAmountTotal) {
                                                            echo $settings->currency . number_format_currency($purchaseAmountTotal, 2);
                                                        }
                                                        ?></td>
                                                <td colspan="4"></td>
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
                            <!-- ====================== Year And Month Wise Report ====================== -->
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
                                    <form role="form" action="<?php echo base_url('report/viewVaccineStockReport'); ?>" method="post" enctype="multipart/form-data">
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

                                        // Amount
                                        echo "<td>";
                                        $amount = $this->report_model->getYearMonthWiseVaccinePurchase($alpha_y, $alpha_m, 'vps_grand_total');
                                        if ($amount) {
                                            echo $settings->currency . number_format_currency($amount, 2);
                                        }
                                        echo "</td>";
                                        // Bill
                                        echo "<td>";
                                        $totalBillCount = $this->report_model->getYearMonthWiseVaccinePurchaseBill($alpha_y, $alpha_m, 'vps_id');
                                        if ($totalBillCount) {
                                            echo $totalBillCount;
                                        }
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                    ?>

                                    <tr class="bg-warning">
                                        <td><?= lang('total'); ?></td>
                                        <td><?php
                                            $YearAmount = $this->report_model->getYearWiseVaccinePurchase($alpha_y, 'vps_grand_total');
                                            if ($YearAmount) {
                                                echo $settings->currency . number_format_currency($YearAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                        <td>
                                            <?php
                                            $YearBill = $this->report_model->getYearWiseVaccinePurchaseBill($alpha_y, 'vps_id');
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