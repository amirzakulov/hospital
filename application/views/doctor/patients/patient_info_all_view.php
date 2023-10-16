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
                                <div class="staff-id"><strong>Бемор ID: </strong><?= $patient["username"] ?></div>
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
                                    <span class="text"><?= empty($patient["dob"]) ? "&nbsp;":(date("Y", strtotime($patient["dob"]))); ?></span>
                                </li>
                                <li>
                                    <span class="title">Манзил:</span>
                                    <span class="text"><?= $patient["region_name"].", ".$patient["city_name"].", ".$patient["address"]; ?></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">
<!--                            <ul class="personal-info">-->
<!--                                <li></li>-->
<!--                            </ul>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-2 mb-2">
    <div class="col-sm-2">
        <a href="<?= $refer ?>" class="btn btn-primary"><i class="fa fa-long-arrow-left"></i> <?= lang("general_back") ?></a>
    </div>
    <div class="col-sm-10"></div>
</div>

<div class="profile-tabs">
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="nav-item"><a class="nav-link active" href="#laboratory" data-toggle="tab">Беморлик тарихи</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane show active" id="laboratory">
            <div class="row">
                <div class="col-md-6"><h4>Ташхис</h4></div>
                <div class="col-md-6"><h4>Лаборатория</h4></div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php foreach ($patient_pds as $pds) {?>
                    <div class="card-box">
                        <span class="time font-18"><?= date("d.m.Y", strtotime($pds["created_date"])); ?></span><hr>
                        <strong>Бемор шикояти</strong>
                        <p><?= $pds["patient_complaint"]; ?></p>

                        <strong>Anamnesis morbi</strong>
                        <p><?= $pds["anamnesis_morbi"]; ?></p>

                        <strong>Anamnesis vitae</strong>
                        <p><?= $pds["anamnesis_vitae"]; ?></p>

                        <strong>Status praesens</strong>
                        <p><?= $pds["status_praesens"]; ?></p>

                        <strong>Қўшимча маълумотлар</strong>
                        <p><?= $pds["description"]; ?></p>

                        <strong>Ташхис</strong>
                        <p><?= $pds["diagnosis"]; ?></p>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-6">
                    <?php if(count($laboratories) > 0) {?>
                        <?php foreach ($laboratories as $laboratory) {
                            echo '<div class="card-box" style="min-height: 15rem;">';
                            echo $laboratory["html"];
                            echo '</div>';
                        } ?>
                    <?php } else { ?>
                        <div class="card-box" style="min-height: 15rem;">
                            <span class="fa fa-exclamation-triangle text-warning"></span> Лаборатория топширилмаган...
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pre_print" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Чоп этишдан олдинги кўриниш</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Бекор қилиш</button>
                <button type="button" class="btn btn-primary">Чоп этиш</button>
            </div>
        </div>
    </div>
</div>