<?php $this->load->view('admin/registry/header_template_view'); ?>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-border table-striped custom-table datatable_order_desc mb-0 compact">
                <thead class="thead-dark">
                <tr>
                    <th width="50px" class="align-text-top">Чек №<br></th>
					<th width="100px" class="align-text-top">Сана</th>
					<th class="align-text-top">Код<br></th>
					<th width="250px" class="align-text-top"><?= lang("index_fname_th"); ?><br></th>
					<th class="align-text-top"><?= lang("index_age_th"); ?></th>
					<th width="150px" class="align-text-top"><?= lang("index_phone_th"); ?></th>
                    <th width="100px" class="align-text-top">Тўлов</th>
                    <th width="100px" class="align-text-top">Чегирма</th>
                    <th width="100px" class="align-text-top">Нақд</th>
                    <th width="100px" class="align-text-top">Пластик</th>
                    <th width="100px" class="align-text-top">Терминал</th>
                    <th width="100px" class="align-text-top">Қарз</th>
                    <th width="200px" class="align-text-top">Йулланма берувчи</th>
                    <th width="200px" class="align-text-top"></th>
<!--                    <th class="text-right align-text-top">Action</th>-->
                </tr>
                </thead>
                <tbody>
                <?php foreach ($payments as $payment) {?>
					<tr id="js_row_<?= $payment["id"] ?>">
						<td class="font-weight-bold"><?= $payment["id"]; ?></td>
						<td><small><?= date("d.m.Y H:i:s", strtotime($payment["payment_date"]));?></small></td>
						<td><?= $payment["username"];?></td>
						<td>
                            <a href="<?= site_url("admin/registry/profile/".$payment["patient_id"]."/history") ?>"><?= $payment["last_name"] ." ". $payment["first_name"];?></a>
                            <div class="doc-prof doc-prof--mb0">
                                <i class="fa fa-map-marker text-danger"></i> <?= $payment["region_name"] .", ". $payment["city_name"] .", ". $payment["address"]; ?>
                            </div>
                        </td>
						<td><?= is_null($payment["dob"]) ? "":date("Y");?></td>
						<td><?= is_null($payment["phone"]) ? "":phone_number_format($payment["phone"]);?></td>
                        <td><?= $payment["paid"];?></td>

						<td><?= $payment["discount"];?></td>
						<td><?= $payment["by_cash"];?></td>
						<td><?= $payment["by_card"];?></td>
						<td><?= $payment["by_bank"];?></td>
						<td><?= $payment["debt"];?></td>
						<td><?= $payment["partner_id"];?></td>


                        <td><a href="<?= site_url("admin/registry/add_items/".$payment["patient_id"]) ?>" class="">Янги тўлов</a></td>
<!--                        <td class="text-right">-->
<!--                            <div class="dropdown dropdown-action">-->
<!--                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>-->
<!--                                <div class="dropdown-menu dropdown-menu-right">-->
<!--                                    <a class="dropdown-item" href="--><?//= site_url("admin/registry/edit/".$payment["id"]."/archive"); ?><!--"><i class="fa fa-pencil m-r-5"></i> --><?//= lang("general_edit"); ?><!--</a>-->
<!--                                    <a class="dropdown-item js_delele_patient" href="javascript:void(0);" data-href="--><?//= site_url("admin/registry/delete/") ?><!--" data-patient-id="--><?//= $payment["id"] ?><!--"><i class="fa fa-trash-o m-r-5"></i> --><?//= lang("general_delete"); ?><!--</a>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </td>-->
                    </tr>
                <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
<?php $this->load->view('admin/registry/footer_template_view'); ?>
