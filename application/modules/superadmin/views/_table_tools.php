<?php
/**
 * Shared Super Admin list toolbar: summary chips + live search + quick filters.
 * Expects: $tt_stats (label => value), $tt_placeholder (string), $tt_filters (label => keyword to match in the row text).
 * Filters rows of the table with id="sa-table" in the browser only; nothing is sent to the server.
 */
$tt_stats = isset($tt_stats) ? $tt_stats : array();
$tt_filters = isset($tt_filters) ? $tt_filters : array();
$tt_placeholder = isset($tt_placeholder) ? $tt_placeholder : 'Search...';
?>
<style>
.sa-toolbar { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; justify-content: space-between; margin-bottom: 16px; }
.sa-stats { display: flex; flex-wrap: wrap; gap: 8px; }
.sa-stat { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 8px 14px; font-size: 12px; color: #64748b; font-weight: 600; }
.sa-stat b { display: block; font-size: 18px; font-weight: 800; color: #0f172a; line-height: 1.2; }
.sa-search { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; flex: 1 1 320px; justify-content: flex-end; }
.sa-search input { flex: 1 1 200px; max-width: 320px; border: 1px solid #cbd5e1; border-radius: 12px; padding: 9px 14px; font-size: 13px; outline: none; background: #ffffff; }
.sa-search input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
.sa-chip { border: 1px solid #cbd5e1; background: #ffffff; color: #475569; border-radius: 20px; padding: 6px 14px; font-size: 12px; font-weight: 700; cursor: pointer; }
.sa-chip.active { background: #6366f1; border-color: #6366f1; color: #ffffff; }
.sa-count { font-size: 12px; color: #64748b; font-weight: 600; }
@media (max-width: 640px) { .sa-search { justify-content: flex-start; } .sa-search input { max-width: none; } }
</style>

<div class="sa-toolbar">
    <div class="sa-stats">
        <?php foreach ($tt_stats as $label => $value): ?>
            <div class="sa-stat"><b><?php echo htmlspecialchars((string)$value); ?></b><?php echo htmlspecialchars($label); ?></div>
        <?php endforeach; ?>
    </div>
    <div class="sa-search">
        <input type="search" id="saSearch" placeholder="<?php echo htmlspecialchars($tt_placeholder); ?>" autocomplete="off">
        <?php if ($tt_filters): ?>
            <button type="button" class="sa-chip active" data-kw="">All</button>
            <?php foreach ($tt_filters as $label => $kw): ?>
                <button type="button" class="sa-chip" data-kw="<?php echo htmlspecialchars($kw); ?>"><?php echo htmlspecialchars($label); ?></button>
            <?php endforeach; ?>
        <?php endif; ?>
        <span class="sa-count" id="saCount"></span>
    </div>
</div>

<script>
(function () {
    function init() {
        var table = document.getElementById('sa-table');
        if (!table) { return; }
        var rows = Array.prototype.slice.call(table.querySelectorAll('tbody tr')).filter(function (r) { return r.children.length > 1; });
        var input = document.getElementById('saSearch');
        var count = document.getElementById('saCount');
        var keyword = '';
        function apply() {
            var q = (input.value || '').toLowerCase().trim();
            var shown = 0;
            rows.forEach(function (r) {
                var text = r.textContent.toLowerCase();
                var ok = (q === '' || text.indexOf(q) !== -1) && (keyword === '' || text.indexOf(keyword) !== -1);
                r.style.display = ok ? '' : 'none';
                if (ok) { shown++; }
            });
            count.textContent = rows.length ? (shown + ' of ' + rows.length) : '';
        }
        input.addEventListener('input', apply);
        Array.prototype.forEach.call(document.querySelectorAll('.sa-chip'), function (chip) {
            chip.addEventListener('click', function () {
                Array.prototype.forEach.call(document.querySelectorAll('.sa-chip'), function (c) { c.classList.remove('active'); });
                chip.classList.add('active');
                keyword = (chip.getAttribute('data-kw') || '').toLowerCase();
                apply();
            });
        });
        apply();
    }
    if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', init); } else { init(); }
})();
</script>
