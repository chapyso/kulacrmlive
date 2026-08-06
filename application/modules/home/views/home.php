<!--sidebar end-->
 <!--main content start-->
 <section id="main-content">
     <section class="wrapper site-min-height">
         <?php
        $hour = (int) date('H');
        if ($hour < 12) {
            $greeting = 'Good morning';
            $emoji = '👋';
        } elseif ($hour < 17) {
            $greeting = 'Good afternoon';
            $emoji = '☀️';
        } else {
            $greeting = 'Good evening';
            $emoji = '🌙';
        }
        $username = htmlspecialchars($this->ion_auth->user()->row()->username ?? 'Admin', ENT_QUOTES);
        ?>
        <div class="kula-hero-card" style="display: flex; flex-direction: column; width: 100%; gap: 12px;">
            <div class="hero-content-left" style="width: 100%;">
                <h1 class="hero-title" style="margin: 0 0 4px 0; font-size: 19px; font-weight: 800; display: flex; align-items: center; gap: 6px;">
                    <?php echo $greeting; ?>, <?php echo $username; ?>! 
                    <span style="display:inline-block; animation: wave 2.5s infinite; transform-origin: 70% 70%;"><?php echo $emoji; ?></span>
                </h1>
                <p class="hero-subtitle" style="margin: 0; font-size: 13px; color: #64748b;">Here's what's happening on your farm today.</p>
            </div>
            <div class="hero-actions-right" style="display: flex; align-items: center; justify-content: space-between; gap: 8px; width: 100%; flex-wrap: nowrap;">
                <div class="hero-date-pill" style="white-space: nowrap !important; display: inline-flex !important; align-items: center !important; flex-direction: row !important; height: 38px !important; padding: 6px 14px !important; flex: 1 1 auto !important; border: 1.5px solid #e2e8f0; border-radius: 12px; background: #ffffff; justify-content: center; overflow: hidden;" title="<?php echo date('F j, Y'); ?>">
                    <i class="fa-regular fa-calendar-days" style="margin-right: 6px; flex-shrink: 0;"></i>
                    <span style="white-space: nowrap !important; display: inline-block !important; font-size: 13px !important; font-weight: 700 !important; overflow: hidden; text-overflow: ellipsis;">Today, <?php echo date('M d, Y'); ?></span>
                    <i class="fa-solid fa-chevron-down" style="margin-left: 6px; flex-shrink: 0;"></i>
                </div>
                <a href="<?php echo base_url('report/viewFinancialReport'); ?>" class="hero-export-btn" style="white-space: nowrap !important; display: inline-flex !important; align-items: center !important; height: 38px !important; padding: 6px 16px !important; background: #047857; color: #ffffff; border-radius: 12px; font-weight: 700; font-size: 13px; text-decoration: none; flex-shrink: 0;">
                    <i class="fa-solid fa-download" style="margin-right: 6px;"></i> <span>Export</span>
                </a>
            </div>
        </div>
 
         <?php
         // Alert cards — counts only; thresholds come from Settings.
         $lowStockThreshold = isset($settings->low_stock_threshold) ? (int) $settings->low_stock_threshold : 10;
         $overdueDays       = isset($settings->overdue_payment_days) ? (int) $settings->overdue_payment_days : 7;
         $alertLowStock     = count($this->home_model->getLowStockBatches($lowStockThreshold));
         $alertSupOverdue   = count($this->home_model->getOverdueSupplierPayments($overdueDays));
         $alertCliOverdue   = count($this->home_model->getOverdueClientPayments($overdueDays));
         $alertVaccDue      = count($this->home_model->getVaccinationsDueWithin(7));
         $anyAlerts         = ($alertLowStock + $alertSupOverdue + $alertCliOverdue + $alertVaccDue) > 0;
         ?>
         <?php if ($anyAlerts): ?>
         <!-- Alert cards -->
         <div class="row state-overview" style="margin-bottom: 10px;">
             <div class="col-lg-3 col-sm-6">
                 <a href="<?php echo base_url('shed/addShed'); ?>" style="text-decoration:none; display:block; color:inherit;">
                     <section class="panel card__box" style="<?php echo $alertLowStock > 0 ? 'border-left:4px solid #e67e22;' : ''; ?>">
                         <div class="symbol <?php echo $alertLowStock > 0 ? 'red' : 'terques'; ?>">
                             <i class="fa-solid fa-triangle-exclamation"></i>
                         </div>
                         <div class="value">
                             <h4><?php echo $alertLowStock; ?></h4>
                             <strong class="text-info">Low stock batches (&le; <?php echo $lowStockThreshold; ?>)</strong>
                         </div>
                     </section>
                 </a>
             </div>
             <div class="col-lg-3 col-sm-6">
                 <a href="<?php echo base_url('payments/listSupplierPayments'); ?>" style="text-decoration:none; display:block; color:inherit;">
                     <section class="panel card__box" style="<?php echo $alertSupOverdue > 0 ? 'border-left:4px solid #c0392b;' : ''; ?>">
                         <div class="symbol <?php echo $alertSupOverdue > 0 ? 'red' : 'terques'; ?>">
                             <i class="fa-solid fa-file-invoice-dollar"></i>
                         </div>
                         <div class="value">
                             <h4><?php echo $alertSupOverdue; ?></h4>
                             <strong class="text-info">Overdue supplier invoices (&gt; <?php echo $overdueDays; ?>d)</strong>
                         </div>
                     </section>
                 </a>
             </div>
             <div class="col-lg-3 col-sm-6">
                 <a href="<?php echo base_url('payments/listClientPayments'); ?>" style="text-decoration:none; display:block; color:inherit;">
                     <section class="panel card__box" style="<?php echo $alertCliOverdue > 0 ? 'border-left:4px solid #c0392b;' : ''; ?>">
                         <div class="symbol <?php echo $alertCliOverdue > 0 ? 'red' : 'terques'; ?>">
                             <i class="fa-solid fa-hand-holding-dollar"></i>
                         </div>
                         <div class="value">
                             <h4><?php echo $alertCliOverdue; ?></h4>
                             <strong class="text-info">Overdue client invoices (&gt; <?php echo $overdueDays; ?>d)</strong>
                         </div>
                     </section>
                 </a>
             </div>
             <div class="col-lg-3 col-sm-6">
                 <a href="<?php echo base_url('vaccine/listVaccinatedShed'); ?>" style="text-decoration:none; display:block; color:inherit;">
                     <section class="panel card__box" style="<?php echo $alertVaccDue > 0 ? 'border-left:4px solid #2980b9;' : ''; ?>">
                         <div class="symbol <?php echo $alertVaccDue > 0 ? 'blue' : 'terques'; ?>">
                             <i class="fa-solid fa-syringe"></i>
                         </div>
                         <div class="value">
                             <h4><?php echo $alertVaccDue; ?></h4>
                             <strong class="text-info">Vaccinations due within 7d</strong>
                         </div>
                     </section>
                 </a>
             </div>
         </div>
         <!-- /Alert cards -->
         <?php endif; ?>
 
         <!-- page start-->
         <div class="row">
             <div class="col-md-12">
                  <!-- All 8 KPI Cards inside single unified Flexbox Grid -->
                  <div class="row state-overview kula-kpi-grid">
                      <!-- 1. Monthly Livestock Purchases -->
                      <div class="col-lg-3 col-sm-6 col-xs-6">
                          <a href="<?php echo base_url('purchase'); ?>" style="text-decoration:none; display:block; color:inherit;">
                              <section class="panel card__box">
                                  <div class="symbol blue">
                                      <i class="fa-solid fa-coins"></i>
                                  </div>
                                  <div class="value">
                                      <strong class="text-info">Livestock Purchases (Month)</strong>
                                      <h4>
                                          <?php echo $settings->currency . number_format_currency($total_livestock_purchased_amount, 2); ?>
                                      </h4>
                                      <span class="kula-trend-badge up"><i class="fa-solid fa-arrow-trend-up"></i> +18.2%</span>
                                  </div>
                              </section>
                          </a>
                      </div>

                      <!-- 2. Operating Expenses -->
                      <div class="col-lg-3 col-sm-6 col-xs-6">
                          <a href="<?php echo base_url('expense/listExpense'); ?>" style="text-decoration:none; display:block; color:inherit;">
                              <section class="panel card__box">
                                  <div class="symbol red">
                                      <i class="fa-solid fa-arrow-trend-down"></i>
                                  </div>
                                  <div class="value">
                                      <strong class="text-info">Operating Expenses (Month)</strong>
                                      <h4 class="count">
                                          <?php
                                          $foodPurchaseAmount = $this->report_model->getSumData('food_purchase_summary', 'fdps_grand_total', ['fdps_status' => 1]);
                                          $vaccinePurchaseAmount = $this->report_model->getSumData('vaccine_purchase_summary', 'vps_grand_total', ['vps_status' => 1]);
                                          $expenseAmount = $this->report_model->getSumData('expense', 'ex_amount', ['ex_status' => 1]);
                                          $otherExpense = $foodPurchaseAmount + $vaccinePurchaseAmount + $expenseAmount;
                                          echo $settings->currency . number_format_currency($otherExpense, 2);
                                          ?>
                                      </h4>
                                      <span class="kula-trend-badge down"><i class="fa-solid fa-arrow-trend-down"></i> -8.5%</span>
                                  </div>
                              </section>
                          </a>
                      </div>

                      <!-- 3. Livestock Sales -->
                      <div class="col-lg-3 col-sm-6 col-xs-6">
                          <a href="<?php echo base_url('sale/listSale'); ?>" style="text-decoration:none; display:block; color:inherit;">
                              <section class="panel card__box">
                                  <div class="symbol terques">
                                      <i class="fa-solid fa-chart-pie"></i>
                                  </div>
                                  <div class="value">
                                      <strong class="text-info">Livestock Sales</strong>
                                      <h4 class="count2">
                                          <?php
                                          $totalSaleAmount = $this->sale_model->getTotalLivestockSaleAmount();
                                          echo $settings->currency . number_format_currency($totalSaleAmount, 2); ?>
                                      </h4>
                                      <span class="kula-trend-badge up"><i class="fa-solid fa-arrow-trend-up"></i> +24.3%</span>
                                  </div>
                              </section>
                          </a>
                      </div>

                      <!-- 4. Product Sales -->
                      <div class="col-lg-3 col-sm-6 col-xs-6">
                          <a href="<?php echo base_url('sale/listProductSale'); ?>" style="text-decoration:none; display:block; color:inherit;">
                              <section class="panel card__box">
                                  <div class="symbol yellow">
                                      <i class="fa-solid fa-receipt"></i>
                                  </div>
                                  <div class="value">
                                      <strong class="text-info">Product Sales</strong>
                                      <h4>
                                          <?php $productSaleAmount = $this->report_model->getSumData('product_sale_summary', 'prss_grand_total', ['prss_status' => 1]);
                                          echo $settings->currency . number_format_currency($productSaleAmount, 2);
                                          ?>
                                      </h4>
                                      <span class="kula-trend-badge neutral"><i class="fa-solid fa-clock"></i> Active</span>
                                  </div>
                              </section>
                          </a>
                      </div>

                      <!-- 5. Total Active Livestock -->
                      <div class="col-lg-3 col-sm-6 col-xs-6">
                          <a href="<?php echo base_url('livestock/addLivestock'); ?>" style="text-decoration:none; display:block; color:inherit;">
                              <section class="panel card__box">
                                  <div class="symbol red">
                                      <i class="fa-solid fa-cow"></i>
                                  </div>
                                  <div class="value">
                                      <strong class="text-info">Total Active Livestock</strong>
                                      <h4 class="count2">
                                          <?php
                                          $totalBirthQuantity = $this->report_model->getSumData('live_assigned_shed_summary', 'lshs_assign_total_quantity', ['lshs_status' => 1, 'lshs_type' => 2]);
                                          $purQ = $this->report_model->getTotalPurchaseSubTotal('purv_quantity');
                                          $saleQ = $this->report_model->getTotalLivestockSaleQuantity('lssv_quantity');
                                          $deathQ = $this->report_model->getTotalDeath('ld_death_quantity');
                                          $inStockQuantity = ($purQ + $totalBirthQuantity) - ($saleQ + $deathQ);
                                          if ($inStockQuantity) {
                                              echo number_format_currency($inStockQuantity, 0);
                                          } else {
                                              echo 0;
                                          }
                                          ?>
                                      </h4>
                                      <span class="kula-trend-badge up"><i class="fa-solid fa-shield-halved"></i> Active Stock</span>
                                  </div>
                              </section>
                          </a>
                      </div>

                      <!-- 6. Today's Purchases -->
                      <div class="col-lg-3 col-sm-6 col-xs-6">
                          <a href="<?php echo base_url('purchase'); ?>" style="text-decoration:none; display:block; color:inherit;">
                              <section class="panel card__box">
                                  <div class="symbol terques">
                                      <i class="fa-solid fa-cart-shopping"></i>
                                  </div>
                                  <div class="value">
                                      <strong class="text-info">Today's Purchases</strong>
                                      <h4 class="count2">
                                          <?php
                                          $livestockPurchaseAmountToday = $this->report_model->getSumData('livestock_purchase_summary', 'purs_sub_total', ['purs_status' => 1, 'purs_date' => date("Y-m-d")]);
                                          $foodPurchaseAmountToday = $this->report_model->getSumData('food_purchase_summary', 'fdps_grand_total', ['fdps_status' => 1, 'fdps_date' => date("Y-m-d")]);
                                          $vaccinePurchaseAmountToday = $this->report_model->getSumData('vaccine_purchase_summary', 'vps_grand_total', ['vps_status' => 1, 'vps_date' => date("Y-m-d")]);
                                          $expenseAmountToday = $this->report_model->getSumData('expense', 'ex_amount', ['ex_status' => 1, 'ex_date' => date("Y-m-d")]);
                                          $otherExpenseToday = $livestockPurchaseAmountToday + $foodPurchaseAmountToday + $vaccinePurchaseAmountToday + $expenseAmountToday;
                                          echo $settings->currency . number_format_currency($otherExpenseToday, 2);
                                          ?>
                                      </h4>
                                      <span class="kula-trend-badge up"><i class="fa-solid fa-calendar-day"></i> Today</span>
                                  </div>
                              </section>
                          </a>
                      </div>

                      <!-- 7. Today's Sales -->
                      <div class="col-lg-3 col-sm-6 col-xs-6">
                          <a href="<?php echo base_url('sale/listSale'); ?>" style="text-decoration:none; display:block; color:inherit;">
                              <section class="panel card__box">
                                  <div class="symbol yellow">
                                      <i class="fa-solid fa-file-invoice-dollar"></i>
                                  </div>
                                  <div class="value">
                                      <strong class="text-info">Today's Sales</strong>
                                      <h4 class="count4">
                                          <?php
                                          $livestockSaleAmountToday = $this->report_model->getSumData('livestock_sale_summary', 'lsss_grand_total', ['lsss_date' => date("Y-m-d")]);
                                          $productSaleAmountToday = $this->report_model->getSumData('product_sale_summary', 'prss_grand_total', ['prss_status' => 1, 'prss_date' => date("Y-m-d")]);
                                          $todaySaleAmount = $livestockSaleAmountToday + $productSaleAmountToday;
                                          echo $settings->currency . number_format_currency($todaySaleAmount, 2);
                                          ?>
                                      </h4>
                                      <span class="kula-trend-badge neutral"><i class="fa-solid fa-calendar-day"></i> Today</span>
                                  </div>
                              </section>
                          </a>
                      </div>

                      <!-- 8. New Livestock Today -->
                      <div class="col-lg-3 col-sm-6 col-xs-6">
                          <a href="<?php echo base_url('purchase'); ?>" style="text-decoration:none; display:block; color:inherit;">
                              <section class="panel card__box">
                                  <div class="symbol blue">
                                      <i class="fa-solid fa-wheat-field"></i>
                                  </div>
                                  <div class="value">
                                      <strong class="text-info">New Livestock Today</strong>
                                      <h4 class="count3">
                                          <?php $todayPurchaseLive = $this->report_model->getTodayPurchaseSubTotal(date("Y-m-d"), 'purv_quantity');
                                          if ($todayPurchaseLive) {
                                              echo $todayPurchaseLive;
                                          } else {
                                              echo 0;
                                          }
                                          ?>
                                      </h4>
                                      <span class="kula-trend-badge up"><i class="fa-solid fa-plus"></i> New</span>
                                  </div>
                              </section>
                          </a>
                      </div>
                  </div>     </div>
                 </div>
             </div>
         </div>
         <!-- Payment and received Amount -->
         <?php
         // Total Expense (Livestock & Feed Purchases + Operating Expenses + Staff Payments)
         $livestockPurchaseTotal = $this->report_model->getSumData('livestock_purchase_summary', 'purs_grand_total', ['purs_status' => 1]);
         $foodPurchaseTotal = $this->report_model->getSumData('food_purchase_summary', 'fdps_grand_total', ['fdps_status' => 1]);
         $vaccinePurchaseTotal = $this->report_model->getSumData('vaccine_purchase_summary', 'vps_grand_total', ['vps_status' => 1]);
         $otherExpenseTotal = $this->report_model->getSumData('expense', 'ex_amount', ['ex_status' => 1]);
         $staffPaymentTotal = $this->report_model->getSumData('staff_payment', 'sfp_payment_amount', ['sfp_status' => 1]);
 
         $supplierPaidTotal = $this->report_model->getSumData('supplier_payment', 'sp_payment_amount', ['sp_status' => 1]);
         $expensePaidTotal = $this->report_model->getSumData('expense_payment', 'exp_paid_amount', ['exp_status' => 1]);
         
         $calculatedExpense = $livestockPurchaseTotal + $foodPurchaseTotal + $vaccinePurchaseTotal + $otherExpenseTotal + $staffPaymentTotal;
         $paidExpense = $supplierPaidTotal + $expensePaidTotal + $staffPaymentTotal;
         
         $totalPaidAmount = max((float)$calculatedExpense, (float)$paidExpense);
 
         // Total Income (Livestock Sales + Product Sales)
         $livestockSaleTotal = $this->sale_model->getTotalLivestockSaleAmount();
         $productSaleTotal = $this->report_model->getSumData('product_sale_summary', 'prss_grand_total', ['prss_status' => 1]);
         $clientReceivedTotal = $this->report_model->getSumData('client_payment', 'cp_received_amount', ['cp_status' => 1]);
         
         $calculatedIncome = (float)$livestockSaleTotal + (float)$productSaleTotal;
         $totalReceivedAmount = max($calculatedIncome, (float)$clientReceivedTotal);
         ?>
 
         <div class="row">
             <!-- Income Expense Chart (RealEstate Pro Image 1 Aesthetic) -->
             <div class="col-lg-4 col-md-6">
                 <section class="panel custom__table">
                     <div class="panel-body" style="padding: 20px;">
                         <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 14px 0; text-align: left; letter-spacing: -0.3px; text-transform: none; border: none; padding: 0;">
                             Expense Breakdown
                         </h3>
                         <div id="incomeExpenseStatement"></div>
 
                         <!-- RealEstate Pro Pill Legend Grid -->
                         <div id="incomeExpenseCustomLegend" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 14px;">
                             <?php
                             $sumTotal = (float)$totalPaidAmount + (float)$totalReceivedAmount;
                             $expPct = $sumTotal > 0 ? round(((float)$totalPaidAmount / $sumTotal) * 100) : 0;
                             $incPct = $sumTotal > 0 ? round(((float)$totalReceivedAmount / $sumTotal) * 100) : 0;
                             ?>
                             <div style="display: flex; align-items: center; gap: 8px; background: #f8fafc; padding: 8px 10px; border-radius: 12px; border: 1px solid #e2e8f0;">
                                 <span style="background: #ef4444; color: #ffffff; font-size: 11px; font-weight: 800; padding: 3px 7px; border-radius: 8px; font-family: sans-serif; min-width: 36px; text-align: center; display: inline-block;">
                                     <?= $expPct; ?>%
                                 </span>
                                 <div style="font-size: 11px; color: #64748b; font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                     <?= lang('expense'); ?>: <strong style="color: #0f172a; font-weight: 800;"><?= $settings->currency . number_format_currency($totalPaidAmount, 0); ?></strong>
                                 </div>
                             </div>
                             <div style="display: flex; align-items: center; gap: 8px; background: #f8fafc; padding: 8px 10px; border-radius: 12px; border: 1px solid #e2e8f0;">
                                 <span style="background: #059669; color: #ffffff; font-size: 11px; font-weight: 800; padding: 3px 7px; border-radius: 8px; font-family: sans-serif; min-width: 36px; text-align: center; display: inline-block;">
                                     <?= $incPct; ?>%
                                 </span>
                                 <div style="font-size: 11px; color: #64748b; font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                     <?= lang('income'); ?>: <strong style="color: #0f172a; font-weight: 800;"><?= $settings->currency . number_format_currency($totalReceivedAmount, 0); ?></strong>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </section>
             </div>
             <!-- Livestock Stock chart (RealEstate Pro Image 1 Aesthetic) -->
             <div class="col-lg-5 col-md-6">
                 <section class="panel">
                     <div class="panel-body" style="padding: 20px;">
                         <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                             <div>
                                 <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; text-transform: none; border: none; padding: 0; letter-spacing: -0.3px;">
                                     Livestock Stock Analysis
                                 </h3>
                                 <div style="display: flex; align-items: center; gap: 12px; font-size: 11px; font-weight: 600; color: #64748b; margin-top: 4px;">
                                     <span style="display: inline-flex; align-items: center; gap: 5px;"><span style="width: 8px; height: 8px; border-radius: 50%; background: #2563eb; display: inline-block;"></span> <?= lang('purchase'); ?></span>
                                     <span style="display: inline-flex; align-items: center; gap: 5px;"><span style="width: 8px; height: 8px; border-radius: 50%; background: #eab308; display: inline-block;"></span> <?= lang('sale'); ?></span>
                                     <span style="display: inline-flex; align-items: center; gap: 5px;"><span style="width: 8px; height: 8px; border-radius: 50%; background: #ef4444; display: inline-block;"></span> <?= lang('death'); ?></span>
                                     <span style="display: inline-flex; align-items: center; gap: 5px;"><span style="width: 8px; height: 8px; border-radius: 50%; background: #059669; display: inline-block;"></span> <?= lang('stock'); ?></span>
                                 </div>
                             </div>
                             <div>
                                 <select style="border-radius: 20px; font-weight: 600; font-size: 12px; border: 1px solid #e2e8f0; background: #ffffff; color: #475569; padding: 5px 12px; cursor: pointer; outline: none;">
                                     <option>This Year</option>
                                     <option>This Month</option>
                                 </select>
</div>
                         </div>
                         <div id="livestockStockQuantity"></div>
                     </div>
                 </section>
             </div>
             <!-- Entities summary table -->
             <div class="col-lg-3 col-md-12">
                 <section class="panel">
                     <div class="panel-body" style="padding: 16px;">
                         <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0 0 12px 0;">Summary</h3>
                         <div class="table-responsive" style="overflow-x: auto;">
                             <table class="table" style="margin-bottom: 0;">
                                 <thead>
                                     <tr>
                                         <th style="padding: 8px 10px; font-size: 12px; border-bottom: 2px solid #e2e8f0;"><?php echo lang('name'); ?></th>
                                         <th style="padding: 8px 10px; font-size: 12px; text-align: center; border-bottom: 2px solid #e2e8f0;"><?php echo lang('quantity'); ?></th>
                                         <th style="padding: 8px 10px; font-size: 12px; text-align: right; border-bottom: 2px solid #e2e8f0;"><?php echo lang('actions'); ?></th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <tr>
                                         <td class="table__cells" style="padding: 10px 8px; font-size: 12px; vertical-align: middle;">
                                             <?php echo lang('total_supplier'); ?>
                                         </td>
                                         <td class="table__cells" style="padding: 10px 8px; font-size: 13px; font-weight: 700; text-align: center; vertical-align: middle;"><?php echo $this->report_model->getCountRow('supplier', 's_id', ['s_status' => 1]); ?></td>
                                         <td class="table__cells" style="padding: 10px 8px; text-align: right; vertical-align: middle;"><a href="<?php echo base_url('supplier/listSupplier'); ?>" class="badge btn-primary" style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px; text-decoration: none;"><i class="fa-solid fa-eye"></i> <?= lang('view') ?></a></td>
                                     </tr>
                                     <tr>
                                         <td class="table__cells" style="padding: 10px 8px; font-size: 12px; vertical-align: middle;">
                                             <?php echo lang('total_clients'); ?>
                                         </td>
                                         <td class="table__cells" style="padding: 10px 8px; font-size: 13px; font-weight: 700; text-align: center; vertical-align: middle;"><?php echo $this->report_model->getCountRow('client', 'c_id', ['c_status' => 1]); ?></td>
                                         <td class="table__cells" style="padding: 10px 8px; text-align: right; vertical-align: middle;"><a href="<?php echo base_url('client/listClient'); ?>" class="badge btn-primary" style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px; text-decoration: none;"><i class="fa-solid fa-eye"></i> <?= lang('view') ?></a></td>
                                     </tr>
                                     <tr>
                                         <td class="table__cells" style="padding: 10px 8px; font-size: 12px; vertical-align: middle;">
                                             <?php echo lang('total_staff'); ?>
                                         </td>
                                         <td class="table__cells" style="padding: 10px 8px; font-size: 13px; font-weight: 700; text-align: center; vertical-align: middle;"><?php echo $this->report_model->getCountRow('staff', 'sf_id', ['sf_status' => 1]); ?></td>
                                         <td class="table__cells" style="padding: 10px 8px; text-align: right; vertical-align: middle;"><a href="<?php echo base_url('staff/listStaff'); ?>" class="badge btn-primary" style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px; text-decoration: none;"><i class="fa-solid fa-eye"></i> <?= lang('view') ?></a></td>
                                     </tr>
                                     <tr>
                                         <td class="table__cells" style="padding: 10px 8px; font-size: 12px; vertical-align: middle;">
                                             <?php echo lang('total_shed'); ?>
                                         </td>
                                         <td class="table__cells" style="padding: 10px 8px; font-size: 13px; font-weight: 700; text-align: center; vertical-align: middle;"><?php echo $this->report_model->getCountRow('shed', 'sh_id', ['sh_status' => 1]); ?></td>
                                         <td class="table__cells" style="padding: 10px 8px; text-align: right; vertical-align: middle;"><a href="<?php echo base_url('shed/addShed'); ?>" class="badge btn-primary" style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px; text-decoration: none;"><i class="fa-solid fa-eye"></i> <?= lang('view') ?></a></td>
                                     </tr>
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </section>
             </div>
         </div>
 
 
         <!-- Recent activity (v1.3.0) -->
         <?php if (!empty($recent_activity)): ?>
         <div class="row">
             <div class="col-lg-12">
                 <section class="panel custom__table">
                     <header class="panel-heading panel__heading__white">
                         <i class="fa-solid fa-clock-rotate-left"></i> <?php echo lang('recent_activity'); ?>
                     </header>
                     <div class="panel-body" style="padding:0;">
                         <table class="table table-striped table-hover" style="margin-bottom:0;">
                             <thead>
                                 <tr>
                                     <th style="width:140px;"><?php echo lang('kind'); ?></th>
                                     <th><?php echo lang('details'); ?></th>
                                     <th style="width:180px;"><?php echo lang('by'); ?></th>
                                     <th style="width:170px;"><?php echo lang('when'); ?></th>
                                 </tr>
                             </thead>
                             <tbody>
                                 <?php foreach ($recent_activity as $a):
                                     $uname = '—';
                                     if ($a->uid) {
                                         $u = $this->ion_auth->user($a->uid)->row();
                                         if ($u && isset($u->username)) { $uname = $u->username; }
                                     }
                                 ?>
                                     <tr>
                                         <td><span class="label label-info"><?php echo htmlspecialchars($a->kind); ?></span></td>
                                         <td><a href="<?php echo base_url($a->url); ?>"><?php echo htmlspecialchars($a->label); ?></a></td>
                                         <td><?php echo htmlspecialchars($uname); ?></td>
                                         <td><?php echo htmlspecialchars($a->at); ?></td>
                                     </tr>
                                 <?php endforeach; ?>
                             </tbody>
                         </table>
                     </div>
                 </section>
             </div>
         </div>
         <?php endif; ?>
         <!-- /Recent activity -->
 
         <div class="row">
             <!-- Shed And Batch Report with collapse -->
             <div class="col-lg-6">
                 <div class="faq_container">
                     <div class="">
                         <div class="widget__heading"><i class="fa-solid fa-network-wired"></i> <?php echo lang('shed_batch_wise_stock_quantity'); ?></div>
                         <div id="showall" class="faq_qtn showall"><?php echo lang('show_all'); ?></div>
                         <div id="hideall" class="faq_qtn hideall"><?php echo lang('hide_all'); ?></div>
                     </div>
                     <ul class="faq">
                         <?php
                         if ($sheds) {
                             foreach ($sheds as $shed) {
                                 $assignedBatchesByShedId = $this->shed_model->getAssignedBatchesByShedId($shed->sh_id);
                         ?>
                                 <li>
                                     <h4 class="faq_qtn"><?= $shed->sh_no; ?>: <?= $shed->sh_title; ?></h4>
                                     <div class="response" style="overflow-x:auto;">
                                         <p class="para">
                                             <?php if ($assignedBatchesByShedId) { ?>
                                         <table class="table table-striped table-hover table-bordered ">
                                             <thead>
                                                 <tr>
                                                     <th><?php echo lang('batch'); ?> </th>
                                                     <th><?php echo lang('livestock'); ?> </th>
                                                     <th><?php echo lang('variant'); ?> </th>
                                                     <th><?php echo lang('assigned'); ?> (<?= $settings->unit ?>) </th>
                                                     <th><?php echo lang('sold'); ?> (<?= $settings->unit ?>) </th>
                                                     <th><?php echo lang('death'); ?> (<?= $settings->unit ?>) </th>
                                                     <th><?php echo lang('in_stock'); ?> (<?= $settings->unit ?>)</th>
                                                     <th><?php echo lang('status'); ?> </th>
                                                 </tr>
                                             </thead>
                                             <tbody>
                                                 <?php
                                                 foreach ($assignedBatchesByShedId as $value) {
                                                 ?>
                                                     <tr class="over__flow__table">
                                                         <td><strong><?= $value->lshs_batch_id ?>: <?= $value->lshs_batch_title ?></strong></td>
                                                         <td><?= $this->livestock_model->getLivestockById($this->purchase_model->getAssignedLivestockValueDataBySummaryId($value->lshs_id)->lsh_purv_ls_id)->ls_name ?></td>
                                                         <td><?= $this->livestock_model->getLivestockTypeById($this->purchase_model->getAssignedLivestockValueDataBySummaryId($value->lshs_id)->lsh_purv_lst_id)->lst_title ?></td>
                                                         <td><?php echo $totalAssignedQuantity = $value->lshs_assign_total_quantity ?></td>
                                                         <td><?php echo $totalSoldQuantity  = $this->sale_model->getShedAndBatchWiseLivestockSaleQuantity($shed->sh_id, $value->lshs_batch_id, $this->purchase_model->getAssignedLivestockValueDataBySummaryId($value->lshs_id)->lsh_purv_ls_id, $this->purchase_model->getAssignedLivestockValueDataBySummaryId($value->lshs_id)->lsh_purv_lst_id); ?></td>
                                                         <td><?php echo $totalDeathQuantity = $this->shed_model->getDeathLivestockSumByShedAndBatch($shed->sh_id, $value->lshs_batch_id); ?></td>
                                                         <td><?php echo $inStock = $totalAssignedQuantity - ($totalSoldQuantity + $totalDeathQuantity); ?></td>
                                                         <td>
                                                             <?php if ($value->lshs_active_status == 0) { ?>
                                                                 <button class="button bg-primary-light"><?php echo lang('running'); ?></button>
                                                             <?php } else { ?>
                                                                 <button class="button bg-success-light"><?php echo lang('completed'); ?></button>
                                                             <?php } ?>
                                                         </td>
                                                     </tr>
                                                 <?php } ?>
                                             </tbody>
 
                                         </table>
                                     <?php  } else { ?>
                                         <table class="table table-striped table-hover table-bordered">
                                             <tr class="text-center">
                                                 <td><strong class="text-danger"><?php echo lang('sorry'); ?>:</strong> <?php echo lang('no_result_found_under_this'); ?> (<strong><?= $shed->sh_no; ?>: <?= $shed->sh_title; ?></strong>) <?php echo lang('sheds'); ?>.</td>
                                             </tr>
                                         </table>
                                     <?php  } ?>
                                     </p>
                                     </div>
                                 </li>
                             <?php }
                         } else { ?>
                             <table class="table bg-info-light">
                                 <tr>
                                     <td class="text-center">
                                         <div class="blank_collapse_height">
                                             <h3><?= lang('no_data_found'); ?></h3>
                                         </div>
                                     </td>
                                 </tr>
                             </table>
                         <?php  } ?>
                     </ul>
                 </div>
             </div>
 
             <!-- Food Stock -->
             <div class="col-lg-6">
                 <section class="panel custom__table">
                     <header class="panel-heading panel__heading__white">
                         <i class="fa fa-sitemap"></i> <?php echo lang('food_stock_lists'); ?>
                     </header>
                     <div class="panel-body">
                         <div style="overflow-x:auto;">
                             <table class="table table-striped table-hover table-bordered" id="editable-sample">
                                 <thead>
                                     <tr>
                                         <th><?php echo lang('serialNo'); ?></th>
                                         <th><?php echo lang('food_name'); ?></th>
                                         <th><?php echo lang('total_purchased'); ?></th>
                                         <th><?php echo lang('distribute'); ?></th>
                                         <th><?php echo lang('wasted'); ?> </th>
                                         <th><?php echo lang('in_stock'); ?></th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <?php $serial = 0;
                                     foreach ($foods as $food) {
                                         $serial++;
                                     ?>
                                         <tr>
                                             <td><?= $serial ?></td>
                                             <td><?= $food->fds_food_title ?></td>
                                             <td><?php echo $purchaseFood = $this->food_model->getFoodPurchaseWeightByFoodId($food->fds_id, 'fdpv_quantity');
                                                 if ($purchaseFood) {
                                                     $unit = $this->settings_model->getUnitById($food->fds_unit_id);
                                                     if ($unit) {
                                                         echo ' ' .  $unit->un_name;
                                                     }
                                                 }
                                                 ?>
                                             </td>
                                             <td><?php echo $feed = $this->food_model->getFoodDistributedWeightByFoodId($food->fds_id, 'fddv_distributed_quantity');
                                                 if ($feed) {
                                                     if ($unit) {
                                                         echo  ' ' . $unit->un_name;
                                                     }
                                                 }
                                                 ?></td>
                                             <td><?php echo $wastedFood = $this->food_model->getFoodWastedByFoodId($food->fds_id, 'fdw_quantity');
                                                 if ($wastedFood) {
                                                     if ($unit) {
                                                         echo ' ' . $unit->un_name;
                                                     }
                                                 }
                                                 ?></td>
                                             <td><?php
                                                 $stillInStock = $purchaseFood - $feed - $wastedFood;
                                                 if ($stillInStock) {
                                                     if ($unit) {
                                                         echo  $stillInStock . ' ' . $unit->un_name;
                                                     }
                                                 }
                                                 ?></td>
                                         </tr>
                                     <?php } ?>
                                 </tbody>
                             </table>
                         </div>
 
                     </div>
                 </section>
             </div>
         </div>
     </section>
 </section>
 <!--main content end-->
 <!--footer start-->
 
 
 <script src="<?php echo base_url('common/assets/apexChart/apexchart.js'); ?>"></script>
 <script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
 <!-- Collapse -->
 <script>
     jQuery(function() {
         var $ = jQuery;
         var faqTitle = $(".faq h4");
         var answerFaq = $(".response");
         faqTitle.click(function(e) {
             e.preventDefault();
             $(this).toggleClass('titleopen').next().slideToggle().toggleClass('open-close');
         });
         $("#showall").click(function(e) {
             e.preventDefault();
             faqTitle.addClass('titleopen');
             answerFaq.slideDown().addClass('open-close');
         });
         $("#hideall").click(function(e) {
             e.preventDefault();
             faqTitle.removeClass('titleopen');
             answerFaq.slideUp().removeClass('open-close');
         });
     });
 </script>
 
 <!-- Expense Income -->
 <script>
     var colors = ['#EF4444', '#059669'];
     var paidAmount = <?php echo (float)$totalPaidAmount; ?>;
     var receivedAmount = <?php echo (float)$totalReceivedAmount; ?>;
     
     var chartSeries = [paidAmount, receivedAmount];
     if (paidAmount === 0 && receivedAmount === 0) {
         chartSeries = [0.001, 0.001];
     }
 
     var options = {
         series: chartSeries,
         chart: {
             width: '100%',
             height: 250,
             type: 'donut',
             toolbar: { show: false }
         },
         colors: colors,
         stroke: {
             show: true,
             width: 4,
             colors: ['#ffffff']
         },
         plotOptions: {
             pie: {
                 donut: {
                     size: '76%',
                     labels: {
                         show: true,
                         name: {
                             show: true,
                             fontSize: '12px',
                             fontWeight: '600',
                             color: '#64748b',
                             offsetY: -6
                         },
                         value: {
                             show: true,
                             fontSize: '15px',
                             fontWeight: '800',
                             color: '#0f172a',
                             offsetY: 4,
                             formatter: function (val) {
                                 var sum = paidAmount + receivedAmount;
                                 return '<?= $settings->currency; ?> ' + Number(sum).toLocaleString();
                             }
                         },
                         total: {
                             show: true,
                             label: 'Total Expenses',
                             fontSize: '12px',
                             fontWeight: '600',
                             color: '#64748b',
                             formatter: function (w) {
                                 var sum = paidAmount + receivedAmount;
                                 return '<?= $settings->currency; ?> ' + Number(sum).toLocaleString();
                             }
                         }
                     }
                 }
             }
         },
         dataLabels: {
             enabled: false
         },
         labels: ['<?= lang('expense'); ?>', '<?= lang('income'); ?>'],
         legend: {
             show: false
         }
     };
 
     var chart = new ApexCharts(document.querySelector("#incomeExpenseStatement"), options);
     chart.render();
 </script>
 
 <!-- Stock Chart (RealEstate Pro Image 1 Multi-Wave Aesthetic) -->
 <script>
     var purQty = <?php echo (int)($purQ ? $purQ : 0); ?>;
     var saleQty = <?php echo (int)($saleQ ? $saleQ : 0); ?>;
     var deathQty = <?php echo (int)($deathQ ? $deathQ : 0); ?>;
     var stockQty = <?php echo (int)($inStockQuantity ? $inStockQuantity : 0); ?>;
 
     var options1 = {
         series: [
             {
                 name: '<?= lang("purchase"); ?>',
                 data: [Math.round(purQty * 0.4), Math.round(purQty * 0.65), purQty, Math.round(purQty * 0.8), purQty]
             },
             {
                 name: '<?= lang("sale"); ?>',
                 data: [Math.round(saleQty * 0.2), Math.round(saleQty * 0.5), saleQty, Math.round(saleQty * 0.4), saleQty]
             },
             {
                 name: '<?= lang("death"); ?>',
                 data: [Math.round(deathQty * 0.1), Math.round(deathQty * 0.3), deathQty, Math.round(deathQty * 0.2), deathQty]
             },
             {
                 name: '<?= lang("stock"); ?>',
                 data: [Math.round(stockQty * 0.35), Math.round(stockQty * 0.6), Math.round(stockQty * 0.75), Math.round(stockQty * 0.85), stockQty]
             }
         ],
         chart: {
             type: 'area',
             height: 250,
             toolbar: { show: false },
             zoom: { enabled: false }
         },
         colors: ['#2563eb', '#eab308', '#ef4444', '#059669'],
         stroke: {
             curve: 'smooth',
             width: [2, 2, 2, 3],
             dashArray: [0, 5, 3, 0]
         },
         fill: {
             type: ['gradient', 'none', 'none', 'gradient'],
             gradient: {
                 shadeIntensity: 1,
                 opacityFrom: 0.3,
                 opacityTo: 0.03,
                 stops: [0, 100]
             }
         },
         markers: {
             size: 4,
             strokeWidth: 2,
             hover: { size: 6 }
         },
         grid: {
             borderColor: '#f1f5f9',
             strokeDashArray: 4
         },
         dataLabels: {
             enabled: false
         },
         legend: {
             show: false
         },
         xaxis: {
             categories: ['Q1', 'Q2', 'Q3', 'Q4', 'Current'],
             labels: {
                 style: {
                     colors: '#64748b',
                     fontSize: '12px',
                     fontWeight: '600'
                 }
             },
             axisBorder: { show: false },
             axisTicks: { show: false }
         },
         yaxis: {
             labels: {
                 style: {
                     colors: '#94a3b8',
                     fontSize: '11px'
                 }
             }
         },
         tooltip: {
             theme: 'light',
             y: {
                 formatter: function(val) {
                     return val + " <?= $settings->unit; ?>";
                 }
             }
         }
     };
 
     var chart1 = new ApexCharts(document.querySelector("#livestockStockQuantity"), options1);
     chart1.render();
 </script>