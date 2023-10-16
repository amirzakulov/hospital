
<div class="row d-none">
    <div class="col-sm-7 col-6">
        <h4 class="page-title"><?= $title ?></h4>
    </div>

    <div class="col-sm-5 col-6 text-right m-b-30">
<!--        <a href="" class="btn btn-primary btn-rounded"><i class="fa fa-plus"></i> --><?//= lang("general_edit") ?><!--</a>-->
    </div>
</div>
<?= $breadcrumbs; ?>
<div class="card-box profile-header">
    <div class="row">
        <div class="col-md-12">
            <div class="profile-view">
                <div class="profile-img-wrap">
                    <div class="profile-img">
                        <a href="#"><img class="avatar" src="<?= site_url("assets/images/user.jpg"); ?>" alt=""></a>
<!--                        <a href="#"><img class="avatar" src="assets/img/doctor-03.jpg" alt=""></a>-->
                    </div>
                </div>
                <div class="profile-basic">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="profile-info-left">
                                <h3 class="user-name m-t-0 mb-0"><?= $patient["last_name"] ." ". $patient["first_name"]; ?></h3>
                                <small class="text-muted"><?= $patient["occupation"]; ?></small>
                                <div class="staff-id">Ходим ID : <?= $patient["username"] ?></div>
                                <div class="staff-id">&nbsp;</div>
<!--                                <div class="staff-msg"><a href="javascript:void(0)" class="btn btn-primary">Хабар юбориш</a></div>-->
                            </div>
                        </div>
                        <div class="col-md-7">
                            <ul class="personal-info">
                                <li>
                                    <span class="title">Телефон:</span>
                                    <span class="text"><?= is_null($patient["phone"]) ? "":phone_number_format($patient["phone"]); ?></span>
                                </li>
                                <li>
                                    <span class="title">Email:</span>
                                    <span class="text"><a href="mailto:<?= $patient["email"]; ?>"><?= $patient["email"]; ?></a></span>
                                </li>
                                <li>
                                    <span class="title">Birthday:</span>
                                    <span class="text"><?= is_null($patient["dob"]) ? "": date("Y", strtotime($patient["dob"])); ?></span>
                                </li>
                                <li>
                                    <span class="title">Address:</span>
                                    <span class="text"><?= $patient["region_name"].", ".$patient["city_name"].", ".$patient["address"]; ?></span>
                                </li>
<!--                                <li>-->
<!--                                    <span class="title">Gender:</span>-->
<!--                                    <span class="text">--><?//= $patient["gender"] != NULL ? $gender[$patient["gender"]] : ""; ?><!--</span>-->
<!--                                </li>-->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="patient_hisotry_box js_patient_hisotry_box mt-1">
    <div class="card-box">
        <h3 class=" mb-3">Бемор тарихи</h3>
        <div class="row">
            <div class="col-4 js_patient_hisotry_list" data-url="<?= site_url("admin/registry/ajax_medical_history") ?>">
                <?php foreach ($history as $payment_date => $payment) {?>
                <div class="card js_patient_history_card" data-payment-date="<?= strtotime($payment_date); ?>" data-patient-id="<?= $patient["id"]; ?>">
                    <div class="card-header bg-dark text-white"><?= date("d.m.Y", strtotime($payment_date)) ?></div>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($payment as $pay) {
                            if($pay["doctor_status"] > 0) {
                                echo '<li class="list-group-item js_patient_history_item" data-item-type="doctor" data-payment-id="'.$pay["id"].'">Шифокор кўриги</li>';
                            }

                            if($pay["laboratory_status"] > 0) {
                                echo '<li class="list-group-item js_patient_history_item" data-item-type="laboratory" data-payment-id="'.$pay["id"].'">Лаборатория</li>';
                            }

                            if($pay["uzi_status"] > 0) {
                                echo '<li class="list-group-item js_patient_history_item" data-item-type="uzi" data-payment-id="'.$pay["id"].'">УЗИ</li>';
                            }
                        } ?>
                    </ul>
                </div>
                <?php } ?>
            </div>
            <div class="col-8 js_patient_hisotry_view">
                <div class="js_patient_hisotry_content patient_hisotry_content printArea"></div>
            </div>
        </div>
    </div>
</div>
