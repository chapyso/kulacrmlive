<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <header class="panel-heading bg-info">
            <i class="fa fa-money"></i> <?= lang('total'); ?> <?= lang('payable'); ?> <?= lang('financial_report'); ?>

            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
        </header>

        <!-- page start-->
        <section class="panel">
            <div class="panel-body card__box">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="25%"><?= lang('category'); ?> <?= lang('name'); ?></th>
                                    <th width="25%"><?= lang('purchase_payable_amount'); ?></th>
                                    <th width="25%"><?= lang('total_paid_amount'); ?></th>
                                    <th width="25%"><?= lang('due_amount'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= lang('livestock_purchase_amount'); ?></td>
                                    <td><?php $livestockPurchasePayableAmount = $this->report_model->getSumData('livestock_purchase_summary', 'purs_grand_total', ['purs_status' => 1]);
                                        if ($livestockPurchasePayableAmount) {
                                            echo $settings->currency . number_format_currency($livestockPurchasePayableAmount, 2);
                                        } else {
                                            echo 0;
                                        }
                                        ?></td>
                                    <td> <?php $livestockPurchasePaidAmount = $this->report_model->getSumData('supplier_payment', 'sp_payment_amount', ['sp_status' => 1, 'sp_purchase_type' => 1]);
                                            if ($livestockPurchasePaidAmount) {
                                                echo $settings->currency . number_format_currency($livestockPurchasePaidAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                    <td><?php $totalLivestockDueAmount = $livestockPurchasePayableAmount - $livestockPurchasePaidAmount;
                                        if ($totalLivestockDueAmount) {
                                            echo $settings->currency . number_format_currency($totalLivestockDueAmount, 2);
                                        }
                                        ?></td>
                                </tr>
                                <tr>
                                    <td><?= lang('food_purchase_amount'); ?></td>
                                    <td><?php $foodPurchasePayableAmount = $this->report_model->getSumData('food_purchase_summary', 'fdps_grand_total', ['fdps_status' => 1]);
                                        if ($foodPurchasePayableAmount) {
                                            echo $settings->currency . number_format_currency($foodPurchasePayableAmount, 2);
                                        } else {
                                            echo 0;
                                        }
                                        ?></td>
                                    <td> <?php $foodPurchasePaidAmount = $this->report_model->getSumData('supplier_payment', 'sp_payment_amount', ['sp_status' => 1, 'sp_purchase_type' => 2]);
                                            if ($foodPurchasePaidAmount) {
                                                echo $settings->currency . number_format_currency($foodPurchasePaidAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                    <td><?php $totalFoodDueAmount = $foodPurchasePayableAmount - $foodPurchasePaidAmount;
                                        if ($totalFoodDueAmount) {
                                            echo $settings->currency . number_format_currency($totalFoodDueAmount, 2);
                                        }
                                        ?></td>
                                </tr>
                                <tr>
                                    <td><?= lang('vaccine_purchase_amount'); ?></td>
                                    <td><?php $vaccinePurchasePayableAmount = $this->report_model->getSumData('vaccine_purchase_summary', 'vps_grand_total', ['vps_status' => 1]);
                                        if ($vaccinePurchasePayableAmount) {
                                            echo $settings->currency . number_format_currency($vaccinePurchasePayableAmount, 2);
                                        } else {
                                            echo 0;
                                        }
                                        ?></td>
                                    <td> <?php $vaccinePurchasePaidAmount = $this->report_model->getSumData('supplier_payment', 'sp_payment_amount', ['sp_status' => 1, 'sp_purchase_type' => 3]);
                                            if ($vaccinePurchasePaidAmount) {
                                                echo $settings->currency . number_format_currency($vaccinePurchasePaidAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                    <td><?php $totalVaccineDueAmount = $vaccinePurchasePayableAmount - $vaccinePurchasePaidAmount;
                                        if ($totalVaccineDueAmount) {
                                            echo $settings->currency . number_format_currency($totalVaccineDueAmount, 2);
                                        }
                                        ?></td>
                                </tr>
                                <tr>
                                    <td><?= lang('staff_payment_amount'); ?></td>
                                    <td></td>
                                    <td> <?php $staffExpenseAmount = $this->report_model->getSumData('staff_payment', 'sfp_payment_amount', ['sfp_status' => 1]);
                                            if ($staffExpenseAmount) {
                                                echo $settings->currency . number_format_currency($staffExpenseAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><?= lang('others_expense_amount'); ?></td>
                                    <td><?php $expensePurchasePayableAmount = $this->report_model->getSumData('expense', 'ex_amount', ['ex_status' => 1]);
                                        if ($expensePurchasePayableAmount) {
                                            echo $settings->currency . number_format_currency($expensePurchasePayableAmount, 2);
                                        } else {
                                            echo 0;
                                        }
                                        ?></td>
                                    <td><?php $expensePurchasePaidAmount = $this->report_model->getSumData('expense_payment', 'exp_paid_amount', ['exp_status' => 1]);
                                        if ($expensePurchasePaidAmount) {
                                            echo $settings->currency . number_format_currency($expensePurchasePaidAmount, 2);
                                        } else {
                                            echo 0;
                                        }
                                        ?></td>
                                    <td><?php $totalExpenseDueAmount = $expensePurchasePayableAmount - $expensePurchasePaidAmount;
                                        if ($totalExpenseDueAmount) {
                                            echo $settings->currency . number_format_currency($totalExpenseDueAmount, 2);
                                        }
                                        ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>


        <header class="panel-heading bg-success">
            <i class="fa fa-money"></i> <?= lang('total'); ?> <?= lang('receivable'); ?> <?= lang('financial_report'); ?>
        </header>
        <section class="panel">
            <div class="panel-body card__box">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="25%"><?= lang('category'); ?> <?= lang('name'); ?></th>
                                    <th width="25%"><?= lang('sold_receivable_amount'); ?></th>
                                    <th width="25%"><?= lang('total_received_amount'); ?></th>
                                    <th width="25%"><?= lang('due_amount'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= lang('livestock_sale_amount'); ?></td>
                                    <td><?php $livestockSoldReceivableAmount = $this->report_model->getSumData('livestock_sale_summary', 'lsss_grand_total', ['lsss_status' => 1]);
                                        if ($livestockSoldReceivableAmount) {
                                            echo $settings->currency . number_format_currency($livestockSoldReceivableAmount, 2);
                                        } else {
                                            echo 0;
                                        }
                                        ?></td>
                                    <td> <?php $livestockSoldReceivedAmount = $this->report_model->getSumData('client_payment', 'cp_received_amount', ['cp_status' => 1, 'cp_sale_type' => 1]);
                                            if ($livestockSoldReceivedAmount) {
                                                echo $settings->currency . number_format_currency($livestockSoldReceivedAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                    <td><?php $totalLivestockSoldDueAmount = $livestockSoldReceivableAmount - $livestockSoldReceivedAmount;
                                        if ($totalLivestockSoldDueAmount) {
                                            echo $settings->currency . number_format_currency($totalLivestockSoldDueAmount, 2);
                                        }
                                        ?></td>
                                </tr>
                                <tr>
                                    <td><?= lang('product_sale_amount'); ?></td>
                                    <td><?php $productSoldReceivableAmount = $this->report_model->getSumData('product_sale_summary', 'prss_grand_total', ['prss_status' => 1]);
                                        if ($productSoldReceivableAmount) {
                                            echo $settings->currency . number_format_currency($productSoldReceivableAmount, 2);
                                        } else {
                                            echo 0;
                                        }
                                        ?></td>
                                    <td> <?php $productSoldReceivedAmount = $this->report_model->getSumData('client_payment', 'cp_received_amount', ['cp_status' => 1, 'cp_sale_type' => 2]);
                                            if ($productSoldReceivedAmount) {
                                                echo $settings->currency . number_format_currency($productSoldReceivedAmount, 2);
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                    <td><?php $totalProductSoldDueAmount = $productSoldReceivableAmount - $productSoldReceivedAmount;
                                        if ($totalProductSoldDueAmount) {
                                            echo $settings->currency . number_format_currency($totalProductSoldDueAmount, 2);
                                        }
                                        ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stock Amount -->
        <div class="row">
            <div class="col-md-12">
                <header class="panel-heading bg-primary">
                    <i class="fa fa-money"></i> <?= lang('total'); ?> <?= lang('finance_report'); ?>
                </header>
                <section class="panel">
                    <div class="panel-body card__box">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="col-xs-4">
                                    <table class="table">
                                        <tr>
                                            <th colspan="2" class="text-center"><?= lang('expense_amount'); ?></th>
                                        </tr>
                                        <tr>
                                            <td><?= lang('total_payable_amount'); ?>:</td>
                                            <td> <?php
                                                    $totalPayableAmount = $livestockPurchasePayableAmount + $foodPurchasePayableAmount + $vaccinePurchasePayableAmount + $staffExpenseAmount + $expensePurchasePayableAmount;
                                                    echo $settings->currency . number_format_currency($totalPayableAmount, 2);
                                                    ?></td>
                                        </tr>
                                        <tr>
                                            <td> <?= lang('total_paid_amount'); ?>:</td>
                                            <td> <?php
                                                    $totalPaidAmount = $livestockPurchasePaidAmount + $foodPurchasePaidAmount + $expensePurchasePaidAmount + $vaccinePurchasePaidAmount + $staffExpenseAmount;
                                                    echo $settings->currency . number_format_currency($totalPaidAmount, 2);
                                                    ?></td>
                                        </tr>
                                        <tr class="table__row">
                                            <td> <?= lang('total'); ?> <?= lang('payable'); ?> <?= lang('due_amount'); ?>:</td>
                                            <td> <?php
                                                    $totalDueAmount = $totalPayableAmount - $totalPaidAmount;
                                                    echo $settings->currency . number_format_currency($totalDueAmount, 2);
                                                    ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-xs-4">
                                    <table class="table">
                                        <tr>
                                            <th colspan="2" class="text-center"><?= lang('income_amount'); ?></th>
                                        </tr>
                                        <tr>
                                            <td><?= lang('total_receivable_amount'); ?></td>
                                            <td>
                                                <?php $totalReceivableAmount = $livestockSoldReceivableAmount + $productSoldReceivableAmount;
                                                echo $settings->currency . number_format_currency($totalReceivableAmount, 2);
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?= lang('total_received_amount'); ?></td>
                                            <td>
                                                <?php $totalReceivedAmount = $livestockSoldReceivedAmount + $productSoldReceivedAmount;
                                                echo $settings->currency . number_format_currency($totalReceivedAmount, 2);
                                                ?>
                                            </td>
                                        </tr>
                                        <tr class="table__row">
                                            <td><?= lang('total'); ?> <?= lang('received_due_amount'); ?></td>
                                            <td>
                                                <?php $totalReceivableDueAmount = $totalReceivableAmount - $totalReceivedAmount;
                                                echo $settings->currency . number_format_currency($totalReceivableDueAmount, 2); ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <!-- Final calculation -->
                                <div class="col-xs-4 table__rows bg-primary-light">
                                    <!-- Cheque Amount -->
                                    <?php
                                    $cashInHand = $totalReceivedAmount - $totalPaidAmount;
                                    $receivedAmountByChequeFromLivestockSale = $this->report_model->getSumData('client_payment', 'cp_received_amount', ['cp_status' => 1, 'cp_sale_type' => 1, 'cp_paid_by' => 'cheque']);
                                    $receivedAmountByChequeFromProductSale = $this->report_model->getSumData('client_payment', 'cp_received_amount', ['cp_status' => 1, 'cp_sale_type' => 2, 'cp_paid_by' => 'cheque']);
                                    $totalReceivedAmountByCheque = $receivedAmountByChequeFromLivestockSale + $receivedAmountByChequeFromProductSale;
                                    ?>
                                    <!-- Others Amount -->
                                    <?php
                                    $receivedAmountByOtherFromLivestockSale = $this->report_model->getSumData('client_payment', 'cp_received_amount', ['cp_status' => 1, 'cp_sale_type' => 1, 'cp_paid_by' => 'other']);
                                    $receivedAmountByOtherFromProductSale = $this->report_model->getSumData('client_payment', 'cp_received_amount', ['cp_status' => 1, 'cp_sale_type' => 2, 'cp_paid_by' => 'other']);
                                    $totalReceivedAmountByOther = $receivedAmountByOtherFromLivestockSale + $receivedAmountByOtherFromProductSale;
                                    ?>
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center" colspan="2"><?= lang('final_calculation'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($cashInHand > 0) { ?>
                                                <tr>
                                                    <td>
                                                        <?= lang('cash_in_hand'); ?> <?= lang('amount'); ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo $settings->currency . number_format_currency(($cashInHand - $totalReceivedAmountByCheque - $totalReceivedAmountByOther), 2);
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <?= lang('cheque'); ?> <?= lang('amount'); ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo $settings->currency . number_format_currency($totalReceivedAmountByCheque, 2);
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <?= lang('other'); ?> <?= lang('amount'); ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo $settings->currency . number_format_currency($totalReceivedAmountByOther, 2);
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> <?= lang('total'); ?> <?= lang('available'); ?> <?= lang('amount'); ?></td>
                                                    <td> <?php
                                                            echo $settings->currency . number_format_currency($cashInHand, 2);
                                                            ?>
                                                    </td>
                                                </tr>
                                            <?php } elseif ($cashInHand == 0) { ?>
                                                <tr>
                                                    <td> <?= lang('total'); ?> <?= lang('available'); ?> <?= lang('amount'); ?></td>
                                                    <td class="bg-warning"> <?php
                                                                            echo $settings->currency . number_format_currency($cashInHand, 2);
                                                                            ?>
                                                    </td>
                                                </tr>
                                            <?php } else { ?>
                                                <tr>
                                                    <td> <?= lang('total'); ?> <?= lang('available'); ?> <?= lang('amount'); ?></td>
                                                    <td class="bg-danger"> <?php
                                                                            echo $settings->currency . number_format_currency($cashInHand, 2);
                                                                            ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.Date to Date Report View -->
                    </div>
                </section>
            </div>
        </div>





        <!-- ======================== Date to date finance report ======================== -->
        <div class="row">
            <div class="col-md-12">
                <header class="panel-heading">
                    <i class="fa fa-money"></i> <?= lang('date_wise_finance_reports'); ?>
                </header>
                <section class="panel">
                    <div class="panel-body card__box">
                        <div class="row">
                            <div class="col-xs-12">
                                <form role="form" action="<?php echo base_url('report/viewFinancialReport'); ?>" method="post" enctype="multipart/form-data">
                                    <div class="col-xs-2">
                                        <span> <?= lang('start_date'); ?>:</span> <br>
                                        <span> <?= $start_date_value; ?></span>
                                    </div>
                                    <div class="col-xs-2">
                                        <span><?= lang('end_date'); ?>:</span><br>
                                        <span> <?= $end_date_value; ?></span>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group col-xs-6">
                                            <input type="text" class="form-control datepicker" name="start_date" value='' placeholder="Select start date" autocomplete="off" required>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <input type="text" class="form-control datepicker" name="end_date" value='' placeholder="select end date" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <section class="text-left">
                                            <button type="submit" name="submit" class="button button-info submit_button"><i class="fa fa-search"></i> <?= lang('search'); ?></button>
                                        </section>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="col-xs-4">
                                    <table class="table">
                                        <tr class="report__card__color">
                                            <th colspan="4" class="text-center"><?= lang('payment_amount_statement'); ?></th>
                                        </tr>
                                        <tr>
                                            <th>#<?= lang('name'); ?></th>
                                            <th><?= lang('total_payable_amount'); ?></th>
                                            <th><?= lang('paid_amount'); ?></th>
                                            <th><?= lang('due_amount'); ?></th>
                                        </tr>
                                        <tr>
                                            <td><?= lang('livestock'); ?></td>
                                            <td><?php $livestockPurchasePayableAmountDateToDate = $this->report_model->getSumData('livestock_purchase_summary', 'purs_grand_total', ['purs_status' => 1, 'DATE(purs_date)>=' => $start_date_value_format, 'DATE(purs_date)<=' => $end_date_value_format]);
                                                if ($livestockPurchasePayableAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($livestockPurchasePayableAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td> <?php $livestockPurchasePaidAmountDateToDate = $this->report_model->getSumData('supplier_payment', 'sp_payment_amount', ['sp_status' => 1, 'sp_purchase_type' => 1, 'DATE(sp_date)>=' => $start_date_value_format, 'DATE(sp_date)<=' => $end_date_value_format]);
                                                    if ($livestockPurchasePaidAmountDateToDate) {
                                                        echo $settings->currency . number_format_currency($livestockPurchasePaidAmountDateToDate, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></td>
                                            <td> <?php $livestockPurchaseDueAmountDateToDate = $livestockPurchasePayableAmountDateToDate - $livestockPurchasePaidAmountDateToDate;
                                                    if ($livestockPurchaseDueAmountDateToDate) {
                                                        echo $settings->currency . number_format_currency($livestockPurchaseDueAmountDateToDate, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></td>
                                        </tr>
                                        <tr>
                                            <td><?= lang('food'); ?></td>
                                            <td><?php $foodPurchasePayableAmountDateToDate = $this->report_model->getSumData('food_purchase_summary', 'fdps_grand_total', ['fdps_status' => 1, 'DATE(fdps_date)>=' => $start_date_value_format, 'DATE(fdps_date)<=' => $end_date_value_format]);
                                                if ($foodPurchasePayableAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($foodPurchasePayableAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td><?php $foodPurchasePaidAmountDateToDate = $this->report_model->getSumData('supplier_payment', 'sp_payment_amount', ['sp_status' => 1, 'sp_purchase_type' => 2, 'DATE(sp_date)>=' => $start_date_value_format, 'DATE(sp_date)<=' => $end_date_value_format]);
                                                if ($foodPurchasePaidAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($foodPurchasePaidAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td> <?php $foodPurchaseDueAmountDateToDate = $foodPurchasePayableAmountDateToDate - $foodPurchasePaidAmountDateToDate;
                                                    if ($foodPurchaseDueAmountDateToDate) {
                                                        echo $settings->currency . number_format_currency($foodPurchaseDueAmountDateToDate, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></td>
                                        </tr>
                                        <tr>
                                            <td><?= lang('vaccine'); ?></td>
                                            <td><?php $vaccinePurchasePayableAmountDateToDate = $this->report_model->getSumData('vaccine_purchase_summary', 'vps_grand_total', ['vps_status' => 1, 'DATE(vps_date)>=' => $start_date_value_format, 'DATE(vps_date)<=' => $end_date_value_format]);
                                                if ($vaccinePurchasePayableAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($vaccinePurchasePayableAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td><?php $vaccinePurchasePaidAmountDateToDate = $this->report_model->getSumData('supplier_payment', 'sp_payment_amount', ['sp_status' => 1, 'sp_purchase_type' => 3, 'DATE(sp_date)>=' => $start_date_value_format, 'DATE(sp_date)<=' => $end_date_value_format]);
                                                if ($vaccinePurchasePaidAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($vaccinePurchasePaidAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td> <?php $vaccinePurchaseDueAmountDateToDate = $vaccinePurchasePayableAmountDateToDate - $vaccinePurchasePaidAmountDateToDate;
                                                    if ($vaccinePurchaseDueAmountDateToDate) {
                                                        echo $settings->currency . number_format_currency($vaccinePurchaseDueAmountDateToDate, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></td>
                                        </tr>
                                        <tr>
                                            <td><?= lang('staff'); ?></td>
                                            <td>0</td>
                                            <td><?php $staffExpenseAmountDateToDate = $this->report_model->getSumData('staff_payment', 'sfp_payment_amount', ['sfp_status' => 1, 'DATE(sfp_date)>=' => $start_date_value_format, 'DATE(sfp_date)<=' => $end_date_value_format]);
                                                if ($staffExpenseAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($staffExpenseAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td>0</td>
                                        </tr>
                                        <tr>
                                            <td><?= lang('other_expense'); ?></td>
                                            <td><?php $expensePurchasePayableAmountDateToDate = $this->report_model->getSumData('expense', 'ex_amount', ['ex_status' => 1, 'DATE(ex_date)>=' => $start_date_value_format, 'DATE(ex_date)<=' => $end_date_value_format]);
                                                if ($expensePurchasePayableAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($expensePurchasePayableAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td><?php $expensePurchasePaidAmountDateToDate = $this->report_model->getSumData('expense_payment', 'exp_paid_amount', ['exp_status' => 1, 'DATE(exp_date)>=' => $start_date_value_format, 'DATE(exp_date)<=' => $end_date_value_format]);
                                                if ($expensePurchasePaidAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($expensePurchasePaidAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td> <?php $expensePurchaseDueAmountDateToDate = $expensePurchasePayableAmountDateToDate - $expensePurchasePaidAmountDateToDate;
                                                    if ($expensePurchaseDueAmountDateToDate) {
                                                        echo $settings->currency . number_format_currency($expensePurchaseDueAmountDateToDate, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></td>
                                        </tr>
                                        <tr class="table__row">
                                            <td><?= lang('purs_grand_total'); ?>: </td>
                                            <td><?php $totalPayableAmountDateToDate = $livestockPurchasePayableAmountDateToDate + $foodPurchasePayableAmountDateToDate + $vaccinePurchasePayableAmountDateToDate + $expensePurchasePayableAmountDateToDate;
                                                if ($totalPayableAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($totalPayableAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?> </td>
                                            <td><?php $totalPaidAmountDateToDate = $livestockPurchasePaidAmountDateToDate + $foodPurchasePaidAmountDateToDate + $vaccinePurchasePaidAmountDateToDate + $staffExpenseAmountDateToDate + $expensePurchasePaidAmountDateToDate;
                                                if ($totalPaidAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($totalPaidAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td> <?php $totalDueAmountDateToDate = $totalPayableAmountDateToDate - $totalPaidAmountDateToDate;
                                                    if ($totalDueAmountDateToDate) {
                                                        echo $settings->currency . number_format_currency($totalDueAmountDateToDate, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-xs-4">
                                    <table class="table">
                                        <tr class="report__card__color">
                                            <th colspan="4" class="text-center"><?= lang('received_amount_statement'); ?></th>
                                        </tr>
                                        <tr>
                                            <th>#<?= lang('name'); ?></th>
                                            <th><?= lang('receivable_amount'); ?></th>
                                            <th><?= lang('received'); ?></th>
                                            <th><?= lang('due'); ?></th>
                                        </tr>
                                        <tr>
                                            <td><?= lang('livestock_sale'); ?></td>
                                            <td><?php $livestockSoldReceivableAmountDateToDate = $this->report_model->getSumData('livestock_sale_summary', 'lsss_grand_total', ['lsss_status' => 1, 'DATE(lsss_date)>=' => $start_date_value_format, 'DATE(lsss_date)<=' => $end_date_value_format]);
                                                if ($livestockSoldReceivableAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($livestockSoldReceivableAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td><?php $livestockSoldReceivedAmountDateToDate = $this->report_model->getSumData('client_payment', 'cp_received_amount', ['cp_status' => 1, 'cp_sale_type' => 1, 'DATE(cp_date)>=' => $start_date_value_format, 'DATE(cp_date)<=' => $end_date_value_format]);
                                                if ($livestockSoldReceivedAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($livestockSoldReceivedAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td> <?php $livestockSoldDueAmountDateToDate = $livestockSoldReceivableAmountDateToDate - $livestockSoldReceivedAmountDateToDate;
                                                    if ($livestockSoldDueAmountDateToDate) {
                                                        echo $settings->currency . number_format_currency($livestockSoldDueAmountDateToDate, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></td>
                                        </tr>
                                        <tr>
                                            <td><?= lang('product_sale'); ?></td>
                                            <td><?php $productSoldReceivableAmountDateToDate = $this->report_model->getSumData('product_sale_summary', 'prss_grand_total', ['prss_status' => 1, 'DATE(prss_date)>=' => $start_date_value_format, 'DATE(prss_date)<=' => $end_date_value_format]);
                                                if ($productSoldReceivableAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($productSoldReceivableAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td> <?php $productSoldReceivedAmountDateToDate = $this->report_model->getSumData('client_payment', 'cp_received_amount', ['cp_status' => 1, 'cp_sale_type' => 2, 'DATE(cp_date)>=' => $start_date_value_format, 'DATE(cp_date)<=' => $end_date_value_format]);
                                                    if ($productSoldReceivedAmountDateToDate) {
                                                        echo $settings->currency . number_format_currency($productSoldReceivedAmountDateToDate, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></td>
                                            <td> <?php $productSoldDueAmountDateToDate = $productSoldReceivableAmountDateToDate - $productSoldReceivedAmountDateToDate;
                                                    if ($productSoldDueAmountDateToDate) {
                                                        echo $settings->currency . number_format_currency($productSoldDueAmountDateToDate, 2);
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?></td>
                                        </tr>
                                        <tr class="table__row">
                                            <td><?= lang('grand_total'); ?>: </td>
                                            <td><?php $totalReceivableAmountDateToDate = $livestockSoldReceivableAmountDateToDate + $productSoldReceivableAmountDateToDate;
                                                if ($totalReceivableAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($totalReceivableAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?> </td>
                                            <td><?php $totalReceivedAmountDateToDate = $livestockSoldReceivedAmountDateToDate + $productSoldReceivedAmountDateToDate;
                                                if ($totalReceivedAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($totalReceivedAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                            <td><?php $totalDueAmountDateToDate = $totalReceivableAmountDateToDate - $totalReceivedAmountDateToDate;
                                                if ($totalDueAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($totalDueAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                }
                                                ?></td>
                                        </tr>
                                    </table>
                                </div>


                                <div class="col-xs-4 table__rows">
                                    <table class="table">
                                        <tr class="report__card__color">
                                            <th colspan="2" class="text-center">#<?= lang('date_to_date_total_amount_statement'); ?></th>
                                        </tr>
                                        <tr>
                                            <td><?= lang('total_paid_amount'); ?></td>
                                            <td><?php
                                                if ($totalPaidAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($totalPaidAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                } ?></td>
                                        </tr>
                                        <tr>
                                            <td><?= lang('total_received_amount'); ?></td>
                                            <td><?php
                                                if ($totalReceivedAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($totalReceivedAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                } ?></td>
                                        </tr>
                                        <tr class="table__row">
                                            <td><?= lang('available_amount'); ?> </td>
                                            <td><?php
                                                $availableAmountDateToDate = $totalReceivedAmountDateToDate - $totalPaidAmountDateToDate;
                                                if ($availableAmountDateToDate) {
                                                    echo $settings->currency . number_format_currency($availableAmountDateToDate, 2);
                                                } else {
                                                    echo 0;
                                                } ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.Date to Date Report View -->
                    </div>
                </section>
            </div>
        </div>




        <!-- page end-->
    </section>
</section>
<!--main content end-->
<!--footer start-->