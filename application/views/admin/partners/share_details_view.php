
<?= $breadcrumbs; ?>

<div class="container-fluid p-0">
	<div class="card no-shadow bg-transparent no-border rm-borders mb-3 report_page">
		<div class="card">
			<div class="p-0 row">
				<div class="col-md-12 col-lg-4">
					<ul class="list-group list-group-flush">
						<li class="bg-transparent list-group-item">
							<div class="widget-content p-0">
								<div class="widget-content-outer">
									<div class="widget-content-wrapper">
										<div class="widget-content-left">
											<div class="widget-heading"><?= $partner["last_name"] .' '. $partner["first_name"]; ?></div>
											<div class="widget-subheading"><span class="text-dark">Йўналиш: </span><?= $partner["job_title"]; ?></div>
											<div class="widget-subheading"><span class="text-dark">Манзил: </span><?= $partner["address"]; ?></div>
											<div class="widget-subheading"><span class="text-dark">Телефон: </span><?= $partner["phone"]; ?></div>
										</div>
										<div class="widget-content-right">
											<span>Келишув</span>
											<div class="widget-numbers text-primary"><?= $partner["agreement"] ?> %</div>
										</div>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>

			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12"></div>
</div>
<div class="row">
	<div class="col-md-7">
		<h3>Кирим</h3>
		<table class="table table-bordered table-striped datatable mb-0 table-small">
			<thead class="bg-dark text-white">
			<tr>
				<th>Чек №</th>
				<th>Бемор</th>
				<th>Сана</th>
				<th>Тўлов</th>
				<th>Улуш (Сум)</th>
			</tr>
			</thead>
			<tbody>
			<?php $total_paid = $total_amount = $counter = 0; ?>
			<?php foreach ($patients as $patient) {?>
				<tr>
					<td class="font-weight-bold"><?= $patient["payment_id"]; ?></td>
					<td><?= $patient["last_name"] .' '. $patient["first_name"]; ?></td>
					<td><?= date("d.m.Y H:i", strtotime($patient["created_date"])); ?></td>
					<td><?= money_formatting($patient["paid"]); ?></td>
					<td><?= money_formatting($patient["amount"]); ?></td>
				</tr>
				<?php $total_paid += $patient["paid"]; ?>
				<?php $total_amount += $patient["amount"]; ?>
			<?php } ?>
			</tbody>

			<tfoot class="bg-dark text-white">
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td class="font-weight-bold"><?= money_formatting($total_paid); ?></td>
				<td class="font-weight-bold"><?= money_formatting($total_amount); ?></td>
			</tr>
			</tfoot>
		</table>
	</div>

	<div class="col-md-5">
		<h3>Чиқим</h3>
		<table class="table table-bordered table-striped datatable mb-0 table-small">
			<thead class="bg-dark text-white">
			<tr>
				<th>Сана</th>
				<th>Тўлов</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($bills as $bill) {?>
				<tr>
					<td><?= money_formatting($bill["amount"]); ?></td>
					<td><?= date("d.m.Y H:i", strtotime($bill["created_date"])); ?></td>
				</tr>
			<?php } ?>
			</tbody>

		</table>
	</div>
</div>


