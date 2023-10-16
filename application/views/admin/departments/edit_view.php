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
                            <?= lang("departments_parent", "parent_id") ?>:<br />
                            <?= form_dropdown($parent_department, $department_options, $department["parent_id"]);?>
                            <div class="invalid-feedback"><?php echo form_error('room_type_id'); ?></div>
                        </div>
                        <div class="form-group">
                            <?= lang("departments_name", "name", array(), true) ?>:<br />
                            <?= form_input($name);?>
                            <div class="invalid-feedback"><?php echo form_error('name'); ?></div>
                        </div>
                        <div class="form-group">
                            <?= lang('departments_description', 'description');?>:
                            <?= form_textarea($description);?>
                        </div>
                        <div class="form-group">
                            <?= lang('departments_status', 'status', array("class"=>"display-block"));?>
                            <div class="form-check form-check-inline">
                                <?= form_radio('status', 1, TRUE, array("id"=>"status_active"));?>
                                <?= lang('departments_status_opt1', 'status_active', array("class"=>"form-check-label"));?>
                            </div>
                            <div class="form-check form-check-inline">
                                <?= form_radio('status', 0, FALSE, array("id"=>"status_inactive"));?>
                                <?= lang('departments_status_opt2', 'status_inactive', array("class"=>"form-check-label"));?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="m-t-20 text-center">
                <button class="btn btn-primary submit-btn"><?= lang("general_save") ?></button>
                <a href="<?= site_url("admin/departments") ?>" class="btn btn-secondary submit-btn"><?= lang("general_cancel") ?></a>
            </div>
        <?= form_close(); ?>
    </div>
</div>
