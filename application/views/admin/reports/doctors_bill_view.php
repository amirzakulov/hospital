<?php $this->load->view('admin/reports/header_template_view'); ?>
<div class="row">
	<div class="col-md-12">
		<table class="table">
			<thead>
			<tr>
				<th width="20%">Шифокор</th>
				<th width="20%">Менеджер</th>
				<th width="20%">Сана</th>
				<th class="text-right">Сумма</th>
			</tr>
			</thead>
			<tbody>
			<?php $total = 0; ?>
            <?php if(count($doctors_bill) > 0) {?>
			<?php foreach ($doctors_bill as $bill) {?>
				<tr>
					<td><?= $bill["last_name"]." ".$bill["first_name"]; ?></td>
					<td><?= $bill["user_id"]; ?></td>
					<td><?= date("d.m.Y", strtotime($bill["created_date"])); ?></td>
					<td class="text-right"><?= money_formatting($bill["amount"]); ?></td>
				</tr>
				<?php $total += $bill["amount"]; ?>
			<?php } ?>
            <?php } else { ?>
                <tr><td colspan="7" class="text-center p-4">Маълумот топилмади</td></tr>
            <?php } ?>
			</tbody>
            <?php foreach ($doctors_bill as $bill) {?>
			<tfoot>
			<tr>
				<td colspan="4" class="text-right bg-header font-18 font-weight-bold"><?= money_formatting($total); ?></td>
			</tr>
			</tfoot>
            <?php } ?>
		</table>
	</div>
</div>
<?php $this->load->view('admin/reports/footer_template_view'); ?>
