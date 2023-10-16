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
                            </div>
                        </div>
                        <div class="col-md-4">
                            <ul class="personal-info">
                                <li>
                                    <span class="title">Телефон:</span>
                                    <span class="text"><?= empty($patient["phone"]) ? "&nbsp;":phone_number_format($patient["phone"]); ?></span>
                                </li>
                                <li>
                                    <span class="title">Туғилган сана:</span>
                                    <span class="text"><?= empty($patient["dob"]) ? "&nbsp;":date("Y", strtotime($patient["dob"])); ?></span>
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
                                    <?php $debt = $patient["total"] - ($patient["paid"] + $patient["discount"]); ?>
                                    <span class="title">Қарз:</span>
                                    <span class="text"><strong class="text-danger"><?= ($debt > 0) ? $debt:0; ?> сум</strong></span>
                                </li>
                                <li>
                                    <span class="title">Чегирма:</span>
                                    <span class="text">
                                        <?php if($patient["discount_type"] == 2) {?>
											<?= $patient["discount_value"] . " %" ?>
                                        <?php } else { ?>
                                            <?= $patient["discount_value"] . " сум" ?>
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
<div class="row mt-2 mb-2">
    <div class="col-sm-2">
        <a href="<?= site_url("doctor/patients") ?>" class="btn btn-primary"><i class="fa fa-long-arrow-left"></i> <?= lang("general_back") ?></a>
    </div>
    <div class="col-sm-10" data-url="<?= site_url("/doctor/patients/ajax_patient_doctor_status"); ?>" data-payment-id="<?= $patient["payment_id"]; ?>">
        <button type="button" class="btn btn-primary btn-lg float-right js_patient_doctor_status <?= ($patient["doctor_status"] == 1) ? "":"d-none"?>" id="qabulda"><span class="fa fa-play"></span> Қабулга кирди</button>
        <button type="button" class="btn btn-danger btn-lg float-right js_patient_doctor_status <?= ($patient["doctor_status"] == 3) ? "":"d-none"?>" id="qabul_tamom" data-patient-doctor-id="<?= $patient["id"]; ?>" data-backtourl="<?= site_url("/doctor/patients"); ?>" data-service-type="service"><span class="fa fa-power-off"></span> Тамомлаш</button>
    </div>
</div>
<div class="profile-tabs">
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="nav-item"><a class="nav-link" href="#diagnos" data-toggle="tab">Ташхис</a></li>
        <li class="nav-item"><a class="nav-link active" href="#illness-history" data-toggle="tab">Касаллик тарихи</a></li>
        <li class="nav-item"><a class="nav-link" href="#test-results" data-toggle="tab">Тахлил натижалари</a></li>
        <li class="nav-item d-none"><a class="nav-link" href="#services" data-toggle="tab">Хизматлар</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="diagnos">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-box" style="min-width: 15rem;">
                        <h4 class="card-title float-left">Ташхис</h4>
                        <div class="btn-group float-right icon-btn-group" role="group" aria-label="">
<!--                            <button type="button" class="btn" onClick=""><span class="text-secondary fa fa-print fa-14em"></span></button>-->
                        </div>
                        <div class="clearfix"></div>
                        <div class="js_diagnosis_result" style="min-height: 10rem">
                            <?php if(!$patient_pds_check) { ?>
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <p class="card-text">Ташхис қўйилмаган...</p>
                                </div>
                            </div>
                            <?php } else { ?>
                            <?php foreach ($patient_pds as $ppd) { ?>
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <div class="card-title"><i class="fa fa-calendar"></i> <?= date("d.m.Y", strtotime($ppd["created_date"])); ?></div>
                                    <p class="card-text"><?= $ppd["diagnosis"] ?></p>
                                </div>
                            </div>
                            <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
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
        <div class="tab-pane show active" id="illness-history">

            <!--//////////////////////////////////////////-->
            <div class="row">
                <div class="col-md-<?= (count($patient_pds) < 2) ? "8":"4"; ?>">
                    <div class="card-box">
                        <?= form_open("doctor/patients/ajax_patient_diagnos_save", array("class" => "needs-validation js_patient_diagnos_save ".$was_validated, "novalidate"=>""), array("patient_doctor_id" => $patient["id"], "patient_id" => $patient["patient_id"])) ?>
                        <h4 class="card-title float-left js_patient_history_btns <?= !empty($patient["patient_complaint"]) ? "":"d-none" ?>"></h4>
                        <div class="btn-group float-right icon-btn-group js_patient_history_btns <?= !empty($patient["patient_complaint"]) ? "":"d-none" ?>" role="group" aria-label="Basic example">
                            <button type="button" class="btn js_edit_patient_history"><span class="text-primary fa fa-edit fa-14em"></span></button>
                            <button type="button" class="btn js_cancel_patient_history d-none"><span class="text-danger fa fa-minus-square-o fa-14em"></span></button>
                            <!--                                <button id="printDiv" type="button" class="btn" onClick=""><span class="text-secondary fa fa-print fa-14em"></span></button>-->
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group js_diagnosis_input <?= empty($patient["patient_complaint"]) ? "":"d-none"; ?>">
                            <?= lang('patients_patient_complaint', 'patient_complaint', array(), true);?>
                            <?= form_textarea($patient_complaint);?>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="pb-2 mb-3 js_diagnosis_text <?= !empty($patient["patient_complaint"]) ? "":"d-none"; ?>">
                            <h5><?= lang("patients_patient_complaint"); ?></h5>
                            <div class="bg-light p-2 pt-4 pb-4 js_patient_complaint_text"><?= $patient["patient_complaint"]; ?></div>
                        </div>

                        <div class="form-group js_diagnosis_input <?= empty($patient["patient_complaint"]) ? "":"d-none"; ?>">
                            <?= lang('patients_anamnesis_morbi', 'anamnesis_morbi', array());?>
                            <?= form_textarea($anamnesis_morbi);?>
                        </div>
                        <div class="pb-2 mb-3 js_diagnosis_text <?= !empty($patient["patient_complaint"]) ? "":"d-none"; ?>">
                            <h5><?= lang("patients_anamnesis_morbi"); ?></h5>
                            <div class="bg-light p-2 pt-4 pb-4 js_anamnesis_morbi_text"><?= $patient["anamnesis_morbi"]; ?></div>
                        </div>

                        <div class="form-group js_diagnosis_input <?= empty($patient["patient_complaint"]) ? "":"d-none"; ?>">
                            <?= lang('patients_anamnesis_vitae', 'anamnesis_vitae', array()); ?>
                            <?= form_textarea($anamnesis_vitae); ?>
                        </div>
                        <div class="pb-2 mb-3 js_diagnosis_text <?= !empty($patient["patient_complaint"]) ? "":"d-none"; ?>">
                            <h5><?= lang("patients_anamnesis_vitae"); ?></h5>
                            <div class="bg-light p-2 pt-4 pb-4 js_anamnesis_vitae_text"><?= $patient["anamnesis_vitae"]; ?></div>
                        </div>

                        <div class="form-group js_diagnosis_input <?= empty($patient["patient_complaint"]) ? "":"d-none"; ?>">
                            <?= lang('patients_status_praesens', 'status_praesens', array()); ?>
                            <?= form_textarea($status_praesens);?>
                        </div>
                        <div class="pb-2 mb-3 js_diagnosis_text <?= !empty($patient["patient_complaint"]) ? "":"d-none"; ?>">
                            <h5><?= lang("patients_status_praesens"); ?></h5>
                            <div class="bg-light p-2 pt-4 pb-4 js_status_praesens_text"><?= $patient["status_praesens"]; ?></div>
                        </div>

                        <div class="form-group js_diagnosis_input <?= empty($patient["patient_complaint"]) ? "":"d-none"; ?>">
                            <?= lang('patients_description', 'description', array());?>
                            <?= form_textarea($description);?>
                        </div>
                        <div class="pb-2 mb-3 js_diagnosis_text <?= !empty($patient["patient_complaint"]) ? "":"d-none"; ?>">
                            <h5><?= lang("patients_description"); ?></h5>
                            <div class="bg-light p-2 pt-4 pb-4 js_description_text"><?= $patient["description"]; ?></div>
                        </div>

                        <div class="form-group js_diagnosis_input <?= empty($patient["patient_complaint"]) ? "":"d-none" ?>">
                            <?= lang('patients_diagnosis', 'diagnosis', array());?>
                            <?= form_textarea($diagnosis);?>
                        </div>
                        <div class="pb-2 mb-3 js_diagnosis_text <?= !empty($patient["patient_complaint"]) ? "":"d-none" ?>">
                            <h5><?= lang("patients_diagnosis"); ?></h5>
                            <div class="bg-light p-2 pt-4 pb-4 js_diagnosiz_text"><?= $patient["diagnosis"] ?></div>
                        </div>

                        <div class="alert alert-warning alert-dismissible fade show d-none" role="alert">
                            Бемор ташхиси сақланди
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="alert alert-warning alert-dismissible fade show d-none js_patient_complaint_validation_message" role="alert">
                            <span class="js_warning_text"></span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <button type="submit" class="btn btn-primary submit-btn btn-block js_patient_diagnos_save_btn <?= empty($patient["patient_complaint"]) ? "":"d-none" ?>"><?= lang("general_save") ?></button>
                        <?= form_close(); ?>
                    </div>
                </div>
                <div class="col-md-4 <?= (count($patient_pds) < 2) ? "d-none":""; ?>">
                    <?php foreach ($patient_pds as $patient_pd) {?>
                        <?php if($patient_pd["id"] != $patient_doctor_id) { ?>
                            <div class="card-box">
                                <span class="time font-18"><?= date("d.m.Y", strtotime($patient_pd["created_date"])); ?></span><hr>
                                <strong>Бемор шикояти</strong>
                                <p><?= $patient_pd["patient_complaint"]; ?></p>

                                <strong>Anamnesis morbi</strong>
                                <p><?= $patient_pd["anamnesis_morbi"]; ?></p>

                                <strong>Anamnesis vitae</strong>
                                <p><?= $patient_pd["anamnesis_vitae"]; ?></p>

                                <strong>Status praesens</strong>
                                <p><?= $patient_pd["status_praesens"]; ?></p>

                                <strong>Қўшимча маълумотлар</strong>
                                <p><?= $patient_pd["description"]; ?></p>

                                <strong>Ташхис</strong>
                                <p><?= $patient_pd["diagnosis"]; ?></p>
                            </div>
                        <?php }?>
                    <?php } ?>
                </div>
                <div class="col-md-4">
                    <div class=" text-right">
                        <button class="btn btn-primary js_show_service_items mb-3" data-toggle="modal" data-target="#patient_selected_items" data-patient-id="<?= $patient["patient_id"]; ?>" data-payment-id="<?= $patient["payment_id"]; ?>">
                            <span class="fa fa-reply"></span> Йўлланма бериш
                        </button>
                        <button class="btn btn-primary js_show_payment_items mb-3 d-none" data-id="<?= $patient["patient_id"]; ?>" data-payment-id="<?= $patient["payment_id"]; ?>" data-url="<?= site_url("doctor/services/ajax_selected_items"); ?>">
                            <span class="fa fa-reply"></span> Тахрирлаш
                        </button>
                    </div>
                    <div class="card-box ">



                    </div>
                </div>
            </div>
            <!--//////////////////////////////////////////-->

        </div>
        <div class="tab-pane" id="test-results">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="card-title">Лаборатория натижалари</h3>
                    <?php if(count($laboratories) > 0) {?>
                        <div class="accordion" id="accordionExample">
                        <?php foreach ($laboratories as $date => $laboratory) {?>
                                <div class="card">
                                    <div class="card-header bg-info pt-1 pb-1" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left text-white" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                <?= date("d.m.Y", strtotime($date)) ?>
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <?= $laboratory["html"] ?>
                                        </div>
                                    </div>
                                </div>


                    <?php
//                            echo '<div class="card-box" style="min-height: 15rem;">';
//                            echo $laboratory["html"];
//                            echo '</div>';
                        } ?>
                    </div>
                    <?php } else { ?>
                        <div class="card-box" style="min-height: 15rem;">
                            <span class="fa fa-exclamation-triangle text-warning"></span> Лаборатория топширилмаган...
                        </div>
                    <?php } ?>
                </div>
                <div class="col-md-6">
                    <h3 class="card-title">УЗИ натижалари</h3>
                    <?php if(count($uzis) > 0) {?>
                        <?php foreach ($uzis as $uzi) {?>
                            <div class="card-box" style="min-height: 15rem;">
                                <span class="time font-18"><?= date("m.d.Y", strtotime($uzi["date"])); ?></span><hr>
                                <ul>
                                <?php foreach ($uzi["data"] as $payment_id => $u) {?>
                                    <li style="list-style-type: decimal;"><?= $u["name"]; ?></li>
                                <?php } ?>
                                </ul>
                                <div><?= $uzi["uzi_result"]; ?></div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="card-box" style="min-height: 15rem;">
                            <span class="fa fa-exclamation-triangle text-warning"></span> УЗИ топширилмаган...
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>
        <div class="tab-pane d-none" id="services">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box" style="min-width: 15rem;">
                        <?php //$services_template; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- start Services Modal -->
<div id="patient_selected_items" class="modal fade" data-backdrop="static" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered1 modal-xl modal-dialog-scrollable" role="document">
        <?= form_open("", array("class"=>"needs-validation w-100", "novalidate"=>""), array("payment_id" => "", "patient_id" => "")); ?>
        <div class="modal-content">
            <div class="modal-header"><h4>Хизматлар</h4></div>
            <div class="modal-body">
                <?= $services_template; ?>
            </div>
            <div class="modal-footer text-center">
                <button class="btn btn-primary submit-btn js_add_services_for_payment" data-url="<?= site_url("doctor/services/ajax_add_selected_items") ?>">Сақлаш ва Чек чиқариш</button>
                <button class="btn btn-secondary submit-btn" data-dismiss="modal">Ойнани ёпиш</button>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>
<!-- end Services Modal -->