<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-sm-5 col-6">

    </div>
    <div class="col-sm-3 col-3 text-right m-b-20">
        <a href="<?= site_url("admin/payment_types/add") ?>" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> <?= lang("general_add") ?></a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-border table-striped custom-table datatable mb-0 compact">
            <thead class="thead-dark">
            <tr>
                <th class="align-text-top" width="30%">Номи</th>
                <th class="text-right align-text-top" width="5%"><?= lang("general_actions"); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($payment_types as $id => $name) {?>
            <tr id="js_row_<?= $id; ?>">
                <td><?= $name; ?></td>
                <td class="text-right">
					<div class="dropdown dropdown-action">
						<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item" href="<?= site_url("admin/payment_types/edit/".$id); ?>"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit"); ?></a>
							<a class="dropdown-item js_delele_item" href="javascript:void(0);" data-href="<?= site_url("admin/payment_types/delete/") ?>" data-id="<?= $id; ?>"><i class="fa fa-trash-o m-r-5"></i> <?= lang("general_delete"); ?></a>
						</div>
					</div>
                </td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
