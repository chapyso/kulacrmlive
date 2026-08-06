<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('reproduction_details'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table ">
                        <div class="clearfix">
                            <a href="<?php echo base_url('product/listLivestockReproduction'); ?>">
                                <div class="btn-group">
                                    <button class="button button-primary">
                                        <i class="fa-solid fa-circle-arrow-left"></i>
                                    </button>
                                </div>
                            </a>
                            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                        </div>
                        <div class="space15">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="new__cards__auto card__box">
                                        <h4 class="text-center"><strong><?= lang('reproduction_details'); ?> </strong></h4>
                                        <div class="col-xs-6">
                                            <table>
                                                <tr>
                                                    <td colspan="2">
                                                        <h4><?= lang('birth_from'); ?> </h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('shed'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?= $this->shed_model->getShedById($reproductionById->lrp_sh_id)->sh_no; ?>: <?= $this->shed_model->getShedById($reproductionById->lrp_sh_id)->sh_title; ?> </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('batch'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?= $reproductionById->lrp_batch_id; ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($reproductionById->lrp_sh_id, $reproductionById->lrp_batch_id)->lshs_batch_title; ?></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('livestock'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?= $this->livestock_model->getLivestockById($reproductionById->lrp_ls_id)->ls_name; ?></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('livestock_variant'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?= $this->livestock_model->getLivestockTypeById($reproductionById->lrp_lst_id)->lst_title; ?></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('reproduction_quantity'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?php
                                                                echo $reproductionById->lrp_birth_quantity . ' ' . $settings->unit;
                                                                ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('birth_date'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?php echo date("$settings->date_format", strtotime($reproductionById->lrp_birth_date)); ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('approx_selling_price_per_quantity'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?php echo $settings->currency . number_format_currency($reproductionById->lrp_approx_selling_price, 2);  ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('approx_selling_price_total'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>:
                                                            <?php
                                                            $approxTotalSellPrice = $reproductionById->lrp_birth_quantity * $reproductionById->lrp_approx_selling_price;
                                                            echo $settings->currency . number_format_currency($approxTotalSellPrice, 2);  ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('note'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?php echo $reproductionById->lrp_description; ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-xs-6">
                                            <table>
                                                <tr>
                                                    <td colspan="2">
                                                        <h4><?= lang('assign_to'); ?> </h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('shed'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?php
                                                                $singleAssignData = $this->report_model->getSingleData('live_assigned_shed_summary', ['lshs_reproduction_id' => $reproductionById->lrp_id, 'lshs_type' => 2, 'lshs_status' => 1]);
                                                                echo $this->shed_model->getShedById($singleAssignData->lshs_sh_id)->sh_no; ?>: <?= $this->shed_model->getShedById($singleAssignData->lshs_sh_id)->sh_title; ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('batch'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?php echo $reproductionById->lrp_batch_id; ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($singleAssignData->lshs_sh_id, $singleAssignData->lshs_batch_id)->lshs_batch_title;
                                                                                                                ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('assign_date'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?php echo date("$settings->date_format", strtotime($singleAssignData->lshs_assign_date)); ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('assigned_quantity'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?php $assignQuantity = $singleAssignData->lshs_assign_total_quantity;
                                                                echo $assignQuantity . ' ' . $settings->unit;
                                                                ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('sold'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?php $soldQuantity = $this->report_model->getShedAndBatchWiseSale($singleAssignData->lshs_sh_id, $singleAssignData->lshs_batch_id, 'lssv_quantity');
                                                                if ($soldQuantity) {
                                                                    echo $soldQuantity . ' ' . $settings->unit;
                                                                }
                                                                ?>
                                                        </p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('death'); ?> </strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?php $deathQuantity = $this->report_model->getSumData('livestock_death_quantity', 'ld_death_quantity', ['ld_sh_id' => $singleAssignData->lshs_sh_id, 'ld_batch_id' => $singleAssignData->lshs_batch_id, 'ld_status' => 1]);
                                                                if ($deathQuantity) {
                                                                    echo $deathQuantity . ' ' . $settings->unit;
                                                                }
                                                                ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="table__padding">
                                                        <p><strong><?= lang('in_stock'); ?></strong></p>
                                                    </td>
                                                    <td class="table__padding">
                                                        <p>: <?php $inStock = $assignQuantity - $soldQuantity - $deathQuantity;
                                                                echo $inStock . ' ' . $settings->unit
                                                                ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><strong><?= lang('batch'); ?> <?= lang('status'); ?></strong></p>
                                                    </td>
                                                    <td>:
                                                        <!-- Complete/Incomplete Status -->
                                                        <?php $batchActiveInactiveStatusInfo = $this->settings_model->getSingleData('live_assigned_shed_summary', ['lshs_sh_id' => $singleAssignData->lshs_sh_id, 'lshs_batch_id' => $singleAssignData->lshs_batch_id, 'lshs_status' => 1])->lshs_active_status;
                                                        if ($batchActiveInactiveStatusInfo == 0) { ?>
                                                            <button class="button bg-primary-light"><i class="fa-solid fa-person-running"></i> <?= lang('running'); ?></button>
                                                        <?php } else { ?>
                                                            <button class="button bg-success-light"><i class="fa-solid fa-circle-check"></i> <?= lang('completed'); ?></button>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>