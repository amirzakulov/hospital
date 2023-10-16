<div class="row">
    <div class="col-lg-8">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-lg-4 text-right m-b-20">
<!--        <a class="btn btn-primary js_room_bed_add" href="javascript:void(0);" title="--><?//= lang("rooms_bed_add") ?><!--" role="button"><span class="fa fa-plus"></span> Ётоқ қўшиш</a>-->
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <?= form_open("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?= lang("room_bed_name", "name") ?>:<br />
                            <?= form_input($name);?>
                        </div>
                        <div class="form-group">
                            <?= lang("room_bed_price", "price") ?>:<br />
                            <?= form_input($price);?>
                        </div>
                    </div>
                </div>
            </div>
        <div class="m-t-20 text-center">
            <button class="btn btn-primary submit-btn"><?= lang("general_save") ?></button>
            <a href="<?= site_url("admin/rooms/room_beds/".$bed["room_id"]) ?>" class="btn btn-secondary submit-btn"><?= lang("general_cancel") ?></a>
        </div>
        <?= form_close(); ?>
    </div>
</div>
