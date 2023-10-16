<?php $this->load->view('admin/registry/header_template_view'); ?>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-border table-striped custom-table datatable_order_desc mb-0 compact">
                <thead class="thead-dark">
                <tr>
                    <th class="align-text-top" width="10%"><?= lang("index_payment_id_th"); ?></th>
                    <th class="align-text-top"><?= lang("index_ID_th"); ?><br></th>
                    <th class="align-text-top"><?= lang("index_fname_th"); ?><br></th>
                    <th class="align-text-top"><?= lang("index_age_th"); ?></th>
                    <th class="align-text-top"><?= lang("index_phone_th"); ?></th>
                    <th class="align-text-top"> Тўланди</th>
                    <th class="text-right align-text-top"> Қарз</th>
                    <th class="text-right align-text-top"> </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($debitor_patients as $c_patient) {?>
                    <tr>
                        <td class="font-weight-bold"><?= $c_patient["payment_id"]; ?></td>
                        <td class="font-weight-bold"><?= $c_patient["username"]; ?></td>
                        <td>
                            <div><?= $c_patient["last_name"] ." ". $c_patient["first_name"] ." ". $c_patient["surname"];?></div>
                            <div class="doc-prof doc-prof--mb0">
                                <i class="fa fa-map-marker text-danger"></i> <?= $c_patient["region_name"] .", ". $c_patient["city_name"] .", ". $c_patient["address"]; ?>
                            </div>
                        </td>
                        <td><?= is_null($c_patient["dob"]) ? "":date("Y", strtotime($c_patient["dob"]));?></td>
                        <td><?= is_null($c_patient["phone"]) ? "":phone_number_format($c_patient["phone"]);?></td>
                        <td class="font-weight-bold text-danger js_show_completed_payment_items show_payment_items" data-id="<?= $c_patient["id"]; ?>" data-payment-id="<?= $c_patient["payment_id"]; ?>" data-url="<?= site_url("admin/registry/ajax_selected_items"); ?>">
                            <span class="js_payments_<?= $c_patient["payment_id"]; ?>"><?= $c_patient["paid"]; ?></span>
                            <div class="doc-prof doc-prof--mb0"><?= date("d M Y H:i", strtotime($c_patient["payment_date"])); ?></div>
                        </td>

                        <td class="text-left"><span class="badge badge-danger font-18"><?= $c_patient["debt"]; ?></span></td>
                        <td class="text-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary js_show_debt" data-url="<?= site_url("admin/registry/ajax_show_debt") ?>" data-payment-id="<?= $c_patient["payment_id"]; ?>"><span class="fa fa-eye pr-1"></span> Кўриш</button>
                                <button type="button" class="btn btn-danger js_payment_debt_discount_block" data-url="<?= site_url("admin/registry/ajax_payment_debt_discount_form") ?>" data-payment-id="<?= $c_patient["payment_id"]; ?>">
                                    <span class="fa fa-random js_show_payment_debt_discount" data-debt-discount-type="debt"></span> Тақсим
                                </button>
                            </div>
                        </td>
					</tr>
                <?php } ?>

                </tbody>
            </table>
        </div>
    </div>

<?php $this->load->view('admin/registry/footer_template_view'); ?>
