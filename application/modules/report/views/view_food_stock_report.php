<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <header class="panel-heading bg-info">
            <i class="fa-solid fa-chart-pie"></i> <?= lang('food_reports'); ?>
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
                                        <th><?= lang('food_name'); ?></th>
                                        <th><?= lang('total_purchased'); ?></th>
                                        <th><?= lang('distribute'); ?> </th>
                                        <th><?= lang('wasted'); ?> </th>
                                        <th><?= lang('total_purchased_amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $serial = 0;
                                    foreach ($foods as $food) {
                                        $serial++;
                                    ?>
                                        <tr class="">
                                            <td> <?= $serial ?></td>
                                            <td> <?= $food->fds_food_title ?></td>
                                            <td><?php echo $purchaseFood = $this->report_model->getTodayFoodWisePurchaseQuantity(date('Y-m-d'), $food->fds_id, 'fdpv_quantity');
                                                $unit = $this->settings_model->getUnitById($food->fds_unit_id);
                                                if ($purchaseFood) {
                                                    if ($unit) {
                                                        echo ' ' . $unit->un_name;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $distributeFood = $this->report_model->getTodayFoodWiseDistributeQuantity(date('Y-m-d'), $food->fds_id);
                                                if ($distributeFood) {
                                                    if ($unit) {
                                                        echo ' ' . $unit->un_name;
                                                    }
                                                }
                                                ?></td>
                                            <td><?php echo $wastedFood = $this->report_model->getTodayFoodWiseWasteQuantity(date('Y-m-d'), $food->fds_id);
                                                if ($wastedFood) {
                                                    if ($unit) {
                                                        echo ' ' . $unit->un_name;
                                                    }
                                                }
                                                ?></td>
                                            <td><?php $purchaseFood = $this->report_model->getTodayFoodWisePurchaseAmount(date('Y-m-d'), $food->fds_id, 'fdpv_total');
                                                if ($purchaseFood) {
                                                    echo $settings->currency;
                                                    echo ' ' . $purchaseFood;
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
                                        <th><?= lang('food_name'); ?></th>
                                        <th><?= lang('total_purchased_amount'); ?></th>
                                        <th><?= lang('total_purchased'); ?></th>
                                        <th><?= lang('distribute'); ?> </th>
                                        <th><?= lang('wasted'); ?> </th>
                                        <th><?= lang('in_stock'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $serial1 = 0;
                                    foreach ($foods as $food1) {
                                        $serial1++;
                                    ?>
                                        <tr class="">
                                            <td> <?= $serial1 ?></td>
                                            <td> <?= $food1->fds_food_title ?></td>
                                            <td><?php $purchaseTotalFoodAmount = $this->report_model->getTotalFoodWisePurchaseAmount($food1->fds_id, 'fdpv_total');
                                                if ($purchaseTotalFoodAmount) {
                                                    echo $settings->currency;
                                                    echo ' ' . $purchaseTotalFoodAmount;
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $purchaseTotalFood = $this->report_model->getTotalFoodWisePurchaseQuantity($food1->fds_id, 'fdpv_quantity');
                                                $unit = $this->settings_model->getUnitById($food1->fds_unit_id);
                                                if ($purchaseTotalFood) {
                                                    if ($unit) {
                                                        echo ' ' . $unit->un_name;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $distributeTotalFood = $this->report_model->getTotalFoodWiseDistributeQuantity($food1->fds_id);
                                                if ($distributeTotalFood) {
                                                    if ($unit) {
                                                        echo ' ' . $unit->un_name;
                                                    }
                                                }
                                                ?></td>
                                            <td><?php echo $wastedTotalFood = $this->report_model->getTotalFoodWiseWasteQuantity($food1->fds_id);
                                                if ($wastedTotalFood) {
                                                    if ($unit) {
                                                        echo ' ' . $unit->un_name;
                                                    }
                                                }
                                                ?></td>
                                            <td>
                                                <?php $foodInStock = $purchaseTotalFood - $distributeTotalFood - $wastedTotalFood;
                                                if ($foodInStock) {
                                                    echo $foodInStock;
                                                    if ($unit) {
                                                        echo ' ' . $unit->un_name;
                                                    }
                                                } else {
                                                    echo "<i class='fa fa-frown-o text-danger' aria-hidden='true'></i>";
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
                        <!-- ====================== Year And Month Wise Report ====================== -->
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
                                <form role="form" action="<?php echo base_url('report/viewFoodStockReport'); ?>" method="post" enctype="multipart/form-data">
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
                            <!-- Date Wise View -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th><?= lang('serialNo'); ?>.</th>
                                                <th><?= lang('food_name'); ?></th>
                                                <th><?= lang('total_purchased'); ?></th>
                                                <th><?= lang('distribute'); ?> </th>
                                                <th><?= lang('wasted'); ?> </th>
                                                <th><?= lang('total_purchased_amount'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $serial3 = 0;
                                            foreach ($foods as $food3) {
                                                $serial3++;
                                            ?>
                                                <tr class="">
                                                    <td> <?= $serial3 ?></td>
                                                    <td> <?= $food3->fds_food_title ?></td>
                                                    <td><?php echo $purchaseFood = $this->report_model->getStartDateEndDateWiseFoodPurchase($start_date_value_format, $end_date_value_format, $food3->fds_id, 'fdpv_quantity');
                                                        $unit = $this->settings_model->getUnitById($food->fds_unit_id);
                                                        if ($purchaseFood) {
                                                            if ($unit) {
                                                                echo ' ' . $unit->un_name;
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $distributeFood = $this->report_model->getStartDateEndDateWiseFoodDistribute($start_date_value_format, $end_date_value_format, $food3->fds_id, 'fddv_distributed_quantity');
                                                        if ($distributeFood) {
                                                            if ($unit) {
                                                                echo ' ' . $unit->un_name;
                                                            }
                                                        }
                                                        ?></td>
                                                    <td><?php echo $wastedFood = $this->report_model->getStartDateEndDateWiseWastedFood($start_date_value_format, $end_date_value_format, $food3->fds_id, 'fdw_quantity');
                                                        if ($wastedFood) {
                                                            if ($unit) {
                                                                echo ' ' . $unit->un_name;
                                                            }
                                                        }
                                                        ?></td>
                                                    <td><?php $purchaseFood = $this->report_model->getStartDateEndDateWiseFoodPurchase($start_date_value_format, $end_date_value_format, $food3->fds_id, 'fdpv_total');
                                                        if ($purchaseFood) {
                                                            echo $settings->currency;
                                                            echo ' ' . $purchaseFood;
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="5"></td>
                                                <td>Total:
                                                    <?php $amountEDTotal = $this->report_model->getStartDateEndDateWiseFoodPurchaseTotalAmount($start_date_value_format, $end_date_value_format, 'fdps_grand_total');
                                                    if ($amountEDTotal) {
                                                        echo $settings->currency;
                                                        echo ' ' . $amountEDTotal;
                                                    } ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.Date to Date Report View -->
                            </div>
                        </div>
                        <!-- ====================== Date to Date Report ====================== -->
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
                                <form role="form" action="<?php echo base_url('report/viewFoodStockReport'); ?>" method="post" enctype="multipart/form-data">
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
                                                <th><?= lang('food_name'); ?></th>
                                                <th><?= lang('total_purchased'); ?></th>
                                                <th><?= lang('distribute'); ?> </th>
                                                <th><?= lang('wasted'); ?> </th>
                                                <th><?= lang('total_purchased_amount'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $serial2 = 0;
                                            foreach ($foods as $food2) {
                                                $serial2++;
                                            ?>
                                                <tr class="">
                                                    <td> <?= $serial2 ?></td>
                                                    <td> <?= $food2->fds_food_title ?></td>
                                                    <td><?php echo $purchaseFood = $this->report_model->getYearMonthWiseFoodPurchaseQuantity($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $food2->fds_id, 'fdpv_quantity');
                                                        $unit = $this->settings_model->getUnitById($food->fds_unit_id);
                                                        if ($purchaseFood) {
                                                            if ($unit) {
                                                                echo ' ' . $unit->un_name;
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $distributeFood = $this->report_model->getYearMonthWiseFoodDistributeQuantity($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $food2->fds_id, 'fddv_distributed_quantity');
                                                        if ($distributeFood) {
                                                            if ($unit) {
                                                                echo ' ' . $unit->un_name;
                                                            }
                                                        }
                                                        ?></td>
                                                    <td><?php echo $wastedFood = $this->report_model->getYearMonthWiseWastedFood($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $food2->fds_id, 'fdw_quantity');
                                                        if ($wastedFood) {
                                                            if ($unit) {
                                                                echo ' ' . $unit->un_name;
                                                            }
                                                        }
                                                        ?></td>
                                                    <td><?php $purchaseFood = $this->report_model->getYearMonthWiseFoodPurchaseQuantity($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $food2->fds_id, 'fdpv_total');
                                                        if ($purchaseFood) {
                                                            echo $settings->currency;
                                                            echo ' ' . $purchaseFood;
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="5"></td>
                                                <td><?= lang('total'); ?>:
                                                    <?php $amountTotal = $this->report_model->getYearMonthWiseFoodPurchase($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, 'fdps_grand_total');
                                                    if ($amountTotal) {
                                                        echo $settings->currency;
                                                        echo ' ' . $amountTotal;
                                                    } ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.Date to Date Report View -->

                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row filtering_heading_text">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <h4><?= lang('yearly_food_purchase_amount_filtering'); ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="space15">
                                <div class="row sale_report_filter">
                                    <!-- Yearly Search -->
                                    <form role="form" action="<?php echo base_url('report/viewFoodStockReport'); ?>" method="post" enctype="multipart/form-data">
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
                                        $amount = $this->report_model->getYearMonthWiseFoodPurchase($alpha_y, $alpha_m, 'fdps_grand_total');
                                        if ($amount) {
                                            echo  $settings->currency;
                                            echo "&nbsp";
                                            echo $amount;
                                        }
                                        echo "</td>";
                                        // Bill
                                        echo "<td>";
                                        $totalBillCount = $this->report_model->getYearMonthWiseFoodPurchaseBill($alpha_y, $alpha_m, 'fdps_id');
                                        if ($totalBillCount) {
                                            echo $totalBillCount;
                                        }
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                    ?>

                                    <tr class="bg-warning">
                                        <td><?= lang('serialNo'); ?>Total</td>
                                        <td><?php
                                            $YearAmount = $this->report_model->getYearWiseFoodPurchase($alpha_y, 'fdps_grand_total');
                                            if ($YearAmount) {
                                                echo  $settings->currency;
                                                echo "&nbsp";
                                                echo $YearAmount;
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                        <td>
                                            <?php
                                            $YearBill = $this->report_model->getYearWiseFoodPurchaseBill($alpha_y, 'fdps_id');
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