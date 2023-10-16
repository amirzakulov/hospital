<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title">Беморлар</h4>
    </div>
    <div class="col-sm-3 col-3"></div>
    <div class="col-sm-5 col-6 text-right m-b-20">
<!--        <button type="button" class="btn btn-danger js_show_cash_expenses" title="Чиқимлар" data-url="--><?//= site_url("admin/registry/ajax_show_expenses") ?><!--" data-url-cash="--><?//= site_url("admin/registry/ajax_get_cash_today") ?><!--"><span class="fa fa-book"></span></button>-->
<!--        <a class="btn btn-primary " href="--><?//= site_url("admin/registry/add") ?><!--" title="--><?//= lang("patients_new_patient_add") ?><!--" role="button"><span class="fa fa-plus"></span></a>-->
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card-box">
            <div class="tab-content">

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-border table-striped custom-table datatable_patients mb-0 compact">
                        <thead class="thead-dark">
                        <tr>
                            <th class="align-text-top"><?= lang("index_ID_th"); ?><br></th>
                            <th class="align-text-top"><?= lang("index_fname_th"); ?><br></th>
                            <th class="align-text-top"><?= lang("index_age_th"); ?></th>
                            <th class="align-text-top"><?= lang("index_phone_th"); ?></th>
                            <th class="align-text-top">Қайд этилган сана</th>
                            <th class="align-text-top">Сўнгги тўлов</th>
                            <th class="text-right align-text-top d-none">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($patients as $patient) {?>
                            <tr id="js_row_<?= $patient["id"] ?>">
                                <td class="font-weight-bold"><?= $patient["username"]; ?></td>
                                <td>
                                    <a href="<?= site_url("admin/patients/profile/".$patient["id"]) ?>"><?= $patient["last_name"] ." ". $patient["first_name"] ." ". $patient["surname"];?></a>
                                    <div class="doc-prof doc-prof--mb0">
                                        <i class="fa fa-map-marker text-danger"></i> <?= $patient["region_name"] .", ". $patient["city_name"] .", ". $patient["address"]; ?>
                                    </div>
                                </td>
                                <td><?= is_null($patient["dob"]) ? "":(date("Y") - date("Y", strtotime($patient["dob"])));?></td>
                                <td><?= is_null($patient["phone"]) ? "":phone_number_format($patient["phone"]);?></td>
                                <td><?= date("d.m.Y", strtotime($patient["user_created_date"]));?></td>
                                <td><?= is_null($patient["last_payment_date"]) ? "":date("d.m.Y", strtotime($patient["last_payment_date"]));?></td>
                                <td class="text-right d-none">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="<?= site_url("admin/patients/edit/".$patient["id"]."/archive"); ?>"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit"); ?></a>
                                            <a class="dropdown-item js_delele_patient" href="javascript:void(0);" data-href="<?= site_url("admin/patients/delete/") ?>" data-patient-id="<?= $patient["id"] ?>"><i class="fa fa-trash-o m-r-5"></i> <?= lang("general_delete"); ?></a>
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
        </div>
    </div>
</div>
