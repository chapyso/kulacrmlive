<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">

                <header class="panel-heading">
                    <i class="fa-solid fa-hospital"></i> <?= lang('vaccination_schedule'); ?>
                </header>

                <div class="panel-body">
                    <div class="clearfix">
                        <a href="<?php echo base_url('vaccine/listVaccinatedShed'); ?>">
                            <div class="btn-group">
                                <button class="button button-primary">
                                    <i class="fa-solid fa-circle-arrow-left"></i>
                                </button>
                            </div>
                        </a>
                        <a data-toggle="modal" href="<?php echo base_url('vaccine/listUsedVaccine') ?>">
                            <div class="btn-group">
                                <button id="" class="button button-info" title="<?= lang('view_used_vaccine_list_with_details'); ?>">
                                    <i class="fas fa-eye"></i> <?= lang('view_used_vaccine'); ?>
                                </button>
                            </div>
                        </a>
                        <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="new__cards__auto card__box">
                                <div class="col-xs-8">
                                    <table class="table">
                                        <thead>
                                            <th colspan="2" class="text-center"><?= lang('basic_information') ?></th>

                                            <th colspan="2" class="text-center"><?= lang('stock_information') ?></th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="25%"><?= lang('shed_no'); ?>.:</td>
                                                <td width="25%"><?= $shedById->sh_no; ?></td>
                                                <td width="25%"><?= lang('total_livestock'); ?>:</td>
                                                <td width="25%"> <?php $totalLive = $this->shed_model->getShedWiseLivestock($shedById->sh_id);
                                                                    if ($totalLive) {
                                                                        echo $totalLive;
                                                                    } else {
                                                                        echo 0;
                                                                    }
                                                                    ?> <?= $settings->unit; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?= lang('shed_title'); ?>:</td>
                                                <td><?= $shedById->sh_title ?></td>
                                                <td><?= lang('sold'); ?>:</td>
                                                <td> <?php $sold = $this->sale_model->getShedWiseSoldLivestock($shedById->sh_id);
                                                        if ($sold) {
                                                            echo $sold . ' ' . $settings->unit;
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?></td>
                                            </tr>
                                            <tr>
                                                <td><?= lang('description'); ?>:</td>
                                                <td><?= $shedById->sh_description ?></td>
                                                <td><?= lang('total'); ?> <?= lang('death'); ?>:</td>
                                                <td><?php $death = $this->shed_model->getShedWiseDeathLivestock($shedById->sh_id);
                                                    if ($death) {
                                                        echo $death . ' ' . $settings->unit;
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></td>
                                            </tr>
                                            <tr>
                                                <td><?= lang('assigned_batch_quantity'); ?>:</td>
                                                <td><?= $this->shed_model->getCountShedWiseBatchAssignedQuantity($shedById->sh_id); ?> <?= $settings->unit ?></td>
                                                <td><?= lang('in_stock'); ?>:</td>
                                                <td> <?php $inStock = $totalLive - ($sold + $death);
                                                        if ($inStock) {
                                                            echo $inStock . ' ' . $settings->unit;
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-xs-4">
                                    <table class="table">
                                        <thead>
                                            <th colspan="4" class="text-center"><?= lang('batch') ?> <?= lang('status') ?></th>
                                        </thead>
                                        <?php
                                        $shedWiseTotalBatch = $this->report_model->getCountRow('live_assigned_shed_summary', 'lshs_id', ['lshs_sh_id' => $shedById->sh_id, 'lshs_status' => 1]);
                                        $shedWiseRunningBatch = $this->report_model->getCountRow('live_assigned_shed_summary', 'lshs_id', ['lshs_sh_id' => $shedById->sh_id, 'lshs_status' => 1, 'lshs_active_status' => 0]);
                                        $shedWiseCompletedBatch = $this->report_model->getCountRow('live_assigned_shed_summary', 'lshs_id', ['lshs_sh_id' => $shedById->sh_id, 'lshs_status' => 1, 'lshs_active_status' => 1]);
                                        ?>
                                        <tbody>
                                            <tr>
                                                <td width="50%"><strong><?php echo lang('total'); ?> <?php echo lang('batch'); ?>: </strong></td>
                                                <td width="50%">
                                                    <?php if ($shedWiseTotalBatch) {
                                                        echo "<span class='button bg-warning-light'> " . $shedWiseTotalBatch .  "</span>";
                                                    } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong><?php echo lang('completed'); ?> <?php echo lang('batch'); ?>: </strong></td>
                                                <td>
                                                    <?php if ($shedWiseCompletedBatch) {
                                                        echo "<span class='button bg-success-light'>  " . $shedWiseCompletedBatch .  "</span>";
                                                    } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong><?php echo lang('running'); ?> <?php echo lang('batch'); ?>: </strong></td>
                                                <td>
                                                    <?php if ($shedWiseRunningBatch) {
                                                        echo "<span class='button bg-primary-light'> " . $shedWiseRunningBatch .  "</span>";
                                                    } ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="table table-striped table-hover table-bordered" id="editable-sample">
                        <thead>
                            <tr>
                                <th><?= lang('serialNo'); ?> </th>
                                <th><?= lang('assigned_date'); ?> </th>
                                <th><?= lang('batch'); ?> </th>
                                <th><?= lang('livestock_name'); ?> </th>
                                <th><?= lang('livestock_variant'); ?> </th>
                                <th><?= lang('assigned_quantity'); ?> (<?= $settings->unit ?>) </th>
                                <th><?= lang('sold'); ?> (<?= $settings->unit ?>) </th>
                                <th><?= lang('death'); ?> (<?= $settings->unit ?>) </th>
                                <th><?= lang('in_stock'); ?> (<?= $settings->unit ?>) </th>
                                <th width="12%"> <?= lang('vaccine_name'); ?> </th>
                                <th><?= lang('dose_quantity'); ?> </th>
                                <th><?= lang('vaccine_issue_date'); ?> </th>
                                <th><?= lang('vaccine_route') ?> </th>
                                <th width="25%"> <?= lang('vaccination_status'); ?>/ <?= lang('vaccine_used_quantity'); ?>/ <?= lang('used_date'); ?> </th>
                                <th><?= lang('batch_status'); ?> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $serial = 0;
                            if ($batchesByShed) {
                                foreach ($batchesByShed as $value) {
                                    $serial++;
                            ?>
                                    <tr>
                                        <td class="table_text_middle"><?= $serial ?></td>
                                        <td class="table_text_middle"><?= date("$settings->date_format", strtotime($value->lshs_assign_date)); ?></td>
                                        <td class="table_text_middle"><strong><?= $value->lshs_batch_id ?>:</strong> <?= $value->lshs_batch_title ?></td>
                                        <td class="table_text_middle">
                                            <?php
                                            $livestockId = $this->purchase_model->getAssignedLivestockValueDataBySummaryId($value->lshs_id)->lsh_purv_ls_id;
                                            $livestockTypeId = $this->purchase_model->getAssignedLivestockValueDataBySummaryId($value->lshs_id)->lsh_purv_lst_id;

                                            echo $this->livestock_model->getLivestockById($livestockId)->ls_name ?>
                                        </td>
                                        <td class="table_text_middle"><?= $this->livestock_model->getLivestockTypeById($livestockTypeId)->lst_title ?></td>
                                        <td class="table_text_middle"><?php $assignedQuantity = $value->lshs_assign_total_quantity;
                                                                        if ($assignedQuantity) {
                                                                            echo $assignedQuantity;
                                                                        }
                                                                        ?></td>
                                        <td class="table_text_middle">
                                            <?php $sold = $this->sale_model->getShedAndBatchWiseLivestockSaleQuantity($value->lshs_sh_id, $value->lshs_batch_id, $livestockId, $livestockTypeId);
                                            if ($sold) {
                                                echo $sold;
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                        <td class="table_text_middle">
                                            <?php $death = $this->report_model->getSumData('livestock_death_quantity', 'ld_death_quantity', ['ld_status' => 1, 'ld_sh_id' => $value->lshs_sh_id, 'ld_batch_id' => $value->lshs_batch_id]);
                                            if ($death) {
                                                echo $death;
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                        <td class="table_text_middle">
                                            <?php $inStock = $assignedQuantity - ($sold + $death);
                                            if ($inStock) {
                                                echo $inStock;
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                        <!-- Vaccine Name -->
                                        <td class="table_text_middle">
                                            <?php
                                            $getVaccineId =  $this->vaccine_model->getVaccineByBatchIdSingleData($livestockId, $livestockTypeId);
                                            if ($getVaccineId) {
                                                foreach ($getVaccineId as $key => $vaccine) {
                                                    // Vaccine Stock Calculation
                                                    $purchaseVaccineQuantity = $this->vaccine_model->getVaccineWiseVaccinePurchaseQuantity($vaccine->vccn_vcc_id, 'vpv_quantity');
                                                    $usedVaccineQuantity = $this->vaccine_model->getVaccineWiseVaccineUsedQuantity($vaccine->vccn_vcc_id, 'vds_vaccine_used_quantity');
                                                    $wastedVaccine = $this->vaccine_model->getVaccineWastedByVaccineId($vaccine->vccn_vcc_id, 'vccw_quantity');
                                                    $inStockVaccine =  $purchaseVaccineQuantity - ($usedVaccineQuantity + $wastedVaccine);

                                                    $vaccinePurchaseUnit = $this->settings_model->getUnitById($this->vaccine_model->getVaccineById($vaccine->vccn_vcc_id)->vcc_unit_id);

                                                    echo "<div>";
                                                    $vaccineDoseAssignedQuantity = $this->vaccine_model->getVaccineAssignedDoseQuantityByVaccinationId($vaccine->vccn_id);
                                                    if ($vaccineDoseAssignedQuantity) {
                                                        foreach ($vaccineDoseAssignedQuantity as $doseQuantity) {
                                                            echo "<li style='margin: 12px 0; list-style: none;'>";

                                                            if ($doseQuantity->vdq_dose_serial == 1) {
                                                                if ($key > 0) {
                                                                    echo "<hr>";
                                                                }
                                                                echo "<strong>" . lang('name') . ":</strong> " . $this->vaccine_model->getVaccineById($vaccine->vccn_vcc_id)->vcc_name;
                                                                echo "<br>";
                                                                if ($inStockVaccine) {
                                                                    echo "<strong>" . lang('stock') . ":</strong> " . $inStockVaccine . ' ' . $vaccinePurchaseUnit->un_name;
                                                                } else {
                                                                    echo "<strong>" . lang('stock') . ":</strong> <i class='fa fa-frown-o text-danger' aria-hidden='true'></i>";
                                                                }
                                                            }
                                                            echo "<br>";
                                                            echo "</li>";
                                                        }
                                                    } else {
                                                        echo "<span class='text-danger'>" . lang('no_dose') . "</span>";
                                                    }
                                                    echo "</div>";
                                                }
                                            } else {
                                                echo "<span class='text-danger'>" . lang('no_result') . "</span>";
                                            }
                                            ?>
                                        </td>
                                        <!-- /.Vaccine Name -->

                                        <!-- Dose Quantity -->
                                        <td class="table_text_middle">
                                            <?php
                                            if ($getVaccineId) {
                                                foreach ($getVaccineId as $key1 => $vaccine1) {
                                                    echo "<div>";
                                                    if ($key1 > 0) {
                                                        echo "<hr>";
                                                    }
                                                    $vaccineDoseAssignedQuantity = $this->vaccine_model->getVaccineAssignedDoseQuantityByVaccinationId($vaccine1->vccn_id);
                                                    if ($vaccineDoseAssignedQuantity) {
                                                        foreach ($vaccineDoseAssignedQuantity as $doseQuantity) {
                                                            echo "<li style='margin: 12px 0; list-style: none;'>";
                                                            echo $doseQuantity->vdq_dose_serial;
                                                            echo "<br>";
                                                            echo "</li>";
                                                        }
                                                    } else {
                                                        echo "<span class='text-danger'>" . lang('no_dose') . "</span>";
                                                    }
                                                    echo "</div>";
                                                }
                                            } else {
                                                echo "<span class='text-danger'>" . lang('no_result') . "</span>";
                                            }
                                            ?>
                                        </td>
                                        <!-- /.Dose Quantity -->

                                        <!-- Find Vaccination Date -->
                                        <td class="table_text_middle">
                                            <?php
                                            if ($getVaccineId) {
                                                foreach ($getVaccineId as $key2 => $vaccine2) {
                                                    echo "<div>";
                                                    if ($key2 > 0) {
                                                        echo "<hr>";
                                                    }
                                                    $vaccineDoseAssignedQuantity = $this->vaccine_model->getVaccineAssignedDoseQuantityByVaccinationId($vaccine2->vccn_id);
                                                    if ($vaccineDoseAssignedQuantity) {
                                                        foreach ($vaccineDoseAssignedQuantity as $doseQuantityDate) {
                                                            echo "<li style='margin: 12px 0; list-style: none;'>";
                                                            $date = date("$settings->date_format", strtotime("$value->lshs_assign_date + $doseQuantityDate->vdq_vaccination_date" . lang('day')));
                                                            $vaccinationDate = date("$settings->date_format", strtotime($date));

                                                            if (strtotime($vaccinationDate) == strtotime(date("d-m-Y"))) {
                                                                echo "<span class='badge bg-danger-light'>" . $vaccinationDate . "</span>";
                                                            } elseif (strtotime($vaccinationDate) > strtotime(date("d-m-Y"))) {
                                                                echo "<span class='badge bg-warning-light'>" . $vaccinationDate . "</span>";
                                                            } elseif (strtotime($vaccinationDate) < strtotime(date("d-m-Y"))) {
                                                                echo "<span class='badge bg-info-light'>" . $vaccinationDate . "</span>";
                                                            } else {
                                                                echo $vaccinationDate;
                                                            }

                                                            echo "<br>";

                                                            echo "</li>";
                                                        }
                                                    } else {
                                                        echo "<span class='text-danger'>" . lang('no_dose') . "</span>";
                                                    }
                                                    echo "</div>";
                                                }
                                            } else {
                                                echo "<span class='text-danger'>" . lang('no_result') . "</span>";
                                            }
                                            ?>
                                        </td>
                                        <!-- /.Find Vaccination Date -->

                                        <!-- Vaccine Routing -->
                                        <td class="table_text_middle">
                                            <ul>
                                                <?php
                                                if ($getVaccineId) {
                                                    foreach ($getVaccineId as $key3 => $vaccine3) {
                                                        echo "<div>";
                                                        if ($key3 > 0) {
                                                            echo "<hr>";
                                                        }
                                                        $vaccineDoseAssignedQuantity = $this->vaccine_model->getVaccineAssignedDoseQuantityByVaccinationId($vaccine3->vccn_id);
                                                        if ($vaccineDoseAssignedQuantity) {
                                                            foreach ($vaccineDoseAssignedQuantity as $doseQuantityRoute) {
                                                                echo "<li style='margin: 12px 0; list-style: none;'>";
                                                                $vaccineRouteName = $this->vaccine_model->getVaccineRouteById($doseQuantityRoute->vdq_vaccine_route);
                                                                if ($vaccineRouteName) {
                                                                    echo $vaccineRouteName->vccr_name;
                                                                } else {
                                                                    echo "<span class='text-danger'>" . lang('no_data_found') . "</span>";
                                                                }
                                                                echo "<br>";
                                                                echo "</li>";
                                                            }
                                                        } else {
                                                            echo "<span class='text-danger'>" . lang('no_dose') . "</span>";
                                                        }
                                                        echo "</div>";
                                                    }
                                                } else {
                                                    echo "<span class='text-danger'>" . lang('no_result') . "</span>";
                                                }
                                                ?>
                                            </ul>
                                        </td>
                                        <!-- /.Vaccine Routing -->

                                        <!-- Actions -->
                                        <td class="table_text_middle">
                                            <ul>
                                                <?php
                                                if ($getVaccineId) {
                                                    foreach ($getVaccineId as $key4 => $vaccine4) {
                                                        echo "<div>";
                                                        if ($key4 > 0) {
                                                            echo "<hr>";
                                                        }

                                                        // Vaccine Stock Calculation for stock alert
                                                        $purchaseVaccineQuantityNew = $this->vaccine_model->getVaccineWiseVaccinePurchaseQuantity($vaccine4->vccn_vcc_id, 'vpv_quantity');
                                                        $usedVaccineQuantityNew = $this->vaccine_model->getVaccineWiseVaccineUsedQuantity($vaccine4->vccn_vcc_id, 'vds_vaccine_used_quantity');
                                                        $wastedVaccineNew = $this->vaccine_model->getVaccineWastedByVaccineId($vaccine4->vccn_vcc_id, 'vccw_quantity');
                                                        $inStockVaccineNew =  $purchaseVaccineQuantityNew - ($usedVaccineQuantityNew + $wastedVaccineNew);
                                                        // Vaccine Stock Calculation for stock alert

                                                        $vaccineDoseAssignedQuantityValue = $this->vaccine_model->getVaccineAssignedDoseQuantityByVaccinationId($vaccine4->vccn_id);
                                                        if ($vaccineDoseAssignedQuantityValue) {
                                                            $newKeyForDisableDoseButton = 0;

                                                            foreach ($vaccineDoseAssignedQuantityValue as $doseQuantityValue) {
                                                                echo "<li>";

                                                                $getId = $this->vaccine_model->getVaccineDoseStatusByPurchaseLivestockTypeVaccineQuantityId($doseQuantityValue->vdq_id, $value->lshs_batch_id);
                                                                if (empty($getId)) {
                                                                    $newKeyForDisableDoseButton++;
                                                ?>
                                                                    <?= $doseQuantityValue->vdq_dose_serial; ?>
                                                                    <?php if ($value->lshs_active_status == 0) { ?>
                                                                        <button <?php echo ($newKeyForDisableDoseButton != 1) ? "disabled" : ""; ?> data-vaccination_id="<?= $vaccine4->vccn_id; ?>" data-vaccine_id="<?= $vaccine4->vccn_vcc_id; ?>" data-vaccineName="<?= $this->vaccine_model->getVaccineById($vaccine4->vccn_vcc_id)->vcc_name; ?>" data-vaccine-unit="<?php if ($vaccinePurchaseUnit) {
                                                                                                                                                                                                                                                                                                                                                                                    echo $vaccinePurchaseUnit->un_name;
                                                                                                                                                                                                                                                                                                                                                                                } ?>" data-vdq_id="<?= $doseQuantityValue->vdq_id; ?>" data-lshs_id="<?= $value->lshs_id ?>" data-sh_id="<?= $value->lshs_sh_id ?>" data-batch_id="<?= $value->lshs_batch_id ?>" data-vaccineStock="<?= $inStockVaccineNew ?>" type='button' class='button button-primary confirmDoseAndUsedVaccine' style="margin-bottom: 1px;" title="Confirm vaccine dose and uses quantity of vaccine of this dose."><i class="fa-solid fa-syringe"></i> <?= lang('dose'); ?></button>
                                                                    <?php } else { ?>
                                                                        <button type='button' class='button button-primary' disabled style="margin-bottom: 1px;"><i class="fa-solid fa-syringe"></i> <?= lang('dose'); ?></button>
                                                                    <?php }
                                                                    ?>
                                                                    <br>
                                                                <?php
                                                                } else {
                                                                    echo $doseQuantityValue->vdq_dose_serial . " <button class='button button-success' style='margin-bottom:1px;'><i class='fa-solid fa-circle-check'></i> " . lang('done') . "</button>";
                                                                ?>

                                                                    <?php if ($value->lshs_active_status == 0) { ?>

                                                                        <!-- Row data for last vaccine undo disable false -->
                                                                        <?php
                                                                        $query =  $this->db->query("SELECT * FROM vaccine_dose_status WHERE vds_vcc_id = ? AND vds_batch_id = ? AND vds_status = 1 ORDER BY vds_id DESC", array($vaccine4->vccn_vcc_id, $value->lshs_batch_id))->row(); ?>

                                                                        <a href="<?php echo base_url('') ?>vaccine/updateVaccinationDoseStatus?vds_id=<?= $getId->vds_id; ?>&&batch_id=<?= $value->lshs_batch_id ?>&&sh_id=<?= $value->lshs_sh_id ?>" onclick="return confirm('<?= lang('are_you_sure_you_have_completed_this_dose'); ?> ');"><button <?php echo ($query->vds_id ==  $getId->vds_id) ? true : "disabled"; ?> type='button' class='button button-warning' style="margin-bottom: 1px;"><i class="fa-solid fa-rotate-left"></i> <?= lang('undo'); ?></button></a>

                                                                    <?php } else { ?>
                                                                        <button type='button' class='button button-warning' disabled style="margin-bottom: 1px;"><i class="fa-solid fa-rotate-left"></i> <?= lang('undo'); ?></button>

                                                                    <?php }
                                                                    ?>
                                                                    <span class="button bg-primary-light"><?php echo $getId->vds_vaccine_used_quantity; ?> <?php if ($vaccinePurchaseUnit) {
                                                                                                                                                                echo $vaccinePurchaseUnit->un_name;
                                                                                                                                                            } ?></span>
                                                                    <span class="button bg-success-light"><?php echo date("d-m-Y", strtotime($getId->vds_created_at)); ?></span>
                                                <?php

                                                                    echo "<br>";
                                                                }
                                                                echo "</li>";
                                                            }
                                                        }

                                                        echo "</div>";
                                                    }
                                                } else {
                                                    echo "<span class='text-danger'>" . lang('no_result') . "</span>";
                                                }
                                                ?>
                                            </ul>
                                        </td>
                                        <td class="table_text_middle">
                                            <?php if ($value->lshs_active_status == 0) { ?>
                                                <button class="button bg-info-light"><i class="fa-solid fa-person-running"></i> <?= lang('running'); ?></button>
                                            <?php } else { ?>
                                                <button class="button bg-success-light"><i class="fa-solid fa-circle-check"></i> <?= lang('completed'); ?></button>
                                            <?php } ?>
                                        </td>
                                        <!-- /.Actions -->
                                    </tr>
                            <?php }
                            } else  ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<!--footer start-->

<!-- ==================== Vaccine Used ==================== -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('add_used_vaccine_and_confirm_dose'); ?> </strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('vaccine/insertVaccinationDoseStatus'); ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('vaccine_name'); ?> </label>
                            <input type="text" class="form-control" name="" id="vaccineNamee" value='' placeholder="" readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('in_stock'); ?> <span class="vaccineUnit"></span> </label>
                            <input type="text" class="form-control input__number" name="" id="vaccine_stock" placeholder="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('vaccine_used_quantity'); ?> </label><span class="vaccineUnit"></span><span class="text-danger">*</span>
                        <input type="text" class="form-control input__number" name="vaccine_used_quantity" value='' id="used_vaccine_quantity" placeholder="Enter Quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('note'); ?> </label>
                        <textarea type="text" name="description" class="form-control" id="description" value="" rows="5" cols="10" style="height: auto !important;" placeholder="Enter Note"></textarea>
                    </div>

                    <input type="hidden" name="vcc_id" id="vaccine_iid">
                    <input type="hidden" name="vdq_id" id="vdq_id">
                    <input type="hidden" name="lshs_id" id="lshs_id">
                    <input type="hidden" name="sh_id" id="shed_id">
                    <input type="hidden" name="batch_id" id="batch_id">
                    <input type="hidden" name="vccn_id" id="vaccination_id">
                    <section class="text-right">
                        <button type="submit" name="submit" class="button button-info submit_button"><?= lang('confirm_dose'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- ==================== /.Vaccine Used ==================== -->



<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        /* ==================== Vaccine Used ==================== */
        $(".confirmDoseAndUsedVaccine").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var vaccineId = $(this).attr('data-vaccine_id');
            var vaccineName = $(this).attr('data-vaccineName');
            var vaccineUnit = $(this).attr('data-vaccine-unit');
            var vdq_id = $(this).attr('data-vdq_id');
            var lshs_id = $(this).attr('data-lshs_id');
            var shed_id = $(this).attr('data-sh_id');
            var batch_id = $(this).attr('data-batch_id');
            var vaccination_id = $(this).attr('data-vaccination_id');
            var vaccine_stock = $(this).attr('data-vaccineStock');
            $('#myModal').modal('show');
            $("#vaccine_iid").val(vaccineId);
            $("#vaccineNamee").val(vaccineName);
            $(".vaccineUnit").text(' (' + vaccineUnit + ')');
            $("#vdq_id").val(vdq_id);
            $("#lshs_id").val(lshs_id);
            $("#shed_id").val(shed_id);
            $("#batch_id").val(batch_id);
            $("#vaccination_id").val(vaccination_id);
            $("#vaccine_stock").val(vaccine_stock);

            $("#used_vaccine_quantity").val('');
            $("#description").val('');

            $("#used_vaccine_quantity").keyup(function() {
                var usedStock = $(this).val();
                var totalStock = vaccine_stock;
                if (parseFloat(usedStock) > parseFloat(totalStock)) {
                    $("#used_vaccine_quantity").val('');
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('please_enter_a_valid_value'); ?>"
                    });
                }
            });

        });
        /* ==================== Vaccine Used ==================== */
    });
</script>