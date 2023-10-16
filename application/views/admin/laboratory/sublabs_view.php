<div class="row">
    <div class="col-lg-4">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-lg-4">
        <a href="<?= site_url("admin/laboratory/sublabs_add/".$laboratory["id"]) ?>" class="btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> <?= lang("laboratory_sublab_add_button"); ?></a>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="card-box">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
					<li class="breadcrumb-item active" aria-current="page"><span class="fa fa-folder"></span> <a href="<?= site_url("admin/laboratory/#js_row_".$laboratory["id"]) ?>"><?= $category["name"] ?></a></li>
					<li class="breadcrumb-item"><?= $laboratory["name"] ?></li>
                </ol>
            </nav>

            <table class="table table-sm table-hover">
                <thead>
                <tr>
                    <th><?= lang("laboratory_name"); ?></th>
                    <th><?= lang("laboratory_norma"); ?></th>
                    <th><?= lang("laboratory_mesurment"); ?></th>
                    <th><?= lang("laboratory_default_value"); ?></th>
                    <th><?= lang("laboratory_sort"); ?></th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($sublabs as $sublab) {?>
                <tr id="js_row_<?= $sublab["id"] ?>">
                    <td><?= $sublab["name"] ?></td>
                    <td><?= $sublab["norma"] ?></td>
                    <td><?= $sublab["mesurment"] ?></td>
                    <td><?= $sublab["default_value"] ?></td>
                    <td><?= $sublab["sort"] ?></td>
                    <td class="text-right" width="50">
                        <div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="<?= site_url("admin/laboratory/sublabs_add/".$sublab["id"]) ?>"><i class="fa fa-plus m-r-5 p-2 text-info"></i> <?= lang("laboratory_sublab_add_button"); ?></a>
                                <a class="dropdown-item" href="<?= site_url("admin/laboratory/sublabs_edit/".$sublab["id"]) ?>"><i class="fa fa-pencil m-r-5 p-2"></i> <?= lang("general_edit"); ?></a>
                                <a class="dropdown-item js_delele_item" href="javascript:void(0);" data-href="<?= site_url("admin/laboratory/delete/") ?>" data-id="<?= $sublab["id"] ?>"><i class="fa fa-trash-o m-r-5 p-2 text-danger"></i> <?= lang("general_delete"); ?></a>
                            </div>
                        </div>
                    </td>
                </tr>
				<?php if(!empty($sublab["sub"])) { ?>
				<?php foreach ($sublab["sub"] as $sublab_item) { ?>
				<tr>
					<td style="padding-left: 20px;">- <?= $sublab_item["name"]; ?></td>
					<td><?= $sublab_item["norma"]; 			?></td>
					<td><?= $sublab_item["mesurment"]; 		?></td>
					<td><?= $sublab_item["default_value"]; 	?></td>
					<td><?= $sublab_item["sort"]; 			?></td>
					<td class="text-right" width="50">
						<div class="dropdown dropdown-action">
							<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
							<div class="dropdown-menu dropdown-menu-right">
								<a class="dropdown-item" href="<?= site_url("admin/laboratory/sublabs_edit/".$sublab_item["id"]) ?>"><i class="fa fa-pencil m-r-5 p-2"></i> <?= lang("general_edit"); ?></a>
								<a class="dropdown-item js_delele_item" href="javascript:void(0);" data-href="<?= site_url("admin/laboratory/delete/") ?>" data-id="<?= $sublab["id"] ?>"><i class="fa fa-trash-o m-r-5 p-2 text-danger"></i> <?= lang("general_delete"); ?></a>
							</div>
						</div>
					</td>
				</tr>
				<?php } ?>
				<?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>

		<a role="button" class="btn btn-secondary submit-btn" tabindex="5" href="<?= site_url("admin/laboratory/sublabs/".$laboratory["parent_id"]) ?>"><?= lang("general_back") ?></a>

    </div>

</div>
