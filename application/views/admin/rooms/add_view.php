<div class="row">
    <div class="col-lg-8">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-lg-4 text-right m-b-20">
        <a class="btn btn-primary js_room_bed_add" href="javascript:void(0);" title="<?= lang("rooms_bed_add") ?>" role="button"><span class="fa fa-plus"></span> Ётоқ қўшиш</a>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <?= form_open("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?= lang("rooms_type", "room_type_id", array(), true) ?>:<br />
                            <?= form_dropdown($room_type_id, $room_type_options);?>
                            <div class="invalid-feedback"><?php echo form_error('room_type_id'); ?></div>
                        </div>
                        <div class="form-group">
                            <?= lang('rooms_number', 'number');?>:
                            <?= form_input($number);?>
                            <div class="invalid-feedback"><?php echo form_error('number'); ?></div>
                        </div>
                        <div class="form-group">
                            <?= lang("room_types_price", "price") ?>:<br />
                            <?= form_input($price);?>
                            <div class="invalid-feedback"><?php echo form_error('price'); ?></div>
                        </div>
                        <div class="form-group">
                            <?= lang("rooms_bed_amount", "bed_amount") ?>:<br />
                            <?= form_input($bed_amount);?>

                        </div>
                    </div>
                </div>
            </div>

<!--            <div class="card-box d-none1">-->
<!--                <div class="row">-->
<!--                    <div class="col-sm-6">-->
<!--                        <div class="form-group">-->
<!--                            --><?//= lang('rooms_number', 'number');?><!--:-->
<!--                            --><?//= form_input($number);?>
<!--                            <div class="invalid-feedback">--><?php //echo form_error('number'); ?><!--</div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-sm-6">-->
<!--                        <div class="form-group">-->
<!--                            --><?//= lang("room_types_price", "price") ?><!--:<br />-->
<!--                            --><?//= form_input($price);?>
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        <div class="m-t-20 text-center">
            <button class="btn btn-primary submit-btn"><?= lang("general_add") ?></button>
            <a href="<?= site_url("admin/rooms") ?>" class="btn btn-secondary submit-btn"><?= lang("general_cancel") ?></a>
        </div>
        <?= form_close(); ?>
    </div>
</div>
