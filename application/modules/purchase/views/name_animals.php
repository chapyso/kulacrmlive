<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="col-md-8">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fa-solid fa-tag"></i> Name your animals
                    <span class="text-muted" style="font-weight:normal;">
                        &mdash; <?= html_escape($livestock ? $livestock->ls_name : ''); ?>
                        <?= $livestock_type ? '(' . html_escape($livestock_type->lst_title) . ')' : ''; ?>,
                        <?= (int) floor($purv->purv_quantity); ?> head, bill <?= html_escape($purchase ? $purchase->purs_bill_no : ''); ?>
                    </span>
                </header>
                <div class="panel-body">
                    <p class="text-muted">Give each animal its own name or tag. You can then pick the animal when recording milk, beef or other production.</p>
                    <form action="<?= base_url('purchase/saveAnimalNames'); ?>" method="post">
                        <input type="hidden" name="purv_id" value="<?= (int) $purv->purv_id; ?>">
                        <input type="hidden" name="action_token" value="<?= action_token(); ?>">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:70px;">#</th>
                                    <th>Animal name / tag</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; foreach ($animals as $animal) { $i++; ?>
                                    <tr>
                                        <td><?= $i; ?></td>
                                        <td><input type="text" class="form-control" maxlength="100" name="an_name[<?= (int) $animal->an_id; ?>]" value="<?= html_escape($animal->an_name); ?>" required></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <button type="submit" class="button button-info"><i class="fas fa-save"></i> Save names</button>
                        <a class="button button-warning" href="<?= base_url('purchase/viewLivestockPurchase?purs_id=' . (int) $purv->purv_purs_id); ?>">Back to invoice</a>
                    </form>
                </div>
            </section>
        </div>
    </section>
</section>
<script>
    $(document).ready(function() {
        if (typeof toastr === 'undefined') return;
        <?php if ($this->session->flashdata('success')): ?>
        toastr.success('<?= html_escape($this->session->flashdata('success')); ?>');
        <?php endif; ?>
    });
</script>
