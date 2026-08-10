<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="row">
            <div class="col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-sm-10 col-sm-offset-1" style="margin: 0 auto; float: none;">
                <section class="panel shadow-sm border-0 rounded-lg">
                    <header class="panel-heading bg-info text-white p-3 font-weight-bold" style="font-size: 18px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa fa-user"></i> <?php echo lang('manage_profile'); ?>
                    </header>
                    <div class="panel-body p-4 bg-white" style="padding: 25px;">
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger mb-3">
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success mb-3">
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="text-center mb-4" style="text-align: center; margin-bottom: 25px;">
                            <div class="avatar-circle mx-auto mb-2" style="width: 80px; height: 80px; background: #e8f5e9; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #00a896; font-size: 36px; margin: 0 auto 10px;">
                                <i class="fa fa-user"></i>
                            </div>
                            <h4 class="mb-1 font-weight-bold" style="color: #2c3e50; margin-top: 5px;">
                                <?php echo !empty($profile->username) ? html_escape($profile->username) : 'User Profile'; ?>
                            </h4>
                            <p class="text-muted" style="color: #6c757d; font-size: 14px;">
                                <?php echo !empty($profile->email) ? html_escape($profile->email) : ''; ?>
                            </p>
                        </div>

                        <form role="form" action="<?php echo base_url('profile/addNew') ?>" method="post">
                            <input type="hidden" name="id" value="<?php echo !empty($profile->id) ? html_escape($profile->id) : ''; ?>">

                            <div class="form-group" style="margin-bottom: 18px;">
                                <label style="font-weight: 600; margin-bottom: 6px;"><?php echo lang('name'); ?> / Username</label>
                                <input type="text" class="form-control" name="name" required value="<?php echo !empty($profile->username) ? html_escape($profile->username) : ''; ?>" placeholder="Enter Name" style="height: 42px; border-radius: 6px;">
                            </div>

                            <div class="form-group" style="margin-bottom: 18px;">
                                <label style="font-weight: 600; margin-bottom: 6px;"><?php echo lang('email'); ?></label>
                                <input type="email" class="form-control" name="email" required value="<?php echo !empty($profile->email) ? html_escape($profile->email) : ''; ?>" placeholder="Enter Email" style="height: 42px; border-radius: 6px;">
                            </div>

                            <div class="form-group" style="margin-bottom: 24px;">
                                <label style="font-weight: 600; margin-bottom: 6px;"><?php echo lang('change_password'); ?></label>
                                <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current password" autocomplete="new-password" style="height: 42px; border-radius: 6px;">
                                <small class="form-text text-muted" style="color: #888; margin-top: 4px; display: block;">Only fill this if you want to update your password (minimum 5 characters).</small>
                            </div>

                            <button type="submit" name="submit" class="btn btn-info w-100 font-weight-bold" style="width: 100%; padding: 12px; background-color: #00a896; border-color: #00a896; color: white; font-weight: bold; border-radius: 6px; font-size: 16px;">
                                <i class="fa fa-save"></i> <?php echo lang('edit_button'); ?>
                            </button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<!--footer start-->

<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>

<script>
    $(document).ready(function() {
        $(".success_flash_message").delay(3000).fadeOut(100);
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