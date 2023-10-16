<div class="row">
    <div class="col-lg-8 offset-lg-1">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
</div>
<div class="row">
    <div class="col-lg-8 offset-lg-1">
        <?= form_open("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?= lang("lab_category_name", "name", array(), true) ?>:
                            <?= form_input($name);?>
                            <div class="invalid-feedback"><?php echo form_error('name'); ?></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?= lang("lab_category_sort", "sort") ?>:
                            <?= form_input($sort);?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-t-20 text-center">
                <button class="btn btn-primary submit-btn"><?= lang("lab_category_update_button") ?></button>
                <a role="button" class="btn btn-primary submit-btn" href="<?= site_url("doctor/lab_categories") ?>"><?= lang("lab_category_cancel_button") ?></a>
            </div>
        <?= form_close(); ?>
    </div>
</div>
