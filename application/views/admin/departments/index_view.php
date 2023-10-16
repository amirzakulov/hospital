<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-sm-8 col-9 text-right m-b-20">
        <a class="btn btn-primary" href="<?= site_url("admin/departments/add") ?>" title="<?= lang("departments_add") ?>" role="button"><span class="fa fa-plus"></span> <?= lang("departments_add") ?></a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table custom-table mb-0 datatable">
                <thead>
                <tr class="bg-dark text-white">
                    <th width="200px"><?= lang("departments_name"); ?></th>
                    <th width="200px"><?= lang("departments_status"); ?></th>
                    <th class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($departments as $counter => $department) {?>
                <tr class="bg-light">
                    <td><?= $department["name"]; ?></td>
                    <td><span class="custom-badge <?= !$department["status"] ? "status-red":"status-green"; ?>"><?= !$department["status"] ? lang("departments_status_inactive"):lang("departments_status_active"); ?></span></td>
                    <td class="text-right">
                        <div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="<?= site_url("admin/departments/edit/".$department["id"]) ?>"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit"); ?></a>
                                <a class="dropdown-item js_delele_item" href="javascript:void(0);" data-href="<?= site_url("admin/departments/delete/") ?>" data-id="<?= $department["id"] ?>"><i class="fa fa-trash-o m-r-5"></i> <?= lang("general_delete"); ?></a>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php if(count($department["sub"]) > 0){ ?>
                <?php foreach ($department["sub"] as $sub) {?>
                <tr class="">
                    <td class="pl-4"><?= $sub["name"]; ?></td>
                    <td><span class="custom-badge <?= !$sub["status"] ? "status-red":"status-green"; ?>"><?= !$sub["status"] ? lang("departments_status_inactive"):lang("departments_status_active"); ?></span></td>
                    <td class="text-right">
                        <div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="<?= site_url("admin/departments/edit/".$sub["id"]) ?>"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit"); ?></a>
                                <a class="dropdown-item js_delele_item" href="javascript:void(0);" data-href="<?= site_url("admin/departments/delete/") ?>" data-id="<?= $sub["id"] ?>"><i class="fa fa-trash-o m-r-5"></i> <?= lang("general_delete"); ?></a>
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
    </div>
</div>
