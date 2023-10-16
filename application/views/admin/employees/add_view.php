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
                        <div class="col-sm-12"><h5>Username: <?= $username; ?> <br></h5></div>
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
                                <?php echo lang('create_user_gender_label', 'gender', array("class"=>"gen-label1"), true);?>
                                <br>
                                <div class="form-check">
                                    <?= form_radio($gender_male, 1, set_checkbox('gender', 1), "id='gender_male'") ?>
                                    <label class="form-check-label" for="gender_male"><?= lang("create_user_gender_male_label") ?></label>
                                </div>
                                <div class="form-check">
                                    <?= form_radio($gender_female, 2, set_checkbox('gender', 2), "id='gender_female'") ?>
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
                                        <?php echo lang('create_user_region_label', 'region_id');?>
                                        <span class="text-danger">*</span> <br />
                                        <?= form_dropdown($region, $region_options, $selected_region_id); ?>
                                        <div class="invalid-feedback"><?php echo form_error('region_id'); ?></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <?php $city_options = $cities; ?>
                                    <?php echo lang('create_user_city_label', 'city_id');?>
                                    <span class="text-danger">*</span> <br />
                                    <?= form_dropdown($city, $city_options, $selected_city_id); ?>
                                    <div class="invalid-feedback"><?php echo form_error('city_id'); ?></div>
                                </div>
                                <div class="col-sm-4">
                                    <?php echo lang('create_user_address_label', 'address');?><br />
                                    <?php echo form_input($address);?>
                                </div>

                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <?php echo lang('create_user_department_name_label', 'department_id');?>
                                        <?= form_dropdown($departments, $department_options); ?>
                                        <div class="invalid-feedback"><?= form_error('department_id'); ?></div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <?php echo lang('create_user_validation_jobtitle', 'job_title_id');?>
                                        <?= form_dropdown($job_title, $job_titles_options); ?>
                                        <div class="invalid-feedback"><?= form_error('job_title_id'); ?></div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <?php echo lang('create_user_doctor_group_label', 'group_id', array(), true);?>
                                        <?= form_dropdown($groups, $groups_options); ?>
                                        <div class="invalid-feedback"><?= form_error('group_id'); ?></div>
                                    </div>
                                </div>
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
                                <label>Расм</label>
                                <div class="profile-upload">
                                    <div class="upload-img">
                                        <img alt="" src="<?= site_url("assets/admin/img/user.jpg"); ?>">
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
        <div class="form-group">
            <?php echo lang('create_user_description_label', 'description');?>
            <?php echo form_textarea($description);?>
        </div>
        <div class="form-group">
            <label class="display-block">Status</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="doctor_active" value="1" checked>
                <label class="form-check-label" for="doctor_active">
                    Active
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="doctor_inactive" value="0">
                <label class="form-check-label" for="doctor_inactive">
                    Inactive
                </label>
            </div>
        </div>

        <div class="m-t-20 text-center">
            <button class="btn btn-primary submit-btn"><?= lang("doctor_save") ?></button>
            <a role="button" class="btn btn-secondary submit-btn" href="<?= site_url("admin/employees") ?>"><?= lang("user_cancel_button") ?></a>
        </div>
        <?= form_close(); ?>
    </div>
</div>
