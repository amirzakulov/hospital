<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-sm-8 col-9 text-right m-b-20">
        <a class="btn btn-primary" href="<?= site_url("admin/room_types/add") ?>" title="<?= lang("room_types_add") ?>" role="button"><span class="fa fa-plus"></span></a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered custom-table mb-0 datatable">
                <thead class="thead-dark">
                <tr>
                    <th class="text-center">Тартиб</th>
                    <th width="" class="text-center">Синф</th>
                    <th class="text-center">Нархи</th>
                    <th class="text-center">Шароитлар</th>
                    <th width="100px" class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($room_types as $type) { ?>
                <tr id="js_row_<?= $type["id"] ?>">
                    <td width="10px" class="text-center"><?= $type["sort"]; ?></td>
                    <td class="text-center"><?= $type["name"]; ?></td>
                    <td class="text-center"><?= $type["price"]; ?></td>
                    <td><?= $type["conditions"]; ?></td>
                    <td class="text-right">
                        <div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="<?= site_url("admin/room_types/edit/".$type["id"]) ?>"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit"); ?></a>
                                <a class="dropdown-item js_delele_item" href="javascript:void(0);" data-href="<?= site_url("admin/room_types/delete/") ?>" data-id="<?= $type["id"] ?>"><i class="fa fa-trash-o m-r-5"></i> <?= lang("general_delete"); ?></a>
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
