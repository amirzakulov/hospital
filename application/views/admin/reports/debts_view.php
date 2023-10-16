<?php //$this->load->view('admin/reports/header_template_view'); ?>
<div class="row">
	<div class="col-md-12">
		<table class="table">
			<thead>
			<tr>
				<th width="15%">Чек №</th>
				<th width="20%">Бемор</th>
				<th width="15%">Сана</th>
				<th width="15%">Умумий</th>
				<th width="15%">Тўланган</th>
				<th>Чегирма</th>
				<th class="text-right">Қарз</th>
			</tr>
			</thead>
			<tbody>
			<?php $total = $paid = $debt = $discount = 0; ?>
			<?php if(count($patients) > 0) { ?>
			<?php foreach ($patients as $patient) { ?>
			<tr>
				<td><?= $patient["payment_id"]; ?></td>
				<td><?= $patient["last_name"] ." ". $patient["first_name"]; ?></td>
				<td><?= date("d.m.Y H:i", strtotime($patient["payment_date"])); ?></td>
				<td><?= $patient["total"]; ?></td>
				<td><?= $patient["paid"]; ?></td>
				<td class="text-danger"><?= $patient["discount"]; ?></td>
				<?php $rdebt = ($patient["total"] - $patient["paid"] - $patient["discount"]); ?>
				<td class="text-right text-danger"><?= $rdebt; ?></td>
			</tr>
			<?php $total += $patient["total"]; ?>
			<?php $paid += $patient["paid"]; ?>
			<?php $debt += $rdebt; ?>
			<?php $discount += $patient["discount"]; ?>
			<?php } ?>
			<?php } else { ?>
				<tr><td colspan="7" class="text-center p-4">Маълумот топилмади</td></tr>
			<?php } ?>

			</tbody>
			<?php if(count($patients) > 0) {?>
				<tfoot>
				<tr>
					<td colspan="3" class="text-right bg-header font-18 font-weight-bold"></td>
					<td class=" bg-header font-18 font-weight-bold"><?= money_formatting($total); ?></td>
					<td class=" bg-header font-18 font-weight-bold"><?= money_formatting($paid); ?></td>
					<td class=" bg-header font-18 font-weight-bold"><?= money_formatting($discount); ?></td>
					<td class="text-right bg-header font-18 font-weight-bold text-danger"><span class=" badge badge-danger"><?= money_formatting($debt); ?></span></td>
				</tr>
				</tfoot>
			<?php } ?>
		</table>
	</div>
</div>
<?php //$this->load->view('admin/reports/footer_template_view'); ?>
