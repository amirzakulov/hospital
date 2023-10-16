<?php $this->load->view('admin/registry/header_template_view'); ?>

<div class="row">
    <div class="col-md-12">
        <table class="table table-border table-striped custom-table datatable_order_desc mb-0 compact" id="incomplete_patients">
            <thead class="thead-dark">
            <tr>
                <th class="align-text-top" width="10%"><?= lang("index_payment_id_th"); ?></th>
                <th class="align-text-top" width="10%"><?= lang("index_ID_th"); ?></th>
                <th class="align-text-top" width="22%"><?= lang("index_fname_th"); ?></th>
                <th class="align-text-top" width="8%"><?= lang("index_age_th"); ?></th>
                <th class="align-text-top" width="15%"><?= lang("index_phone_th"); ?></th>
                <th class="align-text-top" width="15%"><?= lang("index_payment"); ?></th>
                <th class="align-text-top" width="10%">Йўлланма берувчи</th>
                <th class="align-text-top" width="20%">Холати</th>
                <th class="text-right align-text-top" width="5%">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($incomplete_patients as $i_patient) {?>
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
                    <td class="font-weight-bold text-danger js_show_payment_items show_payment_items" data-id="<?= $i_patient["id"]; ?>" data-payment-id="<?= $i_patient["payment_id"]; ?>" data-url="<?= site_url("admin/registry/ajax_selected_items"); ?>" data-table-type="incompleted">
                        <span class="js_payments_<?= $i_patient["payment_id"]; ?>"><?= $i_patient["paid"] ."/".$i_patient["total"]; ?></span>
                        <div class="doc-prof doc-prof--mb0">
                        <?= date("d M Y H:i", strtotime($i_patient["payment_date"])); ?>
                        <?= ($i_patient["debt"] > 0) ? '<span class="badge badge-danger">Қарз</span>':''; ?>
                        <?= ($i_patient["discount_value"] > 0) ? '<span class="badge badge-info">Чегирма</span>':''; ?>
						<?= ($i_patient["by_card"] > 0) ? '<span class="fa fa-credit-card text-danger"></span>':''; ?>
                        </div>
                        <?php $paid_services = ""; ?>
                        <?php
                        //doctorni statusiga qarab rangini belgilaymiz
                        if($i_patient["doctor_status"] == 0) {$paid_services .= "";}
                        elseif ($i_patient["doctor_status"] == 2) {$paid_services .= "<span class='text-danger'>D</span>";}
                        else {$paid_services .= "D";}

                        //labni statusiga qarab rangini belgilaymiz
                        if($i_patient["laboratory_status"] == 0) {$paid_services .= "";}
                        elseif (in_array($i_patient["laboratory_status"], array(2,4))) {$paid_services .= " <span class='text-danger'>L</span>";}
                        else {$paid_services .= " L";}

                        //uzini statusiga qarab rangini belgilaymiz
                        if($i_patient["uzi_status"] == 0) {$paid_services .= "";}
                        elseif ($i_patient["uzi_status"] == 2) {$paid_services .= " <span class='text-danger'>U</span>";}
                        else {$paid_services .= " U";}

                        //serviceni statusiga qarab rangini belgilaymiz
                        if($i_patient["service_status"] == 0) {$paid_services .= "";}
                        elseif ($i_patient["service_status"] == 2) {$paid_services .= " <span class='text-danger'>X</span>";}
                        else {$paid_services .= " X";}
                        ?>
                        <div class="text-dark"><?= $paid_services; ?></div>
                    </td>
                    <td class="js_partner">
						<?php
							if($i_patient["partner_id"] > 0) { echo $i_patient["partner_last_name"] ." ". $i_patient["partner_first_name"]; }
							else {echo $i_patient["sender_doctor_last_name"] ." ". $i_patient["sender_doctor_first_name"];}
						?>
						</td>
                    <td>
<!--                        --><?php // $debt_off_status = $this->payments_debt_discount_model->get_debt_off_status($i_patient["payment_id"]); ?>

                        <!-- qarz yoki chegirma bulgan xolda -->
                        <div class="js_payment_debt_discount_block" data-url="<?= site_url("admin/registry/ajax_payment_debt_discount_form") ?>" data-payment-id="<?= $i_patient["payment_id"]; ?>">
                            <?= ($i_patient["debt"] > 0) ? '<a href="javascript:void(0);" class="badge badge-danger p-1 mb-1 mr-2 text-white js_show_payment_debt_discount" data-debt-discount-type="debt">Қарз</a>':''; ?>
                            <?= ($i_patient["discount_value"] > 0) ? '<a href="javascript:void(0);" class="badge badge-info p-1 mb-1 text-white js_show_payment_debt_discount" data-debt-discount-type="discount">Чегирма</a>':''; ?>
                        </div>

                        <?php if($i_patient["service_status"] == 111111111) {?>
						<button class="btn btn-primary mb-2 js_complete_service" title="Тамомлаш" id="qabul_tamom" data-payment-id="<?= $i_patient["payment_id"]; ?>" data-url="<?= site_url("admin/registry/ajax_patient_service_status"); ?>">Беркитиш</button>
                        <?php  } ?>
						<span class="badge badge-pill js_order_status <?= $i_patient["order_status"] == 0 ? "badge-info-border":"badge-success-border"; ?> p-1 mt-1">
                            <?php
							switch ($i_patient["order_status"]) {
								case 1:
									echo "Шифокор қабулида";
									break;
								case 2:
									echo "Лабораторияда";
									break;
								case 3:
									echo "УЗИда";
									break;
								default:
									echo "Навбатда";
									break;
							} ?>
                            </span>
                    </td>
                    <td class="text-right">
                        <div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item pt-3 pb-2" href="<?= site_url("admin/registry/add_items/".$i_patient["id"]); ?>"><i class="fa fa-plus-circle m-r-5 text-primary"></i> Янги тўлов</a>
                                <a class="dropdown-item pt-3 pb-2 cursor-pointer js_cancel_payment" data-href="<?= site_url("admin/registry/ajax_cancel_payment"); ?>" data-payment-id="<?= $i_patient["payment_id"]; ?>" data-patient-id="<?= $i_patient["id"]; ?>"><i class="fa fa-minus-circle m-r-5 text-danger"></i> Тўловин бекор қилиш</a>
                                <a class="dropdown-item pt-3 pb-2" href="<?= site_url("admin/registry/edit/".$i_patient["id"]); ?>"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit"); ?></a>
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
    <div class="row d-none1">
        <div class="col-md-12">
            <table class="table table-border table-striped custom-table datatable_patients mb-0 compact" id="completed_patients">
                <thead class="thead-dark">
                <tr>
                    <th class="align-text-top" width="10%"><?= lang("index_payment_id_th"); ?></th>
                    <th class="align-text-top" width="10%"><?= lang("index_ID_th"); ?></th>
                    <th class="align-text-top" width="22%"><?= lang("index_fname_th"); ?></th>
                    <th class="align-text-top" width="8%"><?= lang("index_age_th"); ?></th>
                    <th class="align-text-top" width="15%"><?= lang("index_phone_th"); ?></th>
                    <th class="align-text-top" width="15%"><?= lang("index_payment"); ?></th>
                    <th class="align-text-top" width="15%">Йўлланма берувчи</th>
                    <th class="align-text-top" width="15%">Холати</th>
                    <th class="text-right align-text-top" width="5%">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($completed_patients as $c_patient) {?>
                    <tr>
                        <td class="font-weight-bold"><?= $c_patient["payment_id"]; ?></td>
                        <td class="font-weight-bold"><?= strtoupper($c_patient["username"]); ?></td>
                        <td>
                            <a href="<?= site_url("admin/registry/profile/".$c_patient["id"]) ?>"><?= $c_patient["last_name"] ." ". $c_patient["first_name"] ." ". $c_patient["surname"];?></a>
                            <div class="doc-prof doc-prof--mb0">
                                <i class="fa fa-map-marker text-danger"></i> <?= $c_patient["region_name"] .", ". $c_patient["city_name"] .", ". $c_patient["address"]; ?>
                            </div>
                        </td>
                        <td><?= is_null($c_patient["dob"]) ? "":(date("Y") - date("Y", strtotime($c_patient["dob"])));?></td>
                        <td><?= is_null($c_patient["phone"]) ? "":phone_number_format($c_patient["phone"]);?></td>
						<td class="font-weight-bold text-danger js_show_payment_items show_payment_items" data-id="<?= $c_patient["id"]; ?>" data-payment-id="<?= $c_patient["payment_id"]; ?>" data-url="<?= site_url("admin/registry/ajax_selected_items"); ?>" data-table-type="completed">
							<span class="js_payments_<?= $c_patient["payment_id"]; ?>"><?= $c_patient["paid"] ."/".$c_patient["total"]; ?></span>
							<div class="doc-prof doc-prof--mb0">
								<?= date("d M Y H:i", strtotime($c_patient["payment_date"])); ?>
								<?= ($c_patient["debt"] > 0) ? '<span class="badge badge-danger">Қарз</span>':''; ?>
								<?= ($c_patient["discount_value"] > 0) ? '<span class="badge badge-info">Чегирма</span>':''; ?>
								<?= ($c_patient["by_card"] > 0) ? '<span class="fa fa-credit-card text-danger"></span>':''; ?>
							</div>
							<?php $paid_services = ""; ?>
							<?php
							//doctorni statusiga qarab rangini belgilaymiz
							if($c_patient["doctor_status"] == 0) {$paid_services .= "";}
							elseif ($c_patient["doctor_status"] == 2) {$paid_services .= "<span class='text-danger'>D</span>";}
							else {$paid_services .= "D";}

							//labni statusiga qarab rangini belgilaymiz
							if($c_patient["laboratory_status"] == 0) {$paid_services .= "";}
							elseif (in_array($c_patient["laboratory_status"], array(2,4))) {$paid_services .= " <span class='text-danger'>L</span>";}
							else {$paid_services .= " L";}

							//uzini statusiga qarab rangini belgilaymiz
							if($c_patient["uzi_status"] == 0) {$paid_services .= "";}
							elseif ($c_patient["uzi_status"] == 2) {$paid_services .= " <span class='text-danger'>U</span>";}
							else {$paid_services .= " U";}

							//serviceni statusiga qarab rangini belgilaymiz
							if($c_patient["service_status"] == 0) {$paid_services .= "";}
							elseif ($c_patient["service_status"] == 2) {$paid_services .= " <span class='text-danger'>X</span>";}
							else {$paid_services .= " X";}
							?>
							<div class="text-dark"><?= $paid_services; ?></div>
						</td>
                        <td><?= $c_patient["partner_last_name"] ." ". $c_patient["partner_first_name"];?></td>
                        <td>
<!--							--><?php // $debt_off_status = $this->payments_debt_discount_model->get_debt_off_status($c_patient["payment_id"]); ?>

							<!-- qarz yoki chegirma bulgan xolda -->
							<div class="js_payment_debt_discount_block" data-url="<?= site_url("admin/registry/ajax_payment_debt_discount_form") ?>" data-payment-id="<?= $c_patient["payment_id"]; ?>">
								<?= ($c_patient["debt"] > 0) ? '<a href="javascript:void(0);" class="badge badge-danger p-1 mb-1 mr-2 text-white js_show_payment_debt_discount" data-debt-discount-type="debt">Қарз</a>':''; ?>
								<?= ($c_patient["discount_value"] > 0) ? '<a href="javascript:void(0);" class="badge badge-info p-1 mb-1 text-white js_show_payment_debt_discount" data-debt-discount-type="discount">Чегирма</a>':''; ?>
							</div>

							<span class="badge badge-pill badge-danger-border p-2">Ёпилган</span>
						</td>
                        <td class="text-right">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="<?= site_url("admin/registry/edit/".$c_patient["id"]); ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php } ?>

                </tbody>
            </table>
        </div>
    </div>

<?php $this->load->view('admin/registry/footer_template_view'); ?>
