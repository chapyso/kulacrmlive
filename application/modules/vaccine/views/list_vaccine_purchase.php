<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('vaccine_purchase'); ?> <?= lang('list'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table ">
                        <div class="clearfix noprint">
                            <a data-toggle="modal" href="<?php echo base_url('vaccine/addVaccinePurchase'); ?>">
                                <div class="btn-group">
                                    <button id="" class="button button-primary">
                                        <i class="fa fa-plus-circle"></i> <?= lang('vaccine_purchase'); ?>
                                    </button>
                                </div>
                            </a>
                            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                        </div>
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th><?= lang('serialNo'); ?></th>
                                    <th><?= lang('purchase_date'); ?></th>
                                    <th><?= lang('supplier'); ?></th>
                                    <th><?= lang('total_amount'); ?></th>
                                    <th><?= lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serial = 0;
                                foreach ($vaccinePurchases as $value) {
                                    $serial++;
                                ?>
                                    <tr>
                                        <td><?= $serial ?></td>
                                        <td><?= date("$settings->date_format", strtotime($value->vps_date)); ?></td>
                                        <td><?= $this->supplier_model->getData('supplier', 's_id', $value->vps_s_id)->row()->s_name; ?></td>
                                        <td><?= $settings->currency . number_format_currency($value->vps_grand_total, 2); ?></td>
                                        <td>
                                            <a href="<?php echo base_url('') ?>vaccine/viewVaccinePurchase?vps_id=<?php echo $value->vps_id; ?>"><button type="button" class="button button-info"><i class="fa-solid fa-file-invoice"></i> <?= lang('invoice_capital'); ?></button></a>
                                            <a href="<?php echo base_url('') ?>vaccine/editVaccinePurchase?vps_id=<?php echo $value->vps_id; ?>"><button type="button" class="button button-warning"><i class="fas fa-edit"> </i> <?php echo lang('edit'); ?></button></a>
                                            <form action="<?php echo base_url('vaccine/deleteVaccinePurchase'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                <input type="hidden" name="vps_id" value="<?php echo $value->vps_id; ?>">
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