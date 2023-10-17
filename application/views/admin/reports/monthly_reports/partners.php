<div class="row mt-5">
	<div class="col-md-12">
		<h3 class="text-center">Йўналтирувчи хамкорлар ойлик хисобот</h3>

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

			<?php foreach ($partners_monthly_report as $partner_id => $service_modules) {?>
				<?php $total = $partner_total = $counter = 0;  ?>
				<?php foreach ($service_modules as $service_module_name => $service_module) {?>
				<?php if($service_module["total"]) {?>
						<?php if($counter === 0) {?>
							<tr><td colspan="7"><strong><?= $partners_array[$partner_id]; ?></strong></td></tr>
						<?php } ?>
					<?php
					$total +=$service_module["total"];
					$partner_total +=$service_module["partner_total"];
					?>
					<tr class="text-right">
						<td class="text-left"><?= $service_module_name; ?></td>
						<td><?= $service_module["total"]; ?></td>
						<td><?= $service_module["partner_share"]; ?></td>
						<td><?= $service_module["partner_total"]; ?></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<?php $counter++; ?>
				<?php } ?>

				<?php } ?>
				<?php if($total > 0) {?>

				<?php
					$CI =& get_instance();
					$bill = $CI->partners_bill_model->get_partner_bill_by_date($partner_id, '2023-10-01', '2023-10-30');
					$amount = is_null($bill) ? 0 : $bill['amount'];
				?>
				<tr id="js_row_<?= $partner_id; ?>" class="text-right text-primary">
					<td class="text-left">Жами:</td>
					<td><strong><?= $total; ?></strong></td>
					<td></td>
					<td><strong><?= $partner_total; ?></strong></td>
					<td class="js_partner_bill_paid"><strong><?= $amount; ?></strong></td>
					<td class="js_partner_bill_left"><strong><?= $partner_total - $amount; ?></strong></td>
					<td width="200" class="text-right">
						<div class="input-group float-right" style="width: 150px;">
							<input class="form-control form-control-sm" name="payment" type="text">
							<div class="input-group-append">
								<button class="btn btn-primary btn-sm js_pay_partner_bill" type="button"
								data-url="<?= site_url('admin/partners/ajax_partner_checkout') ?>"
								data-partner-id="<?= $partner_id ?>"
								data-paid="<?= $amount ?>"
								data-debt="<?= $partner_total - $amount ?>"
								><span class="fa fa-check"></span></button>
							</div>
						</div>
						<small class="text-danger js_pay_partner_bill_error"></small>
					</td>
				</tr>
				<tr><td colspan="7">&nbsp;</td></tr>
				<?php } ?>
			<?php } ?>

			</tbody>
		</table>
	</div>
</div>
