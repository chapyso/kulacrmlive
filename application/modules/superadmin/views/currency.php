<!-- main content start -->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- Page Title & Header -->
        <div class="row" style="margin-bottom: 24px;">
            <div class="col-md-8">
                <h3 style="font-weight: 800; color: #0f172a; margin: 0 0 6px 0; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-coins" style="color: #f59e0b;"></i> Global Currency Management
                </h3>
                <p style="color: #64748b; margin: 0; font-size: 13px;">
                    Manage multi-currency catalog, set primary base platform currency, configure exchange rates, and customize symbol position formatting across all tenant subscriptions.
                </p>
            </div>
            <div class="col-md-4 text-right" style="padding-top: 6px;">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCurrencyModal" style="border-radius: 10px; font-weight: 700; background: #6366f1; border: none; padding: 10px 18px; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);">
                    <i class="fa-solid fa-plus-circle" style="margin-right: 6px;"></i> Add New Currency
                </button>
            </div>
        </div>

        <!-- Flash Feedback Alert -->
        <?php if ($this->session->flashdata('feedback')): ?>
            <div class="alert alert-info alert-dismissable" style="border-radius: 12px; background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; margin-bottom: 24px;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="color: #1e40af;">&times;</button>
                <i class="fa-solid fa-circle-info" style="margin-right: 8px;"></i>
                <strong>Notice:</strong> <?php echo htmlspecialchars($this->session->flashdata('feedback')); ?>
            </div>
        <?php endif; ?>

        <!-- Stat Cards Row -->
        <div class="row" style="margin-bottom: 24px;">
            <?php
                $active_count = 0;
                $default_curr_code = 'USD';
                $default_curr_symbol = '$';
                foreach ($currencies as $c) {
                    if ($c->is_active) $active_count++;
                    if ($c->is_default) {
                        $default_curr_code = $c->code;
                        $default_curr_symbol = $c->symbol;
                    }
                }
            ?>
            <div class="col-md-3">
                <div style="background: #ffffff; border-radius: 16px; padding: 20px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 16px;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: #e0e7ff; color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                    <div>
                        <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Active Currencies</div>
                        <div style="font-size: 22px; font-weight: 800; color: #0f172a;"><?php echo $active_count; ?> / <?php echo count($currencies); ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div style="background: #ffffff; border-radius: 16px; padding: 20px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 16px;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <div>
                        <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Base Platform Base</div>
                        <div style="font-size: 22px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($default_curr_code); ?> (<?php echo htmlspecialchars($default_curr_symbol); ?>)</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div style="background: #ffffff; border-radius: 16px; padding: 20px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 16px;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: #cff4fc; color: #0891b2; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                    <div>
                        <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Supported Regions</div>
                        <div style="font-size: 22px; font-weight: 800; color: #0f172a;">Global &amp; East Africa</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div style="background: #ffffff; border-radius: 16px; padding: 20px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 16px;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Exchange Sync</div>
                        <div style="font-size: 13px; font-weight: 800; color: #0f172a;">Real-Time Config</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Currencies Table Container -->
        <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03); overflow: hidden;">
            <div style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0; font-weight: 800; color: #0f172a; font-size: 16px;">
                    <i class="fa-solid fa-list-check" style="color: #6366f1; margin-right: 8px;"></i> Currency Directory &amp; Exchange Rates
                </h4>
                <div style="width: 260px;">
                    <input type="text" id="currencySearchInput" onkeyup="filterCurrencyTable()" class="form-control" placeholder="Search currency code or name..." style="border-radius: 8px; font-size: 12px; padding: 6px 12px;">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="currencyTable" style="margin: 0;">
                    <thead style="background: #f8fafc; color: #475569; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <tr>
                            <th style="padding: 14px 20px;">Code &amp; Name</th>
                            <th style="padding: 14px 20px;">Symbol</th>
                            <th style="padding: 14px 20px;">Exchange Rate (to Base)</th>
                            <th style="padding: 14px 20px;">Position</th>
                            <th style="padding: 14px 20px;">Formatting Preview</th>
                            <th style="padding: 14px 20px; text-align: center;">Status</th>
                            <th style="padding: 14px 20px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 13px; color: #334155;">
                        <?php if (!empty($currencies)): ?>
                            <?php foreach ($currencies as $c): ?>
                                <tr style="<?php echo $c->is_default ? 'background: #f0fdf4;' : ''; ?>">
                                    <td style="padding: 16px 20px; font-weight: 700;">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <span style="font-family: monospace; font-size: 14px; color: #0f172a; background: #f1f5f9; padding: 4px 8px; border-radius: 6px; border: 1px solid #cbd5e1;"><?php echo htmlspecialchars($c->code); ?></span>
                                            <span><?php echo htmlspecialchars($c->name); ?></span>
                                            <?php if ($c->is_default): ?>
                                                <span class="label label-success" style="border-radius: 6px; font-size: 9px; padding: 3px 6px; text-transform: uppercase;"><i class="fa-solid fa-star"></i> Base Default</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <td style="padding: 16px 20px; font-weight: 800; font-size: 15px; color: #6366f1;">
                                        <?php echo htmlspecialchars($c->symbol); ?>
                                    </td>

                                    <td style="padding: 16px 20px; font-family: monospace; font-size: 13px;">
                                        1 <?php echo htmlspecialchars($default_curr_code); ?> = 
                                        <strong><?php echo number_format($c->exchange_rate, 4); ?></strong> <?php echo htmlspecialchars($c->code); ?>
                                    </td>

                                    <td style="padding: 16px 20px; text-transform: capitalize;">
                                        <span class="label label-default" style="border-radius: 6px; font-weight: 600; background: #e2e8f0; color: #334155; padding: 4px 8px;">
                                            <?php echo htmlspecialchars($c->symbol_position); ?>
                                        </span>
                                    </td>

                                    <td style="padding: 16px 20px; font-weight: 700; color: #0f172a;">
                                        <?php
                                            $sample_amount = 1250.50;
                                            $formatted_num = number_format($sample_amount, $c->decimal_digits);
                                            if ($c->symbol_position === 'suffix') {
                                                echo $formatted_num . ' ' . htmlspecialchars($c->symbol);
                                            } else {
                                                echo htmlspecialchars($c->symbol) . ' ' . $formatted_num;
                                            }
                                        ?>
                                    </td>

                                    <td style="padding: 16px 20px; text-align: center;">
                                        <?php if ($c->is_active): ?>
                                            <span class="label label-success" style="border-radius: 12px; font-weight: 700; padding: 4px 10px; background: #10b981;">Active</span>
                                        <?php else: ?>
                                            <span class="label label-danger" style="border-radius: 12px; font-weight: 700; padding: 4px 10px; background: #ef4444;">Disabled</span>
                                        <?php endif; ?>
                                    </td>

                                    <td style="padding: 16px 20px; text-align: right;">
                                        <button type="button" class="btn btn-xs btn-default" 
                                            onclick="openEditCurrencyModal(this)"
                                            data-id="<?php echo $c->id; ?>"
                                            data-code="<?php echo htmlspecialchars($c->code); ?>"
                                            data-name="<?php echo htmlspecialchars($c->name); ?>"
                                            data-symbol="<?php echo htmlspecialchars($c->symbol); ?>"
                                            data-rate="<?php echo $c->exchange_rate; ?>"
                                            data-position="<?php echo $c->symbol_position; ?>"
                                            data-decimals="<?php echo $c->decimal_digits; ?>"
                                            data-active="<?php echo $c->is_active; ?>"
                                            data-default="<?php echo $c->is_default; ?>"
                                            style="border-radius: 6px; font-weight: 700; margin-right: 4px; border: 1px solid #cbd5e1; background: #ffffff; color: #334155;" title="Edit Currency">
                                            <i class="fa-solid fa-pen-to-square" style="color: #6366f1;"></i> Edit
                                        </button>

                                        <?php if (!$c->is_default): ?>
                                            <a href="<?php echo base_url('superadmin/set_default_currency/' . $c->id); ?>" class="btn btn-xs btn-success" style="border-radius: 6px; font-weight: 700; margin-right: 4px;" title="Set as Base Platform Default">
                                                <i class="fa-solid fa-star"></i> Set Base
                                            </a>
                                            <a href="<?php echo base_url('superadmin/toggle_currency/' . $c->id); ?>" class="btn btn-xs <?php echo $c->is_active ? 'btn-warning' : 'btn-info'; ?>" style="border-radius: 6px; font-weight: 700; margin-right: 4px;">
                                                <?php echo $c->is_active ? 'Disable' : 'Enable'; ?>
                                            </a>
                                            <a href="<?php echo base_url('superadmin/delete_currency/' . $c->id); ?>" onclick="return confirm('Are you sure you want to delete currency <?php echo htmlspecialchars($c->code); ?>?');" class="btn btn-xs btn-danger" style="border-radius: 6px; font-weight: 700;" title="Delete Currency">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal: Add New Currency -->
        <div class="modal fade" id="addCurrencyModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header" style="background: #0f172a; color: #ffffff;">
                        <button type="button" class="close" data-dismiss="modal" onclick="$('#addCurrencyModal').hide().removeClass('in show');" aria-hidden="true" style="color: #fff;">&times;</button>
                        <h4 class="modal-title" style="font-weight: 800;"><i class="fa-solid fa-plus-circle" style="color: #6366f1;"></i> Add New Supported Currency</h4>
                    </div>
                    <form action="<?php echo base_url('superadmin/save_currency'); ?>" method="post">
                        <div class="modal-body" style="padding: 24px;">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">ISO Code (e.g. USD, UGX)</label>
                                    <input type="text" name="code" class="form-control" placeholder="e.g. KES" required style="border-radius: 8px; text-transform: uppercase;">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Currency Symbol</label>
                                    <input type="text" name="symbol" class="form-control" placeholder="e.g. KSh, $, €" required style="border-radius: 8px;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Full Currency Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Kenyan Shilling" required style="border-radius: 8px;">
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Exchange Rate (Relative to Base)</label>
                                    <input type="number" step="0.0001" name="exchange_rate" class="form-control" value="1.0000" required style="border-radius: 8px;">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Decimal Precision Digits</label>
                                    <input type="number" name="decimal_digits" class="form-control" value="2" min="0" max="4" required style="border-radius: 8px;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Symbol Position</label>
                                <select name="symbol_position" class="form-control" style="border-radius: 8px;">
                                    <option value="prefix">Prefix (Before amount: $ 1,000.00)</option>
                                    <option value="suffix">Suffix (After amount: 1,000.00 UGX)</option>
                                </select>
                            </div>

                            <div class="checkbox" style="margin-top: 16px;">
                                <label style="font-weight: 700; font-size: 13px; color: #334155;">
                                    <input type="checkbox" name="is_active" value="1" checked> Enable this currency for platform display
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer" style="background: #f8fafc;">
                            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#addCurrencyModal').hide().removeClass('in show');" style="border-radius: 8px; font-weight: 700;">Cancel</button>
                            <button type="submit" class="btn btn-primary" style="border-radius: 8px; font-weight: 700; background: #6366f1; border: none;">Save Currency</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal: Edit Currency -->
        <div class="modal fade" id="editCurrencyModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header" style="background: #0f172a; color: #ffffff;">
                        <button type="button" class="close" data-dismiss="modal" onclick="$('#editCurrencyModal').hide().removeClass('in show');" aria-hidden="true" style="color: #fff;">&times;</button>
                        <h4 class="modal-title" style="font-weight: 800;"><i class="fa-solid fa-pen-to-square" style="color: #6366f1;"></i> Edit Currency Configuration</h4>
                    </div>
                    <form action="<?php echo base_url('superadmin/save_currency'); ?>" method="post">
                        <input type="hidden" name="id" id="edit_curr_id">
                        <div class="modal-body" style="padding: 24px;">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">ISO Code</label>
                                    <input type="text" name="code" id="edit_curr_code" class="form-control" required style="border-radius: 8px; text-transform: uppercase;">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Currency Symbol</label>
                                    <input type="text" name="symbol" id="edit_curr_symbol" class="form-control" required style="border-radius: 8px;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Full Currency Name</label>
                                <input type="text" name="name" id="edit_curr_name" class="form-control" required style="border-radius: 8px;">
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Exchange Rate</label>
                                    <input type="number" step="0.0001" name="exchange_rate" id="edit_curr_rate" class="form-control" required style="border-radius: 8px;">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label style="font-weight: 700; font-size: 12px; color: #334155;">Decimal Digits</label>
                                    <input type="number" name="decimal_digits" id="edit_curr_decimals" class="form-control" min="0" max="4" required style="border-radius: 8px;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label style="font-weight: 700; font-size: 12px; color: #334155;">Symbol Position</label>
                                <select name="symbol_position" id="edit_curr_position" class="form-control" style="border-radius: 8px;">
                                    <option value="prefix">Prefix (Before amount: $ 1,000.00)</option>
                                    <option value="suffix">Suffix (After amount: 1,000.00 UGX)</option>
                                </select>
                            </div>

                            <div class="checkbox" style="margin-top: 16px;">
                                <label style="font-weight: 700; font-size: 13px; color: #334155;">
                                    <input type="checkbox" name="is_active" id="edit_curr_active" value="1"> Active &amp; Available for Display
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer" style="background: #f8fafc;">
                            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#editCurrencyModal').hide().removeClass('in show');" style="border-radius: 8px; font-weight: 700;">Cancel</button>
                            <button type="submit" class="btn btn-primary" style="border-radius: 8px; font-weight: 700; background: #6366f1; border: none;">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</section>

<script>
function filterCurrencyTable() {
    var input = document.getElementById("currencySearchInput");
    var filter = input.value.toUpperCase();
    var table = document.getElementById("currencyTable");
    var tr = table.getElementsByTagName("tr");

    for (var i = 1; i < tr.length; i++) {
        var tdText = tr[i].textContent || tr[i].innerText;
        if (tdText.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}

function openEditCurrencyModal(btn) {
    var $btn = $(btn);
    $('#edit_curr_id').val($btn.data('id'));
    $('#edit_curr_code').val($btn.data('code'));
    $('#edit_curr_name').val($btn.data('name'));
    $('#edit_curr_symbol').val($btn.data('symbol'));
    $('#edit_curr_rate').val($btn.data('rate'));
    $('#edit_curr_position').val($btn.data('position'));
    $('#edit_curr_decimals').val($btn.data('decimals'));
    $('#edit_curr_active').prop('checked', parseInt($btn.data('active')) === 1);

    var modalEl = document.getElementById('editCurrencyModal');
    if (modalEl) {
        if (typeof $(modalEl).modal === 'function') {
            $(modalEl).modal('show');
        } else {
            modalEl.style.display = 'block';
            modalEl.classList.add('in', 'show');
        }
    }
}
</script>
