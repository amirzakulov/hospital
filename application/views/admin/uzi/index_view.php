<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-sm-8 col-9 text-right m-b-20">
        <a href="<?= site_url("admin/uzi/add") ?>" class="btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> <?= lang("uzi_add_button") ?></a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped custom-table mb-0 datatable11">
                <thead>
                <tr>
                    <th>#</th>
                    <th>У/Н</th>
                    <th><?= lang("uzi_name"); ?></th>
                    <th><?= lang("uzi_price"); ?></th>
                    <th class="text-right"><?= lang("general_actions") ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $counter = 1; ?>
                <?php foreach ($uzis as $uzi) {?>
                    <tr id="js_row_<?= $uzi["id"]; ?>">
                        <td><?= $counter++; ?></td>
                        <td><?= $uzi["code"]; ?></td>
                        <td><?= $uzi["name"]; ?></td>
                        <td><?= $uzi["price"]; ?></td>
                        <td class="text-right">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="<?= site_url("admin/uzi/edit/".$uzi["id"]) ?>"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit") ?></a>
                                    <a class="dropdown-item js_delele_item" href="javascript:void(0)" data-href="<?= site_url("admin/uzi/delete/") ?>" data-id="<?= $uzi["id"] ?>"><i class="fa fa-trash-o m-r-5"></i> <?= lang("general_delete") ?></a>
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
