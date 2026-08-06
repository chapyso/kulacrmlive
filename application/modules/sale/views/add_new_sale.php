<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel">
            <header class="panel-heading bg-info">
                <i class="fas fa-plus-circle"></i> <?= lang('add_new_sale'); ?>
            </header>

            <style>
                form {
                    border: 2px solid #ccc;
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
                                            <div class="row">
                                                <!-- <form> -->
                                                <input type="hidden" value="0" id="auto_generate_id">

                                                <div class="form-group col-sm-3">
                                                    <label for="exampleInputEmail1"><?= lang('shed'); ?><span class="text-danger">*</span></label>
                                                    <select name="lss_sh_id" id="lss_shed_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;">
                                                        <option value=""><?= lang('please_select_shed'); ?></option>
                                                        <?php if (!empty($sheds)) foreach ($sheds as $shed) {
                                                        ?>
                                                            <option value="<?php echo $shed->sh_id ?>"><?php echo $shed->sh_no ?>: <?php echo $shed->sh_title ?></option>
                                                        <?php }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label for="exampleInputEmail1"><?= lang('batch'); ?><span class="text-danger">*</span></label>
                                                    <select name="lss_batch_id" id="lss_batch_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                                        <option value=""><?= lang('please_select_batch'); ?></option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label for="exampleInputEmail1"><?php echo lang('ls_name_variant_modal'); ?><span class="text-danger">*</span></label>
                                                    <select name="lss_ls_id" id="lss_ls_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                                        <option value=""><?= lang('please_select_livestock'); ?></option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-sm-3">
                                                    <label for="exampleInputEmail1"><?php echo lang('ls_type_title'); ?><span class="text-danger">*</span></label>
                                                    <select name="lss_lst_id" class="form-control js-example-basic-single" id="livestock_type" placeholder="" style="width: 100%;">
                                                        <option value=""><?= lang('please_select_variant'); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-3">
                                                    <label for="exampleInputEmail1"> <?php echo lang('unit_price'); ?><span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control calculate_unit_price input__number" name="lss_unit_price_cal" id="price" value='' placeholder="">
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label for="exampleInputEmail1"> <?php echo lang('quantity'); ?><span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control calculate_quantity input__number" name="lss_quantity_cal" id="qty" value='' placeholder="">
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label for="exampleInputEmail1"> <?php echo lang('discount'); ?></label>
                                                    <input type="text" class="form-control calculate_discount input__number" name="lss_discount_cal" id="discount" value='' placeholder="">
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label for="exampleInputEmail1"> <?php echo lang('total'); ?><span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control calculate_total input__number" name="lss_total_cal" id="total" value='' placeholder="" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-1" style="margin-top: 100px;">
                                            <button type="submit" class="button button-primary form add__button"><i class="fas fa-plus-circle"></i> <?= lang('add'); ?></button>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group" style="margin-top: 50px;">
                                                <span id="totalLivestock"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <form role="form" action="<?php echo base_url('') ?>sale/insertLivestockSale" method="post" id="formId" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="row row-top-margin">
                                                    <div class="col-md-12">
                                                        <table class="table table-bordered data-table">
                                                            <thead>
                                                                <th><?= lang('shed'); ?></th>
                                                                <th><?= lang('batch'); ?></th>
                                                                <th><?= lang('livestock'); ?></th>
                                                                <th><?= lang('livestock_variant'); ?></th>
                                                                <th><?= lang('unit_price'); ?></th>
                                                                <th><?= lang('quantity'); ?></th>
                                                                <th><?= lang('discount'); ?></th>
                                                                <th><?= lang('total'); ?></th>
                                                                <th width="200px"><?= lang('actions'); ?></th>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo lang('client'); ?><span class="text-danger">*</span></label>
                                                    <select class="form-control js-example-basic-single" name="lss_c_id" value='' required>
                                                        <option value=""><?= lang('please_select_client'); ?></option>
                                                        <?php foreach ($clients->result() as $client) { ?>
                                                            <option value="<?php echo $client->c_id; ?>"> <?php echo $client->c_name; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo lang('date'); ?><span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control datepicker" name="lss_date" value='<?= get_current_date(); ?>' placeholder="" autocomplete="off" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo lang('sub_total'); ?><span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control calculate_sub_total readonly" name="lss_sub_total" id="sub_total" value='' placeholder="" required>
                                                </div>

                                                <div class="form-group display_hide">
                                                    <label for="exampleInputEmail1"> <?php echo lang('discount'); ?> </label>
                                                    <input type="text" class="form-control calculate_grand_discount input__number" name="lss_grand_discount" id="" value='' placeholder="">
                                                </div>
                                                <div class="form-group display_hide">
                                                    <label for="exampleInputEmail1"> <?php echo lang('amount_payable'); ?><span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control calculate_grand_total readonly" name="lss_grand_total" id="grand_total" value='' placeholder="" required>
                                                </div>


                                                <!-- Payment Method -->
                                                <div class="button bg-primary-light" style="width: 100%;">
                                                    <input type="checkbox" id="paymentCheckBox" />
                                                    <label for="paymentCheckBox"><?= lang('click_here_to_payment_now'); ?></label>
                                                </div>
                                                <div id="modal_body" style="display: none; border: 1px solid #b8daff; padding: 10px; margin: 5px 0 5px 0">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?= lang('received'); ?> <?= lang('amount'); ?><span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control input__number" name="cp_received_amount" id="receivableAmount" value='' placeholder="">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"> <?php echo lang('paid_by'); ?><span class="text-danger">*</span></label>
                                                        <select id="paid_by" class="form-control m-bot15" name="cp_paid_by" value=''>
                                                            <option value="cash"><?php echo lang('cash'); ?> </option>
                                                            <option value="cheque"><?php echo lang('cheque'); ?></option>
                                                            <option value="other"><?php echo lang('other'); ?> </option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group" id="cheque_no" style="display: none;">
                                                        <label for="exampleInputEmail1"> <?php echo lang('cheque_no'); ?><span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="cp_cheque_no" id="check_number" value='' placeholder="">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?= lang('reference'); ?></label>
                                                        <input type="text" class="form-control" name="cp_reference" id="reference" value='' placeholder="">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                                                        <input name="cp_description" class="form-control" id="description" placeholder="">
                                                    </div>
                                                </div>
                                                <!-- /.Payment Method -->


                                                <div class="form-group">
                                                    <label class="control-label col-md-3 note"><?php echo lang('note'); ?></label>
                                                    <div class="col-md-12 note">
                                                        <textarea class="form-control" name="lss_note" value="" rows="5" cols="10" style="height: auto !important;"> </textarea>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="lss_id" value=''>
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


<input type="hidden" id="shedQuantityAlert" value="<?php echo $this->report_model->getCountRow('shed', 'sh_id', ['sh_status' => 1]); ?>">
<input type="hidden" id="assignedQuantityAlert" value="<?php echo $this->report_model->getCountRow('live_assigned_shed_summary', 'lshs_id', ['lshs_status' => 1]); ?>">
<input type="hidden" id="clientAlert" value="<?php echo $this->report_model->getCountRow('client', 'c_id', ['c_status' => 1]); ?>">



<!-- page end-->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // Clear session
        sessionStorage.clear();

        //Calculate: Unit price * quantity - discount
        $(document).on('keyup change paste', function() {
            var getUnitPrice = $("input[name='lss_unit_price_cal']").val();
            var getQuantity = $("input[name='lss_quantity_cal']").val();
            var getDiscount = $("input[name='lss_discount_cal']").val();
            var calculateUnitPriceIntoQuantity = getUnitPrice * getQuantity;
            var getTotal = calculateUnitPriceIntoQuantity - getDiscount;
            $("input[name='lss_total_cal']").val(getTotal);
        });

        // Livestock Stock Alert if added more than stock
        $("#qty").on('keyup change paste', function() {
            var keyupQuantity = $(this).val();
            var stockQuantity = $(".stockQuantityData").attr('data-stock-value');

            let stockData = sessionStorage.getItem('stockValue');

            if (parseFloat(keyupQuantity) > parseFloat(stockData)) {
                Swal.fire({
                    icon: "warning",
                    title: "<?= lang('warning'); ?>...",
                    text: "<?= lang('please_enter_a_valid_value'); ?>"
                });
                $(this).val('');
                $(".form").attr("disabled", true);
            } else {
                $(".form").attr("disabled", false);
            }
        });

        // On change Livestock dropdown all empty
        $("#lss_shed_id").change(function() {
            $("input[name='lss_unit_price_cal']").val('');
            $("input[name='lss_quantity_cal']").val('');
            $("input[name='lss_discount_cal']").val('');
            $("#lss_batch_id").empty();
            $("#lss_ls_id").empty();
            $("#livestock_type").empty();
            $("#lss_ls_id").append(`<option value=""><?= lang('empty'); ?></option>`);
            $("#livestock_type").append(`<option value=""><?= lang('empty'); ?></option>`);

            $("#totalLivestock").empty();
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



        // Check shed and batch if already added to invoice
        $("#lss_batch_id").change(function() {

            var shed_id = $("#lss_shed_id option:selected").val();
            var batch_id = $("#lss_batch_id option:selected").val();

            if (sessionStorage.getItem(shed_id + "_" + batch_id)) {
                if (sessionStorage.getItem(shed_id + "_" + batch_id) == batch_id) {
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('you_have_already_added_this_item'); ?>"
                    });
                    $("select#lss_batch_id")[0].selectedIndex = 0;
                    $('#lss_batch_id').trigger('change');
                }
            }
        });


        // Create New Row With Entered Data
        $(".form").click(function(e) {
            e.preventDefault();
            // Auto Generate ID
            var auto_generate_id = parseInt($("#auto_generate_id").val()) + 1;
            $("#auto_generate_id").val(auto_generate_id);
            var lss_ls_id1 = "lss_ls_id" + `${auto_generate_id}`;
            var lss_lst_id1 = "livestock_type" + `${auto_generate_id}`;
            var lss_batch_id1 = "lss_batch_id" + `${auto_generate_id}`;
            var lss_shed_id1 = "lss_shed_id" + `${auto_generate_id}`;

            // batch
            var lss_batch_id = $("#lss_batch_id option:selected").val();
            var lss_batch_text = $("#lss_batch_id option:selected").text();
            // shed
            var lss_shed_id = $("#lss_shed_id option:selected").val();
            var lss_shed_text = $("#lss_shed_id option:selected").text();

            var lss_ls_id = $("#lss_ls_id option:selected").val();
            var lss_ls_text = $("#lss_ls_id option:selected").text();

            var lss_lst_id = $("#livestock_type option:selected").val();
            var lss_lst_text = $("#livestock_type option:selected").text();

            var lss_unit_price = $("input[name='lss_unit_price_cal']").val();
            var lss_quantity = $("input[name='lss_quantity_cal']").val();
            var lss_discount = $("input[name='lss_discount_cal']").val();
            var lss_total = $("input[name='lss_total_cal']").val();

            var priceIntoQuantity = lss_unit_price * lss_quantity;
            var newTotal = priceIntoQuantity - lss_discount;

            if (lss_ls_id == "" || lss_ls_id == null) {
                alert('Please Select Livestock.');
            } else if (lss_unit_price == "" || lss_unit_price == null) {
                document.getElementById("price").placeholder = "Please fill this.";
            } else if (lss_quantity == "" || lss_quantity == null) {
                document.getElementById("qty").placeholder = "Please fill this.";
            } else {
                $(".data-table tbody").append("<tr data-" + lss_shed_id1 + "='" + lss_shed_id + "'  data-" + lss_batch_id1 + "='" + lss_batch_id + "' data-" + lss_ls_id1 + "='" + lss_ls_id + "' data-" + lss_lst_id1 + "='" + lss_lst_id + "' data-lss_unit_price='" + lss_unit_price + "' data-lss_quantity='" + lss_quantity + "' data-lss_discount='" + lss_discount + "' data-lss_total='" + lss_total + "'><td>" + lss_shed_text + " <input type='hidden' name='lss_shed_id[]' value='" + lss_shed_id + "'></td><td>" + lss_batch_text + " <input type='hidden' name='lss_batch_id[]' value='" + lss_batch_id + "'></td><td>" + lss_ls_text + " <input type='hidden' name='lss_ls_id[]' value='" + lss_ls_id + "'></td><td>" + lss_lst_text + "<input type='hidden' name='lss_lst_id[]' value='" + lss_lst_id + "'></td> <td>" + lss_unit_price + "<input type='hidden' name='lss_unit_price[]' value=" + lss_unit_price + "></td><td>" + lss_quantity + "<input type='hidden' name='lss_quantity[]' value='" + lss_quantity + "'></td> <td>" + lss_discount + "<input type='hidden' name='lss_discount[]' value='" + lss_discount + "'></td><td>" + newTotal + "<input type='hidden' class='for_sum_sub_total' name='lss_total[]' value='" + newTotal + "'></td><td width='200px'><button type='button' class='button button-warning product-edit display_hide'><i class='fas fa-edit'></i></button> <button data-shed_id=" + lss_shed_id + " data-batch_id=" + lss_batch_id + " class='button button-danger btn-delete'><i class='fas fa-trash'></i></button></td></tr>");
                $("#price").removeAttr('placeholder');
                $("#qty").removeAttr('placeholder');
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
            $("input[name='lss_sub_total']").attr('value', sum);
            $("input[name='lss_grand_total']").attr('value', cal_gra_dis);

            $("input[name='lss_unit_price_cal']").val('');
            $("input[name='lss_quantity_cal']").val('');
            $("input[name='lss_discount_cal']").val('');
            $("input[name='lss_total_cal']").val('');

            // Empty dropdowns
            $("#lss_ls_id").empty();
            $("#lss_ls_id").append(`<option value=""><?= lang('empty'); ?></option>`);
            $("#livestock_type").empty();
            $("#livestock_type").append(`<option value=""><?= lang('empty'); ?></option>`);
            $("#lss_batch_id").empty();
            $("#lss_batch_id").append(`<option value=""><?= lang('empty'); ?></option>`);
            $("select#lss_shed_id")[0].selectedIndex = 0;
            $('#lss_shed_id').trigger('change');

            // Store shed & batch id in session 
            sessionStorage.setItem(lss_shed_id + "_" + lss_batch_id, lss_batch_id);

        });

        // Calculate grand total to minus grand discount
        function sendingData(sum) {
            $(".calculate_grand_discount").keyup(function() {

                var cgd = $(".calculate_grand_discount").val();

                $("input[name='lss_sub_total']").attr('value', sum);

                var variable = sum - cgd;
                $(".calculate_grand_total").val(variable);
            });
        }

        // Remove Data Row
        $("body").on("click", ".btn-delete", function() {
            $(this).parents("tr").remove();

            // Remove Paid Amount Value
            $("#receivableAmount").val('');

            // Sending Calculated Data
            var sum = 0;
            $('.for_sum_sub_total').each(function() {
                sum += parseFloat(this.value);
            });

            var calculate_grand_discount = $(".calculate_grand_discount").val();
            var cal_gra_dis = sum - calculate_grand_discount;

            // Send total sum to this input field
            $("input[name='lss_sub_total']").attr('value', sum);
            $("input[name='lss_grand_total']").attr('value', cal_gra_dis);

            // Empty Subtotal for that user can't submit
            var valueSubTotal = $("input[name='lss_sub_total']").val();
            if (valueSubTotal == 0) {
                $("input[name='lss_sub_total']").val('');
            }

            // Get Shed and batch id from delete button
            let shed_id = $(this).data("shed_id");
            let batch_id = $(this).data("batch_id");
            sessionStorage.removeItem(shed_id + "_" + batch_id);

        });

        // Update Row Data
        $("body").on("click", ".product-edit", function() {
            $(".product-edit").prop("disabled", true);
            // Auto Generated ID
            var auto_generate_id = parseInt($("#auto_generate_id").val());
            $("#auto_generate_id").val(auto_generate_id);

            var lss_ls_id_dynamic = "lss_ls_id" + auto_generate_id;
            var livestock_type_dynamic = "livestock_type" + auto_generate_id;
            var lss_shed_dynamic = "lss_shed_id" + auto_generate_id;
            var lss_batch_dynamic = "lss_batch_id" + auto_generate_id;

            var lss_shed_id = $(this).parents("tr").attr('data-' + lss_shed_dynamic);
            var lss_batch_id = $(this).parents("tr").attr('data-' + lss_batch_dynamic);
            var lss_ls_id = $(this).parents("tr").attr('data-' + lss_ls_id_dynamic);
            var lss_lst_id = $(this).parents("tr").attr('data-' + livestock_type_dynamic);
            var lss_unit_price = $(this).parents("tr").attr('data-lss_unit_price');
            var lss_quantity = $(this).parents("tr").attr('data-lss_quantity');
            var lss_discount = $(this).parents("tr").attr('data-lss_discount');
            var lss_total = $(this).parents("tr").attr('data-lss_total');

            $(this).parents("tr").find("td:eq(0)").html(`<div class="form-group col-sm-2" style="width: 100%">
                                                <select name="lss_shed_id" id="${lss_shed_dynamic}" class="form-control js-example-basic-single selectedValue"  data-id='${lss_shed_id}' placeholder="" style="width: 100%;">
                                                    <option value=""><?= lang('please_select_shed'); ?></option>
                                                    <?php if (!empty($sheds)) foreach ($sheds as $shed) { ?>
                                                        <option value="<?php echo $shed->sh_id ?>"><?php echo $shed->sh_no ?>: <?php echo $shed->sh_title ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>`);
            $(this).parents("tr").find("td:eq(1)").html(`<div class="form-group col-sm-2" style="width: 100%">
                                                <select name="lss_batch_id" id="${lss_batch_dynamic}" class="form-control js-example-basic-single selectedValue_baccha"  data-id='${lss_shed_id}' placeholder="" style="width: 100%;">
                                                    <option value=""><?= lang('please_select_batch'); ?></option>
                                                </select>
                                            </div>`);
            $(this).parents("tr").find("td:eq(2)").html(`<div class="form-group col-sm-2" style="width: 100%">
                                                    <select name="lss_ls_id" id="${lss_ls_id_dynamic}" class="form-control" placeholder="" style="width: 100%;" required>
                                                        <option value=""><?= lang('please_select_livestock'); ?></option>
                                                    </select>
                                                </div>`);

            $(this).parents("tr").find("td:eq(3)").html(`<div class="form-group col-sm-2" style="width: 100%">
                                                    <select name="lss_lst_id" class="form-control" id="${livestock_type_dynamic}" placeholder="" style="width: 100%;">
                                                    <option value=""><?= lang('please_select_variant'); ?></option>
                                                    </select>
                                                </div>`);
            $(this).parents("tr").find("td:eq(4)").html('<input type="number" class="form-control new_lss_unit_price" name="lss_unit_price" value="' + lss_unit_price + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(5)").html('<input type="number" class="form-control new_lss_quantity" name="lss_quantity" value="' + lss_quantity + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(6)").html('<input type="number" class="form-control new_lss_discount" name="lss_discount" value="' + lss_discount + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(7)").html('<input class="form-control new_lss_total" name="lss_total" value="' + lss_total + '" style="width: 100%" readonly>');

            $(".selectedValue").each(function() {
                $(this).val($(this).attr("data-id"));
            });

            // Select Batch by shed id
            var shed_id = lss_shed_id
            var batch_id = lss_batch_id
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('food/getActiveBatchByShedId'); ?>',
                data: {
                    shed_id: shed_id,
                    batch_id: batch_id
                },
                success: function(data) {
                    $('#' + lss_batch_dynamic).html(data);
                }
            });

            // On change shed cascading dropdown
            $('#' + lss_shed_dynamic).on('change', function() {
                var iid = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('food/getActiveBatchByShedId'); ?>',
                    data: {
                        shed_id: iid
                    },
                    success: function(data) {
                        $('#' + lss_batch_dynamic).html(data);
                    }
                });
            });

            // get live and type
            var batch_id = lss_batch_id
            var shed_id = lss_shed_id
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('purchase/getLivestockByShedAndBatchCascadingDropdown'); ?>',
                data: {
                    shed_id: shed_id,
                    batch_id: batch_id
                },
                dataType: 'json',
                success: function(data) {
                    $('#' + lss_ls_id_dynamic).html(data.livestock);
                    $('#' + livestock_type_dynamic).html(data.livestock_type);
                }
            });

            $('#' + lss_batch_dynamic).on('change', function() {
                var shed_id = $("#" + lss_shed_dynamic).val();
                var batch_id = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('purchase/getLivestockByShedAndBatchCascadingDropdown'); ?>',
                    data: {
                        shed_id: shed_id,
                        batch_id: batch_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#' + lss_ls_id_dynamic).html(data.livestock);
                        $('#' + livestock_type_dynamic).html(data.livestock_type);
                    }
                });
            });

            // On change shed deselect live and type
            $('#' + lss_shed_dynamic).on('change', function() {
                // get the selected index
                var index = $('#' + lss_ls_id_dynamic)[0].selectedIndex;
                $(`#${lss_ls_id_dynamic} option:eq(${index})`).remove();
                $(`#${lss_ls_id_dynamic}`).append(`<option value=""><?= lang('empty'); ?></option>`);

                var index2 = $('#' + livestock_type_dynamic)[0].selectedIndex;
                $(`#${livestock_type_dynamic} option:eq(${index2})`).remove();
                $(`#${livestock_type_dynamic}`).append(`<option value=""><?= lang('empty'); ?></option>`);
            });

            // Row Wise Calculation
            $(document).on('keyup change paste', function() {
                var unit_price = $(".new_lss_unit_price").val();
                var quantity = $(".new_lss_quantity").val();
                var discount = $(".new_lss_discount").val();

                var c_total = unit_price * quantity;
                var row_wise_total = c_total - discount;
                $(".new_lss_total").val(row_wise_total);
            });

            $(this).parents("tr").find("td:eq(8)").prepend("<button class='button button-info btn-xs product-update' style='width: auto;'><?= lang('update'); ?></button>");
            $(this).hide();

            // Cascading Dropdown for livestock variant
            $("#" + lss_ls_id_dynamic).on('change', function() {
                var iid = $("#" + lss_ls_id_dynamic).val();
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
            });









        });

        // Update Row Data
        $("body").on("click", ".product-update", function() {
            $(".product-edit").prop("disabled", false);
            // Auto Generate ID
            var auto_generate_id = parseInt($("#auto_generate_id").val());
            $("#auto_generate_id").val(auto_generate_id);
            // Shed
            var lss_shed_id = $(this).parents("tr").find("#lss_shed_id" + auto_generate_id + " option:selected").val();
            var lss_shed_id_text = $(this).parents("tr").find("#lss_shed_id" + auto_generate_id + " option:selected").text();
            // Batch
            var lss_batch_id = $(this).parents("tr").find("#lss_batch_id" + auto_generate_id + " option:selected").val();
            var lss_batch_id_text = $(this).parents("tr").find("#lss_batch_id" + auto_generate_id + " option:selected").text();

            var lss_ls_id = $(this).parents("tr").find("#lss_ls_id" + auto_generate_id + " option:selected").val();
            var lss_ls_id_text = $(this).parents("tr").find("#lss_ls_id" + auto_generate_id + " option:selected").text();

            var lss_lst_id = $(this).parents("tr").find("#livestock_type" + auto_generate_id + " option:selected").val();
            var lss_lst_id_text = $(this).parents("tr").find("#livestock_type" + auto_generate_id + " option:selected").text();

            var lss_unit_price = $(this).parents("tr").find("input[name='lss_unit_price']").val();
            var lss_quantity = $(this).parents("tr").find("input[name='lss_quantity']").val();
            var lss_discount = $(this).parents("tr").find("input[name='lss_discount']").val();
            var lss_total = $(this).parents("tr").find("input[name='lss_total']").val();

            $(this).parents("tr").find("td:eq(0)").html('' + lss_shed_id_text + ' <input type="hidden" name="lss_shed_id[]" value="' + lss_shed_id + '">');
            $(this).parents("tr").find("td:eq(1)").html('' + lss_batch_id_text + ' <input type="hidden" name="lss_batch_id[]" value="' + lss_batch_id + '">');
            $(this).parents("tr").find("td:eq(2)").html('' + lss_ls_id_text + ' <input type="hidden" name="lss_ls_id[]" value="' + lss_ls_id + '">');
            $(this).parents("tr").find("td:eq(3)").html('' + lss_lst_id_text + ' <input type="hidden" name="lss_lst_id[]" value="' + lss_lst_id + '">');
            $(this).parents("tr").find("td:eq(4)").html('' + lss_unit_price + ' <input type="hidden" name="lss_unit_price[]" value="' + lss_unit_price + '">');
            $(this).parents("tr").find("td:eq(5)").html('' + lss_quantity + ' <input type="hidden" name="lss_quantity[]" value="' + lss_quantity + '">');
            $(this).parents("tr").find("td:eq(6)").html('' + lss_discount + ' <input type="hidden" name="lss_discount[]" value="' + lss_discount + '">');
            $(this).parents("tr").find("td:eq(7)").html('' + lss_total + ' <input type="hidden" class="for_sum_sub_total" name="lss_total[]" value="' + lss_total + '">');

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
            $("input[name='lss_sub_total']").attr('value', sum);
            $("input[name='lss_grand_total']").attr('value', cal_gra_dis);

        });

    });
</script>

<script>
    $(document).ready(function() {

        // Cascading - Add
        $('#lss_shed_id').on('change', function() {
            var iid = $(this).val();
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('food/getActiveBatchByShedId'); ?>',
                data: {
                    shed_id: iid
                },
                success: function(data) {
                    $('#lss_batch_id').html(data);
                }
            });
        });

        // Livestock Stock
        $('#lss_batch_id').on('change', function() {
            var batch_id = $(this).val();
            var shed_id = $("#lss_shed_id option:selected").val();
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('purchase/getLivestockByShedAndBatchCascadingDropdown'); ?>',
                data: {
                    shed_id: shed_id,
                    batch_id: batch_id
                },
                dataType: 'json',
                success: function(data) {
                    $('#lss_ls_id').html(data.livestock);

                    $('#livestock_type').html(data.livestock_type);

                    // Batch, Livestock, Livestock Type total Quantity of livestock by Ajax
                    if (data.availableLivestockQuantity == 0) {
                        $("#totalLivestock").html('<h4><?= lang('in_stock'); ?>: <strong class="text-danger stockQuantityData" id="alpha" data-zero="0"><?= lang('empty'); ?></strong></h4>');
                        $(".form").attr("disabled", true);
                        sessionStorage.setItem('stockValue', '0');
                    } else if (data.availableLivestockQuantity > 0) {
                        $("#totalLivestock").html('<h4><?= lang('in_stock'); ?>: <strong class="stockQuantityData" data-stock-value="' + data.availableLivestockQuantity + '">' + data.availableLivestockQuantity + ' </strong> <?= $settings->unit; ?></h4>');
                        $(".form").attr("disabled", false);
                        sessionStorage.setItem('stockValue', data.availableLivestockQuantity);
                    } else {
                        $(".form").attr("disabled", true);
                    }
                }
            });
        });


        // End

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

        // livestock purchase quantity alert
        function assignedQuantityAlert() {
            var assignedQuantityAlert = $("#assignedQuantityAlert").val();

            if (assignedQuantityAlert == 0) {
                toastr.warning('<?= lang('no_batch_found'); ?>');
            }
        }
        assignedQuantityAlert();

        // shed alert
        function shedQuantityAlert() {
            var shedQuantityAlert = $("#shedQuantityAlert").val();

            if (shedQuantityAlert == 0) {
                toastr.warning('<?= lang('no_shed_found'); ?>');
            }
        }
        shedQuantityAlert();

        // client alert
        function clientAlert() {
            var clientAlert = $("#clientAlert").val();

            if (clientAlert == 0) {
                toastr.warning('<?= lang('no_client_found'); ?>');
            }
        }
        clientAlert();
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
                $("#receivableAmount").prop("required", true);
                $("#paid_by").prop("required", true);

                // Disable False When Show 
                $("#receivableAmount").prop("disabled", false);
                $("#paid_by").prop("disabled", false);
                $("#check_number").prop("disabled", false);
                $("#reference").prop("disabled", false);
                $("#description").prop("disabled", false);

                // payment condition
                $("#receivableAmount").on('keyup change paste', function() {
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
                $("#receivableAmount").prop("required", false);
                $("#paid_by").prop("required", false);

                // Disable True When hide 
                $("#receivableAmount").prop("disabled", true);
                $("#paid_by").prop("disabled", true);
                $("#check_number").prop("disabled", true);
                $("#reference").prop("disabled", true);
                $("#description").prop("disabled", true);
            }

        });

        // Reset form after after reload page for firefox browser
        document.getElementById('formId').reset();

        // Reset dropdown on refresh page
        $("select#lss_shed_id")[0].selectedIndex = 0;

    });
</script>