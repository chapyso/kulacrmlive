<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('product_category_list'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table ">
                        <div class="clearfix">
                            <a data-toggle="modal" href="#myModal">
                                <div class="btn-group">
                                    <button class="button button-primary">
                                        <i class="fas fa-plus-circle"></i> <?= lang('add_new_category'); ?>
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
                                    <th><?= lang('serialNo'); ?>.</th>
                                    <th><?= lang('type_name'); ?> </th>
                                    <th><?= lang('description'); ?> </th>
                                    <th><?php echo lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                </style>
                                <?php
                                $serial = 0;
                                foreach ($productCategories->result() as $list) {
                                    $serial++;
                                ?>
                                    <tr class="">
                                        <td><?= $serial; ?></td>
                                        <td><?= $list->prc_name; ?></td>
                                        <td><?= $list->prc_description; ?></td>
                                        <td>
                                            <?php $countDataIfAvailableInProduct = $this->settings_model->getCountRow('product', 'pr_id', ['pr_prc_id' => $list->prc_id, 'pr_status' => 1]); ?>
                                            <button type="button" class="button button-warning editButton" data-toggle="modal" data-id="<?php echo $list->prc_id; ?>"><i class="fas fa-edit"></i> <?php echo lang('edit'); ?></button>
                                            <form action="<?php echo base_url('product/deleteProductCategory'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                <input type="hidden" name="prc_id" value="<?php echo $list->prc_id; ?>">
                                                <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                <button <?php echo ($countDataIfAvailableInProduct != 0) ? "disabled" : ''; ?> type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                            </form>
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
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?php echo lang('add_new_product'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" action="<?php echo base_url('product/insertProductCategory'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('name'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="prc_name" id="" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="prc_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="prc_id" value=''>

                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Edit product -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fas fa-edit"></i> <?php echo lang('edit_product'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" id="productCategoryEditForm" action="<?php echo base_url('product/insertProductCategory'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('name'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="prc_name" id="" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="prc_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="prc_id" value=''>
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
                                <p><?= lang('product_category_popup_message_one'); ?></p>
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

<!-- Javascript For Edit product -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".editButton").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            $.ajax({
                url: 'product/editProductCategoryByJason?prc_id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                // Populate the form fields with the data returned from server 
                $('#productCategoryEditForm').find('[name="prc_id"]').val(response.categories.prc_id).end()
                $('#productCategoryEditForm').find('[name="prc_name"]').val(response.categories.prc_name).end()
                $('#productCategoryEditForm').find('[name="prc_description"]').val(response.categories.prc_description).end()
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