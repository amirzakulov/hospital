<div class="row">
    <div class="col-lg-4">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-lg-4">
        <a href="<?= site_url("doctor/laboratory/sublabs_add/".$laboratory["id"]) ?>" class="btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> <?= lang("laboratory_sublab_add_button"); ?></a>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="card-box">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><span class="fa fa-folder"></span> <a href="<?= site_url("doctor/laboratory") ?>"><?= $laboratory["name"] ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $category["name"] ?></li>
                </ol>
            </nav>

            <table class="table table-sm">
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
                                <a class="dropdown-item" href="<?= site_url("doctor/laboratory/sublabs_edit/".$sublab["id"]) ?>"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit"); ?></a>
                                <a class="dropdown-item js_delele_item" href="javascript:void(0);" data-href="<?= site_url("doctor/laboratory/delete/") ?>" data-id="<?= $sublab["id"] ?>"><i class="fa fa-trash-o m-r-5"></i> <?= lang("general_delete"); ?></a>
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
