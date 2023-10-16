<div class="row">
    <div class="col-lg-8">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <?= form_open("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?= lang("room_types_name", "name", array(), true) ?>:<br />
                            <?= form_input($name);?>
                            <div class="invalid-feedback"><?php echo form_error('name'); ?></div>
                        </div>
                        <div class="form-group">
                            <?= lang("room_types_price", "price") ?>:<br />
                            <?= form_input($price);?>
                            <div class="invalid-feedback"><?php echo form_error('price'); ?></div>
                        </div>
                        <div class="form-group">
                            <?= lang("room_types_conditions", "conditions") ?>:<br />
                            <?= form_multiselect($conditions, $conditions_options, $selected_conditions); ?>
                            <div class="invalid-feedback"><?php echo form_error('conditions[]'); ?></div>
                        </div>
                        <div class="form-group">
                            <?= lang("room_types_sort", "sort") ?>:<br />
                            <?= form_input($sort);?>
                            <div class="invalid-feedback"><?php echo form_error('sort'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-t-20 text-center">
                <button class="btn btn-primary submit-btn"><?= lang("general_save") ?></button>
                <a href="<?= site_url("admin/room_types") ?>" class="btn btn-secondary submit-btn"><?= lang("general_cancel") ?></a>
            </div>
        <?= form_close(); ?>
    </div>
</div>
