<!--sidebar end-->
<?php $filter_from = isset($filter_from) ? $filter_from : ''; $filter_to = isset($filter_to) ? $filter_to : ''; ?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('product_sale_list'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table">
                        <div class="clearfix">
                            <a data-toggle="modal" href="<?php echo base_url('sale/addNewProductSale') ?>">
                                <div class="btn-group">
                                    <button id="" class="button button-primary">
                                        <i class="fas fa-plus-circle"></i> <?= lang('add_new_product_sale'); ?>
                                    </button>
                                </div>
                            </a>
                            <a href="<?php echo base_url('product/listProduct'); ?>">
                                <div class="btn-group">
                                    <button class="button button-info">
                                        <i class="fa-solid fa-circle-arrow-up"></i> <?= lang('production'); ?> <?= lang('list'); ?>
                                    </button>
                                </div>
                            </a>
                            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                            <a data-toggle="modal" href="#informationPopup" class="button button-purple export"><i class="fa-solid fa-circle-question"></i> <?= lang('information'); ?></a>
                            <?php $this->load->view('_partials/date_range_filter', array(
                                'action_url' => base_url('sale/listProductSale'),
                                'clear_url'  => base_url('sale/listProductSale'),
                                'from'       => $filter_from,
                                'to'         => $filter_to,
                            )); ?>
                        </div>
                        <div class="space15"></div>
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th><?= lang('serialNo'); ?>.</th>
                                    <th><?= lang('sale_date'); ?></th>
                                    <th><?= lang('client'); ?></th>
                                    <th><?= lang('total_sold_amount'); ?></th>
                                    <th><?php echo lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serial = 0;
                                foreach ($productsSale as $key => $list) {
                                    $serial++;
                                ?>
                                    <tr>
                                        <td><?= $serial ?></td>
                                        <td><?= date("$settings->date_format", strtotime($list->prss_date)) ?></td>
                                        <td><?= html_escape($this->client_model->getClientById($list->prss_c_id)->c_name); ?></td>
                                        <td><?= $settings->currency . number_format($list->prss_grand_total, 2, '.', ','); ?></td>
                                        <td>
                                            <a class="button button-success" href="<?php echo base_url('') ?>sale/viewProductSale?prss_id=<?php echo $list->prss_id; ?>"><i class="fas fa-eye"></i> <?= lang('view'); ?></a>
                                            <a class="button button-primary" href="<?php echo base_url('') ?>sale/viewProductSaleClientInvoice?prss_id=<?php echo $list->prss_id; ?>"><i class="fa fa-file"></i> <?= lang('invoice_capital'); ?></a>
                                            <a class="button button-warning" href="<?php echo base_url('') ?>sale/editProductSale?prss_id=<?php echo $list->prss_id; ?>"><i class="fas fa-edit"></i> <?php echo lang('edit'); ?></a>
                                            <form action="<?php echo base_url('sale/deleteProductSale'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>')">
                                                <input type="hidden" name="prss_id" value="<?php echo $list->prss_id; ?>">
                                                <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                <button type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
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
                                <p><?= lang('sale_popup_message_one'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('sale_popup_message_two'); ?></p>
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

<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
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