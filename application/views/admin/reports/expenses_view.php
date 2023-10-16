<?php //$this->load->view('admin/reports/header_template_view'); ?>
<div class="row">
	<div class="col-md-12">
		<table class="table">
			<thead>
			<tr>
				<th width="20%">Исм</th>
				<th width="20%">Тури</th>
				<th width="20%">Сабаб</th>
				<th width="20%">Сана</th>
				<th class="text-right">Сумма</th>
			</tr>
			</thead>
			<tbody>
			<?php $total = 0; ?>
            <?php if(count($expenditure) > 0) {?>
			<?php foreach ($expenditure as $exp) {?>
				<tr>
					<td><?= $exp["last_name"]." ".$exp["first_name"]; ?></td>
					<td><?= $exp["expense_type"]; ?></td>
					<td><?= $exp["reason"]; ?></td>
					<td><?= date("d.m.Y H:i", strtotime($exp["created_date"])); ?></td>
					<td class="text-right"><?= money_formatting($exp["amount"]); ?></td>
				</tr>
				<?php $total += $exp["amount"]; ?>
			<?php } ?>
            <?php } else { ?>
                <tr><td colspan="7" class="text-center p-4">Маълумот топилмади</td></tr>
            <?php } ?>
			</tbody>
            <?php if(count($expenditure) > 0) {?>
			<tfoot>
			<tr>
				<td colspan="5" class="text-right bg-header font-18 font-weight-bold"><?= money_formatting($total); ?></td>
			</tr>
			</tfoot>
            <?php } ?>
		</table>
	</div>
</div>
<?php //$this->load->view('admin/reports/footer_template_view'); ?>
