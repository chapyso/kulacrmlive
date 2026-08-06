<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel">
            <header class="panel-heading">
                <i class="fas fa-book"></i> <?= lang('supplier'); ?> <?= lang('ledger'); ?>
            </header>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="space15 noprint">
                        <a href="<?php echo base_url('payments/listSupplierPayments'); ?>">
                            <div class="btn-group">
                                <button class="button button-primary">
                                    <i class="fa-solid fa-circle-arrow-left"></i>
                                </button>
                            </div>
                        </a>
                        <a href="<?php echo base_url('supplier/listSupplier'); ?>">
                            <div class="btn-group">
                                <button class="button button-info">
                                    <i class="fa-solid fa-circle-arrow-up"></i> <?= lang('supplier'); ?> <?= lang('list'); ?>
                                </button>
                            </div>
                        </a>
                        <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="new__cards__auto card__box">
                                <div class="col-xs-3">
                                    <div class="text-center">
                                        <p><strong><?= lang('basic_information'); ?></strong></p>
                                    </div>
                                    <table class="table">
                                        <tr>
                                            <td width="40%">
                                                <?php if ($supplierById->s_img_url) { ?>
                                                    <img class="img-square" style="height: 140px; width: 140px; border-radius: 5px;" src="<?php echo $supplierById->s_img_url; ?>" alt="No img">
                                                <?php } else { ?>
                                                    <img class="img-square" style="height: 140px; width: 140px; border-radius: 5px;" src="<?php echo base_url('uploads/avatar/alter-image.png'); ?>" alt="No img">
                                                <?php } ?>
                                            </td>
                                            <td width="60%">
                                                <p><strong><?= lang('supplier'); ?>:</strong> <?= $supplierById->s_name; ?></p>
                                                <p><strong><?= lang('email'); ?>:</strong> <?= $supplierById->s_email; ?></p>
                                                <p><strong><?= lang('phone'); ?>:</strong> <?= $supplierById->s_phone; ?></p>
                                                <p><strong><?= lang('address'); ?>:</strong> <?= $supplierById->s_address; ?></p>
                                                <p><strong><?= lang('note'); ?>:</strong> <?= $supplierById->s_description; ?></p>
                                            </td>
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
                                            <p><?= lang('total_payable_amount'); ?></p>
                                            <h3> <?php $purchaseTotal = $this->purchase_model->getSumSupplierWiseTotalPurchaseAmount($supplierById->s_id) + $this->food_model->getSumFoodSupplierWiseTotalPurchaseAmount($supplierById->s_id) + $this->vaccine_model->getSumVaccineSupplierWiseTotalPurchaseAmount($supplierById->s_id);
                                                    if ($purchaseTotal) {
                                                        echo $settings->currency . number_format_currency($purchaseTotal, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?>
                                            </h3>
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
                                            <h3> <?php $supplierPaidAmount = $this->payments_model->getSumSupplierWiseTotalPaidAmount($supplierById->s_id);
                                                    if ($supplierPaidAmount) {
                                                        echo $settings->currency . number_format_currency($supplierPaidAmount, 2);
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
                                            <h3> <?php $purchaseTotalDue = $this->purchase_model->getSumSupplierWiseTotalPurchaseAmount($supplierById->s_id) + $this->food_model->getSumFoodSupplierWiseTotalPurchaseAmount($supplierById->s_id) + $this->vaccine_model->getSumVaccineSupplierWiseTotalPurchaseAmount($supplierById->s_id);
                                                    $supplierPaidAmountDue = $this->payments_model->getSumSupplierWiseTotalPaidAmount($supplierById->s_id);

                                                    echo $settings->currency . number_format_currency($purchaseTotalDue - $supplierPaidAmountDue, 2);
                                                    ?>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table  table-hover table-bordered" id="editable-sample">
                    <thead>
                        <tr>
                            <th><?= lang('purchase_date'); ?></th>
                            <th><?= lang('purchase_type'); ?></th>
                            <th><?= lang('total_payable_amount'); ?></th>
                            <th><?= lang('paid_amount'); ?></th>
                            <th><?= lang('due_amount'); ?></th>
                            <th><?= lang('payment_status'); ?></th>
                            <th><?= lang('payment_count'); ?></th>
                            <th><?= lang('options'); ?></th>
                        </tr>
                    </thead>
                    <tbody>


                        <?php
                        $dates1 = array();
                        $dates2 = array();
                        $dates3 = array();
                        foreach ($purchaseBySupplierId as $list) {
                            $dates1[] = $list->purs_date;
                        }
                        foreach ($foodPurchaseBySupplierId as $foodValue) {
                            $dates2[] = $foodValue->fdps_date;
                        }

                        foreach ($vaccinePurchaseBySupplierId as $vaccineValue) {
                            $dates3[] = $vaccineValue->vps_date;
                        }

                        $mergeDate = array_merge($dates1, $dates2, $dates3);
                        $uniqueDate = array_unique($mergeDate);
                        asort($uniqueDate);
                        ?>

                        <!-- ====================== Livestock Purchase Data ====================== -->
                        <?php

                        foreach ($uniqueDate as $key => $value) {
                            foreach ($purchaseBySupplierId as $list) {
                                if ($list->purs_date == $value) {
                        ?>
                                    <tr class="alert-success">
                                        <td class="table__cells"> <?= date("$settings->date_format", strtotime($list->purs_date)); ?></td>
                                        <td class="table__cells"> <?= lang('livestock'); ?></td>
                                        <td class="table__cells"> <?= $settings->currency . number_format_currency($list->purs_grand_total, 2); ?></td>
                                        <td class="table__cells"> <?php $totalPaidAmount = $this->payments_model->getSumSupplierAndPurchaseWiseTotalPaidAmount($supplierById->s_id, $list->purs_id);
                                                                    if ($totalPaidAmount) {
                                                                        echo $settings->currency . number_format_currency($totalPaidAmount, 2);
                                                                    } else {
                                                                        echo 0;
                                                                    }
                                                                    ?></td>
                                        <td class="table__cells">
                                            <?php
                                            $PayableTotalAmount = $list->purs_grand_total;
                                            $paidTotalAmount = $this->payments_model->getSumSupplierAndPurchaseWiseTotalPaidAmount($supplierById->s_id, $list->purs_id);
                                            $totalDueAmount = $PayableTotalAmount - $paidTotalAmount;
                                            echo $settings->currency . number_format_currency($totalDueAmount, 2);
                                            ?>
                                        </td>
                                        <td class="table__cells">
                                            <?php if ($PayableTotalAmount == $paidTotalAmount) {
                                                echo "<span class='badge btn-success'>" . lang('paid') . "</span>";
                                            } elseif ($PayableTotalAmount > $paidTotalAmount) {
                                                echo "<span class='badge btn-danger'>" . lang('pending') . "</span>";
                                            } else {
                                                echo "<span class='badge btn-warning'>" . lang('over_payment') . "</span>";
                                            } ?>
                                        </td>
                                        <td class="table__cells">
                                            <?php echo $this->payments_model->getSupplierPurchaseWisePaymentCountRow($supplierById->s_id, $list->purs_id); ?>
                                        </td>
                                        <td class="table__cells">
                                            <button type="button" class="button button-primary addSupplierPayment" data-toggle="modal" data-id="<?php echo $supplierById->s_id; ?>" data-name="<?php echo $supplierById->s_name; ?>" data-purchase-id="<?php echo $list->purs_id; ?>" data-total-due-amount-livestock="<?php echo $totalDueAmount; ?>"><i class="fas fa-plus-circle"></i> <?= lang('add_payment'); ?></button>
                                            <a class="" href="<?php echo base_url('') ?>payments/viewSupplierPurchaseWisePayments?sp_purs_id=<?php echo $list->purs_id; ?>"><button type="button" class="button button-info"><i class="fas fa-eye"></i> <?= lang('view_payments'); ?></button></a>
                                            <a href="<?php echo base_url('') ?>purchase/viewLivestockPurchase?purs_id=<?php echo $list->purs_id; ?>"><button type="button" class="button button-success"><i class="fas fa-file-invoice"></i> <?= lang('invoice'); ?></button></a>
                                        </td>
                                    </tr>
                            <?php }
                            } ?>
                            <!-- ====================== Food Purchase Data ====================== -->
                            <?php
                            foreach ($foodPurchaseBySupplierId as $foodValue) {
                                if ($foodValue->fdps_date == $value) {
                            ?>
                                    <tr class="alert-warning">
                                        <td class="table__cells"> <?= date("$settings->date_format", strtotime($foodValue->fdps_date)); ?></td>
                                        <td class="table__cells"> Food</td>
                                        <td class="table__cells">
                                            <?= $settings->currency . number_format_currency($foodValue->fdps_grand_total, 2); ?>
                                        </td>
                                        <td class="table__cells">
                                            <!-- Paid Amount -->
                                            <?php $totalPaidAmountFood = $this->payments_model->getSumSupplierAndFoodPurchaseWiseTotalPaidAmount($supplierById->s_id, $foodValue->fdps_id);
                                            if ($totalPaidAmountFood) {
                                                echo $settings->currency . number_format_currency($totalPaidAmountFood, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                        <td class="table__cells">
                                            <!-- Due Amount -->
                                            <?php
                                            $PayableTotalAmountFood = $foodValue->fdps_grand_total;
                                            $paidTotalAmountFood = $this->payments_model->getSumSupplierAndFoodPurchaseWiseTotalPaidAmount($supplierById->s_id, $foodValue->fdps_id);
                                            $totalDueAmountFood = $PayableTotalAmountFood - $paidTotalAmountFood;
                                            echo $settings->currency . number_format_currency($totalDueAmountFood, 2);
                                            ?>
                                        </td>
                                        <td class="table__cells">
                                            <!-- Status -->
                                            <?php if ($PayableTotalAmountFood == $paidTotalAmountFood) {
                                                echo "<span class='badge btn-success'>" . lang('paid') . "</span>";
                                            } elseif ($PayableTotalAmountFood > $paidTotalAmountFood) {
                                                echo "<span class='badge btn-danger'>" . lang('pending') . "</span>";
                                            } else {
                                                echo "<span class='badge btn-warning'>" . lang('over_payment') . "</span>";
                                            } ?>
                                        </td>
                                        <td class="table__cells">
                                            <!-- Payment Count -->
                                            <?php echo $this->payments_model->getSupplierFoodPurchaseWisePaymentCountRow($supplierById->s_id, $foodValue->fdps_id); ?>
                                        </td>
                                        <td class="table__cells">
                                            <!-- Actions -->
                                            <button type="button" class="button button-primary addFoodSupplierPayment" data-toggle="modal" data-id="<?php echo $supplierById->s_id; ?>" data-name="<?php echo $supplierById->s_name; ?>" data-food-purchase-id="<?php echo $foodValue->fdps_id; ?>" data-total-due-amount-food="<?php echo $totalDueAmountFood; ?>"><i class="fas fa-plus-circle"></i> <?= lang('add_payment'); ?></button>
                                            <a class="" href="<?php echo base_url('') ?>payments/viewSupplierFoodPurchaseWisePayments?sp_fdps_id=<?php echo $foodValue->fdps_id; ?>"><button type="button" class="button button-info"><i class="fas fa-eye"></i> <?= lang('view_payments'); ?></button></a>
                                            <a href="<?php echo base_url('') ?>food/viewFoodPurchase?fdps_id=<?php echo $foodValue->fdps_id; ?>"><button type="button" class="button button-success"><i class="fas fa-file-invoice"></i> <?= lang('invoice'); ?></button></a>
                                        </td>
                                    </tr>
                            <?php }
                            } ?>
                            <!-- ====================== Vaccine Purchase Data ====================== -->
                            <?php
                            foreach ($vaccinePurchaseBySupplierId as $vaccineValue) {
                                if ($vaccineValue->vps_date == $value) {
                            ?>
                                    <tr class="alert-info">
                                        <td class="table__cells"> <?= date("$settings->date_format", strtotime($vaccineValue->vps_date)); ?></td>
                                        <td class="table__cells"> Vaccine</td>
                                        <td class="table__cells">
                                            <?= $settings->currency . number_format_currency($vaccineValue->vps_grand_total, 2); ?>
                                        </td>
                                        <td class="table__cells">
                                            <!-- Paid Amount -->
                                            <?php $totalPaidAmountVaccine = $this->payments_model->getSumSupplierAndVaccinePurchaseWiseTotalPaidAmount($supplierById->s_id, $vaccineValue->vps_id);
                                            if ($totalPaidAmountVaccine) {
                                                echo $settings->currency . number_format_currency($totalPaidAmountVaccine, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?>
                                        </td>
                                        <td class="table__cells">
                                            <!-- Due Amount -->
                                            <?php
                                            $PayableTotalAmountVaccine = $vaccineValue->vps_grand_total;
                                            $paidTotalAmountVaccine = $this->payments_model->getSumSupplierAndVaccinePurchaseWiseTotalPaidAmount($supplierById->s_id, $vaccineValue->vps_id);
                                            $totalDueAmountVaccine = $PayableTotalAmountVaccine - $paidTotalAmountVaccine;
                                            echo $settings->currency . number_format_currency($totalDueAmountVaccine, 2);
                                            ?>
                                        </td>
                                        <td class="table__cells">
                                            <!-- Status -->
                                            <?php if ($PayableTotalAmountVaccine == $paidTotalAmountVaccine) {
                                                echo "<span class='badge btn-success'>" . lang('paid') . "</span>";
                                            } elseif ($PayableTotalAmountVaccine > $paidTotalAmountVaccine) {
                                                echo "<span class='badge btn-danger'>" . lang('pending') . "</span>";
                                            } else {
                                                echo "<span class='badge btn-warning'>" . lang('over_payment') . "</span>";
                                            } ?>
                                        </td>
                                        <td class="table__cells">
                                            <!-- Payment Count -->
                                            <?php echo $this->payments_model->getSupplierVaccinePurchaseWisePaymentCountRow($supplierById->s_id, $vaccineValue->vps_id); ?>
                                        </td>
                                        <td class="table__cells">
                                            <!-- Actions -->
                                            <button type="button" class="button button-primary addVaccineSupplierPayment" data-toggle="modal" data-id="<?php echo $supplierById->s_id; ?>" data-name="<?php echo $supplierById->s_name; ?>" data-vaccine-purchase-id="<?php echo $vaccineValue->vps_id; ?>" data-total-due-amount-vaccine="<?php echo $totalDueAmountVaccine; ?>"><i class="fas fa-plus-circle"></i> <?= lang('add_payment'); ?></button>
                                            <a class="" href="<?php echo base_url('') ?>payments/viewSupplierVaccinePurchaseWisePayments?sp_vps_id=<?php echo $vaccineValue->vps_id; ?>"><button type="button" class="button button-info"><i class="fa fa-eye"></i> <?= lang('view_payments'); ?></button></a>
                                            <a href="<?php echo base_url('') ?>vaccine/viewVaccinePurchase?vps_id=<?php echo $vaccineValue->vps_id; ?>"><button type="button" class="button button-success"><i class="fas fa-file-invoice"></i> <?= lang('invoice'); ?></button></a>
                                        </td>
                                    </tr>
                        <?php }
                            }
                        } ?>
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


<!-- Livestock Purchase -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('add_supplier_payment'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('payments/insertSupplierPayment') ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('name'); ?></label>
                            <input type="text" class="form-control" id="s_name" placeholder="" readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('due_amount'); ?></label>
                            <input type="text" class="form-control" id="total_due_amount_livestock" placeholder="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('amount'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control input__number" name="sp_payment_amount" id="payableAmount" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"> <?php echo lang('paid_by'); ?><span class="text-danger">*</span></label>
                        <select id="paid_by" class="form-control m-bot15" name="sp_paid_by" value='' required>
                            <option value="cash"><?php echo lang('cash'); ?> </option>
                            <option value="cheque"><?php echo lang('cheque'); ?></option>
                            <option value="other"><?php echo lang('other'); ?> </option>
                        </select>
                    </div>
                    <div class="form-group" id="cheque_no" style="display: none;">
                        <label for="exampleInputEmail1"> <?php echo lang('cheque_no'); ?></label>
                        <input type="text" class="form-control" name="sp_cheque_no" id="" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('reference'); ?></label>
                        <input type="text" class="form-control" name="sp_reference" id="" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('date'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-inline datepicker" name="sp_date" id="" value='<?php echo get_current_date(); ?>' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="sp_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="sp_s_id" id="s_id" value=''>
                    <input type="hidden" name="sp_purs_id" id="purchase_id" value=''>
                    <section class=" text-right">
                        <button type="submit" name="submit" class="button button-info submit_button" id="submitButton"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Food Purchase -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('add_supplier_payment'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('payments/insertSupplierPayment') ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('name'); ?></label>
                            <input type="text" class="form-control" id="food_s_name" placeholder="" readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('due_amount'); ?></label>
                            <input type="text" class="form-control" id="total_due_amount_food" placeholder="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('amount'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control input__number" name="sp_payment_amount" id="payableAmountFood" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"> <?php echo lang('paid_by'); ?><span class="text-danger">*</span></label>
                        <select id="food_paid_by" class="form-control m-bot15" name="sp_paid_by" value='' required>
                            <option value="cash"><?php echo lang('cash'); ?> </option>
                            <option value="cheque"><?php echo lang('cheque'); ?></option>
                            <option value="other"><?php echo lang('other'); ?> </option>
                        </select>
                    </div>
                    <div class="form-group" id="food_cheque_no" style="display: none;">
                        <label for="exampleInputEmail1"> <?php echo lang('cheque_no'); ?></label>
                        <input type="text" class="form-control" name="sp_cheque_no" id="" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('reference'); ?></label>
                        <input type="text" class="form-control" name="sp_reference" id="" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('date'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-inline datepicker" name="sp_date" id="" value='<?php echo get_current_date(); ?>' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="sp_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="sp_s_id" id="food_s_id" value=''>
                    <input type="hidden" name="sp_fdps_id" id="food_purchase_id" value=''>
                    <section class=" text-right">
                        <button type="submit" name="submit" class="button button-info submit_button" id="submitButtonFood"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Vaccine Purchase -->
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?= lang('add_supplier_payment'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('payments/insertSupplierPayment') ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('name'); ?></label>
                            <input type="text" class="form-control" id="vaccine_s_name" placeholder="" readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail1"><?= lang('due_amount'); ?></label>
                            <input type="text" class="form-control" id="total_due_amount_vaccine" placeholder="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('amount'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control input__number" name="sp_payment_amount" id="payableAmountVaccine" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"> <?php echo lang('paid_by'); ?><span class="text-danger">*</span></label>
                        <select id="food_paid_by" class="form-control m-bot15" name="sp_paid_by" value='' required>
                            <option value="cash"><?php echo lang('cash'); ?> </option>
                            <option value="cheque"><?php echo lang('cheque'); ?></option>
                            <option value="other"><?php echo lang('other'); ?> </option>
                        </select>
                    </div>
                    <div class="form-group" id="food_cheque_no" style="display: none;">
                        <label for="exampleInputEmail1"> <?php echo lang('cheque_no'); ?></label>
                        <input type="text" class="form-control" name="sp_cheque_no" id="" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?= lang('reference'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="sp_reference" id="" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('date'); ?></label>
                        <input type="text" class="form-control form-control-inline datepicker" name="sp_date" id="" value='<?php echo get_current_date(); ?>' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="sp_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="sp_s_id" id="vaccine_s_id" value=''>
                    <input type="hidden" name="sp_vps_id" id="vaccine_purchase_id" value=''>
                    <section class=" text-right">
                        <button type="submit" name="submit" class="button button-info submit_button" id="submitButtonVaccine"><?php echo lang('submit'); ?></button>
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
        // Livestock Purchase 
        $('#paid_by').on('change', function() {
            if ($(this).val() === "cheque") {
                $("#cheque_no").delay(500).fadeIn(100);
            } else {
                $("#cheque_no").hide()
            }
        });

        // Livestock Purchase 
        $(".addSupplierPayment").click(function(e) {
            var iid = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var purchase_id = $(this).attr('data-purchase-id');
            var total_due_amount = $(this).attr('data-total-due-amount-livestock');
            $("#payableAmount").val('');
            $('#myModal').modal('show');
            $('#s_id').val(iid);
            $('#s_name').val(name);
            $('#purchase_id').val(purchase_id);
            $('#total_due_amount_livestock').val("<?php echo $settings->currency; ?>" + total_due_amount);
            $("#payableAmount").on('keyup change paste', function(e) {
                var payableAmount = $(this).val();
                if (parseFloat(total_due_amount) < parseFloat(payableAmount)) {
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('please_enter_a_valid_value'); ?>"
                    });
                    $('#submitButton').attr('disabled', 'disabled');
                    $("#payableAmount").val('');
                } else {
                    $('#submitButton').removeAttr('disabled');
                }
            });
        });

        // Food Purchase
        $('#food_paid_by').on('change', function() {
            if ($(this).val() === "cheque") {
                $("#food_cheque_no").delay(500).fadeIn(100);
            } else {
                $("#food_cheque_no").hide()
            }
        });
        // Food Purchase
        $(".addFoodSupplierPayment").click(function(e) {
            var iid = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var purchase_id = $(this).attr('data-food-purchase-id');
            var total_due_amount_food = $(this).attr('data-total-due-amount-food');
            $("#payableAmountFood").val('');
            $('#myModal2').modal('show');
            $('#food_s_id').val(iid);
            $('#food_s_name').val(name);
            $('#food_purchase_id').val(purchase_id);
            $('#total_due_amount_food').val("<?php echo $settings->currency; ?>" + total_due_amount_food);

            $("#payableAmountFood").on('keyup change paste', function(e) {
                var payableAmountFood = $(this).val();
                if (parseFloat(total_due_amount_food) < parseFloat(payableAmountFood)) {
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('please_enter_a_valid_value'); ?>"
                    });
                    $('#submitButtonFood').attr('disabled', 'disabled');
                    $("#payableAmountFood").val('');
                } else {
                    $('#submitButtonFood').removeAttr('disabled');
                }
            });
        });


        // Vaccine Purchase
        $('#food_paid_by').on('change', function() {
            if ($(this).val() === "cheque") {
                $("#food_cheque_no").delay(500).fadeIn(100);
            } else {
                $("#food_cheque_no").hide()
            }
        });
        // Vaccine Purchase
        $(".addVaccineSupplierPayment").click(function(e) {
            var iid = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var purchase_id = $(this).attr('data-vaccine-purchase-id');
            var total_due_amount_vaccine = $(this).attr('data-total-due-amount-vaccine');
            $("#payableAmountVaccine").val('');
            $('#myModal3').modal('show');
            $('#vaccine_s_id').val(iid);
            $('#vaccine_s_name').val(name);
            $('#vaccine_purchase_id').val(purchase_id);
            $('#total_due_amount_vaccine').val("<?php echo $settings->currency; ?>" + total_due_amount_vaccine);

            $("#payableAmountVaccine").on('keyup change paste', function(e) {
                var payableAmountVaccine = $(this).val();
                if (parseFloat(total_due_amount_vaccine) < parseFloat(payableAmountVaccine)) {
                    Swal.fire({
                        icon: "warning",
                        title: "<?= lang('warning'); ?>...",
                        text: "<?= lang('please_enter_a_valid_value'); ?>"
                    });
                    $('#submitButtonVaccine').attr('disabled', 'disabled');
                    $("#payableAmountVaccine").val('');
                } else {
                    $('#submitButtonVaccine').removeAttr('disabled');
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