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
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?php echo lang('create_user_lname_label', 'last_name');?>
                                <span class="text-danger">*</span> <br />
                                <?php echo form_input($last_name);?>
                                <div class="invalid-feedback"><?php echo form_error('last_name'); ?></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?php echo lang('create_user_fname_label', 'first_name');?>
                                <span class="text-danger">*</span> <br />
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
                                <div class="cal-icon"><?php echo form_input($dob);?></div>
                                <div class="invalid-feedback"><?php echo form_error('dob'); ?></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group gender-select!">
                                <?php echo lang('create_user_gender_label', 'gender', array("class"=>"gen-label1"));?>
                                <br />
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
                                <?php echo lang('create_user_doctors_types_label', 'job_title');?>
                                <?php echo form_input($job_title);?>
                                <div class="invalid-feedback"><?php echo form_error('job_title'); ?></div>
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
									<?php $city_options = $cities; ?>
									<?php echo lang('create_user_city_label', 'city_id', array(), true);?>
									<?= form_dropdown($city, $city_options, $selected_city_id); ?>
									<div class="invalid-feedback"><?php echo form_error('city_id'); ?></div>
                                </div>
                                <div class="col-sm-4">
                                    <?php echo lang('create_user_address_label', 'address');?><br />
                                    <?php echo form_input($address);?>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <?php echo lang('create_user_phone_label', 'phone');?>
                                        <?php echo form_input($phone);?>
                                        <div class="invalid-feedback"><?php echo form_error('phone'); ?></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <?php echo lang('create_user_email_label', 'email');?>
                                        <?php echo form_input($email);?>
                                        <div class="invalid-feedback"><?php echo form_error('email'); ?></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <?php echo lang('create_user_company_label', 'company');?>
                                        <?php echo form_input($company);?>
                                        <div class="invalid-feedback"><?php echo form_error('company'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo lang('create_user_doctor_percent_label', 'agreement');?><br />
                                    <?php echo form_input($agreement);?>
                                </div>
                                <div class="col-sm-4">
                                    <?php echo lang('create_user_partner_type_label', 'type', array(), true);?>
                                    <?= form_dropdown($type, $type_options); ?>
                                    <div class="invalid-feedback"><?php echo form_error('type'); ?></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

		<div id="partner_service_module_box" class="card-box">
			<div class="row">
				<div class="col-sm-4">
					Хизмат тури
					<?= form_dropdown($service_module_id[0], $service_module_options); ?>
					<div class="invalid-feedback"><?php echo form_error('region_id'); ?></div>
				</div>
				<div class="col-sm-4">
					Улуш %<br />
					<?php echo form_input($share[0]);?>
				</div>
				<div class="col-sm-4">
					&nbsp;<br>
					<div class="btn-group" role="group" aria-label="...">
						<button type="button" class="btn btn-primary js_muliply_service_module"><span class="fa fa-plus"></span></button>
						<button type="button" class="btn btn-danger js_remove_service_module d-none"><span class="fa fa-minus"></span></button>
					</div>
				</div>
			</div>
		</div>

        <div class="m-t-20 text-center">
            <button class="btn btn-primary submit-btn"><?= lang("edit_user_submit_btn") ?></button>
            <a role="button" class="btn btn-secondary submit-btn" href="<?= site_url("admin/partners") ?>"><?= lang("user_cancel_button") ?></a>
        </div>
        <?= form_close(); ?>
    </div>
</div>
