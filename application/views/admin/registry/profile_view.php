
<div class="row">
    <div class="col-sm-7 col-6">
        <h4 class="page-title"><?= $title ?></h4>
    </div>

    <div class="col-sm-5 col-6 text-right m-b-30">
        <a href="<?= site_url("admin/registry/edit/".$patient["id"]."/".$page) ?>" class="btn btn-primary btn-rounded"><i class="fa fa-plus"></i> <?= lang("general_edit") ?></a>
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
								<li>
									<span class="title">Қарз:</span>
									<span class="text">
										<?= !$total_payment["debt"] ? '':'<span class="badge badge-danger">'.($total_payment["debt"]).'</span>';?>
									</span>
								</li>
                                <li>
                                    <span class="title">Йўлланма берувчи:</span>
                                    <span class="text">
										<?php foreach ($partners as $partner) { ?>
											<p><?= $partner["last_name"] .' '. $partner["first_name"]; ?></p>
										<?php } ?>
									</span>
                                </li>

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
        <div class="row">
            <div class="col-4 js_patient_hisotry_list" data-url="<?= site_url("admin/registry/ajax_medical_history") ?>">
                <?php foreach ($history as $payment_date => $payment) { ?>
                <div class="card js_patient_history_card" data-payment-date="<?= strtotime($payment_date); ?>" data-patient-id="<?= $patient["id"]; ?>">
                    <div class="card-header bg-dark text-info font-weight-bold">Сана: <span class="text-white"><?= $payment_dates[$payment_date]; ?></span> <span class="font-weight-bold text-info ml-3">Чек №: <span class="text-white"><?= $payment[0]["id"]; ?></span></span> <span class="pt-2 ml-3">Репринт: <span class="fa fa-file-text text-white cursor-pointer js_reprint_cheque" data-url="<?= site_url("admin/registry/reprint_cheque"); ?>" data-payment-id="<?= $payment[0]["id"]; ?>"></span></span></div>

                    <ul class="list-group list-group-flush">
                        <?php foreach ($payment as $pay) {
                            if($pay["doctor_status"] > 0) {
                                echo '<li class="list-group-item">
										<div class="d-inline-block text-left float-left">Шифокор кўриги </div>
											<div class="btn-group text-right float-right w-25" role="group">
												  <button type="button" class="btn btn-outline-secondary w-100 js_patient_history_item" data-item-type="doctor" data-payment-id="'.$pay["id"].'" data-button-type="list"><span class="fa fa-list"></span></button>
											</div>
										</li>';
                            }

                            if($pay["laboratory_status"] > 0) {
                                echo '<li class="list-group-item float-left">
											<div class="text-left float-left w-50">Лаборатория </div>
											<div class="btn-group text-right float-right w-50" role="group" aria-label="Basic example">
												  <button type="button" class="btn btn-outline-secondary w-50 js_patient_history_item" data-item-type="laboratory" data-payment-id="'.$pay["id"].'" data-button-type="print"><span class="fa fa-print"></span></button>
												  <button type="button" class="btn btn-outline-secondary w-50 js_patient_history_item" data-item-type="laboratory" data-payment-id="'.$pay["id"].'" data-button-type="list"><span class="fa fa-list"></span></button>
											</div>
										</li>';
                            }

							if($pay["uzi_status"] > 0) {
								echo '<li class="list-group-item">
										<div class="text-left float-left">УЗИ </div>
										<div class="btn-group text-right float-right w-50" role="group" aria-label="Basic example">
											  <button type="button" class="btn btn-outline-secondary w-50 js_patient_history_item" data-item-type="uzi" data-payment-id="'.$pay["id"].'" data-button-type="print"><span class="fa fa-print"></span></button>
											  <button type="button" class="btn btn-outline-secondary w-50 js_patient_history_item" data-item-type="uzi" data-payment-id="'.$pay["id"].'" data-button-type="list"><span class="fa fa-list"></span></button>
										</div>
									</li>';
							}

							if($pay["service_status"] > 0) {
								echo '<li class="list-group-item">
										<div class="text-left float-left">Қўшимча хизматлар </div>
										<div class="btn-group text-right float-right w-25" role="group">
											  <button type="button" class="btn btn-outline-secondary w-50 js_patient_history_item" data-item-type="service" data-payment-id="'.$pay["id"].'" data-button-type="list"><span class="fa fa-list"></span></button>
										</div>
									</li>';
							}

                            if($pay["room_status"] > 0) {
								echo '<li class="list-group-item">
										<div class="text-left float-left">Ётоқ </div>
										<div class="btn-group text-right float-right w-25" role="group">
											  <button type="button" class="btn btn-outline-secondary w-50 js_patient_history_item" data-item-type="room" data-payment-id="'.$pay["id"].'" data-button-type="list"><span class="fa fa-list"></span></button>
										</div>
									</li>';
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
