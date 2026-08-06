<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('expense_wise_payment'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table">
                        <div class="clearfix">
                            <a href="<?php echo base_url("expense/listExpense"); ?>">
                                <div class=" btn-group">
                                    <button class="button button-primary">
                                        <i class="fa-solid fa-circle-arrow-left"></i>
                                    </button>
                                </div>
                            </a>
                            <?php
                            $totalExpense = $expenseById->ex_amount;
                            $totalPaid = $this->expense_model->getExpenseWiseTotalPaidAmount($expenseById->ex_id);
                            $totalDueAmount = $totalExpense - $totalPaid;
                            ?>
                            <a data-toggle="modal" class="addNewPayment" data-expense-id="<?php echo $expenseById->ex_id; ?>" data-due-amount="<?php echo $totalDueAmount; ?>" data-name="<?php echo $expenseById->ex_name; ?>">
                                <div class="btn-group">
                                    <button type="button" class="button button-primary">
                                        <i class="fa fa-plus-circle"></i> <?= lang('add_new_payment'); ?>
                                    </button>
                                </div>
                            </a>
                            <a data-toggle="modal" href="<?php echo base_url('expense/listExpense'); ?>">
                                <div class="btn-group">
                                    <button id="" class="button button-info">
                                        <i class="fa-solid fa-circle-arrow-up"></i> <?= lang('expense'); ?>
                                    </button>
                                </div>
                            </a>
                            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                        </div>
                        <div class="space15">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="new__cards__auto card__box">
                                        <div class="col-xs-3">
                                            <div class="text-center">
                                                <p><strong><?= lang('basic_information'); ?></strong></p>
                                            </div>
                                            <table class="table">
                                                <tr>
                                                    <td><strong><?= lang('expense_name'); ?></strong></td>
                                                    <td>
                                                        <?php $expense = $this->expense_model->getExpenseById($expenseById->ex_id);
                                                        if ($expense) {
                                                            echo $expense->ex_name;
                                                        } else {
                                                            echo "<span class='text-danger'>" . lang('no_data_found') . "</span>";
                                                        }
                                                        ?>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td><strong><?= lang('category'); ?></strong></td>
                                                    <td><?php $category = $this->expense_model->getExpenseCategoryById($expenseById->ex_exc_id);
                                                        if ($category) {
                                                            echo $category->exc_name;
                                                        } else {
                                                            echo "<span class='text-danger'>" . lang('no_data_found') . "</span>";
                                                        }
                                                        ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong><?= lang('expense'); ?> <?= lang('date'); ?></strong></td>
                                                    <td><?php if ($expense) {
                                                            echo date("$settings->date_format", strtotime($expense->ex_date));
                                                        }
                                                        ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong><?= lang('expense_id'); ?></strong></td>
                                                    <td><?php if ($expense) {
                                                            echo $expense->ex_expense_id;
                                                        }
                                                        ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong><?= lang('note'); ?></strong></td>
                                                    <td><?php if ($expense) {
                                                            echo $expense->ex_description;
                                                        }
                                                        ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="row">
                                                <div class="text-center">
                                                    <ul class="social__icon">
                                                        <li class="icon">
                                                            <span><i class="fas fa-money-bill"></i></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="text-center">
                                                    <p><?= lang('total_expense_amount'); ?> </p>
                                                    <h3><?php
                                                        if ($totalExpense) {
                                                            echo $settings->currency . number_format($totalExpense, 2, '.', ',');
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="row">
                                                <div class="text-center">
                                                    <ul class="social__icon">
                                                        <li class="icon">
                                                            <span><i class="fas fa-money-bill-alt"></i></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="text-center">
                                                    <p><?= lang('total_paid_amount'); ?></p>
                                                    <h3><?php
                                                        if ($totalPaid) {
                                                            echo $settings->currency  . number_format($totalPaid, 2, '.', ',');
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="row">
                                                <div class="text-center">
                                                    <ul class="social__icon">
                                                        <li class="icon">
                                                            <span><i class="fas fa-money-bill-wave"></i></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="text-center">
                                                    <p><?= lang('due_amount'); ?></p>
                                                    <h3>
                                                        <?php
                                                        if ($totalDueAmount) {
                                                            echo $settings->currency . number_format(($totalDueAmount), 2, '.', ',');
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?>
                                                    </h3>
                                                </div>
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
                                    <th><?= lang('payment_date'); ?></th>
                                    <th><?= lang('paid_amount'); ?></th>
                                    <th><?= lang('paid_by'); ?></th>
                                    <th><?= lang('cheque'); ?></th>
                                    <th><?= lang('note'); ?></th>
                                    <th><?= lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $serial = 0;
                                foreach ($paymentsByExpenseId as $list) {
                                    $serial++;
                                ?>
                                    <tr>
                                        <td> <?= $serial ?></td>
                                        <td> <?= date("$settings->date_format", strtotime($list->exp_date)); ?></td>
                                        <td> <?= number_format($list->exp_paid_amount, 2, '.', ',') ?></td>
                                        <td> <?= $list->exp_paid_by ?></td>
                                        <td> <?= $list->exp_cheque_no ?></td>
                                        <td> <?= $list->exp_description ?></td>
                                        <td>
                                            <button type="button" class="button button-warning editExpensePayment" data-toggle="modal" data-payment-id="<?php echo $list->exp_id; ?>" data-expense-id="<?php echo $list->exp_ex_id; ?>" data-paid-amount="<?php echo $list->exp_paid_amount; ?>" data-paid_by="<?php echo $list->exp_paid_by; ?>" data-cheque_no="<?php echo $list->exp_cheque_no; ?>" data-date="<?php echo date("$settings->date_format", strtotime($list->exp_date)); ?>" data-description="<?php echo $list->exp_description; ?>" data-name="<?php echo $expenseById->ex_name; ?>" data-due-amount="<?php echo $totalDueAmount; ?>"><i class="fas fa-edit"></i> <?= lang('edit'); ?></button>
                                            <form action="<?php echo base_url('expense/deleteExpensePayment'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>')">
                                                <input type="hidden" name="exp_id" value="<?php echo $list->exp_id; ?>">
                                                <input type="hidden" name="ex_id" value="<?php echo $list->exp_ex_id; ?>">
                                                <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                <button type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?= lang('delete'); ?></button>
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

<!-- Expense Payment -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('add_new_expense_payment'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('expense/insertExpensePayments') ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('expense_name'); ?></label>
                            <input type="text" class="form-control" id="expenseName" placeholder="" readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('due_amount'); ?></label>
                            <input type="text" class="form-control" id="dueAmount" placeholder="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('paid_amount'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control input__number" name="exp_paid_amount" id="payment_amount" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"> <?php echo lang('paid_by'); ?><span class="text-danger">*</span></label>
                        <select id="paid_by" class="form-control m-bot15" name="exp_paid_by" value='' required>
                            <option value="cash"><?php echo lang('cash'); ?> </option>
                            <option value="cheque"><?php echo lang('cheque'); ?></option>
                            <option value="other"><?php echo lang('other'); ?> </option>
                        </select>
                    </div>
                    <div class="form-group" id="cheque_no" style="display: none;">
                        <label for="exampleInputEmail1"> <?php echo lang('cheque_no'); ?></label>
                        <input type="text" class="form-control" name="exp_cheque_no" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('date'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-inline datepicker" name="exp_date" value='<?php echo get_current_date(); ?>' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="exp_description" class="form-control" id="description" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="exp_ex_id" id="expenseId" value=''>
                    <section class="text-right">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Expense payment Edit -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('edit_expense_payment'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('expense/insertExpensePayments') ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('expense_name'); ?></label>
                            <input type="text" class="form-control" id="expenseNameEdit" placeholder="" readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('due_amount'); ?></label>
                            <input type="text" class="form-control" id="dueAmountEdit" placeholder="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('paid_amount'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control input__number" name="exp_paid_amount" id="paid_amount" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"> <?php echo lang('paid_by'); ?><span class="text-danger">*</span></label>
                        <select id="paid_by_edit" class="form-control m-bot15" name="exp_paid_by" value='' required>
                            <option value="cash"><?php echo lang('cash'); ?> </option>
                            <option value="cheque"><?php echo lang('cheque'); ?></option>
                            <option value="other"><?php echo lang('other'); ?> </option>
                        </select>
                    </div>
                    <div class="form-group" id="cheque_no_edit" style="display: none;">
                        <label for="exampleInputEmail1"> <?php echo lang('cheque_no'); ?></label>
                        <input type="text" class="form-control" name="exp_cheque_no" id="cheque_number" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('date'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-inline datepicker" name="exp_date" id="date" value='<?php echo get_current_date(); ?>' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="exp_description" class="form-control" id="description" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="exp_id" id="payment_id" value=''>
                    <input type="hidden" name="exp_ex_id" id="expense_id" value=''>
                    <section class="text-right">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<!-- Javascript For Edit Trip -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // For Insert
        $('#paid_by').on('change', function() {
            if ($(this).val() === "cheque") {
                $("#cheque_no").delay(500).fadeIn(100);
            } else {
                $("#cheque_no").hide()
            }
        });

        $(".addNewPayment").click(function(e) {
            var expense_id = $(this).attr('data-expense-id');
            var name = $(this).attr('data-name');
            var due_amount = $(this).attr('data-due-amount');
            $('#myModal').modal('show');
            $('#expenseId').val(expense_id);
            $('#expenseName').val(name);
            $('#dueAmount').val("<?php echo $settings->currency; ?>" + due_amount);

            $("#payment_amount").on('keyup change paste', function(e) {
                var payment_amount = $(this).val();
                if (parseFloat(due_amount) < parseFloat(payment_amount)) {
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('please_enter_a_valid_value'); ?>"
                    });
                    $("#payment_amount").val('');
                }
            });
        });

        // For Edit
        $('#paid_by_edit').on('change', function() {
            if ($(this).val() === "cheque") {
                $("#cheque_no_edit").delay(500).fadeIn(100);
            } else {
                $("#cheque_no_edit").hide()
            }
        });

        /* ========== edit expense payment ========== */
        $(".editExpensePayment").click(function(e) {
            var payment_id = $(this).attr('data-payment-id');
            var expense_id = $(this).attr('data-expense-id');
            var paid_amount = $(this).attr('data-paid-amount');
            var paid_by = $(this).attr('data-paid_by');
            var cheque_no = $(this).attr('data-cheque_no');
            var date = $(this).attr('data-date');
            var description = $(this).attr('data-description');
            var name = $(this).attr('data-name');
            var due_amount = $(this).attr('data-due-amount');
            $('#myModal2').modal('show');
            $('#payment_id').val(payment_id);
            $('#expense_id').val(expense_id);
            $('#paid_amount').val(paid_amount);
            $('#paid_by').val(paid_by).trigger('change');
            $('#cheque_number').val(cheque_no);
            $('#date').val(date);
            $('#description').val(description);
            $('#expenseNameEdit').val(name);
            $('#dueAmountEdit').val("<?php echo $settings->currency; ?>" + due_amount);

            $("#paid_amount").on('keyup change paste', function(e) {
                var payments_amount = $(this).val();
                var totalDueAmount = parseFloat(paid_amount) + parseFloat(due_amount);
                if (parseFloat(totalDueAmount) < parseFloat(payments_amount)) {
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('please_enter_a_valid_value'); ?>"
                    });
                    $("#paid_amount").val(paid_amount);
                }
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