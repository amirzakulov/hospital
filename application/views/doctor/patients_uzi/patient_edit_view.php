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
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?php echo lang('create_user_lname_label', 'last_name');?>
                                <span class="text-danger">*</span> <br />
                                <?php echo form_input($last_name);?>
                                <div class="invalid-feedback"><?php echo form_error('last_name'); ?></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?php echo lang('create_user_fname_label', 'first_name');?>
                                <span class="text-danger">*</span> <br />
                                <?php echo form_input($first_name);?>
                                <div class="invalid-feedback"><?php echo form_error('first_name'); ?></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
							<div class="row">
								<div class="col-sm-3">
									<div class="form-group">
										<?php echo lang('create_user_dob_day_label', 'dob_day');?>
										<?= form_dropdown($dob_day, $days, date("d", strtotime($patient["dob"]))); ?>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<?php echo lang('create_user_dob_month_label', 'dob_month');?>
										<?= form_dropdown($dob_month, $months, date("m", strtotime($patient["dob"]))); ?>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<?php echo lang('create_user_dob_year_label', 'dob_year');?>
										<?= form_dropdown($dob_year, $years, date("Y", strtotime($patient["dob"]))); ?>
									</div>
								</div>
							</div>

                        </div>

<!--                        <div class="col-sm-12">-->
<!--                            <div class="row">-->
<!--                                <div class="col-sm-4 col-md-4 col-lg-4">-->
<!--                                    <div class="form-group">-->
<!--                                        --><?php //$region_options = $regions ?>
<!--                                        --><?php //echo lang('create_user_region_label', 'region_id', array(), true);?>
<!--                                        --><?//= form_dropdown($region, $region_options, $patient["region_id"]); ?>
<!--                                        <div class="invalid-feedback">--><?php //echo form_error('region_id'); ?><!--</div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="col-sm-4">-->
<!--									--><?php //$city_options = $cities; ?>
<!--                                    --><?php //echo lang('create_user_city_label', 'city_id', array(), true);?>
<!--                                    --><?//= form_dropdown($city, $city_options, $patient["city_id"]); ?>
<!--                                    <div class="invalid-feedback">--><?php //echo form_error('city_id'); ?><!--</div>-->
<!--                                </div>-->
<!--                                <div class="col-sm-4">-->
<!--                                    <div class="form-group">-->
<!--                                        --><?php //echo lang('create_user_phone_label', 'phone');?>
<!--                                        --><?php //echo form_input($phone);?>
<!--                                        <div class="invalid-feedback">--><?php //echo form_error('phone'); ?><!--</div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="m-t-20 text-center">
            <button class="btn btn-primary submit-btn"><?= lang("edit_user_submit_btn") ?></button>
            <a role="button" class="btn btn-secondary submit-btn" href="<?= site_url("doctor/patients_uzi"); ?>"><?= lang("user_cancel_button") ?></a>
        </div>
        <?= form_close(); ?>
    </div>
</div>
