<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Selected Laboratory table. List and Results
 *
 * @param	int	Payment ID
 * @param	boolean	If Created Date print or not
 * @param	boolean	If some part should print or not
 * @param	boolean If param is false shows partner's laboratory list
 * @return	string
 */
if ( ! function_exists('build_laboratory_results_table'))
{
	function build_laboratory_results_table($payment_id, $created_date = false, $print = true, $clinic = true)
	{
		$ci =& get_instance();
		$ci->load->model(array("patient_laboratories_model", "patients_model", "patients_payments_model", "settings_model", "doctors_model"));
		$patient_active_labs = $ci->patient_laboratories_model->lab_tree($payment_id, $clinic);
		$payment = $ci->patients_payments_model->get_patient_payment($payment_id);
		$patient = $ci->patients_model->get_patient($payment["patient_id"]);
		$class_hide_from_print = ($clinic == false ? "hide_from_print":"");

		if(count($patient_active_labs["laboratories"]) == 0) {
			return "";
		} else {
			if(!$patient_active_labs) {
				$html = false;
			} else {
				$html = '';
				if($print) {
					$clinic_details = $ci->settings_model->get_group_settings("LBP");

					$html = '

					<style>
					.lab_title {
						text-align: '.$clinic_details["lab_title_alignment"].';
						font-size: '.$clinic_details["lab_title_font_size"].'rem !important;
					}
					@media print {
						.lab_title {
							text-align: '.$clinic_details["lab_title_alignment"].';
							font-size: '.$clinic_details["lab_title_font_size"].'rem !important;
						}
					}
					
					
					</style>


                        <div class="table table-responsive zmlogo w-100 page-header">
                            
                            <div class="lab_blank_header">
                                <div class="lab_blank_header_top">
                                    <div class="lab_blank_logo">
                                        <img src="'.site_url("assets/images/logo.png").'" alt="Logo" class="w-100">
                                    </div>
                                    <div class="lab_blank_header_text">';
										if(!empty($clinic_details["name"])) {
											$html .= '<p class="mb-0 pb-1">'.($clinic_details["name"]).'</p>';
										}

										if(!empty($clinic_details["address"])) {
											$html .= '<p class="mb-0 pb-1">Телеграм: <a class="text-info">'.($clinic_details["address"]).'</a></p>';
										}

										if(!empty($clinic_details["orientation"])) {
											$html .= '<p class="mb-0 pb-1">Мўлжал: '.($clinic_details["orientation"]).'</p>';
										}

										if(!empty($clinic_details["phone"])) {
											$html .= '<p class="mb-0 pb-1">Телефонлар: '.($clinic_details["phone"]).'</p>';
										}

										if(!empty($clinic_details["web_address"])) {
											$html .= '<p class="mb-0 pb-1">Веб сайт: <a class="text-info">'.($clinic_details["web_address"]).'</a> </p>';
										}

										if(!empty($clinic_details["telegram"])) {
											$html .= '<p class="mb-0 pb-1">Телеграм: <a class="text-info">'.($clinic_details["telegram"]).'</a></p>';
										}

										if(!empty($clinic_details["email"])) {
											$html .= '<p class="mb-0 pb-1">Email: <a class="text-info">'.($clinic_details["email"]).'</a></p>';
										}



						$html .= '</div>
                                </div>
                                
                                <div class="lab_blank_header_bottom mt-3 pt-3">
                                    <table class="table table-bordered table-sm mb-1">
                                        <thead class="bg-light">
                                        <tr>
                                            <th class="text-center bg-secondary">Фамилияси, Исми</th>
                                            <th class="text-center">Рўйхатга олинди</th>
                                            <th class="text-center">Туғилган сана</th>
                                            <th class="text-center">Чоп этилди</th>
                                            <th class="text-center">Телефон</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-center">'.($patient['last_name'] . ' ' . $patient['first_name']).'</td>
                                            <td class="text-center">'.(date_formating(strtotime($payment['created_date']), 'mt')).'</td>
                                            <td class="text-center">'.(date("Y", strtotime($patient['dob']))).'</td>
                                            <td class="text-center">'.(date("d.m.Y")).'</td>
                                            <td class="text-center">'.(phone_number_format($patient['phone'])).'</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>';

					$html .= '<div class="page-footer">';
					//agar xamkor laboratoriya bulmasa vrachni ismini kursat
					if($clinic && !empty($clinic_details["laborant_id"])) {
						$laborant = $ci->doctors_model->get_doctor($clinic_details["laborant_id"]);

						$html .= '
								<div class="print_doctor pt-3">
									<strong class="w-50 d-inline-block">Врач лаборант:</strong> <span>'.($laborant["last_name"] .' '.$laborant["first_name"]).'</span>
								 </div>
						';
					}
					if(!empty($clinic_details["footer_text"])) {
						$html .='<p class="w-100 text-right '.($class_hide_from_print).'">'.$clinic_details["footer_text"].'</p>';
					}

					$html .= '</div>';
				}



				$html .=
					(!$clinic ? "<h4 class='w-100 text-center text-dark ".(!$clinic ? "hide_from_print":"")."'>Хамкор Лаборатория</h4>":"").
					'<table class="table border-0 table_page_header '.($class_hide_from_print).'">
						<thead class="border-0 table_page_header--thead">
						  <tr>
							<td colspan="3" class="border-0 p-0 table_page_header--td">
							  <!--place holder for the fixed-position header-->
							  <div class="page-header-space"></div>
							</td>
						  </tr>
						</thead>
                
                    <tbody>
                      <tr>
                        <td colspan="3" class="border-0 p-0">';


				$html .= '<div class="pt-2">';
				if($print && $clinic && ($payment["laboratory_status"] == 2)) {
					$html .= '
					<div class=" d-block w-100 mb-3 text-right"><button type="button" class="btn btn-primary printBtn"><span class="fa fa-print font-18"></span></button></div>
					';
				}

				if($created_date != false) {
					$html .= '<span class="time font-18">'.date("d.m.Y", strtotime($created_date)).'</span><hr>';
				}

				$counter = 0;
				foreach ($patient_active_labs["laboratories"] as $cat_id => $category) {
					foreach ($category["sub"] as $lab) {
						if($lab["template_id"] == 1 || is_null($lab["template_id"])) {
							if($lab["result"] != "#" && empty($ci->laboratory_model->sub_categories($lab["lab_id"]))) {

								if($counter == 0) {
									$html .= '<div class="print_page_break '.($class_hide_from_print).'"><div class="font-weight-bold mb-2 lab_title">'.($category["name"]).'</div>';
									$html .= '<table class="table table-bordered table-sm mb-3 d-print-table lab_result_table '.($class_hide_from_print).'">
                                <thead class="bg-light">
                                <tr>
                                    <th class="text-center" width="45%">Наименование</th>
                                    <th class="text-center" width="15%">Результат</th>
                                    <th class="text-center" width="25%">Норма</th>
                                    <th class="text-center" width="15%">Ед. Изм</th>
                                </tr>
                                </thead>
                            <tbody>';
								}

								$html .='
								<tr>
									<td class="text-dark">'.$lab["name"].'</td>
									<td class="print_border result_cell">'.$lab["result"].'</td>
									<td class="text-dark">'.$lab["norma"].'</td>
									<td class="text-dark">'.$lab["mesurment"].'</td>
								</tr>';
								$counter++;

							}
						}

					}
					if($counter > 0) {
						$html .= '</tbody> </table></div>';
					}
					$counter = 0;
				}

				/*****************************
				 * sub laboratory
				 * */
				foreach ($patient_active_labs["laboratories"] as $cat_id => $category) {
					foreach ($category["sub"] as $lab) {

						if($lab["template_id"] == 1 || is_null($lab["template_id"])) {
							if($lab["result"] != "#" && !empty($ci->laboratory_model->sub_categories($lab["lab_id"]))) {

								$html .= '<div class="print_page_break"><div class="font-weight-bold mb-2 lab_title">'.($lab["name"]).'</div>';
								$html .= '<table class="table table-bordered table-sm mb-3 d-print-table lab_result_table">
                                <thead class="bg-light">
                                <tr>
                                    <th class="text-center" width="45%">Наименование</th>
                                    <th class="text-center" width="15%">Результат</th>
                                    <th class="text-center" width="25%">Норма</th>
                                    <th class="text-center" width="15%">Ед. Изм</th>
                                </tr>
                                </thead>
                            <tbody>';

								$recommendation = array();
								foreach ($patient_active_labs["sub_laboratories"][$lab["lab_id"]]["sub"] as $sub_laboratory) {
									if(!$sub_laboratory["is_parent"]) {

										if($sub_laboratory["recommendation"]) {
											$recommendation[] = $sub_laboratory["recommendation_text"];
										}

										if($sub_laboratory["result"] != "#") {
											$html .='
									<tr>
										<td class="pl-3">- '.$sub_laboratory["name"].'</td>
										<td class="text-center print_border result_cell">'.$sub_laboratory["result"].'</td>
										<td class="text-center text-secondary">'.$sub_laboratory["norma"].'</td>
										<td class="text-center text-secondary">'.$sub_laboratory["mesurment"].'</td>
									</tr>';
										}
									}
								}


								$html .= '</tbody> </table></div>';
							}
						}
					}
				}


				/*****************************
				 * sub laboratory
				 * */
				foreach ($patient_active_labs["laboratories"] as $cat_id => $category) {
					foreach ($category["sub"] as $lab) {

						/*****************************
						 * if template = 2
						 * */
						if($lab["template_id"] == 2) {
							if($lab["result"] != "#" && !empty($ci->laboratory_model->sub_categories($lab["lab_id"]))) {
								/////////////////////////////////

								$html .= '<div class="print_page_break">
									<div class="font-weight-bold mb-2 lab_title">'.($category["name"]).'</div>
								<table class="table table-bordered table-sm mb-3 d-print-table lab_result_table">
								<thead class="bg-light">
								<tr>
									<th class="text-center" width="40%">'.$lab["name"].'</th>
									<th class="text-center" width="20%">Влагалище</th>
									<th class="text-center" width="25%">Цервикальный</th>
									<th class="text-center" width="15%">Уретра</th>
								</tr>
								</thead>
								<tbody>';
								foreach ($patient_active_labs["sub_laboratories"][$lab["lab_id"]]["sub"] as $key => $sub_laboratory) {
									if($sub_laboratory["is_parent"] == 2) {
										$html .='
									<tr>
										<td class="print_border result_cell">'.$sub_laboratory["name"].'</td>';
										foreach ($ci->patient_laboratories_model->sub_laboratories_by_payment($sub_laboratory["payment_id"], $sub_laboratory["lab_id"]) as $sub_sub_lab) {

											$html .= '<td class="text-center print_border result_cell">'.($sub_sub_lab["result"]).'</td>';
										}
										$html .= '</tr>';
									}
								}

								$html .= '</tbody> </table></div>';
								/// /////////////////////////////
							}
						}
					}
				}

				/*****************************
				 * sub laboratory
				 * */
				foreach ($patient_active_labs["laboratories"] as $cat_id => $category) {
					foreach ($category["sub"] as $lab) {

						/*****************************
						 * if template = 3
						 * */
						if($lab["template_id"] == 3) {
							if($lab["result"] != "#" && !empty($ci->laboratory_model->sub_categories($lab["lab_id"]))) {
								/////////////////////////////////

								$html .= '<div class="print_page_break">
									<div class="font-weight-bold mb-2 lab_title">'.($category["name"]).'</div>
								<table class="table table-bordered table-sm mb-3 d-print-table lab_result_table">
								<thead class="bg-light">
								<tr>
									<th class="text-center" width="40%">'.$lab["name"].'</th>
									<th class="text-center" width="25%">Цервикальный</th>
									<th class="text-center" width="20%">Влагалище</th>
									<th class="text-center" width="15%">Уретра</th>
								</tr>
								</thead>
								<tbody>';

								$aa = $ci->patient_laboratories_model->sub_laboratories_by_payment_results($payment_id, $lab["lab_id"]);
								list($s, $v, $u) = $aa;

								$form2 = array();
								foreach ($aa[0]["sub"] as $key => $lab) {
									$form2[$key][0] = $lab["name"];
									$form2[$key][1] = $s["sub"][$key]["result"];
									$form2[$key][2] = $v["sub"][$key]["result"];
									$form2[$key][3] = $u["sub"][$key]["result"];
								}

								foreach ($form2 as $lrow) {
									$html .= '<tr>';
									foreach ($lrow as $key => $lcell) {
										$html .='<td class="'.($key == 0 ? "text-left":"text-center").' print_border result_cell">'.$lcell.'</td>';
									}
									$html .= '</tr>';
								}

								$html .= '</tbody> </table></div>';
								/// /////////////////////////////
							}
						}
					}

				}

				foreach ($patient_active_labs["laboratories"] as $cat_id => $category) {
					//agar rasm upload qilingan bulsa
					foreach ($category["sub"] as $lab2) {
						if(!empty($lab2["images"])) {
							$images = explode(";",$lab2["images"]);

							$html .= '<div class="lab_images text-center print_page_break mt-3">';
							$html .= "<h4 class='text-center'>".$lab2["name"]."</h4>";
							foreach ($images as $image) {
								$html .='<img src="'.site_url("uploads/services/lab/".$image).'" class="img-responsive mb-2 mr-1">';
							}
							$html .= '</div>';

						}
					}
				}

				$html .= "</div>";

				$html .= '</td>
                      </tr>
                    </tbody>
                    <tfoot>
					  <tr>
						<td class="border-0">
						  <!--place holder for the fixed-position footer-->
						  <div class="page-footer-space"></div>
						</td>
					  </tr>
					</tfoot>

                 </table>
                 
                ';



			}

			return $html;
		}


	}
}

if ( ! function_exists('print_receipt'))
{
	function print_receipt($payment_id)
	{
		$ci =& get_instance();

		$encoding = 'UTF-8';
		$payment = $ci->patients_payments_model->get_patient_payment($payment_id);
		$payment_data = array(
			"total" => array("text" => "Жами:", "price" => $payment["total"]),
			"paid" =>  array("text" => "Туланди:  ", "price" => $payment["paid"]),
			"debt" =>  array("text" => "Карзингиз:", "price" => $payment["debt"]),
			"discount" =>  array("text" => "Чегирма:", "price" => 0),
			"payment_date" => array("text" => "Сана:", "date" => $payment["created_date"]),
		);

		//chegirmani qushamiz
		$payment_data["discount"] = array("text" => "Чегирма:", "price" => $payment["discount"]);

		$patient = $ci->patients_model->get_patient($payment["patient_id"]);
		$patient_data = array("name" => $patient["last_name"] ." ". $patient["first_name"]." ".date("Y", strtotime($patient["dob"])), "payment_id" => $payment_id);

		$selected_items = array("patient_data" => $patient_data, "payment_data"=>$payment_data, "doctor_items" => false, "laboratory_items" => false, "uzi_items" => false, "service_items" => false, "room_items" => false);
		$doctors        = array();
		$laboratories   = array();
		$uzis           = array();
		$rooms          = array();
		$services       = array();

		//doctor qabuliga tulov qilgan bulsa
		if($payment["doctor_status"] > 0) {
			$doctor_total = 0;
			$c = 1;
			$doctors[0]["text"] = mb_substr("Шифокор куриги", 0, 32, $encoding);
			$doctors[0]["price"] = "";
			$doctors[0]["count"] = "";
			foreach ($ci->patient_doctor_model->get_patient_doctor($payment_id) as $key => $doctor) {
				$doctors[$c]["text"] = mb_substr($doctor["last_name"] . " " . $doctor["first_name"], 0, 32,$encoding);
				$doctors[$c]["price"] = $doctor["price"];
				$doctors[$c]["count"] = $doctor["count"];
				$doctor_total += ($doctor["price"] * $doctor["count"]);
				$c++;
			}
			$doctors["total"]["text"] = "Жами:";
			$doctors["total"]["price"] = $doctor_total;
			$doctors["total"]["count"] = "";

			$selected_items["doctor_items"] = $doctors;
		}

		//laboratoriyaga tulov qilgan bulsa
		if($payment["laboratory_status"] > 0) {
			$lab_total = 0;
			$c = 1;

			$selected_labs = $ci->patient_laboratories_model->get_patient_laboratories_details($payment_id);
			$laboratories[0]["text"] = "Лаборатория";
			$laboratories[0]["price"] = "";
			$laboratories[0]["count"] = "";

			foreach (formatting_selected_laboratories($selected_labs) as $key => $laboratory) {
				$laboratories[$c]["text"] = mb_substr($laboratory["name"], 0, 32);
				$laboratories[$c]["price"] = $laboratory["price"];
				$laboratories[$c]["count"] = $laboratory["count"];
				$lab_total += ($laboratory["price"] * $laboratory["count"]);
				$c++;
			}
			$laboratories["total"]["text"] = "Жами:";
			$laboratories["total"]["price"] = $lab_total;

			$selected_items["laboratory_items"]= $laboratories;
		}

		//uziga tulov qilgan bulsa
		if($payment["uzi_status"] > 0) {
			$uzi_total = 0;
			$c = 1;
			$uzis[0]["text"] = mb_substr("УЗИ Тахлили", 0, 32, $encoding);
			$uzis[0]["price"] = "";
			$uzis[0]["count"] = "";
			foreach ($ci->patient_uzi_model->get_patient_uzi($payment_id) as $key => $uzi) {
				$uzis[$c]["text"] = mb_substr($uzi["name"], 0, 32,$encoding);
				$uzis[$c]["price"] = $uzi["price"];
				$uzis[$c]["count"] = $uzi["count"];
				$uzi_total += ($uzi["price"] * $uzi["count"]);
				$c++;
			}
			$uzis["total"]["text"] = "Жами:";
			$uzis["total"]["price"] = $uzi_total;

			$selected_items["uzi_items"] = $uzis;
		}

		if($payment["room_status"] > 0) {

			$patient_room = $ci->patient_room_model->get_bed_by_payment($payment_id);

			$room_text = "Хона ".$patient_room["room_number"];
			$rooms[0]["text"] = mb_substr($room_text, 0, 32, $encoding);
			$rooms[0]["price"] = "";
			$rooms[0]["count"] = '';

			$start_date = date("d.m.Y", strtotime($patient_room["start_date"]));
			$end_date   = date("d.m.Y", strtotime($patient_room["end_date"]));

			$earlier = new DateTime($start_date);
			$later = new DateTime($end_date);
			$days = $later->diff($earlier)->format("%a");

			$rooms[1]["text"] = mb_substr($start_date . " - " . $end_date, 0, 32,$encoding);
			$rooms[1]["price"] = $patient_room["bed_price"];
			$rooms[1]["count"] = $days;

			$room_total = $days * $patient_room["bed_price"];

			$selected_items["room_items"] = $rooms;
		}

		//servicega tulov qilgan bulsa
		if($payment["service_status"] > 0) {
			$service_total = 0;
			$c = 1;
			$services[0]["text"] = mb_substr("К.Xизматлар", 0, 32, $encoding);//;
			$services[0]["price"] = "";
			$services[0]["count"] = '';
			foreach ($ci->patient_service_model->get_patient_service($payment_id) as $key => $service) {
				$services[$c]["text"] = mb_substr($service["name"], 0, 32,$encoding);
				$services[$c]["price"] = $service["price"];
				$services[$c]["count"] = $service["count"];
				$service_total += ($service["price"] * $service["count"]);
				$c++;
			}
			$services["total"]["text"] = "Жами:";
			$services["total"]["price"] = $service_total;

			$selected_items["service_items"] = $services;
		}


		return $selected_items;
	}
}

if ( ! function_exists('formatting_selected_laboratories')) {
	function formatting_selected_laboratories($selected_laboratories)
	{
		$selected_labs = array();
		foreach ($selected_laboratories as $category) {
			foreach ($category["sub"] as $lab_id => $labs) {
				if(isset($labs["sub"])) {
					$status = 1;
					foreach ($labs["sub"] as $sub) {
						if($sub["status"] == 0) {
							$status = 0;
							continue;
						}
					}

				} else {
					$selected_labs[$lab_id]["name"] 	= $labs["name"];
					$selected_labs[$lab_id]["price"] 	= $labs["price"];
					$selected_labs[$lab_id]["lab_id"] 	= $labs["lab_id"];
					$selected_labs[$lab_id]["status"] 	= $labs["status"];
					$selected_labs[$lab_id]["count"] 	= $labs["count"];
				}
			}
		}

		return $selected_labs;
	}
}

if ( ! function_exists('build_uzi_results'))
{
	function build_uzi_results($payment_id)
	{
		$ci =& get_instance();
		$patient_active_uzis = $ci->patient_uzi_model->get_patient_uzi($payment_id);
		if(!$patient_active_uzis) {
			$html = false;
		} else {
			$html = "";
			foreach ($patient_active_uzis as $uzi) {
				$html .= '<div class="row">
                            <div class="col-md-12">
                                <div class="card-box">
                                    <div class="card-title"><strong>'.lang("doctor_patients_uzi_name_label").'</strong> '.$uzi["name"].'</div>
                                    <hr>'.
					$uzi["result"]
					.'</div>
                            </div>
                        </div>';
			}
		}

		return $html;
	}
}


