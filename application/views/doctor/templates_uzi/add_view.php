<div class="row">
    <div class="col-lg-8 offset-lg-1">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
</div>
<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <?= form_open("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
        <div class="card-box">

            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <?php echo lang('add_title_label', 'title', array(), true);?><br />
                                <?php echo form_input($template_title);?>
                                <div class="invalid-feedback"><?php echo form_error('title'); ?></div>
                            </div>
                        </div>
						<div class="col-sm-12">
							<div class="form-group">
								<?php echo lang('add_uzi_name_label', 'uzi_id', array(), true);?>
								<?= form_multiselect($uzi_id, $uzi_id_options); ?>
								<div class="invalid-feedback"><?= form_error('uzi_id'); ?></div>
							</div>
						</div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <?php echo lang('add_template_label', 'template');?>
                                <?php echo form_textarea($template_text);?>
                                <div class="invalid-feedback"><?php echo form_error('template'); ?></div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

        <div class="m-t-20 text-center">
            <button class="btn btn-primary submit-btn"><?= lang("edit_user_submit_btn") ?></button>
            <a role="button" class="btn btn-primary submit-btn" href="<?= site_url("doctor/templates_uzi") ?>"><?= lang("user_cancel_button") ?></a>
        </div>
        <?= form_close(); ?>
    </div>
</div>

