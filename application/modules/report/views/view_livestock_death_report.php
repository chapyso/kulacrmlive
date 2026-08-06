<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel">

            <header class="panel-heading bg-info">
                <i class="fa-solid fa-chart-pie"></i> <?= lang('death_reports'); ?>
                <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
            </header>

            <div class="panel-body">
                <div class="adv-table editable-table">

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
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial = 0;
                                    $todayTotalDeath = 0;
                                    foreach ($livestocks as $livestock) {
                                        $serial++;
                                    ?>
                                        <tr class="">
                                            <td> <?= $serial; ?></td>
                                            <td> <?php echo $livestock->ls_name; ?></td>
                                            <td><?php $todayLivestockDeathQuantity = $this->report_model->getSumData('livestock_death_quantity', 'ld_death_quantity', ['ld_death_date' => date('Y-m-d'), 'ld_purv_ls_id' => $livestock->ls_id, 'ld_status' => 1]);
                                                if ($todayLivestockDeathQuantity) {
                                                    echo $todayLivestockDeathQuantity;
                                                }
                                                $todayTotalDeath += $todayLivestockDeathQuantity;
                                                ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td class="text-center" colspan="2">
                                            <?= lang('grand_total'); ?>:
                                        </td>
                                        <td>
                                            <?php if ($todayTotalDeath) {
                                                echo $todayTotalDeath . ' ' . $settings->unit;
                                            } ?>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial1 = 0;
                                    $totalDeath = 0;
                                    foreach ($livestocks as $livestock1) {
                                        $serial1++;
                                    ?>
                                        <tr class="">
                                            <td> <?= $serial1; ?></td>
                                            <td> <?php echo $livestock1->ls_name; ?></td>
                                            <td><?php $totalLivestockDeathQuantity = $this->report_model->getSumData('livestock_death_quantity', 'ld_death_quantity', ['ld_purv_ls_id' => $livestock1->ls_id, 'ld_status' => 1]);
                                                if ($totalLivestockDeathQuantity) {
                                                    echo  $totalLivestockDeathQuantity;
                                                }
                                                $totalDeath += $totalLivestockDeathQuantity;
                                                ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td class="text-center" colspan="2">
                                            <?= lang('grand_total'); ?>:
                                        </td>
                                        <td>
                                            <?php if ($totalDeath) {
                                                echo $totalDeath . ' ' . $settings->unit;
                                            } ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

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
                                <form role="form" action="<?php echo base_url('report/viewLivestockDeathReport'); ?>" method="post" enctype="multipart/form-data">
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
                                                <option value=""><?= lang('please_select_month'); ?></option>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial2 = 0;
                                    $totalDeathYM = 0;
                                    foreach ($livestocks as $livestock2) {
                                        $serial2++;
                                    ?>
                                        <tr class="">
                                            <td> <?= $serial2; ?></td>
                                            <td> <?php echo $livestock2->ls_name; ?></td>
                                            <td><?php
                                                $LivestockDeathQuantityYM = $this->report_model->getSumData('livestock_death_quantity', 'ld_death_quantity', ['ld_purv_ls_id' => $livestock2->ls_id, 'ld_status' => 1, 'YEAR(ld_death_date)' => $yearMonth_wise_filer_year, 'MONTH(ld_death_date)' => $yearMonth_wise_filer_month,]);
                                                if ($LivestockDeathQuantityYM) {
                                                    echo  $LivestockDeathQuantityYM;
                                                }
                                                $totalDeathYM += $LivestockDeathQuantityYM;
                                                ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td class="text-center" colspan="2">
                                            <?= lang('grand_total'); ?>:
                                        </td>
                                        <td>
                                            <?php if ($totalDeathYM) {
                                                echo $totalDeathYM . ' ' . $settings->unit;
                                            } ?>
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
                                <form role="form" action="<?php echo base_url('report/viewLivestockDeathReport'); ?>" method="post" enctype="multipart/form-data">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial3 = 0;
                                    $totalDeathDate = 0;
                                    foreach ($livestocks as $livestock3) {
                                        $serial3++;
                                    ?>
                                        <tr class="">
                                            <td> <?= $serial3; ?></td>
                                            <td> <?php echo $livestock3->ls_name; ?></td>
                                            <td><?php
                                                $LivestockDeathQuantityDate = $this->report_model->getSumData('livestock_death_quantity', 'ld_death_quantity', ['ld_purv_ls_id' => $livestock3->ls_id, 'ld_status' => 1, 'DATE(ld_death_date)>=' => $start_date_value_format, 'DATE(ld_death_date)<=' => $end_date_value_format]);
                                                if ($LivestockDeathQuantityDate) {
                                                    echo  $LivestockDeathQuantityDate;
                                                }
                                                $totalDeathDate += $LivestockDeathQuantityDate;
                                                ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td class="text-center" colspan="2">
                                            <?= lang('grand_total'); ?>:
                                        </td>
                                        <td>
                                            <?php if ($totalDeathDate) {
                                                echo $totalDeathDate . ' ' . $settings->unit;
                                            } ?>
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
                                    <form role="form" action="<?php echo base_url('report/viewLivestockDeathReport'); ?>" method="post" enctype="multipart/form-data">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $yearMonthDeath = 0;
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
                                        $quantity = $this->report_model->getSumData('livestock_death_quantity', 'ld_death_quantity', ['ld_status' => 1, 'YEAR(ld_death_date)' => $alpha_y, 'MONTH(ld_death_date)' => $alpha_m,]);
                                        if ($quantity) {
                                            echo $quantity;
                                        }
                                        $yearMonthDeath += $quantity;
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                    ?>

                                    <tr class="bg-primary-light">
                                        <td><?= lang('total'); ?></td>
                                        <td>
                                            <?php
                                            if ($yearMonthDeath) {
                                                echo $yearMonthDeath . ' ' . $settings->unit;
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