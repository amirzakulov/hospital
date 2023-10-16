<?php $this->load->view('admin/registry/header_template_view'); ?>

<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered custom-table mb-0">
            <thead class="thead-dark">
            <tr>
                <th width="2%" class="text-center">Ётоқ</th>
                <th width="80px" class="text-left">Чек №</th>
                <th width="" class="text-left">ID</th>
                <th width="20%" class="text-left">Исм</th>
                <th width="100px" class="text-left">Кундан</th>
                <th width="100px" class="text-left">Кунгача</th>
                <th width="100px" class="text-left">Туланди</th>
                <th width="100px" class="text-left">Қарз</th>
                <th width="20%" class="text-left">Масъул Шифокор</th>
                <th class="text-left">action</th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($rooms as $room) { ?>
                <tr class="bg-light">
                    <td style="border-top: 2px solid #999;border-bottom: 2px solid #999;"></td>
                    <td colspan="9" style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
                        <div class="row">
                            <div class="col-sm-2"><strong>Хона: </strong><span class="text-danger font-weight-bold"><?= $room["number"]; ?></span></div>
                            <div class="col-sm-2"><strong>Тури: </strong><span class="text-white1"><?= $room["rtype_name"]; ?></span></div>
                            <div class="col-sm-8"><strong>Шароитлари: </strong><span><?= $room["conditions"]; ?></span></div>
                        </div>
                    </td>
                </tr>
                <?php foreach ($room["beds"] as $bed) { ?>
                    <?php $today = date("Y-m-d H:i:s"); ?>
                <tr id="js_row_<?= $bed["payment_id"] ?>" class="<?= $bed["debt"] > 0 ? "bg-danger":""; ?>">
                    <td class="align-middle text-center <?= ($today >= $bed["start_date"] && $today <= $bed["end_date"]) ? "bg-danger":"bg-dark"; ?> text-white">
                        <span class="badge mr-1 "> <?= $bed["name"]; ?> </span>
                    </td>
                    <td class="align-text-top font-weight-bold"><?= $bed["payment_id"] ?></td>
                    <td class="align-text-top"><?= $bed["username"]; ?></td>
                    <td class="text-left align-text-top">
                        <div><?= !empty($bed["last_name"]) ? '<span class="fa fa-user"></span>':''; ?> <?= $bed["last_name"]." ".$bed["first_name"]; ?></div>
                        <small><?= !empty($bed["region_name"]) ? "<span class='fa fa-map-marker text-danger'></span> ".$bed["region_name"].", ".$bed["city_name"].", ".$bed["address"] : ""; ?></small>
                    </td>
                    <td class="text-left align-text-top">
                        <?= !is_null($bed["start_date"]) ? date_formating(strtotime($bed["start_date"]), "dt") :""; ?>
                    </td>
                    <td class="text-left align-text-top">
                        <?= !is_null($bed["start_date"]) ? date_formating(strtotime($bed["end_date"]), "dt"):""; ?>
                    </td>
                    <td class="text-left align-text-top"><?= $bed["paid"]; ?></td>
                    <td class="text-left align-text-top"><?= $bed["debt"] > 0 ? $bed["debt"] : ""; ?></td>
                    <td class="text-left align-text-top"><?= $bed["doctor_last_name"] ." ". $bed["doctor_first_name"] ; ?></td>
<!--                    <td>-->
<!--                        <a class="btn btn-primary --><?//= $bed["busy"] == 0 ? "":"d-none"; ?><!-- js_assign_bed" data-bed="--><?//= $bed["name"]; ?><!--" data-bed-id="--><?//= $bed["id"]; ?><!--" data-price="--><?//= $bed["price"]; ?><!--" href="javascript:void(0)" role="button"><span class="fa fa-plus"></span></a>-->
<!--                        <a class="btn btn-success --><?//= $bed["busy"] != 0 ? "":"d-none"; ?><!-- js_view_bed" data-bed="--><?//= $bed["name"]; ?><!--" data-bed-id="--><?//= $bed["id"]; ?><!--" data-price="--><?//= $bed["price"]; ?><!--" data-url="--><?//= site_url("admin/rooms/ajax_show_patient_room"); ?><!--" href="javascript:void(0)" role="button"><span class="fa fa-edit"></span></a>-->
<!--                    </td>-->
                    <td class="text-right">
                        <div class="dropdown dropdown-action">
<!--                            <button class="btn btn-success --><?//= $bed["busy"] != 0 ? "":"d-none"; ?><!-- js_show_payment_items" data-service-type="room" data-id="--><?//= $bed["patient_id"]; ?><!--" data-payment-id="--><?//= $bed["payment_id"]; ?><!--" data-url="--><?//= site_url("admin/registry/ajax_selected_items"); ?><!--"><span class="fa fa-edit"></span></button>-->
<!--                            <button class="btn btn-success --><?//= $bed["busy"] != 0 ? "":"d-none"; ?><!-- js_show_payment_items" data-bed="--><?//= $bed["name"]; ?><!--" data-bed-id="--><?//= $bed["id"]; ?><!--" data-price="--><?//= $bed["price"]; ?><!--" data-url="--><?//= site_url("admin/registry/ajax_selected_items"); ?><!--" href="javascript:void(0)" role="button"><span class="fa fa-edit"></span></button>-->
                            <a href="#" class="action-icon dropdown-toggle  <?= $bed["busy"] != 0 ? "":"d-none"; ?>" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
								<a href="javascript:void(0);" class="dropdown-item pt-3 pb-2 js_show_payment_items" data-service-type="room" data-id="<?= $bed["patient_id"]; ?>" data-payment-id="<?= $bed["payment_id"]; ?>" data-url="<?= site_url("admin/registry/ajax_selected_items"); ?>"><i class="fa fa-pencil m-r-5 text-primary"></i> <?= lang("general_edit"); ?></a>
<!--								<a class="dropdown-item" href="--><?//= site_url("admin/registry/add_items/".$bed["id"]); ?><!--"><i class="fa fa-plus-circle m-r-5"></i> Янги тўлов</a>-->
								<a class="dropdown-item pt-3 pb-2 cursor-pointer js_cancel_payment" data-href="<?= site_url("admin/registry/ajax_cancel_payment"); ?>" data-payment-id="<?= $bed["payment_id"]; ?>" data-patient-id="<?= $bed["patient_id"]; ?>"><i class="fa fa-minus-circle m-r-5 text-danger"></i> Тўловин бекор қилиш</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->load->view('admin/registry/footer_template_view'); ?>
