<?php //$this->load->view('admin/reports/header_template_view'); ?>
<div class="row">
	<div class="col-md-12">
		<table class="table report_datatable">
			<thead>
			<tr>
				<th width="1%">#</th>
				<th width="15%">Чек №</th>
				<th width="15%">Бемор</th>
				<th width="20%">Йўлланма берувчи</th>
				<th width="20%">Сана</th>
				<th class="text-right">Тушум</th>
			</tr>
			</thead>
			<tbody>
			<?php $total = $counter = 0; ?>
			<?php if(count($uzis) > 0) {?>
			<?php foreach ($uzis as $uzi) {?>
				<tr>
					<td><?= ++$counter; ?></td>
					<td><?= $uzi["id"]; ?></td>
					<td><?= $uzi["last_name"]." ".$uzi["first_name"]; ?></td>
					<td>
						<?php
						$partner = "<span class='text-info'>Ташқаридан</span>";
						if($uzi["partner_id"] > 0) $partner = $uzi["partner_last_name"]." ".$uzi["partner_first_name"];
						if($uzi["sender_doctor_id"] > 0) $partner = $uzi["sender_doctor_last_name"]." ".$uzi["sender_doctor_first_name"];

						?>
						<?= $partner; ?>

					</td>
					<td><?= date("d.m.Y H:i", strtotime($uzi["created_date"])); ?></td>
					<td class="text-right"><?= money_formatting($uzi["paid"]); ?></td>
				</tr>
					<?php $total += $uzi["paid"]; ?>
			<?php } ?>
			<?php } else { ?>
				<tr><td colspan="6" class="text-center p-4">Маълумот топилмади</td></tr>
			<?php } ?>
			</tbody>
            <?php if(count($uzis) > 0) {?>
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
