<?php //$this->load->view('admin/reports/header_template_view'); ?>
<div class="row">
	<div class="col-md-12">
		<table class="table">
			<thead>
			<tr>
				<th width="15%">Чек №</th>
				<th width="15%">Бемор</th>
				<th width="20%">Йўлланма берувчи</th>
				<th width="20%">Сана</th>
				<th width="5%">Хона</th>
				<th class="text-right">Тушум</th>
			</tr>
			</thead>
			<tbody>
			<?php $total = 0; ?>
			<?php if(count($rooms) > 0) {?>
				<?php foreach ($rooms as $room) {?>
					<tr>
						<td><?= $room["id"]; ?></td>
						<td><?= $room["last_name"]." ".$room["first_name"]; ?></td>
						<td>
							<?php
								$partner = "<span class='text-info'>Ташқаридан</span>";
								if($room["partner_id"] > 0) $partner = $room["partner_last_name"]." ".$room["partner_first_name"];
								if($room["sender_doctor_id"] > 0) $partner = $room["sender_doctor_last_name"]." ".$room["sender_doctor_first_name"];
							?>
							<?= $partner; ?>
						</td>
						<td><?= date("d.m.Y", strtotime($room["created_date"])); ?></td>
						<td><?= $room["number"]; ?></td>
						<td class="text-right"><?= money_formatting($room["paid"]); ?></td>
					</tr>
					<?php $total 	+= $room["paid"]; ?>
				<?php } ?>
			<?php } else { ?>
				<tr><td colspan="7" class="text-center p-4">Маълумот топилмади</td></tr>
			<?php } ?>
			</tbody>
            <?php if(count($rooms) > 0) {?>
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
