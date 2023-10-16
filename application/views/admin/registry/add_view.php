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
                        <?php echo lang('create_user_lname_label', 'last_name', array(), true);?>
                        <?php echo form_input($last_name);?>
                        <div class="invalid-feedback"><?php echo form_error('last_name'); ?></div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo lang('create_user_fname_label', 'first_name', array(), true);?>
                        <?php echo form_input($first_name);?>
                        <div class="invalid-feedback"><?php echo form_error('first_name'); ?></div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo lang('create_user_surname_label', 'surname');?> <br />
                        <?php echo form_input($surname);?>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo lang('create_user_dob_label', 'dob');?>
                        <?php echo form_input($dob);?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group gender-select!">
                        <?php echo lang('create_user_gender_label', 'gender', array("class"=>"gen-label1"));?><br />
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" name="gender" value="1" <?= set_checkbox('gender', '1'); ?> class="form-check-input">Эркак
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" name="gender" value="2" <?= set_checkbox('gender', '2'); ?> class="form-check-input">Аёл
                            </label>
                        </div>
                        <div class="invalid-feedback"><?php echo form_error('gender'); ?></div>
                    </div>
                </div>
				<div class="col-sm-4">
					<div class="form-group">
						<?php echo lang('create_user_phone_label', 'phone');?>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">+998</span>
							</div>
							<?php echo form_input($phone);?>
						</div>
						<div class="invalid-feedback"><?php echo form_error('phone'); ?></div>
					</div>
				</div>

                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-4 col-md-4 col-lg-4">
                            <div class="form-group">
                                <?php $region_options = $regions ?>
                                <?php echo lang('create_user_region_label', 'region_id', array(), true);?>
                                <?= form_dropdown($region, $region_options, $selected_region_id); ?>
                                <div class="invalid-feedback"><?php echo form_error('region_id'); ?></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?php $city_options = $cities; ?>
                                <?php echo lang('create_user_city_label', 'city_id', array(), true);?>
                                <?= form_dropdown($city, $city_options, $selected_city_id); ?>
                                <div class="invalid-feedback"><?php echo form_error('city_id'); ?></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?php echo lang('create_user_address_label', 'address');?><br />
                                <?php echo form_input($address);?>
                            </div>
                        </div>
<!--                        <div class="col-sm-4">-->
<!--                            <div class="form-group">-->
<!--                                --><?php //echo lang('create_user_phone_label', 'phone');?>
<!--                                <div class="input-group">-->
<!--                                    <div class="input-group-prepend">-->
<!--                                        <span class="input-group-text">+998</span>-->
<!--                                    </div>-->
<!--                                    --><?php //echo form_input($phone);?>
<!--                                </div>-->
<!--                                <div class="invalid-feedback">--><?php //echo form_error('phone'); ?><!--</div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="col-sm-4">-->
<!--                            <div class="form-group">-->
<!--                                --><?php //echo lang('create_user_email_label', 'email');?><!--<br />-->
<!--                                --><?php //echo form_input($email);?>
<!--                                <div class="invalid-feedback">--><?php //echo form_error('email'); ?><!--</div>-->
<!--                            </div>-->
<!--                        </div>-->

                    </div>
                </div>
            </div>
        </div>

        <?= $services_template; ?>

        <div class="form-group">
            <?= lang('create_user_description_label', 'description');?>
            <?= form_textarea($description); ?>
        </div>
        <div class="form-group">
            <label class="display-block">Status</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="user_status" id="doctor_active" value="1">
                <label class="form-check-label" for="doctor_active">
                    Фаол
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="user_status" id="doctor_inactive" value="0" checked>
                <label class="form-check-label" for="doctor_inactive">
                    Но-фаол
                </label>
            </div>
        </div>

        <div class="m-t-20 text-center">
            <button class="btn btn-primary submit-btn" name="print_cheque"><?= lang("create_user_submit_btn") ?></button>
			<button class="btn btn-primary submit-btn" tabindex="5"><?= lang("general_save") ?></button>
            <a role="button" class="btn btn-secondary submit-btn" href="<?= site_url("admin/registry") ?>"><?= lang("user_cancel_button") ?></a>
        </div>
        <?= form_close(); ?>
    </div>
</div>
