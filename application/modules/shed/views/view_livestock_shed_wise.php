<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?php echo lang('shed_wise_stock_quantity'); ?>
                </header>
                <div class="panel-body">
                    <div class="clearfix">
                        <a href="<?php echo base_url('shed/addShed'); ?>">
                            <div class="btn-group">
                                <button class="button button-primary">
                                    <i class="fa-solid fa-circle-arrow-left"></i>
                                </button>
                            </div>
                        </a>
                        <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>

                        <a data-toggle="modal" href="#informationPopup" class="button button-purple export"><i class="fa-solid fa-circle-question"></i> <?= lang('information'); ?></a>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="new__cards__auto card__box">
                                <div class="col-xs-4">
                                    <table class="table">
                                        <thead>
                                            <th colspan="4" class="text-center"><?= lang('basic_information') ?></th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="50%"><strong><?php echo lang('shed_no'); ?>.: </strong></td>
                                                <td width="50%"><?= $shedById->sh_no ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong><?php echo lang('shed_title'); ?>: </strong></td>
                                                <td><?= $shedById->sh_title ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong><?php echo lang('shed'); ?> <?php echo lang('description'); ?>: </strong></td>
                                                <td><?= $shedById->sh_description ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong><?php echo lang('assigned_batch_quantity'); ?>: </strong></td>
                                                <td><?= $this->shed_model->getCountShedWiseBatchAssignedQuantity($shedById->sh_id); ?> (<?= $settings->unit ?>)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-xs-4">
                                    <table class="table">
                                        <thead>
                                            <th colspan="4" class="text-center"><?= lang('stock_information') ?></th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="50%"><strong><?php echo lang('total_livestock'); ?>: </strong></td>
                                                <td width="50%"><?php $totalLive = $this->shed_model->getShedWiseLivestock($shedById->sh_id);
                                                                if ($totalLive) {
                                                                    echo $totalLive;
                                                                } else {
                                                                    echo 0;
                                                                }
                                                                ?> <?= $settings->unit; ?></td>
                                            </tr>
                                            <tr>
                                                <td> <strong><?php echo lang('total'); ?> <?php echo lang('sold'); ?>: </strong></td>
                                                <td> <?php $sold = $this->sale_model->getShedWiseSoldLivestock($shedById->sh_id);
                                                        if ($sold) {
                                                            echo $sold . ' ' . $settings->unit;
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong><?php echo lang('total'); ?> <?php echo lang('death'); ?>:</strong></td>
                                                <td> <?php $death = $this->shed_model->getShedWiseDeathLivestock($shedById->sh_id);
                                                        if ($death) {
                                                            echo $death . ' ' . $settings->unit;
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong><?php echo lang('in_stock'); ?>: </strong></td>
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
                                <th> <?= lang('serialNo'); ?> </th>
                                <th> <?= lang('assigned_date'); ?> </th>
                                <th> <?= lang('batch'); ?> </th>
                                <th> <?= lang('livestock_name'); ?> </th>
                                <th> <?= lang('variant_name'); ?> </th>
                                <th> <?= lang('total_assigned_quantity'); ?> (<?= $settings->unit ?>) </th>
                                <th> <?= lang('total'); ?> <?= lang('sold'); ?> (<?= $settings->unit ?>) </th>
                                <th> <?= lang('total'); ?> <?= lang('death'); ?> (<?= $settings->unit ?>) </th>
                                <th> <?= lang('in_stock'); ?> (<?= $settings->unit ?>)</th>
                                <th> <?= lang('batch'); ?> <?= lang('status'); ?></th>
                                <th> <?= lang('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $serial = 0;
                            if ($assignedBatchesByShedId) {
                                foreach ($assignedBatchesByShedId as $value) {
                                    $serial++;
                            ?>
                                    <tr>
                                        <td><?= $serial ?></td>
                                        <td><?= date("$settings->date_format", strtotime($value->lshs_assign_date)); ?></td>
                                        <td><strong><?= $value->lshs_batch_id ?>: <?= $value->lshs_batch_title ?></strong></td>
                                        <td><?= $this->livestock_model->getLivestockById($this->purchase_model->getAssignedLivestockValueDataBySummaryId($value->lshs_id)->lsh_purv_ls_id)->ls_name ?></td>
                                        <td><?= $this->livestock_model->getLivestockTypeById($this->purchase_model->getAssignedLivestockValueDataBySummaryId($value->lshs_id)->lsh_purv_lst_id)->lst_title ?></td>
                                        <td><?= $value->lshs_assign_total_quantity ?></td>
                                        <td><?php echo $totalSoldQuantity  = $this->sale_model->getShedAndBatchWiseLivestockSaleQuantity($value->lshs_sh_id, $value->lshs_batch_id, $this->purchase_model->getAssignedLivestockValueDataBySummaryId($value->lshs_id)->lsh_purv_ls_id, $this->purchase_model->getAssignedLivestockValueDataBySummaryId($value->lshs_id)->lsh_purv_lst_id); ?></td>
                                        <td><?php echo $totalDeathQuantity = $this->shed_model->getDeathLivestockSumByShedAndBatch($value->lshs_sh_id, $value->lshs_batch_id); ?></td>
                                        <td>
                                            <?php $inStock = $value->lshs_assign_total_quantity - ($totalSoldQuantity + $totalDeathQuantity);
                                            if ($inStock) {
                                                echo $inStock;
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($value->lshs_active_status == 0) { ?>
                                                <button class="button bg-primary-light"><i class="fa-solid fa-person-running"></i> <?= lang('running'); ?></button>
                                            <?php } else { ?>
                                                <button class="button bg-success-light"><i class="fa-solid fa-circle-check"></i> <?= lang('completed'); ?></button>
                                            <?php } ?>
                                        </td>
                                        <td width="20%">
                                            <?php if ($value->lshs_active_status == 0) { ?>
                                                <a href="<?php echo base_url('') ?>shed/batchInactiveStatus?shed_id=<?php echo $value->lshs_sh_id; ?>&&lshs_id=<?php echo $value->lshs_id; ?>" onclick="return confirm('<?= lang('are_you_sure_this_batch_is_completed'); ?>');"><button <?php echo ($inStock == 0) ? true : "disabled"; ?> class="button button-primary"><i class="fa-solid fa-check-double"></i><?= lang('click_to_complete'); ?></button></a>
                                            <?php } else { ?>
                                                <a href="<?php echo base_url('') ?>shed/batchActiveStatus?shed_id=<?php echo $value->lshs_sh_id; ?>&&lshs_id=<?php echo $value->lshs_id; ?>" onclick="return confirm('<?= lang('are_you_sure_this_batch_is_not_completed_yet'); ?>');"><button class="button button-warning"><i class="fa-solid fa-rotate-left"></i> <?= lang('click_to_running'); ?> </button></a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                            <?php }
                            } ?>
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



<!-- Information Popup-->
<div class="modal fade" id="informationPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-center">
        <div class="modal-content">
            <div class="modal-header bg-purple">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><strong><i class="fa-solid fa-circle-info"></i> <?php echo lang('basic_information'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <div class="row">
                    <div class="col-xs-6">
                        <ol class="information__modal__ol">
                            <li>
                                <p><?= lang('shed_details_popup_message_one'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('shed_details_popup_message_two'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('shed_details_popup_message_three'); ?></p>
                            </li>
                        </ol>
                    </div>
                    <div class="col-xs-6">
                        <img class="img-square information__modal__image" src="<?php echo base_url('uploads/avatar/livestock-dummy-image.jpg'); ?>" alt="No img">
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