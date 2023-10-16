<div class="row">
	<div class="col-sm-4 col-3 offset-2">
		<h4 class="page-title"><?= $title; ?></h4>
	</div>
	<div class="col-sm-8 col-9 text-right m-b-20">

	</div>
</div>

<div class="row">
	<div class="col-md-8 offset-2 card-box">
		<div class="table-responsive">
			<table class="table table-striped custom-table mb-0 datatable">
				<thead class="bg-dark">
				<tr class="bg-dark text-white">
					<th width="20">#</th>
					<th><?= lang("uzi_name"); ?></th>
					<th width="150">Тил</th>
					<th width="20" class="text-right"></th>
				</tr>
				</thead>
				<tbody>
				<?php $counter = 1; ?>
				<?php foreach ($uzis as $uzi) {?>
					<tr id="js_row_<?= $uzi["id"]; ?>">
						<td><?= $counter++; ?></td>
						<td><?= $uzi["name"]; ?></td>
						<td>
							<div class="btn-group" role="group">
								<button class="btn btn-outline-success p-1 <?= empty($uzi["template"]) ? "":"active"; ?>"><span class="fa fa-file-pdf-o"></span> Узб</button>
								<button class="btn btn-outline-info p-1  <?= empty($uzi["template_ru"]) ? "":"active"; ?>"><span class="fa fa-file-pdf-o"></span> Рус</button>
							</div>
						</td>
						<td class="text-right">
							<div class="dropdown dropdown-action">
								<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="<?= site_url("admin/uzi/template_show/".$uzi["id"]) ?>"><i class="fa fa-eye m-r-5"></i> <?= lang("general_show") ?></a>
									<a class="dropdown-item" href="<?= site_url("admin/uzi/template_edit/".$uzi["id"]) ?>"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit") ?></a>
								</div>
							</div>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
