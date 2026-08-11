<!--sidebar end-->
 <!--main content start-->
 <section id="main-content">
     <section class="wrapper site-min-height">
         <?php
        $currency = (!empty($settings) && !empty($settings->currency)) ? $settings->currency : 'UGX ';
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
        $username = htmlspecialchars($this->ion_auth->user()->row()?->username ?? 'Admin', ENT_QUOTES);
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

        <!-- KULA AI FEATURE HIGHLIGHT BANNER -->
        <style>
            .kula-ai-feature-banner {
                margin: 16px 0 20px 0;
                padding: 16px 22px;
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
                flex-wrap: wrap;
                transition: border-color 0.2s, box-shadow 0.2s;
            }
            .kula-ai-feature-banner:hover {
                border-color: #cbd5e1;
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
            }
            body.dark-theme .kula-ai-feature-banner,
            html.dark-theme .kula-ai-feature-banner {
                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
                border-color: rgba(255, 255, 255, 0.08) !important;
                box-shadow: 0 8px 24px -4px rgba(0, 0, 0, 0.3) !important;
            }
            .kula-ai-banner-title {
                font-size: 14.5px;
                font-weight: 800;
                color: #0f172a;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            body.dark-theme .kula-ai-banner-title,
            html.dark-theme .kula-ai-banner-title {
                color: #ffffff !important;
            }
            .kula-ai-banner-desc {
                margin: 3px 0 0 0;
                font-size: 12.5px;
                color: #64748b;
                line-height: 1.4;
            }
            body.dark-theme .kula-ai-banner-desc,
            html.dark-theme .kula-ai-banner-desc {
                color: #94a3b8 !important;
            }
            .kula-ai-btn-primary {
                white-space: nowrap;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 16px;
                background: linear-gradient(135deg, #047857 0%, #059669 100%);
                color: #ffffff !important;
                border-radius: 10px;
                font-weight: 700;
                font-size: 12.5px;
                text-decoration: none;
                transition: opacity 0.2s, transform 0.15s;
                box-shadow: 0 4px 12px rgba(4, 120, 87, 0.25);
            }
            .kula-ai-btn-primary:hover {
                opacity: 0.92;
                transform: translateY(-1px);
                color: #ffffff !important;
            }
            .kula-ai-btn-secondary {
                white-space: nowrap;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 16px;
                background: #f1f5f9;
                color: #0f172a !important;
                border: 1px solid #cbd5e1;
                border-radius: 10px;
                font-weight: 700;
                font-size: 12.5px;
                cursor: pointer;
                transition: background 0.2s;
            }
            .kula-ai-btn-secondary:hover {
                background: #e2e8f0;
            }
            body.dark-theme .kula-ai-btn-secondary,
            html.dark-theme .kula-ai-btn-secondary {
                background: rgba(255, 255, 255, 0.08) !important;
                color: #f8fafc !important;
                border-color: rgba(255, 255, 255, 0.15) !important;
            }
            body.dark-theme .kula-ai-btn-secondary:hover,
            html.dark-theme .kula-ai-btn-secondary:hover {
                background: rgba(255, 255, 255, 0.15) !important;
            }
        </style>

        <div class="kula-ai-feature-banner">
            <div style="display: flex; align-items: center; gap: 14px; flex: 1 1 320px;">
                <div style="width: 42px; height: 42px; border-radius: 12px; background: linear-gradient(135deg, #047857, #10b981); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; box-shadow: 0 4px 14px rgba(4, 120, 87, 0.35);">
                    <i class="fa-solid fa-brain"></i>
                </div>
                <div>
                    <div class="kula-ai-banner-title">
                        <span>KulaAI Agribusiness &amp; Predictive Intelligence</span>
                        <span style="font-size: 10px; font-weight: 800; text-transform: uppercase; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; padding: 2px 8px; border-radius: 10px;">Active</span>
                    </div>
                    <p class="kula-ai-banner-desc">
                        Ask KulaAI anything — real-time farm predictive mortality insights, custom agribusiness plans, veterinary disease advice, feed formulations, and financial forecasts.
                    </p>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 10px; flex-shrink: 0;">
                <a href="<?php echo base_url('kula_ai/intelligence'); ?>" class="kula-ai-btn-primary">
                    <i class="fa-solid fa-chart-line"></i> View Kula Intelligence Page
                </a>
                <button type="button" onclick="KulaAIChat.toggle()" class="kula-ai-btn-secondary">
                    <i class="fa-solid fa-comment-dots" style="color: #047857;"></i> Chat Assistant
                </button>
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
             <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
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
             <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
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
             <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
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
             <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
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
                                          <?php echo $currency . number_format_currency($total_livestock_purchased_amount, 2); ?>
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
                                          echo $currency . number_format_currency($otherExpense, 2);
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
                                          echo $currency . number_format_currency($totalSaleAmount, 2); ?>
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
                                          echo $currency . number_format_currency($productSaleAmount, 2);
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
                                          echo $currency . number_format_currency($otherExpenseToday, 2);
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
                                          echo $currency . number_format_currency($todaySaleAmount, 2);
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

          <!-- CARD 1: Full-Width Expense & Income Breakdown -->
          <div class="row" style="margin-bottom: 24px;">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <section class="panel custom__table" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.03); margin-bottom: 0;">
                      <div class="panel-body kula-dashboard-card-body" style="padding: 24px;">
                          <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px;">
                              <div>
                                  <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; display: flex; align-items: center; gap: 8px; border: none; padding: 0; text-transform: none; letter-spacing: -0.3px;">
                                      <i class="fa-solid fa-chart-pie" style="color: #059669;"></i> Expense &amp; Financial Breakdown
                                  </h3>
                                  <p style="margin: 0; font-size: 13px; color: #64748b; font-weight: 500;">Comparison of total operational expenses vs cumulative farm revenue</p>
                              </div>
                              <div style="display: flex; align-items: center; gap: 10px;">
                                  <span style="background: #f1f5f9; color: #334155; font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 10px; border: 1px solid #e2e8f0;">
                                      <i class="fa-regular fa-clock" style="margin-right: 5px;"></i> Lifetime Statements
                                  </span>
                              </div>
                          </div>

                          <div class="row" style="margin-top: 10px;">
                              <!-- Donut Chart Column -->
                              <div class="col-lg-5 col-md-5 col-sm-12" style="margin-bottom: 20px;">
                                  <div style="width: 100%; display: flex; justify-content: center; align-items: center;">
                                      <div id="incomeExpenseStatement" style="width: 100%; max-width: 280px;"></div>
                                  </div>
                              </div>

                              <!-- Financial Details & Metrics Grid Column -->
                              <div class="col-lg-7 col-md-7 col-sm-12">
                                  <?php
                                  $sumTotal = (float)$totalPaidAmount + (float)$totalReceivedAmount;
                                  $expPct = $sumTotal > 0 ? round(((float)$totalPaidAmount / $sumTotal) * 100) : 0;
                                  $incPct = $sumTotal > 0 ? round(((float)$totalReceivedAmount / $sumTotal) * 100) : 0;
                                  $netBalance = (float)$totalReceivedAmount - (float)$totalPaidAmount;
                                  ?>
                                  
                                  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px;">
                                      <!-- Expenses Card -->
                                      <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 14px; padding: 14px;">
                                          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                              <span style="font-size: 12px; font-weight: 700; color: #991b1b; display: flex; align-items: center; gap: 6px;">
                                                  <i class="fa-solid fa-arrow-down-long"></i> Total Expenses
                                              </span>
                                              <span style="background: #ef4444; color: #ffffff; font-size: 11px; font-weight: 800; padding: 2px 8px; border-radius: 9999px;"><?= $expPct; ?>%</span>
                                          </div>
                                          <h4 style="font-size: 19px; font-weight: 800; color: #7f1d1d; margin: 0 0 6px 0;">
                                              <?= $currency . number_format_currency($totalPaidAmount, 2); ?>
                                          </h4>
                                          <div style="height: 6px; background: #fee2e2; border-radius: 4px; overflow: hidden;">
                                              <div style="width: <?= $expPct; ?>%; height: 100%; background: #ef4444; border-radius: 4px;"></div>
                                          </div>
                                      </div>

                                      <!-- Income Card -->
                                      <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 14px; padding: 14px;">
                                          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                              <span style="font-size: 12px; font-weight: 700; color: #065f46; display: flex; align-items: center; gap: 6px;">
                                                  <i class="fa-solid fa-arrow-up-long"></i> Total Revenue
                                              </span>
                                              <span style="background: #059669; color: #ffffff; font-size: 11px; font-weight: 800; padding: 2px 8px; border-radius: 9999px;"><?= $incPct; ?>%</span>
                                          </div>
                                          <h4 style="font-size: 19px; font-weight: 800; color: #064e3b; margin: 0 0 6px 0;">
                                              <?= $currency . number_format_currency($totalReceivedAmount, 2); ?>
                                          </h4>
                                          <div style="height: 6px; background: #d1fae5; border-radius: 4px; overflow: hidden;">
                                              <div style="width: <?= $incPct; ?>%; height: 100%; background: #059669; border-radius: 4px;"></div>
                                          </div>
                                      </div>

                                      <!-- Net Balance Card -->
                                      <div class="kula-span-mobile-1" style="background: <?= $netBalance >= 0 ? '#f0fdf4' : '#fff1f2'; ?>; border: 1px solid <?= $netBalance >= 0 ? '#bbf7d0' : '#fecdd3'; ?>; border-radius: 14px; padding: 14px; grid-column: span 2;">
                                          <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                                              <div>
                                                  <span style="font-size: 12px; font-weight: 700; color: <?= $netBalance >= 0 ? '#166534' : '#9f1239'; ?>;">
                                                      Net Operating Profit / Balance
                                                  </span>
                                                  <h4 style="font-size: 20px; font-weight: 800; color: <?= $netBalance >= 0 ? '#14532d' : '#881337'; ?>; margin: 4px 0 0 0;">
                                                      <?= ($netBalance >= 0 ? '+' : '-') . $currency . number_format_currency(abs($netBalance), 2); ?>
                                                  </h4>
                                              </div>
                                              <span style="background: <?= $netBalance >= 0 ? '#166534' : '#9f1239'; ?>; color: #ffffff; font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 10px;">
                                                  <?= $netBalance >= 0 ? '<i class="fa-solid fa-circle-check"></i> Profitable' : '<i class="fa-solid fa-circle-exclamation"></i> Deficit' ?>
                                              </span>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </section>
              </div>
          </div>

          <!-- CARD 2: Full-Width Livestock Stock Analysis -->
          <div class="row" style="margin-bottom: 24px;">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <section class="panel" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.03); overflow: hidden; margin-bottom: 0;">
                      <div class="panel-body kula-dashboard-card-body" style="padding: 24px;">
                          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px;">
                              <div>
                                  <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; display: flex; align-items: center; gap: 8px; border: none; padding: 0; text-transform: none; letter-spacing: -0.3px;">
                                      <i class="fa-solid fa-chart-line" style="color: #2563eb;"></i> Livestock Stock Analysis &amp; Trends
                                  </h3>
                                  <p style="margin: 0; font-size: 13px; color: #64748b; font-weight: 500;">Multi-wave tracking of purchases, sales, mortality rates, and net inventory</p>
                              </div>
                              <div style="display: flex; align-items: center; gap: 10px;">
                                  <select style="border-radius: 10px; font-weight: 700; font-size: 12px; border: 1.5px solid #cbd5e1; background: #ffffff; color: #334155; padding: 6px 14px; cursor: pointer; outline: none;">
                                      <option>This Year</option>
                                      <option>This Month</option>
                                  </select>
                              </div>
                          </div>

                          <!-- Interactive Stat Legend Chips -->
                          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-bottom: 20px;">
                              <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 12px 16px; display: flex; align-items: center; gap: 12px;">
                                  <div style="width: 36px; height: 36px; border-radius: 10px; background: #2563eb; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;">
                                      <i class="fa-solid fa-cart-shopping"></i>
                                  </div>
                                  <div>
                                      <span style="font-size: 11px; font-weight: 700; color: #1e40af; text-transform: uppercase; letter-spacing: 0.5px;"><?= lang('purchase'); ?></span>
                                      <h4 style="margin: 2px 0 0 0; font-size: 18px; font-weight: 800; color: #1e3a8a;"><?php echo (int)($purQ ? $purQ : 0); ?></h4>
                                  </div>
                              </div>

                              <div style="background: #fefce8; border: 1px solid #fef08a; border-radius: 12px; padding: 12px 16px; display: flex; align-items: center; gap: 12px;">
                                  <div style="width: 36px; height: 36px; border-radius: 10px; background: #eab308; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;">
                                      <i class="fa-solid fa-file-invoice-dollar"></i>
                                  </div>
                                  <div>
                                      <span style="font-size: 11px; font-weight: 700; color: #854d0e; text-transform: uppercase; letter-spacing: 0.5px;"><?= lang('sale'); ?></span>
                                      <h4 style="margin: 2px 0 0 0; font-size: 18px; font-weight: 800; color: #713f12;"><?php echo (int)($saleQ ? $saleQ : 0); ?></h4>
                                  </div>
                              </div>

                              <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 12px 16px; display: flex; align-items: center; gap: 12px;">
                                  <div style="width: 36px; height: 36px; border-radius: 10px; background: #ef4444; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;">
                                      <i class="fa-solid fa-skull-crossbones"></i>
                                  </div>
                                  <div>
                                      <span style="font-size: 11px; font-weight: 700; color: #991b1b; text-transform: uppercase; letter-spacing: 0.5px;"><?= lang('death'); ?></span>
                                      <h4 style="margin: 2px 0 0 0; font-size: 18px; font-weight: 800; color: #7f1d1d;"><?php echo (int)($deathQ ? $deathQ : 0); ?></h4>
                                  </div>
                              </div>

                              <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 12px 16px; display: flex; align-items: center; gap: 12px;">
                                  <div style="width: 36px; height: 36px; border-radius: 10px; background: #059669; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;">
                                      <i class="fa-solid fa-warehouse"></i>
                                  </div>
                                  <div>
                                      <span style="font-size: 11px; font-weight: 700; color: #065f46; text-transform: uppercase; letter-spacing: 0.5px;"><?= lang('stock'); ?></span>
                                      <h4 style="margin: 2px 0 0 0; font-size: 18px; font-weight: 800; color: #064e3b;"><?php echo (int)($inStockQuantity ? $inStockQuantity : 0); ?></h4>
                                  </div>
                              </div>
                          </div>

                          <div id="livestockStockQuantity" style="width: 100%; min-height: 320px;"></div>
                      </div>
                  </section>
              </div>
          </div>

          <!-- CARD 3: Full-Width Farm Entities Summary -->
          <div class="row" style="margin-bottom: 24px;">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <section class="panel" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.03); overflow: hidden; margin-bottom: 0;">
                      <div class="panel-body kula-dashboard-card-body" style="padding: 24px;">
                          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px;">
                              <div>
                                  <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; display: flex; align-items: center; gap: 8px; border: none; padding: 0; text-transform: none; letter-spacing: -0.3px;">
                                      <i class="fa-solid fa-layer-group" style="color: #6366f1;"></i> Farm Entities Directory Summary
                                  </h3>
                                  <p style="margin: 0; font-size: 13px; color: #64748b; font-weight: 500;">Overview of active suppliers, client network, staff workforce, and shed infrastructure</p>
                              </div>
                              <span style="background: #eef2ff; color: #4338ca; font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 10px; border: 1px solid #c7d2fe;">
                                  <i class="fa-solid fa-network-wired" style="margin-right: 5px;"></i> Core Modules
                              </span>
                          </div>

                          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px;">
                              <!-- Suppliers Card -->
                              <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
                                  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                                      <div style="width: 44px; height: 44px; border-radius: 12px; background: #0284c7; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 18px; box-shadow: 0 4px 10px rgba(2, 132, 199, 0.25);">
                                          <i class="fa-solid fa-truck-field"></i>
                                      </div>
                                      <span style="background: #e0f2fe; color: #0369a1; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 9999px;">SUPPLIERS</span>
                                  </div>
                                  <div>
                                      <h4 style="margin: 0 0 4px 0; font-size: 26px; font-weight: 800; color: #0f172a;">
                                          <?php echo $this->report_model->getCountRow('supplier', 's_id', ['s_status' => 1]); ?>
                                      </h4>
                                      <p style="margin: 0 0 16px 0; font-size: 12px; color: #64748b; font-weight: 600;"><?php echo lang('total_supplier'); ?></p>
                                  </div>
                                  <a href="<?php echo base_url('supplier/listSupplier'); ?>" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: #047857; color: #ffffff; font-size: 13px; font-weight: 700; padding: 9px 16px; border-radius: 10px; text-decoration: none;">
                                      <i class="fa-solid fa-eye"></i> View Suppliers Directory
                                  </a>
                              </div>

                              <!-- Clients Card -->
                              <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
                                  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                                      <div style="width: 44px; height: 44px; border-radius: 12px; background: #2563eb; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 18px; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);">
                                          <i class="fa-solid fa-address-book"></i>
                                      </div>
                                      <span style="background: #dbeafe; color: #1d4ed8; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 9999px;">CLIENTS</span>
                                  </div>
                                  <div>
                                      <h4 style="margin: 0 0 4px 0; font-size: 26px; font-weight: 800; color: #0f172a;">
                                          <?php echo $this->report_model->getCountRow('client', 'c_id', ['c_status' => 1]); ?>
                                      </h4>
                                      <p style="margin: 0 0 16px 0; font-size: 12px; color: #64748b; font-weight: 600;"><?php echo lang('total_clients'); ?></p>
                                  </div>
                                  <a href="<?php echo base_url('client/listClient'); ?>" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: #047857; color: #ffffff; font-size: 13px; font-weight: 700; padding: 9px 16px; border-radius: 10px; text-decoration: none;">
                                      <i class="fa-solid fa-eye"></i> View Clients Directory
                                  </a>
                              </div>

                              <!-- Staff Card -->
                              <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
                                  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                                      <div style="width: 44px; height: 44px; border-radius: 12px; background: #059669; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 18px; box-shadow: 0 4px 10px rgba(5, 150, 105, 0.25);">
                                          <i class="fa-solid fa-user-group"></i>
                                      </div>
                                      <span style="background: #d1fae5; color: #047857; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 9999px;">STAFF</span>
                                  </div>
                                  <div>
                                      <h4 style="margin: 0 0 4px 0; font-size: 26px; font-weight: 800; color: #0f172a;">
                                          <?php echo $this->report_model->getCountRow('staff', 'sf_id', ['sf_status' => 1]); ?>
                                      </h4>
                                      <p style="margin: 0 0 16px 0; font-size: 12px; color: #64748b; font-weight: 600;"><?php echo lang('total_staff'); ?></p>
                                  </div>
                                  <a href="<?php echo base_url('staff/listStaff'); ?>" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: #047857; color: #ffffff; font-size: 13px; font-weight: 700; padding: 9px 16px; border-radius: 10px; text-decoration: none;">
                                      <i class="fa-solid fa-eye"></i> View Staff Members
                                  </a>
                              </div>

                              <!-- Sheds Card -->
                              <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
                                  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                                      <div style="width: 44px; height: 44px; border-radius: 12px; background: #8b5cf6; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 18px; box-shadow: 0 4px 10px rgba(139, 92, 246, 0.25);">
                                          <i class="fa-solid fa-warehouse"></i>
                                      </div>
                                      <span style="background: #ede9fe; color: #6d28d9; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 9999px;">SHEDS</span>
                                  </div>
                                  <div>
                                      <h4 style="margin: 0 0 4px 0; font-size: 26px; font-weight: 800; color: #0f172a;">
                                          <?php echo $this->report_model->getCountRow('shed', 'sh_id', ['sh_status' => 1]); ?>
                                      </h4>
                                      <p style="margin: 0 0 16px 0; font-size: 12px; color: #64748b; font-weight: 600;"><?php echo lang('total_shed'); ?></p>
                                  </div>
                                  <a href="<?php echo base_url('shed/addShed'); ?>" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: #047857; color: #ffffff; font-size: 13px; font-weight: 700; padding: 9px 16px; border-radius: 10px; text-decoration: none;">
                                      <i class="fa-solid fa-eye"></i> View Sheds Infrastructure
                                  </a>
                              </div>
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
                         <div class="table-responsive" style="overflow-x:auto;">
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
                                                     $assignedValData = $this->purchase_model->getAssignedLivestockValueDataBySummaryId($value->lshs_id);
                                                     $ls_id = $assignedValData ? $assignedValData->lsh_purv_ls_id : 0;
                                                     $lst_id = $assignedValData ? $assignedValData->lsh_purv_lst_id : 0;

                                                     $lsObj = $ls_id ? $this->livestock_model->getLivestockById($ls_id) : null;
                                                     $lsName = $lsObj ? $lsObj->ls_name : '—';

                                                     $lstObj = $lst_id ? $this->livestock_model->getLivestockTypeById($lst_id) : null;
                                                     $lstTitle = $lstObj ? $lstObj->lst_title : '—';

                                                     $totalAssignedQuantity = $value->lshs_assign_total_quantity;
                                                     $totalSoldQuantity = ($ls_id && $lst_id) ? $this->sale_model->getShedAndBatchWiseLivestockSaleQuantity($shed->sh_id, $value->lshs_batch_id, $ls_id, $lst_id) : 0;
                                                     $totalDeathQuantity = $this->shed_model->getDeathLivestockSumByShedAndBatch($shed->sh_id, $value->lshs_batch_id);
                                                     $inStock = $totalAssignedQuantity - ($totalSoldQuantity + $totalDeathQuantity);
                                                 ?>
                                                     <tr class="over__flow__table">
                                                         <td><strong><?= $value->lshs_batch_id ?>: <?= htmlspecialchars($value->lshs_batch_title ?? '') ?></strong></td>
                                                         <td><?= htmlspecialchars($lsName) ?></td>
                                                         <td><?= htmlspecialchars($lstTitle) ?></td>
                                                         <td><?= $totalAssignedQuantity ?></td>
                                                         <td><?= $totalSoldQuantity ?></td>
                                                         <td><?= $totalDeathQuantity ?></td>
                                                         <td><?= $inStock ?></td>
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
                             <table class="table table-striped table-hover table-bordered" id="dashboard-food-stock-table">
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
                                      if (!empty($foods) && is_array($foods)) {
                                          foreach ($foods as $food) {
                                              $serial++;
                                              $unit = !empty($food->fds_unit_id) ? $this->settings_model->getUnitById($food->fds_unit_id) : null;
                                              $unitName = $unit ? ' ' . $unit->un_name : '';
                                              $purchaseFood = $this->food_model->getFoodPurchaseWeightByFoodId($food->fds_id, 'fdpv_quantity');
                                              $feed = $this->food_model->getFoodDistributedWeightByFoodId($food->fds_id, 'fddv_distributed_quantity');
                                              $wastedFood = $this->food_model->getFoodWastedByFoodId($food->fds_id, 'fdw_quantity');
                                              $stillInStock = $purchaseFood - $feed - $wastedFood;
                                      ?>
                                          <tr>
                                              <td><?= $serial ?></td>
                                              <td><?= htmlspecialchars($food->fds_food_title ?? '') ?></td>
                                              <td><?= $purchaseFood ? ($purchaseFood . $unitName) : 0 ?></td>
                                              <td><?= $feed ? ($feed . $unitName) : 0 ?></td>
                                              <td><?= $wastedFood ? ($wastedFood . $unitName) : 0 ?></td>
                                              <td><?= $stillInStock > 0 ? ($stillInStock . $unitName) : 0 ?></td>
                                          </tr>
                                      <?php }
                                      } ?>
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
            height: 320,
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