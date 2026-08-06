<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel">
            <header class="panel-heading bg-info">
                <i class="fas fa-plus-circle"></i> <?= lang('add_new_product_sale'); ?>
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

                                            <div class="row">
                                                <div class="form-group col-sm-4">
                                                    <label for="exampleInputEmail1"><?= lang('products'); ?><span class="text-danger">*</span></label>
                                                    <select name="prs_pra_id" id="prs_pra_id" class="form-control js-example-basic-single" placeholder="" style="width: 100%;">
                                                        <option value=""><?= lang('please_select_product_from_shed_and_batch'); ?></option>
                                                        <?php if (!empty($assignedProducts)) foreach ($assignedProducts as $product) {
                                                        ?>
                                                            <option value="<?php echo $product->pra_id ?>">
                                                                <?= $this->product_model->getProductById($product->pra_pr_id)->pr_name; ?> -
                                                                <?= $this->shed_model->getShedById($product->pra_shed_id)->sh_no ?>: <?= $this->shed_model->getShedById($product->pra_shed_id)->sh_title ?> -
                                                                <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($product->pra_shed_id, $product->pra_batch_id)->lshs_batch_id ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($product->pra_shed_id, $product->pra_batch_id)->lshs_batch_title ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label for="exampleInputEmail1"> <?php echo lang('unit_price'); ?><span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control calculate_unit_price input__number" name="prs_unit_price_cal" id="price" value='' placeholder="">
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label for="exampleInputEmail1"> <?php echo lang('quantity'); ?><span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control calculate_quantity input__number" name="prs_quantity_cal" id="qty" value='' placeholder="">
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label for="exampleInputEmail1"> <?php echo lang('discount'); ?> </label>
                                                    <input type="text" class="form-control calculate_discount input__number" name="prs_discount_cal" id="discount" value='' placeholder="">
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label for="exampleInputEmail1"> <?php echo lang('total'); ?><span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control calculate_total input__number" name="prs_total_cal" id="total" value='' placeholder="" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" class="button button-primary save-btn form" style="margin: 0 0 10px 0;"><i class="fas fa-plus-circle"></i> <?= lang('add'); ?></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group" style="margin-top: 30px;">
                                                <span id="stockInProduct"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <form role="form" action="<?php echo base_url('') ?>sale/insertProductSale" method="post" id="formId" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="row row-top-margin">
                                                    <div class="col-md-12">
                                                        <table class="table table-bordered data-table">
                                                            <thead>
                                                                <th><?= lang('product_name'); ?></th>
                                                                <th><?= lang('unit_price'); ?></th>
                                                                <th><?= lang('quantity'); ?></th>
                                                                <th><?= lang('discount_amount'); ?></th>
                                                                <th><?= lang('total_amount'); ?></th>
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
                                                    <select class="form-control js-example-basic-single" name="prs_c_id" value='' required>
                                                        <option value=""><?= lang('please_select_client'); ?></option>
                                                        <?php foreach ($clients->result() as $client) { ?>
                                                            <option value="<?php echo $client->c_id; ?>"> <?php echo $client->c_name; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?= lang('date'); ?><span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control datepicker" name="prs_date" id="" value='<?= get_current_date(); ?>' placeholder="" autocomplete="off" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo lang('sub_total'); ?><span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control calculate_sub_total input__number readonly" name="prs_sub_total" id="sub_total" value='' placeholder="" required>
                                                </div>
                                                <div class="form-group display_hide">
                                                    <label for="exampleInputEmail1"> <?php echo lang('discount'); ?> </label>
                                                    <input type="text" class="form-control calculate_grand_discount input__number" name="prs_grand_discount" id="" value='' placeholder="">
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo lang('amount_payable'); ?><span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control calculate_grand_total readonly" name="prs_grand_total" id="grand_total" value='' placeholder="" required>
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
                                                        <textarea class="form-control" name="prs_note" value="" rows="5" cols="10" style="height: auto !important;"> </textarea>
                                                    </div>
                                                </div>
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

<input type="hidden" id="productAssignAlert" value="<?php echo $this->report_model->getCountRow('product_assign', 'pra_id', ['pra_status' => 1]); ?>">



<!-- page end-->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // Clear session
        sessionStorage.clear();

        // Calculate Data
        $(document).on('keyup change paste', function() {
            var getUnitPrice = $("input[name='prs_unit_price_cal']").val();
            var getQuantity = $("input[name='prs_quantity_cal']").val();
            var getDiscount = $("input[name='prs_discount_cal']").val();
            var calculateUnitPriceIntoQuantity = getUnitPrice * getQuantity;
            var getTotal = calculateUnitPriceIntoQuantity - getDiscount;
            $("input[name='prs_total_cal']").val(getTotal);
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
        $("#prs_pra_id").change(function() {
            $("input[name='prs_quantity_cal']").val('');
            $("input[name='prs_discount_cal']").val('');

            $("#stockInProduct").empty();
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


        // Check Product if already added to invoice
        $("#prs_pra_id").change(function() {

            var prs_pra_id = $("#prs_pra_id option:selected").val();

            if (sessionStorage.getItem(prs_pra_id)) {
                if (sessionStorage.getItem(prs_pra_id) == prs_pra_id) {
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('you_have_already_added_this_item'); ?>"
                    });
                    $("select#prs_pra_id")[0].selectedIndex = 0;
                    $('#prs_pra_id').trigger('change');
                }
            }
        });



        // Create New Row With Entered Data
        $(".form").click(function(e) {
            e.preventDefault();

            // Auto Generate ID
            var auto_generate_id = parseInt($("#auto_generate_id").val()) + 1;
            $("#auto_generate_id").val(auto_generate_id);

            var prs_pra_id1 = "prs_pra_id" + `${auto_generate_id}`;

            // Product
            var prs_pra_id = $("#prs_pra_id option:selected").val();
            var prs_product_text = $("#prs_pra_id option:selected").text();

            var prs_unit_price = $("input[name='prs_unit_price_cal']").val();
            var prs_quantity = $("input[name='prs_quantity_cal']").val();
            var prs_discount = $("input[name='prs_discount_cal']").val();
            var prs_total = $("input[name='prs_total_cal']").val();

            var priceIntoQuantity = prs_unit_price * prs_quantity;
            var newTotal = priceIntoQuantity - prs_discount;

            if (prs_pra_id == "" || prs_pra_id == null) {
                alert('Please Select Product.');
            } else if (prs_unit_price == "" || prs_unit_price == null) {
                document.getElementById("price").placeholder = "Please fill this.";
            } else if (prs_quantity == "" || prs_quantity == null) {
                document.getElementById("qty").placeholder = "Please fill this.";
            } else {
                $(".data-table tbody").append("<tr data-" + prs_pra_id1 + "='" + prs_pra_id + "' data-prs_unit_price='" + prs_unit_price + "' data-prs_quantity='" + prs_quantity + "' data-prs_discount='" + prs_discount + "' data-prs_total='" + prs_total + "'><td>" + prs_product_text + " <input type='hidden' name='prs_pra_id[]' value='" + prs_pra_id + "'></td><td>" + prs_unit_price + "<input type='hidden' name='prs_unit_price[]' value=" + prs_unit_price + "></td><td>" + prs_quantity + "<input type='hidden' name='prs_quantity[]' value='" + prs_quantity + "'></td> <td>" + prs_discount + "<input type='hidden' name='prs_discount[]' value='" + prs_discount + "'></td><td>" + newTotal + "<input type='hidden' class='for_sum_sub_total' name='prs_total[]' value='" + newTotal + "'></td><td width='200px'><button type='button' class='button button-warning product-edit display_hide'><i class='fas fa-edit'></i></button> <button class='button button-danger btn-delete' data-prs_pra_id=" + prs_pra_id + "><i class='fas fa-trash'></i></button></td></tr>");
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
            $("input[name='prs_sub_total']").attr('value', sum);
            $("input[name='prs_grand_total']").attr('value', cal_gra_dis);

            $("input[name='prs_unit_price_cal']").val('');
            $("input[name='prs_quantity_cal']").val('');
            $("input[name='prs_discount_cal']").val('');
            $("input[name='prs_total_cal']").val('');

            // Select 1st index in dropdown
            $("select#prs_pra_id")[0].selectedIndex = 0;
            $('#prs_pra_id').trigger('change');

            // Store shed & batch id in session 
            sessionStorage.setItem(prs_pra_id, prs_pra_id);
        });

        // Calculate grand total to minus grand discount
        function sendingData(sum) {
            $(".calculate_grand_discount").keyup(function() {
                var cgd = $(".calculate_grand_discount").val();
                $("input[name='prs_sub_total']").attr('value', sum);
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
            $("input[name='prs_sub_total']").attr('value', sum);
            $("input[name='prs_grand_total']").attr('value', cal_gra_dis);

            // Empty Subtotal for that user can't submit
            var valueSubTotal = $("input[name='prs_sub_total']").val();
            if (valueSubTotal == 0) {
                $("input[name='prs_sub_total']").val('');
            }


            // Get prs_pra_id id from delete button
            let prs_pra_id = $(this).data("prs_pra_id");
            sessionStorage.removeItem(prs_pra_id);


        });

        // Update Row Data
        $("body").on("click", ".product-edit", function() {
            $(".product-edit").prop("disabled", true);
            // Auto Generated ID
            var auto_generate_id = parseInt($("#auto_generate_id").val());
            $("#auto_generate_id").val(auto_generate_id);

            var prs_product_dynamic = "prs_pra_id" + auto_generate_id;

            var prs_pra_id = $(this).parents("tr").attr('data-' + prs_product_dynamic);
            var prs_unit_price = $(this).parents("tr").attr('data-prs_unit_price');
            var prs_quantity = $(this).parents("tr").attr('data-prs_quantity');
            var prs_discount = $(this).parents("tr").attr('data-prs_discount');
            var prs_total = $(this).parents("tr").attr('data-prs_total');

            $(this).parents("tr").find("td:eq(0)").html(`<div class="form-group col-sm-2" style="width: 100%">
                                                <select name="prs_pra_id" id="${prs_product_dynamic}" class="form-control js-example-basic-single  selectedValue" data-id='${prs_pra_id}'" placeholder="" style="width: 100%;">
                                                <option value=""><?= lang('please_select_product_from_shed_and_batch'); ?></option>
                                                        <?php if (!empty($assignedProducts)) foreach ($assignedProducts as $product) {
                                                        ?>
                                                            <option value="<?php echo $product->pra_pr_id ?>">
                                                                <?= $this->product_model->getProductById($product->pra_pr_id)->pr_name; ?> -
                                                                <?= $this->shed_model->getShedById($product->pra_shed_id)->sh_no ?>: <?= $this->shed_model->getShedById($product->pra_shed_id)->sh_title ?> -
                                                                <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($product->pra_shed_id, $product->pra_batch_id)->lshs_batch_id ?>: <?= $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($product->pra_shed_id, $product->pra_batch_id)->lshs_batch_title ?>
                                                            </option>
                                                        <?php } ?>
                                                </select>
                                            </div>`);
            $(this).parents("tr").find("td:eq(1)").html('<input type="number" class="form-control new_prs_unit_price" name="prs_unit_price" value="' + prs_unit_price + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(2)").html('<input type="number" class="form-control new_prs_quantity" name="prs_quantity" value="' + prs_quantity + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(3)").html('<input type="number" class="form-control new_prs_discount" name="prs_discount" value="' + prs_discount + '" style="width: 100%">');
            $(this).parents("tr").find("td:eq(4)").html('<input class="form-control new_prs_total" name="prs_total" value="' + prs_total + '" style="width: 100%" readonly>');

            $(".selectedValue").each(function() {
                $(this).val($(this).attr("data-id"));
            });

            // Row Wise Calculation
            $(document).on('keyup change paste', function() {
                var unit_price = $(".new_prs_unit_price").val();
                var quantity = $(".new_prs_quantity").val();
                var discount = $(".new_prs_discount").val();

                var c_total = unit_price * quantity;
                var row_wise_total = c_total - discount;
                $(".new_prs_total").val(row_wise_total);
            });

            $(this).parents("tr").find("td:eq(5)").prepend("<button type='submit' class='button button-info product-update' style='width: auto;'><?= lang('update'); ?></button>");
            $(this).hide();
        });



        // Update Row Data
        $("body").on("click", ".product-update", function() {
            $(".product-edit").prop("disabled", false);
            // Auto Generate ID
            var auto_generate_id = parseInt($("#auto_generate_id").val());
            $("#auto_generate_id").val(auto_generate_id);
            // Product
            var prs_pra_id = $(this).parents("tr").find("#prs_pra_id" + auto_generate_id + " option:selected").val();
            var prs_pra_id_text = $(this).parents("tr").find("#prs_pra_id" + auto_generate_id + " option:selected").text();

            var prs_unit_price = $(this).parents("tr").find("input[name='prs_unit_price']").val();
            var prs_quantity = $(this).parents("tr").find("input[name='prs_quantity']").val();
            var prs_discount = $(this).parents("tr").find("input[name='prs_discount']").val();
            var prs_total = $(this).parents("tr").find("input[name='prs_total']").val();

            $(this).parents("tr").find("td:eq(0)").html('' + prs_pra_id_text + ' <input type="hidden" name="prs_pra_id[]" value="' + prs_pra_id + '">');
            $(this).parents("tr").find("td:eq(1)").html('' + prs_unit_price + ' <input type="hidden" name="prs_unit_price[]" value="' + prs_unit_price + '">');
            $(this).parents("tr").find("td:eq(2)").html('' + prs_quantity + ' <input type="hidden" name="prs_quantity[]" value="' + prs_quantity + '">');
            $(this).parents("tr").find("td:eq(3)").html('' + prs_discount + ' <input type="hidden" name="prs_discount[]" value="' + prs_discount + '">');
            $(this).parents("tr").find("td:eq(4)").html('' + prs_total + ' <input type="hidden" class="for_sum_sub_total" name="prs_total[]" value="' + prs_total + '">');

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
            $("input[name='prs_sub_total']").attr('value', sum);
            $("input[name='prs_grand_total']").attr('value', cal_gra_dis);

        });


        // Get Product Selling Price
        $("#prs_pra_id").change(function() {
            var prs_pra_id = $(this).val();
            // Product Price
            $.ajax({
                url: 'sale/getProductPriceByProductIdByAjax?pra_id=' + prs_pra_id,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                $("#price").val(response.productById.pr_selling_price);
                if (response.product_stock == 0) {
                    $("#stockInProduct").html('<h4><?= lang('product_in_stock'); ?>: <strong class="text-danger stockQuantityData" id="alpha" data-zero="0"><?= lang('empty'); ?></strong></h4>');
                    $(".form").attr("disabled", true);
                    sessionStorage.setItem('stockValue', '0');
                } else if (response.product_stock > 0) {
                    $("#stockInProduct").html('<h4><?= lang('product_in_stock'); ?>: <strong class="stockQuantityData" data-stock-value="' + response.product_stock + '">' + response.product_stock + ' </strong> ' + response.unit_name + '</h4>');
                    $(".form").attr("disabled", false);
                    sessionStorage.setItem('stockValue', response.product_stock);
                } else {
                    $(".form").attr("disabled", true);
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
        function productAssignAlert() {
            var productAssignAlert = $("#productAssignAlert").val();

            if (productAssignAlert == 0) {
                toastr.warning('<?= lang('no_product_assign_yet'); ?>');
            }
        }
        productAssignAlert();
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
        $("select#prs_pra_id")[0].selectedIndex = 0;

        <?php if ($this->session->flashdata('success')): ?>
        toastr.success('<?= html_escape($this->session->flashdata('success')); ?>');
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
        toastr.error('<?= html_escape($this->session->flashdata('error')); ?>');
        <?php endif; ?>
    });
</script>