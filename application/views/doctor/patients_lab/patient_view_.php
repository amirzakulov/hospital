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
                                <div class="staff-id"><strong>Бемор ID: </strong><?= $patient["username"] ?></div>
                                <div class="staff-id"><strong>Чек рақами: </strong><span class="text-danger border-bottom"><?= $patient["payment_id"] . " (" . date("d.m.Y", strtotime($patient["payment_date"])) . ")" ?></span></div>
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
                            <ul class="personal-info d-none">
                                <li>
                                    <span class="title">Умумий тўлов:</span>
                                    <h4><?= $patient["paid"]; ?> сум</h4>
                                </li>
                                <li>
                                    <span class="title">Қарз:</span>
                                    <span class="text"><strong class="text-danger"><?= $patient["total"] - $patient["paid"]; ?> сум</strong></span>
                                </li>
                                <li>
                                    <span class="title">Чегирма:</span>
									<span class="text">
                                        <?php if($patient["discount_type"] == 2) {?>
											<?= $patient["discount_value"] . " %" ?>
										<?php } else { ?>
											<?= $patient["discount_value"] . " сум" ?>
										<?php } ?>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-2 mb-2">
    <div class="col-sm-2">
        <a href="<?= $back_url; ?>" class="btn btn-primary"><i class="fa fa-long-arrow-left"></i> <?= lang("general_back") ?></a>
    </div>
    <div class="col-sm-10" data-url="<?= site_url("/doctor/patients_lab/ajax_patient_laboratory_status"); ?>" data-payment-id="<?= $patient["payment_id"]; ?>">
        <button type="button" class="btn btn-primary btn-lg float-right js_patient_laboratory_status <?= ($patient["laboratory_status"] == 1) ? "":"d-none"?>" id="qabulda"><span class="fa fa-play"></span> Қабулга кирди</button>
        <button type="button" class="btn btn-success btn-lg float-right js_patient_laboratory_status <?= ($patient["laboratory_status"] == 3) ? "":"d-none"?>" id="qabul_tamom"><span class="fa fa-stop"></span> Қабулни тамомлаш</button>
        <button type="button" class="btn btn-danger btn-lg float-right js_patient_laboratory_status <?= ($patient["laboratory_status"] == 4) ? "":"d-none"?>" id="natija_tayyor" data-backtourl="<?= site_url("/doctor/patients_lab"); ?>"><span class="fa fa-power-off"></span> Тамомлаш</button>
    </div>
</div>
<?php
function build_laboratory_input($patient, $laboratory, $index, $is_parent_id = null) {

	$html = '
				<div class="lab_result_input_block mb-1">
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon2">'.($laboratory["name"]).'</span>
						</div>
			
						<input type="text" name="result[]" class="form-control js_tabInput" placeholder="Норма: '.($laboratory["norma"]).'"
							   value="'.($laboratory["result"]).'" id="'.($laboratory["id"]).'" data-precategory="'.($laboratory["parent_id"]).'" data-is-parent-id="'.($is_parent_id).'" '.(in_array($patient["laboratory_status"], array(1)) ? "disabled='disabled'":"").'
							   aria-label="'.($laboratory["norma"]).'" aria-describedby="basic-addon2" '.($index == 0 ? "autofocus":"").'>
					</div>
					<small class="d-block bg-secondary text-white">Норма: <span class="">'.($laboratory["norma"]).'</span></small>
					';

					 if(!empty($laboratory["recommendation_text"])) {
						$html .= '<small class="form-text">
									<label for="recommendation_'.($laboratory["id"]).'" class=" pl-3 pt-1">Тавсия</label>
									<input style="margin-left: -55px;" class="form-check-input" type="checkbox" name="recommendation[]" value="1" id="recommendation_'.($laboratory["id"]).'" data-id="'.($laboratory["id"]).'">
									<div>'.($laboratory["recommendation_text"]).'</div>
								</small>';
					}
			$html .= '<span style="font-size: 10px;" class="text-danger js_sub_laboratory_error js_sub_laboratory_error_'.($laboratory["id"]).'"></span>
				</div>
				
			';

		 return $html;
}
?>
<div class="profile-tabs">
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="nav-item"><a class="nav-link active" href="#laboratory" data-toggle="tab">Лаборатория</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane show active" id="laboratory">
            <div class="row">
                <div class="col-md-10">
                    <?= form_open_multipart("", array("name" => "lab_results"), array("payment_id" => $patient["payment_id"])) ?>
                    <div class="card-box" style="min-width: 15rem;">
                        <h4 class="card-title float-left">Лаборатория</h4>
                        <div class="btn-group float-right icon-btn-group" role="group" aria-label="Basic example">
							<button type="button" class="btn btn-primary btn_pre_print js_pre_print" data-url="<?= $print_preview_url; ?>" data-payment-id="<?= $patient["payment_id"]; ?>"><span class="fa fa-file-text"></span> Кўриш</button>
						</div>
						<div class="float-right mr-5">
							Пастга
							<kbd class="font-16"><span class="fa fa-long-arrow-down"></span></kbd>
							ёки
							<kbd class="font-16 mr-5">Enter</kbd>
							Юқорига
							<kbd class="font-16"><span class="fa fa-long-arrow-up"></span></kbd>
						</div>
						<div class="clearfix"></div>
                        <div class="js_diagnosis_result" style="min-height: 10rem;">
                            <div class="row">
                                <div class="col-4">
                                    <div class="list-group" id="list-tab" role="tablist">
                                        <?php $counter = 0; ?>
                                        <?php $first_active = null; ?>
                                        <?php foreach ($active_labs_tree["laboratories"] as $category) {?>
                                        <li class="list-group-item bg-lab-category font-weight-bold lab_result_category text"><span class="fa fa-folder text-white"></span> <?= $category["name"]; ?></li>
                                        <?php
                                            foreach ($category["sub"] as $pactive_lab) {
                                            	if($counter == 0) {$first_active = $pactive_lab["lab_id"];};
                                            	$lab_result = true;
												$lab_count = count($active_labs_tree["sub_laboratories"][$pactive_lab["lab_id"]]["sub"]);
                                                foreach ($active_labs_tree["sub_laboratories"][$pactive_lab["lab_id"]]["sub"] as $aa => $sub_laboratory) {
													if($lab_count == 1) {
														if(empty($sub_laboratory["result"]) && $sub_laboratory["is_parent"] == 1) {$lab_result = false;}
													} else {
														if(empty($sub_laboratory["result"]) && $sub_laboratory["is_parent"] == 0) {$lab_result = false;}
													}
                                                } ?>
                                                <a class="list-group-item list-group-item-action lab_result_finish <?= $counter == 0 ? "active":""; ?>" id="list-<?= $pactive_lab["lab_id"]; ?>-list" data-toggle="list" href="#list-content-<?= $pactive_lab["lab_id"]; ?>" role="tab" aria-controls="">
													<span class="fa <?= $lab_result ? "fa-check-square":"fa-circle-o";?>"></span>
													<?= $pactive_lab["name"]; ?>
												</a>
                                                <?php $counter++; ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="tab-content pt-0 js_tab_content" id="nav-tabContent" data-url="<?= site_url("doctor/patients_lab/ajax_lab_result_save") ?>">
                                        <?php $counter = 0; ?>
                                        <?php foreach ($laboratories_level1 as $key => $laboratory) {?>
											<?php $active_class = $laboratory["lab_id"] == $first_active ? "active":""; ?>
											<?php $sub_laboratories = $this->patient_laboratories_model->sub_laboratories_by_payment($patient["payment_id"], $laboratory["lab_id"]); ?>
											<div class="tab-pane fade show <?= $active_class ?>" id="list-content-<?= $laboratory["lab_id"]; ?>" role="tabpanel" aria-labelledby="list-<?= $laboratory["lab_id"]; ?>-list">
											<?php if(count($sub_laboratories) == 0) { ?>
												<?= build_laboratory_input($patient, $laboratory, $key); ?>
											<?php } elseif (count($sub_laboratories) > 0) { ?>
												<?php foreach ($sub_laboratories as $subindex => $sub_laboratory) {
													$sub_sub_laboratories = $this->patient_laboratories_model->sub_laboratories_by_payment($patient["payment_id"], $sub_laboratory["lab_id"]);
													if(count($sub_sub_laboratories) == 0) {
														echo build_laboratory_input($patient, $sub_laboratory, $subindex);
													} else {
														echo '<div class="font-weight-bold text-dark">'.($sub_laboratory["name"]).'</div>';
														foreach ($sub_sub_laboratories as $sub_sub_index => $sub_sub_laboratory) {
															$is_parent_id = $sub_laboratory["parent_id"];
															echo build_laboratory_input($patient, $sub_sub_laboratory, $sub_sub_index, $is_parent_id);
														}
													}
												} ?>
											<?php } ?>
											<div class="custom-file">
												<input type="file" class="custom-file-input js_files_to_upload" id="<?= $laboratory["id"]; ?>" name="lab_shots[]" multiple="multiple" <?= in_array($patient["laboratory_status"], array(1)) ? "disabled='disabled'":""; ?>>
												<label class="custom-file-label js_files_to_upload_label" for="lab_shots"><?= !empty($laboratory["images"]) ? (substr_count($laboratory["images"], ";")+1)." та файл танланган": "Файлларни танланг..."; ?></label>
											</div>
											</div>
                                        <?php } ?>

                                        <button class="btn btn-primary js_save_all_lab_results float-right mt-5" <?= in_array($patient["laboratory_status"], array(1)) ? "disabled='disabled'":""; ?>>Барчасини сақлаш</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div>
                <div class="col-md-4 d-none">
                    <div class="card-box" style="min-height: 15rem;">
                        <h4 class="card-title">Тўловлар</h4>
                        <div class="experience-box">
                            <ul class="experience-list">
                                <?php foreach ($payments as $payment) {?>
                                    <li>
                                        <div class="experience-user"><div class="before-circle"></div></div>
                                        <div class="experience-content">
                                            <div class="timeline-content">
                                                <div><?= date("d.m.Y", strtotime($payment["created_date"])) ?></div>
                                                <a href="javascript:void(0);" class="name">Чек рақами: <abbr><?= $payment["id"]; ?></abbr></a>
                                                <div>Умумий тўлов: <?= $payment["total"] ?></div>
                                            </div>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pre_print" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Чоп этишдан олдинги кўриниш</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
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
