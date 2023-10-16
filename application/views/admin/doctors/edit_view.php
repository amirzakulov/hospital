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
                        <div class="row" style="box-shadow: 0 1px 0 rgba(0,0,0,0.1); margin-bottom: 10px;">
                            <div class="col-sm-12"><h5>Username: <?= $doctor["username"]; ?> <br></h5></div>
                        </div>
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
                                    <?php echo lang('create_user_dob_label', 'dob', array(), true);?>
                                    <div class="cal-icon"><?php echo form_input($dob);?></div>
                                    <div class="invalid-feedback custom-invalid-feedback"><?php echo form_error('dob'); ?></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <?php echo lang('create_user_gender_label', 'gender', array(), true);?>
                                    <br>
                                    <div class="form-check">
                                        <?= form_radio($gender_male, 1, set_checkbox('gender', 1, ($doctor["gender"] == 1 ? true:false)), "id='gender_male'") ?>
                                        <label class="form-check-label" for="gender_male"><?= lang("create_user_gender_male_label") ?></label>
                                    </div>
                                    <div class="form-check">
                                        <?= form_radio($gender_female, 2, set_checkbox('gender', 2, ($doctor["gender"] == 2 ? true:false)), "id='gender_female'") ?>
                                        <label class="form-check-label" for="gender_female"><?= lang("create_user_gender_female_label") ?></label>
                                    </div>
                                    <div class="invalid-feedback custom-invalid-feedback"><?php echo form_error('gender'); ?></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <?php echo lang('create_user_phone_label', 'phone');?>
                                    <?php echo form_input($phone);?>
                                    <div class="invalid-feedback"><?php echo form_error('phone'); ?></div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <?php $region_options = $regions ?>
                                            <?php echo lang('create_user_region_label', 'region_id', array(), true);?>
                                                <?= form_dropdown($region, $region_options, $doctor["region_id"]); ?>
                                            <div class="invalid-feedback"><?php echo form_error('region_id'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php $city_options = $cities; ?>
                                        <?php echo lang('create_user_city_label', 'city_id', array(), true);?>
                                        <?= form_dropdown($city, $city_options, $doctor["city_id"]); ?>
                                        <div class="invalid-feedback"><?php echo form_error('city_id'); ?></div>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php echo lang('create_user_address_label', 'address');?><br />
                                        <?php echo form_input($address);?>
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
                                            <?php echo lang('create_user_doctor_price_label', 'price');?>
                                            <?php echo form_input($price);?>
                                            <div class="invalid-feedback"><?php echo form_error('price'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <?php echo lang('create_user_doctor_percent_label', 'agreement');?>
                                            <?php echo form_input($agreement);?>
                                            <div class="invalid-feedback"><?php echo form_error('agreement'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <?php echo lang('create_user_department_name_label', 'department_id');?>
                                    <?= form_dropdown($departments, $department_options, $doctor["department_id"]); ?>
                                    <div class="invalid-feedback"><?= form_error('department_id'); ?></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <?php echo lang('create_user_doctor_group_label', 'groups', array(), true);?>
                                    <?= form_dropdown($groups, $group_options, $doctor["group_id"]); ?>
                                    <div class="invalid-feedback"><?= form_error('group_id'); ?></div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <?php echo lang('create_user_doctors_types_label', 'doctors_types');?>
                                    <?= form_dropdown($doctors_types, $doctors_types_options, $doctor["doctor_type_id"]); ?>
                                    <div class="invalid-feedback"><?= form_error('doctor_type_id'); ?></div>
                                </div>
                            </div>


                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Расм</label>
                                    <div class="profile-upload">
                                        <div class="upload-img">
                                            <img alt="" src="<?= empty($doctor["photo"]) ? site_url("assets/admin/img/user.jpg") : site_url(EMPLOYEE_PHOTO_PATH).$doctor["photo"]; ?>">
                                        </div>
                                        <div class="upload-input">
                                            <input type="file" class="form-control" name="userfile">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mb-5">
                <label class="display-block">Холати</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="doctor_active" value="1" <?= $doctor["active"] == 1 ? "checked" : ""; ?>>
                    <label class="form-check-label" for="doctor_active">
                        Фаол
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="doctor_inactive" value="0" <?= $doctor["active"] == 0 ? "checked" : ""; ?>>
                    <label class="form-check-label" for="doctor_inactive">
                        Нофаол
                    </label>
                </div>
            </div>



		<div id="partner_service_module_box" class="card-box">
			<?php  if(count($partner_service_module) == 0) { ?>
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
			<?php } else { ?>
				<?php foreach ($partner_service_module as $key => $service_module) { ?>
					<div class="row">
						<div class="col-sm-4">
							Хизмат тури
							<?= form_dropdown($service_module_id[$key], $service_module_options, $service_module["service_module_id"]); ?>
							<div class="invalid-feedback"><?php echo form_error('region_id'); ?></div>
						</div>
						<div class="col-sm-4">
							Улуш %<br />
							<?php echo form_input($share[$key]);?>
						</div>
						<div class="col-sm-4">
							&nbsp;<br>
							<div class="btn-group" role="group" aria-label="...">
								<button type="button" class="btn btn-primary js_muliply_service_module <?= !$key ? "":"d-none"; ?>"><span class="fa fa-plus"></span></button>
								<button type="button" class="btn btn-danger js_remove_service_module <?= !$key ? "d-none":""; ?>"><span class="fa fa-minus"></span></button>
							</div>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		</div>



            <div class="m-t-20 text-center">
                <button class="btn btn-primary submit-btn"><?= lang("doctor_save") ?></button>
                <a role="button" class="btn btn-secondary submit-btn" href="<?= site_url("admin/doctors/profile/".$doctor["id"]) ?>"><?= lang("user_cancel_button") ?></a>
            </div>
        <?= form_close(); ?>
    </div>
</div>
