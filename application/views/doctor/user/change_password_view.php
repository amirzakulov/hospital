<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-sm-8 col-9 text-right m-b-20"></div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="content">
            <div class="row">
                <div class="col-md-8">
                    <?= form_open("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php echo lang('general_enter_new_password', 'password', array(), true);?><br />
                                <?php echo form_input($password);?>
                                <div class="invalid-feedback"><?php echo form_error('password'); ?></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php echo lang('general_confirm_password', 'confirm_password', array(), true);?><br />
                                <?php echo form_input($confirm_password);?>
                                <div class="invalid-feedback"><?php echo form_error('confirm_password'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-center m-t-20">
                            <button class="btn btn-primary submit-btn"><?= lang("general_save") ?></button>
                            <a role="button" class="btn btn-secondary submit-btn" href="<?= site_url("doctor/user") ?>"><?= lang("user_cancel_button") ?></a>
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>