<div class="row">
    <div class="col-lg-12">
        <div class="content">
            <div class="row">
                <div class="col-sm-7 col-6">
                    <h4 class="page-title"><?= $title; ?></h4>
                </div>

                <div class="col-sm-5 col-6 text-right m-b-30">
                    <a href="<?= site_url("doctor/user/edit"); ?>" class="btn btn-primary btn-rounded"><i class="fa fa-pencil"></i> Тахрирлаш</a>
                    <a href="<?= site_url("doctor/user/change_password"); ?>" class="btn btn-primary btn-rounded"><i class="fa fa-pencil"></i> Паролни ўзгартириш</a>
                </div>
            </div>
            <div class="card-box profile-header">
                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-view">
                            <div class="profile-basic m-0">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="profile-info-left">
                                            <h3 class="user-name m-t-0 mb-0"><?= $user["last_name"] ." ". $user["first_name"]; ?></h3>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <ul class="personal-info">
                                            <li>
                                                <span class="title">Phone:</span>
                                                <span class="text"><?= $user["phone"]; ?></span>
                                            </li>
                                            <li>
                                                <span class="title">Email:</span>
                                                <span class="text"><a href="mailto:<?= $user["email"]; ?>"><?= $user["email"]; ?></a></span>
                                            </li>
                                            <li>
                                                <span class="title">Birthday:</span>
                                                <span class="text"><?= date("d.m.Y", strtotime($user["dob"])); ?></span>
                                            </li>
                                            <li>
                                                <span class="title">Address:</span>
                                                <span class="text"><?= $user["region_name"] .", ".$user["city_name"] .", ".$user["address"]; ?></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>