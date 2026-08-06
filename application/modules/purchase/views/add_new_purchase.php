<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel">
            <header class="panel-heading bg-info">
                <i class="fas fa-plus-circle"></i> <?php echo lang('add_new_purchase'); ?>
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
                                    <?php if ($this->session->flashdata('error')): ?>
                                        <div class="alert alert-danger noprint" role="alert">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <?php echo html_escape($this->session->flashdata('error')); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($this->session->flashdata('success')): ?>
                                        <div class="alert alert-success noprint" role="alert">
                                            <i class="fas fa-check-circle"></i>
                                            <?php echo html_escape($this->session->flashdata('success')); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php echo validation_errors(); ?>
                                    <div class="row">
                                        <!-- col-md-9 -->
                                        <div class="col-md-9">
                                            <!-- <form> -->
                                            <input type="hidden" value="0" id="auto_generate_id">
                                            <div class="form-group col-sm-2">
                                                <label for="exampleInputEmail1"><?php echo lang('ls_name_variant_modal'); ?><span class="text-danger">*</span></label>
                                                <select name="pur_ls_id" id="pur_ls_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                                    <option value=""><?php echo lang('please_select_livestock'); ?></option>
                                                    <?php if ($livestocks) {
                                                        foreach ($livestocks as $livestock) { ?>
                                                            <option value="<?php echo $livestock->ls_id; ?>"> <?php echo $livestock->ls_name; ?></option>
                                                    <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label for="exampleInputEmail1"><?php echo lang('livestock_variant'); ?></label>
                                                <select name="pur_lst_id" class="form-control js-example-basic-single" id="livestock_type" placeholder="" style="width: 100%;" required>
                                                    <option value=""><?php echo lang('please_select_variant'); ?></option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label for="exampleInputEmail1"> <?php echo lang('unit_price'); ?><span class="text-danger">*</span></label>
                                                <input type="text" class="form-control calculate_unit_price input__number" name="pur_unit_price_cal" id="price" value='' placeholder="">
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label for="exampleInputEmail1"> <?php echo lang('quantity'); ?><span class="text-danger">*</span></label>
                                                <input type="number" class="form-control calculate_quantity input__number" name="pur_quantity_cal" id="qty" value='' placeholder="">
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label for="exampleInputEmail1"> <?php echo lang('discount'); ?></label>
                                                <input type="text" class="form-control calculate_discount input__number" name="pur_discount_cal" id="discount" value='' placeholder="">
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label for="exampleInputEmail1"> <?php echo lang('total'); ?><span class="text-danger">*</span></label>
                                                <input type="text" class="form-control calculate_total" name="pur_total_cal" id="total" value='' placeholder="" readonly>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" class="button button-primary save-btn form" style="margin: 0 15px 10px 0;"><i class="fas fa-plus-circle"></i> <?= lang('add'); ?></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                        </div>
                                    </div>

                                    <form role="form" action="<?php echo base_url('') ?>purchase/insertPurchase" method="post" id="formId" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="row row-top-margin">
                                                    <div class="col-md-12">
                                                        <table class="table table-bordered data-table">
                                                            <thead class="table_heading_color">
                                                                <th><?php echo lang('livestock_name'); ?></th>
                                                                <th><?php echo lang('variant_name'); ?></th>
                                                                <th><?php echo lang('unit_price'); ?></th>
                                                                <th><?php echo lang('quantity'); ?></th>
                                                                <th><?php echo lang('discount'); ?></th>
                                                                <th><?php echo lang('total'); ?></th>
                                                                <th width="200px"><?php echo lang('actions'); ?></th>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="hidden" value="1" id="legal_sum">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo lang('supplier'); ?> </label><span class="text-danger">*</span>
                                                    <select class="form-control js-example-basic-single" name="pur_supp_id" value='' required>
                                                        <option value=""><?php echo lang('please_select_supplier'); ?></option>
                                                        <?php foreach ($suppliers as $supplier) { ?>
                                                            <option value="<?php echo $supplier->s_id; ?>"> <?php echo $supplier->s_name; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo lang('purchase_date'); ?><span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control datepicker" name="pur_date" id="" value='<?= get_current_date(); ?>' placeholder="" autocomplete="off" required>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-sm-6">
                                                        <label for="exampleInputEmail1"> <?php echo lang('sub_total'); ?><span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control calculate_sub_total readonly" name="pur_sub_total" id="sub_total" value='' placeholder="" required>
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label for="exampleInputEmail1"> <?php echo lang('purchase_bill_no'); ?>.<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control readonly" name="pur_bill_no" value='<?php
                                                                                                                                    if (!empty($this->purchase_model->getLastPurchaseBillNo())) {
                                                                                                                                        $lastBillNo = $this->purchase_model->getLastPurchaseBillNo();
                                                                                                                                    } else {
                                                                                                                                        $lastBillNo = 0;
                                                                                                                                    }
                                                                                                                                    echo $lastBillNo + 1;
                                                                                                                                    ?>' placeholder="" required>
                                                    </div>
                                                </div>


                                                <div class="form-group display_hide">
                                                    <label for="exampleInputEmail1"> <?php echo lang('discount'); ?> </label>
                                                    <input type="text" class="form-control calculate_grand_discount input__number" name="pur_grand_discount" id="" value='' placeholder="">
                                                </div>
                                                <div class="form-group display_hide">
                                                    <label for="exampleInputEmail1"> <?php echo lang('amount_payable'); ?><span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control calculate_grand_total readonly" name="pur_grand_total" id="grand_total" value='' placeholder="" required>
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
                                                    <label class="control-label col-md-3 note"><?php echo lang('note'); ?></label>
                                                    <div class="col-md-12 note">
                                                        <textarea class="form-control" name="pur_note" value="" rows="5" cols="10" style="height: auto !important;"> </textarea>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="pur_id" value=''>
                                                <div class="form-group">
                                                    <button type="submit" name="submit" class="button button-info text-right" style="margin-top: 35px; width: 100%; padding: 10px 0 10px 0;"> <?php echo lang('submit'); ?></button>
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

<input type="hidden" id="supplierQuantityAlert" value="<?php echo $this->report_model->getCountRow('supplier', 's_id', ['s_status' => 1]); ?>">
<input type="hidden" id="livestockQuantityAlert" value="<?php echo $this->report_model->getCountRow('livestock', 'ls_id', ['ls_status' => 1]); ?>">
<input type="hidden" id="variantQuantityAlert" value="<?php echo $this->report_model->getCountRow('livestock_type', 'lst_id', ['lst_status' => 1]); ?>">

<!-- page end-->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // Clear session
        sessionStorage.clear();

        // Calculate Data
        $(document).on('keyup change paste', function() {
            var getUnitPrice = $("input[name='pur_unit_price_cal']").val();
            var getQuantity = $("input[name='pur_quantity_cal']").val();
            var getDiscount = $("input[name='pur_discount_cal']").val();
            var calculateUnitPriceIntoQuantity = getUnitPrice * getQuantity;
            var getTotal = calculateUnitPriceIntoQuantity - getDiscount;
            $("input[name='pur_total_cal']").val(getTotal);
        });

        // On change Livestock dropdown all empty
        $("#pur_ls_id").change(function() {
            $("input[name='pur_unit_price_cal']").val('');
            $("input[name='pur_quantity_cal']").val('');
            $("input[name='pur_discount_cal']").val('');
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


        // Check livestock & type if already added to invoice
        $("#livestock_type").change(function() {

            var pur_ls_id = $("#pur_ls_id option:selected").val();
            var pur_lst_id = $("#livestock_type option:selected").val();

            if (sessionStorage.getItem(pur_ls_id + "_" + pur_lst_id)) {
                if (sessionStorage.getItem(pur_ls_id + "_" + pur_lst_id) == pur_lst_id) {
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('you_have_already_added_this_item'); ?>"
                    });
                    $("select#livestock_type")[0].selectedIndex = 0;
                    $('#livestock_type').trigger('change');
                }
            }
        });


        // Create New Row With Entered Data
        $(".form").click(function(e) {
            e.preventDefault();

            // Auto Generate ID
            var auto_generate_id = parseInt($("#auto_generate_id").val()) + 1;
            $("#auto_generate_id").val(auto_generate_id);
            var pur_ls_id1 = "pur_ls_id" + `${auto_generate_id}`;
            var pur_lst_id1 = "livestock_type" + `${auto_generate_id}`;

            var pur_ls_id = $("#pur_ls_id option:selected").val();
            var pur_ls_text = $("#pur_ls_id option:selected").text();

            var pur_lst_id = $("#livestock_type option:selected").val();
            var pur_lst_text = $("#livestock_type option:selected").text();

            var pur_unit_price = $("input[name='pur_unit_price_cal']").val();
            var pur_quantity = $("input[name='pur_quantity_cal']").val();
            var pur_discount = $("input[name='pur_discount_cal']").val();
            var pur_total = $("input[name='pur_total_cal']").val();

            var priceIntoQuantity = pur_unit_price * pur_quantity;
            var newTotal = priceIntoQuantity - pur_discount;



            if (pur_ls_id == "") {
                alert('Please Select Livestock.');
            } else if (pur_lst_id == "") {
                alert('Please Select Variant.');
            } else if (pur_unit_price == "") {
                document.getElementById("price").placeholder = "Enter Unit Price";
            } else if (pur_quantity == "") {
                document.getElementById("qty").placeholder = "Enter Quantity";
            } else {
                $(".data-table tbody").append("<tr data-" + pur_ls_id1 + "='" + pur_ls_id + "' data-" + pur_lst_id1 + "='" + pur_lst_id + "' data-pur_unit_price='" + pur_unit_price + "' data-pur_quantity='" + pur_quantity + "' data-pur_discount='" + pur_discount + "' data-pur_total='" + pur_total + "'><td>" + pur_ls_text + " <input type='hidden' name='pur_ls_id[]' value='" + pur_ls_id + "'></td><td>" + pur_lst_text + "<input type='hidden' name='pur_lst_id[]' value='" + pur_lst_id + "'></td> <td>" + pur_unit_price + "<input type='hidden' name='pur_unit_price[]' value=" + pur_unit_price + "></td><td>" + pur_quantity + "<input type='hidden' name='pur_quantity[]' value='" + pur_quantity + "'></td> <td>" + pur_discount + "<input type='hidden' name='pur_discount[]' value='" + pur_discount + "'></td><td>" + newTotal + "<input type='hidden' class='for_sum_sub_total' name='pur_total[]' value='" + newTotal + "'></td><td width='200px'><button class='button button-warning product-edit display_hide'><i class='fas fa-edit'></i></button><button class='button button-danger btn-delete' data-pur_ls_id=" + pur_ls_id + " data-pur_lst_id=" + pur_lst_id + "><i class='fas fa-trash'></i></button></td></tr>");

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
            $("input[name='pur_sub_total']").attr('value', sum);
            $("input[name='pur_grand_total']").attr('value', cal_gra_dis);

            $("input[name='pur_unit_price_cal']").val('');
            $("input[name='pur_quantity_cal']").val('');
            $("input[name='pur_discount_cal']").val('');
            $("input[name='pur_total_cal']").val('');

            // Empty type & select 0 index
            $("#livestock_type").empty();
            $("select#pur_ls_id")[0].selectedIndex = 0;
            $('#pur_ls_id').trigger('change');

            // Store livestock & type id in session 
            sessionStorage.setItem(pur_ls_id + "_" + pur_lst_id, pur_lst_id);


        });

        // Calculate grand total to minus grand discount
        function sendingData(sum) {
            $(".calculate_grand_discount").keyup(function() {
                var cgd = $(".calculate_grand_discount").val();
                $("input[name='pur_sub_total']").attr('value', sum);
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
            $("input[name='pur_sub_total']").attr('value', sum);
            $("input[name='pur_grand_total']").attr('value', cal_gra_dis);

            // Empty Subtotal and Grand Total for that user can't submit
            var valueSubTotal = $("input[name='pur_sub_total']").val();
            if (valueSubTotal == 0) {
                $("input[name='pur_sub_total']").val('');
            }


            // Get Shed and batch id from delete button
            let pur_ls_id = $(this).data("pur_ls_id");
            let pur_lst_id = $(this).data("pur_lst_id");
            sessionStorage.removeItem(pur_ls_id + "_" + pur_lst_id);

        });

        // Update Row Data
        $("body").on("click", ".product-edit", function() {
            // Auto Generated ID

            $(".product-edit").prop("disabled", true);
            var auto_generate_id = parseInt($("#auto_generate_id").val());
            $("#auto_generate_id").val(auto_generate_id);

            var pur_ls_id_dynamic = "pur_ls_id" + auto_generate_id;
            var livestock_type_dynamic = "livestock_type" + auto_generate_id;

            var pur_ls_id = $(this).parents("tr").attr('data-' + pur_ls_id_dynamic);
            var pur_lst_id = $(this).parents("tr").attr('data-' + livestock_type_dynamic);
            var livestock_id = $(this).parents("tr").attr('data-pur_ls_id' + auto_generate_id);
            var livestock_type = $(this).parents("tr").attr('data-livestock_type' + auto_generate_id);
            var pur_unit_price = $(this).parents("tr").attr('data-pur_unit_price');
            var pur_quantity = $(this).parents("tr").attr('data-pur_quantity');
            var pur_discount = $(this).parents("tr").attr('data-pur_discount');
            var pur_total = $(this).parents("tr").attr('data-pur_total');

            $(this).parents("tr").find("td:eq(0)").html(`<div class="form-group col-sm-2" style="width: 100%">
                                                    <select name="pur_ls_id" id="${pur_ls_id_dynamic}" class="form-control selectedValue"  data-id='${livestock_id}' placeholder="" style="width: 100%;" required>
                                                        <option value=""><?php echo lang('please_select_livestock'); ?></option>
                                                        <?php if (!empty($livestocks)) foreach ($livestocks as $livestock) { ?>
                                                            <option value="<?php echo $livestock->ls_id; ?>"> <?php echo $livestock->ls_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>`);

            $(this).parents("tr").find("td:eq(1)").html(`<div class="form-group col-sm-2" style="width: 100%">
                                                    <select name="pur_lst_id" class="form-control selectedValue_kutta"  data-id='${livestock_id}' id="${livestock_type_dynamic}" placeholder="" style="width: 100%;" required>
                                                    <option value=""><?php echo lang('please_select_variant'); ?></option>
                                                    </select>
                                                </div>`);
            $(this).parents("tr").find("td:eq(2)").html('<input type="number" class="form-control new_pur_unit_price" name="pur_unit_price" value="' + pur_unit_price + '" style="width: 100%" required>');
            $(this).parents("tr").find("td:eq(3)").html('<input type="number" class="form-control new_pur_quantity" name="pur_quantity" value="' + pur_quantity + '" style="width: 100%" required>');
            $(this).parents("tr").find("td:eq(4)").html('<input type="number" class="form-control new_pur_discount" name="pur_discount" value="' + pur_discount + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(5)").html('<input class="form-control new_pur_total" name="pur_total" value="' + pur_total + '" style="width: 100%" readonly>');

            $(".selectedValue").each(function() {
                $(this).val($(this).attr("data-id"));
            });


            // Cascading Dropdown
            var iid = livestock_id;
            var child_id = livestock_type;
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('purchase/getLivestockTypeCascadingDropdown'); ?>',
                data: {
                    livestock_id: iid,
                    livestock_type_id: child_id,
                },
                success: function(data) {
                    $("#" + livestock_type_dynamic).html(data);
                }
            });



            // Row Wise Calculation
            $(document).on('keyup change paste', function() {
                var unit_price = $(".new_pur_unit_price").val();
                var quantity = $(".new_pur_quantity").val();
                var discount = $(".new_pur_discount").val();

                var c_total = unit_price * quantity;
                var row_wise_total = c_total - discount;
                $(".new_pur_total").val(row_wise_total);
            });


            $(this).parents("tr").find("td:eq(6)").prepend("<button class='button button-info product-update' style='width: auto;'><?= lang('update'); ?></button>");
            $(this).hide();


            // Cascading Dropdown
            $("#" + pur_ls_id_dynamic).on('change', function() {
                var iid = $("#" + pur_ls_id_dynamic).val();
                // if (iid != '') {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('purchase/getLivestockTypeCascadingDropdown'); ?>',
                    data: {
                        livestock_id: iid,
                    },
                    success: function(data) {
                        $("#" + livestock_type_dynamic).html(data);
                    }
                });
                // }
            });
        });

        // Update Row Data
        $("body").on("click", ".product-update", function() {
            $(".product-edit").prop("disabled", false);
            // Auto Generate ID
            var auto_generate_id = parseInt($("#auto_generate_id").val());
            $("#auto_generate_id").val(auto_generate_id);

            var pur_ls_id = $(this).parents("tr").find("#pur_ls_id" + auto_generate_id + " option:selected").val();
            var pur_ls_id_text = $(this).parents("tr").find("#pur_ls_id" + auto_generate_id + " option:selected").text();

            var pur_lst_id = $(this).parents("tr").find("#livestock_type" + auto_generate_id + " option:selected").val();
            var pur_lst_id_text = $(this).parents("tr").find("#livestock_type" + auto_generate_id + " option:selected").text();

            var pur_unit_price = $(this).parents("tr").find("input[name='pur_unit_price']").val();
            var pur_quantity = $(this).parents("tr").find("input[name='pur_quantity']").val();
            var pur_discount = $(this).parents("tr").find("input[name='pur_discount']").val();
            var pur_total = $(this).parents("tr").find("input[name='pur_total']").val();

            $(this).parents("tr").find("td:eq(0)").html('' + pur_ls_id_text + ' <input type="hidden" name="pur_ls_id[]" value="' + pur_ls_id + '" required>');
            $(this).parents("tr").find("td:eq(1)").html('' + pur_lst_id_text + ' <input type="hidden" name="pur_lst_id[]" value="' + pur_lst_id + '" required>');
            $(this).parents("tr").find("td:eq(2)").html('' + pur_unit_price + ' <input type="hidden" name="pur_unit_price[]" value="' + pur_unit_price + '" required>');
            $(this).parents("tr").find("td:eq(3)").html('' + pur_quantity + ' <input type="hidden" name="pur_quantity[]" value="' + pur_quantity + '" required>');
            $(this).parents("tr").find("td:eq(4)").html('' + pur_discount + ' <input type="hidden" name="pur_discount[]" value="' + pur_discount + '">');
            $(this).parents("tr").find("td:eq(5)").html('' + pur_total + ' <input type="hidden" class="for_sum_sub_total" name="pur_total[]" value="' + pur_total + '">');

            $(this).parents("tr").find(".product-edit").show();

            // var get_value_on_update = pur_ls_id;
            $(this).parents("tr").attr('data-pur_ls_id' + auto_generate_id, pur_ls_id);
            $(this).parents("tr").attr('data-livestock_type' + auto_generate_id, pur_lst_id);
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
            $("input[name='pur_sub_total']").attr('value', sum);
            $("input[name='pur_grand_total']").attr('value', cal_gra_dis);

        });

    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        // Cascading Dropdown
        $("#pur_ls_id").on('change', function() {
            var iid = $('#pur_ls_id').val();
            // if (iid != '') {
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('purchase/getLivestockTypeCascadingDropdown'); ?>',
                data: {
                    livestock_id: iid,
                },
                success: function(data) {
                    $('#livestock_type').html(data);
                }
            });
            // }
        });
    });
</script>

<script type="text/javascript">
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

        // Supplier quantity alert
        function supplierQuantityAlert() {
            var supplierQuantityAlert = $("#supplierQuantityAlert").val();

            if (supplierQuantityAlert == 0) {
                toastr.warning('<?= lang('no_supplier_found'); ?>');
            }
        }
        supplierQuantityAlert();

        // livestock quantity alert
        function livestockQuantityAlert() {
            var livestockQuantityAlert = $("#livestockQuantityAlert").val();

            if (livestockQuantityAlert == 0) {
                toastr.warning('<?= lang('no_livestock_found'); ?>');
            }
        }
        livestockQuantityAlert();

        // Supplier quantity alert
        function variantQuantityAlert() {
            var variantQuantityAlert = $("#variantQuantityAlert").val();

            if (variantQuantityAlert == 0) {
                toastr.warning('<?= lang('no_livestock_variant_found'); ?>');
            }
        }
        variantQuantityAlert();

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
        document.getElementById('formId').reset();

    });
</script>