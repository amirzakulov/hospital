<?php //$this->load->view('admin/reports/header_template_view'); ?>
<div class="row">
	<div class="col-md-12">
		<table class="table">
			<thead>
			<tr>
				<th width="15%">Тўланган вақт</th>
				<th width="15%">Чек №</th>
				<th width="20%">Бемор</th>
				<th width="30%">Хизмат тури</th>
				<th class="text-right">Тўланган қарз</th>
			</tr>
			</thead>
			<tbody>
			<?php $total = 0; ?>
			<?php if(count($patients) > 0) { ?>
			<?php foreach ($patients as $payment_id => $patient) { ?>
			<tr>
				<td><?= date("d.m.Y H:i", strtotime($patient["debt_off_date"])); ?></td>
				<td><strong><?= $patient["payment_id"]; ?></strong></td>
				<td><?= $patient["patient_name"]; ?></td>
				<td>
					<table class="table table-borderless p-0 m-0" style="border: 0 !important;">
					<?php $service_debt = 0; ?>
					<?php foreach ($patient["service_types"] as $service_type_id => $service_name) {?>
						<tr>
							<td width="40%" class="p-0 text-info"><?= $service_name; ?></td>
							<td width="50%" class="p-0"><?= money_formatting($patient["amount"][$service_type_id]); ?></td>
						</tr>
						<?php $service_debt += $patient["amount"][$service_type_id]; ?>
					<?php } ?>
					</table>
				</td>
				<td class="text-right"><?= money_formatting($service_debt); ?></td>
			</tr>
			<?php $total += $service_debt; ?>
			<?php } ?>
			<?php } else { ?>
				<tr><td colspan="7" class="text-center p-4">Маълумот топилмади</td></tr>
			<?php } ?>

			</tbody>
			<?php if(count($patients) > 0) {?>
				<tfoot>
				<tr>
					<td colspan="6" class="text-right bg-header font-18 font-weight-bold"><?= money_formatting($total); ?></td>
				</tr>
				</tfoot>
			<?php } ?>
		</table>
	</div>
</div>
<?php //$this->load->view('admin/reports/footer_template_view'); ?>
