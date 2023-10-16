<?php if(isset($page)) :?>
	<!-- Completed -->
	<div id="patient_completed" class="modal fade" data-backdrop="static" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered1 modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <table class="table border-0">
                        <tbody>
                        <tr>
                            <td width="10%"><strong>Тўланди: </strong><br><strong class="js_completed_paid"></strong></td>
                            <td width="10%"><strong>Қарз: </strong><br><strong class="text-danger js_completed_debt"></strong></td>
                            <td width="10%"><strong>Чегирма: </strong><br><strong class="text-danger js_completed_discount"></strong></td>
                            <td width="10%"><strong>Жами: </strong><br><strong class="js_completed_total"></strong></td>
                            <td width="20%"><strong>ФИШ: </strong><br><span class="js_completed_patient_name"></span></td>
                            <td width="20%"><strong>Туғилган сана: </strong><br><span class="js_completed_patient_dob"></span></td>
                            <td width="20%"><strong>Манзил: </strong><br><i class="fa fa-map-marker text-danger"></i> <span class="js_completed_patient_address"></span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4 js_completed_doctors d-none"></div>
                        <div class="col-lg-4 js_completed_labs d-none"></div>
                        <div class="col-lg-4 js_completed_uzis d-none"></div>
                        <div class="col-lg-4 js_completed_services d-none"></div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-secondary submit-btn" data-dismiss="modal">Ойнани ёпиш</button>
                </div>
            </div>
        </div>
    </div>

    <?php if($page == 'index' || $page == 'debitor') { ?>
        <div id="payment_debt_discount" class="modal fade" data-backdrop="static" aria-modal="true">
            <div class="modal-dialog modal-dialog-centered1 modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-body pt-0 pb-0"></div>
                    <div class="modal-footer text-center">
                        <button class="btn btn-primary submit-btn js_payment_debt_discount_save">Сақлаш</button>
                        <button class="btn btn-secondary submit-btn" data-dismiss="modal">Ойнани ёпиш</button>
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>

    <?php if($page != 'debitor' && $page != 'rooms') :?>
    <!-- InCompleted -->
    <div id="patient_selected_items" class="modal fade" data-backdrop="static" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered1 modal-xl modal-dialog-scrollable" role="document">
            <?= form_open("", array("class"=>"needs-validation w-100", "novalidate"=>""), array("payment_id" => "", "patient_id" => "")); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <table class="table border-0">
                        <tbody>
                        <tr>
                            <td width="10%"><strong>Тўланди: </strong><br><strong class="js_completed_paid"></strong></td>
                            <td width="10%"><strong>Қарз: </strong><br><strong class="text-danger js_completed_debt"></strong></td>
                            <td width="10%"><strong>Чегирма: </strong><br><strong class="text-danger js_completed_discount"></strong></td>
                            <td width="10%"><strong>Жами: </strong><br><strong class="js_completed_total"></strong></td>
                            <td width="20%"><strong>ФИШ: </strong><br><span class="js_completed_patient_name"></span></td>
                            <td width="20%"><strong>Туғилган сана: </strong><br><span class="js_completed_patient_dob"></span></td>
                            <td width="20%"><strong>Манзил: </strong><br><i class="fa fa-map-marker text-danger"></i> <span class="js_completed_patient_address"></span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-body">
                    <?= $services_template; ?>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-primary submit-btn <?= $page == 'for_payment_patients' ? "js_do_payment":"js_save_print"; ?>" data-print-cheque="print" data-url="<?= $page == 'for_payment_patients' ? site_url("admin/registry/ajax_do_payment"):site_url("admin/registry/ajax_update_selected_items"); ?>">Сақлаш ва Чек чиқариш</button>
					<button class="btn btn-primary submit-btn <?= $page == 'for_payment_patients' ? "js_do_payment":"js_save_print"; ?>" data-print-cheque="notPrint" data-url="<?= $page == 'for_payment_patients' ? site_url("admin/registry/ajax_do_payment"):site_url("admin/registry/ajax_update_selected_items"); ?>">Сақлаш</button>
					<button class="btn btn-secondary submit-btn" data-dismiss="modal">Ойнани ёпиш</button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>

    <!--  Index va credit pagelarni auto update uchun  -->
    <div id="registry_urls" data-update-payments-url="<?= site_url("admin/registry/ajax_update_payments"); ?>" data-page="<?= $page; ?>"></div>
    <?php endif; ?>

    <?php if($page == 'debitor') :?>
        <div id="debt_details" class="modal fade" data-backdrop="static" aria-modal="true">
            <div class="modal-dialog modal-dialog-centered1 modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0"></div>
                    <div class="modal-footer text-center">
                        <button class="btn btn-secondary" data-dismiss="modal">Бекор қлилш</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if($page == 'rooms') :?>
        <div id="patient_selected_room" class="modal fade" data-backdrop="static" aria-modal="true">
            <div class="modal-dialog modal-dialog-centered1 modal-xl modal-dialog-scrollable" role="document">
                <?= form_open("", array("class"=>"needs-validation w-100", "novalidate"=>""), array("payment_id" => "", "patient_id" => "")); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <table class="table border-0">
                            <tbody>
                            <tr>
                                <td width="10%"><strong>Тўланди: </strong><br><strong class="js_completed_paid"></strong></td>
                                <td width="10%"><strong>Қарз: </strong><br><strong class="text-danger js_completed_debt"></strong></td>
                                <td width="10%"><strong>Чегирма: </strong><br><strong class="text-danger js_completed_discount"></strong></td>
                                <td width="10%"><strong>Жами: </strong><br><strong class="js_completed_total"></strong></td>
                                <td width="20%"><strong>ФИШ: </strong><br><span class="js_completed_patient_name"></span></td>
                                <td width="20%"><strong>Туғилган сана: </strong><br><span class="js_completed_patient_dob"></span></td>
                                <td width="20%"><strong>Манзил: </strong><br><i class="fa fa-map-marker text-danger"></i> <span class="js_completed_patient_address"></span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-body">
                        <div class="row js_items_content">
                            <div class="col-lg-5">
                                <div class="card-box" style="min-height: 192px;">
                                    <div class="row">
                                        <div class="col-sm-12 js_selected_items_menu">
                                            <div class="yigindi_block">
                                                <div class="font-weight-bold mt-1 row">
                                                    <div class="col-sm-4">Йулланма: </div>
                                                    <div class="col-sm-8">
                                                        <div class="form-group">
                                                            <?= form_dropdown($partner_id, $partners_options); ?>
                                                        </div>
                                                    </div>
                                                </div>
												<div class="font-weight-bold mt-1 row">
													<div class="col-sm-4">Шифокор йўлланма: </div>
													<div class="col-sm-8">
														<div class="form-group">
															<?= form_dropdown($sender_doctor_id, $doctors_options); ?>
														</div>
													</div>
												</div>
                                                <div class="js_selected_items_date_range font-weight-bold row">
                                                    <div class="col-sm-4">Сана: </div>
                                                    <div class="col-sm-8">
                                                        <div class="row">
                                                            <div class="col-sm-6 pr-0">
                                                                <label for="by_cash">Бошланғич</label>
                                                                <?php echo form_input($start_date);?>
                                                            </div>
                                                            <div class="col-sm-6 pl-0">
                                                                <label for="by_cash">Якуний</label>
                                                                <?php echo form_input($end_date);?>
                                                            </div>
                                                        </div>
                                                        <small class="w-100 text-danger text-center d-block"></small>
                                                    </div>
                                                </div>
                                                <div class="mt-2 js_selected_items_sum_total font-weight-bold row">
                                                    <div class="col-sm-4">Жами: </div>
                                                    <h3 class="col-sm-8 ">
                                                        <?php echo form_input($total_sum);?>
                                                    </h3>
                                                </div>
                                                <div class="js_selected_items_sum_tulandi font-weight-bold row">
                                                    <div class="col-sm-4">Тўланди: </div>
                                                    <div class="col-sm-8">
                                                        <?php echo form_input($paid);?>
                                                        <small class="text-danger"></small>
                                                    </div>
                                                </div>
                                                <div class="mt-1 mb-2 js_selected_items_sum_qarzingiz font-weight-bold row">
                                                    <div class="col-sm-4">Қарзингиз: </div>
                                                    <div class="col-sm-8"><?php echo form_input($debt);?></div>
                                                </div>
                                                <div class="js_selected_items_discount font-weight-bold mt-1 mb-2 row">
                                                    <div class="col-sm-4">Чегирма: </div>
                                                    <div class="col-sm-8">
                                                        <div class="form-group">
                                                            <?= form_dropdown($discount_type, $discount_options); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-1 mb-1 js_discount font-weight-bold row <?= (!is_null($this->input->post("discount_type"))) ? "":"d-none" ?>">
                                                    <div class="col-sm-4"> </div>
                                                    <div class="col-sm-8"><?= form_input($discount_value) ?></div>
                                                </div>

                                                <div class="mt-1 mb-3 js_selected_items_payment_type font-weight-bold row">
                                                    <div class="col-sm-4">Тўлов тури</div>
                                                    <div class="col-sm-8">
                                                        <div class="row">
                                                            <div class="col-sm-4 pr-0">
                                                                <label for="by_cash">Нақд</label>
                                                                <?= form_input($by_cash) ?></div>
                                                            <div class="col-sm-4 p-0">
                                                                <label for="by_card">Пластик</label>
                                                                <?= form_input($by_card) ?>
                                                            </div>
                                                            <div class="col-sm-4 pl-0">
                                                                <label for="by_bank">Терминал</label>
                                                                <?= form_input($by_bank) ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="js_selected_rooms">
                                                <ul class="list-group list-group-flush custom-list-group js_selected_items--tulovga">
                                                    <li class="list-group-item active bg-light text-dark font-weight-bold js_selected_items_title">Хона</li>
                                                    <li class="list-group-item font-weight-bold js_total_sum--tulovga"></li>
                                                </ul>
                                            </div>

                                            <br>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card-box" style="min-height: 192px;">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <nav>
                                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                    <a class="nav-item nav-link active" id="nav-room-tab" data-toggle="tab" href="#nav-room" role="tab" aria-controls="nav-room" aria-selected="true">Хоналар</a>
                                                </div>
                                            </nav>
                                            <div class="tab-content" id="nav-tabContent" style="max-height: 500px;">
                                                <div class="tab-pane fade show active js_form js_form_rooms" id="nav-room" role="tabpanel" aria-labelledby="nav-room-tab">
                                                    <input id="bed_search" class="form-control search-items" name="search" placeholder="Қидириш..." type="text" data-list=".js_bed_search">
                                                    <div class="js_bed_search overflow-auto" style="max-height: 500px;">
                                                        <?php foreach ($rooms as $room) {?>
															<div class="item_selection_lab_caption p-1 pl-3 bg-success text-white font-18"><?= $room["number"] ." ". $room["rtype_name"]; ?></div>
                                                            <?php foreach ($room["beds"] as $bed) {?>
																<?php if((time() < strtotime($bed["end_date"])) && ($bed["id"])) {
																	$disabled = "disabled";
																	$bg_class = "bg-danger";
																	$text_color = "text-white";
																} else {
																	$disabled = "";
																	$bg_class = "bg-light";
																	$text_color = "text-dark";
																} ?>
																<div class="checkbox mb-1 border <?= $bg_class; ?> <?= $text_color; ?>">
																	<label class="d-block w-100 border p-2 cursor-pointer">
																		<input type="radio" style="visibility: hidden;" <?= $disabled; ?> id="js_room_item_<?= $bed["id"]; ?>" class="js_select_item" name="bed_id[]" value="<?= $bed["id"]; ?>" <?= set_checkbox('bed_id', $bed["id"]); ?> data-price="<?= $bed["price"]; ?>" data-title="<?= "Хона: ".  $room["number"] ." ". $room["rtype_name"] ." / Ётоқ: ". $bed["name"]; ?>">
																		<span><?= $bed["name"] ." - ".$bed["price"]; ?></span>
																		<input  type="hidden" name="bed_count[<?= $bed["id"]; ?>]" class="js_item_count_value_input" value="1" id="js_item_count_<?= $bed["id"]; ?>">
																	</label>
																</div>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-center">
                        <button class="btn btn-primary submit-btn js_save_print" data-print-cheque="print" data-url="<?= site_url("admin/registry/ajax_update_selected_items") ?>">Сақлаш ва Чек чиқариш</button>
                        <button class="btn btn-primary submit-btn js_save_print" data-print-cheque="noPrint" data-url="<?= site_url("admin/registry/ajax_update_selected_items") ?>">Сақлаш</button>
						<button class="btn btn-secondary submit-btn" data-dismiss="modal">Ойнани ёпиш</button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    <?php endif; ?>


<?php endif; ?>



            </div>
        </div>
    </div>
</div>
