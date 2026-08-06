<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel">
            <header class="panel-heading bg-info">
                <i class="fas fa-edit"></i> <?= lang('update_food_purchase'); ?>
            </header>
            <style>
                form {
                    border: 1px solid #ccc;
                    padding: 23px;
                    background: #fff;
                    height: auto;
                    clear: both;
                }
            </style>
            <div class="panel">
                <div class="adv-table editable-table ">
                    <div class="clearfix">
                        <div class="col-md-12">
                            <section class="panel">
                                <div class="panel-body">
                                    <?php echo validation_errors(); ?>
                                    <div class="row">
                                        <!-- col-md-9 -->
                                        <div class="col-md-9">
                                            <!-- <form> -->
                                            <input type="hidden" value="0" id="auto_generate_id">
                                            <input type="hidden" value="0" id="auto_generate_id_edit">
                                            <div class="form-group col-sm-4">
                                                <label for="exampleInputEmail1"><?= lang('food_name'); ?><span class="text-danger">*</span></label>
                                                <select name="fdp_fd_id" id="fdp_fd_id" class="form-control js-example-basic-single " placeholder="" style="width: 100%;" required>
                                                    <option value=""><?= lang('please_select_food'); ?></option>
                                                    <?php if (!empty($foods)) foreach ($foods as $food) { ?>
                                                        <option value="<?php echo $food->fds_id; ?>"> <?php echo $food->fds_food_title; ?> (<?php $unit = $this->settings_model->getUnitById($food->fds_unit_id);
                                                                                                                                            if ($unit) {
                                                                                                                                                echo  $unit->un_name;
                                                                                                                                            }
                                                                                                                                            ?>)</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label for="exampleInputEmail1"> <?php echo lang('unit_price'); ?><span class="text-danger">*</span></label>
                                                <input type="text" class="form-control calculate_unit_price input__number" name="fdp_unit_price_cal" id="price" value='' placeholder="">
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label for="exampleInputEmail1"><?= lang('quantity'); ?><span class="text-danger">*</span></label>
                                                <input type="text" class="form-control calculate_quantity input__number" name="fdp_quantity_cal" id="qty" value='' placeholder="">
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label for="exampleInputEmail1"> <?php echo lang('discount'); ?> </label>
                                                <input type="text" class="form-control calculate_discount input__number" name="fdp_discount_cal" id="discount" value='' placeholder="">
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label for="exampleInputEmail1"> <?php echo lang('total'); ?><span class="text-danger">*</span></label>
                                                <input type="text" class="form-control calculate_total" name="fdp_total_cal" id="total" value='' placeholder="" readonly>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" class="button button-primary save-btn form" style="margin: 0 15px 10px 0;"><i class="fas fa-plus-circle"></i> <?= lang('add'); ?> </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Payment warning message -->
                                        <div class="col-md-3">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="new__cards__auto card__box bg-warning-light">
                                                        <div class="col-xs-12">
                                                            <p><strong class="text-danger"><?= lang('warning'); ?>:</strong> <?= lang('payments_delete_warning_in_invoice_edit'); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.Payment warning message -->
                                    </div>

                                    <form role="form" action="<?php echo base_url('food/updateFoodPurchase') ?>" method="post" id="editPurchaseForm" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="row row-top-margin">
                                                    <div class="col-md-12">
                                                        <table class="table table-bordered data-table">
                                                            <thead>
                                                                <th><?= lang('food_name'); ?></th>
                                                                <th><?= lang('unit_price'); ?></th>
                                                                <th><?= lang('quantity'); ?></th>
                                                                <th><?= lang('discount'); ?></th>
                                                                <th><?= lang('total'); ?></th>
                                                                <th width="200px"><?= lang('actions'); ?></th>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                foreach ($foodPurchaseValueBySummaryById as $summary_id) {
                                                                ?>
                                                                    <tr class="session_data_store_class" data-fdp-fd-id="<?= $summary_id->fdpv_fd_id ?>" data-fdp-unit-price="<?= $summary_id->fdpv_unit_price ?>" data-fdp-quantity="<?= $summary_id->fdpv_quantity ?>" data-fdp-discount="<?= $summary_id->fdpv_discount ?>" data-fdp-total="<?= $summary_id->fdpv_total ?>">
                                                                        <td> <?= $this->food_model->getFoodSummaryById($summary_id->fdpv_fd_id)->fds_food_title; ?><input type="hidden" name="fdp_fd_id[]" value="<?= $summary_id->fdpv_fd_id ?>"></td>
                                                                        <td> <?= $summary_id->fdpv_unit_price ?><input type="hidden" name="fdp_unit_price[]" value="<?= $summary_id->fdpv_unit_price ?>"></td>
                                                                        <td> <?= $summary_id->fdpv_quantity ?><input type="hidden" name="fdp_quantity[]" value="<?= $summary_id->fdpv_quantity ?>"></td>
                                                                        <td> <?= $summary_id->fdpv_discount ?><input type="hidden" name="fdp_discount[]" value="<?= $summary_id->fdpv_discount ?>"></td>
                                                                        <td> <?= $summary_id->fdpv_total ?><input type="hidden" class="for_sum_sub_total" name="fdp_total[]" value="<?= $summary_id->fdpv_total ?>"></td>
                                                                        <td>
                                                                            <button type="button" class='button button-warning dynamic-product-edit display_hide'><i class="fas fa-edit"></i></button>
                                                                            <button type="button" class='button button-danger btn-delete' data-fdp_fd_id="<?= $summary_id->fdpv_fd_id ?>"><i class="fas fa-trash"></i></button>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="hidden" value="1" id="legal_sum">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo lang('supplier'); ?><span class="text-danger">*</span></label>
                                                    <select class="form-control js-example-basic-single" name="fdp_s_id" value='' required>
                                                        <option value=""><?= lang('please_select_supplier'); ?></option>
                                                        <?php foreach ($suppliers as $supplier) { ?>
                                                            <option value="<?php echo $supplier->s_id; ?>" <?php if ($foodPurchaseById) {
                                                                                                                if ($foodPurchaseById->fdps_s_id == $supplier->s_id) {
                                                                                                                    echo "selected";
                                                                                                                }
                                                                                                            } ?>> <?php echo $supplier->s_name; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo lang('date'); ?><span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control datepicker" name="fdp_date" id="" value='<?= date("$settings->date_format", strtotime($foodPurchaseById->fdps_date)); ?>' placeholder="" autocomplete="off" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo lang('sub_total'); ?><span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control calculate_sub_total readonly" name="fdp_sub_total" id="sub_total" value='<?= $foodPurchaseById->fdps_sub_total; ?>' placeholder="" required>
                                                </div>

                                                <div class="form-group display_hide">
                                                    <label for="exampleInputEmail1"> <?php echo lang('discount'); ?> </label>
                                                    <input type="text" class="form-control calculate_grand_discount input__number" name="fdp_grand_discount" id="discount" value='<?= $foodPurchaseById->fdps_grand_discount; ?>' placeholder="">
                                                </div>
                                                <div class="form-group display_hide">
                                                    <label for="exampleInputEmail1"> <?php echo lang('amount_payable'); ?><span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control calculate_grand_total readonly" name="fdp_grand_total" id="grand_total" value='<?= $foodPurchaseById->fdps_grand_total; ?>' placeholder="" required>
                                                </div>

                                                <!-- Payment Method -->
                                                <div class="button bg-primary-light" style="width: 100%;">
                                                    <input type="checkbox" id="paymentCheckBox" />
                                                    <label for="paymentCheckBox"><?= lang('click_here_to_payment_now'); ?></label>
                                                </div>
                                                <div id="modal_body" style="display: none; border: 1px solid #b8daff; padding: 10px; margin: 5px 0 5px 0">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?= lang('paid'); ?> <?= lang('amount'); ?><span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control input__number" name="sp_payment_amount" id="payableAmount" value='' placeholder="">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"> <?php echo lang('paid_by'); ?><span class="text-danger">*</span></label>
                                                        <select id="paid_by" class="form-control m-bot15" name="sp_paid_by" value=''>
                                                            <option value="cash"><?php echo lang('cash'); ?> </option>
                                                            <option value="cheque"><?php echo lang('cheque'); ?></option>
                                                            <option value="other"><?php echo lang('other'); ?> </option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group" id="cheque_no" style="display: none;">
                                                        <label for="exampleInputEmail1"> <?php echo lang('cheque_no'); ?><span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="sp_cheque_no" id="check_number" value='' placeholder="">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?= lang('reference'); ?></label>
                                                        <input type="text" class="form-control" name="sp_reference" id="reference" value='' placeholder="">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                                                        <input name="sp_description" class="form-control" id="description" placeholder="">
                                                    </div>
                                                </div>
                                                <!-- /.Payment Method -->


                                                <div class="form-group">
                                                    <!-- Text Area -->
                                                    <label class="control-label col-md-3 note"><?php echo lang('note'); ?></label>
                                                    <div class="col-md-12 note">
                                                        <textarea class="form-control" name="fdp_description" value="" rows="5" cols="10" style="height: auto !important;"><?= $foodPurchaseById->fdps_description; ?> </textarea>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="fdps_id" value='<?= $foodPurchaseById->fdps_id; ?>'>
                                                <div class="form-group">
                                                    <button type="submit" name="submit" class="button button-info text-right" style="margin-top: 35px; width: 100%; padding: 10px 0 10px 0;"> <?= lang('submit'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <br />
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
</section>
<!--main content end-->
<!--footer start-->



<!-- page end-->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // Clear session
        sessionStorage.clear();

        $(".session_data_store_class").each(function() {
            let fdp_fd_id = $(this).data("fdp-fd-id");
            sessionStorage.setItem(fdp_fd_id, fdp_fd_id);
        });

        // Calculate Data
        $(document).on('keyup change paste', function() {
            var getUnitPrice = $("input[name='fdp_unit_price_cal']").val();
            var getQuantity = $("input[name='fdp_quantity_cal']").val();
            var getDiscount = $("input[name='fdp_discount_cal']").val();
            var calculateUnitPriceIntoQuantity = getUnitPrice * getQuantity;
            var getTotal = calculateUnitPriceIntoQuantity - getDiscount;
            $("input[name='fdp_total_cal']").val(getTotal);
        });

        // Remove discount amount if discount amount is more than total amount
        $("#discount").on('keyup change paste', function() {
            var keyupDiscountAmount = $(this).val();
            var unitPrice = $("#price").val();
            var quantity = $("#qty").val();
            var totalAmount = parseFloat(unitPrice) * parseFloat(quantity);

            if (parseFloat(keyupDiscountAmount) > parseFloat(totalAmount)) {
                Swal.fire({
                    icon: "warning",
                    title: "<?= lang('warning'); ?>...",
                    text: "<?= lang('please_enter_a_valid_value'); ?>"
                });
                $(this).val('');
            }
        });

        // Check food if already added to invoice
        $("#fdp_fd_id").change(function() {

            var fdp_fd_id = $("#fdp_fd_id option:selected").val();

            if (sessionStorage.getItem(fdp_fd_id)) {
                if (sessionStorage.getItem(fdp_fd_id) == fdp_fd_id) {
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('you_have_already_added_this_item'); ?>"
                    });
                    $("select#fdp_fd_id")[0].selectedIndex = 0;
                    $('#fdp_fd_id').trigger('change');
                }
            }
        });

        // Create New Row With Entered Data
        $(".form").click(function(e) {
            e.preventDefault();

            // Auto Generate ID
            var auto_generate_id = parseInt($("#auto_generate_id").val()) + 1;
            $("#auto_generate_id").val(auto_generate_id);
            var fdp_fd_id1 = "fdp_fd_id" + `${auto_generate_id}`;
            var fdp_lst_id1 = "livestock_type" + `${auto_generate_id}`;

            var fdp_fd_id = $("#fdp_fd_id option:selected").val();
            var fdp_fd_text = $("#fdp_fd_id option:selected").text();

            var fdp_lst_id = $("#livestock_type option:selected").val();
            var fdp_lst_text = $("#livestock_type option:selected").text();

            var fdp_unit_price = $("input[name='fdp_unit_price_cal']").val();
            var fdp_quantity = $("input[name='fdp_quantity_cal']").val();
            var fdp_discount = $("input[name='fdp_discount_cal']").val();
            var fdp_total = $("input[name='fdp_total_cal']").val();

            var priceIntoQuantity = fdp_unit_price * fdp_quantity;
            var newTotal = priceIntoQuantity - fdp_discount;

            if (fdp_fd_id == "" || fdp_fd_id == null) {
                alert('Please Select Livestock.');
            } else if (fdp_unit_price == "" || fdp_unit_price == null) {
                document.getElementById("price").placeholder = "Please fill this.";
                document.getElementById("price").style.borderColor = 'red';
            } else if (fdp_quantity == "" || fdp_quantity == null) {
                document.getElementById("qty").placeholder = "Please fill this.";
                document.getElementById("qty").style.borderColor = 'red';
            } else {
                $(".data-table tbody").append("<tr data-" + fdp_fd_id1 + "='" + fdp_fd_id + "' data-fdp_unit_price='" + fdp_unit_price + "' data-fdp_quantity='" + fdp_quantity + "' data-fdp_discount='" + fdp_discount + "' data-fdp_total='" + fdp_total + "'><td>" + fdp_fd_text + " <input type='hidden' name='fdp_fd_id[]' value='" + fdp_fd_id + "'></td><td>" + fdp_unit_price + "<input type='hidden' name='fdp_unit_price[]' value=" + fdp_unit_price + "></td><td>" + fdp_quantity + "<input type='hidden' name='fdp_quantity[]' value='" + fdp_quantity + "'></td> <td>" + fdp_discount + "<input type='hidden' name='fdp_discount[]' value='" + fdp_discount + "'></td><td>" + newTotal + "<input type='hidden' class='for_sum_sub_total' name='fdp_total[]' value='" + newTotal + "'></td><td width='200px'><button type='button' class='button button-warning product-edit display_hide'><i class='fas fa-edit'></i></button> <button class='button button-danger btn-delete data-prs_pra_id=" + fdp_fd_id + "'><i class='fas fa-trash'></i></button></td></tr>");
            }



            // Sending Calculated Data
            var sum = 0;
            $('.for_sum_sub_total').each(function() {
                sum += parseFloat(this.value);
            });

            sendingData(sum);

            var calculate_grand_discount = $(".calculate_grand_discount").val();
            var cal_gra_dis = sum - calculate_grand_discount;

            // Send total sum to this input field
            $("input[name='fdp_sub_total']").attr('value', sum);
            $("input[name='fdp_grand_total']").attr('value', cal_gra_dis);


            $("input[name='fdp_unit_price_cal']").val('');
            $("input[name='fdp_quantity_cal']").val('');
            $("input[name='fdp_discount_cal']").val('');
            $("input[name='fdp_total_cal']").val('');

            // Select 1st index in dropdown
            $("select#fdp_fd_id")[0].selectedIndex = 0;
            $('#fdp_fd_id').trigger('change');

            // Store vaccine id in session 
            sessionStorage.setItem(fdp_fd_id, fdp_fd_id);
        });

        // Calculate grand total to minus grand discount
        function sendingData(sum) {
            $(".calculate_grand_discount").keyup(function() {

                var cgd = $(".calculate_grand_discount").val();

                $("input[name='fdp_sub_total']").attr('value', sum);

                var variable = sum - cgd;
                $(".calculate_grand_total").val(variable);
            });
        }

        // Remove Data Row
        $("body").on("click", ".btn-delete", function() {
            $(this).parents("tr").remove();

            // Remove Paid Amount Value
            $("#payableAmount").val('');

            // Sending Calculated Data
            var sum = 0;
            $('.for_sum_sub_total').each(function() {
                sum += parseFloat(this.value);
            });

            var calculate_grand_discount = $(".calculate_grand_discount").val();
            var cal_gra_dis = sum - calculate_grand_discount;

            // Send total sum to this input field
            $("input[name='fdp_sub_total']").attr('value', sum);
            $("input[name='fdp_grand_total']").attr('value', cal_gra_dis);

            // Empty Subtotal for that user can't submit
            var valueSubTotal = $("input[name='fdp_sub_total']").val();
            if (valueSubTotal == 0) {
                $("input[name='fdp_sub_total']").val('');
            }

            // Get fdp_fd_id id from delete button and remove from session
            let fdp_fd_id = $(this).data("fdp_fd_id");
            sessionStorage.removeItem(fdp_fd_id);
        });

        // Update Row Data
        $("body").on("click", ".product-edit", function() {
            $(".dynamic-product-edit").prop("disabled", true);
            $(".product-edit").prop("disabled", true);
            // Auto Generated ID
            var auto_generate_id = parseInt($("#auto_generate_id").val());
            $("#auto_generate_id").val(auto_generate_id);

            var fdp_fd_id_dynamic = "fdp_fd_id" + `${auto_generate_id}`;

            var fdp_fd_id = $(this).parents("tr").attr('data-' + fdp_fd_id_dynamic);
            var fdp_unit_price = $(this).parents("tr").attr('data-fdp_unit_price');
            var fdp_quantity = $(this).parents("tr").attr('data-fdp_quantity');
            var fdp_discount = $(this).parents("tr").attr('data-fdp_discount');
            var fdp_total = $(this).parents("tr").attr('data-fdp_total');

            $(this).parents("tr").find("td:eq(0)").html(`<div class="form-group col-sm-2" style="width: 100%">
                                                    <select name="fdp_fd_id" id="${fdp_fd_id_dynamic}" class="form-control selectedValue" data-id='${fdp_fd_id}' placeholder="" style="width: 100%;" required>
                                                        <option value=""><?= lang('please_select_food'); ?></option>
                                                        <?php if (!empty($foods)) foreach ($foods as $fds) { ?>
                                                            <option value="<?php echo $fds->fds_id; ?>"> <?php echo $fds->fds_food_title; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>`);

            $(this).parents("tr").find("td:eq(1)").html('<input type="number" class="form-control new_fdp_unit_price input__number" name="fdp_unit_price" value="' + fdp_unit_price + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(2)").html('<input type="number" class="form-control new_fdp_quantity input__number" name="fdp_quantity" value="' + fdp_quantity + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(3)").html('<input type="number" class="form-control new_fdp_discount input__number" name="fdp_discount" value="' + fdp_discount + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(4)").html('<input class="form-control new_fdp_total" name="fdp_total" value="' + fdp_total + '" style="width: 100%" readonly>');

            $(".selectedValue").each(function() {
                $(this).val($(this).attr("data-id"));
            });

            // Row Wise Calculation
            $(document).on('keyup change paste', function() {
                var unit_price = $(".new_fdp_unit_price").val();
                var quantity = $(".new_fdp_quantity").val();
                var discount = $(".new_fdp_discount").val();

                var c_total = unit_price * quantity;
                var row_wise_total = c_total - discount;
                $(".new_fdp_total").val(row_wise_total);
            });


            $(this).parents("tr").find("td:eq(5)").prepend("<button class='button button-info btn-xs product-update' style='width: auto;'><?= lang('update'); ?></button>");
            $(this).hide();


        });

        // Update Row Data
        $("body").on("click", ".product-update", function() {
            $(".dynamic-product-edit").prop("disabled", false);
            $(".product-edit").prop("disabled", false);
            // Auto Generate ID
            var auto_generate_id = parseInt($("#auto_generate_id").val());
            $("#auto_generate_id").val(auto_generate_id);

            var fdp_fd_id = $(this).parents("tr").find("#fdp_fd_id" + auto_generate_id + " option:selected").val();
            var fdp_fd_id_text = $(this).parents("tr").find("#fdp_fd_id" + auto_generate_id + " option:selected").text();

            var fdp_unit_price = $(this).parents("tr").find("input[name='fdp_unit_price']").val();
            var fdp_quantity = $(this).parents("tr").find("input[name='fdp_quantity']").val();
            var fdp_discount = $(this).parents("tr").find("input[name='fdp_discount']").val();
            var fdp_total = $(this).parents("tr").find("input[name='fdp_total']").val();



            $(this).parents("tr").find("td:eq(0)").html('' + fdp_fd_id_text + ' <input type="hidden" name="fdp_fd_id[]" value="' + fdp_fd_id + '">');
            $(this).parents("tr").find("td:eq(1)").html('' + fdp_unit_price + ' <input type="hidden" name="fdp_unit_price[]" value="' + fdp_unit_price + '">');
            $(this).parents("tr").find("td:eq(2)").html('' + fdp_quantity + ' <input type="hidden" name="fdp_quantity[]" value="' + fdp_quantity + '">');
            $(this).parents("tr").find("td:eq(3)").html('' + fdp_discount + ' <input type="hidden" name="fdp_discount[]" value="' + fdp_discount + '">');
            $(this).parents("tr").find("td:eq(4)").html('' + fdp_total + ' <input type="hidden" class="for_sum_sub_total" name="fdp_total[]" value="' + fdp_total + '">');

            $(this).parents("tr").find(".product-edit").show();
            $(this).parents("tr").find(".product-cancel").remove();
            $(this).parents("tr").find(".product-update").remove();



            // Sending Calculated Data
            var sum = 0;
            $('.for_sum_sub_total').each(function() {
                sum += parseFloat(this.value);
            });

            sendingData(sum);

            var calculate_grand_discount = $(".calculate_grand_discount").val();
            var cal_gra_dis = sum - calculate_grand_discount;

            // Send total sum to this input field
            $("input[name='fdp_sub_total']").attr('value', sum);
            $("input[name='fdp_grand_total']").attr('value', cal_gra_dis);

        });



        /* ********************* For Edit Page ********************* */
        $("body").on("click", ".dynamic-product-edit", function() {
            // Auto Generated ID
            var auto_generate_id = parseInt($("#auto_generate_id_edit").val());
            $("#auto_generate_id_edit").val(auto_generate_id);

            var fdp_fd_id_dynamic = "fdp_fd_id" + `${auto_generate_id}`;

            var fdp_fd_id = $(this).parents("tr").attr('data-fdp-fd-id');
            var fdp_unit_price = $(this).parents("tr").attr('data-fdp-unit-price');
            var fdp_quantity = $(this).parents("tr").attr('data-fdp-quantity');
            var fdp_discount = $(this).parents("tr").attr('data-fdp-discount');
            var fdp_total = $(this).parents("tr").attr('data-fdp-total');

            $(this).parents("tr").find("td:eq(0)").html(`<div class="form-group col-sm-2" style="width: 100%">
                                                    <select name="fdp_fd_id[]" id="${fdp_fd_id_dynamic}" class="form-control selectedValueEdit" data-id='${fdp_fd_id}' placeholder="" style="width: 100%;" required>
                                                        <option value=""><?= lang('please_select_food'); ?></option>
                                                        <?php if (!empty($foods)) foreach ($foods as $fds) { ?>
                                                            <option value="<?php echo $fds->fds_id; ?>"> <?php echo $fds->fds_food_title; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>`);
            $(this).parents("tr").find("td:eq(1)").html('<input type="number" class="form-control new_fdp_unit_price" name="fdp_unit_price[]" value="' + fdp_unit_price + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(2)").html('<input type="number" class="form-control new_fdp_quantity" name="fdp_quantity[]" value="' + fdp_quantity + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(3)").html('<input type="number" class="form-control new_fdp_discount" name="fdp_discount[]" value="' + fdp_discount + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(4)").html('<input class="form-control new_fdp_total" name="fdp_total[]" value="' + fdp_total + '" style="width: 100%" readonly>');

            $(".selectedValueEdit").each(function() {
                $(this).val($(this).attr("data-id"));
            });

            // Row Wise Calculation
            $(document).on('keyup change paste', function() {
                var unit_price = $(".new_fdp_unit_price").val();
                var quantity = $(".new_fdp_quantity").val();
                var discount = $(".new_fdp_discount").val();

                var c_total = unit_price * quantity;
                var row_wise_total = c_total - discount;
                $(".new_fdp_total").val(row_wise_total);
            });


            $(this).parents("tr").find("td:eq(5)").prepend("<button type='button' class='button button-info dynamic-product-update1' style='width: auto;'><?= lang('update'); ?></button> ");
            $(this).hide();

        });


        $("body").on("click", ".dynamic-product-update1", function() {
            // Auto Generate ID
            var auto_generate_id = parseInt($("#auto_generate_id_edit").val());
            $("#auto_generate_id_edit").val(auto_generate_id);

            var fdp_fd_id = $(this).parents("tr").find("#fdp_fd_id" + auto_generate_id + " option:selected").val();
            var fdp_fd_id_text = $(this).parents("tr").find("#fdp_fd_id" + auto_generate_id + " option:selected").text();


            var fdp_unit_price = $(".new_fdp_unit_price").val();
            var fdp_quantity = $(".new_fdp_quantity").val();
            var fdp_discount = $(".new_fdp_discount").val();
            var fdp_total = $(".new_fdp_total").val();

            $(this).parents("tr").find("td:eq(0)").html('' + fdp_fd_id_text + ' <input type="hidden" name="fdp_fd_id[]" value="' + fdp_fd_id + '">');
            $(this).parents("tr").find("td:eq(1)").html('' + fdp_unit_price + ' <input type="hidden" name="fdp_unit_price[]" value="' + fdp_unit_price + '">');
            $(this).parents("tr").find("td:eq(2)").html('' + fdp_quantity + ' <input type="hidden" name="fdp_quantity[]" value="' + fdp_quantity + '">');
            $(this).parents("tr").find("td:eq(3)").html('' + fdp_discount + ' <input type="hidden" name="fdp_discount[]" value="' + fdp_discount + '">');
            $(this).parents("tr").find("td:eq(4)").html('' + fdp_total + ' <input type="hidden" class="for_sum_sub_total" name="fdp_total[]" value="' + fdp_total + '">');

            $(this).parents("tr").find(".dynamic-product-edit").show();
            $(this).parents("tr").find(".dynamic-product-update1").remove();

            // Sending Calculated Data
            var sum = 0;
            $('.for_sum_sub_total').each(function() {
                sum += parseFloat(this.value);
            });

            console.log(sendingData(sum));

            var calculate_grand_discount = $(".calculate_grand_discount").val();
            var cal_gra_dis = sum - calculate_grand_discount;

            // Send total sum to this input field
            $("input[name='fdp_sub_total']").attr('value', sum);
            $("input[name='fdp_grand_total']").attr('value', cal_gra_dis);

        });









    });
</script>



<!-- For Payment -->
<script>
    $(document).ready(function() {
        // open close check field
        $('#paid_by').on('change', function() {
            if ($(this).val() === "cheque") {
                $("#cheque_no").delay(100).fadeIn(100);
                $("#check_number").prop("required", true)
            } else {
                $("#cheque_no").hide();
                $("#check_number").prop("required", false)
            }
        });

        // Show and hide payment module 
        $('#paymentCheckBox').click(function() {
            if ($(this).is(':checked')) {
                $("#modal_body").delay(100).fadeIn(100);
                $("#payableAmount").prop("required", true);
                $("#paid_by").prop("required", true);

                // Disable False When Show 
                $("#payableAmount").prop("disabled", false);
                $("#paid_by").prop("disabled", false);
                $("#check_number").prop("disabled", false);
                $("#reference").prop("disabled", false);
                $("#description").prop("disabled", false);

                // payment condition
                $("#payableAmount").on('keyup change paste', function() {
                    var subAmount = $("#sub_total").val();
                    var subTotalAmount = 0;
                    if (!subAmount || subAmount == undefined || subAmount == "" || subAmount.length == 0) {
                        var subTotalAmount = 0;
                    } else {
                        var subTotalAmount = $("#sub_total").val();
                    }
                    var paidAmount = $(this).val();
                    if (parseFloat(paidAmount) > parseFloat(subTotalAmount)) {
                        Swal.fire({
                            icon: "warning",
                            title: "<?= lang('warning'); ?>...",
                            text: "<?= lang('please_enter_a_valid_value'); ?>"
                        });
                        $(this).val('');
                    }
                });

            } else {
                $("#modal_body").hide();
                $("#payableAmount").prop("required", false);
                $("#paid_by").prop("required", false);

                // Disable True When hide 
                $("#payableAmount").prop("disabled", true);
                $("#paid_by").prop("disabled", true);
                $("#check_number").prop("disabled", true);
                $("#reference").prop("disabled", true);
                $("#description").prop("disabled", true);
            }

        });

        // Reset form after after reload page for firefox browser
        document.getElementById('editPurchaseForm').reset();

    });
</script>