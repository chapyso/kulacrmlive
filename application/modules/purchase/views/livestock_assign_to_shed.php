<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="row">
            <div class="col-md-12 col-12">
                <section class="panel">
                    <header class="panel-heading noprint">
                        <i class="fa-solid fa-people-carry-box"></i> <?php echo lang('assign_to_shed'); ?>
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="adv-table editable-table">
                                    <div class="clearfix">

                                        <a href="<?php echo base_url('') ?>shed/addShed">
                                            <div class="btn-group">
                                                <button class="button button-info">
                                                    <i class="fa-solid fa-circle-arrow-right"></i> <?php echo lang('shed'); ?> <?php echo lang('list'); ?>
                                                </button>
                                            </div>
                                        </a>

                                        <a data-toggle="modal" href="#informationPopup" class="button button-purple export"><i class="fa-solid fa-circle-question"></i> <?= lang('information'); ?></a>

                                    </div>

                                    <div class="space15">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="new__cards__auto card__box">
                                                    <div class="col-xs-12">
                                                        <table class="table">
                                                            <tr>
                                                                <td><?php echo lang('total_purchased'); ?> (<?= $settings->unit; ?>)</td>
                                                                <td><?php $totalPurchaseQuantity = $this->purchase_model->getTotalPurchasedLivestockQuantity();
                                                                    if ($totalPurchaseQuantity) {
                                                                        echo $totalPurchaseQuantity;
                                                                    } else {
                                                                        echo 0;
                                                                    }
                                                                    ?></td>
                                                            </tr>
                                                            <?php $totalBirthQuantity = $this->report_model->getSumData('live_assigned_shed_summary', 'lshs_assign_total_quantity', ['lshs_status' => 1, 'lshs_type' => 2]); ?>
                                                            <?php if ($totalBirthQuantity) { ?>
                                                                <tr>
                                                                    <td><?php echo lang('reproduction'); ?> <?php echo lang('quantity'); ?> (<?= $settings->unit; ?>)</td>
                                                                    <td><?php echo $totalBirthQuantity; ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                            <tr>
                                                                <td><?php echo lang('total_assigned_to_shed'); ?> (<?= $settings->unit; ?>)</td>
                                                                <td><?php
                                                                    $totalAssignedQuantity = $this->purchase_model->getTotalLivestockAssignedToShedQuantity();
                                                                    if ($totalAssignedQuantity) {
                                                                        echo $totalAssignedQuantity;
                                                                    } else {
                                                                        echo 0;
                                                                    }
                                                                    ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?php echo lang('out_of_shed'); ?> (<?= $settings->unit; ?>)</td>
                                                                <td><?php
                                                                    $outOfShed = ($totalPurchaseQuantity + $totalBirthQuantity) - ($totalAssignedQuantity);
                                                                    if ($outOfShed) {
                                                                        echo $outOfShed;
                                                                    } else {
                                                                        echo 0;
                                                                    }
                                                                    ?></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="over__flow__table">
                                        <table class="table table-striped table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th><?php echo lang('livestock_name'); ?></th>
                                                    <th><?php echo lang('livestock_variant'); ?></th>
                                                    <th><?php echo lang('quantity'); ?></th>
                                                    <th><?php echo lang('total_assigned_to_shed'); ?></th>
                                                    <th><?php echo lang('available'); ?> </th>
                                                    <th><?php echo lang('options'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $serial = 0;
                                                foreach ($livestocks as $list) {
                                                    $serial++;
                                                ?>
                                                    <tr class="table__rows">
                                                        <td rowspan="<?= $this->livestock_model->countLivestockVariantByLivestockId($list->ls_id) + 1; ?>" class="table__cells">
                                                            <?= $list->ls_name ?>
                                                        </td>
                                                        <?php
                                                        $types = $this->livestock_model->getLivestockTypeByLivestockId($list->ls_id);
                                                        foreach ($types as $value) {
                                                        ?>
                                                    <tr>
                                                        <td><?= $value->lst_title ?></td>
                                                        <td>
                                                            <?php
                                                            $getPurchasedLivestockQuantity = $this->purchase_model->getPurchasedLivestockByLivestockAndType($list->ls_id, $value->lst_id);
                                                            if ($getPurchasedLivestockQuantity) {
                                                                echo $getPurchasedLivestockQuantity;
                                                            } else {
                                                                echo 0;
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $getAssignedLivestockSumByLivestockId = $this->purchase_model->getAssignedLivestockSumByLivestockPurchaseSummaryId($list->ls_id, $value->lst_id);
                                                            if ($getAssignedLivestockSumByLivestockId) {
                                                                echo $getAssignedLivestockSumByLivestockId;
                                                            } else {
                                                                echo 0;
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $getPurchasedLivestockQuantity - $getAssignedLivestockSumByLivestockId; ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if ($getPurchasedLivestockQuantity == $getAssignedLivestockSumByLivestockId) {
                                                                if ($getPurchasedLivestockQuantity > 0) {
                                                            ?>
                                                                    <button class="button button-success"><i class="fa-solid fa-circle-check"></i> <?= lang('done'); ?></button>
                                                                <?php }
                                                            } else { ?>
                                                                <a total_quantity="<?php echo $getPurchasedLivestockQuantity ?>" available-quantity="<?php echo $getPurchasedLivestockQuantity - $getAssignedLivestockSumByLivestockId; ?>" ls_id="<?= $list->ls_id ?>" ls_title="<?= $list->ls_name ?>" lst_id="<?= $value->lst_id ?>" lst_title="<?= $value->lst_title ?>" class="button button-primary getDataModalAssignToShed" data-toggle="modal"><i class="fa-solid fa-paper-plane"></i> <?= lang('assign'); ?></a>
                                                            <?php }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php }
                                                ?>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-md-12 col-12">
                <section class="panel">
                    <header class="panel-heading noprint">
                        <i class="fas fa-stream"></i> <?php echo lang('assigned_lists'); ?>
                    </header>
                    <div class="panel-body">
                        <a data-toggle="modal" href="#informationPopupForAssigned" class="button button-purple export"><i class="fa-solid fa-circle-question"></i> <?= lang('information'); ?></a>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="adv-table editable-table">
                                    <div class="space15"></div>
                                    <div class="over__flow__table">
                                        <table class="table table-striped table-hover table-bordered" id="editable-sample" style="overflow:auto;">
                                            <thead>
                                                <tr>
                                                    <th><?= lang('serialNo'); ?>SL.</th>
                                                    <th><?= lang('assigned'); ?> <?= lang('date'); ?></th>
                                                    <th><?= lang('livestocks'); ?></th>
                                                    <th><?= lang('variant'); ?></th>
                                                    <th><?= lang('assigned_quantity'); ?></th>
                                                    <th><?= lang('shed'); ?></th>
                                                    <th><?= lang('batch'); ?></th>
                                                    <th><?= lang('actions'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $serial = 0;
                                                foreach ($assignedSummaryData as $data) {
                                                    $serial++;
                                                ?>
                                                    <tr>
                                                        <td class="table__cells"><?= $serial; ?></td>
                                                        <td class="table__cells">
                                                            <p class="badge" style="padding: 8px;"><?= date("$settings->date_format", strtotime($data->lshs_assign_date)); ?></p>
                                                        </td>
                                                        <td class="table__cells"><?php $livestockAssignedValueData = $this->purchase_model->getAssignedLivestockValueDataBySummaryId($data->lshs_id);
                                                                                    echo $this->livestock_model->getLivestockById($livestockAssignedValueData->lsh_purv_ls_id)->ls_name;
                                                                                    ?></td>
                                                        <td class="table__cells"><?php
                                                                                    echo $this->livestock_model->getLivestockTypeById($livestockAssignedValueData->lsh_purv_lst_id)->lst_title;
                                                                                    ?></td>
                                                        <td class="table__cells"><?= $data->lshs_assign_total_quantity ?></td>
                                                        <td class="table__cells">
                                                            <strong><?php echo $this->shed_model->getShedById($data->lshs_sh_id)->sh_no; ?>:</strong> <?php echo $this->shed_model->getShedById($data->lshs_sh_id)->sh_title; ?>
                                                            <?php $shedInfo = $this->shed_model->getShedById($data->lshs_sh_id)->sh_no . ": " . $this->shed_model->getShedById($data->lshs_sh_id)->sh_title; ?>
                                                        </td>
                                                        <td class="table__cells">
                                                            <strong><?= $data->lshs_batch_id; ?>:</strong> <?= $data->lshs_batch_title; ?>
                                                        </td>
                                                        <td class="table__cells">
                                                            <!-- Complete/Incomplete Status -->
                                                            <?php $batchActiveInactiveStatusInfo = $this->settings_model->getSingleData('live_assigned_shed_summary', ['lshs_sh_id' => $data->lshs_sh_id, 'lshs_batch_id' => $data->lshs_batch_id, 'lshs_status' => 1])->lshs_active_status;
                                                            if ($batchActiveInactiveStatusInfo == 0) { ?>

                                                                <?php if ($data->lshs_type == 1) { ?>
                                                                    <button batch_title="<?= $data->lshs_batch_title; ?>" assigned-summary-id="<?= $data->lshs_id; ?>" live-assign-date="<?= date("$settings->date_format", strtotime($data->lshs_assign_date)); ?>" live_batch_id="<?= $data->lshs_batch_id; ?>" live_assign_id="<?= $data->lshs_sh_id; ?>" purv_total_quantity="<?= $data->lshs_assign_total_quantity; ?>" live_shed_description="<?= $data->lshs_description ?>" live_shed_id="<?= $data->lshs_sh_id ?>" live_shed_info="<?= $shedInfo ?>" assigned_quantity="<?= $data->lshs_assign_total_quantity; ?>" available_quantity="<?php  ?>" ls_id="<?= $livestockAssignedValueData->lsh_purv_ls_id; ?>" lst_id="<?= $livestockAssignedValueData->lsh_purv_lst_id; ?>" livestock_name=" <?= $this->livestock_model->getLivestockById($livestockAssignedValueData->lsh_purv_ls_id)->ls_name; ?>" livestock_type="<?= $this->livestock_model->getLivestockTypeById($livestockAssignedValueData->lsh_purv_lst_id)->lst_title; ?>" class="button button-warning editModalAssignToShed" data-toggle="modal"><i class="fas fa-edit"></i> <?php echo lang('edit'); ?></button>
                                                                    <!-- conditional delete -->
                                                                    <?php
                                                                    $countDataIfAvailableInFood = $this->settings_model->getCountRow('food_value', 'fdv_id', ['fdv_assigned_shed_id' => $data->lshs_sh_id, 'fdv_assigned_batch_id' => $data->lshs_batch_id, 'fdv_status' => 1]);
                                                                    $countDataIfAvailableInLivestockSale = $this->settings_model->getCountRow('livestock_sale_value', 'lssv_id', ['lssv_shed_id' => $data->lshs_sh_id, 'lssv_batch_id' => $data->lshs_batch_id, 'lssv_status' => 1]);
                                                                    $countDataIfAvailableInProduct = $this->settings_model->getCountRow('product_assign', 'pra_id', ['pra_shed_id' => $data->lshs_sh_id, 'pra_batch_id' => $data->lshs_batch_id, 'pra_status' => 1]);
                                                                    if ($countDataIfAvailableInFood  == 0 && $countDataIfAvailableInLivestockSale == 0 && $countDataIfAvailableInProduct == 0) { ?>
                                                                        <form action="<?php echo base_url('purchase/deleteLivestockToShed'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                                            <input type="hidden" name="lshs_id" value="<?= $data->lshs_id ?>">
                                                                            <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                                            <button type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                                                        </form>
                                                                    <?php } else { ?>
                                                                        <button type="button" class="button button-danger" title="<?= lang('this_item_used_another_places'); ?>" disabled><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                                                    <?php } ?>
                                                                    <!-- /.conditional delete -->
                                                                <?php } else { ?>
                                                                    <span class="btn bg-primary-light"><?= lang('reproduction'); ?></span>
                                                                <?php } ?>

                                                            <?php } else { ?>
                                                                <button class="button bg-success-light" title="<?= lang('this_batch_is_already_completed'); ?>"><i class="fa-solid fa-circle-check"></i> <?php echo lang('completed'); ?></button>
                                                            <?php }  ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<!--footer start-->

<!-- Assign livestock to shed Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fas fa-plus-circle"></i> <?php echo lang('assign_to_shed'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('purchase/insertLivestockToShed'); ?>" method="post" enctype="multipart/form-data">
                    <table class="table table-bordered">
                        <tr>
                            <td width="50%"><?php echo lang('livestock_name'); ?>: </td>
                            <td id="livestock_name" width="50%"></td>
                        </tr>
                        <tr>
                            <td><?php echo lang('livestock_variant'); ?>:</td>
                            <td id="livestock_type"></td>
                        </tr>
                        <tr>
                            <td><?php echo lang('total'); ?> <?php echo lang('quantity'); ?> (<?php echo $settings->unit; ?>):</td>
                            <td id="livestock_quantity_value"></td>
                        </tr>
                        <tr>
                            <td><?php echo lang('available'); ?> <?php echo lang('quantity'); ?> (<?php echo $settings->unit; ?>): </td>
                            <td id="available_quantity"></td>
                        </tr>
                        <tr>
                            <td><?php echo lang('batch_no'); ?>.(<?php echo lang('auto_generated'); ?>)</td>
                            <td id="newBatchNumberUnderShed">
                            </td>
                            <input type="hidden" class="form-control" name="lsh_batch_number" id="lsh_batch_number" value='' required>
                        </tr>
                    </table>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td><?php echo lang('purchase_bill_no'); ?>.</td>
                                <td><?php echo lang('purchase_quantity'); ?></td>
                                <td><?php echo lang('assigned_quantity'); ?></td>
                                <td><?php echo lang('available_to_quantity'); ?></td>
                                <td><?php echo lang('assign_to_quantity'); ?></td>
                                <td><?php echo lang('dont_want_to_assign_now'); ?> </td>
                            </tr>
                        </thead>
                        <tbody class="viewP" id="disableSubmitButton">
                        </tbody>
                    </table>
                    <div id="invalidInfo" class="text-center">

                    </div>
                    <hr />
                    <!-- Check Button -->
                    <table class="table table-striped table-hover table-bordered">
                        <tr>
                            <td class="table__cells">
                                <label for="chkPassport">
                                    <input type="checkbox" id="chkPassport" />
                                    <?php echo lang('do_you_want_to_assign_to_an_existing_batch'); ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                    <!-- Existing Batch -->
                    <div id="showExistingRow" style="display: none">
                        <div class="row" id="hideRow">
                            <div class="form-group col-sm-6">
                                <label for="shed_id" class="text-danger"><?php echo lang('existing'); ?> <?php echo lang('sheds'); ?><span class="text-danger">*</span></label>
                                <select name="lsh_sh_id" id="existing_shed_id" class="form-control js-example-basic-single " placeholder="" style="width: 100%;" required>
                                    <option value=""><?php echo lang('please_select_shed'); ?></option>
                                    <?php if (!empty($sheds)) foreach ($sheds as $shed) { ?>
                                        <option value="<?php echo $shed->sh_id; ?>"> <?php echo $shed->sh_no; ?>. <?php echo $shed->sh_title; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="" class="text-danger"><?php echo lang('existing'); ?> <?php echo lang('batch'); ?> <span class="text-danger">*</span></label>
                                <select name="existing_batch_id" class="form-control js-example-basic-single" id="existing_batch_id" placeholder="" style="width: 100%;" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.Existing Batch -->
                    <!-- New Batch -->
                    <div class="row" id="hideDefaultRow">
                        <div class="form-group col-sm-6">
                            <label for="shed_id"><?php echo lang('sheds'); ?><span class="text-danger">*</span></label>
                            <select name="lsh_sh_id" id="shed_id" class="form-control js-example-basic-single " placeholder="" style="width: 100%;" required>
                                <option value=""><?php echo lang('please_select_shed'); ?></option>
                                <?php if (!empty($sheds)) foreach ($sheds as $shed) { ?>
                                    <option value="<?php echo $shed->sh_id; ?>"> <?php echo $shed->sh_no; ?>. <?php echo $shed->sh_title; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for=""><?php echo lang('new_batch_title'); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="lsh_batch_title" id="uniqueBatchTitle" value='' placeholder="" required readonly>
                        </div>
                    </div>
                    <div class="text-center border" id="totalLivestock">

                    </div>
                    <!-- /.New Batch -->
                    <div class="form-group">
                        <label for="assign_date"><?php echo lang('assign_date'); ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker" name="lsh_assign_date" id="" value='<?= get_current_date(); ?>' placeholder="" required>
                        <div class="bg-primary-light vaccination__date__warning"><strong class="text-danger"><?= lang('note'); ?>: </strong><?= lang('all_vaccination_dates_will_be_added_under_this_date'); ?></div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="lsh_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <section class="text-right">
                        <button type="submit" name="submit" class="button button-info submit_button add_button" id="submitButton"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Edit Assign livestock to shed Modal -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?php echo lang('update_assign_to_shed'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('purchase/updateLivestockToShed'); ?>" method="post" enctype="multipart/form-data">
                    <table class="table table-bordered">
                        <tr>
                            <td width="50%"><?php echo lang('livestock_name'); ?>: </td>
                            <td id="edit_livestock_name" width="50%"></td>
                        </tr>
                        <tr>
                            <td><?php echo lang('livestock_type'); ?>:</td>
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
                        <tr>
                            <td><?php echo lang('shed'); ?></td>
                            <td id="edit_shed_info"></td>
                        </tr>
                        <tr>
                            <td><?php echo lang('batch'); ?></td>
                            <td id="edit_batch_id"></td>
                        </tr>
                    </table>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td width="20%"><?php echo lang('purchase_bill_no'); ?></td>
                                <td width="20%"><?php echo lang('purchase_quantity'); ?></td>
                                <td width="20%"><?php echo lang('available_to_quantity'); ?></td>
                                <td width="20%"><?php echo lang('assign_to_quantity'); ?></td>
                                <td width="20%"><?php echo lang('dont_want_to_assign_now'); ?></td>
                            </tr>
                        </thead>
                        <tbody class="viewEdit" id="disableSubmitButtonEdit">
                        </tbody>
                    </table>
                    <div id="invalidInfoEdit" class="text-center">

                    </div>
                    <div class="form-group">
                        <label for="assign_date"><?php echo lang('assign_date'); ?> <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control datepicker" name="lsh_assign_date" id="edit_assign_date" value='' placeholder="" required>
                        <div class="bg-primary-light vaccination__date__warning"><strong class="text-danger"><?= lang('note'); ?>: </strong><?= lang('all_vaccination_dates_will_be_added_under_this_date'); ?></div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="lsh_description" class="form-control" id="edit_lsh_description" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="lsh_lshs_id" id="edit_live_assign_id" value='' placeholder="" required>
                    <input type="hidden" name="batch_id" id="edit_batch_iid" value='' placeholder="" required>
                    <input type="hidden" name="lsh_sh_id" id="edit_shed_id" value='' placeholder="" required>
                    <section class="text-right">
                        <button type="button" class="button button-default" data-dismiss="modal"><?php echo lang('cancel'); ?></button>
                        <button type="submit" name="submit" class="button button-info submit_button edit_button" id="submitButtonEdit"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.Edit Assign livestock to shed Modal -->

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
                                <p><?= lang('assign_popup_message_one'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('assign_popup_message_two'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('assign_popup_message_three'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('assign_popup_message_four'); ?></p>
                            </li>
                        </ol>
                    </div>
                    <div class="col-xs-6">
                        <img class="img-square information__modal__image" src="<?php echo base_url('uploads/information/assign_to_shed.jpg'); ?>" alt="No img">
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


<!-- Information Popup for assigned -->
<div class="modal fade" id="informationPopupForAssigned" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
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
                                <p><?= lang('assign_popup_message_five'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('assign_popup_message_six'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('assign_popup_message_seven'); ?></p>
                            </li>
                        </ol>
                    </div>
                    <div class="col-xs-6">
                        <img class="img-square information__modal__image" src="<?php echo base_url('uploads/information/assign_to_shed_list.jpg'); ?>" alt="No img">
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

<input type="hidden" id="livestockPurchasedQuantityAlert" value="<?php echo $this->report_model->getCountRow('livestock_purchase_summary', 'purs_id', ['purs_status' => 1]); ?>">
<input type="hidden" id="shedQuantityAlert" value="<?php echo $this->report_model->getCountRow('shed', 'sh_id', ['sh_status' => 1]); ?>">


<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Get Data
        $(".getDataModalAssignToShed").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute 
            var total_quantity = $(this).attr('total_quantity');
            var available_quantity = $(this).attr('available-quantity');
            var ls_id = $(this).attr('ls_id');
            var ls_title = $(this).attr('ls_title');
            var lst_id = $(this).attr('lst_id');
            var lst_title = $(this).attr('lst_title');
            $("#livestock_name").text(ls_title);
            $("#purv_ls_id").val(ls_id);
            $("#livestock_type").text(lst_title);
            $("#purv_lst_id").val(lst_id);
            $("#livestock_quantity").val(total_quantity);
            $("#livestock_quantity_value").text(total_quantity);
            $("#available_quantity").text(available_quantity);
            $('.viewP').html('');
            $('#invalidInfo').html('');
            $('#submitButton').removeAttr('disabled');
            $.ajax({
                url: '<?php echo base_url('purchase/getLivestockAndTypesById'); ?>',
                type: 'GET',
                data: {
                    livestock_id: ls_id,
                    type_id: lst_id,
                },
                dataType: 'json',
            }).success(function(response) {
                var all = response.livePurInfo.livestockPurchasedInfo;
                var all2 = response.assiQuantity;
                var all3 = response.livePurSummaryInfo;
                var array = '';
                $.each(all, function(key, values) {
                    if (values.purv_quantity != all2[key]) {
                        array += `
                    <tr class="parentRow">
                        <td class="table__cells">
                        ${all3[key]}
                        <input type="hidden" name="lsh_purs_id[]" class="form-control" value='${values.purv_purs_id}'>
                        <input type="hidden" name="lsh_purv_id[]" class="form-control" value='${values.purv_id}'>
                        </td>
                        <td class="table__cells">
                        <input type="hidden" name="lsh_ls_id[]" class="form-control " value='${values.purv_ls_id}'>
                        <input type="hidden" name="lsh_lst_id[]" class="form-control" value='${values.purv_lst_id}'>
                        ${values.purv_quantity}
                        <input type="hidden" class="purchase__value" value='${values.purv_quantity}'>
                        </td>
                        <td class="table__cells">
                        ${all2[key] == null ? 0 : all2[key]}
                        </td>
                        <td class="table__cells unassignedAvailableQuantity" data-available-value="${parseFloat(values.purv_quantity) - parseFloat(all2[key] == null ? 0 : all2[key])}">
                        ${parseFloat(values.purv_quantity) - parseFloat(all2[key] == null ? 0 : all2[key])}
                        </td>
                        <td> 
                        <input type="number" name="lsh_assign_quantity[]" class="form-control assign__value input__number" value='' required>
                        </td>
                        <td class="table__cells">
                        <button type="button" class="button button-danger remove"><i class="fas fa-trash"></i> <?= lang('remove'); ?></button>
                        </td>
                    </tr>                    
                    `;
                        // remove Row
                        $('table').on('click', 'tr .remove', function(e) {
                            e.preventDefault();
                            $(this).parents('tr').remove();
                            buttonEnabler();
                        });

                        // Button Disable if there is no row
                        function buttonEnabler() {
                            var rowCount = $('#disableSubmitButton tr').length;
                            if (rowCount < 1) {
                                $('#submitButton').attr('disabled', 'disabled');
                                $('#invalidInfo').html('<p class="text-danger"><?= lang('invalid_information'); ?></p>');
                            } else {
                                $('#submitButton').removeAttr('disabled');
                                $('#invalidInfo').html('');
                            }
                        }

                        // Show alert if inter invalid value
                        $('table').on('keyup change paste', 'tr .assign__value', function(e) {
                            var keyupValue = $(this).parents('tr').find("input[name='lsh_assign_quantity[]']").val();
                            var availableValue = $(this).parents('tr').find(".unassignedAvailableQuantity").attr('data-available-value');
                            if (parseFloat(keyupValue) > parseFloat(availableValue)) {
                                Swal.fire({
                                    icon: "warning",
                                    title: "<?= lang('warning'); ?>...",
                                    text: "<?= lang('please_enter_a_valid_value'); ?>"
                                });
                                $('#submitButton').attr('disabled', 'disabled');
                                $(this).parents('tr').find("input[name='lsh_assign_quantity[]']").val('');
                            } else {
                                $('#submitButton').removeAttr('disabled');
                            }
                        });
                        // Show alert if inter invalid value

                    }
                });
                $(".viewP").append(array);
                $('#myModal').modal('show');

                // Remove White Space 
                $(".input__number").on('keyup change paste', function(e) {
                    var data, i;
                    data = document.querySelectorAll(".input__number"); //HTML DOM querySelector() Method
                    for (i = 0; i < data.length; i++) {
                        data[i].value = data[i].value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                    }
                });

            });

            // Create New Batch Number Under Shed Wise
            $("#shed_id").on('change', function() {
                var iid = $(this).val();
                var words = $('#shed_id option:selected').text().split(' ');
                // if (iid != '') {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('purchase/getLastBatchNumberShedWiseByAjax'); ?>',
                    data: {
                        shed_id: iid,
                    },
                    success: function(data) {
                        $("#lsh_batch_number").val(data);
                        $("#newBatchNumberUnderShed").html(data);

                        // Auto title create for batch
                        var text = '';
                        $.each(words, function() {
                            text += this.substring(0, 1);
                            newText = text.substring(1);
                        });
                        var uniqueTextForBatchTitle = newText + "-" + data;
                        $("#uniqueBatchTitle").val(uniqueTextForBatchTitle);
                    }
                });
                // }
            });

            $("#assign_quantity").keyup(function() {
                var assign_quantity = $(this).val();

                var available = parseFloat(available_quantity) - parseFloat(assign_quantity);
                $("#available_quantity").text(available);
                if (parseFloat(assign_quantity) > parseFloat(available_quantity)) {
                    $("#assign_quantity").val('');
                    $("#assign_quantity").attr("placeholder", "Warning: Please enter a valid value.");
                    $('body').append('<style>#assign_quantity::placeholder{color:red}</style>');

                    $("#available_quantity").text(available_quantity);
                }
            });

            // If assigned input is 0 or null disable button
            $("#assign_quantity").keyup(function() {
                var edit_assigned_quantity1 = $(this).val();
                if (edit_assigned_quantity1 == 0) {
                    $(".add_button").prop('disabled', true);
                } else {
                    $(".add_button").prop('disabled', false);
                }
            });

            $("#existing_shed_id").attr('disabled', 'disabled');
            $("#existing_batch_id").attr('disabled', 'disabled');

            // Show Hide Shed And Batch Selector
            $(function() {
                $("#chkPassport").click(function() {

                    if ($(this).is(":checked")) {
                        $("#showExistingRow").show();
                        $("#existing_shed_id").prop('disabled', false);
                        $("#existing_batch_id").prop('disabled', false);
                        $("#hideDefaultRow").hide();
                        $("#shed_id").prop('disabled', true);
                        $("#uniqueBatchTitle").prop('disabled', true);

                        // Livestock Stock Check
                        $('#existing_batch_id').on('change', function() {
                            var batch_id = $(this).val();
                            var shed_id = $("#existing_shed_id option:selected").val();
                            $.ajax({
                                type: 'POST',
                                url: '<?php echo base_url('purchase/getLivestockByShedAndBatchCascadingDropdown'); ?>',
                                data: {
                                    shed_id: shed_id,
                                    batch_id: batch_id
                                },
                                dataType: 'json',
                                success: function(data) {
                                    if (data.availableLivestockQuantity) {
                                        $("#totalLivestock").html('<div class="bg-info-light vaccination__date__warning"><h4> <?php echo lang('in_stock'); ?> : <strong> ' + data.availableLivestockQuantity + ' </strong> <?php echo $settings->unit; ?></h4 ></div>');
                                    } else {
                                        $("#totalLivestock").html('');
                                    }

                                }
                            });
                        });

                    } else {
                        $("#totalLivestock").hide();
                        $("#showExistingRow").hide();
                        $("#shed_id").prop('disabled', false);
                        $("#uniqueBatchTitle").prop('disabled', false);
                        $("#hideDefaultRow").show();
                        $("#existing_shed_id").prop('disabled', true);
                        $("#existing_batch_id").prop('disabled', true);
                    }

                    // Find Existing Number Under Shed Wise
                    $("#existing_shed_id").on('change', function() {
                        var shed_id = $(this).val();
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo base_url('purchase/getExistingBatchByShedIdByAjax'); ?>',
                            data: {
                                existing_shed_id: shed_id,
                                existing_ls_id: ls_id,
                                existing_lst_id: lst_id,
                            },
                            dataType: 'json',
                            success: function(data) {
                                $("#existing_batch_id").html(data.batches);

                            }
                        });
                    });

                });
            });
        });








        /* ==================== Edit Data ==================== */
        $(".editModalAssignToShed").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var assigned_summary_id = $(this).attr('assigned-summary-id');
            var batch_title = $(this).attr('batch_title');
            var live_assign_id = $(this).attr('live_assign_id');
            var live_shed_info = $(this).attr('live_shed_info');
            var livestock_name = $(this).attr('livestock_name');
            var purv_ls_id = $(this).attr('ls_id');
            var livestock_type = $(this).attr('livestock_type');
            var purv_lst_id = $(this).attr('lst_id');
            var live_shed_id = $(this).attr('live_shed_id');
            var live_batch_id = $(this).attr('live_batch_id');
            var live_assign_date = $(this).attr('live-assign-date');
            var purv_quantity = $(this).attr('purv_quantity');

            var assigned_quantity = $(this).attr('assigned_quantity');
            var available_quantity = $(this).attr('available_quantity');
            var purv_total_quantity = $(this).attr('purv_total_quantity');
            var live_shed_description = $(this).attr('live_shed_description');

            $("#edit_live_assign_id").val(assigned_summary_id);
            $("#edit_livestock_name").text(livestock_name);
            $("#edit_purv_ls_id").val(purv_ls_id);
            $("#edit_livestock_type").text(livestock_type);
            $("#edit_purv_lst_id").val(purv_lst_id);
            $("#edit_shed_id").val(live_shed_id);
            $("#edit_batch_id").text(live_batch_id + ": " + batch_title);
            $("#edit_batch_iid").val(live_batch_id);
            $("#edit_assign_date").val(live_assign_date);
            $("#edit_batch_title").text(batch_title);
            $("#edit_shed_info").text(live_shed_info);
            $("#edit_livestock_quantity").val(purv_quantity);
            $("#edit_lsh_description").val(live_shed_description);
            $("#edit_available_quantity").text(available_quantity);
            $("#edit_assign_quantity").val(assigned_quantity);
            $("#edit_livestock_quantity_value").text(purv_total_quantity);

            $('.viewEdit').html('');
            $('#invalidInfoEdit').html('');
            $('#submitButtonEdit').removeAttr('disabled');
            $.ajax({
                url: '<?php echo base_url('purchase/getAssignedLivestockAndTypesBySummaryId'); ?>',
                type: 'GET',
                data: {
                    assigned_summary_id: assigned_summary_id,
                    purv_ls_id: purv_ls_id,
                    purv_lst_id: purv_lst_id,
                },
                dataType: 'json',
            }).success(function(response) {
                var all = response.assignInfo.assignedLiveStocks;
                var purchaseBill = response.livePurSummaryInfo;
                var purchaseQuantity = response.livePurValueInfo;
                var array = '';
                $.each(all, function(key, value) {
                    array += `
                    <tr>
                        <td class="table__cells">
                        ${purchaseBill[key]}
                        <input type="hidden" name="lsh_purs_id[]" class="form-control" value='${value.lsh_purs_id}'>
                        <input type="hidden" name="lsh_id[]" class="form-control" value='${value.lsh_id}'>
                        <input type="hidden" name="lsh_purv_id[]" class="form-control" value='${value.lsh_purv_id}'>
                        <input type="hidden" name="lsh_lshs_id[]" class="form-control" value='${value.lsh_lshs_id}'>
                        </td>
                        <td class="table__cells purchaseQuantity" data-purchase-value="${purchaseQuantity[key]}">${purchaseQuantity[key]}</td>
                        <td class="table__cells">${parseFloat(purchaseQuantity) - parseFloat(value.lsh_assign_quantity)}</td>
                        <td class="assignedValue" data-assigned-value="${value.lsh_assign_quantity}">
                        <input type="hidden" name="lsh_ls_id[]" class="form-control " value='${value.lsh_purv_ls_id}'>
                        <input type="hidden" name="lsh_lst_id[]" class="form-control" value='${value.lsh_purv_lst_id}'>
                        <input type="number" name="lsh_assign_quantity[]" class="form-control input__number assigned__value" value='${value.lsh_assign_quantity}' required>
                        </td>
                        <td class="table__cells">
                        <button type="button" class="button button-danger removeEdit"><i class="fas fa-trash"></i> <?= lang('remove'); ?></button>
                        </label>
                        </td>
                    </tr>                    
                    `;
                    // remove Row
                    $('table').on('click', 'tr .removeEdit', function(e) {
                        e.preventDefault();
                        $(this).parents('tr').remove();
                        buttonEnablerInEdit();
                    });
                    // Button Disable if there is no row
                    function buttonEnablerInEdit() {
                        var rowCountEdit = $('#disableSubmitButtonEdit tr').length;
                        console.log(rowCountEdit)
                        if (rowCountEdit < 1) {
                            $('#submitButtonEdit').attr('disabled', 'disabled');
                            $('#invalidInfoEdit').html('<p class="text-danger">Invalid Information</p>');
                        } else {
                            $('#submitButtonEdit').removeAttr('disabled');
                            $('#invalidInfoEdit').html('');
                        }
                    }

                    // Show alert if inter invalid value
                    $('table').on('keyup change paste', 'tr .assigned__value', function(e) {
                        var keyupValueEdit = $(this).parents('tr').find("input[name='lsh_assign_quantity[]']").val();
                        var purchaseValueEdit = $(this).parents('tr').find(".purchaseQuantity").attr('data-purchase-value');
                        var assignedValueEdit = $(this).parents('tr').find(".assignedValue").attr('data-assigned-value');
                        if (parseFloat(keyupValueEdit) > parseFloat(purchaseValueEdit)) {
                            Swal.fire({
                                icon: "warning",
                                title: "Warning",
                                text: "<?= lang('please_enter_a_valid_value'); ?>"
                            });
                            // $('#submitButtonEdit').attr('disabled', 'disabled');
                            $(this).parents('tr').find("input[name='lsh_assign_quantity[]']").val(assignedValueEdit);
                        }
                    });
                    // Show alert if inter invalid value


                });
                $(".viewEdit").append(array);

                // Remove White Space
                $(".input__number").keyup(function(e) {
                    var data, i;
                    data = document.querySelectorAll(".input__number"); //HTML DOM querySelector() Method
                    for (i = 0; i < data.length; i++) {
                        data[i].value = data[i].value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                    }
                });
            });

            // Find Last Batch Number Under Shed Wise
            $("#edit_shed_id").on('change', function() {
                var iid = $(this).val();
                if (iid != '') {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('purchase/getLastBatchNumberShedWiseByAjax'); ?>',
                        data: {
                            shed_id: iid,
                        },
                        success: function(data) {
                            $("#edit_lsh_batch_number").val(data);
                            $("#edit_newBatchNumberUnderShed").html(data);

                        }
                    });
                }
            });

            $('#myModal2').modal('show');

        });



    });
</script>

<script>
    $(document).ready(function() {
        // toastr custom css
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

        // livestock purchase quantity alert
        function livestockPurchasedQuantityAlert() {
            var livestockPurchasedQuantityAlert = $("#livestockPurchasedQuantityAlert").val();

            if (livestockPurchasedQuantityAlert == 0) {
                toastr.warning('No livestock found. Please go to purchase module and purchase livestock.');
            }
        }
        livestockPurchasedQuantityAlert();

        // shed quantity alert
        function shedQuantityAlert() {
            var shedQuantityAlert = $("#shedQuantityAlert").val();

            if (shedQuantityAlert == 0) {
                toastr.warning('No shed found. Please go to shed module and add new shed.');
            }
        }
        shedQuantityAlert();
        <?php if ($this->session->flashdata('success')): ?>
        toastr.success('<?= html_escape($this->session->flashdata('success')); ?>');
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
        toastr.error('<?= html_escape($this->session->flashdata('error')); ?>');
        <?php endif; ?>
    });
</script>