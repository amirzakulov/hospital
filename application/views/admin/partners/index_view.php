<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title">Хамкорлар</h4>
    </div>
    <div class="col-sm-5 col-6">

    </div>
    <div class="col-sm-3 col-3 text-right m-b-20">
        <a href="<?= site_url("admin/partners/add") ?>" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> <?= lang("general_add") ?></a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-border table-striped custom-table datatable_patients mb-0 compact">
            <thead class="thead-dark">
            <tr>
                <th class="align-text-top" width="22%"><?= lang("index_fname_th"); ?></th>
                <th class="align-text-top" width="15%"><?= lang("index_job_title_th"); ?></th>
                <th class="align-text-top" width="15%"><?= lang("index_phone_th"); ?></th>
                <th class="align-text-top" width="15%"><?= lang("index_company_th"); ?></th>
                <th class="align-text-top" width="10%">Статус</th>
                <th class="align-text-top" width="10%"><?= lang("index_agreement_th"); ?></th>
                <th class="align-text-top" width="15%"><?= lang("index_partner_department_th"); ?></th>
                <th class="text-right align-text-top" width="5%"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($partners as $partner) {?>
            <tr id="js_row_<?= $partner["id"] ?>">
                <td class="font-weight-bold"><?= $partner["last_name"] ." ". $partner["first_name"]; ?></td>
                <td><?= $partner["job_title"]; ?></td>
                <td><?= phone_number_format($partner["phone"]); ?></td>
                <td><?= $partner["company"]; ?></td>
				<td><?= $partner["active"] ? "Фаол":"Нофаол"; ?></td>
                <td><?= $partner["agreement"]; ?></td>
                <td><?= $type_options[$partner["type"]]; ?></td>
                <td class="text-right">
                    <div class="dropdown dropdown-action">
                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="<?= site_url("admin/partners/edit/".$partner["id"]); ?>"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit"); ?></a>
                            <a class="dropdown-item js_delele_item" href="javascript:void(0);" data-href="<?= site_url("admin/partners/delete/") ?>" data-id="<?= $partner["id"] ?>"><i class="fa fa-trash-o m-r-5"></i> <?= lang("general_delete"); ?></a>
                        </div>
                    </div>
                </td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
