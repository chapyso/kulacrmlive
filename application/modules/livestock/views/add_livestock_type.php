<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-home"></i> <?php echo lang('livestock_type'); ?>
                        </header>
                        <div class="panel-body">
                            <div class="adv-table editable-table ">
                                <div class="space15"></div>

                                <table class="table table-striped table-hover table-bordered" id="editable-sample">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;"><?php echo lang('serialNo'); ?></th>
                                            <th style="width: 15%;"><?php echo lang('lst_title'); ?></th>
                                            <th style="width: 15%;"><?php echo lang('livestock_title'); ?></th>
                                            <th style="width: 30%;"><?php echo lang('lst_description'); ?></th>
                                            <th style="width: 10%;"><?php echo lang('lst_next_type'); ?></th>
                                            <th style="width: 15%;"><?php echo lang('lst_days'); ?></th>
                                            <th style="width: 25%;"><?php echo lang('option'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $serial = 0;
                                        foreach ($types as $type) {
                                            $serial++;
                                        ?>
                                            <tr class="">
                                                <td> <?= $serial ?></td>
                                                <td> <?= $type->lst_title ?></td>
                                                <td> <?= $this->livestock_model->getLivestockById($type->lst_ls_id)->ls_name; ?></td>
                                                <td> <?= $type->lst_description ?></td>
                                                <td>
                                                    <?php $nextTypeStep = $this->livestock_model->getLivestockTypeIncreasingByTypeId($type->lst_id);
                                                    if (!empty($nextTypeStep)) {
                                                        echo $this->livestock_model->getLivestockTypeById($nextTypeStep->lstin_next_lst_id)->lst_title;
                                                    } else {
                                                        echo "<span class='text-danger'>Not assign</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td> <?php $getDays = $this->livestock_model->getLivestockTypeIncreasingByTypeId($type->lst_id);
                                                        if ($getDays) {
                                                            echo $getDays->lstin_days;
                                                        } else {
                                                            echo "<span class='text-danger'>No Days Found</span>";
                                                        }
                                                        ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm editButton" data-toggle="modal" data-id="<?php echo $type->lst_id; ?>"><i class="fa fa-edit"></i> <?php echo lang('edit'); ?></button>
                                                    <form action="<?php echo base_url('livestock/deleteLivestockType'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?= lang('are_you_sure_want_to_delete_this_item'); ?>');">
                                                        <input type="hidden" name="lst_id" value="<?php echo $type->lst_id; ?>">
                                                        <input type="hidden" name="action_token" value="<?php echo action_token(); ?>">
                                                        <button type="submit" class="button button-danger"><i class="fas fa-trash"></i> <?php echo lang('delete'); ?></button>
                                                    </form>

                                                    <?php $getDays = $this->livestock_model->getLivestockTypeIncreasingByTypeId($type->lst_id);
                                                    if ($getDays) {
                                                        echo "<a data-lstin_id='$getDays->lstin_id' data-type_title='$type->lst_title' class='btn btn-warning btn-sm updateAssignToButton' data-target='#myModal4' data-toggle='modal'>Update</a>";
                                                    } else {
                                                        echo "<a iid='$type->lst_id' title='$type->lst_title' class='btn btn-success btn-sm assignToButton' data-target='#myModal3' data-toggle='modal'>Assign</a>";
                                                    }
                                                    ?>

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

            <div class="card">
                <div class="col-md-4">
                    <!-- page start-->
                    <section class="panel">
                        <header class="panel-heading">
                            <?php
                            if (!empty($patient->id))
                                echo '<i class="fa fa-edit"></i> ' . lang('edit_livestock_type');
                            else
                                echo '<i class="fa fa-plus-circle"></i> ' . lang('add_livestock_type');
                            ?>
                        </header>
                        <div class="panel-body col-md-12">
                            <div class="adv-table editable-table ">
                                <div class="clearfix">
                                    <div class="col-lg-12">
                                        <section class="panel">
                                            <div class="panel-body">
                                                <?php echo $this->session->flashdata('feedback'); ?>
                                                <div class="col-lg-12">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-6">
                                                        <?php echo validation_errors(); ?>
                                                    </div>
                                                    <div class="col-lg-3"></div>
                                                </div>
                                                <form role="form" action="<?php echo base_url('insert_livestock_type') ?>" method="post" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo lang('lst_title'); ?></label>
                                                        <input type="text" class="form-control" name="lst_title" id="exampleInputEmail1" value='' placeholder="Enter Title" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo lang('livestock_title'); ?></label>
                                                        <select name="lst_ls_id" class="form-control js-example-basic-single" id="exampleInputEmail1" placeholder="" style="width: 100%;" required>
                                                            <option value=""><?php echo lang('please_select_livestock'); ?></option>
                                                            <?php if (!empty($livestocks)) foreach ($livestocks as $livestock) { ?>
                                                                <option value="<?php echo $livestock->ls_id; ?>"> <?php echo $livestock->ls_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo lang('lst_description'); ?></label>
                                                        <textarea class="form-control" name="lst_description" id="" cols="30" rows="5" placeholder="Enter Description" style="height: auto !important;"></textarea>
                                                    </div>
                                                    <input type="hidden" name="lst_id" value=''>
                                                    <section class="">
                                                        <button type="submit" name="submit" class="btn btn-success submit_button"><?php echo lang('submit'); ?></button>
                                                    </section>
                                                </form>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- page end-->
                </div>

            </div>


        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->

<!-- Edit Type -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-edit"></i> <?php echo lang('edit_livestock_type'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" id="livestockTypeEditForm" action="<?php echo base_url('update_livestock_type') ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('ls_name'); ?></label>
                        <input type="text" class="form-control" name="lst_title" id="" value='' placeholder="Enter Title" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('livestock_title'); ?></label>
                        <select name="lst_ls_id" class="form-control js-example-basic-single" id="" placeholder="" style="width: 100%;" required>
                            <option value=""><?php echo lang('please_select_livestock'); ?></option>
                            <?php if (!empty($livestocks)) foreach ($livestocks as $livestock) { ?>
                                <option value="<?php echo $livestock->ls_id; ?>"> <?php echo $livestock->ls_name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                        <textarea name="lst_description" class="form-control" id="" rows="5" placeholder="Enter Description" style="height: auto !important;" required></textarea>
                    </div>

                    <input type="hidden" name="lst_id" value=''>

                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /. Edit Type -->

<!-- Edit Type -->
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-plus-circle"></i> <?php echo lang('livestock_type_to_assign'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" id="livestockTypeEditForm" action="<?php echo base_url('') ?>livestock/insertLivestockTypeToIncreasing" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('lst_to_growing_title'); ?></label>
                        <span id="title1"> </span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('next_growing_type'); ?></label>
                        <select name="lstin_next_lst_id" class="form-control js-example-basic-single" id="" placeholder="" style="width: 100%;" required>
                            <option value=""><?php echo lang('please_select_type'); ?></option>
                            <?php if (!empty($types)) foreach ($types as $type) { ?>
                                <option value="<?php echo $type->lst_id; ?>"> <?php echo $type->lst_title; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('growing_days'); ?></label>
                        <input type="number" class="form-control" name="lstin_days" id="" value='' placeholder="Enter Growing Day">
                    </div>

                    <input type="hidden" name="lst_id" value=''>
                    <input type="hidden" name="lstin_lst_id" value='' id="iiid">

                    <section class="">
                        <button type="submit" name="submit" class="button button-info submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /. Edit Type -->



<!-- Edit Assigned Type -->
<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><strong><i class="fa fa-edit"></i> <?php echo lang('livestock_type_to_assign_update'); ?></strong></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <form role="form" id="livestockTypeAssignEditForm" action="<?php echo base_url('') ?>livestock/updateLivestockTypeToAssign" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('lst_to_growing_title'); ?></label>
                        <span id="show_type_title"> </span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('next_growing_type'); ?></label>
                        <select name="lstin_next_lst_id" class="form-control js-example-basic-single" id="" placeholder="" style="width: 100%;" required>
                            <option value=""><?php echo lang('please_select_type'); ?></option>
                            <?php if (!empty($types)) foreach ($types as $type) { ?>
                                <option value="<?php echo $type->lst_id; ?>"> <?php echo $type->lst_title; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('growing_days'); ?></label>
                        <input type="number" class="form-control" name="lstin_days" id="" value='' placeholder="Enter Growing Day">
                    </div>

                    <input type="hidden" name="lstin_lst_id" value=''>

                    <section class="">
                        <button type="submit" name="submit" class="btn btn-success submit_button"><?php echo lang('submit'); ?></button>
                    </section>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /. Edit Assigned Type -->

<!-- Javascript For Edit Trip -->
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".editButton").click(function(e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            // alert(iid)
            $.ajax({
                url: 'livestock/editLivestockTypeByJason?lst_id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                // Populate the form fields with the data returned from server
                $('#livestockTypeEditForm').find('[name="lst_id"]').val(response.livestockType.lst_id).end()
                $('#livestockTypeEditForm').find('[name="lst_ls_id"]').val(response.livestockType.lst_ls_id).trigger('change').end()
                $('#livestockTypeEditForm').find('[name="lst_title"]').val(response.livestockType.lst_title).end()
                $('#livestockTypeEditForm').find('[name="lst_description"]').val(response.livestockType.lst_description).end()
                $('#myModal2').modal('show');
            });

        });

        // Assign to Next Step type days
        $(".assignToButton").click(function(e) {
            var iid = $(this).attr('iid');
            var title = $(this).attr('title');
            $('#myModal3').modal('show');
            $('#iiid').val(iid);
            $('#title1').html(title);
        });



        // Update Assigned type days
        $(".updateAssignToButton").click(function(e) {
            e.preventDefault(e);
            var iid = $(this).attr('data-lstin_id');
            var type_title = $(this).attr('data-type_title');
            $('#show_type_title').html(type_title);
            // alert(iid)
            $.ajax({
                url: 'livestock/editLivestockTypeToAssignByJason?lstin_id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).success(function(response) {
                // Populate the form fields with the data returned from server
                $('#livestockTypeAssignEditForm').find('[name="lstin_lst_id"]').val(response.livestockTypeToAssign.lstin_lst_id).end()
                $('#livestockTypeAssignEditForm').find('[name="lstin_next_lst_id"]').val(response.livestockTypeToAssign.lstin_next_lst_id).trigger('change').end()
                $('#livestockTypeAssignEditForm').find('[name="lstin_days"]').val(response.livestockTypeToAssign.lstin_days).end()
                $('#myModal4').modal('show');
            });

        });


    });
</script>


<script>
    $(document).ready(function() {
        $(".success_flash_message").delay(3000).fadeOut(100);
    });
</script>