<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">

                <header class="panel-heading">
                    <i class="fa fa-money"></i> <?php echo lang('view_shed'); ?>
                </header>

                <div class="panel-body">
                    <div class="clearfix noprint">
                        <a href="<?php echo base_url('shed/listShedWiseLivestockTransfer'); ?>">
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
                            <div class="new__cards__auto card__box  ">
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
                                <th> <?= lang('assigned_date'); ?></th>
                                <th> <?= lang('batch'); ?></th>
                                <th> <?= lang('livestock_name'); ?> </th>
                                <th> <?= lang('variant_name'); ?> </th>
                                <th> <?= lang('livestock_quantity'); ?> (<?= $settings->unit ?>) </th>
                                <th> <?= lang('batch'); ?> <?= lang('status'); ?></th>
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
                                        <td><?= $serial ?></td>
                                        <td><?= date("$settings->date_format", strtotime($value->lshs_assign_date)); ?></td>
                                        <td><strong><?= $value->lshs_batch_id ?>: </strong><?= $value->lshs_batch_title ?></td>
                                        <td><?php
                                            $livestockId = $this->purchase_model->getAssignedLivestockValueDataBySummaryId($value->lshs_id)->lsh_purv_ls_id;
                                            $livestockTypeId = $this->purchase_model->getAssignedLivestockValueDataBySummaryId($value->lshs_id)->lsh_purv_lst_id;

                                            echo  $this->livestock_model->getLivestockById($livestockId)->ls_name ?></td>
                                        <td><?= $this->livestock_model->getLivestockTypeById($livestockTypeId)->lst_title ?></td>
                                        <td><?= $value->lshs_assign_total_quantity;  ?></td>

                                        <td>
                                            <!-- Batch Status -->
                                            <?php
                                            $batchActiveInactiveStatusInfo = $this->settings_model->getSingleData('live_assigned_shed_summary', ['lshs_sh_id' => $shedById->sh_id, 'lshs_batch_id' => $value->lshs_batch_id, 'lshs_status' => 1])->lshs_active_status;
                                            if ($batchActiveInactiveStatusInfo == 0) { ?>
                                                <button class="button bg-primary-light"><i class="fa-solid fa-person-running"></i> <?= lang('running'); ?></button>
                                            <?php } else { ?>
                                                <button class="button bg-success-light"><i class="fa-solid fa-circle-check"></i> <?= lang('completed'); ?></button>
                                            <?php } ?>
                                        </td>

                                        <td class="align-middle">
                                            <!-- If this batch is in product and sale module user can't transfer this shed -->
                                            <?php
                                            $countDataIfAvailableInProduct = $this->settings_model->getCountRow('product_assign', 'pra_id', ['pra_shed_id' => $shedById->sh_id, 'pra_batch_id' => $value->lshs_batch_id, 'pra_status' => 1]);
                                            $countDataIfAvailableInLivestockSale = $this->settings_model->getCountRow('livestock_sale_value', 'lssv_id', ['lssv_shed_id' => $shedById->sh_id, 'lssv_batch_id' => $value->lshs_batch_id, 'lssv_status' => 1]);
                                            if ($countDataIfAvailableInProduct == 0 && $countDataIfAvailableInLivestockSale == 0) { ?>
                                                <a <?php echo ($batchActiveInactiveStatusInfo == 1) ? "disabled" : ""; ?> assign_summary_id="<?= $value->lshs_id ?>" batch_id="<?= $value->lshs_batch_id ?>" shed_details="<?= $shedById->sh_no ?>: <?= $shedById->sh_title ?>" batch_details="<?= $value->lshs_batch_id ?>" live_quantity="<?= $value->lshs_assign_total_quantity ?>" shed_id="<?= $shedById->sh_id ?>" purv_quantity="<?= $value->lshs_assign_total_quantity ?>" purv_ls_id="<?= $livestockId ?>" purv_lst_id="<?= $livestockTypeId ?>" livestock_name=" <?= $this->livestock_model->getLivestockById($livestockId)->ls_name; ?>" livestock_type="<?= $this->livestock_model->getLivestockTypeById($livestockTypeId)->lst_title; ?>" class="button button-warning getDataModalAssignToTransfer" data-toggle="modal" title="You can transfer livestock one shed to another shed with creating new batch."><i class="fa fa-forward" aria-hidden="true"></i> <?= lang('transfer'); ?></a>
                                            <?php } else { ?>
                                                <button class="button button-warning transferAlert" disabled><i class="fa fa-forward" aria-hidden="true"></i> <?= lang('transfer'); ?></button>
                                            <?php } ?>
                                            <!-- /.If this batch is in product and sale module user can't transfer this shed -->
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




<!-- Assign livestock to shed Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('transfer_one_shed_to_another_shed'); ?> </strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('shed/insertLivestockTransfer'); ?>" method="post" onsubmit="return confirm('<?= lang('are_you_sure_want_to_transfer_this_item'); ?>')" enctype="multipart/form-data">
                    <div class="bg-warning-light transfer__warning">
                        <span><strong><?= lang('note'); ?>: </strong><?= lang('livestock_transfer_note'); ?></span>
                    </div>
                    <table class="table table-bordered">
                        <tr>
                            <td width="50%"><?= lang('shed_and_title'); ?> : </td>
                            <td id="shed_details" width="50%"></td>
                        </tr>
                        <tr>
                            <td width="50%"><?= lang('batch_number'); ?> : </td>
                            <td id="batch_details" width="50%"></td>
                        </tr>
                        <tr>
                            <td width="50%"><?= lang('livestock_name'); ?> : </td>
                            <td id="livestock_name" width="50%"></td>
                        </tr>
                        <tr>
                            <td><?= lang('variant_name'); ?> :</td>
                            <td id="livestock_type"></td>
                        </tr>
                        <tr>
                            <td><?= lang('total'); ?> <?= lang('quantity'); ?>(<?php echo $settings->unit; ?>):</td>
                            <td id="livestock_quantity_value"></td>
                        </tr>
                        <tr>
                            <td><?= lang('available'); ?> <?= lang('quantity'); ?>(<?php echo $settings->unit; ?>): </td>
                            <td id="available_quantity"></td>
                        </tr>
                        <tr>
                            <td><?= lang('batch_no'); ?>.(<?= lang('auto_generated'); ?>)</td>
                            <td id="newBatchNumberUnderShed">
                            </td>
                            <input type="hidden" class="form-control" name="lsh_batch_number" id="lsh_batch_number" value='' required>
                        </tr>
                    </table>

                    <!-- New Batch -->
                    <div class="row" id="hideDefaultRow">
                        <div class="form-group col-sm-6">
                            <label for="shed_id"><?php echo lang('sheds'); ?><span class="text-danger">*</span></label>
                            <select name="lsh_sh_id" id="shed_id" class="form-control js-example-basic-single select" placeholder="" style="width: 100%;" required>
                                <option value=""><?= lang('please_select_shed'); ?> </option>
                                <?php if (!empty($sheds)) foreach ($sheds as $shed) { ?>
                                    <option value="<?php echo $shed->sh_id; ?>"> <?php echo $shed->sh_no; ?>. <?php echo $shed->sh_title; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for=""><?= lang('new_batch_title'); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="lsh_batch_title" id="uniqueBatchTitle" value='' placeholder="" required readonly>
                        </div>
                    </div>
                    <!-- /.New Batch -->
                    <div class="form-group">
                        <label for=""><?= lang('transfer'); ?> <?= lang('date'); ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker" name="ltr_transfer_date" id="" value='<?= get_current_date(); ?>' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('description'); ?> </label>
                        <textarea name="ltr_description" class="form-control " id="lshs_description" rows="5" placeholder="Enter Description" style="height: auto !important;" required></textarea>
                    </div>
                    <input type="hidden" name="ltr_purv_ls_id" id="purv_ls_id" value='' placeholder="">
                    <input type="hidden" name="ltr_purv_lst_id" id="purv_lst_id" value='' placeholder="">
                    <input type="hidden" name="ltr_shed_id" id="sheds_id" value='' placeholder="">
                    <input type="hidden" name="ltr_batch_id" id="batch_id" value='' placeholder="">
                    <input type="hidden" name="ltr_transfer_quantity" id="livestock_quantity" value='' placeholder="">
                    <input type="hidden" name="" id="live_quantity_hidden" value='' placeholder="">
                    <input type="hidden" name="assigned_summary_id" id="assigned_summary_id" value='' placeholder="">
                    <section class="text-right">
                        <button type="submit" name="submit" id="transferButton" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
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
                                <p><?= lang('shed_transfer_popup_message_one'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('shed_transfer_popup_message_two'); ?></p>
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
        $(".getDataModalAssignToTransfer").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var shed_id = $(this).attr('shed_id');
            var assign_summary_id = $(this).attr('assign_summary_id');
            var purv_purs_id = $(this).attr('purv_purs_id');
            var batch_id = $(this).attr('batch_id');
            var livestock_name = $(this).attr('livestock_name');
            var purv_ls_id = $(this).attr('purv_ls_id');
            var livestock_type = $(this).attr('livestock_type');
            var purv_lst_id = $(this).attr('purv_lst_id');
            var purv_quantity = $(this).attr('purv_quantity');
            var live_quantity = $(this).attr('live_quantity');
            var shed_details = $(this).attr('shed_details');
            var batch_details = $(this).attr('batch_details');
            // Assign Value 
            $("#sheds_id").val(shed_id);
            $("#assigned_summary_id").val(assign_summary_id);
            $("#batch_id").val(batch_id);
            $("#purchase_summary_id").val(purv_purs_id);
            $("#livestock_name").text(livestock_name);
            $("#purv_ls_id").val(purv_ls_id);
            $("#livestock_type").text(livestock_type);
            $("#purv_lst_id").val(purv_lst_id);
            $("#livestock_quantity").val(purv_quantity);
            $("#livestock_quantity_value").text(purv_quantity);
            $("#available_quantity").text(live_quantity);
            $("#live_quantity_hidden").val(live_quantity);
            $("#shed_details").text(shed_details);
            $("#batch_details").text(batch_details);
            $('#myModal').modal('show');

            // Show items in dropdown without this value 
            $(".select option").each(function() {
                $(this).siblings('[value="' + shed_id + '"]').select();
            });

            // Create New Batch Number Under Shed Wise
            $("#shed_id").on('change', function() {
                var iid = $(this).val();
                var words = $('#shed_id option:selected').text().split(' ');
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
                        $("#uniqueBatchTitle").val(uniqueTextForBatchTitle)
                    }
                });
            });

        });



        $(".transferAlert").click(function() {
            Swal.fire({
                icon: "error",
                title: "Sorry",
                text: "<?= lang('transfer_alert_message'); ?>"
            });
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