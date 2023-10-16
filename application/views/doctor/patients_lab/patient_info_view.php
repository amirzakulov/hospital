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
                                <div class="staff-id"><strong>Чек рақами: </strong><span class="text-danger border-bottom"><?= $patient["payment_id"] . " (" . date("d.m.Y", strtotime($patient["payment_date"])) . ")" ?></span></div>

<!--                                <div class="staff-msg"><a href="javascript:void(0)" class="btn btn-primary">Хабар юбориш</a></div>-->
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
                            <ul class="personal-info d-none">
                                <li>
                                    <span class="title">Умумий тўлов:</span>
                                    <h4><?= $patient["paid"]; ?> сум</h4>
                                </li>
                                <li>
                                    <span class="title">Қарз:</span>
                                    <span class="text"><strong class="text-danger"><?= $patient["total"] - ($patient["paid"] + $patient["discount"]); ?> сум</strong></span>
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
<!--        <a href="--><?//= site_url("doctor/patients_lab") ?><!--" class="btn btn-primary"><i class="fa fa-long-arrow-left"></i> --><?//= lang("general_back") ?><!--</a>-->
        <a href="<?= $refer ?>" class="btn btn-primary"><i class="fa fa-long-arrow-left"></i> <?= lang("general_back") ?></a>
    </div>
    <div class="col-sm-10"></div>
</div>

<div class="profile-tabs">
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="nav-item"><a class="nav-link active" href="#laboratory" data-toggle="tab">Лаборатория</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane show active" id="laboratory">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-box" style="min-height: 15rem;">
                        <?= $laboratory ?>
                    </div>
                </div>
                <div class="col-md-4"></div>
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
