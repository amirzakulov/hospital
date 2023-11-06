<div class="row mt-5">
	<div class="col-md-12">
		<h3 class="text-center">Шифокорлар ойлик хисобот</h3>

		<table class="table table-borderless custom-table mb-0 font-13">
			<thead>
			<tr class="bg-primary text-white">
				<th class="align-text-top">Номи<br></th>
				<th class="align-text-top text-right">Жами<br></th>
				<th class="align-text-top text-right">Улуш %</th>
				<th class="align-text-top text-right">Улуш сўм</th>
				<th class="align-text-top text-right">Тўланди</th>
				<th class="align-text-top text-right">Қолди</th>
				<th class="align-text-top text-right"></th>
			</tr>
			</thead>
			<tbody>
			<!-- Yullanma bergan shifokorlar ulushlari -->

			<?php foreach ($sender_doctors_monthly_report as $doctor_id => $service_modules) {?>
				<?php $total = $partner_total = $counter = 0;  ?>
				<?php foreach ($service_modules as $service_module_name => $service_module) {?>
					<?php if($service_module["total"]) {?>
						<?php if($counter === 0) {?>
							<tr><td colspan="7"><strong><?= $doctors_array[$doctor_id]; ?></strong></td></tr>
						<?php } ?>
						<?php
						$total +=$service_module["total"];
						$partner_total +=$service_module["partner_total"];
						?>

						<?php
						$CI =& get_instance();
						$bill = $CI->doctors_bill_model->get_doctor_bill_by_date($doctor_id, $m_start_date, $m_end_date);
						$amount = is_null($bill) ? 0 : $bill['amount'];
						?>
						<tr class="text-right">
							<td class="text-left"><?= $service_module_name; ?></td>
							<td><?= $service_module["total"]; ?></td>
							<td><?= $service_module["partner_share"]; ?></td>
							<td><?= $service_module["partner_total"]; ?></td>
							<td colspan="3">&nbsp;</td>
						</tr>
						<?php $counter++; ?>
					<?php } ?>

				<?php } ?>

				<?php if($total > 0) {?>
					<tr class="text-right text-primary">
						<td class="text-left">Жами:</td>
						<td><strong><?= $total; ?></strong></td>
						<td></td>
						<td><strong><?= $partner_total; ?></strong></td>
						<td class="js_doctor_bill_paid"><strong><?= $amount; ?></strong></td>
						<td class="js_doctor_bill_left"><strong><?= $partner_total - $amount; ?></strong></td>
						<td width="300" class="text-right">
							<input type="hidden" name="paid" value="<?= $amount ?>">
							<input type="hidden" name="debt" value="<?= $partner_total - $amount ?>">
							<div class="input-group float-right">
								<div class="input-group-prepend ml-auto d-none">
									<select name="from_cash" class="form-control form-control-sm">
										<option value="0" selected>Депозитдан</option>
										<option value="1">Кассадан</option>
									</select>
								</div>
								<div class="input-group-prepend ml-auto">
									<select name="payment_type_id" class="form-control form-control-sm">
										<?php foreach ($payment_types as $id => $type) {?>
											<option value="<?= $id; ?>"><?= $type; ?></option>
										<?php } ?>
									</select>
								</div>
								<input class="form-control form-control-sm" name="payment" type="text">
								<div class="input-group-append">
									<button class="btn btn-primary btn-sm js_pay_doctor_bill" type="button"
											data-url="<?= site_url('admin/doctors/ajax_doctor_checkout') ?>"
											data-doctor-id="<?= $doctor_id ?>"
									><span class="fa fa-check"></span></button>
								</div>
								<div class="input-group-append">
									<button class="btn btn-success btn-sm js_show_partner_bills border-dark" type="button"
											data-url="<?= site_url('admin/doctors/ajax_show_doctor_bills') ?>"
											data-partner-id="<?= $doctor_id ?>"
											data-start-date="<?= $m_start_date ?>"
											data-end-date="<?= $m_end_date ?>"
									><span class="fa fa-eye"></span></button>
								</div>
							</div>
							<small class="text-danger js_pay_doctor_bill_error"></small>
						</td>
					</tr>
					<tr><td colspan="7">&nbsp;</td></tr>
				<?php } ?>
			<?php } ?>

			</tbody>
		</table>

	</div>
</div>
