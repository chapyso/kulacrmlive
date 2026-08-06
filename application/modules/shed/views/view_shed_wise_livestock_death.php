<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">

                <header class="panel-heading">
                    <i class="fa fa-money"></i> <?php echo lang('shed_wise_livestock_death'); ?>
                </header>

                <div class="panel-body">
                    <div class="clearfix">
                        <a href="<?php echo base_url('shed/listDeath'); ?>">
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
                                <th> <?= lang('assigned'); ?> <?= lang('date'); ?> </th>
                                <th> <?= lang('batch'); ?> </th>
                                <th> <?= lang('livestock_name'); ?></th>
                                <th> <?= lang('variant_name'); ?> </th>
                                <th> <?= lang('livestock_quantity'); ?> (<?= $settings->unit ?>) </th>
                                <th> <?= lang('sold'); ?> (<?= $settings->unit ?>) </th>
                                <th> <?= lang('death'); ?> (<?= $settings->unit ?>) </th>
                                <th> <?= lang('available'); ?> <?= lang('quantity'); ?> (<?= $settings->unit ?>) </th>
                                <th> <?= lang('date'); ?>/<?= lang('quantity'); ?>/<?= lang('actions'); ?> </th>
                                <th> <?= lang('option'); ?> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $serial = 0;
                            if ($assignedLivestockByShedId) {
                                foreach ($assignedLivestockByShedId as $value) {
                                    $serial++;
                            ?>
                                    <tr>
                                        <td class="table__cells"><?= $serial ?></td>
                                        <td class="table__cells"><?= date("$settings->date_format", strtotime($value->lshs_assign_date)); ?></td>
                                        <td class="table__cells"><strong><?= $value->lshs_batch_id ?>: </strong><?= $value->lshs_batch_title ?></td>
                                        <td class="table__cells"><?php
                                                                    $livestockId = $this->purchase_model->getAssignedLivestockValueDataBySummaryId($value->lshs_id)->lsh_purv_ls_id;
                                                                    $livestockTypeId = $this->purchase_model->getAssignedLivestockValueDataBySummaryId($value->lshs_id)->lsh_purv_lst_id;
                                                                    echo $this->livestock_model->getLivestockById($livestockId)->ls_name ?></td>
                                        <td class="table__cells"><?= $this->livestock_model->getLivestockTypeById($livestockTypeId)->lst_title ?></td>
                                        <td class="table__cells"><?= $value->lshs_assign_total_quantity ?></td>

                                        <td class="table__cells">
                                            <?php echo $totalSoldQuantity  = $this->sale_model->getShedAndBatchWiseLivestockSaleQuantity($value->lshs_sh_id, $value->lshs_batch_id, $this->purchase_model->getAssignedLivestockValueDataBySummaryId($value->lshs_id)->lsh_purv_ls_id, $this->purchase_model->getAssignedLivestockValueDataBySummaryId($value->lshs_id)->lsh_purv_lst_id); ?></td>

                                        <td class="table__cells">
                                            <?php $getDeathLivestockSumByLivestockAssignId = $this->shed_model->getDeathLivestockSumByLivestockAssignId($shedById->sh_id, $value->lshs_id, $livestockId, $livestockTypeId);
                                            if ($getDeathLivestockSumByLivestockAssignId) {
                                                echo $getDeathLivestockSumByLivestockAssignId;
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>

                                        <td class="table__cells">
                                            <?php
                                            echo  $value->lshs_assign_total_quantity - $getDeathLivestockSumByLivestockAssignId - $totalSoldQuantity;
                                            ?>
                                        </td>
                                        <td class="table__cells" width="25%">
                                            <?php
                                            $batchActiveInactiveStatusInfo = $this->settings_model->getSingleData('live_assigned_shed_summary', ['lshs_sh_id' => $shedById->sh_id, 'lshs_batch_id' => $value->lshs_batch_id, 'lshs_status' => 1])->lshs_active_status;


                                            $getDeathLivestockByLivestockPurchaseShedId = $this->shed_model->getDeathLivestockByLivestockPurchaseShedId($shedById->sh_id, $value->lshs_id, $livestockId, $livestockTypeId);
                                            foreach ($getDeathLivestockByLivestockPurchaseShedId as $getData) { ?>
                                                <p class="button bg-danger-light"><?= date("$settings->date_format", strtotime($getData->ld_death_date)); ?></p>
                                                <p class="button bg-info-light"><?= $getData->ld_death_quantity; ?></p>

                                                <button <?php echo ($batchActiveInactiveStatusInfo == 1) ? "disabled" : ""; ?> death_date="<?= date("$settings->date_format", strtotime($getData->ld_death_date)); ?>" shed_details="<?= $shedById->sh_no ?>: <?= $shedById->sh_title ?>" batch_details="<?= $value->lshs_batch_id ?>" live_assign_id="<?= $getData->ld_id ?>" live_shed_description="<?= $getData->ld_death_reason ?>" live_shed_id="<?= $getData->ld_sh_id ?>" death_quantity="<?= $getData->ld_death_quantity; ?>" total_quantity="<?= $value->lshs_assign_total_quantity; ?>" available_quantity="<?= $value->lshs_assign_total_quantity - $getDeathLivestockSumByLivestockAssignId - $totalSoldQuantity; ?>" purv_purs_id="<?= $getData->ld_purv_purs_id ?>" purv_ls_id="<?= $getData->ld_purv_ls_id ?>" purv_lst_id="<?= $getData->ld_purv_lst_id ?>" livestock_name=" <?= $this->livestock_model->getLivestockById($getData->ld_purv_ls_id)->ls_name; ?>" livestock_type="<?= $this->livestock_model->getLivestockTypeById($getData->ld_purv_lst_id)->lst_title; ?>" class="button button-warning editModalAssignToDeath" data-toggle="modal"><i class="fas fa-edit" aria-hidden="true"></i> Edit</button>
                                                <form action="<?php echo base_url('shed/deleteLivestockToDeath'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                    <input type="hidden" name="ld_id" value="<?= $getData->ld_id ?>">
                                                    <input type="hidden" name="ld_sh_id" value="<?= $getData->ld_sh_id ?>">
                                                    <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                    <button <?php echo ($batchActiveInactiveStatusInfo == 1) ? "disabled" : ''; ?> type="submit" class="button button-danger"><i class="fas fa-trash" aria-hidden="true"></i> <?= lang('delete'); ?> </button>
                                                </form>
                                                <br>
                                                <div class="bg-primary-light">
                                                    <strong><?= lang('note'); ?></strong>: <?= $getData->ld_death_reason; ?>
                                                </div>
                                                <br>
                                            <?php } ?>
                                        </td>

                                        <td class="table__cells">

                                            <!-- Complete/Incomplete Status -->
                                            <?php
                                            if ($batchActiveInactiveStatusInfo == 0) { ?>

                                                <?php if (($value->lshs_assign_total_quantity - $getDeathLivestockSumByLivestockAssignId - $totalSoldQuantity) == 0) { ?>
                                                    <button class='button button-danger'><i class="fa-solid fa-face-frown-open"></i> <?php echo lang('all_died'); ?></button>
                                                <?php } else { ?>
                                                    <a batch_id="<?= $value->lshs_batch_id ?>" assigned_summary_id="<?= $value->lshs_id ?>" shed_details="<?= $shedById->sh_no ?>: <?= $shedById->sh_title ?>" batch_details="<?= $value->lshs_batch_id ?>" live_quantity="<?= $value->lshs_assign_total_quantity - $getDeathLivestockSumByLivestockAssignId - $totalSoldQuantity; ?>" shed_id="<?= $shedById->sh_id ?>" purv_quantity="<?= $value->lshs_assign_total_quantity; ?>" purv_ls_id="<?= $livestockId ?>" purv_lst_id="<?= $livestockTypeId ?>" livestock_name=" <?= $this->livestock_model->getLivestockById($livestockId)->ls_name; ?>" livestock_type="<?= $this->livestock_model->getLivestockTypeById($livestockTypeId)->lst_title; ?>" class="button button-primary getDataModalAssignToDeath" data-toggle="modal"><i class="fas fa-plus-circle"></i> <?php echo lang('add_new'); ?> <?php echo lang('death'); ?> </a>
                                                <?php } ?>

                                            <?php } else { ?>
                                                <button class="button bg-success-light" title="<?= lang('this_batch_is_already_completed'); ?>"><i class="fa-solid fa-circle-check"></i> <?php echo lang('completed'); ?></button>
                                            <?php }  ?>


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

<!-- Add death livestock -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?php echo lang('add_new_death_quantity'); ?> </strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('shed/insertLivestockToDeath'); ?>" method="post" enctype="multipart/form-data">
                    <table class="table table-bordered">
                        <tr>
                            <td width="50%"> <?php echo lang('shed_and_title'); ?>: </td>
                            <td id="shed_details" width="50%"></td>
                        </tr>
                        <tr>
                            <td width="50%"> <?php echo lang('batch_number'); ?>: </td>
                            <td id="batch_details" width="50%"></td>
                        </tr>
                        <tr>
                            <td width="50%"> <?php echo lang('livestock_name'); ?>: </td>
                            <td id="livestock_name" width="50%"></td>
                        </tr>
                        <tr>
                            <td> <?php echo lang('livestock_variant'); ?>:</td>
                            <td id="livestock_type"></td>
                        </tr>
                        <tr>
                            <td> <?php echo lang('total'); ?> <?php echo lang('quantity'); ?> (<?php echo $settings->unit; ?>):</td>
                            <td id="livestock_quantity_value"></td>
                        </tr>
                        <tr>
                            <td> <?php echo lang('available'); ?> <?php echo lang('quantity'); ?> (<?php echo $settings->unit; ?>): </td>
                            <td id="available_quantity"></td>
                        </tr>
                    </table>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="assign_quantity"> <?php echo lang('death'); ?> <?php echo lang('quantity'); ?><span class="text-danger">*</span></label>
                            <input type="number" class="form-control input__number" name="ld_death_quantity" id="assign_quantity" value='' placeholder="" required>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for=""> <?php echo lang('death'); ?> <?php echo lang('date'); ?><span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" name="ld_death_date" id="" value='<?= get_current_date(); ?>' placeholder="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"> <?php echo lang('death'); ?> <?php echo lang('reason'); ?><span class="text-danger">*</span></label>
                        <textarea name="ld_death_reason" class="form-control " id="lsh_description" rows="5" placeholder="Enter Death Reason" style="height: auto !important;" required></textarea>
                    </div>
                    <input type="hidden" name="ld_lshs_id" id="assigned_summary_id" value='' placeholder="">
                    <input type="hidden" name="ld_batch_id" id="batch_id" value='' placeholder="">
                    <input type="hidden" name="ld_purv_ls_id" id="purv_ls_id" value='' placeholder="">
                    <input type="hidden" name="ld_purv_lst_id" id="purv_lst_id" value='' placeholder="">
                    <input type="hidden" name="ld_shed_id" id="sheds_id" value='' placeholder="">
                    <input type="hidden" name="" id="livestock_quantity" value='' placeholder="">
                    <input type="hidden" name="" id="live_quantity_hidden" value='' placeholder="">
                    <section class="text-right">
                        <button type="submit" name="submit" id="assignToShedButton" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Edit death livestock -->
<div class="modal fade " id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?php echo lang('update_death_quantity'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('shed/updateLivestockToDeath'); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                    <table class="table table-bordered">
                        <tr>
                            <td width="50%"><?php echo lang('shed_and_title'); ?>: </td>
                            <td id="edit_shed_details" width="50%"></td>
                        </tr>
                        <tr>
                            <td width="50%"><?php echo lang('batch_number'); ?>: </td>
                            <td id="edit_batch_details" width="50%"></td>
                        </tr>
                        <tr>
                            <td width="50%"><?php echo lang('livestock_name'); ?>: </td>
                            <td id="edit_livestock_name" width="50%"></td>
                        </tr>
                        <tr>
                            <td><?php echo lang('variant_name'); ?>:</td>
                            <td id="edit_livestock_type"></td>
                        </tr>
                        <tr>
                            <td><?php echo lang('total'); ?> <?php echo lang('quantity'); ?> (<?php echo $settings->unit; ?>):</td>
                            <td id="edit_livestock_quantity_value"></td>
                        </tr>
                        <tr>
                            <td><?php echo lang('available'); ?> <?php echo lang('quantity'); ?> (<?php echo $settings->unit; ?>): </td>
                            <td id="edit_available_quantity"></td>
                        </tr>
                    </table>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="assign_quantity"> <?php echo lang('death'); ?> <?php echo lang('quantity'); ?><span class="text-danger">*</span></label>
                            <input type="number" class="form-control input__number" name="ld_death_quantity" id="edit_death_quantity" value='' placeholder="" required>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for=""> <?php echo lang('death'); ?> <?php echo lang('date'); ?><span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" name="ld_death_date" id="edit_death_date" value='' placeholder="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="ld_death_reason" class="form-control" id="edit_lsh_description" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="ld_id" id="edit_live_assign_id" value='' placeholder="">
                    <input type="hidden" name="ld_sh_id" id="edit_shed_id" value='' placeholder="">
                    <input type="hidden" name="" id="edit_available_quantity_hidden" value='' placeholder="">
                    <section class="text-right">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


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
                                <p><?= lang('shed_death_popup_message_one'); ?> </p>
                            </li>
                            <li>
                                <p><?= lang('shed_death_popup_message_two'); ?> </p>
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

<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Get Data
        $(".getDataModalAssignToDeath").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var shed_id = $(this).attr('shed_id');
            var assigned_summary_id = $(this).attr('assigned_summary_id');
            var livestock_name = $(this).attr('livestock_name');
            var purv_ls_id = $(this).attr('purv_ls_id');
            var livestock_type = $(this).attr('livestock_type');
            var shed_details = $(this).attr('shed_details');
            var batch_details = $(this).attr('batch_details');
            var purv_lst_id = $(this).attr('purv_lst_id');
            var batch_id = $(this).attr('batch_id');
            var purv_quantity = $(this).attr('purv_quantity');
            var live_quantity = $(this).attr('live_quantity');
            // Assign Value 
            $("#sheds_id").val(shed_id);
            $("#assigned_summary_id").val(assigned_summary_id);
            $("#livestock_name").text(livestock_name);
            $("#purv_ls_id").val(purv_ls_id);
            $("#livestock_type").text(livestock_type);
            $("#purv_lst_id").val(purv_lst_id);
            $("#batch_id").val(batch_id);
            $("#shed_details").text(shed_details);
            $("#batch_details").text(batch_details);
            $("#livestock_quantity").val(purv_quantity);
            $("#livestock_quantity_value").text(purv_quantity);
            $("#available_quantity").text(live_quantity);
            $("#live_quantity_hidden").val(live_quantity);
            $("#assign_quantity").val('');
            $("#lsh_description").val('');
            $('#myModal').modal('show');
            // Keyup calculate value
            $("#assign_quantity").keyup(function() {
                var assign_quantity = $("#assign_quantity").val();
                var livestock_quantity = $("#live_quantity_hidden").val();
                var available = parseFloat(livestock_quantity) - parseFloat(assign_quantity);
                $("#available_quantity").text(available);

                // alert(available)
                if (parseFloat(assign_quantity) > parseFloat(livestock_quantity)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning',
                        text: '<?= lang('please_enter_a_valid_value'); ?>',
                    });
                    $("#assign_quantity").val('');
                    $("#available_quantity").text(livestock_quantity);
                }
            });
        });

        // Edit Data
        $(".editModalAssignToDeath").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var live_assign_id = $(this).attr('live_assign_id');
            var purv_purs_id = $(this).attr('purv_purs_id');
            var livestock_name = $(this).attr('livestock_name');
            var purv_ls_id = $(this).attr('purv_ls_id');
            var livestock_type = $(this).attr('livestock_type');
            var purv_lst_id = $(this).attr('purv_lst_id');
            var live_shed_id = $(this).attr('live_shed_id');
            var total_quantity = $(this).attr('total_quantity');
            var death_quantity = $(this).attr('death_quantity');
            var available_quantity = $(this).attr('available_quantity');
            var live_shed_description = $(this).attr('live_shed_description');
            var shed_details = $(this).attr('shed_details');
            var batch_details = $(this).attr('batch_details');
            var death_date = $(this).attr('death_date');
            // Assign Value 
            $("#edit_live_assign_id").val(live_assign_id);
            $("#edit_livestock_name").text(livestock_name);
            $("#edit_purv_ls_id").val(purv_ls_id);
            $("#edit_livestock_type").text(livestock_type);
            $("#edit_purv_lst_id").val(purv_lst_id);
            $("#edit_shed_id").val(live_shed_id);
            $("#edit_livestock_quantity").val(total_quantity);
            $("#edit_lsh_description").val(live_shed_description);
            $("#edit_available_quantity").text(available_quantity);
            $("#edit_death_quantity").val(death_quantity);
            $("#edit_available_quantity_hidden").val(available_quantity);
            $("#edit_livestock_quantity_value").text(total_quantity);
            $("#edit_shed_details").text(shed_details);
            $("#edit_batch_details").text(batch_details);
            $("#edit_death_date").val(death_date);
            // Keyup calculate value 
            $("#edit_death_quantity").keyup(function() {
                var available_quantity = $("#edit_available_quantity_hidden").val();
                var death_quantity_edit = $("#edit_death_quantity").val();

                var totalAvailableAfterKeyup = parseFloat(available_quantity) + parseFloat(death_quantity);
                $("#edit_available_quantity").text(parseFloat(totalAvailableAfterKeyup));

                var available = parseFloat(totalAvailableAfterKeyup) - parseFloat(death_quantity_edit);
                $("#edit_available_quantity").text(parseFloat(available));

                if (parseFloat(death_quantity_edit) > parseFloat(totalAvailableAfterKeyup)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning',
                        text: '<?= lang('please_enter_a_valid_value'); ?>',
                    });
                    $("#edit_death_quantity").val(death_quantity);
                    $("#edit_available_quantity").text(available_quantity);
                }

            });
            $('#myModal2').modal('show');
        });


    });
</script>
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