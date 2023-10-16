<?php $this->load->view('doctor/patients/header_template_view'); ?>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-border table-striped custom-table datatable_order_desc mb-0 compact">
                <thead class="thead-dark">
                <tr>
                    <th class="align-text-top" width="10%"><?= lang("index_payment_id_th"); ?></th>
                    <th class="align-text-top" width="10%"><?= lang("index_ID_th"); ?><br></th>
                    <th class="align-text-top" width="25%"><?= lang("index_fname_th"); ?><br></th>
                    <th class="align-text-top" width="10%"><?= lang("index_age_th"); ?></th>
                    <th class="align-text-top" width="15%"><?= lang("index_phone_th"); ?></th>
                    <th class="align-text-top" width="15%"> <?= lang("index_payment"); ?></th>
                    <th class="align-text-top" width="15%">Холати</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($incomplete_patients as $i_patient) {?>
                    <tr>
                        <td class="font-weight-bold"><?= $i_patient["id"]; ?><br>
							<?= date("d M Y H:i", strtotime($i_patient["created_date"])); ?></td>
                        <td class="font-weight-bold"><?= strtoupper($i_patient["username"]); ?></td>
                        <td>
                            <a href="<?= site_url("doctor/patients/patient/".$i_patient["patient_doctor_id"]) ?>"><?= $i_patient["last_name"] ." ". $i_patient["first_name"] ." ". $i_patient["surname"];?></a>
                            <div class="doc-prof doc-prof--mb0">
                                <i class="fa fa-map-marker text-danger"></i> <?= $i_patient["region_name"] .", ". $i_patient["city_name"] .", ". $i_patient["address"]; ?>
                            </div>
                        </td>
                        <td><?= is_null($i_patient["dob"]) ? "":(date("Y")-date("Y", strtotime($i_patient["dob"])));?></td>
                        <td><?= is_null($i_patient["phone"]) ? "":phone_number_format($i_patient["phone"]);?></td>
                        <td class="font-weight-bold text-danger js_show_payment_items show_payment_items" data-id="<?= $i_patient["patient_doctor_id"]; ?>" data-payment-id="<?= $i_patient["id"]; ?>">
                            <span class="js_payments_<?= $i_patient["id"]; ?>"><?= $i_patient["paid"] ."/".$i_patient["total"]; ?></span>
                            <div class="doc-prof doc-prof--mb0">
                                <?= date("d M Y", strtotime($i_patient["created_date"])); ?> <?= (($i_patient["total"] - ($i_patient["paid"] + $i_patient["discount"])) > 0) ? '<span class="badge badge-danger">Қарз</span>':''; ?>
                                <?= ($i_patient["discount"] > 0) ? '<span class="badge badge-info">Чегирма</span>':''; ?>
                            </div>
                        </td>
                        <td><?php switch ($i_patient["doctor_status"]) {
                                case 1:
                                    $status_class = "badge-info-border";
                                    $status_text = "Янги";
                                    break;
                                case 3:
                                    $status_class = "badge-success-border";
                                    $status_text = "Қабулда";
                                    break;
                            } ?>
                            <span class="badge badge-pill <?= $status_class; ?> p-2"><?= $status_text ?></span>
                        </td>
                    </tr>
                <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-border table-striped custom-table datatable_order_desc mb-0 compact">
                <thead class="thead-dark">
                <tr>
                    <th class="align-text-top" width="10%"><?= lang("index_payment_id_th"); ?></th>
                    <th class="align-text-top" width="10%"><?= lang("index_ID_th"); ?><br></th>
                    <th class="align-text-top" width="25%"><?= lang("index_fname_th"); ?><br></th>
                    <th class="align-text-top" width="10%"><?= lang("index_age_th"); ?></th>
                    <th class="align-text-top" width="15%"><?= lang("index_phone_th"); ?></th>
                    <th class="align-text-top" width="15%"> <?= lang("index_payment"); ?></th>
                    <th class="align-text-top" width="15%">Холати</th>
                </tr>
                </thead>
                <tbody>
                <?php if(count($completed_patients) > 0) {?>
                    <?php foreach ($completed_patients as $c_patient) {?>
                        <tr>
                            <td class="font-weight-bold"><?= $c_patient["id"]; ?></td>
                            <td class="font-weight-bold"><?= strtoupper($c_patient["username"]); ?></td>
                            <td>
                                <a href="<?= site_url("doctor/patients/patient_info/".$c_patient["patient_doctor_id"]) ?>"><?= $c_patient["last_name"] ." ". $c_patient["first_name"] ." ". $c_patient["surname"];?></a>
                                <div class="doc-prof doc-prof--mb0">
                                    <i class="fa fa-map-marker text-danger"></i> <?= $c_patient["region_name"] .", ". $c_patient["city_name"] .", ". $c_patient["address"]; ?>
                                </div>
                            </td>
                            <td><?= is_null($c_patient["dob"]) ? "":date("Y", strtotime($c_patient["dob"]));?></td>
                            <td><?= is_null($c_patient["phone"]) ? "":phone_number_format($c_patient["phone"]);?></td>
                            <td class="font-weight-bold text-danger js_show_completed_payment_items show_payment_items" data-id="<?= $c_patient["patient_doctor_id"]; ?>" data-payment-id="<?= $c_patient["id"]; ?>">
                                <span class="js_payments_<?= $c_patient["id"]; ?>"><?= $c_patient["paid"] ."/".$c_patient["total"]; ?></span>
                                <div class="doc-prof doc-prof--mb0">
                                    <?= date("d M Y", strtotime($c_patient["created_date"])); ?> <?= (($c_patient["total"] - $c_patient["paid"]) > 0) ? '<span class="badge badge-danger">Қарз</span>':''; ?>
									<?= ($c_patient["discount"] > 0) ? '<span class="badge badge-info">Чегирма</span>':''; ?>
                                </div>
                            </td>
                            <td><span class="badge badge-pill badge-danger-border p-2">Ёпилган</span></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Тасдиқлаш -->

<?php $this->load->view('doctor/patients/footer_template_view'); ?>
