<div class="row">
    <div class="col-sm-4 col-3 offset-sm-2">
        <h4 class="page-title"><?= $title ?></h4>
    </div>
    <div class="col-sm-4">
        <a href="<?= site_url("doctor/templates_uzi/add_batch") ?>" class="btn btn btn-primary btn-rounded float-right ml-3 d-none"><i class="fa fa-plus"></i> <?= lang("index_add_batch_btn") ?></a>
        <a href="<?= site_url("doctor/templates_uzi/add") ?>" class="btn btn-primary btn-rounded float-right d-none"><i class="fa fa-plus"></i> <?= lang("general_add") ?></a>
        <a data-url="<?= site_url("doctor/templates_uzi/download") ?>" class="d-none btn btn btn-primary btn-rounded float-right text-white js_download_templates"><i class="fa fa-copy"></i> Андозаларни кўчириш</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <table class="table table-border custom-table mb-0">
            <thead class="thead-dark">
            <tr>
                <th width="20" class="align-text-top">#</th>
                <th class="align-text-top">Органлар</th>
                <th width="150" class="align-text-top">Тил</th>
                <th class="align-text-top" width="20"></th>
            </tr>
            </thead>
            <tbody>
			<?php $counter = 1; ?>
			<?php foreach ($templates as $template) {?>
			<tr id="js_row_<?= $template["id"]; ?>">
				<td><?= $counter++; ?></td>
				<td><?= $template["title"]; ?></td>
				<td>
					<div class="btn-group text-right" role="group">
						<button class="btn btn-outline-success p-1 <?= empty($template["template"]) ? "":"active"; ?>"><span class="fa fa-file-pdf-o"></span> Узб</button>
						<button class="btn btn-outline-info p-1  <?= empty($template["template_ru"]) ? "":"active"; ?>"><span class="fa fa-file-pdf-o"></span> Рус</button>
					</div>
				</td>
				<td class="text-right">
					<div class="dropdown dropdown-action">
						<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item" href="<?= site_url("doctor/templates_uzi/tview/".$template["id"]); ?>"><i class="fa fa-eye m-r-5"></i> <?= lang("general_show") ?></a>
							<a class="dropdown-item" href="<?= site_url("doctor/templates_uzi/edit/".$template["id"]); ?>"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit") ?></a>
						</div>
					</div>
				</td>



			</tr>
			<?php } ?>
            </tbody>
        </table>
    </div>
</div>
