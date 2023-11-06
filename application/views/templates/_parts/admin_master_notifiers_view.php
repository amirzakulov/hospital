<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Огохлантирувчи хабарлар -->
<!-- Ўчираолмайди -->
<div id="nodelete_notifier" class="modal fade" data-backdrop="static" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body"></div>
            <div class="modal-footer text-center">
                <button class="btn btn-primary submit-btn" data-dismiss="modal">Бекор қилиш</button>
            </div>

        </div>
    </div>
</div>

<!-- Тасдиқлаш -->
<div id="confirm_delete_notifier" class="modal fade" data-backdrop="static" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body"></div>
            <div class="modal-footer text-center">
                <div class="js_buttons_confirm">
                    <button class="btn btn-primary submit-btn js_yes">Ха</button>
                    <button class="btn btn-secondary submit-btn" data-dismiss="modal">Йўқ</button>
                </div>
                <div class="js_button_close d-none">
                    <button class="btn btn-secondary submit-btn" data-dismiss="modal">Ойнани беркитиш</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(isset($page) && $page != "debitor") {?>
	<!-- chiqimlar -->
	<div class="modal checkout_modal_wrapper" id="expenses" tabindex="-1" data-backdrop="static">
		<div class="modal-dialog modal-xl modal-dialog-scrollable1">
			<div class="modal-content">
				<div class="modal-header bg-light pb-0">
					<h4 class="text-primary">Чиқимлар</h4>
					<table width="100%" border="0" cellspacing="0" cellpadding="0px" class="text-center mb-3">
						<tr>
							<th width="33%">Кирим (сум)</th>
							<th width="33%">Чиқим (сум)</th>
							<th width="33%">Касса (сум)</th>
						</tr>
						<tr>
							<td class="js_total_income   reg_cash_format"></td>
							<td class="js_total_expense  reg_cash_format"></td>
							<td class="js_total_cash     reg_cash_format reg_cash_format--red"></td>
						</tr>
					</table>
				</div>
				<div class="modal-body">
					<table class="table table-border table-striped custom-table mb-0 compact expenses_table" id="js_datatable_cash">
						<thead>
						<tr>
							<th>Ким киритди</th>
							<th>Сумма</th>
							<th>Тўлов тури</th>
							<th>Чиқим тури</th>
							<th>Сабаб</th>
							<th><div class="fa fa-cog1"></div></th>
						</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div class="modal-footer bg-light">
					<form name="f1" action="<?= site_url("admin/registry/ajax_add_expenses"); ?>" class="mb-3">
						<input type="hidden" name="from_cash" value="1">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<input type="text" class="form-control-sm w-100" id="amount" name="amount" placeholder="Суммани киритинг">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<select id="payment_type_id" name="payment_type_id" class="select">
										<?php foreach ($payment_type_options as $id => $name) {?>
											<option value="<?= $id ?>"><?= $name ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<select id="expense_type_id" name="expense_type_id" class="select">
										<?php foreach ($expense_type_options as $id => $name) {?>
											<option value="<?= $id ?>"><?= $name ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<textarea class="form-control" name="reason" id="reason" rows="3" placeholder="Сабабини ёзинг"></textarea>
						</div>
						<div class="float-right">
							<button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Ойнани ёпиш</button>
							<button type="button" class="btn btn-primary btn-lg js_add_expense" data-url="<?= site_url("admin/registry/ajax_show_expenses") ?>">Бажариш</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Xamkorlarga tulovlar  -->
	<div class="modal checkout_modal_wrapper" id="partners_checkout" tabindex="-1" data-backdrop="static">
		<div class="modal-dialog modal-xl modal-dialog-scrollable1">
			<div class="modal-content">
				<div class="modal-header bg-light pb-0">
					<h4 class="text-primary">Хамкорлар</h4>
					<table width="100%" border="0" cellspacing="0" cellpadding="0px" class="text-center mb-3">
						<tr>
							<th width="33%">Кирим (сум)</th>
							<th width="33%">Чиқим (сум)</th>
							<th width="33%">Касса (сум)</th>
						</tr>
						<tr>
							<td class="js_total_income   reg_cash_format"></td>
							<td class="js_total_expense  reg_cash_format"></td>
							<td class="js_total_cash     reg_cash_format reg_cash_format--red"></td>
						</tr>
					</table>
				</div>
				<div class="modal-body">
					<table class="table table-border table-striped custom-table mb-0 compact expenses_table" id="js_datatable_partners">
						<thead>
						<tr>
							<th>Ким киритди</th>
							<th>Сумма</th>
							<th>Тўлов тури</th>
							<th>Хамкор</th>
							<th><div class="fa fa-cog1"></div></th>
						</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div class="modal-footer bg-light">

					<form name="f2" action="<?= site_url("admin/partners/ajax_partner_checkout"); ?>" class="mb-3">
						<input type="hidden" name="from_cash" value="1">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<input type="text" class="form-control-sm w-100" name="amount" placeholder="Суммани киритинг">
									<small class="text-danger"></small>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<select name="payment_type_id" class="select">
										<?php foreach ($payment_type_options as $id => $name) {?>
											<option value="<?= $id ?>"><?= $name ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<select name="partner_id" class="select">
										<option value="">-- Хамкорни танлаш --</option>
										<?php foreach ($partners as $partner) {?>
											<option value="<?= $partner["id"]; ?>"><?= $partner["last_name"]." ".$partner["first_name"]; ?></option>
										<?php } ?>
									</select>
									<small class="text-danger "></small>
								</div>
							</div>
						</div>
						<div class="float-right">
							<button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Ойнани ёпиш</button>
							<button type="button" class="btn btn-primary btn-lg js_add_partner_payment">Бажариш</button>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>

	<!-- Shifokorlarga tulovlar  -->
	<div class="modal checkout_modal_wrapper" id="doctors_checkout" tabindex="-1" data-backdrop="static">
		<div class="modal-dialog modal-xl modal-dialog-scrollable1">
			<div class="modal-content">
				<div class="modal-header bg-light pb-0">
					<h4 class="text-primary">Шифокорлар</h4>
					<table width="100%" border="0" cellspacing="0" cellpadding="0px" class="text-center mb-3">
						<tr>
							<th width="33%">Кирим (сум)</th>
							<th width="33%">Чиқим (сум)</th>
							<th width="33%">Касса (сум)</th>
						</tr>
						<tr>
							<td class="js_total_income   reg_cash_format"></td>
							<td class="js_total_expense  reg_cash_format"></td>
							<td class="js_total_cash     reg_cash_format reg_cash_format--red"></td>
						</tr>
					</table>
				</div>
				<div class="modal-body">
					<table class="table table-border table-striped custom-table mb-0 compact expenses_table" id="js_datatable_doctors">
						<thead>
						<tr>
							<th>Ким киритди</th>
							<th>Сумма</th>
							<th>Тўлов тури</th>
							<th>Шифокор</th>
							<th><div class="fa fa-cog1"></div></th>
						</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div class="modal-footer bg-light">

					<form name="f2" action="<?= site_url("admin/doctors/ajax_doctor_checkout"); ?>" class="mb-3">
						<input type="hidden" name="from_cash" value="1">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<input type="text" class="form-control-sm w-100" name="amount" placeholder="Суммани киритинг">
									<small class="text-danger"></small>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<select name="payment_type_id" class="select">
										<?php foreach ($payment_type_options as $id => $name) {?>
											<option value="<?= $id ?>"><?= $name ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<select name="doctor_id" class="select">
										<option value="">-- Шифокорни танлаш --</option>
										<?php foreach ($doctors as $doctor) {?>
											<option value="<?= $doctor["id"]; ?>"><?= $doctor["last_name"]." ".$doctor["first_name"]; ?></option>
										<?php } ?>
									</select>
									<small class="text-danger "></small>
								</div>
							</div>
						</div>
						<div class="float-right">
							<button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Ойнани ёпиш</button>
							<button type="button" class="btn btn-primary btn-lg js_add_doctor_payment">Бажариш</button>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>

<?php } ?>

