<?php //$this->load->view('admin/reports/header_template_view'); ?>
<div class="row">
	<div class="col-md-12">
		<table class="table report_datatable">
			<thead>
			<tr>
				<th width="1%">#</th>
				<th width="15%">Чек №</th>
                <th width="20%">Шифокор</th>
				<th width="15%">Сана</th>
				<th width="15%">Чегирма</th>
				<th width="15%">Қарз</th>
				<th class="text-right">Тушум</th>
			</tr>
			</thead>
			<tbody>
			<?php $total = $counter = 0; ?>
			<?php if(count($doctors) > 0) {?>
				<?php foreach ($doctors as $doctor) {?>
					<tr>
						<td><?= ++$counter; ?></td>
						<td><?= $doctor["id"]; ?></td>
                        <td><a class="js_doctor_patients cursor-pointer text-primary" data-href="<?= site_url("admin/reports/ajax_doctors_patients"); ?>"
							data-doctor-id="<?= $doctor["doctor_id"] ?>"
							data-start-date="<?= $start_date_param ?>"
							data-end-date="<?= $end_date_param ?>"
							><?= $doctor["doctor_last_name"] .' '. $doctor["doctor_first_name"]; ?></a></td>
						<td><?= date("d.m.Y", strtotime($start_date_param)); ?> - <?= date("d.m.Y", strtotime($end_date_param)); ?></td>
						<td></td>
						<td></td>
						<td class="text-right"><?= money_formatting($doctor["paid"]); ?></td>
					</tr>
					<?php $total += $doctor["paid"]; ?>
				<?php } ?>
			<?php } else { ?>
			<tr><td colspan="8" class="text-center p-4">Маълумот топилмади</td></tr>
			<?php } ?>
			</tbody>
            <?php if(count($doctors) > 0) {?>
			<tfoot>
			<tr>
				<td colspan="7" class="text-right bg-header font-18 font-weight-bold"><?= money_formatting($total); ?></td>
			</tr>
			</tfoot>
            <?php } ?>
		</table>
	</div>
</div>
<?php //$this->load->view('admin/reports/footer_template_view'); ?>
