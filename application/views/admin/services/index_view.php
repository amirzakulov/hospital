<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-sm-3 col-3"></div>
    <div class="col-sm-5 col-6 text-right m-b-20">
        <a class="btn btn-primary " href="<?= site_url("admin/services/add") ?>" title="<?= lang("patients_new_patient_add") ?>" role="button"><span class="fa fa-plus"></span></a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-border table-striped custom-table datatable_patients mb-0 compact">
            <thead class="thead-dark">
            <tr>
                <th class="align-text-top" width="22%"><?= lang("services_name"); ?></th>
                <th class="align-text-top" width="15%"><?= lang("services_price"); ?></th>
                <th class="align-text-top" width="15%"><?= lang("services_sort"); ?></th>
                <th class="align-text-top" width="15%"><?= lang("services_status"); ?></th>
                <th class="text-right align-text-top" width="5%"><?= lang("general_actions"); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($services as $service) {?>
                <tr id="js_row_<?= $service["id"] ?>">
                    <td class="font-weight-bold"><?= $service["name"]; ?></td>
                    <td class="font-weight-bold"><?= $service["price"]; ?></td>
                    <td class="font-weight-bold"><?= $service["sort"]; ?></td>
                    <td class="font-weight-bold"><?= $service["active"] ? '<span class="custom-badge status-green">'.lang("services_status_active").'</span>':'<span class="custom-badge status-red">'.lang("services_status_inactive").'</span>'; ?></td>
                    <td class="text-right">
                        <div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="<?= site_url("admin/services/edit/".$service["id"]); ?>"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit"); ?></a>
                                <a class="dropdown-item js_delele_item" href="javascript:void(0);" data-href="<?= site_url("admin/services/delete/") ?>" data-id="<?= $service["id"] ?>"><i class="fa fa-trash-o m-r-5"></i> <?= lang("general_delete"); ?></a>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
