<?php $tTotal = 0 ?>
<?php if(count($uzis) > 0) {?>
	<?php foreach ($uzis as $uzi) {?>
		<?php $tTotal += $uzi["paid"]; ?>
	<?php } ?>
<?php } ?>

<div class="container-fluid p-0">
	<h4 class="text-danger" style="margin-top: -15px;"><?= date_formating(time(), "mt"); ?></h4>
	<div class="card no-shadow bg-transparent no-border rm-borders mb-3 report_page">
		<div class="card">
			<div class="p-0 row">
				<div class="col-md-12 col-lg-2 bg-success1">
					<ul class="list-group list-group-flush">
						<li class="bg-transparent list-group-item">
							<div class="widget-content p-0">
								<div class="widget-content-outer">
									<div class="widget-content-wrapper">
										<div class="widget-content-left">
											<div class="widget-heading">Исм</div>
											<div class="font-weight-bold">Рустамов Бахтиёржон</div>
										</div>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>
				<div class="col-md-12 col-lg-2 bg-success1">
					<ul class="list-group list-group-flush">
						<li class="bg-transparent list-group-item">
							<div class="widget-content p-0">
								<div class="widget-content-outer">
									<div class="widget-content-wrapper">
										<div class="widget-content-left">
											<div class="widget-heading">Улуш (%)</div>
											<div class="widget-subheading1 font-18 text-danger">40</div>
										</div>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>

				<div class="col-md-12 col-lg-2 bg-success1">
					<ul class="list-group list-group-flush">
						<li class="bg-transparent list-group-item">
							<div class="widget-content p-0">
								<div class="widget-content-outer">
									<div class="widget-content-wrapper">
										<div class="widget-content-left">
											<div class="widget-heading">Умумий тушум</div>
											<div class="widget-subheading1 font-18 text-dark"><?= money_formatting($tTotal); ?></div>
										</div>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>
				<div class="col-md-12 col-lg-2">
					<ul class="list-group list-group-flush">
						<li class="bg-transparent list-group-item">
							<div class="widget-content p-0">
								<div class="widget-content-outer">
									<div class="widget-content-wrapper">
										<div class="widget-content-left">
											<div class="widget-heading">Улуш (sum)</div>
											<div class="font-18 text-danger"><?= money_formatting($tTotal * 0.4); ?></div>
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
	<div class="col-md-12">
		<table class="table">
			<thead>
			<tr>
				<th width="15%">Чек №</th>
				<th width="15%">Бемор</th>
				<th width="20%">Йўлланма берувчи</th>
				<th width="20%">Сана</th>
				<th class="text-right">Тушум</th>
			</tr>
			</thead>
			<tbody>
			<?php $total = 0; ?>
			<?php if(count($uzis) > 0) {?>
			<?php foreach ($uzis as $uzi) {?>
				<tr>
					<td><?= $uzi["id"]; ?></td>
					<td><?= $uzi["last_name"]." ".$uzi["first_name"]; ?></td>
					<td><?= $uzi["partner_id"] == 0 ? "<span class='text-info'>Ташқаридан</span>" : $uzi["partner_last_name"]." ".$uzi["partner_first_name"]; ?></td>
					<td><?= date("d.m.Y H:i", strtotime($uzi["created_date"])); ?></td>
					<td class="text-right"><?= money_formatting($uzi["paid"]); ?></td>
				</tr>
					<?php $total += $uzi["paid"]; ?>
			<?php } ?>
			<?php } else { ?>
				<tr><td colspan="5" class="text-center p-4">Маълумот топилмади</td></tr>
			<?php } ?>
			</tbody>
            <?php if(count($uzis) > 0) {?>
			<tfoot>
			<tr>
				<td colspan="5" class="text-right bg-header font-18 font-weight-bold"><?= money_formatting($total); ?></td>
			</tr>
			</tfoot>
            <?php } ?>
		</table>
	</div>
</div>
