<div class="row mt-5">
	<div class="col-md-12">
		<h3 class="text-center">Шифокорлар кунлик хисобот</h3>

		<table class="table table-borderless custom-table mb-0 compact1 font-13">
			<thead>
			<tr class="bg-primary text-white">
				<th class="align-text-top">Номи<br></th>
				<th class="align-text-top text-right">Жами<br></th>
				<th class="align-text-top text-right">Улуш %</th>
				<th class="align-text-top text-right">Улуш сўм</th>
			</tr>
			</thead>
			<tbody>
			<!-- Yullanma bergan shifokorlar ulushlari -->

			<?php foreach ($sender_doctors_report as $doctor_id => $service_modules) {?>
				<?php $total = $partner_total = $counter = 0;  ?>
				<?php foreach ($service_modules as $service_module_name => $service_module) {?>
					<?php if($service_module["total"]) {?>
						<?php if($counter === 0) {?>
							<tr><td colspan="5"><strong><?= $doctors_array[$doctor_id]; ?></strong></td></tr>
						<?php } ?>
						<?php
						$total +=$service_module["total"];
						$partner_total +=$service_module["partner_total"];
						?>
						<tr class="text-right">
							<td class="text-left"><?= $service_module_name; ?></td>
							<td><?= $service_module["total"]; ?></td>
							<td><?= $service_module["partner_share"]; ?></td>
							<td><?= $service_module["partner_total"]; ?></td>
						</tr>
						<?php $counter++; ?>
					<?php } ?>

				<?php } ?>

				<?php if($total > 0) {?>
					<tr class="text-right text-primary">
						<td class="text-left">Жами:</td>
						<td><strong><?= $total; ?></strong></td>
						<td></td>
						<td><strong><?= $partner_total; ?></strong></td>
					</tr>
					<tr><td colspan="5">&nbsp;</td></tr>
				<?php } ?>
			<?php } ?>

			</tbody>
		</table>


	</div>
</div>
