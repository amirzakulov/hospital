<div class="row">
    <div class="col-lg-8">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-lg-4 text-right m-b-20">
        <a class="btn btn-primary js_room_bed_add" href="javascript:void(0);" title="<?= lang("rooms_bed_add") ?>" role="button"><span class="fa fa-plus"></span> Ётоқ қўшиш</a>
    </div>
</div>
<?= form_open("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
<div class="row">
        <div class="col-lg-6">
            <div class="card-box" style="min-height: 25rem;">
                <div class="card-title">Хона учун маълумотларни киритинг</div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?= lang("rooms_type", "room_type_id", array(), true) ?>:<br />
                            <?= form_dropdown($room_type_id, $room_type_options, $room["room_type_id"]);?>
                            <div class="invalid-feedback"><?php echo form_error('room_type_id'); ?></div>
                        </div>
                        <div class="form-group">
                            <?= lang('rooms_number', 'number', array(), true);?>:
                            <?= form_input($number);?>
                            <div class="invalid-feedback"><?php echo form_error('number'); ?></div>
                        </div>
                        <div class="form-group">
                            <?= lang("rooms_price", "price", array(), true) ?>:<br />
                            <?= form_input($price);?>
                            <div class="invalid-feedback"><?php echo form_error('price'); ?></div>
                        </div>
                    </div>
                </div>
            </div>

        <div class="m-t-20 text-center">
            <button class="btn btn-primary submit-btn"><?= lang("general_save") ?></button>
            <a href="<?= site_url("admin/rooms") ?>" class="btn btn-secondary submit-btn"><?= lang("general_cancel") ?></a>
        </div>
    </div>
    <div class="col-lg-6"></div>
</div>
<?= form_close(); ?>
