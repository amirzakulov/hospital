<div class="card-box profile-header">
    <div class="row">
        <div class="col-md-12">
            <div class="profile-view">
                <div class="profile-basic1">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="profile-info-left">
                                <h3 class="user-name m-t-0 mb-0"><?= $patient["last_name"] ." ". $patient["first_name"] ." ". $patient["surname"]; ?></h3>
                                <div class="staff-id"><strong>Касби: </strong><?= $patient["occupation"] ?></div>
                                <div class="staff-id"><strong>Ходим ID: </strong><?= $patient["username"] ?></div>
                                <div class="staff-id"><strong>Чек рақами: </strong><span class="text-danger border-bottom"><?= $patient["payment_id"] . " (" . date("d.m.Y", strtotime($patient["payment_date"])) . ")" ?></span></div>

<!--                                <div class="staff-msg"><a href="javascript:void(0)" class="btn btn-primary">Хабар юбориш</a></div>-->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <ul class="personal-info">
                                <li>
                                    <span class="title">Телефон:</span>
                                    <span class="text"><?= is_null($patient["phone"]) ? "&nbsp;":phone_number_format($patient["phone"]); ?></span>
                                </li>
                                <li>
                                    <span class="title">Туғилган сана:</span>
                                    <span class="text"><?= is_null($patient["dob"]) ? "&nbsp;":date("Y", strtotime($patient["dob"])); ?></span>
                                </li>
                                <li>
                                    <span class="title">Манзил:</span>
                                    <span class="text"><?= $patient["region_name"].", ".$patient["city_name"].", ".$patient["address"]; ?></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <ul class="personal-info">
                                <li>
                                    <span class="title">Умумий тўлов:</span>
                                    <h4><?= $patient["paid"]; ?> сум</h4>
                                </li>
                                <li>
                                    <span class="title">Қарз:</span>
                                    <span class="text"><strong class="text-danger"><?= $patient["debt"]; ?> сум</strong></span>
                                </li>
                                <li>
                                    <span class="title">Чегирма:</span>
                                    <span class="text">
                                        <?php if($patient["discount_sum"] > 0) {?>
                                            <?= $patient["discount_sum"] . " сум" ?>
                                        <?php } elseif($patient["discount_percent"] > 0) { ?>
                                            <?= $patient["discount_percent"] . " %" ?>
                                        <?php } else {
                                            echo "0 сум";
                                        } ?>
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
<div class="row mt-2 mb-2">
    <div class="col-sm-2">
        <a href="<?= site_url("doctor/patients_uzi") ?>" class="btn btn-primary"><i class="fa fa-long-arrow-left"></i> <?= lang("general_back") ?></a>
    </div>

<!--    <div class="col-sm-10" data-url="--><?//= site_url("/doctor/patients/ajax_patient_doctor_status"); ?><!--" data-payment-id="--><?//= $patient["payment_id"]; ?><!--">-->
<!--        <button type="button" class="btn btn-primary btn-lg float-right js_patient_doctor_status --><?//= ($patient["doctor_status"] == 1) ? "":"d-none"?><!--" id="qabulda"><span class="fa fa-play"></span> Қабулга кирди</button>-->
<!--        <button type="button" class="btn btn-danger btn-lg float-right js_patient_doctor_status --><?//= ($patient["doctor_status"] == 3) ? "":"d-none"?><!--" id="qabul_tamom" data-patient-doctor-id="--><?//= $patient["id"]; ?><!--" data-backtourl="--><?//= site_url("/doctor/patients"); ?><!--"><span class="fa fa-power-off"></span> Тамомлаш</button>-->
<!--    </div>-->

    <div class="col-sm-10" data-url="<?= site_url("/doctor/patients_uzi/ajax_patient_uzi_status"); ?>" data-payment-id="<?= $patient["payment_id"]; ?>">
        <button type="button" class="btn btn-primary btn-lg float-right js_patient_doctor_status <?= ($patient["uzi_status"] == 1) ? "":"d-none"?>" id="qabulda"><span class="fa fa-play"></span> Қабулга кирди</button>
        <button type="button" class="btn btn-danger btn-lg float-right js_patient_doctor_status <?= ($patient["uzi_status"] == 3) ? "":"d-none"?>" id="qabul_tamom" data-patient-doctor-id="<?= $patient["id"]; ?>" data-backtourl="<?= site_url("/doctor/patients_uzi"); ?>"><span class="fa fa-power-off"></span> Қабулни тамомлаш</button>
    </div>
</div>

<div class="profile-tabs">
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="nav-item"><a class="nav-link active" href="#laboratory" data-toggle="tab">УЗИ</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane show active" id="laboratory">
            <div class="row">
                <div class="col-md-9">
                    <?= form_open("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
                    <div class="card-box" style="min-width: 15rem;">
                        <h4 class="card-title float-left">УЗИ</h4>
                        <div class="btn-group float-right icon-btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-primary btn_pre_print js_pre_print_uzi" data-url="<?= $print_preview_url; ?>" data-payment-id="<?= $patient["payment_id"]; ?>"><span class="fa fa-file-text"></span> Кўриш</button>
                        </div>
                        <div class="clearfix"></div>
                        <div class="js_diagnosis_result" style="min-height: 10rem">
                            <div class="row">
                                <div class="col-4">
                                    <div class="list-group" id="list-tab" role="tablist">
                                        <?php foreach ($patient_active_uzis as $key => $pauzi) {?>
                                        <a class="list-group-item list-group-item-action lab_result_finish <?= $key == 0 ? "active":""; ?>" id="list-<?= $pauzi["id"] ?>-list" data-toggle="list" href="#list-content-<?= $pauzi["id"] ?>" role="tab" aria-controls=""><span class="fa fa-circle-o"></span> <?= $pauzi["name"] ?></a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-8">

                                    <div class="form-group">
                                        <select class="form-control" name="uzi_template">
                                            <option>-- Select --</option>
                                            <?php foreach ($uzi_templates as $template_id => $template_title) {?>
                                            <option value="<?= $template_id; ?>"><?= $template_title; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="tab-content pt-0 js_tab_content" id="nav-tabContent" data-url="<?= site_url("doctor/patients_uzi/ajax_uzi_result_save") ?>">
                                        <?php foreach ($result as $key => $ruzi) {?>
                                        <div class="tab-pane fade show <?= $key == 0 ? "active":""; ?>" id="list-content-<?= $ruzi["id"] ?>" role="tabpanel" aria-labelledby="list-<?= $ruzi["id"] ?>-list">
                                            <div class="form-group border-bottom bg-light">
<!--                                                --><?php //echo lang('doctor_patients_uzi_result_label', 'result');?>
                                                <?php echo form_textarea($ruzi);?>
                                                <div class="invalid-feedback"><?php echo form_error('result[]'); ?></div>
                                            </div>
                                            <button class="btn btn-primary pull-right js_save_uzi_result" data-url="<?= site_url("doctor/patients_uzi/ajax_save_uzi_result"); ?>">Сақлаш</button>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div>
                <div class="col-md-3">
                    <div class="card-box" style="min-height: 15rem;">
                        <h4 class="card-title">Тўловлар</h4>
                        <div class="experience-box">
                            <ul class="experience-list">
                                <?php foreach ($payments as $payment) {?>
                                    <li>
                                        <div class="experience-user"><div class="before-circle"></div></div>
                                        <div class="experience-content">
                                            <div class="timeline-content">
                                                <div><?= date("d.m.Y", strtotime($payment["created_date"])) ?></div>
                                                <a href="javascript:void(0);" class="name">Чек рақами: <abbr><?= $payment["id"]; ?></abbr></a>
                                                <div>Умумий тўлов: <?= $payment["total"] ?></div>
                                            </div>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pre_print" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Чоп этишдан олдинги кўриниш</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light"></div>
            <div class="modal-footer">
<!--                <button type="button" class="btn btn-primary">Чоп этиш</button>-->
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Ойнани ёпиш</button>
            </div>
        </div>
    </div>
</div>