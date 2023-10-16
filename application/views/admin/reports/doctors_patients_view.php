<?//= $breadcrumbs; ?>
<div class="row">
	<div class="col-md-12">
		<table class="table">
			<thead>
			<tr>
                <th width="20%">Чек №</th>
                <th width="20%">Бемор</th>
				<th width="20%">Йўлланма берувчи</th>
                <th width="15%">Сана</th>
				<th class="text-right">Тушум</th>
			</tr>
			</thead>
			<tbody>
			<?php $total = 0; ?>
            <?php foreach ($patients as $patient) {?>
                <?php $payment = is_null($patient["debt"]) ? $patient["price"]:$patient["real_payment"]; ?>
                <tr>
                    <td><?= $patient["payment_id"]; ?></td>
                    <td><?= $patient["patient_last_name"] .' '. $patient["patient_first_name"]; ?></td>
					<td>
						<?php
							$partner = "<span class='text-info'>Ташқаридан</span>";
							if($patient["partner_id"] > 0) $partner = $patient["partner_last_name"]." ".$patient["partner_first_name"];
							if($patient["sender_doctor_id"] > 0) $partner = $patient["sender_doctor_last_name"]." ".$patient["sender_doctor_first_name"];
						?>
						<?= $partner; ?>
					</td>
                    <td><?= date("d.m.Y H:i", strtotime($patient["created_date"])); ?></td>
                    <td class="text-right"><?= money_formatting($payment); ?></td>
                </tr>
                <?php $total += $payment; ?>
            <?php } ?>
			</tbody>

			<tfoot>
			<tr>
				<td colspan="6" class="text-right bg-header font-18 font-weight-bold"><?= money_formatting($total); ?></td>
			</tr>
			</tfoot>
		</table>
	</div>
</div>
