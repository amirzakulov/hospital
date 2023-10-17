<div class="row mt-5">
	<div class="col-md-12">
		<h3 class="text-center">Шифокорлар ойлик хисобот</h3>

		<table class="table table-borderless custom-table mb-0 compact1 font-13">
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
						$bill = $CI->doctors_bill_model->get_doctor_bill_by_date($doctor_id, '2023-10-01', '2023-10-30');
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
						<td width="200" class="text-right">
							<div class="input-group float-right" style="width: 150px;">
								<input class="form-control form-control-sm" name="doctor_payment" type="text">
								<div class="input-group-append">
									<button class="btn btn-primary btn-sm js_pay_doctor_bill" type="button"
											data-url="<?= site_url('admin/doctors/ajax_doctor_checkout') ?>"
											data-doctor-id="<?= $doctor_id ?>"
											data-paid="<?= $amount ?>"
											data-debt="<?= $partner_total - $amount ?>"
									><span class="fa fa-check"></span></button>
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





<!--		<table class="table table-borderless custom-table mb-0 compact1 font-13">-->
<!--			<thead>-->
<!--			<tr class="bg-primary text-white">-->
<!--				<th class="align-text-top">Номи<br></th>-->
<!--				<th class="align-text-top text-right">Жами<br></th>-->
<!--				<th class="align-text-top text-right">Улуш %</th>-->
<!--				<th class="align-text-top text-right">Улуш сўм</th>-->
<!--				<th class="align-text-top text-right">Клиника</th>-->
<!--			</tr>-->
<!--			</thead>-->
<!--			<tbody>-->
<!--			<tr><td colspan="5"><strong>Зарифжон Муродов (уролог)</strong></td></tr>-->
<!--			<tr class="text-right">-->
<!--				<td class="text-left">Лаборатория</td>-->
<!--				<td>5 000 000</td>-->
<!--				<td>20</td>-->
<!--				<td>1 000 000</td>-->
<!--				<td>4 000 000</td>-->
<!--			</tr>-->
<!--			<tr class="text-right">-->
<!--				<td class="text-left">УЗИ</td>-->
<!--				<td>5 000 000</td>-->
<!--				<td>20</td>-->
<!--				<td>1 000 000</td>-->
<!--				<td>4 000 000</td>-->
<!--			</tr>-->
<!--			<tr class="text-right">-->
<!--				<td class="text-left">Ётоқ</td>-->
<!--				<td>5 000 000</td>-->
<!--				<td>20</td>-->
<!--				<td>1 000 000</td>-->
<!--				<td>4 000 000</td>-->
<!--			</tr>-->
<!--			<tr class="text-right">-->
<!--				<td class="text-left">Муолажа хизмати</td>-->
<!--				<td>5 000 000</td>-->
<!--				<td>20</td>-->
<!--				<td>1 000 000</td>-->
<!--				<td>4 000 000</td>-->
<!--			</tr>-->
<!--			<tr class="text-right">-->
<!--				<td></td>-->
<!--				<td><strong>20 000 000</strong></td>-->
<!--				<td></td>-->
<!--				<td><strong>4 000 000</strong></td>-->
<!--				<td><strong>16 000 000</strong></td>-->
<!--			</tr>-->
<!---->
<!--			<tr><td colspan="5"><strong>Абдулазиз Хамролиев (Кардиолог)</strong></td></tr>-->
<!--			<tr class="text-right">-->
<!--				<td class="text-left">Лаборатория</td>-->
<!--				<td>5 000 000</td>-->
<!--				<td>20</td>-->
<!--				<td>1 000 000</td>-->
<!--				<td>4 000 000</td>-->
<!--			</tr>-->
<!--			<tr class="text-right">-->
<!--				<td class="text-left">УЗИ</td>-->
<!--				<td>5 000 000</td>-->
<!--				<td>20</td>-->
<!--				<td>1 000 000</td>-->
<!--				<td>4 000 000</td>-->
<!--			</tr>-->
<!--			<tr class="text-right">-->
<!--				<td class="text-left">ЭКГ</td>-->
<!--				<td>5 000 000</td>-->
<!--				<td>20</td>-->
<!--				<td>1 000 000</td>-->
<!--				<td>4 000 000</td>-->
<!--			</tr>-->
<!--			<tr class="text-right">-->
<!--				<td class="text-left">Холтер</td>-->
<!--				<td>5 000 000</td>-->
<!--				<td>20</td>-->
<!--				<td>1 000 000</td>-->
<!--				<td>4 000 000</td>-->
<!--			</tr>-->
<!--			<tr class="text-right">-->
<!--				<td class="text-left">Ётоқ</td>-->
<!--				<td>5 000 000</td>-->
<!--				<td>20</td>-->
<!--				<td>1 000 000</td>-->
<!--				<td>4 000 000</td>-->
<!--			</tr>-->
<!--			<tr class="text-right">-->
<!--				<td></td>-->
<!--				<td><strong>20 000 000</strong></td>-->
<!--				<td></td>-->
<!--				<td><strong>4 000 000</strong></td>-->
<!--				<td><strong>16 000 000</strong></td>-->
<!--			</tr>-->
<!--			</tbody>-->
<!--		</table>-->
	</div>
</div>
