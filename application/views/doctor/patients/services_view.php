<div class="card-box profile-header">
    <div class="row">
        <div class="col-md-12">
            <div class="profile-view">
                <div class="profile-basic1">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="profile-info-left">
                                <h3 class="user-name m-t-0 mb-0"><?= $patient["last_name"] ." ". $patient["first_name"] ." ". $patient["surname"]; ?></h3>
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

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="profile-tabs mt-2 mb-2">
    <?= form_open("", array("class"=>"needs-validation", "novalidate"=>"")); ?>
    <?= $services; ?>
    <?= form_close(); ?>
</div>