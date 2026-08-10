<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        
        <!-- 2026 MODERN PAGE HEADER & ACTIONS BAR -->
        <div class="kula-hero-card" style="margin-bottom: 20px; padding: 20px 28px;">
            <div class="hero-content-left">
                <h1 class="hero-title" style="font-size: 22px;">
                    <i class="fa-solid fa-warehouse" style="color: #059669; margin-right: 8px;"></i>
                    <?= lang('shed_lists'); ?>
                </h1>
                <p class="hero-subtitle">Monitor livestock shed allocations, batch capacities, and mortality metrics</p>
            </div>

            <div class="hero-actions-right" style="gap: 8px; flex-wrap: wrap;">
                <a href="<?php echo base_url('kula_ai/vision'); ?>" class="hero-export-btn" style="background: linear-gradient(135deg, #10b981, #059669); text-decoration: none; color: #fff;">
                    <i class="fa-solid fa-eye"></i> KulaAI Vision Scan
                </a>

                <a data-toggle="modal" href="#myModal" class="hero-export-btn" style="background: #047857; text-decoration: none;">
                    <i class="fa-solid fa-plus-circle"></i> <?php echo lang('add_new_shed'); ?>
                </a>

                <button class="hero-date-pill" onclick="javascript:window.print();" style="border: 1px solid #e2e8f0; background: #fff;">
                    <i class="fa-solid fa-print" style="color: #475569;"></i>
                    <span><?php echo lang('print'); ?></span>
                </button>

                <a data-toggle="modal" href="#informationPopup" class="hero-date-pill" style="text-decoration: none; border: 1px solid #e9d5ff; background: #faf5ff;">
                    <i class="fa-solid fa-circle-question" style="color: #9333ea;"></i>
                    <span style="color: #9333ea;"><?= lang('information'); ?></span>
                </a>
            </div>
        </div>
                        <div class="space15">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <div class="new__cards card__box">
                                        <?php $livestockReproductionQuantity = $this->report_model->getSumData('live_assigned_shed_summary', 'lshs_assign_total_quantity', ['lshs_type' => 2, 'lshs_status' => 1]); ?>
                                        <div class="<?php echo ($livestockReproductionQuantity == 0) ? "col-xs-4" : "col-xs-3"; ?>">
                                            <p><?= lang('total_purchased'); ?> (<?= $settings->unit; ?>)</p>
                                            <h3><?php $totalPurchaseQuantity = $this->purchase_model->getTotalPurchasedLivestockQuantity();
                                                if ($totalPurchaseQuantity) {
                                                    echo $totalPurchaseQuantity;
                                                } else {
                                                    echo 0;
                                                }
                                                ?></h3>
                                        </div>
                                        <?php if ($livestockReproductionQuantity) { ?>
                                            <div class="col-xs-3">
                                                <p><?= lang('reproduction'); ?> (<?= $settings->unit; ?>)</p>
                                                <h3><?php
                                                    if ($livestockReproductionQuantity) {
                                                        echo $livestockReproductionQuantity;
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></h3>
                                            </div>
                                        <?php } ?>
                                        <div class="<?php echo ($livestockReproductionQuantity == 0) ? "col-xs-4" : "col-xs-3"; ?>">
                                            <p><?= lang('total_assigned_to_shed'); ?> (<?= $settings->unit; ?>)</p>
                                            <h3><?php $totalAssignedQuantity = $this->purchase_model->getTotalLivestockAssignedToShedQuantity();
                                                if ($totalAssignedQuantity) {
                                                    echo $totalAssignedQuantity;
                                                } else {
                                                    echo 0;
                                                }
                                                ?></h3>
                                        </div>
                                        <div class="<?php echo ($livestockReproductionQuantity == 0) ? "col-xs-4" : "col-xs-3"; ?>">
                                            <p><?= lang('out_of_shed'); ?> (<?= $settings->unit; ?>)</p>
                                            <h3> <?php
                                                    $outOfShed = ($totalPurchaseQuantity + $livestockReproductionQuantity) - $totalAssignedQuantity;
                                                    if ($outOfShed) {
                                                        echo $outOfShed;
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <div class="new__cards card__box">
                                        <div class="col-xs-3">
                                            <p><?= lang('total'); ?> <?= lang('batch'); ?> (<?= $settings->unit; ?>)</p>
                                            <h3><?php $totalBatch = $this->report_model->getCountRow('live_assigned_shed_summary', 'lshs_id', ['lshs_status' => 1]);
                                                if ($totalBatch) {
                                                    echo $totalBatch;
                                                } else {
                                                    echo 0;
                                                }
                                                ?></h3>
                                        </div>
                                        <div class="col-xs-3">
                                            <p><?= lang('running'); ?> <?= lang('batch'); ?> (<?= $settings->unit; ?>)</p>
                                            <h3><?php $runningBatch = $this->report_model->getCountRow('live_assigned_shed_summary', 'lshs_id', ['lshs_status' => 1, 'lshs_active_status' => 0]);
                                                if ($runningBatch) {
                                                    echo $runningBatch;
                                                } else {
                                                    echo 0;
                                                }
                                                ?></h3>
                                        </div>
                                        <div class="col-xs-3">
                                            <p><?= lang('completed'); ?> <?= lang('batch'); ?> (<?= $settings->unit; ?>)</p>
                                            <h3><?php $completedBatch = $this->report_model->getCountRow('live_assigned_shed_summary', 'lshs_id', ['lshs_status' => 1, 'lshs_active_status' => 1]);
                                                if ($completedBatch) {
                                                    echo $completedBatch;
                                                } else {
                                                    echo 0;
                                                }
                                                ?></h3>
                                        </div>
                                        <div class="col-xs-3">
                                            <p><?= lang('total_shed'); ?> (<?= $settings->unit; ?>)</p>
                                            <h3><?php $totalShed = $this->report_model->getCountRow('shed', 'sh_id', ['sh_status' => 1]);
                                                if ($totalShed) {
                                                    echo $totalShed;
                                                } else {
                                                    echo 0;
                                                }
                                                ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th><?php echo lang('serialNo'); ?></th>
                                    <th><?php echo lang('shed_no'); ?></th>
                                    <th><?php echo lang('shed_title'); ?></th>
                                    <th><?php echo lang('total'); ?> <?php echo lang('assigned'); ?> (<?= $settings->unit ?>)</th>
                                    <th><?php echo lang('sold'); ?> (<?= $settings->unit ?>)</th>
                                    <th><?php echo lang('death'); ?> (<?= $settings->unit ?>)</th>
                                    <th><?php echo lang('in_stock'); ?> (<?= $settings->unit ?>)</th>
                                    <th><?php echo lang('batch'); ?> <?php echo lang('running'); ?></th>
                                    <th><?php echo lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $serial = 0;
                                foreach ($sheds as $shed) {
                                    $serial++;
                                ?>
                                    <tr>
                                        <td class="table_text_middle"> <?= $serial ?></td>
                                        <td class="table_text_middle"> <?= $shed->sh_no; ?></td>
                                        <td class="table_text_middle"> <?= $shed->sh_title; ?></td>
                                        <td class="table_text_middle"> <?php $total_sum = $this->shed_model->getShedWiseLivestock($shed->sh_id);
                                                                        if ($total_sum) {
                                                                            echo $total_sum;
                                                                        }
                                                                        ?>
                                        </td>
                                        <td class="table_text_middle">
                                            <?php $sold = $this->sale_model->getShedWiseSoldLivestock($shed->sh_id);
                                            if ($sold) {
                                                echo $sold;
                                            }
                                            ?>
                                        </td>
                                        <td class="table_text_middle">
                                            <?php $death = $this->shed_model->getShedWiseDeathLivestock($shed->sh_id);
                                            if ($death) {
                                                echo $death;
                                            }
                                            ?>
                                        </td>
                                        <td class="table_text_middle"><?php $inStock = $total_sum - ($sold + $death);
                                                                        if ($inStock) {
                                                                            echo $inStock;
                                                                        }
                                                                        ?>
                                        </td>
                                        <td class="table_text_middle">
                                            <?php
                                            $shedWiseRunningBatch = $this->report_model->getCountRow('live_assigned_shed_summary', 'lshs_id', ['lshs_sh_id' => $shed->sh_id, 'lshs_status' => 1, 'lshs_active_status' => 0]);
                                            if ($shedWiseRunningBatch) {
                                                echo "<span class='button bg-success-light'> " . $shedWiseRunningBatch .  "</span>";
                                            }
                                            ?>
                                        </td>
                                        <td class="table_text_middle">
                                            <a href="<?php echo base_url('') ?>shed/viewShedWiseAssignedLivestock?sh_id=<?php echo $shed->sh_id; ?>"><button type="button" class="button button-info"><i class="fas fa-eye"></i> <?= lang('view'); ?> <?= lang('details'); ?></button></i></a>
                                            <button type="button" class="button button-warning editButton" data-toggle="modal" data-id="<?php echo $shed->sh_id; ?>"><i class="fas fa-edit"></i> <?php echo lang('edit'); ?></button>

                                            <!-- conditional delete -->
                                            <?php
                                            $countDataIfAvailableAssignedModule = $this->settings_model->getCountRow('live_assigned_shed_summary', 'lshs_id', ['lshs_sh_id' => $shed->sh_id, 'lshs_status' => 1]);
                                            if ($countDataIfAvailableAssignedModule  == 0) { ?>
                                                <form action="<?php echo base_url('shed/deleteShed'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                    <input type="hidden" name="sh_id" value="<?php echo $shed->sh_id; ?>">
                                                    <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                    <button type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                                </form>
                                            <?php } else { ?>
                                                <button type="button" class="button button-danger" title="<?= lang('this_item_used_another_places'); ?>" disabled><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                            <?php } ?>
                                            <!-- /.conditional delete -->
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?php echo lang('add_new_shed'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('shed/insertShed') ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('shed'); ?> <?php echo lang('title'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="sh_title" id="" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('shed_no'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="sh_no" id="shedSerialNumber" value='' required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="sh_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="sh_id" value=''>
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Edit Car -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-edit"></i> <?php echo lang('edit_shed'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" id="shedEditForm" action="<?php echo base_url('shed/insertShed') ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('shed'); ?> <?php echo lang('title'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="sh_title" id="" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('shed_no'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="sh_no" id="shedSerialNumberEdit" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="sh_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="sh_id" value=''>
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Add Payment To That Car -->

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
                                <p><?= lang('shed_popup_message_one'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('shed_popup_message_two'); ?> </p>
                            </li>
                            <li>
                                <p><?= lang('shed_popup_message_three'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('shed_popup_message_four'); ?></p>
                            </li>
                        </ol>
                    </div>
                    <div class="col-xs-6">
                        <img class="img-square information__modal__image" src="<?php echo base_url('uploads/information/shed_create.jpg'); ?>" alt="No img">
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

<input type="hidden" id="shedQuantityAlert" value="<?php echo $this->report_model->getCountRow('shed', 'sh_id', ['sh_status' => 1]); ?>">

<!-- Javascript For Edit Trip -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".editButton").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            $.ajax({
                url: 'shed/editShedByJason?sh_id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                $('#shedEditForm').find('[name="sh_id"]').val(response.shed.sh_id).end()
                $('#shedEditForm').find('[name="sh_no"]').val(response.shed.sh_no).end()
                $('#shedEditForm').find('[name="sh_title"]').val(response.shed.sh_title).end()
                $('#shedEditForm').find('[name="sh_description"]').val(response.shed.sh_description).end()
                $('#myModal2').modal('show');
                checkUniqueNumberForShed(response.shed.sh_no);
            });
        });

        // Serial Number Check
        $("#shedSerialNumber").on('blur', function() {
            var shedSerialNumber = $("#shedSerialNumber").val();
            $.ajax({
                method: 'GET',
                url: 'shed/duplicateShedNumberCheckAjax?shed_serial_number=' + shedSerialNumber,
                data: '',
                dataType: 'json',
            }).success(function(data) {
                if (data) {
                    $("#shedSerialNumber").val('');
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('please_enter_a_valid_value'); ?>"
                    });
                } else {
                    $("#shedSerialNumber").val(data);
                }
            });
        });

        // Serial Number Check edit page
        function checkUniqueNumberForShed(shedNo) {
            $("#shedSerialNumberEdit").on('blur', function() {
                var shedSerialNumberEdit = $("#shedSerialNumberEdit").val();
                $.ajax({
                    method: 'GET',
                    url: 'shed/duplicateShedNumberCheckAjax?shed_serial_number=' + shedSerialNumberEdit,
                    data: '',
                    dataType: 'json',
                }).success(function(response) {
                    if (shedSerialNumberEdit == shedNo) {
                        $("#shedSerialNumberEdit").val(shedSerialNumberEdit);
                    } else if (response && response == shedSerialNumberEdit) {
                        $("#shedSerialNumberEdit").val('');
                        $("#shedSerialNumberEdit").attr('placeholder', 'Please enter a unique number.');
                    } else {
                        $("#shedSerialNumberEdit").val(shedSerialNumberEdit);
                    }
                });
            });
        }
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
        // shed quantity alert
        function shedQuantityAlert() {
            var shedQuantityAlert = $("#shedQuantityAlert").val();

            if (shedQuantityAlert == 0) {
                toastr.warning('No shed found. Please add new shed.');
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