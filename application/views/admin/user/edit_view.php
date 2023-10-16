<div class="row">
    <div class="col-lg-12">
        <div class="content">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title"><?= $title; ?></h4>
                </div>
            </div>
            <?= form_open("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
                <div class="card-box">
                    <h3 class="card-title">Асосий маълумотлар</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-img-wrap d-none">
<!--                                <img class="inline-block" src="assets/img/user.jpg" alt="user">-->
                                <div class="fileupload btn">
                                    <span class="btn-text">edit</span>
                                    <input class="upload" type="file">
                                </div>
                            </div>
                            <div class="profile-basic ml-0">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-focus">
                                            <?php echo lang('create_user_lname_label', 'last_name', array("class"=>"focus-label"));?>
                                            <?php echo form_input($last_name);?>
<!--                                            <div class="invalid-feedback">--><?php //echo form_error('last_name'); ?><!--</div>-->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-focus">
                                            <?php echo lang('create_user_fname_label', 'first_name', array("class"=>"focus-label"));?>
                                            <?php echo form_input($first_name);?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-focus">
                                            <?php echo lang('create_user_surname_label', 'surname', array("class"=>"focus-label"));?>
                                            <?php echo form_input($surname);?>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-focus">
                                            <?php echo lang('create_user_dob_label', 'dob', array("class"=>"focus-label"));?>
                                            <div class="cal-icon">
                                                <?= form_input($dob);?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-focus select-focus">
                                            <label class="focus-label"><?= lang("create_user_gender_label"); ?></label>
                                            <select class="select form-control floating" name="gender">
                                                <option value="1" <?=  set_select('gender', 1, ($user["gender"] == 1 ? TRUE : FALSE)); ?>>Эркак</option>
                                                <option value="2"<?=  set_select('gender', 2, ($user["gender"] == 2 ? TRUE : FALSE)); ?>>Аёл</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-focus">
                                            <?php echo lang('create_user_phone_label', 'phone', array("class"=>"focus-label"));?>
                                            <?php echo form_input($phone);?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-box d-none">
                    <h3 class="card-title">Алоқа учун маълумотлар</h3>
<!--                    <div class="row">-->
<!--                        <div class="col-md-6">-->
<!--                            <div class="form-group form-focus">-->
<!--                                <label class="focus-label">--><?//= lang("create_user_address_label"); ?><!--</label>-->
<!--                                <input type="text" class="form-control floating" value="4487 Snowbird Lane">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="col-md-6">-->
<!--                            <div class="form-group form-focus">-->
<!--                                <label class="focus-label">--><?//= lang("create_user_region_label"); ?><!--</label>-->
<!--                                <input type="text" class="form-control floating" value="New York">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="col-md-6">-->
<!--                            <div class="form-group form-focus">-->
<!--                                <label class="focus-label">--><?//= lang("create_user_city_label"); ?><!--</label>-->
<!--                                <input type="text" class="form-control floating" value="United States">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="col-md-6">-->
<!--                            <div class="form-group form-focus">-->
<!--                                <label class="focus-label">--><?//= lang("create_user_phone_label"); ?><!--</label>-->
<!--                                <input type="text" class="form-control floating" value="631-889-3206">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>

                <div class="m-t-20 text-center">
                    <button class="btn btn-primary submit-btn"><?= lang("general_save") ?></button>
                    <a role="button" class="btn btn-secondary submit-btn" href="<?= site_url("admin/user/profile") ?>"><?= lang("general_cancel") ?></a>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>