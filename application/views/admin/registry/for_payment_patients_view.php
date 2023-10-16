<?php $this->load->view('admin/registry/header_template_view'); ?>

<div class="row">
    <div class="col-md-12">
        <table class="table table-border table-striped custom-table datatable_patients mb-0 compact">
            <thead class="thead-dark">
            <tr>
                <th class="align-text-top" width="10%"><?= lang("index_payment_id_th"); ?></th>
                <th class="align-text-top" width="10%"><?= lang("index_ID_th"); ?></th>
                <th class="align-text-top" width="22%"><?= lang("index_fname_th"); ?></th>
                <th class="align-text-top" width="8%"><?= lang("index_age_th"); ?></th>
                <th class="align-text-top" width="15%"><?= lang("index_phone_th"); ?></th>
                <th class="align-text-top" width="15%"><?= lang("index_payment"); ?></th>
                <th class="align-text-top" width="15%">Йўлланма берувчи</th>
                <th class="text-right align-text-top" width="5%">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($patients as $i_patient) {?>
                <tr id="js_row_<?= $i_patient["payment_id"] ?>">
                    <td class="font-weight-bold"><?= $i_patient["payment_id"]; ?></td>
                    <td class="font-weight-bold"><?= strtoupper($i_patient["username"]); ?></td>
                    <td>
                        <a href="<?= site_url("admin/registry/profile/".$i_patient["id"]) ?>" class=""><?= $i_patient["last_name"] ." ". $i_patient["first_name"] ." ". $i_patient["surname"];?></a>
                        <div class="doc-prof doc-prof--mb0">
                            <i class="fa fa-map-marker text-danger"></i> <?= $i_patient["region_name"] .", ". $i_patient["city_name"] .", ". $i_patient["address"]; ?>
                        </div>
                    </td>
                    <td><?= is_null($i_patient["dob"]) ? "":(date("Y") - date("Y", strtotime($i_patient["dob"])));?></td>
                    <td><?= is_null($i_patient["phone"]) ? "":phone_number_format($i_patient["phone"]);?></td>
                    <td class="font-weight-bold text-danger js_show_payment_items show_payment_items <?= ($page == "for_payment_patients" ? 'js_for_payment_patients':''); ?>" data-id="<?= $i_patient["id"]; ?>" data-payment-id="<?= $i_patient["payment_id"]; ?>" data-url="<?= site_url("admin/registry/ajax_selected_items"); ?>">
                        <span class="js_payments_<?= $i_patient["payment_id"]; ?>"><?= $i_patient["paid"] ."/".$i_patient["total"]; ?></span>
                        <div class="doc-prof doc-prof--mb0">
                        <?= date("d M Y"); ?>

                        </div>
                        <?php $paid_services = ""; ?>
                        <?php $paid_services .= (!$i_patient["doctor_status"] ? "":"D"); ?>
                        <?php $paid_services .= !$i_patient["laboratory_status"] ? "":" L"; ?>
                        <?php $paid_services .= !$i_patient["uzi_status"] ? "":" U"; ?>
                        <div class="text-dark"><?= $paid_services; ?></div>
                    </td>
                    <td><?= $i_patient["partner_last_name"] ." ". $i_patient["partner_first_name"];?></td>

                    <td class="text-right">
                        <div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item cursor-pointer js_do_payment d-none" data-href="<?= site_url("admin/registry/ajax_do_payment/"); ?>" data-payment-id="<?= $i_patient["payment_id"]; ?>" data-patient-id="<?= $i_patient["id"]; ?>"><i class="fa fa-bolt m-r-5 text-primary"></i> Тўловин амалга ошириш</a>
                                <a class="dropdown-item cursor-pointer js_cancel_payment" data-href="<?= site_url("admin/registry/ajax_cancel_payment/"); ?>" data-payment-id="<?= $i_patient["payment_id"]; ?>" data-patient-id="<?= $i_patient["id"]; ?>"><i class="fa fa-minus-circle m-r-5 text-danger"></i> Тўловин бекор қилиш</a>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php } ?>

            </tbody>
        </table>
    </div>
</div>
<hr>


<?php $this->load->view('admin/registry/footer_template_view'); ?>