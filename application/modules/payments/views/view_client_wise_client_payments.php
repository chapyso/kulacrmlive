<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel">
            <header class="panel-heading">
                <i class="fa fa-home"></i> <?= lang('client_wise_payment_list'); ?>
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
                                <th><?= lang('serialNo'); ?></th>
                                <th><?= lang('date'); ?></th>
                                <th><?= lang('received_amount'); ?></th>
                                <th><?= lang('payments'); ?></th>
                                <th><?= lang('paid_by'); ?></th>
                                <th><?= lang('cheque'); ?></th>
                                <th><?= lang('reference'); ?></th>
                                <th><?= lang('note'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $serial = 0;
                            foreach ($paymentsByClient as $list) {
                                $serial++;
                            ?>
                                <tr class="">
                                    <td> <?= $serial ?></td>
                                    <td> <?= date("$settings->date_format", strtotime($list->cp_date)); ?></td>
                                    <td> <?= $list->cp_received_amount ?></td>
                                    <td> <?= $list->cp_paid_by ?></td>
                                    <td> <?= $list->cp_cheque_no ?></td>
                                    <td> <?= $list->cp_reference ?></td>
                                    <td> <?= $list->cp_description ?></td>
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