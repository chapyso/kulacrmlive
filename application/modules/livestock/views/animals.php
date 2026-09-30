<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="kula-hero-card" style="margin-bottom: 20px; padding: 20px 28px;">
            <div class="hero-content-left">
                <h1 class="hero-title" style="font-size: 22px;"><i class="fa-solid fa-tags" style="color:#059669; margin-right:8px;"></i> Animals</h1>
                <p class="hero-subtitle">Give every head its own name and tag, link calves to their mother, and place animals in a batch. Production and KulaAI Vision use this list.</p>
            </div>
            <div class="hero-actions-right">
                <a href="<?= base_url('kula_ai/vision'); ?>" class="hero-export-btn" style="background: linear-gradient(135deg,#10b981,#059669); text-decoration:none; color:#fff;"><i class="fa-solid fa-eye"></i> KulaAI Vision</a>
            </div>
        </div>

        <!-- Herd overview: how many exist, how many are named -->
        <div class="row">
            <?php foreach ($herd as $h) { ?>
                <div class="col-md-4 col-sm-6">
                    <section class="panel">
                        <div class="panel-body">
                            <h4 style="margin-top:0;"><?= html_escape($h->ls_name . ($h->lst_title ? ' - ' . $h->lst_title : '')); ?></h4>
                            <p style="margin:0 0 6px;">In herd: <strong><?= $h->stock; ?></strong> &nbsp;|&nbsp; Named: <strong style="color:#059669;"><?= $h->named; ?></strong> &nbsp;|&nbsp; Not named: <strong style="color:<?= $h->not_named > 0 ? '#dc2626' : '#059669'; ?>;"><?= $h->not_named; ?></strong></p>
                            <div class="progress" style="height:8px; margin:6px 0;"><div class="progress-bar progress-bar-success" style="width:<?= $h->stock > 0 ? min(100, round($h->named / $h->stock * 100)) : 0; ?>%"></div></div>
                            <a class="button button-info" href="<?= base_url('livestock/animals?ls_id=' . $h->ls_id . '&lst_id=' . $h->lst_id . '&state=unnamed'); ?>">Name them</a>
                            <?php if ($h->unregistered > 0) { ?>
                                <form action="<?= base_url('livestock/generateAnimals'); ?>" method="post" style="display:inline;" data-confirm="Create <?= $h->unregistered; ?> blank animal records with auto tags?" data-confirm-title="Create animal records" data-confirm-btn="Yes, create">
                                    <input type="hidden" name="action_token" value="<?= action_token(); ?>">
                                    <input type="hidden" name="ls_id" value="<?= $h->ls_id; ?>">
                                    <input type="hidden" name="lst_id" value="<?= $h->lst_id; ?>">
                                    <input type="hidden" name="count" value="<?= $h->unregistered; ?>">
                                    <button type="submit" class="button button-warning">Create <?= $h->unregistered; ?> records</button>
                                </form>
                            <?php } ?>
                        </div>
                    </section>
                </div>
            <?php } ?>
            <?php if (empty($herd)) { ?>
                <div class="col-md-12"><p class="text-muted">No livestock in stock yet. Purchase livestock first, then come back to name each animal.</p></div>
            <?php } ?>
        </div>

        <!-- Filters -->
        <section class="panel">
            <div class="panel-body">
                <form method="get" action="<?= base_url('livestock/animals'); ?>" class="form-inline">
                    <select name="ls_id" class="form-control">
                        <option value="">All livestock</option>
                        <?php $seen = array(); foreach ($herd as $h) { if (isset($seen[$h->ls_id])) continue; $seen[$h->ls_id] = 1; ?>
                            <option value="<?= $h->ls_id; ?>" <?= $filters['ls_id'] == $h->ls_id ? 'selected' : ''; ?>><?= html_escape($h->ls_name); ?></option>
                        <?php } ?>
                    </select>
                    <select name="batch" class="form-control">
                        <option value="">All batches</option>
                        <?php foreach ($batchOptions as $k => $label) { ?>
                            <option value="<?= $k; ?>" <?= $batch_filter === $k ? 'selected' : ''; ?>><?= html_escape($label); ?></option>
                        <?php } ?>
                    </select>
                    <select name="state" class="form-control">
                        <option value="">Named &amp; not named</option>
                        <option value="unnamed" <?= $filters['state'] === 'unnamed' ? 'selected' : ''; ?>>Not named yet</option>
                        <option value="named" <?= $filters['state'] === 'named' ? 'selected' : ''; ?>>Named</option>
                    </select>
                    <input type="text" name="q" class="form-control" placeholder="Search name or tag" value="<?= html_escape($filters['q']); ?>">
                    <input type="hidden" name="lst_id" value="<?= $filters['lst_id'] ?: ''; ?>">
                    <button type="submit" class="button button-info">Filter</button>
                </form>
            </div>
        </section>

        <!-- Roster -->
        <section class="panel">
            <div class="panel-body">
                <datalist id="motherList">
                    <?php foreach ($motherOptions as $m) { if ($m->an_sex === 'M') continue; ?>
                        <option value="<?= html_escape(Animal_model::label($m)); ?>"><?= html_escape($m->ls_name); ?></option>
                    <?php } ?>
                </datalist>
                <form action="<?= base_url('livestock/saveAnimals'); ?>" method="post">
                    <input type="hidden" name="action_token" value="<?= action_token(); ?>">
                    <button type="submit" style="display:none" aria-hidden="true" tabindex="-1"></button>
                    <input type="hidden" name="return" value="<?= html_escape('livestock/animals?' . http_build_query($this->input->get())); ?>">
                    <p class="text-muted"><?= (int) $total; ?> animal(s) match. Edit any rows, then press Save.</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr><th>#</th><th>Kind</th><th>Name</th><th>Tag</th><th>Sex</th><th>Mother (optional)</th><th>Shed / Batch</th><th></th></tr>
                            </thead>
                            <tbody>
                                <?php $i = ($page - 1) * 100; foreach ($animals as $a) { $i++;
                                    $key = $a->an_shed_id . ':' . $a->an_batch_id; ?>
                                    <tr>
                                        <td><?= $i; ?></td>
                                        <td><?= html_escape($a->ls_name . ($a->lst_title ? ' - ' . $a->lst_title : '')); ?><?= $a->an_origin === 'born' ? ' <span class="label label-info">born</span>' : ''; ?></td>
                                        <td><input type="text" class="form-control" maxlength="100" name="an[<?= $a->an_id; ?>][name]" value="<?= html_escape($a->an_name); ?>" placeholder="e.g. Lisa"></td>
                                        <td><input type="text" class="form-control" maxlength="50" style="width:110px;" name="an[<?= $a->an_id; ?>][tag]" value="<?= html_escape($a->an_tag); ?>" placeholder="001"></td>
                                        <td>
                                            <select class="form-control" name="an[<?= $a->an_id; ?>][sex]" style="width:80px;">
                                                <option value="">-</option>
                                                <option value="F" <?= $a->an_sex === 'F' ? 'selected' : ''; ?>>F</option>
                                                <option value="M" <?= $a->an_sex === 'M' ? 'selected' : ''; ?>>M</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control" list="motherList" name="an[<?= $a->an_id; ?>][mother]" value="<?= $a->an_mother_id ? html_escape(Animal_model::label((object) array('an_name' => $a->mother_name, 'an_tag' => $a->mother_tag, 'an_id' => $a->an_mother_id))) : ''; ?>" placeholder="e.g. Jana"></td>
                                        <td>
                                            <select class="form-control" name="an[<?= $a->an_id; ?>][batch]">
                                                <option value="0:0">-</option>
                                                <?php foreach ($batchOptions as $k => $label) { ?>
                                                    <option value="<?= $k; ?>" <?= $key === $k ? 'selected' : ''; ?>><?= html_escape($label); ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td><button type="submit" class="button button-danger" formaction="<?= base_url('livestock/removeAnimal'); ?>" formmethod="post" name="an_id" value="<?= $a->an_id; ?>" data-confirm="Remove this animal from the registry?" data-confirm-btn="Yes, remove"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                <?php } ?>
                                <?php if (empty($animals)) { ?><tr><td colspan="8" class="text-center text-muted">No animals here yet.</td></tr><?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="button button-info"><i class="fas fa-save"></i> Save changes</button>
                    <?php if ($pages > 1) { ?>
                        <span style="margin-left:15px;">Page
                            <?php for ($p = 1; $p <= $pages; $p++) { $q = $this->input->get(); $q['page'] = $p; ?>
                                <a href="<?= base_url('livestock/animals?' . http_build_query($q)); ?>" <?= $p === $page ? 'style="font-weight:bold;"' : ''; ?>><?= $p; ?></a>
                            <?php } ?>
                        </span>
                    <?php } ?>
                </form>
            </div>
        </section>
    </section>
</section>
<script>
    $(document).ready(function() {
        if (typeof toastr === 'undefined') return;
        <?php if ($this->session->flashdata('success')): ?>toastr.success('<?= html_escape($this->session->flashdata('success')); ?>');<?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>toastr.error('<?= html_escape($this->session->flashdata('error')); ?>');<?php endif; ?>
    });
</script>
