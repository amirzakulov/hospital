<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title">Ходимлар</h4>
    </div>
    <div class="col-sm-5 col-6">

    </div>
    <div class="col-sm-3 col-3 text-right m-b-20">
        <a href="<?= site_url("admin/employees/add") ?>" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> <?= lang("general_add") ?></a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-border table-striped custom-table datatable mb-0 compact">
                <thead class="thead-dark">
                <tr>
                    <th style="min-width:200px;">Исм</th>
                    <th>Username</th>
                    <th>Телефон</th>
                    <th style="min-width: 110px;">Иш бошлаган сана</th>
                    <th>Мутахассислиги</th>
                    <th class="text-right">Харакатлар</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($employees as $employee) {?>
                <tr id="js_row_<?= $employee["id"] ?>">
                    <td>
                        <img width="28" height="28" src="<?= empty($employee["photo"]) ? site_url("assets/images/user.jpg") : site_url("uploads/avatar/".$employee["photo"]) ?>" class="rounded-circle" alt=""> <h2><?= $employee["last_name"] ." ". $employee["first_name"]; ?></h2>
                    </td>
                    <td><?= $employee["username"] ?></td>
                    <td><?= $employee["phone"] ?></td>
                    <td><?= date("d.m.Y", strtotime($employee["created_date"])) ?></td>
                    <td>
                        <span class="custom-badge status-green"><?= $employee["job_title"] ?></span>
                    </td>
                    <td class="text-right">
                        <div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="<?= site_url("admin/employees/edit/".$employee["id"]); ?>"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit"); ?></a>
                                <a class="dropdown-item js_delele_item" href="javascript:void(0);" data-href="<?= site_url("admin/employees/delete/") ?>" data-id="<?= $employee["id"] ?>"><i class="fa fa-trash-o m-r-5"></i> <?= lang("general_delete"); ?></a>
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
