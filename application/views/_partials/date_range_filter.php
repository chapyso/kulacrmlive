<?php
if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * Date-range filter partial. Variables required from caller:
 *   $action_url  — URL to submit the filter to (GET).
 *   $from        — current "from" value (Y-m-d) or ''.
 *   $to          — current "to" value (Y-m-d) or ''.
 *   $clear_url   — URL to reset the filter (typically the action_url itself).
 *
 * Renders a small inline form. Submits via GET so it composes cleanly with
 * pagination + CSV-export links on the same page.
 */
?>
<form method="get" action="<?php echo $action_url; ?>" class="date-range-filter" style="display:inline-block; margin: 6px 0 6px 6px;">
    <span style="font-size:12px; color:#cbd5e1; font-weight:600; margin-right:4px;"><i class="fa-solid fa-filter"></i> <?php echo lang('filter'); ?>:</span>
    <input type="date" name="from" value="<?php echo htmlspecialchars($from, ENT_QUOTES); ?>"
        title="<?php echo lang('from_date'); ?>" style="padding:5px 10px; background:#030712 !important; color:#ffffff !important; border:1px solid rgba(255,255,255,0.2) !important; border-radius:10px !important; color-scheme: dark !important; font-weight:600;" />
    <input type="date" name="to" value="<?php echo htmlspecialchars($to, ENT_QUOTES); ?>"
        title="<?php echo lang('to_date'); ?>" style="padding:5px 10px; background:#030712 !important; color:#ffffff !important; border:1px solid rgba(255,255,255,0.2) !important; border-radius:10px !important; color-scheme: dark !important; font-weight:600;" />
    <button type="submit" class="button button-info" style="padding:6px 14px; border-radius:10px; font-weight:700;"><?php echo lang('apply'); ?></button>
    <?php if ($from !== '' || $to !== ''): ?>
        <a href="<?php echo $clear_url; ?>" class="button button-default" style="padding:6px 14px; background:rgba(30,41,59,0.8); color:#f8fafc; border:1px solid rgba(255,255,255,0.2); border-radius:10px; text-decoration:none; font-weight:700;"><?php echo lang('clear'); ?></a>
    <?php endif; ?>
</form>
