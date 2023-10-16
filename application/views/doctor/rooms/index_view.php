<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-sm-8 col-9 text-right m-b-20"></div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered custom-table mb-0 datatable">
                <thead class="thead-dark">
                <tr>
                    <th width="100px" class="text-center">Синф</th>
                    <th width="10px" class="text-center">Хона</th>
                    <th width="150px" class="text-center"># Ётоқ</th>
                    <th class="text-center">Нархи</th>
                    <th class="text-center">Шароитлар</th>
                    <th width="100px" class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rooms as $room) { ?>
                    <tr>
                        <td class="text-center"><?= $room["rtype_name"]; ?></td>
                        <td class="text-center"><?= $room["number"]; ?></td>
                        <td class="text-center">
                        <?php foreach ($room["beds"] as $bed) {?>
                            <span title="Ётоқ <?= $bed["name"]; ?>" class="fa mr-1 <?= !$bed["busy"] ? "fa-circle-o":"fa-circle text-danger"; ?>"></span>
                        <?php }?>
                        </td>
                        <td class="text-center"><?= number_format($room["price"], 0, 0, " "); ?></td>
                        <td><?= $room["conditions"]; ?></td>
                        <td class="text-right">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="<?= site_url("doctor/rooms/beds/".$room["id"]) ?>"><i class="fa fa-eye m-r-5"></i> <?= lang("rooms_beds"); ?></a>
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
