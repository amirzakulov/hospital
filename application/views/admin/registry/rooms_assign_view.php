<div class="row">
	<div class="col-lg-8">
		<h4 class="page-title"><?= $title; ?></h4>
	</div>
</div>

<?php if(!is_null($patient_id)) {?>
    <div class="card-box profile-header mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="profile-view">
                    <div class="profile-basic1">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="profile-info-left">
                                    <h3 class="user-name m-t-0 mb-0"><?= $patient["last_name"] ." ". $patient["first_name"] ." ". $patient["surname"]; ?></h3>
                                    <div class="staff-id"><strong>Касби: </strong><?= $patient["occupation"] ?></div>
                                    <div class="staff-id"><strong>Бемор рақами: </strong><?= $patient["username"] ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <ul class="personal-info">
                                    <li>
                                        <span class="title">Телефон:</span>
                                        <span class="text"><?= is_null($patient["phone"]) ? "":phone_number_format($patient["phone"]); ?></span>
                                    </li>
                                    <li>
                                        <span class="title">Туғилган сана:</span>
                                        <span class="text"><?= is_null($patient["dob"]) ? "":date("Y", strtotime($patient["dob"])); ?></span>
                                    </li>
                                    <li>
                                        <span class="title">Манзил:</span>
                                        <span class="text"><?= $patient["region_name"].", ".$patient["city_name"].", ".$patient["address"]; ?></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <div class="personal-info" style="min-height: 5.1rem;">
                                    <strong>Қисқача маълумот: </strong>
                                    <div class="staff-id">
                                        <?= $patient["description"]; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="row">
	<div class="col-lg-12">
		<?= form_open("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
        <?php if(is_null($patient_id)) {?>
            <div class="card-box">
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						<?php echo lang('create_user_lname_label', 'last_name', array(), true);?>
						<?php echo form_input($last_name);?>
						<div class="invalid-feedback"><?php echo form_error('last_name'); ?></div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<?php echo lang('create_user_fname_label', 'first_name', array(), true);?>
						<?php echo form_input($first_name);?>
						<div class="invalid-feedback"><?php echo form_error('first_name'); ?></div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<?php echo lang('create_user_surname_label', 'surname');?> <br />
						<?php echo form_input($surname);?>
					</div>
				</div>

				<div class="col-sm-4">
					<div class="form-group">
						<?php echo lang('create_user_dob_label', 'dob');?>
						<?php echo form_input($dob);?>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group gender-select!">
						<?php echo lang('create_user_gender_label', 'gender', array("class"=>"gen-label1"));?><br />
						<div class="form-check-inline">
							<label class="form-check-label">
								<input type="radio" name="gender" value="1" <?= set_checkbox('gender', '1'); ?> class="form-check-input">Эркак
							</label>
						</div>
						<div class="form-check-inline">
							<label class="form-check-label">
								<input type="radio" name="gender" value="2" <?= set_checkbox('gender', '2'); ?> class="form-check-input">Аёл
							</label>
						</div>
						<div class="invalid-feedback"><?php echo form_error('gender'); ?></div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<?php echo lang('create_user_phone_label', 'phone');?>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">+998</span>
							</div>
							<?php echo form_input($phone);?>
						</div>
						<div class="invalid-feedback"><?php echo form_error('phone'); ?></div>
					</div>
				</div>

				<div class="col-sm-12">
					<div class="row">
						<div class="col-sm-4 col-md-4 col-lg-4">
							<div class="form-group">
								<?php $region_options = $regions ?>
								<?php echo lang('create_user_region_label', 'region_id', array(), true);?>
								<?= form_dropdown($region, $region_options, $selected_region_id); ?>
								<div class="invalid-feedback"><?php echo form_error('region_id'); ?></div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<?php $city_options = $cities; ?>
								<?php echo lang('create_user_city_label', 'city_id', array(), true);?>
								<?= form_dropdown($city, $city_options, $selected_city_id); ?>
								<div class="invalid-feedback"><?php echo form_error('city_id'); ?></div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<?php echo lang('create_user_address_label', 'address');?><br />
								<?php echo form_input($address);?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    <?php } ?>

		<div class="row js_items_content">
			<div class="col-lg-5">
				<div class="card-box" style="min-height: 192px;">
					<div class="row">
						<div class="col-sm-12 js_selected_items_menu <?= (isset($print_total) && $print_total != 0) ? "":"selected_items_menu"; ?>">
							<div class="yigindi_block <?= (isset($print_total) && $print_total != 0) ? "":"d-none"?>">
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
								<ul class="list-group list-group-flush custom-list-group js_selected_items--tulovga <?= isset($print_beds) ? "":"d-none"; ?>">
									<li class="list-group-item active bg-light text-dark font-weight-bold js_selected_items_title">Хона</li>
									<?php
									if(isset($print_beds)) {
										foreach ($print_beds as $bed_id => $print_bed) {?>
											<li class="list-group-item js_selected_item js_item_<?= $bed_id ?>" data-id="<?= $bed_id ?>">
												<span><?= $print_bed ?></span>
												<button type="button" class="close js_close_selected_item" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
											</li>
										<?php }
									} ?>
									<li class="list-group-item font-weight-bold js_total_sum--tulovga <?= isset($print_beds) ? "":"d-none"; ?>">Жами: <?= isset($print_beds_price_total) ? $print_beds_price_total:""; ?></li>
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
									<a class="nav-item nav-link active" id="nav-room-tab" data-toggle="tab" href="#nav-room" role="tab" aria-controls="nav-room" aria-selected="true">Хоналар <span class="badge badge-warning js_nav_room_tab"></span></a>
								</div>
							</nav>
							<div class="tab-content overflow-auto" id="nav-tabContent" style="max-height: 500px;">
								<div class="tab-pane fade show active js_form js_form_rooms" id="nav-room" role="tabpanel" aria-labelledby="nav-room-tab">
									<input id="bed_search" class="form-control search-items" name="search" placeholder="Қидириш..." type="text" data-list=".js_bed_search">
									<div class="js_bed_search overflow-auto" style="max-height: 500px;">
										<?php foreach ($rooms as $room) {?>
											<div class="item_selection_lab_caption p-1 pl-3 bg-success text-white font-18"><?= $room["number"] ." ". $room["rtype_name"]; ?></div>
											<?php foreach ($room["beds"] as $bed) { ?>
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
		<div class="form-group">
			<label class="display-block">Status</label>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="user_status" id="doctor_active" value="1">
				<label class="form-check-label" for="doctor_active">
					Фаол
				</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="user_status" id="doctor_inactive" value="0" checked>
				<label class="form-check-label" for="doctor_inactive">
					Но-фаол
				</label>
			</div>
		</div>

		<div class="m-t-20 text-center">
			<button class="btn btn-primary submit-btn" name="print_cheque"><?= lang("create_user_submit_btn") ?></button>
			<button class="btn btn-primary submit-btn" tabindex="5"><?= lang("general_save") ?></button>
			<a role="button" class="btn btn-secondary submit-btn" href="<?= site_url("admin/registry/rooms") ?>"><?= lang("user_cancel_button") ?></a>
		</div>
		<?= form_close(); ?>
	</div>
</div>
