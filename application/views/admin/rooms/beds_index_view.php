<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-sm-5 col-6 font-weight-bold font-18">
        <?= $room["number"] ." / ". $room["rtype_name"] ." / ". number_format($room["price"], 0, 0, " ");?>

    </div>
    <div class="col-sm-3 col-3 text-right m-b-20">
        <a class="btn btn-primary" href="<?= site_url("admin/rooms/room_beds_add/".$room_id) ?>" title="<?= lang("rooms_bed_add") ?>" role="button"><span class="fa fa-plus"></span> <?= lang("room_bed") ?></a>
    </div>
</div>
<?= $breadcrumbs; ?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered custom-table mb-0 datatable">
                <thead class="thead-dark">
                <tr>
                    <th width="100px" class="text-center">Ётоқ</th>
                    <th width="100px" class="text-center">Нархи</th>
                    <th width="10px" class="text-center">Бандлик</th>
                    <th width="100px" class="text-center">Чек №</th>
                    <th width="100px" class="text-center">Кундан</th>
                    <th width="100px" class="text-center">Кунгача</th>
                    <th width="100px" class="text-center">Бемор</th>
                    <th width="100px" class="text-center">Жами</th>
                    <th width="" class="text-center">Тўланди</th>
                    <th width="150px" class="text-right"></th>
                    <th width="100px" class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($beds as $bed) { ?>
                    <tr id="js_row_<?= $bed["id"] ?>">
                        <td class="text-center"><?= $bed["name"]; ?></td>
                        <td class="text-center"><?= number_format($bed["price"], 0, 0, " "); ?></td>
                        <td class="text-center js_busy">
                            <?php $text_color = "fa-circle-o";
                                if($bed["busy"] == 1){
                                    $text_color = "fa-circle text-danger";
                                } else if($bed["busy"] == 2) {
                                    $text_color = "fa-circle text-info";
                                }
                            ?>
                            <span title="Ётоқ <?= $bed["name"]; ?>" class="fa mr-1 <?= $text_color; ?>"></span>
                        </td>
						<td class="text-center"><?= $bed["payment_id"]; ?></td>
                        <td class="text-center js_start_date"><?= !is_null($bed["start_date"]) ? date("d.m.Y", strtotime($bed["start_date"])) : ""; ?></td>
                        <td class="text-center js_end_date"><?= !is_null($bed["end_date"]) ? date("d.m.Y", strtotime($bed["end_date"])) : ""; ?></td>
                        <td class="js_patient_name"><?= $bed["last_name"]." ".$bed["first_name"] ?></td>
                        <td class="js_room_total"><?= $bed["total"] ?></td>
                        <td class="js_room_paid"><?= $bed["paid"] ?></td>
                        <td>
                            <a class="btn btn-primary <?= $bed["busy"] == 0 ? "":"d-none"; ?> js_assign_bed" data-bed="<?= $bed["name"]; ?>" data-bed-id="<?= $bed["id"]; ?>" data-price="<?= $bed["price"]; ?>" href="javascript:void(0)" role="button"><span class="fa fa-plus"></span> Банд қилиш</a>
                            <a class="btn btn-success <?= $bed["busy"] != 0 ? "":"d-none"; ?> js_view_bed" data-bed="<?= $bed["name"]; ?>" data-bed-id="<?= $bed["id"]; ?>" data-price="<?= $bed["price"]; ?>" data-url="<?= site_url("admin/rooms/ajax_show_patient_room"); ?>" href="javascript:void(0)" role="button"><span class="fa fa-edit"></span> Ўзгартириш</a>
                        </td>
                        <td class="text-right">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="<?= site_url("admin/rooms/room_beds_edit/".$bed["id"]) ?>"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit"); ?></a>
                                    <a class="dropdown-item js_delele_item" href="javascript:void(0);" data-href="<?= site_url("admin/rooms/room_beds_delete/") ?>" data-id="<?= $bed["id"] ?>"><i class="fa fa-trash-o m-r-5"></i> <?= lang("general_delete"); ?></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- modal -->
<div id="assign_bed" class="modal fade" data-backdrop="static" aria-modal="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white text-center p-3">
                <div class="row" style="width: 90%;">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-md-3 font-weight-bold" style="border-right: 1px solid #fff; ">Хона</div>
                            <div class="col-md-3 font-weight-bold" style="border-right: 1px solid #fff;">Тури</div>
                            <div class="col-md-3 font-weight-bold" style="border-right: 1px solid #fff;">Нархи</div>
                            <div class="col-md-3 font-weight-bold" style="">Ётоқ</div>
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="border-right: 1px solid #fff;"><?= $room["number"]; ?></div>
                            <div class="col-md-3" style="border-right: 1px solid #fff;"><?= $room["rtype_name"] ?></div>
                            <div class="col-md-3" style="border-right: 1px solid #fff;"><?= $room["price"] ?></div>
                            <div class="col-md-3 js_bed_name"></div>
                        </div>
                    </div>
                </div>
                <button type="button" class="close text-white"  style="width: 10%;" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="new_patient" class="js_new_patient"> Янги бемор
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row mt-3 js_room_patient_searchbox">
                    <?= form_open(
                        "",
                        array("name"=>"f1", "class"=>"needs-validation w-100 ".$was_validated, "novalidate"=>""),
                        array("patient_id"=>"", "bed_id"=>"", "price"=>"")
                    ); ?>
                    <div class="col-sm-12">
                        <div class="form-group">
							<?php echo lang('rooms_partner_id', 'partner_id');?>
                            <?= form_dropdown($partners, $partners_options); ?>
                            <div class="invalid-feedback"><?= form_error('partner_id'); ?></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?php echo lang('room_patient', 'patient', array(), true);?>
                            <?php echo form_input($patient);?>
                            <div class="invalid-feedback"><?php echo form_error('room_autocomplete'); ?></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<?php echo lang('room_start_date', 'start_date', array(), true);?>
									<div class="cal-icon">
										<?php echo form_input($start_date);?>
									</div>
									<div class="invalid-feedback"><?php echo form_error('start_date'); ?></div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<?php echo lang('room_end_date', 'end_date', array(), true);?>
									<div class="cal-icon">
										<?php echo form_input($end_date);?>
									</div>
									<div class="invalid-feedback"><?php echo form_error('end_date'); ?></div>
								</div>
							</div>
						</div>
                    </div>

					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<?= lang('room_total', 'total');?>
									<?= form_input($total);?>
									<div class="invalid-feedback"><?php echo form_error('total'); ?></div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<?= lang('room_paid', 'paid');?>
									<?= form_input($paid);?>
									<div class="invalid-feedback"><?php echo form_error('paid'); ?></div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<?= lang('room_debt', 'debt');?>
									<?= form_input($debt);?>
									<div class="invalid-feedback"><?php echo form_error('debt'); ?></div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<?= lang('room_discount_type', 'discount_type');?>
									<?= form_dropdown($discount_type, $discount_options); ?>
									<div class="invalid-feedback"><?php echo form_error('discount_type'); ?></div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<?= lang('room_discount_value', 'discount_value');?>
									<?= form_input($discount_value); ?>
									<div class="invalid-feedback"><?php echo form_error('discount_value'); ?></div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<?php echo lang('room_by_cash', 'by_cash');?>
									<?php echo form_input($by_cash);?>
									<div class="invalid-feedback"><?php echo form_error('by_cash'); ?></div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<?php echo lang('room_by_card', 'by_card');?>
									<?php echo form_input($by_card);?>
									<div class="invalid-feedback"><?php echo form_error('by_card'); ?></div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<?php echo lang('room_by_bank', 'by_bank');?>
									<?php echo form_input($by_bank);?>
									<div class="invalid-feedback"><?php echo form_error('by_bank'); ?></div>
								</div>
							</div>
						</div>
					</div>

                    <div class="col-sm-12">
                        <div class="text-right mt-3">
							<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close"><?= lang("general_cancel") ?></button>
                            <button type="button" class="btn btn-primary js_room_assign_old_patient" data-url="<?= site_url("admin/rooms/ajax_assign_old_patient_to_room") ?>">Сақлаш</button>
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div>
                <div class="row mt-3 js_room_patient_add_form d-none">
                    <div class="col-sm-12">
                        <?= form_open("", array("name"=>"f2", "class"=>"needs-validation ".$was_validated, "novalidate"=>""),
                            array("bed_id"=>"", "price"=>"")); ?>
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
                        <div class="form-group">
                            <?php echo lang('create_user_payment_type_label', 'payment_type', array(), true);?>
                            <?= form_dropdown($payment_type, $payment_type_options); ?>
                            <div class="invalid-feedback"><?php echo form_error('payment_type'); ?></div>
                        </div>
                        <div class="form-group">
                            <?php echo lang('create_user_lname_label', 'last_name', array(), true);?>
                            <?php echo form_input($last_name);?>
                            <div class="invalid-feedback"><?php echo form_error('last_name'); ?></div>
                        </div>
                        <div class="form-group">
                            <?php echo lang('create_user_fname_label', 'first_name', array(), true);?>
                            <?php echo form_input($first_name);?>
                            <div class="invalid-feedback"><?php echo form_error('first_name'); ?></div>
                        </div>
                        <div class="form-group">
                            <?php echo lang('create_user_surname_label', 'surname');?> <br />
                            <?php echo form_input($surname);?>
                        </div>
                        <div class="form-group">
                            <?php echo lang('create_user_dob_label', 'dob');?>
                            <?php echo form_input($dob);?>
                        </div>
                        <div class="form-group">
                            <?php echo lang('create_user_phone_label', 'phone', array(), true);?>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">+998</span>
                                </div>
                                <?php echo form_input($phone);?>
                            </div>
                            <div class="invalid-feedback"><?php echo form_error('phone'); ?></div>
                        </div>
                        <div class="form-group">
                            <?php $region_options = $regions ?>
                            <?php echo lang('create_user_region_label', 'region_id', array(), true);?>
                            <?= form_dropdown($region, $region_options, $selected_region_id); ?>
                            <div class="invalid-feedback"><?php echo form_error('region_id'); ?></div>
                        </div>
                        <div class="form-group">
                            <?php $city_options = $cities; ?>
                            <?php echo lang('create_user_city_label', 'city_id', array(), true);?>
                            <?= form_dropdown($city, $city_options, $selected_city_id); ?>
                            <div class="invalid-feedback"><?php echo form_error('city_id'); ?></div>
                        </div>
                        <div class="form-group">
                            <?php echo lang('create_user_address_label', 'address');?><br />
                            <?php echo form_input($address);?>
                        </div>
                        <div class="form-group">
                            <?php echo lang('room_start_date', 'start_date', array(), true);?>
                            <div class="cal-icon">
                                <?php echo form_input($start_date);?>
                            </div>
                            <div class="invalid-feedback"><?php echo form_error('start_date'); ?></div>
                        </div>
                        <div class="form-group">
                            <?php echo lang('room_end_date', 'end_date', array(), true);?>
                            <div class="cal-icon">
                                <?php echo form_input($end_date);?>
                            </div>
                            <div class="invalid-feedback"><?php echo form_error('end_date'); ?></div>
                        </div>
                        <div class="col-sm-12 pl-0 pr-0">
                            <div class="row  pl-0 pr-0">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <?php echo lang('room_total', 'total');?>
                                        <?php echo form_input($total);?>
                                        <div class="invalid-feedback"><?php echo form_error('total'); ?></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <?php echo lang('room_paid', 'paid');?>
                                        <?php echo form_input($paid);?>
                                        <div class="invalid-feedback"><?php echo form_error('paid'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right mt-3">
							<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close"><?= lang("general_cancel") ?></button>
                            <button type="button" class="btn btn-primary js_room_assign_new_patient" data-url="<?= site_url("admin/rooms/ajax_assign_new_patient_to_room") ?>">Сақлаш</button>
                        </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="view_room" class="modal fade" data-backdrop="static" aria-modal="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white text-center p-3">
                <div class="row" style="width: 90%;">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-md-3 font-weight-bold" style="border-right: 1px solid #fff; ">Хона</div>
                            <div class="col-md-3 font-weight-bold" style="border-right: 1px solid #fff;">Тури</div>
                            <div class="col-md-3 font-weight-bold" style="border-right: 1px solid #fff;">Нархи</div>
                            <div class="col-md-3 font-weight-bold" style="">Ётоқ</div>
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="border-right: 1px solid #fff;"><?= $room["number"]; ?></div>
                            <div class="col-md-3" style="border-right: 1px solid #fff;"><?= $room["rtype_name"] ?></div>
                            <div class="col-md-3" style="border-right: 1px solid #fff;"><?= $room["price"] ?></div>
                            <div class="col-md-3 js_bed_name"></div>
                        </div>
                    </div>
                </div>
                <button type="button" class="close text-white"  style="width: 10%;" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mt-3 js_room_patient_searchbox">
                    <?= form_open(
                        "",
                        array("name"=>"f3", "class"=>"needs-validation w-100 ".$was_validated, "novalidate"=>""),
                        array("patient_id"=>"", "bed_id"=>"", "price"=>"", "patient_room_id"=>"", "payment_id"=>"")
                    ); ?>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?= lang('room_patient', 'patient2', array(), true);?>
                            <?= form_input($patient2);?>
                            <div class="invalid-feedback"><?php echo form_error('patient2'); ?></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?= lang('room_start_date', 'start_date', array(), true);?>
                            <div class="cal-icon">
                                <?= form_input($start_date);?>
                            </div>
                            <div class="invalid-feedback"><?php echo form_error('start_date'); ?></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?= lang('room_end_date', 'end_date', array(), true);?>
                            <div class="cal-icon">
                                <?= form_input($end_date);?>
                            </div>
                            <div class="invalid-feedback"><?= form_error('end_date'); ?></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?= lang('room_total', 'total');?>
                                    <?= form_input($total);?>
                                    <div class="invalid-feedback"><?php echo form_error('total'); ?></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?= lang('room_paid', 'paid');?>
                                    <?= form_input($paid);?>
                                    <div class="invalid-feedback"><?php echo form_error('paid'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="row d-none">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?= lang('room_debt_off', 'debt_off');?>
                                    <?= form_input($debt_off);?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="text-right mt-3">
                            <button type="button" class="btn btn-secondary"><?= lang("general_cancel") ?></button>
                            <button type="button" class="btn btn-primary js_patient_room_update" data-url="<?= site_url("admin/rooms/ajax_update_patient_room") ?>">Сақлаш</button>
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
