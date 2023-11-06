<div class="row mt-5">
	<div class="col-md-12">
		<h3 class="text-center">Чиқимлар ойлик хисобот</h3>

		<div><?php ?></div>

		<table class="table table-borderless custom-table mb-0 compact1 font-13">
			<thead>
			<tr class="bg-primary text-white">
				<th class="align-text-top" width="100">Номи<br></th>
				<th class="align-text-top text-right" width="200">Сумма<br></th>
			</tr>
			</thead>
			<tbody>
			<?php $total = 0; ?>
			<?php foreach ($expenditure_monthly as $expense) { ?>
				<?php $total += $expense["amount"]; ?>
				<tr>
					<td class="text-left"><?= $expense["name"]; ?></td>
					<td class="text-right"><?= $expense["amount"]; ?></td>
				</tr>
			<?php } ?>
			<tr>
				<td class="text-left"><strong>Жами:</strong></td>
				<td class="text-right"><strong><?= $total ?></strong></td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
