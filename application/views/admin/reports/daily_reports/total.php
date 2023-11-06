<div class="row">
	<div class="col-md-12">

		<h3 class="text-center">Умумий кунлик хисобот </h3>

		<table class="table table-borderless custom-table mb-0 compact1 font-13">
			<thead>
			<tr class="bg-primary text-white">
				<th class="align-text-top">Номи<br></th>
				<th class="align-text-top text-right">Жами<br></th>
				<th class="align-text-top text-right">Улуш %</th>
				<th class="align-text-top text-right">Йўлланма берувчи</th>
				<th class="align-text-top text-right">Клиника</th>
			</tr>
			</thead>
			<tbody>

			<tr>
				<td colspan="5" class="text-center text-muted-light font-14">Хизматлар</td>
			</tr>

			<tr>
				<td>Лаборатория</td>
				<td class="text-right"><?= money_formatting($laboratory_total['total']); ?></td>
				<td class="text-right"><?= money_formatting($laboratory_total['share']); ?></td>
				<td class="text-right"><?= money_formatting($laboratory_total['partner']); ?></td>
				<td class="text-right">
					<?php $laboratory_clinic = $laboratory_total['total'] - $laboratory_total['share'] - $laboratory_total['partner']; ?>
					<?= money_formatting($laboratory_clinic); ?>
				</td>
			</tr>

			<tr>
				<td>УЗИ</td>
				<td class="text-right"><?= money_formatting($uzi_total['total']); ?></td>
				<td class="text-right"><?= money_formatting($uzi_total['share']); ?></td>
				<td class="text-right"><?= money_formatting($uzi_total['partner']); ?></td>
				<td class="text-right">
					<?php $uzi_clinic = $uzi_total['total'] - $uzi_total['share'] - $uzi_total['partner']; ?>
					<?= money_formatting($uzi_clinic); ?>
				</td>
			</tr>

			<tr>
				<td>Қўшимча хизматлар</td>
				<td class="text-right"><?= money_formatting($services_total['total']); ?></td>
				<td></td>
				<td class="text-right"><?= money_formatting($services_total['partner']); ?></td>
				<td class="text-right">
					<?php $service_clinic = $services_total['total'] - $services_total['share'] - $services_total['partner']; ?>
					<?= money_formatting($service_clinic); ?>
				</td>
			</tr>

			<tr>
				<td>Ётоқ</td>
				<td class="text-right"><?= money_formatting($rooms_total['total']); ?></td>
				<td class="text-right"></td>
				<td class="text-right"><?= money_formatting($rooms_total['partner']); ?></td>
				<td class="text-right">
					<?php $room_clinic = $rooms_total['total'] - $rooms_total['share'] - $rooms_total['partner']; ?>
					<?= money_formatting($room_clinic); ?>
				</td>
			</tr>

			<tr>
				<td>Эски қарзлардан</td>
				<td class="text-right"><?= money_formatting($paid_debts); ?></td>
				<td></td>
				<td></td>
				<td class="text-right"><?= money_formatting($paid_debts); ?></td>
			</tr>

			<tr class="text-primary">
				<td>Жами</td>
				<td class="text-right"><?= money_formatting($laboratory_total['total'] + $uzi_total['total'] + $services_total['total'] + $rooms_total['total'] + $paid_debts); ?></td>
				<td class="text-right"><?= money_formatting($laboratory_total['share'] + $uzi_total['share'] + $services_total['share'] + $rooms_total['share']); ?></td>
				<td class="text-right"><?= money_formatting($laboratory_total['partner'] + $uzi_total['partner'] + $services_total['partner'] + $rooms_total['partner']); ?></td>
				<td class="text-right"><?= $services_clinic = $laboratory_clinic + $uzi_clinic + $service_clinic + $room_clinic + $paid_debts; ?></td>
			</tr>

			<tr>
				<td colspan="5" class="text-center text-muted-light font-14">Шифокорлар</td>
			</tr>

			<?php $doctor_total = $doctor_share = $doctor_partner = $doctors_clinic = 0; ?>
			<?php foreach ($doctors_total as $doctor) { ?>
				<?php
				$doctor_total +=$doctor['total'];
				$doctor_share +=$doctor['share'];
				$doctor_partner +=$doctor['partner'];
				$doctors_clinic +=$doctor['total'] - $doctor['share'] - $doctor['partner'];
				?>
				<tr>
					<td><?= $doctor['name']; ?></td>
					<td class="text-right"><?= money_formatting($doctor['total']); ?></td>
					<td class="text-right"><?= money_formatting($doctor['share']); ?></td>
					<td class="text-right"><?= money_formatting($doctor['partner']); ?></td>
					<td class="text-right">
						<?= money_formatting($doctor['total'] - $doctor['share'] - $doctor['partner']); ?>
					</td>
				</tr>

			<?php } ?>

			<tr class="text-primary">
				<td>Жами</td>
				<td class="text-right"><?= money_formatting($doctor_total); ?></td>
				<td class="text-right"><?= money_formatting($doctor_share); ?></td>
				<td class="text-right"><?= money_formatting($doctor_partner); ?></td>
				<td class="text-right"><?= money_formatting($doctors_clinic); ?></td>
			</tr>

			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>

			<tr>
				<td>Чиқимлар</td>
				<td class="text-danger text-right">-<?= money_formatting($cash["expenditure"]); ?></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>


			<tr>
				<td>Қарз</td>
				<td class="text-danger text-right">-<?= money_formatting($cash["debt"]["debt"]); ?></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>


			<tr>
				<td>Чегирма</td>
				<td class="text-danger text-right">-<?= money_formatting($cash["total_payment"]["discount"]); ?></td>
				<td></td>
				<td></td>
				<td class="text-danger text-right"></td>
			</tr>

<!--			<tr>-->
<!--				<td colspan="5">&nbsp;99999999</td>-->
<!--			</tr>-->

			<tr class="text-primary">
				<td>Касса: </td>
				<td class="text-right">
					<?php $cash_total = ($laboratory_total['total'] + $uzi_total['total'] + $services_total['total'] + $rooms_total['total'] + $doctor_total + $paid_debts) - ($cash["expenditure"] + $cash["debt"]["debt"] + $cash["total_payment"]["discount"]); ?>
					<?= money_formatting($cash_total); ?>
				</td>
				<td colspan="2"></td>
				<td class="text-right text-warning">
					<?php
					$clinic_total = ($services_clinic + $doctors_clinic);
					?>
					Клиника: <?= money_formatting($clinic_total); ?>
				</td>
			</tr>



			</tbody>
		</table>
	</div>
</div>
