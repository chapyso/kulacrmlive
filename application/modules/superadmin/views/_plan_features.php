<?php
/**
 * Feature checkboxes for the plan editor (used by the create and edit modals).
 * Expects: $features (key => bool) for the plan being edited; catalog comes from Plan_features::catalog().
 */
$catalog = Plan_features::catalog();
$features = isset($features) && is_array($features) ? $features : array();
$groups = array();
foreach ($catalog as $key => $def) {
    $groups[$def['group']][$key] = $def;
}
?>
<div class="row">
    <div class="col-md-12 form-group">
        <label style="font-weight: 800; font-size: 13px; color: #334155; margin-bottom: 8px;">
            <i class="fa-solid fa-toggle-on" style="color:#6366f1; margin-right:6px;"></i> Features included in this plan
        </label>
        <?php foreach ($groups as $group => $items): ?>
            <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.6px; color: #64748b; font-weight: 800; margin: 10px 0 6px;"><?php echo htmlspecialchars($group); ?></div>
            <?php foreach ($items as $key => $def): ?>
                <label style="display: flex; width: 100%; align-items: flex-start; gap: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 9px 12px; margin: 0 0 6px 0; cursor: pointer; font-weight: 400;">
                    <input type="checkbox" name="features[<?php echo htmlspecialchars($key); ?>]" value="1" style="margin-top: 3px;" <?php echo !empty($features[$key]) ? 'checked' : ''; ?>>
                    <span>
                        <i class="fa-solid <?php echo htmlspecialchars($def['icon']); ?>" style="color:#6366f1; margin-right:6px;"></i>
                        <strong style="color:#0f172a;"><?php echo htmlspecialchars($def['label']); ?></strong>
                        <span style="display:block; font-size:12px; color:#64748b;"><?php echo htmlspecialchars($def['desc']); ?></span>
                    </span>
                </label>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
</div>
