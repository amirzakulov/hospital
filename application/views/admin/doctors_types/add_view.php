<div class="row">
    <div class="col-lg-8 offset-lg-1">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 offset-lg-1">
        <?= form_open("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
            <div class="card-box">
<!--                --><?php //if(isset($message)) echo $message; ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?= lang("name", "name") ?>:
                            <span class="text-danger">*</span> <br />
                            <?= form_input($name);?>
                            <div class="invalid-feedback"><?php echo form_error('name'); ?></div>
                        </div>
                        <div class="form-group">
                            <?= lang('description', 'description');?>:
                            <?= form_textarea($description);?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-t-20 text-center">
                <button class="btn btn-primary submit-btn"><?= lang("general_save") ?></button>
                <a href="<?= site_url("admin/doctors_types") ?>" class="btn btn-primary submit-btn"><?= lang("general_cancel") ?></a>
            </div>
        <?= form_close(); ?>
    </div>
</div>
