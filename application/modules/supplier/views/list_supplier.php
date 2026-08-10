<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        
        <!-- 2026 MODERN PAGE HEADER & ACTIONS BAR -->
        <div class="kula-hero-card" style="margin-bottom: 20px; padding: 20px 28px;">
            <div class="hero-content-left">
                <h1 class="hero-title" style="font-size: 22px;">
                    <i class="fa-solid fa-truck-field" style="color: #059669; margin-right: 8px;"></i>
                    <?php echo lang('supplier'); ?>
                </h1>
                <p class="hero-subtitle">Directory of vendors, feed & medicine suppliers, procurement history, and payables</p>
            </div>

            <div class="hero-actions-right" style="gap: 8px; flex-wrap: wrap;">
                <a data-toggle="modal" href="#myModal" class="hero-export-btn" style="background: #047857; text-decoration: none;">
                    <i class="fa-solid fa-plus-circle"></i> <?php echo lang('add_supplier'); ?>
                </a>

                <a href="<?php echo base_url('supplier/exportCSV'); ?>" class="hero-date-pill" style="text-decoration: none; padding: 10px 18px;">
                    <i class="fa-solid fa-file-csv" style="color: #0284c7;"></i>
                    <span>Export CSV</span>
                </a>

                <button class="hero-date-pill" onclick="javascript:window.print();" style="border: 1px solid #e2e8f0; background: #fff;">
                    <i class="fa-solid fa-print" style="color: #475569;"></i>
                    <span><?php echo lang('print'); ?></span>
                </button>
            </div>
        </div>
                            <a data-toggle="modal" href="#myModal">
                                <div class="btn-group">
                                    <button id="" class="button button-primary">
                                        <i class="fa fa-plus-circle"></i> <?php echo lang('add_new_supplier'); ?>
                                    </button>
                                </div>
                            </a>
                            <a href="<?php echo base_url('supplier/exportCSV'); ?>" style="margin-left:6px;">
                                <div class="btn-group">
                                    <button class="button button-info" type="button">
                                        <i class="fas fa-file-csv"></i> Export CSV
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
                                    <th><?= lang('image'); ?></th>
                                    <th><?= lang('name'); ?></th>
                                    <th><?= lang('email'); ?></th>
                                    <th><?= lang('phone'); ?></th>
                                    <th><?= lang('address'); ?></th>
                                    <th><?= lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serial = 0;
                                foreach ($suppliers->result() as $supplier) {
                                    $serial++;
                                ?>
                                    <tr>
                                        <td><?= $serial ?></td>
                                        <td>
                                            <?php if ($supplier->s_img_url) { ?>
                                                <img class="img-circle" style="height: 50px; width: 50px;" src="<?php echo $supplier->s_img_url; ?>" alt="No img">
                                            <?php } else { ?>
                                                <img class="img-circle" style="height: 50px; width: 50px;" src="<?php echo base_url('uploads/avatar/alter-image.png'); ?>" alt="No img">
                                            <?php } ?>
                                        </td>
                                        <td> <?= html_escape($supplier->s_name); ?></td>
                                        <td><?= html_escape($supplier->s_email); ?></td>
                                        <td><?= html_escape($supplier->s_phone); ?></td>
                                        <td><?= html_escape($supplier->s_address); ?></td>
                                        <td>
                                            <?php
                                            $countDataIfAvailableInLivestockPurchase = $this->settings_model->getCountRow('livestock_purchase_summary', 'purs_id', ['purs_supp_id' => $supplier->s_id, 'purs_status' => 1]);
                                            $countDataIfAvailableInFoodPurchase = $this->settings_model->getCountRow('food_purchase_summary', 'fdps_id', ['fdps_s_id' => $supplier->s_id, 'fdps_status' => 1]);
                                            $countDataIfAvailableInVaccinePurchase = $this->settings_model->getCountRow('vaccine_purchase_summary', 'vps_id', ['vps_s_id' => $supplier->s_id, 'vps_status' => 1]);
                                            ?>
                                            <button type="button" class="button button-warning editButton" data-toggle="modal" data-id="<?= $supplier->s_id; ?>"><i class="fas fa-edit"></i> <?php echo lang('edit'); ?></button>
                                            <form action="<?php echo base_url('supplier/deleteSupplier'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                <input type="hidden" name="s_id" value="<?= $supplier->s_id; ?>">
                                                <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                <button <?php echo ($countDataIfAvailableInLivestockPurchase == 0 && $countDataIfAvailableInFoodPurchase == 0 && $countDataIfAvailableInVaccinePurchase == 0) ? '' : "disabled"; ?> type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                            </form>
                                            <a href="<?php echo base_url(''); ?>payments/viewSupplierWisePayment?id=<?= $supplier->s_id; ?>"><button type="button" class="button button-info"><i class="fas fa-eye"></i> <?= lang('ledger'); ?> </button></i></a>
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
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?php echo lang('add_new_supplier'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" action="<?php echo base_url('supplier/insertSupplier'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('name'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="s_name" id="" value='' required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('email'); ?></label>
                        <input type="text" class="form-control" name="s_email" id="" value=''>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="s_phone" id="" value='' required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('address'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="s_address" id="" value='' required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="s_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('image'); ?></label>
                        <input type="file" name="s_img_url">
                    </div>

                    <input type="hidden" name="id" value=''>
                    <input type="hidden" name="s_id" value=''>

                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Edit Client -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-edit"></i> <?php echo lang('edit_supplier'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" id="supplierEditForm" action="<?php echo base_url('supplier/insertSupplier'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('name'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="s_name" id="" value='' required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('email'); ?></label>
                        <input type="email" class="form-control" name="s_email" id="" value=''>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="s_phone" id="" value='' required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('address'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="s_address" id="" value='' required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="s_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('image'); ?></label>
                        <input type="file" name="s_img_url">
                    </div>
                    <input type="hidden" name="s_id" value=''>
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- ==================== Information Popup ==================== -->
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
                                <p><?= lang('supplier_popup_message_one'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('supplier_popup_message_two'); ?></p>
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


<!-- Javascript For Edit Supplier -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".editButton").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            $.ajax({
                url: 'supplier/editSupplierByJason?s_id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                // Populate the form fields with the data returned from server
                $('#supplierEditForm').find('[name="s_id"]').val(response.suppliers.s_id).end()
                $('#supplierEditForm').find('[name="s_name"]').val(response.suppliers.s_name).end()
                $('#supplierEditForm').find('[name="s_email"]').val(response.suppliers.s_email).end()
                $('#supplierEditForm').find('[name="s_phone"]').val(response.suppliers.s_phone).end()
                $('#supplierEditForm').find('[name="s_address"]').val(response.suppliers.s_address).end()
                $('#supplierEditForm').find('[name="s_description"]').val(response.suppliers.s_description).end()
                $('#myModal2').modal('show');
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