<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel">
            <header class="panel-heading">
                <i class="fa fa-home"></i> <?= lang('supplier_wise_payments_list'); ?>
            </header>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="clearfix">
                        <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                    </div>
                    <div class="space15"></div>

                    <table class="table table-striped table-hover table-bordered" id="editable-sample">
                        <thead>
                            <tr>
                                <th><?php echo lang('serialNo'); ?></th>
                                <th><?= lang('date'); ?></th>
                                <th><?= lang('payments'); ?></th>
                                <th><?= lang('received'); ?></th>
                                <th><?= lang('paid_by'); ?></th>
                                <th><?= lang('cheque_no'); ?></th>
                                <th><?= lang('reference'); ?></th>
                                <th><?= lang('note'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $serial = 0;
                            foreach ($paymentsBySupplier as $list) {
                                $serial++;
                            ?>
                                <tr class="">
                                    <td> <?= $serial ?></td>
                                    <td> <?= date("$settings->date_format", strtotime($list->sp_date)); ?></td>
                                    <td> <?= $list->sp_payment_amount ?></td>
                                    <td> <?= $list->sp_received_amount ?></td>
                                    <td> <?= $list->sp_paid_by ?></td>
                                    <td> <?= $list->sp_cheque_no ?></td>
                                    <td> <?= $list->sp_reference ?></td>
                                    <td> <?= $list->sp_description ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<!--footer start-->