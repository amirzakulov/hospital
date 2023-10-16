<div class="row">
    <div class="col-lg-8">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <?= form_open("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
        <div class="card-box">
            <div class="alert alert-dark"><span class="fa fa-folder-o"></span> <strong><?= $category["name"] ?></strong></div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_name", "name", array(), true) ?>:<br />
                        <?= form_input($name);?>
                        <div class="invalid-feedback"><?php echo form_error('name'); ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_price", "price", array(), true) ?>:<br />
                        <?= form_input($price);?>
                        <div class="invalid-feedback"><?php echo form_error('price'); ?></div>
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
                        <?= lang("laboratory_norma", "norma") ?>:<br />
                        <?= form_textarea($norma);?>
                        <div class="invalid-feedback"><?php echo form_error('norma'); ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_mesurment", "mesurment") ?>:<br />
                        <?= form_input($mesurment);?>
                        <div class="invalid-feedback"><?php echo form_error('mesurment'); ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_default_value", "default_value") ?>:<br />
                        <?= form_input($default_value);?>
                        <div class="invalid-feedback"><?php echo form_error('default_value'); ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_partner", "partner_id") ?>:<br />
                        <?= form_dropdown($partner_id, $partners);?>
                        <div class="invalid-feedback"><?php echo form_error('partner_id'); ?></div>
                    </div>
                </div>
            </div>
            <div class="row <?= empty(form_error('price_partner')) ? "d-none":"" ?>">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_price_partner", "price_partner", array(), true) ?>:<br />
                        <?= form_input($price_partner);?>
                        <div class="invalid-feedback"><?= form_error('price_partner'); ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("laboratory_recommendation", "recommendation") ?>:<br />
                        <?= form_textarea($recommendation);?>
                        <div class="invalid-feedback"><?php echo form_error('recommendation'); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-t-20 text-center">
            <button class="btn btn-primary submit-btn" tabindex="4"><?= lang("laboratory_create_button") ?></button>
            <button class="btn btn-primary submit-btn" tabindex="5" name="renew"><?= lang("laboratory_renew_button") ?></button>
            <a role="button" class="btn btn-secondary submit-btn" tabindex="5" href="<?= site_url("admin/laboratory") ?>"><?= lang("laboratory_cancel_button") ?></a>
        </div>
        <?= form_close(); ?>
    </div>
    <div class="col-lg-4">
        <div class="card-box">
            <div class="card-title">Лабораториялар</div>
            <div class="row">
                <div class="col-sm-12">
                <?php foreach ($sub_categories as $lab) {?>
                    <div class="alert alert-dark" role="alert"><?= $lab["name"]; ?></div>
                <?php } ?>
                </div>
            </div>
        </div>
    </div>
