<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel">
            <header class="panel-heading bg-info">
                <i class="fa fa-plus-circle"></i> <?= lang('update_vaccine_purchase'); ?>
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
                                                <label for="exampleInputEmail1"><?= lang('vaccine_name'); ?><span class="text-danger">*</span> </label>
                                                <select name="vp_vcc_id" id="vp_vcc_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                                    <option value=""><?= lang('please_select_vaccine'); ?> </option>
                                                    <?php if (!empty($vaccines)) foreach ($vaccines as $list) { ?>
                                                        <option value="<?php echo $list->vcc_id; ?>"> <?php echo $list->vcc_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label for="exampleInputEmail1"> <?php echo lang('unit_price'); ?><span class="text-danger">*</span></label>
                                                <input type="text" class="form-control calculate_unit_price input__number" name="vp_unit_price_cal" id="price" value='' placeholder="">
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label for="exampleInputEmail1"><?= lang('quantity'); ?><span class="text-danger">*</span></label>
                                                <input type="text" class="form-control calculate_quantity input__number" name="vp_quantity_cal" id="qty" value='' placeholder="">
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label for="exampleInputEmail1"> <?php echo lang('discount'); ?> </label>
                                                <input type="text" class="form-control calculate_discount input__number" name="vp_discount_cal" id="discount" value='' placeholder="">
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label for="exampleInputEmail1"> <?php echo lang('total'); ?><span class="text-danger">*</span> </label>
                                                <input type="text" class="form-control calculate_total" name="vp_total_cal" id="total" value='' placeholder="" readonly>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" class="button button-primary save-btn form" style="margin: 0 15px 10px 0;"><i class="fas fa-plus-circle"></i> <?= lang('add'); ?></button>
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

                                    <form role="form" action="<?php echo base_url('vaccine/updateVaccinePurchase') ?>" method="post" id="editPurchaseForm" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="row row-top-margin">
                                                    <div class="col-md-12">
                                                        <table class="table table-bordered data-table">
                                                            <thead>
                                                                <th><?= lang('vaccine_name'); ?> </th>
                                                                <th><?= lang('unit_price'); ?></th>
                                                                <th><?= lang('quantity'); ?></th>
                                                                <th><?= lang('discount'); ?></th>
                                                                <th><?= lang('total'); ?> </th>
                                                                <th width="200px"><?= lang('actions'); ?> </th>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                foreach ($vaccinePurchaseValueBySummaryById as $summary_id) {
                                                                ?>
                                                                    <tr class="session_data_store_class" data-vp-vcc-id="<?= $summary_id->vpv_vcc_id ?>" data-vp-unit-price="<?= $summary_id->vpv_unit_price ?>" data-vp-quantity="<?= $summary_id->vpv_quantity ?>" data-vp-discount="<?= $summary_id->vpv_discount ?>" data-vp-total="<?= $summary_id->vpv_total ?>">
                                                                        <td> <?= $this->vaccine_model->getVaccineById($summary_id->vpv_vcc_id)->vcc_name; ?><input type="hidden" name="vp_vcc_id[]" value="<?= $summary_id->vpv_vcc_id ?>"></td>
                                                                        <td> <?= $summary_id->vpv_unit_price ?><input type="hidden" name="vp_unit_price[]" value="<?= $summary_id->vpv_unit_price ?>"></td>
                                                                        <td> <?= $summary_id->vpv_quantity ?><input type="hidden" name="vp_quantity[]" value="<?= $summary_id->vpv_quantity ?>"></td>
                                                                        <td> <?= $summary_id->vpv_discount ?><input type="hidden" name="vp_discount[]" value="<?= $summary_id->vpv_discount ?>"></td>
                                                                        <td> <?= $summary_id->vpv_total ?><input type="hidden" class="for_sum_sub_total" name="vp_total[]" value="<?= $summary_id->vpv_total ?>"></td>
                                                                        <td>
                                                                            <button type="button" class='button button-warning dynamic-product-edit display_hide'><i class="fas fa-edit"></i></button>
                                                                            <button type="button" class='button button-danger btn-delete' data-vp_vcc_id="<?= $summary_id->vpv_vcc_id ?>"><i class="fas fa-trash"></i></button>
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
                                                    <label for="exampleInputEmail1"> <?php echo lang('supplier'); ?><span class="text-danger">*</span> </label>
                                                    <select class="form-control js-example-basic-single" name="vp_s_id" value='' required>
                                                        <option value=""><?= lang('please_select_supplier'); ?> </option>
                                                        <?php foreach ($suppliers as $supplier) { ?>
                                                            <option value="<?php echo $supplier->s_id; ?>" <?php if ($vaccinePurchaseById) {
                                                                                                                if ($vaccinePurchaseById->vps_s_id == $supplier->s_id) {
                                                                                                                    echo "selected";
                                                                                                                }
                                                                                                            } ?>> <?php echo $supplier->s_name; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo lang('date'); ?><span class="text-danger">*</span> </label>
                                                    <input type="text" class="form-control datepicker" name="vp_date" id="" value='<?= date("$settings->date_format", strtotime($vaccinePurchaseById->vps_date)); ?>' placeholder="" autocomplete="off" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo lang('sub_total'); ?><span class="text-danger">*</span> </label>
                                                    <input type="text" class="form-control calculate_sub_total readonly" name="vp_sub_total" id="sub_total" value='<?= $vaccinePurchaseById->vps_sub_total; ?>' placeholder="" required>
                                                </div>

                                                <div class="form-group display_hide">
                                                    <label for="exampleInputEmail1"> <?php echo lang('discount'); ?> </label>
                                                    <input type="text" class="form-control calculate_grand_discount input__number" name="vp_grand_discount" id="discount" value='<?= $vaccinePurchaseById->vps_grand_discount; ?>' placeholder="">
                                                </div>
                                                <div class="form-group display_hide">
                                                    <label for="exampleInputEmail1"> <?php echo lang('amount_payable'); ?><span class="text-danger">*</span> </label>
                                                    <input type="text" class="form-control calculate_grand_total readonly" name="vp_grand_total" id="grand_total" value='<?= $vaccinePurchaseById->vps_grand_total; ?>' placeholder="" required>
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
                                                        <textarea class="form-control" name="vp_description" value="" rows="5" cols="10" style="height: auto !important;"><?= $vaccinePurchaseById->vps_description; ?> </textarea>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="vps_id" value='<?= $vaccinePurchaseById->vps_id; ?>'>
                                                <div class="form-group">
                                                    <button type="submit" name="submit" class="button button-info text-right" style="margin-top: 35px; width: 100%; padding: 10px 0 10px 0;"> <?= lang('edit_button'); ?> </button>
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
            let vp_vcc_id = $(this).data("vp-vcc-id");
            sessionStorage.setItem(vp_vcc_id, vp_vcc_id);
        });

        // Calculate Data
        $(document).on('keyup change paste', function() {
            var getUnitPrice = $("input[name='vp_unit_price_cal']").val();
            var getQuantity = $("input[name='vp_quantity_cal']").val();
            var getDiscount = $("input[name='vp_discount_cal']").val();
            var calculateUnitPriceIntoQuantity = getUnitPrice * getQuantity;
            var getTotal = calculateUnitPriceIntoQuantity - getDiscount;
            $("input[name='vp_total_cal']").val(getTotal);
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

        // Check vaccine if already added to invoice
        $("#vp_vcc_id").change(function() {

            var vp_vcc_id = $("#vp_vcc_id option:selected").val();

            if (sessionStorage.getItem(vp_vcc_id)) {
                if (sessionStorage.getItem(vp_vcc_id) == vp_vcc_id) {
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('you_have_already_added_this_item'); ?>"
                    });
                    $("select#vp_vcc_id")[0].selectedIndex = 0;
                    $('#vp_vcc_id').trigger('change');
                }
            }
        });

        // Create New Row With Entered Data
        $(".form").click(function(e) {
            e.preventDefault();

            // Auto Generate ID
            var auto_generate_id = parseInt($("#auto_generate_id").val()) + 1;
            $("#auto_generate_id").val(auto_generate_id);
            var vp_vcc_id1 = "vp_vcc_id" + `${auto_generate_id}`;
            var vp_lst_id1 = "livestock_type" + `${auto_generate_id}`;

            var vp_vcc_id = $("#vp_vcc_id option:selected").val();
            var vp_vcc_text = $("#vp_vcc_id option:selected").text();

            var vp_lst_id = $("#livestock_type option:selected").val();
            var vp_lst_text = $("#livestock_type option:selected").text();

            var vp_unit_price = $("input[name='vp_unit_price_cal']").val();
            var vp_quantity = $("input[name='vp_quantity_cal']").val();
            var vp_discount = $("input[name='vp_discount_cal']").val();
            var vp_total = $("input[name='vp_total_cal']").val();

            var priceIntoQuantity = vp_unit_price * vp_quantity;
            var newTotal = priceIntoQuantity - vp_discount;

            if (vp_vcc_id == "" || vp_vcc_id == null) {
                alert('Please Select Livestock.');
            } else if (vp_unit_price == "" || vp_unit_price == null) {
                document.getElementById("price").placeholder = "Please fill this.";
                document.getElementById("price").style.borderColor = 'red';
            } else if (vp_quantity == "" || vp_quantity == null) {
                document.getElementById("qty").placeholder = "Please fill this.";
                document.getElementById("qty").style.borderColor = 'red';
            } else {
                $(".data-table tbody").append("<tr data-" + vp_vcc_id1 + "='" + vp_vcc_id + "' data-vp_unit_price='" + vp_unit_price + "' data-vp_quantity='" + vp_quantity + "' data-vp_discount='" + vp_discount + "' data-vp_total='" + vp_total + "'><td>" + vp_vcc_text + " <input type='hidden' name='vp_vcc_id[]' value='" + vp_vcc_id + "'></td><td>" + vp_unit_price + "<input type='hidden' name='vp_unit_price[]' value=" + vp_unit_price + "></td><td>" + vp_quantity + "<input type='hidden' name='vp_quantity[]' value='" + vp_quantity + "'></td> <td>" + vp_discount + "<input type='hidden' name='vp_discount[]' value='" + vp_discount + "'></td><td>" + newTotal + "<input type='hidden' class='for_sum_sub_total' name='vp_total[]' value='" + newTotal + "'></td><td width='200px'><button class='button button-warning product-edit display_hide'><i class='fas fa-edit'></i></button><button class='button button-danger btn-delete' data-vp_vcc_id=" + vp_vcc_id + "><i class='fas fa-trash'></i></button></td></tr>");
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
            $("input[name='vp_sub_total']").attr('value', sum);
            $("input[name='vp_grand_total']").attr('value', cal_gra_dis);



            $("input[name='vp_unit_price_cal']").val('');
            $("input[name='vp_quantity_cal']").val('');
            $("input[name='vp_discount_cal']").val('');
            $("input[name='vp_total_cal']").val('');

            // Select 1st index in dropdown
            $("select#vp_vcc_id")[0].selectedIndex = 0;
            $('#vp_vcc_id').trigger('change');

            // Store vaccine id in session 
            sessionStorage.setItem(vp_vcc_id, vp_vcc_id);

        });

        // Calculate grand total to minus grand discount
        function sendingData(sum) {
            $(".calculate_grand_discount").keyup(function() {

                var cgd = $(".calculate_grand_discount").val();

                $("input[name='vp_sub_total']").attr('value', sum);

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
            $("input[name='vp_sub_total']").attr('value', sum);
            $("input[name='vp_grand_total']").attr('value', cal_gra_dis);

            // Empty Subtotal for that user can't submit
            var valueSubTotal = $("input[name='vp_sub_total']").val();
            if (valueSubTotal == 0) {
                $("input[name='vp_sub_total']").val('');
            }


            // Get vp_vcc_id id from delete button and remove from session
            let vp_vcc_id = $(this).data("vp_vcc_id");
            sessionStorage.removeItem(vp_vcc_id);

        });

        // Update Row Data
        $("body").on("click", ".product-edit", function() {
            $(".product-edit").prop("disabled", true);
            $(".dynamic-product-edit").prop("disabled", true);
            // Auto Generated ID
            var auto_generate_id = parseInt($("#auto_generate_id").val());
            $("#auto_generate_id").val(auto_generate_id);

            var vp_vcc_id_dynamic = "vp_vcc_id" + `${auto_generate_id}`;

            var vp_vcc_id = $(this).parents("tr").attr('data-' + vp_vcc_id_dynamic);
            var vp_unit_price = $(this).parents("tr").attr('data-vp_unit_price');
            var vp_quantity = $(this).parents("tr").attr('data-vp_quantity');
            var vp_discount = $(this).parents("tr").attr('data-vp_discount');
            var vp_total = $(this).parents("tr").attr('data-vp_total');

            $(this).parents("tr").find("td:eq(0)").html(`<div class="form-group col-sm-2" style="width: 100%">
                                                    <select name="vp_vcc_id" id="${vp_vcc_id_dynamic}" class="form-control selectedValue" data-id='${vp_vcc_id}' placeholder="" style="width: 100%;" required>
                                                        <option value=""><?= lang('please_select_vaccine'); ?> </option>
                                                        <?php if (!empty($vaccines)) foreach ($vaccines as $list1) { ?>
                                                        <option value="<?php echo $list1->vcc_id; ?>"> <?php echo $list1->vcc_name; ?></option>
                                                    <?php } ?>
                                                    </select>
                                                </div>`);

            $(this).parents("tr").find("td:eq(1)").html('<input type="number" class="form-control new_vp_unit_price input__number" name="vp_unit_price" value="' + vp_unit_price + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(2)").html('<input type="number" class="form-control new_vp_quantity input__number" name="vp_quantity" value="' + vp_quantity + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(3)").html('<input type="number" class="form-control new_vp_discount input__number" name="vp_discount" value="' + vp_discount + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(4)").html('<input class="form-control new_vp_total" name="vp_total" value="' + vp_total + '" style="width: 100%" readonly>');

            $(".selectedValue").each(function() {
                $(this).val($(this).attr("data-id"));
            });

            // Row Wise Calculation
            $(document).on('keyup change paste', function() {
                var unit_price = $(".new_vp_unit_price").val();
                var quantity = $(".new_vp_quantity").val();
                var discount = $(".new_vp_discount").val();

                var c_total = unit_price * quantity;
                var row_wise_total = c_total - discount;
                $(".new_vp_total").val(row_wise_total);
            });


            $(this).parents("tr").find("td:eq(5)").prepend("<button class='btn btn-info btn-xs product-update' style='width: auto;'><?= lang('update'); ?> </button>");
            $(this).hide();


        });

        // Update Row Data
        $("body").on("click", ".product-update", function() {
            $(".product-edit").prop("disabled", false);
            $(".dynamic-product-edit").prop("disabled", false);
            // Auto Generate ID
            var auto_generate_id = parseInt($("#auto_generate_id").val());
            $("#auto_generate_id").val(auto_generate_id);

            var vp_vcc_id = $(this).parents("tr").find("#vp_vcc_id" + auto_generate_id + " option:selected").val();
            var vp_vcc_id_text = $(this).parents("tr").find("#vp_vcc_id" + auto_generate_id + " option:selected").text();

            var vp_unit_price = $(this).parents("tr").find("input[name='vp_unit_price']").val();
            var vp_quantity = $(this).parents("tr").find("input[name='vp_quantity']").val();
            var vp_discount = $(this).parents("tr").find("input[name='vp_discount']").val();
            var vp_total = $(this).parents("tr").find("input[name='vp_total']").val();



            $(this).parents("tr").find("td:eq(0)").html('' + vp_vcc_id_text + ' <input type="hidden" name="vp_vcc_id[]" value="' + vp_vcc_id + '">');
            $(this).parents("tr").find("td:eq(1)").html('' + vp_unit_price + ' <input type="hidden" name="vp_unit_price[]" value="' + vp_unit_price + '">');
            $(this).parents("tr").find("td:eq(2)").html('' + vp_quantity + ' <input type="hidden" name="vp_quantity[]" value="' + vp_quantity + '">');
            $(this).parents("tr").find("td:eq(3)").html('' + vp_discount + ' <input type="hidden" name="vp_discount[]" value="' + vp_discount + '">');
            $(this).parents("tr").find("td:eq(4)").html('' + vp_total + ' <input type="hidden" class="for_sum_sub_total" name="vp_total[]" value="' + vp_total + '">');

            $(this).parents("tr").find(".product-edit").show();
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
            $("input[name='vp_sub_total']").attr('value', sum);
            $("input[name='vp_grand_total']").attr('value', cal_gra_dis);

        });


        /* ********************* For Edit Page ********************* */
        // Update Row Data
        $("body").on("click", ".dynamic-product-edit", function() {
            $(".dynamic-product-edit").prop("disabled", true);
            $(".product-edit").prop("disabled", true);
            // Auto Generated ID
            var auto_generate_id = parseInt($("#auto_generate_id_edit").val());
            $("#auto_generate_id_edit").val(auto_generate_id);

            var vp_vcc_id_dynamic = "vp_vcc_id" + `${auto_generate_id}`;

            var vp_vcc_id = $(this).parents("tr").attr('data-vp-vcc-id');
            var vp_unit_price = $(this).parents("tr").attr('data-vp-unit-price');
            var vp_quantity = $(this).parents("tr").attr('data-vp-quantity');
            var vp_discount = $(this).parents("tr").attr('data-vp-discount');
            var vp_total = $(this).parents("tr").attr('data-vp-total');

            $(this).parents("tr").find("td:eq(0)").html(`<div class="form-group col-sm-2" style="width: 100%">
                                                    <select name="vp_vcc_id[]" id="${vp_vcc_id_dynamic}" class="form-control selectedValueEdit" data-id='${vp_vcc_id}' placeholder="" style="width: 100%;" required>
                                                        <option value=""><?= lang('please_select_vaccine'); ?> </option>
                                                        <?php if (!empty($vaccines)) foreach ($vaccines as $list2) { ?>
                                                            <option value="<?php echo $list2->vcc_id; ?>"> <?php echo $list2->vcc_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>`);
            $(this).parents("tr").find("td:eq(1)").html('<input type="number" class="form-control new_vp_unit_price" name="vp_unit_price[]" value="' + vp_unit_price + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(2)").html('<input type="number" class="form-control new_vp_quantity" name="vp_quantity[]" value="' + vp_quantity + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(3)").html('<input type="number" class="form-control new_vp_discount" name="vp_discount[]" value="' + vp_discount + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(4)").html('<input class="form-control new_vp_total" name="vp_total[]" value="' + vp_total + '" style="width: 100%" readonly>');

            $(".selectedValueEdit").each(function() {
                $(this).val($(this).attr("data-id"));
            });

            // Row Wise Calculation
            $(document).on('keyup change paste', function() {
                var unit_price = $(".new_vp_unit_price").val();
                var quantity = $(".new_vp_quantity").val();
                var discount = $(".new_vp_discount").val();

                var c_total = unit_price * quantity;
                var row_wise_total = c_total - discount;
                $(".new_vp_total").val(row_wise_total);
            });


            $(this).parents("tr").find("td:eq(5)").prepend("<button type='button' class='btn btn-info btn-xs dynamic-product-update1' style='width: auto;'><?= lang('update'); ?> </button> ");
            $(this).hide();

        });


        $("body").on("click", ".dynamic-product-update1", function() {
            $(".dynamic-product-edit").prop("disabled", false);
            $(".product-edit").prop("disabled", false);
            // Auto Generate ID
            var auto_generate_id = parseInt($("#auto_generate_id_edit").val());
            $("#auto_generate_id_edit").val(auto_generate_id);

            var vp_vcc_id = $(this).parents("tr").find("#vp_vcc_id" + auto_generate_id + " option:selected").val();
            var vp_vcc_id_text = $(this).parents("tr").find("#vp_vcc_id" + auto_generate_id + " option:selected").text();


            var vp_unit_price = $(".new_vp_unit_price").val();
            var vp_quantity = $(".new_vp_quantity").val();
            var vp_discount = $(".new_vp_discount").val();
            var vp_total = $(".new_vp_total").val();

            $(this).parents("tr").find("td:eq(0)").html('' + vp_vcc_id_text + ' <input type="hidden" name="vp_vcc_id[]" value="' + vp_vcc_id + '">');
            $(this).parents("tr").find("td:eq(1)").html('' + vp_unit_price + ' <input type="hidden" name="vp_unit_price[]" value="' + vp_unit_price + '">');
            $(this).parents("tr").find("td:eq(2)").html('' + vp_quantity + ' <input type="hidden" name="vp_quantity[]" value="' + vp_quantity + '">');
            $(this).parents("tr").find("td:eq(3)").html('' + vp_discount + ' <input type="hidden" name="vp_discount[]" value="' + vp_discount + '">');
            $(this).parents("tr").find("td:eq(4)").html('' + vp_total + ' <input type="hidden" class="for_sum_sub_total" name="vp_total[]" value="' + vp_total + '">');

            $(this).parents("tr").find(".dynamic-product-edit").show();
            $(this).parents("tr").find(".dynamic-product-update1").remove();

            // Sending Calculated Data
            var sum = 0;
            $('.for_sum_sub_total').each(function() {
                sum += parseFloat(this.value);
            });

            var calculate_grand_discount = $(".calculate_grand_discount").val();
            var cal_gra_dis = sum - calculate_grand_discount;

            // Send total sum to this input field
            $("input[name='vp_sub_total']").attr('value', sum);
            $("input[name='vp_grand_total']").attr('value', cal_gra_dis);

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