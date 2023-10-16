<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-sm-8 col-9 text-right m-b-20"></div>
</div>

<div class="row">
    <div class="col-lg-8">
		<div class="form-group row">
			<label class="col-lg-4 col-form-label">Чек чиқаришни ёқиш</label>
			<div class="col-md-8">
				<div class="material-switch">
					<input type="checkbox" id="pos_printer_status" class="js_switch_pos_printer_item" data-url="<?= site_url("admin/settings/posprint/ajax_update_setting_value") ?>" data-settings-item-id="1" <?= $printer_settings["pos_printer_status"] ? "checked":""; ?>>
					<label for="pos_printer_status" class="badge-success"></label>
				</div>
			</div>
		</div>

		<div class="form-group row mt-4">
			<label class="col-lg-4 col-form-label">QR кодни чоп этиш</label>
			<div class="col-md-8">
				<div class="material-switch">
					<input type="checkbox" id="pos_printer_qrcode" class="js_switch_pos_printer_item" data-url="<?= site_url("admin/settings/posprint/ajax_update_setting_value") ?>" data-settings-item-id="16" <?= $printer_settings["pos_printer_qrcode"] ? "checked":""; ?>>
					<label for="pos_printer_qrcode" class="badge-success"></label>
				</div>
			</div>
		</div>
		<div class="form-group row mt-4">
			<label class="col-lg-4 col-form-label">Логони чоп этиш</label>
			<div class="col-md-8">
				<div class="material-switch">
					<input type="checkbox" id="pos_printer_logo_print" class="js_switch_pos_printer_item" data-url="<?= site_url("admin/settings/posprint/ajax_update_setting_value") ?>" data-settings-item-id="18" <?= $printer_settings["pos_printer_logo_print"] ? "checked":""; ?>>
					<label for="pos_printer_logo_print" class="badge-success"></label>
				</div>
			</div>
		</div>

		<div class="form-group row d-none">
			<label class="col-lg-4 col-form-label">Лого</label>
			<div class="col-lg-6">
				<input class="form-control" type="file">
				<span class="form-text text-muted">Rasm PNG formatda bo'lsin. O'lchamlari W:300px H:217px</span>
			</div>
			<div class="col-lg-2">
				<div class="img-thumbnail float-right"><img src="<?= site_url("assets/images/receipt_logo.png"); ?>" alt="" width="40" height="40"></div>
			</div>
		</div>

		<hr>
		<div class="form-group row">
			<label class="col-lg-4 col-form-label">Чек принтерлар</label>
			<div class="col-lg-8">
				<select class="form-control js_select_pos_printer" name="pos_printer_selected_id" data-url="<?= site_url("admin/settings/posprint/ajax_select_printer") ?>">
					<?php foreach ($printers as $printer) {?>
					<option value="<?= $printer["id"] ?>" <?= set_select('selected_pos_printer_id', $printer["id"], $printer_settings["selected_pos_printer_id"] == $printer["id"]); ?>><?= $printer["name"]; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>

	</div>
</div>

