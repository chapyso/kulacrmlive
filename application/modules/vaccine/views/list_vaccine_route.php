<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-stream"></i> <?= lang('vaccine_routing_list'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table ">
                        <div class="clearfix">
                            <a data-toggle="modal" href="#myModal">
                                <div class="btn-group">
                                    <button id="" class="button button-primary">
                                        <i class="fas fa-plus-circle"></i> <?= lang('add_new_routing'); ?>
                                    </button>
                                </div>
                            </a>
                            <a href="<?php echo base_url('vaccine'); ?>">
                                <div class="btn-group">
                                    <button class="button button-info">
                                        <i class="fa-solid fa-circle-arrow-up"></i> <?= lang('vaccine_lists'); ?>
                                    </button>
                                </div>
                            </a>
                            <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
                        </div>
                        <div class="space15"></div>
                        <table class="table table-striped table-hover table-bordered" id="editable-sample">
                            <thead>
                                <tr>
                                    <th><?= lang('serialNo'); ?>.</th>
                                    <th><?= lang('route_name'); ?> </th>
                                    <th><?= lang('note'); ?></th>
                                    <th><?php echo lang('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serial = 0;
                                foreach ($vaccineRoutes->result() as $list) {
                                    $serial++;
                                ?>
                                    <tr class="">
                                        <td><?= $serial; ?></td>
                                        <td><?= $list->vccr_name; ?></td>
                                        <td> <?= $list->vccr_description; ?></td>
                                        <td>
                                            <button type="button" class="button button-warning editButton" data-toggle="modal" data-id="<?php echo $list->vccr_id; ?>"><i class="fas fa-edit"></i> <?php echo lang('edit'); ?></button>

                                            <!-- conditional delete -->
                                            <?php
                                            $countDataIfAvailableInVaccine = $this->settings_model->getCountRow('vaccine_dose_assigned_quantity', 'vdq_id', ['vdq_vaccine_route' => $list->vccr_id, 'vdq_status' => 1]);
                                            if ($countDataIfAvailableInVaccine  == 0) { ?>
                                                <form action="<?php echo base_url('vaccine/deleteVaccineRoute'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                    <input type="hidden" name="vccr_id" value="<?php echo $list->vccr_id; ?>">
                                                    <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                    <button type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                                </form>
                                            <?php } else { ?>
                                                <button type="button" class="button button-danger" title="<?= lang('this_item_used_another_places'); ?>" disabled><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                            <?php } ?>
                                            <!-- /.conditional delete -->
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
<!--footer start-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?php echo lang('add_new_staff'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" action="<?php echo base_url('vaccine/insertVaccineRoute'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('name'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="vccr_name" id="" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="vccr_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="id" value=''>
                    <input type="hidden" name="vccr_id" value=''>

                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Edit staff -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fas fa-edit"></i> <?= lang('edit_vaccine_route'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height:100%;">
                <form role="form" id="vaccineRouteEditForm" action="<?php echo base_url('vaccine/insertVaccineRoute'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('name'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="vccr_name" id="" value='' placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="vccr_description" class="form-control" id="" rows="5" placeholder="Enter description" style="height: auto !important;"></textarea>
                    </div>
                    <input type="hidden" name="vccr_id" value='' id="">

                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('edit_button'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<!-- Javascript For Edit staff -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".editButton").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            $.ajax({
                url: 'vaccine/editVaccineRouteByJason?vccr_id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                // Populate the form fields with the data returned from server 
                $('#vaccineRouteEditForm').find('[name="vccr_id"]').val(response.routes.vccr_id).end()
                $('#vaccineRouteEditForm').find('[name="vccr_name"]').val(response.routes.vccr_name).end()
                $('#vaccineRouteEditForm').find('[name="vccr_description"]').val(response.routes.vccr_description).end()
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

