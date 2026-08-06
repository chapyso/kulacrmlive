<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <header class="panel-heading bg-info">
            <i class="fa-solid fa-chart-pie"></i> <?= lang('production_reports'); ?>
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
                                        <th><?= lang('product_name'); ?></th>
                                        <th><?= lang('category'); ?></th>
                                        <th><?= lang('production'); ?></th>
                                        <th><?= lang('sold'); ?></th>
                                        <th><?= lang('waste'); ?></th>
                                        <th><?= lang('in_stock'); ?></th>
                                        <th><?= lang('sold_amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial = 0;
                                    foreach ($products->result() as $product) {
                                        $serial++;
                                    ?>
                                        <tr>
                                            <td><?= $serial ?></td>
                                            <td> <?= $product->pr_name; ?></td>
                                            <td> <?= $this->product_model->getProductCategoryById($product->pr_prc_id)->prc_name; ?></td>
                                            <td>
                                                <?php echo $totalProductionToday = $this->report_model->getTodayProductionQuantityByProductId(date('Y-m-d'), $product->pr_id);
                                                $unit = $this->settings_model->getUnitById($product->pr_unit_id);
                                                if ($totalProductionToday) {
                                                    echo ' ' . $unit->un_name;
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $totalSoldProductionToday = $this->report_model->getTodayProductSoldQuantityByProductId(date('Y-m-d'), $product->pr_id, 'prsv_quantity');
                                                if ($totalSoldProductionToday) {
                                                    if ($unit) {
                                                        echo  ' ' . $unit->un_name;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo $totalWastedProductionToday = $this->report_model->getTodayProductWastedQuantityByProductId(date('Y-m-d'), $product->pr_id);
                                                if ($totalWastedProductionToday) {
                                                    if ($unit) {
                                                        echo  ' ' . $unit->un_name;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td><?php
                                                echo $productInStockToday = $totalProductionToday - $totalSoldProductionToday - $totalWastedProductionToday;
                                                if ($productInStockToday) {
                                                    if ($unit) {
                                                        echo  ' ' . $unit->un_name;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td><?php $totalSoldProductionAmountToday = $this->report_model->getTodayProductWiseTotalAmount(date('Y-m-d'), $product->pr_id, 'prsv_total');
                                                if ($totalSoldProductionAmountToday) {
                                                    echo $settings->currency . ' ' . number_format_currency($totalSoldProductionAmountToday, 2);
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="4"> </td>
                                        <td colspan="3">
                                            <p><?= lang('purs_grand_total'); ?>:</p>
                                        </td>
                                        <td>
                                            <p><?php $SoldAmountToday = $this->report_model->getTodayProductSoldAmount(date('Y-m-d'), 'prss_grand_total');
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
                                        <th><?= lang('serialNo'); ?>.</th>
                                        <th><?= lang('product_name'); ?></th>
                                        <th><?= lang('category'); ?></th>
                                        <th><?= lang('production'); ?></th>
                                        <th><?= lang('sold'); ?></th>
                                        <th><?= lang('waste'); ?></th>
                                        <th><?= lang('in_stock'); ?></th>
                                        <th><?= lang('sold_amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial1 = 0;
                                    foreach ($products->result() as $product1) {
                                        $serial1++;
                                    ?>
                                        <tr class="">
                                            <td><?= $serial1 ?></td>
                                            <td> <?= $product1->pr_name; ?></td>
                                            <td> <?= $this->product_model->getProductCategoryById($product1->pr_prc_id)->prc_name; ?></td>
                                            <td>
                                                <?php echo $totalProduction = $this->product_model->getTotalProductionQuantityByProductId($product1->pr_id);
                                                $unit = $this->settings_model->getUnitById($product1->pr_unit_id);
                                                if ($totalProduction) {
                                                    echo ' ' . $unit->un_name;
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $totalSoldProduction = $this->sale_model->getProductSoldQuantityByProductId($product1->pr_id, 'prsv_quantity');
                                                if ($totalSoldProduction) {
                                                    if ($unit) {
                                                        echo  ' ' . $unit->un_name;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo $totalWastedProduction = $this->product_model->getTotalProductWastedQuantityByProductId($product1->pr_id);
                                                if ($totalWastedProduction) {
                                                    if ($unit) {
                                                        echo  ' ' . $unit->un_name;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td><?php
                                                echo $productInStock = $totalProduction - $totalSoldProduction - $totalWastedProduction;
                                                if ($productInStock) {
                                                    if ($unit) {
                                                        echo  ' ' . $unit->un_name;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td><?php $totalSoldProductionAmount = $this->sale_model->getProductSoldQuantityByProductId($product1->pr_id, 'prsv_total');
                                                if ($totalSoldProductionAmount) {
                                                    echo $settings->currency . ' ' . number_format_currency($totalSoldProductionAmount, 2);
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="4"> </td>
                                        <td colspan="3">
                                            <p><?= lang('purs_grand_total'); ?>:</p>
                                        </td>
                                        <td>
                                            <p><?php $SoldAmount = $this->report_model->getTotalProductSoldAmount('prss_grand_total');
                                                if ($SoldAmount) {
                                                    echo $settings->currency . ' ' . number_format_currency($SoldAmount, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?>
                                            </p>
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
                                <form role="form" action="<?php echo base_url('report/viewProductStockReport'); ?>" method="post" enctype="multipart/form-data">
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
                                                <th><?= lang('serialNo'); ?></th>
                                                <th><?= lang('product_name'); ?></th>
                                                <th><?= lang('category'); ?></th>
                                                <th><?= lang('production'); ?></th>
                                                <th><?= lang('sold'); ?></th>
                                                <th><?= lang('waste'); ?></th>
                                                <th><?= lang('in_stock'); ?></th>
                                                <th><?= lang('sold_amount'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $serial2 = 0;
                                            foreach ($products->result() as $product2) {
                                                $serial2++;
                                            ?>
                                                <tr class="">
                                                    <td><?= $serial2 ?></td>
                                                    <td> <?= $product2->pr_name; ?></td>
                                                    <td> <?= $this->product_model->getProductCategoryById($product2->pr_prc_id)->prc_name; ?></td>
                                                    <td>
                                                        <?php echo $totalProductionYM = $this->report_model->getYearMonthWiseProductionQuantityByProductId($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $product2->pr_id);
                                                        $unit = $this->settings_model->getUnitById($product2->pr_unit_id);
                                                        if ($totalProductionYM) {
                                                            echo ' ' . $unit->un_name;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $totalSoldProductionYM = $this->report_model->getYearMonthWiseProductSoldQuantityByProductId($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $product2->pr_id, 'prsv_quantity');
                                                        if ($totalSoldProductionYM) {
                                                            if ($unit) {
                                                                echo  ' ' . $unit->un_name;
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $totalWastedProductionYM = $this->report_model->getYearMonthWiseProductWastedQuantityByProductId($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $product2->pr_id);
                                                        if ($totalWastedProductionYM) {
                                                            if ($unit) {
                                                                echo  ' ' . $unit->un_name;
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php
                                                        echo $productInStockYM = $totalProductionYM - $totalSoldProductionYM - $totalWastedProductionYM;
                                                        if ($productInStockYM) {
                                                            if ($unit) {
                                                                echo  ' ' . $unit->un_name;
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php $totalSoldProductionAmount = $this->report_model->getYearMonthWiseProductSoldQuantityByProductId($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, $product2->pr_id, 'prsv_total');
                                                        if ($totalSoldProductionAmount) {
                                                            echo $settings->currency . number_format_currency($totalSoldProductionAmount, 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="4"> </td>
                                                <td colspan="3">
                                                    <p><?= lang('purs_grand_total'); ?>:</p>
                                                </td>
                                                <td>
                                                    <p><?php $SoldAmountYM = $this->report_model->getYearMonthWiseProductSoldAmount($yearMonth_wise_filer_year, $yearMonth_wise_filer_month, 'prss_grand_total');
                                                        if ($SoldAmountYM) {
                                                            echo $settings->currency .  number_format_currency($SoldAmountYM, 2);
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
                                <form role="form" action="<?php echo base_url('report/viewProductStockReport'); ?>" method="post" enctype="multipart/form-data">
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
                                                <th><?= lang('product_name'); ?></th>
                                                <th><?= lang('category'); ?></th>
                                                <th><?= lang('production'); ?></th>
                                                <th><?= lang('sold'); ?></th>
                                                <th><?= lang('waste'); ?></th>
                                                <th><?= lang('in_stock'); ?></th>
                                                <th><?= lang('sold_amount'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $serial3 = 0;
                                            foreach ($products->result() as $product3) {
                                                $serial3++;
                                            ?>
                                                <tr class="">
                                                    <td><?= $serial3 ?></td>
                                                    <td> <?= $product3->pr_name; ?></td>
                                                    <td> <?= $this->product_model->getProductCategoryById($product3->pr_prc_id)->prc_name; ?></td>
                                                    <td>
                                                        <?php echo $totalProductionDate = $this->report_model->getDateToDateWiseProductionQuantityByProductId($start_date_value_format, $end_date_value_format, $product3->pr_id);
                                                        $unit = $this->settings_model->getUnitById($product3->pr_unit_id);
                                                        if ($totalProductionDate) {
                                                            echo ' ' . $unit->un_name;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $totalSoldProductionDate = $this->report_model->getDateToDateWiseProductSoldQuantityByProductId($start_date_value_format, $end_date_value_format, $product3->pr_id, 'prsv_quantity');
                                                        if ($totalSoldProductionDate) {
                                                            if ($unit) {
                                                                echo  ' ' . $unit->un_name;
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $totalWastedProductionDate = $this->report_model->getDateToDateWiseProductWastedQuantityByProductId($start_date_value_format, $end_date_value_format, $product3->pr_id);
                                                        if ($totalWastedProductionDate) {
                                                            if ($unit) {
                                                                echo  ' ' . $unit->un_name;
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php
                                                        echo $productInStockDate = $totalProductionDate - $totalSoldProductionDate - $totalWastedProductionDate;
                                                        if ($productInStockDate) {
                                                            if ($unit) {
                                                                echo  ' ' . $unit->un_name;
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php $totalSoldProductionAmount = $this->report_model->getDateToDateWiseProductSoldQuantityByProductId($start_date_value_format, $end_date_value_format, $product3->pr_id, 'prsv_total');
                                                        if ($totalSoldProductionAmount) {
                                                            echo $settings->currency . ' ' . number_format_currency($totalSoldProductionAmount, 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="4"> </td>
                                                <td colspan="3">
                                                    <p><?= lang('purs_grand_total'); ?>:</p>
                                                </td>
                                                <td>
                                                    <p><?php $SoldAmountDate = $this->report_model->getDateToDateWiseProductSoldAmount($start_date_value_format, $end_date_value_format, 'prss_grand_total');
                                                        if ($SoldAmountDate) {
                                                            echo $settings->currency . ' ' . number_format_currency($SoldAmountDate, 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?></p>
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
                                        $amount = $this->report_model->getYearMonthWiseProductSoldAmount($alpha_y, $alpha_m, 'prss_grand_total');
                                        if ($amount) {
                                            echo  $settings->currency . number_format_currency($amount, 2);
                                        }
                                        echo "</td>";
                                        // Bill
                                        echo "<td>";
                                        $totalBillCount = $this->report_model->getYearMonthWiseProductWiseSoldBill($alpha_y, $alpha_m, 'prss_id');
                                        if ($totalBillCount) {
                                            echo $totalBillCount;
                                        }
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                    ?>

                                    <tr class="bg-warning">
                                        <td>Total</td>
                                        <td><?php
                                            $YearAmount = $this->report_model->getYearWiseProductSoldAmount($alpha_y, 'prss_grand_total');
                                            if ($YearAmount) {
                                                echo  $settings->currency . number_format_currency($YearAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                        <td>
                                            <?php
                                            $YearBill = $this->report_model->getYearWiseProductWiseSoldBill($alpha_y, 'prss_id');
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