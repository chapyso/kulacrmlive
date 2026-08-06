<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        
        <!-- 2026 MODERN PAGE HEADER & ACTIONS BAR -->
        <div class="kula-hero-card" style="margin-bottom: 20px; padding: 20px 28px;">
            <div class="hero-content-left">
                <h1 class="hero-title" style="font-size: 22px;">
                    <i class="fa-solid fa-users" style="color: #059669; margin-right: 8px;"></i>
                    <?= lang('client_list'); ?>
                </h1>
                <p class="hero-subtitle">Directory of customers, contact profiles, purchasing history, and balance ledgers</p>
            </div>

            <div class="hero-actions-right" style="gap: 8px; flex-wrap: wrap;">
                <a data-toggle="modal" href="#myModal" class="hero-export-btn" style="background: #047857; text-decoration: none;">
                    <i class="fa-solid fa-plus-circle"></i> <?= lang('add_new_client'); ?>
                </a>

                <a href="<?php echo base_url('client/exportCSV'); ?>" class="hero-date-pill" style="text-decoration: none; padding: 10px 18px;">
                    <i class="fa-solid fa-file-csv" style="color: #0284c7;"></i>
                    <span>Export CSV</span>
                </a>

                <button class="hero-date-pill" onclick="javascript:window.print();" style="border: 1px solid #e2e8f0; background: #fff;">
                    <i class="fa-solid fa-print" style="color: #475569;"></i>
                    <span><?php echo lang('print'); ?></span>
                </button>
            </div>
        </div>
                            <a data-toggle="modal" href="#myModal">
                                <div class="btn-group">
                                    <button id="" class="button button-primary">
                                        <i class="fa fa-plus-circle"></i> <?php echo lang('add_new_client'); ?>
                                    </button>
                                </div>
                            </a>
                            <a href="<?php echo base_url('client/exportCSV'); ?>" style="margin-left:6px;">
                                <div class="btn-group">
                                    <button class="button button-info" type="button">
                                        <i class="fas fa-file-csv"></i> Export CSV
                                    </button>
                                </div>
                            </a>
                            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                        </div>
                        <div class="space15"></div>
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th><?= lang('serialNo'); ?></th>
                                    <th><?= lang('image'); ?></th>
                                    <th><?= lang('name'); ?></th>
                                    <th><?= lang('email'); ?></th>
                                    <th><?= lang('phone'); ?></th>
                                    <th><?= lang('address'); ?></th>
                                    <th><?= lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serial = 0;
                                foreach ($clients->result() as $client) {
                                    $serial++;
                                ?>
                                    <tr>
                                        <td><?= $serial ?></td>
                                        <td>
                                            <?php if ($client->c_img_url) { ?>
                                                <img class="img-circle" style="height: 50px; width: 50px;" src="<?php echo $client->c_img_url; ?>" alt="No img">
                                            <?php } else { ?>
                                                <img class="img-circle" style="height: 50px; width: 50px;" src="<?php echo base_url('uploads/avatar/alter-image.png'); ?>" alt="No img">
                                            <?php } ?>
                                        </td>
                                        <td><?= html_escape($client->c_name); ?></td>
                                        <td><?= html_escape($client->c_email); ?></td>
                                        <td><?= html_escape($client->c_phone); ?></td>
                                        <td><?= html_escape($client->c_address); ?></td>
                                        <td>
                                            <!-- find available data in target table -->
                                            <?php
                                            $countDataIfAvailableInProductSale = $this->settings_model->getCountRow('product_sale_summary', 'prss_id', ['prss_c_id' => $client->c_id, 'prss_status' => 1]);
                                            $countDataIfAvailableInLivestockSale = $this->settings_model->getCountRow('livestock_sale_summary', 'lsss_id', ['lsss_c_id' => $client->c_id, 'lsss_status' => 1]);
                                            ?>
                                            <button type="button" class="button button-warning editButton" data-toggle="modal" data-id="<?= $client->c_id; ?>"><i class="fas fa-edit"></i> <?php echo lang('edit'); ?></button>
                                            <form action="<?php echo base_url('client/deleteClient'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                <input type="hidden" name="c_id" value="<?= $client->c_id; ?>">
                                                <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                <button <?php echo ($countDataIfAvailableInProductSale == 0 && $countDataIfAvailableInLivestockSale == 0) ? '' : 'disabled'; ?> type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                            </form>
                                            <a href="<?php echo base_url(''); ?>payments/viewClientWisePayment?c_id=<?= $client->c_id; ?>"><button type="button" class="button button-info"><i class="fas fa-eye"></i> <?= lang('ledger'); ?></button></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->

<!-- ========== Add new client ========== -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?php echo lang('add_new_client'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" action="<?php echo base_url('client/insertClient'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('name'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="c_name" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('email'); ?></label>
                        <input type="text" class="form-control" name="c_email" value='' placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="c_phone" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('address'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="c_address" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="c_description" class="form-control" id="" rows="5" placeholder="Enter description" style="height: auto !important;"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('image'); ?></label>
                        <input type="file" name="c_img_url">
                    </div>
                    <input type="hidden" name="c_id" value=''>

                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<!-- ========== Edit client ========== -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-edit"></i> <?php echo lang('edit_client'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" id="clientEditForm" action="<?php echo base_url('client/insertClient'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('name'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="c_name" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('email'); ?></label>
                        <input type="email" class="form-control" name="c_email" value='' placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="c_phone" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('address'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="c_address" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="c_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('image'); ?></label>
                        <input type="file" name="c_img_url">
                    </div>
                    <input type="hidden" name="c_id" value=''>


                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<!-- Javascript For Edit client -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".editButton").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            $.ajax({
                url: 'client/editClientByJason?c_id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                // Populate the form fields with the data returned from server
                $('#clientEditForm').find('[name="c_id"]').val(response.clients.c_id).end()
                $('#clientEditForm').find('[name="c_name"]').val(response.clients.c_name).end()
                $('#clientEditForm').find('[name="c_email"]').val(response.clients.c_email).end()
                $('#clientEditForm').find('[name="c_phone"]').val(response.clients.c_phone).end()
                $('#clientEditForm').find('[name="c_address"]').val(response.clients.c_address).end()
                $('#clientEditForm').find('[name="c_description"]').val(response.clients.c_description).end()
                $('#myModal2').modal('show');
            });

        });
    });
</script>
<script>
    $(document).ready(function() {
        toastr.options = {
            'closeButton': true,
            'debug': false,
            'newestOnTop': false,
            'progressBar': true,
            'positionClass': 'toast-bottom-right',
            'preventDuplicates': false,
            'showDuration': '1000',
            'hideDuration': '1000',
            'timeOut': '10000',
            'extendedTimeOut': '1000',
            'showEasing': 'swing',
            'hideEasing': 'linear',
            'showMethod': 'fadeIn',
            'hideMethod': 'fadeOut',
        }
        <?php if ($this->session->flashdata('success')): ?>
        toastr.success('<?= html_escape($this->session->flashdata('success')); ?>');
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
        toastr.error('<?= html_escape($this->session->flashdata('error')); ?>');
        <?php endif; ?>
    });
</script>