<!--sidebar end-->
<style>
    ul.trash-tabs > li > a,
    ul.trash-tabs > li > a:link,
    ul.trash-tabs > li > a:visited { color: #333 !important; }
    ul.trash-tabs > li > a:hover,
    ul.trash-tabs > li > a:focus { color: #000 !important; background-color: #eee !important; }
    ul.trash-tabs > li.active > a,
    ul.trash-tabs > li.active > a:link,
    ul.trash-tabs > li.active > a:visited,
    ul.trash-tabs > li.active > a:hover,
    ul.trash-tabs > li.active > a:focus { color: #333 !important; background-color: #fff !important; }
    ul.trash-tabs > li > a .badge { color: #fff; background-color: #777; }
</style>
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <section class="panel">
            <header class="panel-heading bg-info">
                <i class="fa fa-trash"></i> <?php echo lang('trash'); ?>
            </header>
            <div class="panel-body">
                <?php if ($msg = $this->session->flashdata('feedback')): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div>
                <?php endif; ?>
                <p class="text-muted" style="margin-bottom:14px;">
                    Soft-deleted records from each module. Click <strong><?php echo lang('restore'); ?></strong>
                    to bring an item back. Only the 50 most-recently-deleted rows per module are shown.
                </p>

                <ul class="nav nav-tabs trash-tabs" role="tablist">
                    <?php $first = true; foreach ($trash_tables as $table => $meta): ?>
                        <li role="presentation" class="<?php echo $first ? 'active' : ''; ?>">
                            <a href="#tab-<?php echo $table; ?>" aria-controls="tab-<?php echo $table; ?>" role="tab" data-toggle="tab"
                               style="color:#333 !important;background-color:transparent;">
                                <span style="color:#333 !important;"><?php echo htmlspecialchars($meta['module']); ?></span>
                                <span class="badge" style="color:#fff;background-color:#777;"><?php echo count($trash_rows[$table]); ?></span>
                            </a>
                        </li>
                    <?php $first = false; endforeach; ?>
                </ul>

                <div class="tab-content" style="padding-top:14px;">
                    <?php $first = true; foreach ($trash_tables as $table => $meta): ?>
                        <div role="tabpanel" class="tab-pane <?php echo $first ? 'active' : ''; ?>" id="tab-<?php echo $table; ?>">
                            <?php if (empty($trash_rows[$table])): ?>
                                <div class="text-muted" style="padding:12px;"><?php echo lang('no_deleted_records'); ?></div>
                            <?php else: ?>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:80px;">ID</th>
                                            <th>Label</th>
                                            <th style="width:180px;">Deleted at</th>
                                            <th style="width:140px;"><?php echo lang('options'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($trash_rows[$table] as $r):
                                            $id_col    = $meta['id'];
                                            $label_col = $meta['label'];
                                            $updt_col  = $meta['updated_at'];
                                            $label_val = $label_col && isset($r->$label_col) ? $r->$label_col : ('#' . $r->$id_col);
                                        ?>
                                            <tr>
                                                <td><?php echo (int) $r->$id_col; ?></td>
                                                <td><?php echo htmlspecialchars((string) $label_val); ?></td>
                                                <td><?php echo isset($r->$updt_col) ? htmlspecialchars((string) $r->$updt_col) : ''; ?></td>
                                                <td>
                                                    <form action="<?php echo base_url('settings/restore'); ?>" method="post" style="display:inline"
                                                          onsubmit="return confirm('<?php echo lang('restore'); ?>?');">
                                                        <input type="hidden" name="table" value="<?php echo htmlspecialchars($table); ?>">
                                                        <input type="hidden" name="id" value="<?php echo (int) $r->$id_col; ?>">
                                                        <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                        <button type="submit" class="button button-info"><i class="fa fa-undo"></i> <?php echo lang('restore'); ?></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    <?php $first = false; endforeach; ?>
                </div>
            </div>
        </section>
    </section>
</section>
<!--main content end-->
