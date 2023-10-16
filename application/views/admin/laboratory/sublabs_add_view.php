<div class="row">
    <div class="col-lg-8">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
</div>
<div class="row">
    <div class="col-lg-5">
        <div class="card-box">
            <h3 class="card-title">
                <span class="fa fa-folder-o"></span>
                <strong><?= $laboratory["name"]; ?></strong>
            </h3>

            <div class="experience-box overflow-auto" style="max-height: 25rem;">
                <?php if(count($subcategories) == 0) {?>
                    <?= lang("laboratory_sublab_not_add") ?>
                <?php } else { ?>
                    <ul class="experience-list">
                        <?php foreach ($subcategories as $subcategory) {?>
                            <li>
                                <div class="experience-user"><div class="before-circle"></div></div>
                                <div class="experience-content">
                                    <div class="timeline-content">
                                        <div class="pt-3">
                                            <a href="javascript: void (0);" class="name name--mb"><?= $subcategory["name"]; ?></a>
                                        </div>
                                        <div><strong>Norma:</strong> <?= $subcategory["norma"]; ?></div>
                                        <div><strong><?= lang("laboratory_mesurment"); ?>:</strong> <?= $subcategory["mesurment"]; ?></div>
                                        <div><strong>Тартиб рақами:</strong> <?= $subcategory["sort"]; ?></div>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <?= form_open("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
        <div class="card-box">
            <div class="alert alert-dark"><span class="fa fa-folder-o"></span>
                <strong><?= $laboratory["name"]; ?></strong>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_subname", "name", array(), true) ?>:<br />
                        <?= form_input($name);?>
                        <div class="invalid-feedback"><?php echo form_error('name'); ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_sort", "sort") ?>:<br />
                        <?= form_input($sort);?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_norma", "norma", array(), true) ?>:<br />
                        <?= form_textarea($norma);?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_mesurment", "mesurment") ?>:<br />
                        <?= form_input($mesurment);?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_default_value", "default_value") ?>:<br />
                        <?= form_input($default_value);?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_recommendation", "recommendation") ?>:<br />
                        <?= form_textarea($recommendation);?>
                    </div>
                </div>
            </div>
        </div>


        <div class="m-t-20 text-center">
			<button class="btn btn-primary submit-btn" tabindex="20" name="renew" value="renew"><?= lang("laboratory_save_renew_button") ?></button>
			<button class="btn btn-primary submit-btn" tabindex="21"><?= lang("laboratory_save_button") ?></button>
            <a role="button" class="btn btn-secondary submit-btn" tabindex="22" href="<?= site_url("admin/laboratory/sublabs/".$parent_lab_id) ?>"><?= lang("laboratory_cancel_button") ?></a>
        </div>
        <?= form_close(); ?>
    </div>

</div>
