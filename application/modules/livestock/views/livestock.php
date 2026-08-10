<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        
        <!-- 2026 MODERN PAGE HEADER & ACTIONS BAR -->
        <div class="kula-hero-card" style="margin-bottom: 20px; padding: 20px 28px;">
            <div class="hero-content-left">
                <h1 class="hero-title" style="font-size: 22px;">
                    <i class="fa-solid fa-cow" style="color: #059669; margin-right: 8px;"></i>
                    <?php echo lang('livestocks'); ?> <?php echo lang('list'); ?>
                </h1>
                <p class="hero-subtitle">Manage animal breeds, shed allocations, stock levels, and reproduction metrics</p>
            </div>

            <div class="hero-actions-right" style="gap: 8px; flex-wrap: wrap;">
                <a href="<?php echo base_url('kula_ai/vision'); ?>" class="hero-export-btn" style="background: linear-gradient(135deg, #10b981, #059669); text-decoration: none; color: #fff;">
                    <i class="fa-solid fa-eye"></i> KulaAI Vision Scan
                </a>

                <a data-toggle="modal" href="#myModal" class="hero-export-btn" style="background: #047857; text-decoration: none;">
                    <i class="fa-solid fa-plus-circle"></i> <?php echo lang('add_new_livestock'); ?>
                </a>

                <a href="<?php echo base_url('livestock/exportCSV'); ?>" class="hero-date-pill" style="text-decoration: none; padding: 10px 18px;">
                    <i class="fa-solid fa-file-csv" style="color: #0284c7;"></i>
                    <span>Export CSV</span>
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

        <!-- SYSTEM ALERTS -->
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger noprint" role="alert" style="border-radius: 12px; font-weight: 600; margin-bottom: 20px;">
                <i class="fas fa-exclamation-triangle"></i>
                <?php echo html_escape($this->session->flashdata('error')); ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success noprint" role="alert" style="border-radius: 12px; font-weight: 600; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i>
                <?php echo html_escape($this->session->flashdata('success')); ?>
            </div>
        <?php endif; ?>

        <!-- KPI METRICS SUMMARY GRID -->
        <div class="row" style="margin-bottom: 24px;">
            <?php
            $totalPurchaseQuantity = $this->purchase_model->getTotalPurchasedLivestockQuantity();
            $livestockReproductionQuantity = $this->report_model->getSumData('live_assigned_shed_summary', 'lshs_assign_total_quantity', ['lshs_type' => 2, 'lshs_status' => 1]);
            $totalAssignedQuantity = $this->purchase_model->getTotalLivestockAssignedToShedQuantity();
            $outOfShed = ($totalPurchaseQuantity + $livestockReproductionQuantity) - $totalAssignedQuantity;
            $totalPurAmount = $this->purchase_model->getTotalLivestockPurchasedAmount();
            ?>

            <!-- Card 1: Total Purchased -->
            <div class="col-lg-3 col-sm-6" style="margin-bottom: 12px;">
                <div class="kula-card-container" style="padding: 20px; margin: 0;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo lang('total_purchased'); ?></span>
                            <h3 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 6px 0 0 0;"><?php echo $totalPurchaseQuantity ? $totalPurchaseQuantity : 0; ?></h3>
                            <?php if ($livestockReproductionQuantity): ?>
                                <span style="font-size: 11px; color: #059669; font-weight: 600;"><?php echo lang('reproduction'); ?>: +<?php echo $livestockReproductionQuantity; ?></span>
                            <?php endif; ?>
                        </div>
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Assigned to Shed -->
            <div class="col-lg-3 col-sm-6" style="margin-bottom: 12px;">
                <div class="kula-card-container" style="padding: 20px; margin: 0;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo lang('total_assigned_to_shed'); ?></span>
                            <h3 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 6px 0 0 0;"><?php echo $totalAssignedQuantity ? $totalAssignedQuantity : 0; ?> <span style="font-size: 13px; font-weight: 600; color: #64748b;"><?= $settings->unit; ?></span></h3>
                        </div>
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                            <i class="fa-solid fa-warehouse"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Out of Shed -->
            <div class="col-lg-3 col-sm-6" style="margin-bottom: 12px;">
                <div class="kula-card-container" style="padding: 20px; margin: 0;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo lang('out_of_shed'); ?></span>
                            <h3 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 6px 0 0 0;"><?php echo $outOfShed > 0 ? $outOfShed : 0; ?> <span style="font-size: 13px; font-weight: 600; color: #64748b;"><?= $settings->unit; ?></span></h3>
                        </div>
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: #fffbeb; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                            <i class="fa-solid fa-store-slash"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4: Total Purchased Amount -->
            <div class="col-lg-3 col-sm-6" style="margin-bottom: 12px;">
                <div class="kula-card-container" style="padding: 20px; margin: 0;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo lang('total_purchased_amount'); ?></span>
                            <h3 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 6px 0 0 0;"><?php echo $settings->currency . number_format($totalPurAmount, 2, ".", ","); ?></h3>
                        </div>
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: #faf5ff; color: #9333ea; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DATA TABLE CONTAINER CARD -->
        <div class="kula-card-container">
            <div class="kula-card-header">
                <div>
                    <h3 class="kula-card-title"><?php echo lang('livestocks'); ?> Directory</h3>
                    <p class="kula-card-sub">Registered livestock breeds, variant breakdown, and management options</p>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span class="badge" style="background: #ecfdf5; color: #059669; font-weight: 700; border-radius: 9999px; padding: 6px 12px; font-size: 12px;">
                        <?php echo count($livestocks); ?> Breeds Active
                    </span>
                </div>
            </div>

            <div style="padding: 20px;">
                <div class="adv-table editable-table table-responsive">
                    <table class="table table-striped table-hover align-middle" id="editable-sample" style="width: 100%;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                <th style="width: 6%; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b;"><?php echo lang('serialNo'); ?></th>
                                <th style="width: 24%; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b;"><?php echo lang('livestock_id'); ?></th>
                                <th style="width: 20%; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b;"><?php echo lang('total_purchased'); ?> (<?= $settings->unit ?>)</th>
                                <th style="width: 15%; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b;"><?php echo lang('variant'); ?></th>
                                <th style="width: 35%; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; text-align: right;"><?php echo lang('options'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $serial = 0;
                            foreach ($livestocks as $livestock) {
                                $serial++;
                            ?>
                                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;">
                                    <td style="font-weight: 700; color: #64748b; vertical-align: middle;">#<?= sprintf('%02d', $serial); ?></td>
                                    <td style="font-weight: 800; color: #0f172a; font-size: 14px; vertical-align: middle;">
                                        <i class="fa-solid fa-cow" style="color: #10b981; margin-right: 8px;"></i>
                                        <?php echo html_escape($livestock->ls_name); ?>
                                    </td>
                                    <td style="font-weight: 700; color: #334155; vertical-align: middle;">
                                        <?php 
                                        $totalLivestockPurchasedQuantity = $this->livestock_model->getLivestockPurchaseQuantityByLivestockId($livestock->ls_id, 'purv_quantity');
                                        echo $totalLivestockPurchasedQuantity ? $totalLivestockPurchasedQuantity : 0;
                                        ?>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <?php
                                        $variant_count = $this->livestock_model->countLivestockVariantByLivestockId($livestock->ls_id);
                                        ?>
                                        <span class="badge" style="background: #eff6ff; color: #2563eb; font-weight: 700; font-size: 11px; border-radius: 6px; padding: 4px 10px;">
                                            <?php echo $variant_count ? $variant_count : "0"; ?> Variants
                                        </span>
                                    </td>
                                    <td style="text-align: right; vertical-align: middle;">
                                        <div style="display: inline-flex; align-items: center; gap: 6px; flex-wrap: wrap; justify-content: flex-end;">
                                            <!-- Edit Button -->
                                            <button type="button" class="kula-btn-xs kula-btn-emerald editButton" data-toggle="modal" data-id="<?php echo $livestock->ls_id; ?>">
                                                <i class="fa-solid fa-pen-to-square"></i> <?php echo lang('edit'); ?>
                                            </button>

                                            <!-- Delete Button -->
                                            <?php
                                            $countDataIfAvailableInPurchase = $this->settings_model->getCountRow('livestock_purchase_value', 'purv_id', ['purv_ls_id' => $livestock->ls_id, 'purv_status' => 1]);
                                            if ($countDataIfAvailableInPurchase == 0) { ?>
                                                <form action="<?php echo base_url('livestock/deleteLivestock'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                    <input type="hidden" name="ls_id" value="<?php echo $livestock->ls_id; ?>">
                                                    <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                    <button type="submit" class="kula-btn-xs kula-btn-rose">
                                                        <i class="fa-solid fa-trash-can"></i> <?php echo lang('delete'); ?>
                                                    </button>
                                                </form>
                                            <?php } else { ?>
                                                <button type="button" class="kula-btn-xs" style="background: #f1f5f9; color: #94a3b8; border: 1px solid #e2e8f0;" title="<?= lang('this_item_used_another_places'); ?>" disabled>
                                                    <i class="fa-solid fa-lock"></i> <?php echo lang('delete'); ?>
                                                </button>
                                            <?php } ?>

                                            <!-- Add Variant Button -->
                                            <a ls-id="<?= $livestock->ls_id; ?>" ls-name="<?= $livestock->ls_name; ?>" class="kula-btn-xs kula-btn-blue addVariantButton" style="cursor: pointer;">
                                                <i class="fa-solid fa-circle-plus"></i> <?php echo lang('ls_add_variant'); ?>
                                            </a>

                                            <!-- View Variant Button -->
                                            <a href="<?php echo base_url(''); ?>livestock/viewVariant?ls_id=<?php echo $livestock->ls_id; ?>" class="kula-btn-xs kula-btn-purple">
                                                <i class="fa-solid fa-eye"></i> <?php echo lang('ls_view_variant'); ?>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
</section>
<!--main content end-->

<!--Add New Livestock-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?php echo lang('add_new_livestock'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('livestock/insertLivestock') ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('ls_name'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ls_name" id="" value='' placeholder="Enter livestock name" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="ls_description" class="form-control" id="" rows="3" placeholder="Enter description" style="height: auto !important;"></textarea>
                    </div>
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Edit Livestock-->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-edit"></i> <?php echo lang('edit_livestock'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" id="livestockEditForm" action="<?php echo base_url('livestock/updateLivestock') ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('ls_name'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ls_name" id="" value='' placeholder="Enter Title" required>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="ls_description" class="form-control" id="" rows="3" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>

                    <input type="hidden" name="id" value=''>
                    <input type="hidden" name="ls_id" value=''>

                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Add Variant -->
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?php echo lang('ls_add_new_variant'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('') ?>livestock/insertLivestockType" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-striped table-hover table-bordered">
                                <tr>
                                    <td width="50%"><?php echo lang('ls_name_variant_modal'); ?></td>
                                    <td width="50%"><span id="ls_name"></span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('ls_type_title'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="lst_title" id="" value='' placeholder="Enter Title" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="lst_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="lst_ls_id" value='' id="ls_id">

                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
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
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title"><strong><i class="fa-solid fa-circle-info"></i> <?php echo lang('basic_information'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <div class="row">
                    <div class="col-xs-6">
                        <ol class="information__modal__ol">
                            <li>
                                <p><?= lang('livestock_popup_message_one'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('livestock_popup_message_two'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('livestock_popup_message_three'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('livestock_popup_message_four'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('livestock_popup_message_five'); ?></p>
                            </li>
                        </ol>
                    </div>
                    <div class="col-xs-6">
                        <img class="img-square information__modal__image" src="<?php echo base_url('uploads/information/livestock_and_variant.jpg'); ?>" alt="No img">
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



<!-- Javascript For Edit Trip -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".editButton").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            $.ajax({
                url: 'livestock/editLivestockByJason?ls_id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                // Populate the form fields with the data returned from server
                $('#livestockEditForm').find('[name="ls_id"]').val(response.livestock.ls_id).end()
                $('#livestockEditForm').find('[name="ls_name"]').val(response.livestock.ls_name).end()
                $('#livestockEditForm').find('[name="ls_description"]').val(response.livestock.ls_description).end()
                $('#myModal2').modal('show');
            });
        });

        // Add Livestock Variant 
        $(".addVariantButton").click(function(e) {
            var iid = $(this).attr('ls-id');
            var name = $(this).attr('ls-name');
            $('#myModal3').modal('show');
            $('#ls_id').val(iid);
            $('#ls_name').html(name);
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