<!--sidebar end-->
<?php $filter_from = isset($filter_from) ? $filter_from : ''; $filter_to = isset($filter_to) ? $filter_to : ''; ?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        
        <!-- 2026 MODERN PAGE HEADER & ACTIONS BAR -->
        <div class="kula-hero-card" style="margin-bottom: 20px; padding: 20px 28px;">
            <div class="hero-content-left">
                <h1 class="hero-title" style="font-size: 22px;">
                    <i class="fa-solid fa-receipt" style="color: #059669; margin-right: 8px;"></i>
                    <?= lang('expense'); ?> <?= lang('list'); ?>
                </h1>
                <p class="hero-subtitle">Track operational farm expenditures, category breakdowns, and payment vouchers</p>
            </div>

            <div class="hero-actions-right" style="gap: 8px; flex-wrap: wrap;">
                <a data-toggle="modal" href="#myModal" class="hero-export-btn" style="background: #047857; text-decoration: none;">
                    <i class="fa-solid fa-plus-circle"></i> <?php echo lang('add_new_expense'); ?>
                </a>

                <?php $csv_qs = http_build_query(array_filter(array('from' => $filter_from, 'to' => $filter_to))); ?>
                <a href="<?php echo base_url('expense/exportCSV') . ($csv_qs ? '?' . $csv_qs : ''); ?>" class="hero-date-pill" style="text-decoration: none; padding: 10px 18px;">
                    <i class="fa-solid fa-file-csv" style="color: #0284c7;"></i>
                    <span>Export CSV</span>
                </a>

                <button class="hero-date-pill" onclick="javascript:window.print();" style="border: 1px solid #e2e8f0; background: #fff;">
                    <i class="fa-solid fa-print" style="color: #475569;"></i>
                    <span><?php echo lang('print'); ?></span>
                </button>
            </div>
        </div>
                        <div class="btn-group">
                            <button data-toggle="modal" class="button button-primary addNewExpense">
                                <i class="fas fa-plus-circle"></i> <?= lang('add_new_expense'); ?>
                            </button>
                        </div>
                        <a data-toggle="modal" href="<?php echo base_url('expense/listExpenseCategory'); ?>">
                            <div class="btn-group">
                                <button id="" class="button button-info">
                                    <i class="fa-solid fa-circle-arrow-up"></i> <?= lang('category'); ?>
                                </button>
                            </div>
                        </a>
                        <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>

                        <a data-toggle="modal" href="#informationPopup" class="button button-purple export"><i class="fa-solid fa-circle-question"></i> <?= lang('information'); ?></a>
                        <?php $this->load->view('_partials/date_range_filter', array(
                            'action_url' => base_url('expense/listExpense'),
                            'clear_url'  => base_url('expense/listExpense'),
                            'from'       => $filter_from,
                            'to'         => $filter_to,
                        )); ?>
                    </div>
                    <div class="space15">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="new__cards card__box">
                                    <div class="col-xs-8">
                                        <p><?= lang('total_expense_amount'); ?> </p>
                                        <h3><?php $totalExpense = $this->expense_model->getTotalSumOfExpense();
                                            if ($totalExpense) {
                                                echo $settings->currency . number_format($totalExpense, 2, '.', ',');
                                            } else {
                                                echo 0;
                                            }
                                            ?></h3>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="text-right">
                                            <ul class="social__icon">
                                                <li class="icon">
                                                    <i class="fa-solid fa-money-bill"></i>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="new__cards card__box">
                                    <div class="col-xs-8">
                                        <p><?= lang('total_paid_amount'); ?></p>
                                        <h3><?php $totalPaid = $this->expense_model->getTotalSumPaidAmount();
                                            if ($totalPaid) {
                                                echo $settings->currency . number_format($totalPaid, 2, '.', ',');
                                            } else {
                                                echo 0;
                                            }
                                            ?></h3>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="text-right">
                                            <ul class="social__icon">
                                                <li class="icon">
                                                    <i class="fa-solid fa-money-bill-1"></i>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="new__cards card__box">
                                    <div class="col-xs-8">
                                        <p><?= lang('due_amount'); ?></p>
                                        <h3>
                                            <?php
                                            if ($totalPaid || $totalExpense) {
                                                echo $settings->currency . number_format(($totalExpense - $totalPaid), 2, '.', ',');
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </h3>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="text-right">
                                            <ul class="social__icon">
                                                <li class="icon">
                                                    <i class="fa-solid fa-money-bill-1-wave"></i>
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
                                <th><?= lang('serialNo'); ?>.</th>
                                <th><?= lang('expense_id'); ?> </th>
                                <th><?= lang('date'); ?> </th>
                                <th><?= lang('expense_name'); ?> </th>
                                <th><?= lang('category'); ?></th>
                                <th><?= lang('expense_amount'); ?></th>
                                <th><?= lang('paid_amount'); ?></th>
                                <th><?= lang('options'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $serial = 0;
                            foreach ($expenses->result() as $list) {
                                $serial++;
                            ?>
                                <tr class="">
                                    <td><?= $serial; ?></td>
                                    <td><?= html_escape($list->ex_expense_id); ?></td>
                                    <td><?= date("$settings->date_format", strtotime($list->ex_date)); ?></td>
                                    <td><?= html_escape($list->ex_name); ?></td>
                                    <td><?php $category = $this->expense_model->getExpenseCategoryById($list->ex_exc_id);
                                        if ($category) {
                                            echo $category->exc_name;
                                        } else {
                                            echo "<span class='text-danger'>" . lang('no_data_found') . "</span>";
                                        }
                                        ?></td>
                                    <td><?= $settings->currency . number_format_currency($list->ex_amount, 2); ?></td>
                                    <td><?php $paidAmount = $this->expense_model->getTotalSumAmountByExpenseId($list->ex_id);
                                        if ($paidAmount) {
                                            echo $settings->currency . number_format_currency($paidAmount, 2);
                                        } else {
                                            echo 0;
                                        }
                                        ?></td>
                                    <td>
                                        <button type="button" class="button button-warning editButton" data-sub-category-id="<?= $list->ex_exsc_id ?>" data-toggle="modal" data-id="<?php echo $list->ex_id; ?>" data-expense-amount="<?php echo $list->ex_amount; ?>" data-paid-amount="<?php echo $paidAmount; ?>"><i class="fas fa-edit"></i> <?php echo lang('edit'); ?></button>

                                        <form action="<?php echo base_url('expense/deleteExpense'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('expense_delete_warning'); ?>');">
                                            <input type="hidden" name="ex_id" value="<?php echo $list->ex_id; ?>">
                                            <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                            <button type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                        </form>

                                        <a href="<?php echo base_url('') ?>expense/viewExpensePayments?ex_id=<?php echo $list->ex_id; ?>"><button type="button" class="button button-info"><i class="fa-solid fa-file-invoice"></i> <?= lang('expense_payments'); ?></button></i></a>
                                    </td>
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
<input type="hidden" id="expenseCategoryAlert" value="<?php echo $this->report_model->getCountRow('expense_category', 'exc_id', ['exc_status' => 1]); ?>">



<!-- ========== add new expense ========== -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('add_new_expense'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" action="<?php echo base_url('expense/insertExpense'); ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('expense_id'); ?><span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ex_expense_id" id="" value='' placeholder="" required>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('expense_name'); ?><span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ex_name" id="" value='' placeholder="" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label for="exampleInputEmail1"><?= lang('category'); ?><span class="text-danger">*</span></label>
                            <select name="ex_exc_id" class="form-control js-example-basic-single" id="ExCategoryId" placeholder="" style="width: 100%;" required>
                                <option value=""><?= lang('please_select_category'); ?></option>
                                <?php if (!empty($expenseCategories))
                                    foreach ($expenseCategories->result() as $exCategories) {
                                ?>
                                    <option value="<?php echo $exCategories->exc_id ?>"><?php echo $exCategories->exc_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('expense_amount'); ?><span class="text-danger">*</span></label>
                            <input type="text" class="form-control input__number" name="ex_amount" id="expenseAmount" value='' placeholder="" required>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('paid_amount'); ?></label>
                            <input type="text" class="form-control input__number" name="exp_paid_amount" id="paidAmount" value='' placeholder="">
                        </div>
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
                        <input type="text" class="form-control" name="exp_cheque_no" id="" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('expense_date'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-inline datepicker" name="ex_date" id="" value='<?php echo get_current_date(); ?>' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="ex_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <section class="text-right">
                        <button type="submit" name="submit" class="button button-info submit_button" id="submitButton"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<!-- ========== edit expense ========== -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-edit"></i> <?php echo lang('edit_expense'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" id="ExpenseEditForm" action="<?php echo base_url('expense/insertExpense'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('expense_id'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ex_expense_id" id="" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('expense_name'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ex_name" id="" value='' placeholder="" required>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('expense_amount'); ?><span class="text-danger">*</span></label>
                            <input type="text" class="form-control input__number" name="ex_amount" id="expenseAmountEdit" value='' placeholder="" required>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('paid_amount'); ?></label>
                            <input type="text" class="form-control" id="expensePaidAmount" value='' placeholder="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('category'); ?><span class="text-danger">*</span></label>
                        <select name="ex_exc_id" class="form-control js-example-basic-single" id="ExCategoryIdEdit" placeholder="" style="width: 100%;" required>
                            <option value=""><?= lang('please_select_category'); ?></option>
                            <?php if (!empty($expenseCategories))
                                foreach ($expenseCategories->result() as $exCategoriesEdit) {
                            ?>
                                <option value="<?php echo $exCategoriesEdit->exc_id ?>"><?php echo $exCategoriesEdit->exc_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('expense_date'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker" name="ex_date" id="" value='<?php echo get_current_date(); ?>' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="ex_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="ex_id" value=''>
                    <section class="text-right">
                        <button type="submit" name="submit" class="button button-info submit_button" id="submitButtonEdit"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Information popup -->
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
                                <p><?= lang('expense_popup_message_one'); ?></p>
                            </li>
                            <li>
                                <p><?= lang('expense_popup_message_two'); ?></p>
                            </li>
                        </ol>
                    </div>
                    <div class="col-xs-6">
                        <img class="img-square information__modal__image" src="<?php echo base_url('uploads/avatar/livestock-dummy-image.jpg'); ?>" alt="No img">
                    </div>
                </div>
                <section class="text-right">
                    <button type="button" class="button button-purple" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> <?= lang('close'); ?></button>
                </section>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Information popup -->


<!-- Javascript For Edit staff -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $(".addNewExpense").click(function() {
            $('#myModal').modal('show');

            $("#expenseAmount, #paidAmount").on('keyup change paste', function() {
                var expenseAmount = $("#expenseAmount").val();
                var paidAmount = $("#paidAmount").val();
                if (parseFloat(paidAmount) > parseFloat(expenseAmount)) {
                    $('#submitButton').attr('disabled', 'disabled');
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('please_enter_a_valid_value'); ?>"
                    });
                    $("#paidAmount").val('');
                } else {
                    $('#submitButton').removeAttr('disabled');
                }

                if (parseFloat(expenseAmount) <= 0) {
                    $('#submitButton').attr('disabled', 'disabled');
                } else {
                    $('#submitButton').removeAttr('disabled');
                }
            });
        });



        $('#paid_by').on('change', function() {
            if ($(this).val() === "cheque") {
                $("#cheque_no").delay(500).fadeIn(100);
            } else {
                $("#cheque_no").hide()
            }
        });

        // Edit Button
        $(".editButton").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            var expenseAmount = $(this).attr('data-expense-amount');
            var paidAmount = $(this).attr('data-paid-amount');

            $.ajax({
                url: 'expense/editExpenseByJason?ex_id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                // Populate the form fields with the data returned from server 
                $('#ExpenseEditForm').find('[name="ex_id"]').val(response.expensesById.ex_id).end()
                $('#ExpenseEditForm').find('[name="ex_expense_id"]').val(response.expensesById.ex_expense_id).end()
                $('#ExpenseEditForm').find('[name="ex_name"]').val(response.expensesById.ex_name).end()
                $('#ExpenseEditForm').find('[name="ex_amount"]').val(response.expensesById.ex_amount).end()
                $('#ExpenseEditForm').find('[name="ex_exc_id"]').val(response.expensesById.ex_exc_id).trigger('change').end()
                $('#ExpenseEditForm').find('[name="ex_date"]').val(response.expensesById.ex_date).end()
                $('#ExpenseEditForm').find('[name="ex_description"]').val(response.expensesById.ex_description).end()
                $('#ExpenseEditForm').find('#expensePaidAmount').val(paidAmount).end()
                $('#myModal2').modal('show');
            });

            // Alert in adit if give value less than paid amount
            $("#expenseAmountEdit").on('blur', function() {
                var expenseAmountEdit = $(this).val();
                if (parseFloat(expenseAmountEdit) < parseFloat(paidAmount)) {
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('expense_paid_amount_edit_alert'); ?>"
                    });
                }
            });
            $("#expenseAmountEdit").on('keyup change paste', function() {
                var expenseAmountEdit = $(this).val();
                if (expenseAmountEdit == '') {
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('please_enter_a_valid_value'); ?>"
                    });
                    $('#submitButtonEdit').attr('disabled', 'disabled');
                } else {
                    $('#submitButtonEdit').removeAttr('disabled');
                }

                if (parseFloat(expenseAmountEdit) <= 0) {
                    $('#submitButtonEdit').attr('disabled', 'disabled');
                } else {
                    $('#submitButtonEdit').removeAttr('disabled');
                }
            });

        });

    });
</script>

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
        function expenseCategoryAlert() {
            var expenseCategoryAlert = $("#expenseCategoryAlert").val();

            if (expenseCategoryAlert == 0) {
                toastr.warning('<?= lang('no_expense_category_found'); ?>');
            }
        }
        expenseCategoryAlert();

        <?php if ($this->session->flashdata('success')): ?>
        toastr.success('<?= html_escape($this->session->flashdata('success')); ?>');
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
        toastr.error('<?= html_escape($this->session->flashdata('error')); ?>');
        <?php endif; ?>
    });
</script>