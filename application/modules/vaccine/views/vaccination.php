<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="row">
            <div class="col-md-12">
                <section class="panel">
                    <header class="panel-heading">
                        <i class="fa fa-money"></i> <?php echo lang('vaccination'); ?>
                    </header>
                    <div class="panel-body">
                        <div class="adv-table editable-table ">
                            <div class="space15"></div>
                            <table class="table table-striped table-hover table-bordered" id="editable-sample">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('serialNo'); ?></th>
                                        <th><?php echo lang('vaccine_id'); ?></th>
                                        <th><?php echo lang('vaccine_name'); ?></th>
                                        <th><?php echo lang('vaccine_type'); ?></th>
                                        <th><?php echo lang('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial = 0;
                                    foreach ($vaccines as $vaccine) {
                                        $serial++;
                                    ?>
                                        <tr class="">
                                            <td><?= $serial; ?></td>
                                            <td><?= $vaccine->vcc_vaccine_id; ?></td>
                                            <td><?= $vaccine->vcc_name; ?></td>
                                            <td><?= $vaccine->vcc_type; ?></td>
                                            <td>
                                                <a data-id="<?= $vaccine->vcc_id; ?>" vaccine-id="<?= $vaccine->vcc_vaccine_id; ?>" vaccine-name="<?= $vaccine->vcc_name; ?>" class="btn btn-xs btn-primary assignToVaccination" data-toggle="modal"><i class="fa fa-plus-circle"></i> <?= lang('assign'); ?></a>
                                                <a href="<?php echo base_url('') ?>vaccine/viewVaccinationList?vcc_id=<?= $vaccine->vcc_id ?>" class=" btn btn-xs btn-success"><i class="fa fa-eye"></i> <?= lang('view'); ?></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<!--footer start-->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?php echo lang('assign_to_vaccination'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" action="<?php echo base_url('vaccine/insertVaccination'); ?>" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <div class="row border">
                            <div class="col-xs-6">
                                <p><strong><?= lang('vaccine_name'); ?>: </strong><span id="vccn_vaccine_id"></span> </p>
                            </div>
                            <div class="col-xs-6">
                                <p><strong><?= lang('vaccine_id'); ?>: </strong> <span id="vccn_name"></span></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo lang('ls_name_variant_modal'); ?></label>
                                <select name="vccn_ls_id" id="vccn_ls_id" class="form-control js-example-basic-single " placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_livestock'); ?> </option>
                                    <?php if (!empty($livestocks)) foreach ($livestocks as $livestock) { ?>
                                        <option value="<?php echo $livestock->ls_id; ?>"> <?php echo $livestock->ls_name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group ">
                                <label for="exampleInputEmail1"><?php echo lang('ls_type_title'); ?></label>
                                <select name="vccn_lst_id" class="form-control js-example-basic-single" id="livestock_type" placeholder="" style="width: 100%;">
                                    <option value=""><?= lang('please_select_variant'); ?> </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo lang('vaccine_dose'); ?></label>
                                <select name="vccn_vd_id[]" id="vccn_vd_id" class="form-control js-example-basic-single" multiple placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_dose'); ?> </option>
                                    <?php if (!empty($vaccine_doses)) foreach ($vaccine_doses as $dose) { ?>
                                        <option value="<?php echo $dose->vd_id; ?>"> <?php echo $dose->vd_name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo lang('vaccine_route'); ?></label>
                                <select name="vccn_route" class="form-control js-example-basic-single" placeholder="" style="width: 100%;" required>
                                    <option value=""><?= lang('please_select_route'); ?></option>
                                    <?php if (!empty($vaccineRoutes))
                                        foreach ($vaccineRoutes->result() as $route) { ?>
                                        <option value="<?php echo $route->vccr_id; ?>"><?php echo $route->vccr_name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo lang('vaccination_start_day'); ?></label>
                                <input type="number" class="form-control" name="vccn_start_day" id="" value='' placeholder="">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo lang('vaccination_end_day'); ?></label>
                                <input type="number" class="form-control" name="vccn_end_day" id="" value='' placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label note"><?php echo lang('description'); ?></label>
                        <div class="note">
                            <textarea class="form-control" name="vccn_description" value="" rows="5" cols="10" style="height: auto !important;"> </textarea>
                        </div>
                    </div>
                    <input type="hidden" name="vccn_vcc_id" id="vccn_id" value=''>
                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<!-- View Vaccination -->
<div class="modal" id="modal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><i class="fa fa-eye"></i> <?= lang('view_vaccination_start_end_day'); ?></h4>
            </div>
            <div><span class="vcc_name"></span></div>
            <div class="modal-body">
                <table class="table table-striped table-hover table-bordered" id="editable-sample">
                    <thead>
                        <tr>
                            <th> <?php echo lang('id'); ?></th>
                            <th> <?php echo lang('date'); ?></th>
                            <th> <?php echo lang('reference'); ?></th>
                            <th> <?php echo lang('amount'); ?></th>
                            <th> <?php echo lang('paid_by'); ?></th>
                            <th class="option_th"> <?php echo lang('options'); ?></th>
                        </tr>
                    </thead>
                    <tbody class="viewVaccination">

                    </tbody>
                </table>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Add Payment To That Car -->



<!-- Javascript For Edit Trip -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Assign Vaccination
        $(".assignToVaccination").click(function() {
            var iid = $(this).attr('data-id');
            var vaccine_id = $(this).attr('vaccine-id');
            var vaccine_name = $(this).attr('vaccine-name');
            $("#myModal").modal('show');
            $("#vccn_id").val(iid);
            $("#vccn_vaccine_id").text(vaccine_id);
            $("#vccn_name").text(vaccine_name);
        });

        // Cascading Dropdown
        $("#vccn_ls_id").on('change', function() {
            var iid = $('#vccn_ls_id').val();
            if (iid != '') {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('purchase/getLivestockTypeCascadingDropdown'); ?>',
                    data: {
                        livestock_id: iid,
                    },
                    success: function(data) {
                        $('#livestock_type').html(data);
                    }
                });
            }
        });


    });
</script>

