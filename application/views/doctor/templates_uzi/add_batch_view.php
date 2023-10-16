<div class="row">
    <div class="col-lg-8 offset-lg-1">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
</div>
<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <?= form_open_multipart("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
        <div class="card-box">

            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <?php echo lang('add_lang_label', 'lang', array(), true);?>
                                <?= form_dropdown($lang, $lang_options); ?>
                                <div class="invalid-feedback"><?= form_error('lang'); ?></div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <?php echo lang('add_uzi_name_label', 'uzi_id', array(), true);?>
                                <?= form_dropdown($uzi_id, $uzi_id_options); ?>
                                <div class="invalid-feedback"><?= form_error('uzi_id'); ?></div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <?php echo lang('add_file_upload_label', 'userfile', array(), true);?><br />
                                <?php echo form_upload($template_file_upload);?>
                                <div class="invalid-feedback"><?php echo form_error('userfile'); ?></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="m-t-20 text-center">
            <button class="btn btn-primary submit-btn"><?= lang("edit_user_submit_btn") ?></button>
            <a role="button" class="btn btn-secondary submit-btn" href="<?= site_url("doctor/templates_uzi") ?>"><?= lang("user_cancel_button") ?></a>
        </div>
        <?= form_close(); ?>
    </div>
</div>

