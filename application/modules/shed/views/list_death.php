<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fa fa-home"></i> <?= lang('shed_wise_death_list'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table">
                        <div class="clearfix">
                            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                            <a data-toggle="modal" href="#informationPopup" class="button button-purple export"><i class="fa-solid fa-circle-question"></i> <?= lang('information'); ?></a>
                        </div>
                        <div class="space15">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="new__cards card__box">
                                        <div class="col-xs-8">
                                            <p><?= lang('total_assigned_to_shed'); ?> (<?= $settings->unit; ?>)</p>
                                            <h3><?php $totalAssignedQuantity = $this->purchase_model->getTotalLivestockAssignedToShedQuantity();
                                                if ($totalAssignedQuantity) {
                                                    echo $totalAssignedQuantity;
                                                } else {
                                                    echo 0;
                                                }
                                                ?></h3>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="text-right">
                                                <ul class="social__icon">
                                                    <li class="icon">
                                                        <span><i class="fa-solid fa-store"></i></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="new__cards card__box">
                                        <div class="col-xs-8">
                                            <p><?= lang('total_sold'); ?> (<?= $settings->unit; ?>)</p>
                                            <h3><?php $totalSold = $this->sale_model->getTotalSaleLivestockQuantity();
                                                if ($totalSold) {
                                                    echo $totalSold;
                                                } else {
                                                    echo 0;
                                                }
                                                ?></h3>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="text-right">
                                                <ul class="social__icon">
                                                    <li class="icon">
                                                        <span><i class="fa-solid fa-circle-check"></i></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="new__cards card__box">
                                        <div class="col-xs-8">
                                            <p><?= lang('total'); ?> <?= lang('death'); ?> (<?= $settings->unit; ?>)</p>
                                            <h3><?php $totalDeathQuantity = $this->shed_model->getTotalDeathLivestock();
                                                if ($totalDeathQuantity) {
                                                    echo $totalDeathQuantity;
                                                } else {
                                                    echo 0;
                                                }
                                                ?></h3>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="text-right">
                                                <ul class="social__icon">
                                                    <li class="icon">
                                                        <span><i class="fa-solid fa-face-frown-open"></i></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="new__cards card__box">
                                        <div class="col-xs-8">
                                            <p><?= lang('available'); ?> (<?= $settings->unit; ?>)</p>
                                            <h3><?php
                                                $alive = $totalAssignedQuantity - $totalDeathQuantity - $totalSold;
                                                if ($alive) {
                                                    echo $alive;
                                                } else {
                                                    echo 0;
                                                }
                                                ?></h3>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="text-right">
                                                <ul class="social__icon">
                                                    <li class="icon">
                                                        <span><i class="fa-solid fa-shop-lock"></i></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th><?= lang('serialNo'); ?></th>
                                    <th><?= lang('shed'); ?></th>
                                    <th><?= lang('total_assigned_quantity'); ?> (<?= $settings->unit ?>)</th>
                                    <th><?= lang('sold'); ?> (<?= $settings->unit ?>)</th>
                                    <th><?= lang('death'); ?> (<?= $settings->unit ?>)</th>
                                    <th><?= lang('in_stock'); ?> (<?= $settings->unit ?>)</th>
                                    <th><?php echo lang('batch'); ?> <?php echo lang('running'); ?></th>
                                    <th><?= lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $serial = 0;
                                foreach ($sheds as $shed) {
                                    $serial++;
                                ?>
                                    <tr class="">
                                        <td> <?= $serial ?></td>
                                        <td> <strong><?= $shed->sh_no; ?>:</strong> <?= $shed->sh_title; ?></td>
                                        <td> <?php $total_sum = $this->shed_model->getShedWiseLivestock($shed->sh_id);
                                                if ($total_sum) {
                                                    echo $total_sum;
                                                }
                                                ?>
                                        </td>
                                        <td>
                                            <?php $sold = $this->sale_model->getShedWiseSoldLivestock($shed->sh_id);
                                            if ($sold) {
                                                echo $sold;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php $death = $this->shed_model->getShedWiseDeathLivestock($shed->sh_id);
                                            if ($death) {
                                                echo $death;
                                            }
                                            ?>
                                        </td>
                                        <td><?php $inStock = $total_sum - ($sold + $death);
                                            if ($inStock) {
                                                echo $inStock;
                                            }
                                            ?>
                                        </td>
                                        <td class="table_text_middle">
                                            <?php
                                            $shedWiseRunningBatch = $this->report_model->getCountRow('live_assigned_shed_summary', 'lshs_id', ['lshs_sh_id' => $shed->sh_id, 'lshs_status' => 1, 'lshs_active_status' => 0]);
                                            if ($shedWiseRunningBatch) {
                                                echo "<span class='button bg-primary-light'> " . $shedWiseRunningBatch .  "</span>";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a class="" href="<?php echo base_url('') ?>shed/addShedWiseLivestockDeath?sh_id=<?php echo $shed->sh_id; ?>"><button type="button" class="button button-info"><i class="fas fa-eye"></i> <?= lang('death'); ?> <?= lang('details'); ?></button></i></a>
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
                                <p><?= lang('shed_death_popup_message_one'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('shed_death_popup_message_two'); ?></p>
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

<input type="hidden" id="shedQuantityAlert" value="<?php echo $this->report_model->getCountRow('shed', 'sh_id', ['sh_status' => 1]); ?>">

<!-- Javascript For Edit Trip -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
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