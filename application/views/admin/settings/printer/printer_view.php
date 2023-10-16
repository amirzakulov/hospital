<?php $this->load->view('admin/settings/printer/menu_view'); ?>
<h4>Бош қисми</h4>

<div class="row">
	<div class="col-lg-8">
		<?= form_open("admin/settings/lab_print/ajax_save_data"); ?>
		<div class="form-group row d-none">
			<label class="col-lg-4 col-form-label">Лого</label>
			<div class="col-lg-6">
				<input class="form-control" type="file">
				<span class="form-text text-muted">Rasm PNG formatda bo'lsin. O'lchamlari W:550px H:170px</span>
			</div>
			<div class="col-lg-2">
				<div class="img-thumbnail float-right"><img src="<?= site_url("assets/admin/img/logo-dark.png"); ?>" alt="" width="40" height="40"></div>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-lg-4 col-form-label">Клиника номи</label>
			<div class="col-lg-8">
				<input type="text" name="name" class="form-control" value="<?= $print_details["name"]; ?>">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-lg-4 col-form-label">Манзил</label>
			<div class="col-lg-8">
				<input type="text" name="address" class="form-control" value="<?= $print_details["address"]; ?>">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-lg-4 col-form-label">Мўлжал</label>
			<div class="col-lg-8">
				<input type="text" name="orientation" class="form-control" value="<?= $print_details["orientation"]; ?>">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-lg-4 col-form-label">Телефон рақамлар</label>
			<div class="col-lg-8">
				<input type="text" name="phone" class="form-control" value="<?= $print_details["phone"]; ?>">
			</div>
		</div>

		<div class="form-group row">
			<label class="col-lg-4 col-form-label">Веб сайт</label>
			<div class="col-lg-8">
				<input type="text" name="web_address" class="form-control" value="<?= $print_details["web_address"]; ?>">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-lg-4 col-form-label">Email</label>
			<div class="col-lg-8">
				<input type="text" name="email" class="form-control" value="<?= $print_details["email"]; ?>">
			</div>
		</div>

		<div class="form-group row">
			<label class="col-lg-4 col-form-label">Телеграм</label>
			<div class="col-lg-8">
				<input type="text" name="telegram" class="form-control" value="<?= $print_details["telegram"]; ?>">
			</div>
		</div>


		<hr>
		<div class="form-group row">
			<label class="col-lg-4 col-form-label">Лаборатория номи жойлашуви</label>
			<div class="col-lg-8">
				<select class="form-control" name="lab_title_alignment">
					<option value="left" <?= set_select('lab_title_font_size', $print_details["lab_title_alignment"], ($print_details["lab_title_alignment"] == 'left' ? TRUE:FALSE)); ?>>Чапда</option>
					<option value="center" <?= set_select('lab_title_font_size', $print_details["lab_title_alignment"], ($print_details["lab_title_alignment"] == 'center' ? TRUE:FALSE)); ?>>Ўртада</option>
					<option value="right" <?= set_select('lab_title_font_size', $print_details["lab_title_alignment"], ($print_details["lab_title_alignment"] == 'right' ? TRUE:FALSE)); ?>>Ўнгда</option>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-lg-4 col-form-label">Лаборатория номи шрифт катталиги</label>
			<div class="col-lg-8">
				<input type="text" name="lab_title_font_size" class="form-control" value="<?= $print_details["lab_title_font_size"]; ?>">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-lg-4 col-form-label">Лаборатория текст шрифт катталиги</label>
			<div class="col-lg-8">
				<input type="text" name="lab_text_font_size" class="form-control" value="<?= $print_details["lab_text_font_size"]; ?>">
			</div>
		</div>

		<hr>
		<div class="form-group row">
			<label class="col-lg-4 col-form-label">Врач лаборант</label>
			<div class="col-lg-8">
				<select class="form-control" name="laborant_id">
					<option value="">-- Танлаш --</option>
					<?php foreach ($laboratorists as $laborant) {?>
					<option value="<?= $laborant["id"] ?>" <?= set_select('laborant_id', $laborant["id"], ($laborant["id"] == $print_details["laborant_id"] ? TRUE:FALSE)); ?>><?= $laborant["last_name"] .' '.$laborant["first_name"]; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-lg-4 col-form-label">Вароқ остидаги текст <br>(хар бир вароқда)</label>
			<div class="col-lg-8">
				<input type="text" name="footer_text" class="form-control" value="<?= $print_details["footer_text"]; ?>">
			</div>
		</div>


		<div class="m-t-20 text-center">
			<button class="btn btn-primary submit-btn js_save_lab_print_details" tabindex="5"><?= lang("general_save") ?></button>
		</div>
		<?php form_close(); ?>
	</div>
</div>

