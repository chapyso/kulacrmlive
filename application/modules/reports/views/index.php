<!-- BI Reporting Engine View -->
<div class="content-wrapper" style="padding: 20px;">
    <div class="page-header" style="margin-bottom: 24px;">
        <h2 style="font-weight: 800; font-size: 24px; color: #0f172a; margin: 0;">Business Intelligence & Reports</h2>
        <p style="color: #64748b; font-size: 13px; margin-top: 4px;">Generate, filter, and export detailed operational and financial reports.</p>
    </div>

    <!-- Filter Form Card -->
    <div class="panel" style="border-radius: 14px; border: 1px solid #e2e8f0; background: #ffffff; box-shadow: 0 4px 16px rgba(0,0,0,0.04); margin-bottom: 24px;">
        <div class="panel-body" style="padding: 20px;">
            <form method="get" action="<?php echo base_url('reports'); ?>" class="form-inline" style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end;">
                <div class="form-group">
                    <label style="font-weight: 700; font-size: 12px; display: block; margin-bottom: 6px;">Report Type</label>
                    <select name="type" class="form-control" style="border-radius: 8px; font-weight: 600;">
                        <option value="sales" <?php echo ($type === 'sales') ? 'selected' : ''; ?>>Sales Report</option>
                        <option value="expenses" <?php echo ($type === 'expenses') ? 'selected' : ''; ?>>Expenses Report</option>
                        <option value="livestock" <?php echo ($type === 'livestock') ? 'selected' : ''; ?>>Livestock Inventory Report</option>
                    </select>
                </div>
                <div class="form-group">
                    <label style="font-weight: 700; font-size: 12px; display: block; margin-bottom: 6px;">Start Date</label>
                    <input type="date" name="start_date" value="<?php echo $start_date; ?>" class="form-control" style="border-radius: 8px;">
                </div>
                <div class="form-group">
                    <label style="font-weight: 700; font-size: 12px; display: block; margin-bottom: 6px;">End Date</label>
                    <input type="date" name="end_date" value="<?php echo $end_date; ?>" class="form-control" style="border-radius: 8px;">
                </div>
                <div class="form-group" style="display: flex; gap: 8px;">
                    <button type="submit" class="btn btn-primary" style="background: #10b981; border: none; border-radius: 8px; font-weight: 700; padding: 8px 18px;">
                        <i class="fa-solid fa-filter" style="margin-right: 6px;"></i> Filter Report
                    </button>
                    <a href="<?php echo base_url('reports/export_csv?type='.$type.'&start_date='.$start_date.'&end_date='.$end_date); ?>" class="btn btn-success" style="background: #059669; border: none; border-radius: 8px; font-weight: 700; padding: 8px 18px;">
                        <i class="fa-solid fa-file-csv" style="margin-right: 6px;"></i> Export CSV
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="panel" style="border-radius: 14px; border: 1px solid #e2e8f0; background: #ffffff; box-shadow: 0 4px 16px rgba(0,0,0,0.04);">
        <div class="panel-body" style="padding: 20px;">
            <div class="table-responsive">
                <table class="table table-hover" style="margin: 0;">
                    <thead>
                        <tr style="background: #f8fafc; font-size: 12px; text-transform: uppercase; color: #64748b;">
                            <th>#</th>
                            <th>Title / Reference</th>
                            <th>Category / Type</th>
                            <th>Amount / Total</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($report_data)): ?>
                            <?php foreach ($report_data as $idx => $row): ?>
                                <tr>
                                    <td><?php echo $idx + 1; ?></td>
                                    <td style="font-weight: 700;"><?php echo htmlspecialchars($row->ex_name ?? $row->ls_name ?? ('Ref #' . ($row->id ?? $row->ex_id ?? $row->ls_id))); ?></td>
                                    <td><span class="label label-info" style="border-radius: 6px; font-size: 11px;"><?php echo strtoupper($type); ?></span></td>
                                    <td style="font-weight: 800; color: #10b981;"><?php echo !empty($settings->currency) ? $settings->currency : '$'; ?> <?php echo number_format($row->sale_grand_total ?? $row->amount ?? 0, 2); ?></td>
                                    <td style="color: #64748b; font-size: 12px;"><?php echo date('M d, Y H:i', strtotime($row->created_at ?? 'now')); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: #94a3b8; padding: 40px;">No report records found for the selected filter parameters.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
