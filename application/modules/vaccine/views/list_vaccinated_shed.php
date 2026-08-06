<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('shed_lists'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table ">
                        <div class="clearfix">
                            <a href="<?php echo base_url('vaccine'); ?>">
                                <div class="btn-group">
                                    <button class="button button-primary">
                                        <i class="fa-solid fa-circle-arrow-up"></i> <?= lang('vaccine_lists'); ?>
                                    </button>
                                </div>
                            </a>
                            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>

                            <a data-toggle="modal" href="#informationPopup" class="button button-purple export"><i class="fa-solid fa-circle-question"></i> <?= lang('information'); ?></a>
                        </div>
                        <div class="space15"></div>
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th><?= lang('serialNo'); ?></th>
                                    <th><?= lang('sheds'); ?> </th>
                                    <th><?= lang('assigned_livestock'); ?> (<?= $settings->unit; ?>)</th>
                                    <th><?= lang('sold'); ?> (<?= $settings->unit ?>)</th>
                                    <th><?= lang('death'); ?> (<?= $settings->unit ?>)</th>
                                    <th><?= lang('in_stock'); ?> (<?= $settings->unit ?>)</th>
                                    <th><?= lang('last'); ?> <?= lang('vaccine_issue_date'); ?></th>
                                    <th><?= lang('last_vaccinated'); ?></th>
                                    <th><?= lang('next_vaccination'); ?></th>
                                    <th><?= lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $serial = 0;
                                foreach ($sheds as $shed) {
                                    $serial++;
                                ?>
                                    <tr class="">
                                        <td> <?= $serial ?></td>
                                        <td> <strong><?= $shed->sh_no; ?>:</strong> <?= $shed->sh_title; ?></td>
                                        <td>
                                            <?php $bchWiseQuantity = $this->purchase_model->getBatchOrShedWiseTotalAssignedQuantity('lshs_sh_id', $shed->sh_id);
                                            if ($bchWiseQuantity) {
                                                echo $bchWiseQuantity;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php $sold = $this->sale_model->getShedWiseSoldLivestock($shed->sh_id);
                                            if ($sold) {
                                                echo $sold;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php $death = $this->shed_model->getShedWiseDeathLivestock($shed->sh_id);
                                            if ($death) {
                                                echo $death;
                                            }
                                            ?>
                                        </td>
                                        <td><?php $inStock = $bchWiseQuantity - ($sold + $death);
                                            if ($inStock) {
                                                echo $inStock;
                                            }
                                            ?>
                                        </td>
                                        <!-- Next Vaccination Day in shed -->
                                        <td>
                                            <?php
                                            $batchesForLastIssueDate =  $this->vaccine_model->getAssignedBatchesBySheds($shed->sh_id);
                                            if ($batchesForLastIssueDate) {
                                                $selectMaximumDate = 0;
                                                foreach ($batchesForLastIssueDate as $value1) {
                                                    $value1->lshs_assign_date;
                                                    $livestockInfoLastIssueDate = $this->settings_model->getSingleData('live_assigned_shed', ['lsh_lshs_id' => $value1->lshs_id]);

                                                    $getVaccineIdLastIssueDate =  $this->vaccine_model->getVaccineByBatchIdSingleData($livestockInfoLastIssueDate->lsh_purv_ls_id, $livestockInfoLastIssueDate->lsh_purv_lst_id);
                                                    if ($getVaccineIdLastIssueDate) {
                                                        foreach ($getVaccineIdLastIssueDate as $vaccine1) {
                                                            $vaccineDoseAssignedQuantityLastIssueDate = $this->vaccine_model->getVaccineAssignedDoseQuantityByVaccinationId($vaccine1->vccn_id);
                                                            if ($vaccineDoseAssignedQuantityLastIssueDate) {
                                                                foreach ($vaccineDoseAssignedQuantityLastIssueDate as $doseQuantityDateLastIssueDate) {
                                                                    $doseQuantityDateLastIssueDate->vdq_vaccination_date;
                                                                    $date = date("$settings->date_format", strtotime("$value1->lshs_assign_date + $doseQuantityDateLastIssueDate->vdq_vaccination_date" . lang('day')));
                                                                    $vaccinationDateLastIssueDate = date("$settings->date_format", strtotime($date));

                                                                    if (strtotime($vaccinationDateLastIssueDate) <= strtotime(date('d-m-Y'))) {
                                                                        $nexVaccinationDatesLastIssueDate = strtotime($vaccinationDateLastIssueDate);

                                                                        if ($selectMaximumDate == 0) {
                                                                            $selectMaximumDate = $nexVaccinationDatesLastIssueDate;
                                                                        } elseif ($selectMaximumDate < $nexVaccinationDatesLastIssueDate) {
                                                                            $selectMaximumDate = $nexVaccinationDatesLastIssueDate;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                // Print nex vaccination date and days name
                                                if ($selectMaximumDate) {
                                                    $dateToDayLastIssueDate = date('l', $selectMaximumDate);
                                                    echo "<span class='badge bg-danger-light'>" . date("d-m-Y", $selectMaximumDate) . ", $dateToDayLastIssueDate </span>";
                                                } else {
                                                    echo lang('no_vaccination_date');
                                                }
                                            } else {
                                                echo lang('no_data_found');
                                            }

                                            ?>
                                        </td>

                                        <!-- Last Vaccine Issued by shed -->
                                        <td>
                                            <?php $lastVaccineIssuedDateInShed = $this->vaccine_model->getLastVaccineIssuedDate($shed->sh_id);
                                            if ($lastVaccineIssuedDateInShed) {
                                                $lastVaccinatedDateToDayName = date('l', strtotime($lastVaccineIssuedDateInShed));
                                                echo "<span class='badge bg-success-light'>" . date("d-m-Y", strtotime($lastVaccineIssuedDateInShed)) . ", $lastVaccinatedDateToDayName </span>";
                                            } else {
                                                echo lang('no_vaccinated_yet');
                                            }
                                            ?>
                                        </td>

                                        <!-- Next Vaccination Day in shed -->
                                        <td>
                                            <?php
                                            $batches =  $this->vaccine_model->getAssignedBatchesBySheds($shed->sh_id);
                                            if ($batches) {
                                                $selectMinimumDate = 0;
                                                foreach ($batches as $value) {
                                                    $value->lshs_assign_date;
                                                    $livestockInfo = $this->settings_model->getSingleData('live_assigned_shed', ['lsh_lshs_id' => $value->lshs_id]);

                                                    $getVaccineId =  $this->vaccine_model->getVaccineByBatchIdSingleData($livestockInfo->lsh_purv_ls_id, $livestockInfo->lsh_purv_lst_id);
                                                    if ($getVaccineId) {
                                                        foreach ($getVaccineId as  $vaccine) {
                                                            $vaccineDoseAssignedQuantity = $this->vaccine_model->getVaccineAssignedDoseQuantityByVaccinationId($vaccine->vccn_id);
                                                            if ($vaccineDoseAssignedQuantity) {
                                                                foreach ($vaccineDoseAssignedQuantity as $doseQuantityDate) {
                                                                    $doseQuantityDate->vdq_vaccination_date;
                                                                    $date = date("$settings->date_format", strtotime("$value->lshs_assign_date + $doseQuantityDate->vdq_vaccination_date" . lang('day')));
                                                                    $vaccinationDate = date("$settings->date_format", strtotime($date));

                                                                    if (strtotime($vaccinationDate) >= strtotime(date('d-m-Y'))) {
                                                                        $nexVaccinationDates = strtotime($vaccinationDate);

                                                                        if ($selectMinimumDate == 0) {
                                                                            $selectMinimumDate = $nexVaccinationDates;
                                                                        } elseif ($selectMinimumDate > $nexVaccinationDates) {
                                                                            $selectMinimumDate = $nexVaccinationDates;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                // Print next vaccination date and days name
                                                if ($selectMinimumDate) {
                                                    $dateToDay = date('l', $selectMinimumDate);
                                                    echo "<span class='badge bg-primary-light'>" . date("d-m-Y", $selectMinimumDate) . ", $dateToDay </span>";
                                                } else {
                                                    echo lang('no_vaccination_date');
                                                }
                                            } else {
                                                echo lang('no_data_found');
                                            }

                                            ?>
                                        </td>


                                        <td>
                                            <a href="<?php echo base_url('') ?>vaccine/viewBatchWiseVaccinationSchedule?shed_id=<?php echo $shed->sh_id; ?>"><button type="button" class="button button-info"><i class="fa fa-eye"></i> <?= lang('vaccine_schedule'); ?> </button></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<!--footer start-->



<!-- Information Popup-->
<div class="modal fade" id="informationPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-center">
        <div class="modal-content">
            <div class="modal-header bg-purple">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa-solid fa-circle-info"></i> <?php echo lang('basic_information'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <div class="row">
                    <div class="col-xs-6">
                        <ol class="information__modal__ol">
                            <li>
                                <p><?= lang('vaccination_popup_message_one'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('vaccination_popup_message_two'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('vaccination_popup_message_three'); ?></p>
                            </li>
                        </ol>
                    </div>
                    <div class="col-xs-6">
                        <img class="img-square information__modal__image" src="<?php echo base_url('uploads/information/vaccine_schedule.jpg'); ?>" alt="No img">
                    </div>
                </div>
                <section class="text-right">
                    <button type="button" class="button button-purple" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> <?= lang('close'); ?></button>
                </section>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.Information Popup-->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script>
    $(document).ready(function() {
        toastr.options = {
            'closeButton': true,
            'debug': false,
            'newestOnTop': false,
            'progressBar': true,
            'positionClass': 'toast-bottom-right',
            'preventDuplicates': false,
            'showDuration': '1000',
            'hideDuration': '1000',
            'timeOut': '10000',
            'extendedTimeOut': '1000',
            'showEasing': 'swing',
            'hideEasing': 'linear',
            'showMethod': 'fadeIn',
            'hideMethod': 'fadeOut',
        }
        <?php if ($this->session->flashdata('success')): ?>
        toastr.success('<?= html_escape($this->session->flashdata('success')); ?>');
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
        toastr.error('<?= html_escape($this->session->flashdata('error')); ?>');
        <?php endif; ?>
    });
</script>