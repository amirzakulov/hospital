<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-sm-8 col-9 text-right m-b-20">

    </div>
</div>

<div class="row">
    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-3">
        <a href="javascript: void(0);" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> Гурух қўшиш</a>
        <div class="roles-menu">
            <ul>
                <?php foreach ($roles as $role) {?>
                <li class="<?= $role["id"] == $role_id ? "active":""; ?>">
                    <a href="<?= $role["id"] == $role_id ? "javascript:void(0);":site_url("admin/settings/roles_permissions/?role_id=".$role["id"]); ?>"><?=$role["description"] ?></a>
                    <?php if($role["id"] == $role_id && $role_id != 1): ?>
                    <span class="role-action">
                        <a href="edit-role.html">
                            <span class="action-circle large">
                                <i class="fa fa-edit"></i>
                            </span>
                        </a>
                        <a href="#" data-toggle="modal" data-target="#delete_role">
                            <span class="action-circle large delete-btn">
                                <i class="fa fa-remove"></i>
                            </span>
                        </a>
                    </span>
                    <?php endif; ?>
                </li>
                <?php } ?>

<!--                <li><a href="#">Doctor</a></li>-->
<!--                <li><a href="#">Nurse</a></li>-->
<!--                <li><a href="#">Laboratorist</a></li>-->
<!--                <li><a href="#">Pharmacist</a></li>-->
<!--                <li><a href="#">Accountant</a></li>-->
<!--                <li><a href="#">Receptionist</a></li>-->
            </ul>
        </div>
    </div>
    <div class="col-sm-8 col-md-8 col-lg-8 col-xl-9">
        <h6 class="card-title m-b-20">Module Access</h6>
        <div class="m-b-30">
            <ul class="list-group">
                <li class="list-group-item">
                    Employee
                    <div class="material-switch float-right">
                        <input id="staff_module" type="checkbox">
                        <label for="staff_module" class="badge-success"></label>
                    </div>
                </li>
                <li class="list-group-item">
                    Holidays
                    <div class="material-switch float-right">
                        <input id="holidays_module" type="checkbox">
                        <label for="holidays_module" class="badge-success"></label>
                    </div>
                </li>
                <li class="list-group-item">
                    Leave Request
                    <div class="material-switch float-right">
                        <input id="leave_module" type="checkbox">
                        <label for="leave_module" class="badge-success"></label>
                    </div>
                </li>
                <li class="list-group-item">
                    Events
                    <div class="material-switch float-right">
                        <input id="events_module" type="checkbox">
                        <label for="events_module" class="badge-success"></label>
                    </div>
                </li>
                <li class="list-group-item">
                    Chat
                    <div class="material-switch float-right">
                        <input id="chat_module" type="checkbox">
                        <label for="chat_module" class="badge-success"></label>
                    </div>
                </li>
            </ul>
        </div>
        <div class="table-responsive">
            <table class="table table-striped custom-table">
                <thead>
                <tr>
                    <th>Module Permission</th>
                    <th class="text-center">Read</th>
                    <th class="text-center">Write</th>
                    <th class="text-center">Create</th>
                    <th class="text-center">Delete</th>
                    <th class="text-center">Import</th>
                    <th class="text-center">Export</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Employee</td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                </tr>
                <tr>
                    <td>Holidays</td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                </tr>
                <tr>
                    <td>Leave Request</td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                </tr>
                <tr>
                    <td>Events</td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" checked="">
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>