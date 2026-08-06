<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="row">
            <div class="col-md-12">
                <section class="panel">
                    <header class="panel-heading">
                        <i class="fas fa-stream"></i> <?php echo lang('livestock_variant'); ?>
                    </header>

                    <div class="panel-body">
                        <div class="adv-table editable-table">
                            <div class="space15">
                                <a href="<?php echo base_url('livestock/addLivestock'); ?>">
                                    <div class="btn-group">
                                        <button class="button button-primary">
                                            <i class="fa-solid fa-circle-arrow-left"></i>
                                        </button>
                                    </div>
                                </a>
                                <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                            </div>

                            <table class="table table-striped table-hover table-bordered" id="editable-sample">
                                <thead>
                                    <tr class="bg-info-light">
                                        <th colspan="3"><?php echo lang('livestock_name'); ?>: <?php echo $livestockById->ls_name; ?></th>
                                        <th colspan="3"><?php echo lang('ls_description'); ?>: <?php echo $livestockById->ls_description; ?></th>
                                    </tr>
                                    <tr>
                                        <th style="width: 10%;"><?php echo lang('serialNo'); ?></th>
                                        <th style="width: 10%;"><?php echo lang('livestock_id'); ?></th>
                                        <th style="width: 30%;"><?php echo lang('purchase_quantity'); ?>(<?= $settings->unit ?>)</th>
                                        <th style="width: 30%;"><?php echo lang('ls_description'); ?></th>
                                        <th style="width: 25%;"><?php echo lang('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial = 0;
                                    foreach ($variantByLivestocks as $variantByLivestock) {
                                        $serial++;
                                    ?>
                                        <tr data-id="<?= $variantByLivestock->lst_id ?>">
                                            <td><?= $serial ?></td>
                                            <td><?= $variantByLivestock->lst_title ?> </td>
                                            <td><?= $this->livestock_model->getLivestockPurchaseQuantityByVariantId($variantByLivestock->lst_id); ?></td>
                                            <td><?= $variantByLivestock->lst_description ?></td>
                                            <td>
                                                <a data-lst_id="<?= $variantByLivestock->lst_id ?>" data-lst_title="<?= $variantByLivestock->lst_title ?>" data-lst_description="<?= $variantByLivestock->lst_description ?>" data-ls_title="<?= $livestockById->ls_id ?>" class='button button-warning updateVariant' data-toggle='modal'><i class="fas fa-edit"></i> <?php echo lang('edit'); ?></a>

                                                <!-- conditional delete -->
                                                <?php
                                                $countDataIfAvailableInPurchase = $this->settings_model->getCountRow('livestock_purchase_value', 'purv_id', ['purv_lst_id' => $variantByLivestock->lst_id, 'purv_status' => 1]);
                                                if ($countDataIfAvailableInPurchase  == 0) { ?>
                                                    <form action="<?php echo base_url('livestock/deleteLivestockType'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                        <input type="hidden" name="lst_id" value="<?php echo $variantByLivestock->lst_id; ?>">
                                                        <input type="hidden" name="ls_id" value="<?php echo $livestockById->ls_id; ?>">
                                                        <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                        <button type="submit" class="button button-danger"><i class="fa fa-trash"></i> <?php echo lang('delete'); ?></button>
                                                    </form>
                                                <?php } else { ?>
                                                    <button type="button" class="button button-danger" title="<?= lang('this_item_used_another_places'); ?>" disabled><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                                <?php } ?>
                                                <!-- /.conditional delete -->

                                            </td>
                                        </tr>
                                        <!-- </ul> -->
                                    <?php } ?>
                                    <tr>
                                        <td></td>
                                        <td><?php echo lang('total'); ?></td>
                                        <td><?php $totalLivestockPurchasedQuantity = $this->livestock_model->getLivestockPurchaseQuantityByLivestockId($livestockById->ls_id, 'purv_quantity');
                                            if ($totalLivestockPurchasedQuantity) {
                                                echo $totalLivestockPurchasedQuantity;
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->

<!-- Update Livestock Variant -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-edit"></i> <?php echo lang('edit_livestock_type'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" id="livestockTypeEditForm" action="<?php echo base_url('livestock/updateLivestockType') ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('livestock_type'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="lst_title" id="" value='' placeholder="Enter Title" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('lst_description'); ?></label>
                        <textarea name="lst_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="lst_id" value=''>
                    <input type="hidden" name="lst_ls_id" value=''>
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.Update Livestock Variant -->



<!-- Javascript For Edit Trip -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Update type 
        $(".updateVariant").click(function(e) {
            e.preventDefault(e);
            var lst_id = $(this).attr('data-lst_id');
            $.ajax({
                url: 'livestock/editLivestockTypeByJason?lst_id=' + lst_id,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                // Populate the form fields with the data returned from server
                $('#livestockTypeEditForm').find('[name="lst_id"]').val(response.livestockType.lst_id).end()
                $('#livestockTypeEditForm').find('[name="lst_title"]').val(response.livestockType.lst_title).end()
                $('#livestockTypeEditForm').find('[name="lst_description"]').val(response.livestockType.lst_description).end()
                $('#livestockTypeEditForm').find('[name="lst_ls_id"]').val(response.livestockType.lst_ls_id).end()
                $('#livestockTypeEditForm').find('[name="lst_order_by"]').val(response.livestockType.lst_order_by).trigger('change').end()
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