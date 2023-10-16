<div class="card-box profile-header">
    <div class="row">
        <div class="col-md-12">
            <div class="profile-view">
                <div class="profile-basic1">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="profile-info-left">
                                <h3 class="user-name m-t-0 mb-0"><?= $patient["last_name"] ." ". $patient["first_name"] ." ". $patient["surname"]; ?></h3>
                                <div class="staff-id"><strong>Касби: </strong><?= $patient["occupation"] ?></div>
                                <div class="staff-id"><strong>Ходим ID: </strong><?= $patient["username"] ?></div>
                                <div class="staff-id"><strong>Чек рақами: </strong><span class="text-danger border-bottom"><?= $patient["payment_id"] . " (" . date("d.m.Y", strtotime($patient["payment_date"])) . ")" ?></span></div>

<!--                                <div class="staff-msg"><a href="javascript:void(0)" class="btn btn-primary">Хабар юбориш</a></div>-->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <ul class="personal-info">
                                <li>
                                    <span class="title">Телефон:</span>
                                    <span class="text"><?= empty($patient["phone"]) ? "&nbsp;":phone_number_format($patient["phone"]); ?></span>
                                </li>
                                <li>
                                    <span class="title">Туғилган сана:</span>
                                    <span class="text"><?= empty($patient["dob"]) ? "&nbsp;":date("Y", strtotime($patient["dob"])); ?></span>
                                </li>
                                <li>
                                    <span class="title">Манзил:</span>
                                    <span class="text"><?= $patient["region_name"].", ".$patient["city_name"].", ".$patient["address"]; ?></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-2 mb-2">
    <div class="col-sm-2">
        <a href="<?= site_url("doctor/patients_uzi") ?>" class="btn btn-primary"><i class="fa fa-long-arrow-left"></i> <?= lang("general_back") ?></a>
    </div>

    <div class="col-sm-10" data-url="<?= site_url("/doctor/patients_uzi/ajax_patient_uzi_status"); ?>" data-payment-id="<?= $patient["payment_id"]; ?>">
        <button type="button" class="btn btn-primary btn-lg float-right js_patient_doctor_status <?= ($patient["uzi_status"] == 1) ? "":"d-none"?>" id="qabulda"><span class="fa fa-play"></span> Қабулга кирди</button>
        <button type="button" class="btn btn-danger btn-lg float-right js_patient_doctor_status <?= ($patient["uzi_status"] == 3) ? "":"d-none"?>" id="qabul_tamom" data-patient-doctor-id="<?= $patient["id"]; ?>" data-backtourl="<?= site_url("/doctor/patients_uzi"); ?>"><span class="fa fa-power-off"></span> Қабулни тамомлаш</button>
    </div>
</div>

<div class="profile-tabs" id="patient_uzi_page" data-payment-id="<?= $patient["payment_id"] ?>" data-url="<?= site_url("doctor/patients_uzi/get_patient_uzis") ?>">
	<div class="row mb-2">
		<div class="col-md-4"></div>
		<div class="col-md-8 text-left js_uzi_buttons_block">Тилни танлаш:
			<div class="btn-group" role="group" data-payment-id="<?= $patient["payment_id"] ?>" data-url="<?= site_url("doctor/patients_uzi/ajax_load_template_lang") ?>">
				<button type="button" class="btn btn-outline-info js_template_uzi_lang template_uzi_lang <?= $patient_active_uzi_conclusion["lang"] == 1 ? "active":""; ?>" data-lang="uz">Ўзб</button>
				<button type="button" class="btn btn-outline-info js_template_uzi_lang template_uzi_lang <?= $patient_active_uzi_conclusion["lang"] == 2 ? "active":""; ?>" data-lang="ru">Рус</button>
			</div>

			<div class="btn-group ml-3" role="group">
				<button type="button" class="btn btn-outline-info js_uzi_pre_print" data-payment-id="<?= $patient["payment_id"] ?>" data-url="<?= site_url("doctor/patients_uzi/ajax_print_preview2") ?>"><span class="fa fa-file-text"></span> Кўриш</button>
				<a target="_blank" href="<?= site_url("doctor/patients_uzi/download_pdf/".$patient["payment_id"])."/".$lang."/"; ?>" class="btn btn-outline-success js_generate_pdf_btn"><span class="fa fa-file-pdf-o"></span> PDF</a>
			</div>
<!--			<div class="btn-group ml-3" role="group">-->
<!--				-->
<!--			</div>-->
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="card-box" style="min-height: 15rem;">
				<div class="list-group" id="list-tab" role="tablist">
					<?php foreach ($patient_active_uzis as $k => $patient_uzi) {?>
					<a class="list-group-item list-group-item-action <?= !$k ? "active":""; ?>" id="list-<?= $patient_uzi["uzi_id"]; ?>-list" data-toggle="list" href="#list-<?= $patient_uzi["uzi_id"]; ?>" role="tab" aria-controls="uzi_<?= $patient_uzi["uzi_id"]; ?>"><?= $patient_uzi["name"]; ?></a>
					<?php } ?>
					<a class="list-group-item list-group-item-action" id="list-conclusion-list" data-toggle="list" href="#list-conclusion" role="tab">Хулоса</a>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<?= form_open(site_url("doctor/patients_uzi/ajax_save_uzi_result"), array("class"=>"needs-validation ".$was_validated, "novalidate"=>""), array("lang"=>'')); ?>
			<div class="card-box pt-0" style="min-height: 15rem;">
				<div class="tab-content pt-0" id="nav-tabContent">
					<?php foreach ($patient_active_uzis as $k => $patient_uzi) {?>
					<div class="tab-pane fade show <?= !$k ? "active":""; ?>" id="list-<?= $patient_uzi["uzi_id"]; ?>" role="tabpanel" aria-labelledby="list-<?= $patient_uzi["uzi_id"]; ?>-list">
						<div class="col-sm-12">
							<div class="form-group">
								<?= lang('edit_template_label', 'template');?>
								<?= form_textarea(
									[
										'id'  => $patient_uzi["id"],
										'name'  => 'result[]',
										'type'  => 'text',
										'value' => $this->form_validation->set_value('result', $patient_uzi["result"]),
										'rows'	=> 20,
										"class" => "form-control uzi_result_".$patient_uzi["uzi_id"],
										"required" => "",
										"data-uzi-id" => $patient_uzi["uzi_id"],
									]
								);?>
								<div class="invalid-feedback"><?php echo form_error('template'); ?></div>
							</div>
						</div>
					</div>
					<?php } ?>

					<div class="tab-pane fade show pt-4" id="list-conclusion" role="tabpanel" aria-labelledby="list-conclusion-list">
						<div class="col-sm-12">
							<div class="form-group">
								<?= form_textarea(
										[
											'id'  => $patient_active_uzi_conclusion["id"],
											'name'  => 'uzi_conclusion',
											'type'  => 'text',
											'value' => $this->form_validation->set_value('result', $patient_active_uzi_conclusion["result"]),
											'rows'	=> 20,
											"class" => "form-control uzi_conclusion",
											"required" => ""
										]
								);?>
								<div class="invalid-feedback"><?php echo form_error('template'); ?></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12 mt-3 text-right">
				<a role="button" class="btn btn-secondary" href="<?= site_url("doctor/patients_uzi"); ?>"><?= lang("user_cancel_button") ?></a>
				<button type="button" class="btn btn-primary js_save_uzi_result" <?= ($patient["uzi_status"] == 1) ? "disabled":""?>>Сақлаш</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>

</div>

<div class="modal fade" id="pre_print_uzi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Чоп этишдан олдинги кўриниш</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-dark" id="printArea"></div>
            <div class="modal-footer">
<!--                <button type="button" class="btn btn-primary printBtn">Чоп этиш</button>-->
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Ойнани ёпиш</button>
            </div>
        </div>
    </div>
</div>

<div id="notifier" class="modal fade" data-backdrop="static" aria-modal="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-info p-2"><h4 class="text-white"><span class="fa fa-info-circle"></span> Эслатма</h4></div>
			<div class="modal-body p-5 h4 text-center"></div>
		</div>
	</div>
</div>
