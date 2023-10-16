<div class="row">
    <div class="col-lg-8">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <?= form_open("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
        <div class="card-box">
            <div class="alert alert-dark"><span class="fa fa-folder-o"></span>
                <strong><?= $laboratory["name"]; ?></strong>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_subname", "name", array(), true); ?>:<br />
                        <?= form_input($name); ?>
                        <div class="invalid-feedback"><?php echo form_error('name'); ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_sort", "sort"); ?>:<br />
                        <?= form_input($sort); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_norma", "norma", array(), true); ?>:<br />
                        <?= form_textarea($norma);?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_mesurment", "mesurment"); ?>:<br />
                        <?= form_input($mesurment); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_default_value", "default_value"); ?>:<br />
                        <?= form_input($default_value); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_recommendation", "recommendation"); ?>:<br />
                        <?= form_textarea($recommendation); ?>
                    </div>
                </div>
            </div>
        </div>


        <div class="m-t-20 text-center">
            <button class="btn btn-primary submit-btn" tabindex="4"><?= lang("laboratory_save_button"); ?></button>
            <a role="button" class="btn btn-secondary submit-btn" tabindex="5" href="<?= site_url("admin/laboratory"); ?>"><?= lang("laboratory_cancel_button"); ?></a>
        </div>
        <?= form_close(); ?>
    </div>
</div>
