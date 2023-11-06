<div class="row mt-5">
	<div class="col-md-12">
		<h3 class="text-center">Хамкор лаборатория ойлик хисобот</h3>



		<table class="table table-borderless custom-table mb-0 compact1 font-13">
			<thead>
			<tr class="bg-primary text-white">
				<th class="align-text-top" width="100">Номи<br></th>
				<th class="align-text-top text-right" width="200">Жами<br></th>
				<th class="align-text-top text-right" width="200">Улуш сўм</th>
				<th class="align-text-top text-right" width="200">Тўланди</th>
				<th class="align-text-top text-right" width="200">Қолди</th>
				<th class="align-text-top text-right"></th>
			</tr>
			</thead>
			<tbody>

			<?php
			$partner_id = 8;
			$CI =& get_instance();
			$bill = $CI->partners_bill_model->get_partner_bill_by_date($partner_id, $m_start_date, $m_end_date);
			$amount = is_null($bill) ? 0 : $bill['amount'];
			?>
			<tr class="text-right">
				<td class="text-left">VitaMed</td>
				<td><?= $vitamed_monthly["total"]; ?></td>
				<td><?= $vitamed_monthly["partner_total"]; ?></td>
				<td class="js_partner_bill_paid"><strong><?= $amount; ?></strong></td>
				<td class="js_partner_bill_left"><strong><?= $vitamed_monthly["partner_total"] - $amount; ?></strong></td>
				<td class="text-right">
					<input type="hidden" name="paid" value="<?= $amount; ?>">
					<input type="hidden" name="debt" value="<?= $vitamed_monthly["partner_total"] - $amount; ?>">
					<div class="input-group text-right">
						<div class="input-group-prepend ml-auto">
							<select name="payment_type_id" class="form-control form-control-sm">
								<?php foreach ($payment_types as $id => $type) {?>
									<option value="<?= $id; ?>"><?= $type; ?></option>
								<?php } ?>
							</select>
						</div>
						<input class="form-control-sm" name="payment" type="text">
						<div class="input-group-append">
							<button class="btn btn-primary btn-sm js_pay_partner_bill" type="button"
									data-url="<?= site_url('admin/partners/ajax_partner_checkout') ?>"
									data-partner-id="<?= $partner_id; ?>"
							><span class="fa fa-check"></span></button>
						</div>
						<div class="input-group-append">
							<button class="btn btn-success btn-sm js_show_partner_bills border-dark" type="button"
									data-url="<?= site_url('admin/partners/ajax_show_partner_bills') ?>"
									data-partner-id="<?= $partner_id ?>"
									data-start-date="<?= $m_start_date ?>"
									data-end-date="<?= $m_end_date ?>"
							><span class="fa fa-eye"></span></button>
						</div>
					</div>
					<small class="text-danger js_pay_partner_bill_error float-right1"></small>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
