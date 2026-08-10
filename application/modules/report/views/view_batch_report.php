<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading bg-info">
                    <i class="fa-solid fa-chart-pie"></i> <?= lang('batch_reports'); ?>
                    <button class="export" type="button" onclick="KulaAIChat.open('Explain this batch report, mortality trends, feed usage, and batch performance.')" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); color: #ffffff; margin-right: 8px; border: none; border-radius: 6px; padding: 4px 10px; font-weight: 700; cursor: pointer;"><i class="fa-solid fa-wand-magic-sparkles"></i> Explain with KulaAI</button>
                    <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                </header>

                <div class="panel-body">
                    <div class="row noprint">
                        <form role="form" action="<?php echo base_url('report/viewBatchAnalysisReport'); ?>" method="post" enctype="multipart/form-data">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?= lang('shed'); ?></label>
                                    <select name="shed_id" id="shed_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                        <option value=""><?= lang('please_select_shed'); ?></option>
                                        <?php if (!empty($sheds)) foreach ($sheds as $shed) {
                                        ?>
                                            <option value="<?php echo $shed->sh_id ?>"><?php echo $shed->sh_no ?>: <?php echo $shed->sh_title ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?= lang('batch'); ?></label>
                                    <select name="batch_id" id="batch_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                        <option value=""><?= lang('please_select_batch'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <section class="text-left search__button">
                                    <button type="submit" name="submit" class="button button-info submit_button report__search__button" value="submit"><i class="fas fa-search"></i> <?= lang('search'); ?></button>
                                </section>
                            </div>
                        </form>
                    </div>
                    <?php if ($LivestockInfo && $LivestockTypeInfo && $shed_id && $batch_id) { ?>
                        <!-- Live information -->
                        <div class="row ">
                            <div class="col-xs-12">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr class="table__header">
                                            <th colspan="6" class="text-center"><?= lang('basic_information'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td width="50%">
                                                <p><?= lang('shed'); ?>: <?= $this->shed_model->getShedById($shed_id)->sh_no ?>: <?= $this->shed_model->getShedById($shed_id)->sh_title ?> </p>
                                                <p><?= lang('batch'); ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($shed_id, $batch_id)->lshs_batch_id ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($shed_id, $batch_id)->lshs_batch_title ?></p>
                                                <p><?= lang('livestock_name'); ?>: <?= $LivestockInfo->ls_name; ?></p>
                                                <p><?= lang('livestock_variant'); ?>: <?= $LivestockTypeInfo->lst_title; ?></p>
                                                <p><?= lang('batch_status'); ?>:
                                                    <?php
                                                    $assignedSummaryInfo = $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($shed_id, $batch_id);
                                                    if ($assignedSummaryInfo->lshs_active_status == 0) { ?>
                                                        <button class="button bg-info-light"><i class="fa-solid fa-person-running"></i> <?= lang('running'); ?></button>
                                                    <?php } else { ?>
                                                        <button class="button bg-success-light"><i class="fa-solid fa-circle-check"></i> <?= lang('completed'); ?></button>
                                                    <?php } ?>
                                                </p>

                                                <!-- If This baches livestocks are reproductive from another batch then view this info -->
                                                <?php $livestockReproductionInformation = $this->report_model->getSingleData('live_assigned_shed_summary', ['lshs_sh_id' => $shed_id, 'lshs_batch_id' => $batch_id, 'lshs_status' => 1]);
                                                ?>
                                                <?php if ($livestockReproductionInformation) {
                                                    $livestockReproductionInformationByReproductionId = $this->report_model->getSingleData('livestock_reproduction', ['lrp_id' => $livestockReproductionInformation->lshs_reproduction_id, 'lrp_status' => 1]);
                                                    if ($livestockReproductionInformationByReproductionId) {
                                                ?>
                                                        <hr>
                                                        <p class="text-center"><strong><?= lang('reproduction'); ?> <?= lang('from'); ?></strong></p>
                                                        <p><?= lang('shed'); ?>: <?= $this->shed_model->getShedById($livestockReproductionInformationByReproductionId->lrp_sh_id)->sh_no ?>: <?= $this->shed_model->getShedById($livestockReproductionInformationByReproductionId->lrp_sh_id)->sh_title ?> </p>
                                                        <p><?= lang('batch'); ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($livestockReproductionInformationByReproductionId->lrp_sh_id, $livestockReproductionInformationByReproductionId->lrp_batch_id)->lshs_batch_id ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($livestockReproductionInformationByReproductionId->lrp_sh_id, $livestockReproductionInformationByReproductionId->lrp_batch_id)->lshs_batch_title ?></p>
                                                <?php }
                                                } ?>
                                                <!-- /.If This baches livestocks are reproductive from another batch then view this info   -->

                                            </td>
                                            <td width="50%">
                                                <p> <?= lang('assigned_quantity'); ?>: <?php $assignedQuantity = $this->sale_model->getShedAndBatchWiseLivestockPurchaseQuantity($shed_id, $batch_id);
                                                                                        if ($assignedQuantity) {
                                                                                            echo $assignedQuantity;
                                                                                        } else {
                                                                                            echo 0;
                                                                                        }
                                                                                        ?> <?= $settings->unit ?></p>
                                                <p><?= lang('sold_quantity'); ?>: <?php $soldQuantity = $this->sale_model->getShedAndBatchWiseLivestockSaleQuantity($shed_id, $batch_id, $LivestockInfo->ls_id, $LivestockTypeInfo->lst_id);
                                                                                    if ($soldQuantity) {
                                                                                        echo $soldQuantity;
                                                                                    } else {
                                                                                        echo 0;
                                                                                    }
                                                                                    ?> <?= $settings->unit ?></p>
                                                <p> <?= lang('death_quantity'); ?>: <?php $deathQuantity = $this->sale_model->getShedAndBatchWiseLivestockDeathQuantity($shed_id, $batch_id, $LivestockInfo->ls_id, $LivestockTypeInfo->lst_id);
                                                                                    if ($deathQuantity) {
                                                                                        echo $deathQuantity;
                                                                                    } else {
                                                                                        echo 0;
                                                                                    }
                                                                                    ?> <?= $settings->unit ?></p>
                                                <p><?= lang('in_stock'); ?>: <?php echo $assignedQuantity - ($soldQuantity + $deathQuantity); ?> <?= $settings->unit ?></p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Assign And Purchase Details -->
                        <div class="row ">
                            <div class="col-md-12">
                                <table class="table  table-hover table-bordered">
                                    <thead>
                                        <?php if ($livestockReproductionInformationByReproductionId) {
                                        ?>
                                            <tr class="bg-primary-light">
                                                <th colspan="6" class="text-center"><?= lang('livestock_reproduction_and_assigned_status'); ?></th>
                                            </tr>
                                        <?php } else { ?>
                                            <tr class="table__header">
                                                <th colspan="6" class="text-center"><?= lang('livestock_purchase_and_assigned_status'); ?></th>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <th width="5%"><?= lang('serialNo'); ?></th>
                                            <th width="19%"><?= lang('assigned_date'); ?> </th>
                                            <th width="19%"><?= lang('assigned_quantity'); ?> (<?= $settings->unit ?>)</th>
                                            <th width="19%"><?= lang('unit_price'); ?></th>
                                            <th width="19%"><?= lang('discount'); ?></th>
                                            <th width="19%"><?= lang('total_amount'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $serial4 = 0;
                                        $grandTotalPurchaseQuantity = 0;
                                        $grandTotalPurchaseDiscountAmount = 0;
                                        $grandTotalPurchaseAmount = 0;
                                        foreach ($assignedLiveStocks as $value4) {
                                            $serial4++;
                                        ?>
                                            <tr>
                                                <td width="5%"><?= $serial4 ?></td>
                                                <td width="19%"><?php
                                                                $assignSummaryById = $this->purchase_model->getAssignedSummaryInfoById($value4->lsh_lshs_id);

                                                                // Reproduction Data
                                                                $reproductionDataByAssignedReproductionId = $this->report_model->getSingleData('livestock_reproduction', ['lrp_id' => $assignSummaryById->lshs_reproduction_id, 'lrp_status' => 1]);

                                                                echo date("$settings->date_format", strtotime($assignSummaryById->lshs_assign_date)) ?></td>

                                                <td width="19%">
                                                    <?php echo $livestockAssignQuantity = $value4->lsh_assign_quantity;
                                                    $grandTotalPurchaseQuantity += $value4->lsh_assign_quantity;
                                                    ?>
                                                </td>
                                                <td width="19%"><?php

                                                                if ($assignSummaryById->lshs_reproduction_id == 0 && $assignSummaryById->lshs_reproduction_status == 0 && $assignSummaryById->lshs_type == 1) {
                                                                    $livePurchaseValueById = $this->purchase_model->getLivestockPurchaseValueById($value4->lsh_purv_id);
                                                                    echo $settings->currency . number_format_currency($livePurchaseValueById->purv_unit_price, 2);
                                                                } elseif ($assignSummaryById->lshs_type == 2) {
                                                                    echo $settings->currency . number_format_currency($reproductionDataByAssignedReproductionId->lrp_approx_selling_price, 2);
                                                                } else {
                                                                    echo lang('no_data_found');
                                                                }
                                                                ?></td>
                                                <td width="19%"><?php
                                                                if ($assignSummaryById->lshs_reproduction_id == 0 && $assignSummaryById->lshs_reproduction_status == 0 && $assignSummaryById->lshs_type == 1) {
                                                                    $totalPurchaseDiscountAmount = $livePurchaseValueById->purv_discount;
                                                                    if ($totalPurchaseDiscountAmount) {
                                                                        echo $settings->currency . number_format_currency($totalPurchaseDiscountAmount, 2);
                                                                    }
                                                                    $grandTotalPurchaseDiscountAmount += $totalPurchaseDiscountAmount;
                                                                }
                                                                ?></td>
                                                <td width="19%"><?php
                                                                if ($assignSummaryById->lshs_reproduction_id == 0 && $assignSummaryById->lshs_reproduction_status == 0 && $assignSummaryById->lshs_type == 1) {
                                                                    $totalPurchaseAmount = $livestockAssignQuantity * $livePurchaseValueById->purv_unit_price;
                                                                    echo $settings->currency . number_format_currency(($totalPurchaseAmount - $totalPurchaseDiscountAmount), 2);
                                                                    $grandTotalPurchaseAmount += $totalPurchaseAmount;
                                                                } elseif ($assignSummaryById->lshs_type == 2) {
                                                                    echo $settings->currency . number_format_currency($reproductionDataByAssignedReproductionId->lrp_approx_selling_price * $livestockAssignQuantity, 2);
                                                                    $grandTotalPurchaseAmount += $reproductionDataByAssignedReproductionId->lrp_approx_selling_price * $livestockAssignQuantity;
                                                                } else {
                                                                    echo $settings->currency . number_format_currency(0, 2);
                                                                }
                                                                ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr class="table__row">
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <?php if ($grandTotalPurchaseQuantity) {
                                                    echo $grandTotalPurchaseQuantity;
                                                } ?>
                                            </td>
                                            <td></td>
                                            <td> <?php
                                                    if ($grandTotalPurchaseDiscountAmount) {
                                                        echo $grandTotalPurchaseDiscountAmount;
                                                    }
                                                    ?></td>
                                            <td> <?php echo $settings->currency . number_format_currency($grandTotalPurchaseAmount, 2); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Sold Details -->
                        <div class="row ">
                            <div class="col-md-12">
                                <table class="table  table-hover table-bordered">
                                    <thead>
                                        <tr class="table__header">
                                            <th colspan="6" class="text-center"><?= lang('livestock_sold_status'); ?></th>
                                        </tr>
                                        <tr>
                                            <th width="5%"><?= lang('serialNo'); ?></th>
                                            <th width="19%"><?= lang('sold_date'); ?> </th>
                                            <th width="19%"><?= lang('sold_quantity'); ?> (<?= $settings->unit ?>)</th>
                                            <th width="19%"><?= lang('unit_price'); ?></th>
                                            <th width="19%"><?= lang('discount'); ?></th>
                                            <th width="19%"><?= lang('total_amount'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $serial = 0;
                                        $grandTotalSoldQuantity = 0;
                                        $grandTotalSoldDiscountAmount = 0;
                                        $grandTotalSoldAmount = 0;
                                        foreach ($livestockSoldValueByShedBatch as $value) {
                                            $serial++;
                                        ?>
                                            <tr>
                                                <td width="5%"><?= $serial ?></td>
                                                <td width="19%"><?= date("$settings->date_format", strtotime($value->lssv_created_at)) ?></td>
                                                <td width="19%"><?php
                                                                echo $value->lssv_quantity;
                                                                $grandTotalSoldQuantity += $value->lssv_quantity;

                                                                ?></td>
                                                <td width="19%"><?php echo $settings->currency . number_format_currency($value->lssv_unit_price, 2); ?></td>
                                                <td width="19%"><?php $grandTotalSoldDiscountAmount = $value->lssv_discount;
                                                                if ($grandTotalSoldDiscountAmount) {
                                                                    echo $settings->currency . number_format_currency($grandTotalSoldDiscountAmount, 2);
                                                                }
                                                                ?></td>
                                                <td width="19%"><?php
                                                                echo $settings->currency . number_format_currency($value->lssv_total, 2);
                                                                $grandTotalSoldAmount += $value->lssv_total;
                                                                ?></td>
                                            </tr>
                                        <?php } ?>
                                        <tr class="table__row">
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <?php
                                                if ($grandTotalSoldQuantity) {
                                                    echo $grandTotalSoldQuantity;
                                                }
                                                ?>
                                            </td>
                                            <td></td>
                                            <td> <?php if ($grandTotalSoldDiscountAmount) {
                                                        echo $settings->currency . number_format_currency($grandTotalSoldDiscountAmount, 2);
                                                    } ?></td>
                                            <td> <?php
                                                    echo $settings->currency . number_format_currency($grandTotalSoldAmount, 2);
                                                    ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Food Details -->
                        <div class="row ">
                            <div class="col-md-12">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr class="table__header">
                                            <th colspan="6" class="text-center"><?= lang('food_status'); ?></th>
                                        </tr>
                                        <tr>
                                            <th width="5%"><?= lang('serialNo'); ?> </th>
                                            <th width="19%"><?= lang('food_name'); ?> </th>
                                            <th width="19%"><?= lang('distributed_quantity'); ?> </th>
                                            <th width="19%"><?= lang('unit_price'); ?></th>
                                            <th width="19%"><?= lang('discount'); ?></th>
                                            <th width="19%"><?= lang('total_amount'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $serial1 = 0;
                                        $grandTotalAverageDiscountAmount = 0;
                                        $grandTotalAverageAmount = 0;
                                        foreach ($assignedFoodList as $value1) {
                                            $serial1++;
                                        ?>
                                            <tr>
                                                <td width="5%"><?= $serial1 ?></td>
                                                <td width="19%"><?php $foodInfo = $this->food_model->getFoodSummaryById($value1->fdv_fds_id);
                                                                if ($foodInfo) {
                                                                    echo $foodInfo->fds_food_title;
                                                                    $unit = $this->settings_model->getUnitById($foodInfo->fds_unit_id);
                                                                }

                                                                ?></td>
                                                <td width="19%"><?php echo $distributedFood = $this->report_model->getDistributedFoodByShedAndBatch($shed_id, $batch_id, $value1->fdv_fds_id, 'fddv_distributed_quantity');
                                                                if ($distributedFood) {
                                                                    if ($unit) {
                                                                        echo ' ' . $unit->un_name;
                                                                    }
                                                                }
                                                                ?></td>
                                                <td width="19%"><?php
                                                                if ($distributedFood) {
                                                                    $foodWisePurchaseDiscount = $this->report_model->getTotalFoodWisePurchaseQuantity($foodInfo->fds_id, 'fdpv_discount');
                                                                    $foodWisePurchaseAmount = $this->report_model->getTotalFoodWisePurchaseQuantity($foodInfo->fds_id, 'fdpv_total') + $foodWisePurchaseDiscount;
                                                                    $foodWiseQuantity = $this->report_model->getTotalFoodWisePurchaseQuantity($foodInfo->fds_id, 'fdpv_quantity');
                                                                    $averageUnitPrice = $foodWisePurchaseAmount / $foodWiseQuantity;
                                                                    if ($averageUnitPrice) {
                                                                        echo $settings->currency . number_format_currency($averageUnitPrice, 2);
                                                                    }
                                                                }
                                                                ?></td>
                                                <td width="19%"><?php if ($distributedFood) {
                                                                    $averageDiscountAmount = $foodWisePurchaseDiscount / $foodWiseQuantity;
                                                                    if ($averageDiscountAmount) {
                                                                        echo $settings->currency . number_format_currency($averageDiscountAmount, 2);
                                                                    }
                                                                    $grandTotalAverageDiscountAmount += $averageDiscountAmount;
                                                                }
                                                                ?></td>
                                                <td width="19%"><?php
                                                                if ($distributedFood) {
                                                                    $foodWiseExpenseAmount = ($distributedFood * $averageUnitPrice) - $averageDiscountAmount;
                                                                    if ($foodWiseExpenseAmount) {
                                                                        echo $settings->currency . number_format_currency($foodWiseExpenseAmount, 2);
                                                                    }
                                                                    $grandTotalAverageAmount += $foodWiseExpenseAmount;
                                                                }
                                                                ?></td>
                                            </tr>
                                        <?php } ?>
                                        <tr class="table__row">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td> <?php if ($grandTotalAverageDiscountAmount) {
                                                        echo $settings->currency . number_format_currency($grandTotalAverageDiscountAmount, 2);
                                                    } ?></td>
                                            <td> <?php
                                                    echo $settings->currency . number_format_currency($grandTotalAverageAmount, 2);
                                                    ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Vaccine Details -->
                        <div class="row ">
                            <div class="col-md-12">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr class="table__header">
                                            <th colspan="6" class="text-center"><?= lang('vaccine_status'); ?></th>
                                        </tr>
                                        <tr>
                                            <th width="5%"><?= lang('serialNo'); ?> </th>
                                            <th width="19%"><?= lang('vaccine_name'); ?> </th>
                                            <th width="19%"><?= lang('vaccination_quantity'); ?> </th>
                                            <th width="19%"><?= lang('unit_price'); ?></th>
                                            <th width="19%"><?= lang('discount'); ?></th>
                                            <th width="19%"><?= lang('total_amount'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $serial2 = 0;
                                        $vaccineWiseGrandTotalDiscount = 0;
                                        $vaccineWiseGrandTotalAmount = 0;
                                        foreach ($vaccines as $value2) {
                                            $serial2++;
                                        ?>
                                            <tr>
                                                <td width="5%"><?= $serial2 ?></td>
                                                <td width="19%"><?php $vaccineById = $this->vaccine_model->getVaccineById($value2->vds_vcc_id);
                                                                echo $vaccineById->vcc_name;
                                                                ?></td>
                                                <td width="19%"><?php $vaccinationQuantity = $this->report_model->getVaccinatedQuantityByVaccineShedBatchId($shed_id, $batch_id,  $vaccineById->vcc_id, 'vds_vaccine_used_quantity');
                                                                $unit = $this->settings_model->getUnitById($vaccineById->vcc_unit_id);
                                                                if ($vaccinationQuantity) {
                                                                    if ($unit) {
                                                                        echo $vaccinationQuantity . ' ' . $unit->un_name;
                                                                    }
                                                                }
                                                                ?></td>
                                                <td width="19%">
                                                    <?php
                                                    if ($vaccinationQuantity) {

                                                        $vaccineWiseDiscountAmount = $this->vaccine_model->getVaccineWiseVaccinePurchaseQuantity($vaccineById->vcc_id, 'vpv_discount');
                                                        $vaccineWiseTotalPurchaseAmount = $this->vaccine_model->getVaccineWiseVaccinePurchaseQuantity($vaccineById->vcc_id, 'vpv_total') + $vaccineWiseDiscountAmount;

                                                        $vaccineWiseUnitQuantity = $this->vaccine_model->getVaccineWiseVaccinePurchaseQuantity($vaccineById->vcc_id, 'vpv_quantity');

                                                        $avgUnitPrice = 0;
                                                        if ($vaccinationQuantity) {
                                                            $averageVaccineWiseUnitPrice = $vaccineWiseTotalPurchaseAmount / $vaccineWiseUnitQuantity;
                                                            echo $settings->currency . number_format_currency($averageVaccineWiseUnitPrice, 2);
                                                            $avgUnitPrice = $averageVaccineWiseUnitPrice;
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td width="19%">
                                                    <?php
                                                    if ($vaccinationQuantity) {
                                                        $avgDiscountAmount = 0;
                                                        if ($vaccineWiseDiscountAmount) {
                                                            $averageVaccineWiseDiscountAmount = $vaccineWiseDiscountAmount / $vaccineWiseUnitQuantity;
                                                            echo $settings->currency . number_format_currency($averageVaccineWiseDiscountAmount, 2);
                                                            $avgDiscountAmount = $averageVaccineWiseDiscountAmount;
                                                        }
                                                        $vaccineWiseGrandTotalDiscount += $avgDiscountAmount;
                                                    }
                                                    ?>
                                                </td>
                                                <td width="19%">
                                                    <?php $vaccineWiseTotalAmount = ($vaccinationQuantity * $avgUnitPrice) - $avgDiscountAmount;
                                                    if ($vaccineWiseTotalAmount) {
                                                        echo $settings->currency . number_format_currency($vaccineWiseTotalAmount, 2);
                                                    }
                                                    $vaccineWiseGrandTotalAmount += $vaccineWiseTotalAmount;
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr class="table__row">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><?php if ($vaccineWiseGrandTotalDiscount) {
                                                    echo $settings->currency . number_format_currency($vaccineWiseGrandTotalDiscount, 2);
                                                } ?></td>
                                            <td><?php
                                                echo $settings->currency . number_format_currency($vaccineWiseGrandTotalAmount, 2);
                                                ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Production Details -->
                        <div class="row ">
                            <div class="col-md-12">
                                <table class="table  table-hover table-bordered">
                                    <thead>
                                        <tr class="table__header">
                                            <th colspan="6" class="text-center"><?= lang('production_status'); ?></th>
                                        </tr>
                                        <tr>
                                            <th width="5%"><?= lang('serialNo'); ?></th>
                                            <th width="19%"><?= lang('product_name'); ?> </th>
                                            <th width="19%"><?= lang('production_quantity'); ?> </th>
                                            <th width="19%"><?= lang('sold_quantity'); ?> </th>
                                            <th width="19%"><?= lang('discount'); ?></th>
                                            <th width="19%"><?= lang('total_amount'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $serial3 = 0;
                                        $grandTotalProductSaleDiscountAmount = 0;
                                        $grandTotalProductSaleAmount = 0;
                                        foreach ($assignedProducts as $value3) {
                                            $serial3++;
                                        ?>
                                            <tr>
                                                <td width="5%"><?= $serial3 ?></td>
                                                <td width="19%"><?php $productById = $this->product_model->getProductById($value3->pra_pr_id);
                                                                if ($productById) {
                                                                    echo $productById->pr_name;
                                                                }
                                                                ?></td>
                                                <td width="19%"><?php $productionQuantity = $this->product_model->getTotalProductionQuantityByShedAndBatchId($shed_id, $batch_id);
                                                                $unit = $this->settings_model->getUnitById($productById->pr_unit_id);
                                                                if ($productionQuantity) {
                                                                    if ($unit) {
                                                                        echo $productionQuantity . ' ' . $unit->un_name;
                                                                    }
                                                                }
                                                                ?></td>
                                                <td width="19%"><?php $totalSold = $this->sale_model->getProductAssignedWiseProductSaleValue($value3->pra_id, 'prsv_quantity');
                                                                $unit = $this->settings_model->getUnitById($productById->pr_unit_id);
                                                                if ($totalSold) {
                                                                    if ($unit) {
                                                                        echo $totalSold . ' ' . $unit->un_name;
                                                                    }
                                                                }
                                                                ?></td>
                                                <td width="19%">
                                                    <?php $totalSoldDiscount = $this->sale_model->getProductAssignedWiseProductSaleValue($value3->pra_id, 'prsv_discount');
                                                    $unit = $this->settings_model->getUnitById($productById->pr_unit_id);
                                                    if ($totalSoldDiscount) {
                                                        echo $settings->currency . number_format_currency($totalSoldDiscount, 2);
                                                    }
                                                    $grandTotalProductSaleDiscountAmount += $totalSoldDiscount;
                                                    ?>
                                                </td>
                                                <td width="19%">
                                                    <?php $totalSoldAmount = $this->sale_model->getProductAssignedWiseProductSaleValue($value3->pra_id, 'prsv_total');
                                                    $unit = $this->settings_model->getUnitById($productById->pr_unit_id);
                                                    if ($totalSoldAmount) {
                                                        echo $settings->currency . number_format_currency($totalSoldAmount, 2);
                                                    }
                                                    $grandTotalProductSaleAmount += $totalSoldAmount;
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr class="table__row">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><?php if ($grandTotalProductSaleDiscountAmount) {
                                                    echo $settings->currency . number_format_currency($grandTotalProductSaleDiscountAmount, 2);
                                                }
                                                ?></td>
                                            <td> <?php
                                                    echo $settings->currency . number_format_currency($grandTotalProductSaleAmount, 2);
                                                    ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>







                        <!-- Birth/Reproduction -->
                        <!-- If any reproduction from this shed and batch
                         and Assign it to another shed abd batch  -->
                        <?php if ($livestockReproduction) { ?>
                            <div class="row ">
                                <div class="col-md-12">
                                    <table class="table  table-hover table-bordered">
                                        <thead>
                                            <tr class="bg-primary-light">
                                                <th colspan="6" class="text-center"><?= lang('reproduction'); ?></th>
                                            </tr>
                                            <tr>
                                                <th width="5%"><?= lang('serialNo'); ?></th>
                                                <th width="19%"> <?= lang('date'); ?></th>
                                                <th width="19%"><?= lang('assigned_quantity'); ?>(<?= $settings->unit ?>)</th>
                                                <th width="19%"><?= lang('assign_shed'); ?></th>
                                                <th width="19%"><?= lang('assign_batch'); ?></th>
                                                <th width="19%"><?= lang('total_amount'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($livestockReproduction) {
                                                $serial5 = 0;
                                                $totalReproductionAndAssignQuantity = 0;
                                                $totalReproductionAndAssignQuantityAmount = 0;
                                                foreach ($livestockReproduction as $reproductions) {
                                                    $serial5++;
                                            ?>
                                                    <tr>
                                                        <?php $reproductionAssignDataByReproductionId = $this->report_model->getSingleData('live_assigned_shed_summary', ['lshs_reproduction_id' => $reproductions->lrp_id, 'lshs_type' => 2, 'lshs_status' => 1]); ?>
                                                        <td class="table__cells"><?php echo $serial5; ?></td>
                                                        <td class="table__cells">
                                                            <div class="row">
                                                                <div class="col-xs-6 text-right">
                                                                    <strong><?= lang('reproduction'); ?> <?= lang('date'); ?></strong>:
                                                                </div>
                                                                <div class="col-xs-6">
                                                                    <?php echo date("$settings->date_format", strtotime($reproductions->lrp_birth_date)); ?>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-6 text-right">
                                                                    <strong><?= lang('assigned'); ?> <?= lang('date'); ?></strong>:
                                                                </div>
                                                                <div class="col-xs-6">
                                                                    <?php echo date("$settings->date_format", strtotime($reproductionAssignDataByReproductionId->lshs_assign_date)); ?>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="table__cells"><?php
                                                                                    echo $reproductionAssignDataByReproductionId->lshs_assign_total_quantity;
                                                                                    $totalReproductionAndAssignQuantity += $reproductionAssignDataByReproductionId->lshs_assign_total_quantity;
                                                                                    ?></td>
                                                        <td class="table__cells"><?= $this->shed_model->getShedById($reproductionAssignDataByReproductionId->lshs_sh_id)->sh_no ?>: <?= $this->shed_model->getShedById($reproductionAssignDataByReproductionId->lshs_sh_id)->sh_title ?>
                                                        </td>
                                                        <td class="table__cells">
                                                            <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($reproductionAssignDataByReproductionId->lshs_sh_id, $reproductionAssignDataByReproductionId->lshs_batch_id)->lshs_batch_id ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($reproductionAssignDataByReproductionId->lshs_sh_id, $reproductionAssignDataByReproductionId->lshs_batch_id)->lshs_batch_title ?>
                                                        </td>
                                                        <td class="table__cells">
                                                            <?php
                                                            $approxPricePerQuantity = $reproductions->lrp_approx_selling_price;
                                                            $totalAssignedQuantity = $reproductionAssignDataByReproductionId->lshs_assign_total_quantity;
                                                            $totalAmountReproductionAmount = $approxPricePerQuantity * $totalAssignedQuantity;
                                                            if ($totalAmountReproductionAmount) {
                                                                echo $settings->currency . number_format_currency($totalAmountReproductionAmount, 2);
                                                            } else {
                                                                echo $settings->currency . number_format_currency(0, 2);
                                                            }
                                                            $totalReproductionAndAssignQuantityAmount += $totalAmountReproductionAmount;
                                                            ?>
                                                        </td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                            <tr class="table__row">
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <?php if ($totalReproductionAndAssignQuantity) {
                                                        echo $totalReproductionAndAssignQuantity;
                                                    } ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td> <?php echo $settings->currency . number_format_currency($totalReproductionAndAssignQuantityAmount, 2); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- /.Reproduction Data calculation from this shed and batch -->
                        <!-- /.Birth/Reproduction -->

                        <!-- Balance Sheet -->
                        <div class="row ">
                            <div class="col-md-12">
                                <table class="table  table-hover table-bordered">
                                    <thead>
                                        <tr class="table__header">
                                            <th colspan="6" class="text-center"><?= lang('balance_sheet'); ?></th>
                                        </tr>
                                        <tr class="table__row text-right">
                                            <td width="40%">
                                                <p><?= lang('livestock_purchase_cost'); ?>:</p>
                                                <p><?= lang('food_cost'); ?>:</p>
                                                <p><?= lang('vaccine_cost'); ?>:</p>
                                                <p><?= lang('livestock_sold_income'); ?>:</p>
                                                <p><?= lang('product_sold_income'); ?>:</p>
                                                <?php
                                                if ($livestockReproduction) { ?>
                                                    <p><?= lang('reproduction_income'); ?>:</p>
                                                <?php } ?>
                                                <hr>
                                                <p><?= lang('profit'); ?>/<?= lang('loss'); ?></p>
                                            </td>
                                            <td width="10%">
                                                <p><?php echo $settings->currency . number_format_currency($grandTotalPurchaseAmount, 2); ?></p>
                                                <p><?php echo $settings->currency . number_format_currency($grandTotalAverageAmount, 2); ?></p>
                                                <p><?php echo $settings->currency . number_format_currency($vaccineWiseGrandTotalAmount, 2); ?></p>
                                                <p><?php echo $settings->currency . number_format_currency($grandTotalSoldAmount, 2); ?></p>
                                                <p><?php echo $settings->currency . number_format_currency($grandTotalProductSaleAmount, 2); ?></p>
                                                <?php if ($livestockReproduction) { ?>
                                                    <p><?php
                                                        echo $settings->currency . number_format_currency($totalReproductionAndAssignQuantityAmount, 2);
                                                        $totalReproductionAndAssignQuantityAmount = $totalReproductionAndAssignQuantityAmount;
                                                        ?></p>
                                                <?php } ?>
                                                <hr>
                                                <p>
                                                    <?php if ($livestockReproduction) { ?>
                                                        <?php
                                                        $profitLossAmount = ($grandTotalSoldAmount + $grandTotalProductSaleAmount + $totalReproductionAndAssignQuantityAmount) - ($grandTotalPurchaseAmount + $grandTotalAverageAmount + $vaccineWiseGrandTotalAmount);
                                                        echo $settings->currency . number_format_currency($profitLossAmount, 2); ?>
                                                    <?php } else { ?>
                                                        <?php
                                                        $profitLossAmount = ($grandTotalSoldAmount + $grandTotalProductSaleAmount) - ($grandTotalPurchaseAmount + $grandTotalAverageAmount + $vaccineWiseGrandTotalAmount);
                                                        echo $settings->currency . number_format_currency($profitLossAmount, 2); ?>
                                                    <?php } ?>
                                                </p>
                                            </td>
                                            <td width="50%" class="text-center table_text_middle">

                                                <?php if ($profitLossAmount > 0) { ?>
                                                    <h2><?= lang('profit'); ?>: <?php echo $settings->currency . number_format_currency($profitLossAmount, 2); ?></h2>
                                                <?php } elseif ($profitLossAmount < 0) { ?>
                                                    <h2><?= lang('loss'); ?>: <?php echo $settings->currency . number_format_currency(abs($profitLossAmount), 2); ?></h2>
                                                <?php } else { ?>
                                                    <h2><?= lang('the_balance_is'); ?>: <?php echo $settings->currency . number_format_currency(0, 2); ?></h2>
                                                <?php }  ?>
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    <?php } else {  ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="report__notice bg-success-light">
                                    <h4 class="text__margin"><strong><?= lang('notice'); ?>:</strong> <?= lang('please_select'); ?> <strong><?= lang('shed'); ?></strong> <?= lang('and'); ?> <strong><?= lang('batch'); ?></strong>. <?= lang('then_search_it_to_get_the_report'); ?>. </h4>
                                </div>
                            </div>
                        </div>
                    <?php }  ?>
                </div>
            </section>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<!--footer start-->


<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script>
    $(document).ready(function() {
        $('#shed_id').on('change', function() {
            var iid = $(this).val();
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
        });

    });
</script>