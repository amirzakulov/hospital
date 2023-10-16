<div class="row">
    <div class="col-lg-8">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
</div>

<div class="card-box profile-header mb-5">
    <div class="row">
        <div class="col-md-12">
            <div class="profile-view">
                <div class="profile-basic1">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="profile-info-left">
                                <h3 class="user-name m-t-0 mb-0"><?= $patient["last_name"] ." ". $patient["first_name"] ." ". $patient["surname"]; ?></h3>
                                <div class="staff-id"><strong>Касби: </strong><?= $patient["occupation"] ?></div>
                                <div class="staff-id"><strong>Бемор рақами: </strong><?= $patient["username"] ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <ul class="personal-info">
                                <li>
                                    <span class="title">Телефон:</span>
                                    <span class="text"><?= is_null($patient["phone"]) ? "":phone_number_format($patient["phone"]); ?></span>
                                </li>
                                <li>
                                    <span class="title">Туғилган сана:</span>
                                    <span class="text"><?= is_null($patient["dob"]) ? "":date("Y", strtotime($patient["dob"])); ?></span>
                                </li>
                                <li>
                                    <span class="title">Манзил:</span>
                                    <span class="text"><?= $patient["region_name"].", ".$patient["city_name"].", ".$patient["address"]; ?></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <div class="personal-info" style="min-height: 5.1rem;">
                                <strong>Қисқача маълумот: </strong>
                                <div class="staff-id">
                                    <?= $patient["description"]; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="staff-id"><strong>Тўловлари: </strong>
                            <?php foreach ($payments as $payment) {?>
                                <span class="badge badge-light"><?= date("d.m.Y", strtotime($payment["created_date"])) ?>; </span>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $services_template; ?>
