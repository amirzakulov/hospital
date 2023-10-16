<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-sm-8 col-9 text-right m-b-20">
        <a href="<?= site_url("admin/doctors/add") ?>" class="btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> <?= lang("general_add"); ?></a>
    </div>
</div>
<div class="row doctor-grid">
    <?php foreach ($doctors as $id => $doctor) {?>
    <div class="col-md-4 col-sm-4 col-lg-3"  id="js_row_<?= $id ?>">
        <div class="profile-widget <?= !$doctor["active"] ? "bg-light":""; ?>">
            <div class="doctor-img">
                <a class="avatar" href="<?= site_url("admin/doctors/profile/").$doctor["id"] ?>"><img alt="" src="<?= empty($doctor["photo"]) ? site_url("assets/admin/img/user.jpg"): site_url(EMPLOYEE_PHOTO_PATH.$doctor["photo"]) ?>"></a>
            </div>
            <?= !$doctor["active"] ? '<div class="dropdown profile-action profile-action--left"><span class="badge-danger p-1">Нофаол</span></div>':'';?>
            <div class="dropdown profile-action">
                <a href="javascript:void(0);" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="<?= site_url("admin/doctors/edit/").$doctor["id"] ?>"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit"); ?></a>
                    <a href="javascript: void(0);" class="dropdown-item js_delele_item" data-href="<?= site_url("admin/doctors/delete/")?>" data-id="<?= $doctor["id"]; ?>"><i class="fa fa-trash-o m-r-5"></i> <?= lang("general_delete"); ?></a>
                </div>
            </div>
            <h4 class="doctor-name text-ellipsis"><a href="<?= site_url("admin/doctors/profile/").$doctor["id"] ?>"><?= $doctor["last_name"] ." ". $doctor["first_name"]; ?></a></h4>
            <div class="doc-prof">
                <?php foreach ($doctors_types[$id] as $doctor_type) {?>
                    <?= $doctor_type; ?>
                <?php } ?>
            </div>
            <div class="user-country">
                <i class="fa fa-map-marker"></i> <?= $doctor["region_name"] .", ". $doctor["city_name"]; ?>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<!--<div class="row">-->
<!--    <div class="col-sm-12">-->
<!--        <div class="see-all">-->
<!--            <a class="see-all-btn" href="javascript:void(0);">Load More</a>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->