<?php //$this->load->view('admin/reports/header_template_view'); ?>
<div class="row">
	<div class="col-md-12"></div>
	<div class="col-md-12">
		<table class="table report_datatable">
			<thead>
			<tr>
				<th width="1%">#</th>
				<th width="15%">Чек №</th>
				<th width="15%">Бемор</th>
				<th width="15%">Йўлланма берувчи</th>
				<th width="15%">Сана</th>
				<th width="15%">Хамкор</th>
				<th width="15%">Клиника</th>
				<th class="text-right" width="15%">Тўлов</th>
			</tr>
			</thead>
			<tbody>
			<?php $total = $total_clinic = $total_eurolab = $counter = 0; ?>
			<?php if(count($laboratories) > 0) {?>
				<?php foreach ($laboratories as $laboratory) {?>
					<tr>
						<td><?= ++$counter; ?></td>
						<td><?= $laboratory["id"]; ?></td>
						<td><?= $laboratory["last_name"]." ".$laboratory["first_name"]; ?></td>
						<td>
							<?php
							$partner = "<span class='text-info'>Ташқаридан</span>";
							if($laboratory["partner_id"] > 0) $partner = $laboratory["partner_last_name"]." ".$laboratory["partner_first_name"];
							if($laboratory["sender_doctor_id"] > 0) $partner = $laboratory["sender_doctor_last_name"]." ".$laboratory["sender_doctor_first_name"];

							?>
							<?= $partner; ?>

						</td>
						<td><?= date("d.m.Y", strtotime($laboratory["created_date"])); ?></td>
						<td><?= $laboratory["price_partner"]; ?></td>
						<td><?= $amount = ($laboratory["price"] * $laboratory["count"]) - $laboratory["price_partner"]; ?></td>
						<td class="text-right"><?= money_formatting($laboratory["paid"]); ?></td>
					</tr>
					<?php $total += $laboratory["paid"]; ?>
					<?php $total_clinic += $amount; ?>
					<?php $total_eurolab += $laboratory["price_partner"]; ?>

				<?php } ?>
			<?php } else { ?>
			<tr><td colspan="7" class="text-center p-4">Маълумот топилмади</td></tr>
			<?php } ?>
			</tbody>
            <?php if(count($laboratories) > 0) {?>
			<tfoot>
			<tr class="font-weight-bold">
                <td class="bg-header"></td>
                <td class="bg-header"></td>
                <td class="bg-header"></td>
                <td class="bg-header"></td>
                <td class="bg-header"></td>
				<td class="bg-header"><?= $total_eurolab > 0 ? $total_eurolab : ""; ?></td>
				<td class="bg-header"><?= $total_clinic; ?></td>
				<td class="text-right bg-header font-18 font-weight-bold"><?= money_formatting($total); ?></td>
			</tr>
			</tfoot>
            <?php } ?>
		</table>
	</div>
</div>
<?php //$this->load->view('admin/reports/footer_template_view'); ?>
