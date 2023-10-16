<?php //$this->load->view('admin/reports/header_template_view'); ?>
<div class="row">
	<div class="col-md-12">
		<table class="table">
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
			<?php if(count($services) > 0) {?>
			<?php foreach ($services as $service) {?>
				<tr>
					<td><?= ++$counter; ?></td>
					<td><?= $service["id"]; ?></td>
					<td><?= $service["last_name"]." ".$service["first_name"]; ?></td>
					<td>
						<?php
						$partner = "<span class='text-info'>Ташқаридан</span>";
						if($service["partner_id"] > 0) $partner = $service["partner_last_name"]." ".$service["partner_first_name"];
						if($service["sender_doctor_id"] > 0) $partner = $service["sender_doctor_last_name"]." ".$service["sender_doctor_first_name"];

						?>
						<?= $partner; ?>

					</td>
					<td><?= date("d.m.Y H:i", strtotime($service["created_date"])); ?></td>
					<td class="text-right"><?= money_formatting($service["paid"]); ?></td>
				</tr>
					<?php $total += $service["paid"]; ?>
			<?php } ?>
			<?php } else { ?>
				<tr><td colspan="7" class="text-center p-4">Маълумот топилмади</td></tr>
			<?php } ?>
			</tbody>
            <?php if(count($services) > 0) {?>
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
