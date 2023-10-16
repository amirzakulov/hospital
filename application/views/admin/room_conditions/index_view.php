<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-sm-8 col-9 text-right m-b-20">
        <a class="btn btn-primary" href="<?= site_url("admin/room_conditions/add") ?>" title="<?= lang("room_condition_add") ?>" role="button"><span class="fa fa-plus"></span></a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">

            <table class="table table-bordered custom-table mb-0 datatable">
                <thead class="thead-dark">
                <tr>
                    <th>Номи</th>
                    <th width="100px" class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($conditions as $condition) {?>
                <tr id="js_row_<?= $condition["id"] ?>">
                    <td><?= $condition["title"] ?></td>
                    <td class="text-right">
                        <div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="<?= site_url("admin/room_conditions/edit/".$condition["id"]) ?>"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit"); ?></a>
                                <a class="dropdown-item js_delele_item" href="javascript:void(0);" data-href="<?= site_url("admin/room_conditions/delete/") ?>" data-id="<?= $condition["id"] ?>"><i class="fa fa-trash-o m-r-5"></i> <?= lang("general_delete"); ?></a>
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
