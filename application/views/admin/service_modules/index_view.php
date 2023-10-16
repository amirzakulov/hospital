<div class="row">
	<div class="col-sm-4 col-3">
		<h4 class="page-title"><?= $title; ?></h4>
	</div>
	<div class="col-sm-8 col-9 text-right m-b-20">
		<a href="<?= site_url("admin/service_modules/add") ?>" class="btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> <?= lang("general_add") ?></a>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-striped table-hover custom-table mb-0 datatable">
				<thead class="bg-dark">
				<tr>
					<th>Номи</th>
					<th class="text-right"><?= lang("general_actions"); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($service_modules as $module) {?>
					<tr id="js_row_<?= $module["id"] ?>">
						<td><?= $module["name"]; ?></td>
						<td class="text-right">
							<div class="dropdown dropdown-action">
								<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="<?= site_url("admin/service_modules/edit/".$module["id"]); ?>"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit"); ?></a>
									<a href="javascript: void (0);" class="dropdown-item js_delele_item" data-href="<?= site_url("admin/service_modules/delete/"); ?>" data-id="<?= $module["id"]; ?>"><i class="fa fa-trash-o m-r-5"></i> <?= lang("general_delete"); ?></a>
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
