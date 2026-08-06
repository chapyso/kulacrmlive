<?php
/**
 * Shared invoice layout used by purchase, livestock-sale, and product-sale PDFs.
 * Expected $invoice variables (set by the caller before include):
 *   $invoice['title']        Invoice / Purchase / Sale label
 *   $invoice['doc_number']   Bill or sale ID
 *   $invoice['doc_date']     Document date (string)
 *   $invoice['party_label']  "Supplier" or "Client"
 *   $invoice['party_name']
 *   $invoice['party_email']
 *   $invoice['party_phone']
 *   $invoice['party_address']
 *   $invoice['lines']        array of [name, qty, unit_price, discount, total]
 *   $invoice['sub_total']
 *   $invoice['grand_discount']
 *   $invoice['grand_total']
 *   $invoice['paid']
 *   $invoice['note']
 *   $invoice['currency']
 *   $invoice['company']      array of [name, address, phone, email]
 */
?><!DOCTYPE html>
<html><head><meta charset="UTF-8"><title><?= htmlspecialchars($invoice['title']) ?> #<?= htmlspecialchars($invoice['doc_number']) ?></title>
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
h1, h2, h3 { margin: 0; }
.header { border-bottom: 2px solid #333; padding-bottom: 8px; margin-bottom: 16px; }
.header .left { float: left; }
.header .right { float: right; text-align: right; }
.clear { clear: both; }
.party { margin: 12px 0; }
.party strong { display: inline-block; min-width: 70px; }
table.lines { width: 100%; border-collapse: collapse; margin-top: 12px; }
table.lines th { background: #f0f0f0; padding: 6px; border: 1px solid #999; text-align: left; }
table.lines td { padding: 6px; border: 1px solid #ccc; }
table.lines td.num, table.lines th.num { text-align: right; }
.totals { width: 50%; margin-left: 50%; margin-top: 12px; }
.totals td { padding: 4px 6px; }
.totals td.label { text-align: right; font-weight: bold; }
.totals td.value { text-align: right; }
.totals tr.grand { border-top: 2px solid #333; font-size: 13px; }
.note { margin-top: 18px; padding: 8px; background: #fafafa; border-left: 3px solid #999; font-size: 10px; }
.footer { margin-top: 28px; font-size: 9px; color: #888; text-align: center; }
</style>
</head><body>

<div class="header">
    <div class="left">
        <h2><?= htmlspecialchars($invoice['company']['name']) ?></h2>
        <?= nl2br(htmlspecialchars($invoice['company']['address'])) ?><br>
        <?php if (!empty($invoice['company']['phone'])): ?>Phone: <?= htmlspecialchars($invoice['company']['phone']) ?><br><?php endif; ?>
        <?php if (!empty($invoice['company']['email'])): ?>Email: <?= htmlspecialchars($invoice['company']['email']) ?><?php endif; ?>
    </div>
    <div class="right">
        <h1><?= htmlspecialchars($invoice['title']) ?></h1>
        <strong>#<?= htmlspecialchars($invoice['doc_number']) ?></strong><br>
        Date: <?= htmlspecialchars($invoice['doc_date']) ?>
    </div>
    <div class="clear"></div>
</div>

<div class="party">
    <strong><?= htmlspecialchars($invoice['party_label']) ?>:</strong> <?= htmlspecialchars($invoice['party_name']) ?><br>
    <?php if (!empty($invoice['party_email'])): ?><strong>Email:</strong> <?= htmlspecialchars($invoice['party_email']) ?><br><?php endif; ?>
    <?php if (!empty($invoice['party_phone'])): ?><strong>Phone:</strong> <?= htmlspecialchars($invoice['party_phone']) ?><br><?php endif; ?>
    <?php if (!empty($invoice['party_address'])): ?><strong>Address:</strong> <?= htmlspecialchars($invoice['party_address']) ?><?php endif; ?>
</div>

<table class="lines">
    <thead>
        <tr>
            <th>#</th>
            <th>Description</th>
            <th class="num">Qty</th>
            <th class="num">Unit Price</th>
            <th class="num">Discount</th>
            <th class="num">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($invoice['lines'] as $i => $line): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($line['name']) ?></td>
            <td class="num"><?= number_format((float) $line['qty'], 2) ?></td>
            <td class="num"><?= htmlspecialchars($invoice['currency']) ?><?= number_format((float) $line['unit_price'], 2) ?></td>
            <td class="num"><?= htmlspecialchars($invoice['currency']) ?><?= number_format((float) $line['discount'], 2) ?></td>
            <td class="num"><?= htmlspecialchars($invoice['currency']) ?><?= number_format((float) $line['total'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<table class="totals">
    <tr><td class="label">Sub-total</td>      <td class="value"><?= htmlspecialchars($invoice['currency']) ?><?= number_format((float) $invoice['sub_total'], 2) ?></td></tr>
    <tr><td class="label">Discount</td>       <td class="value"><?= htmlspecialchars($invoice['currency']) ?><?= number_format((float) $invoice['grand_discount'], 2) ?></td></tr>
    <tr class="grand"><td class="label">Grand Total</td><td class="value"><?= htmlspecialchars($invoice['currency']) ?><?= number_format((float) $invoice['grand_total'], 2) ?></td></tr>
    <tr><td class="label">Paid</td>           <td class="value"><?= htmlspecialchars($invoice['currency']) ?><?= number_format((float) $invoice['paid'], 2) ?></td></tr>
    <tr><td class="label">Balance</td>        <td class="value"><?= htmlspecialchars($invoice['currency']) ?><?= number_format((float) $invoice['grand_total'] - (float) $invoice['paid'], 2) ?></td></tr>
</table>

<?php if (!empty($invoice['note'])): ?>
<div class="note"><strong>Note:</strong> <?= nl2br(htmlspecialchars($invoice['note'])) ?></div>
<?php endif; ?>

<div class="footer">Generated <?= htmlspecialchars(get_current_time()) ?></div>
</body></html>
