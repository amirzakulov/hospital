<div class="row">
    <div class="col-lg-8">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <?= form_open("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
        <div class="card-box">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo lang('general_name', 'name', array(), true);?>
                        <?php echo form_input($name);?>
                        <div class="invalid-feedback"><?php echo form_error('name'); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="m-t-20 text-center">
            <button class="btn btn-primary submit-btn"><?= lang("general_add") ?></button>
            <a role="button" class="btn btn-secondary submit-btn" href="<?= site_url("admin/payment_types") ?>"><?= lang("user_cancel_button") ?></a>
        </div>
        <?= form_close(); ?>
    </div>
</div>
