<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('services_list'))
{
    function services_list($doctor_id = null, $payment_id = null)
    {
        $ci =& get_instance();
        $ci->load->model(array("partners_model"));
        $ci->load->library("services");

        $back_url = is_null($doctor_id) ? site_url("doctor/patients/") : site_url("doctor/patients/patient/".$payment_id);;


        $items          = $ci->services->items_list();
        $docs           = $items["docs"];
        $docs_price     = $items["docs_price"];
        $laboratories   = $items["laboratories"];
        $uzis           = $items["uzis"];
        $uzis_price     = $items["uzis_price"];
		$services       = $items["services"];
		$services_price = $items["services_price"];

        $partners_options[] = "-- Танлаш --";
        foreach ($ci->partners_model->get_partners() as $partner) {
            $partners_options[$partner["id"]] = $partner["last_name"] ." ".$partner["first_name"];
        }

        $partner_id = [
            'name' => 'partner_id',
            'id' => 'partner_id',
            'value' => $ci->form_validation->set_value('partner_id'),
            'class' => 'custom-select',
        ];
        $paid = [
            'name' => 'paid',
            'id' => 'paid',
            'type' => 'text',
            'value' => $ci->form_validation->set_value('paid'),
            'class' => 'form-control form-control-paid',
        ];
        $total_sum = [
            'name' => 'total',
            'id' => 'total',
            'type' => 'text',
            'value' => $ci->form_validation->set_value('total'),
            'class' => 'form-control form-control-paid',
            "readonly" => "readonly"
        ];
        $debt = [
            'name' => 'debt',
            'id' => 'debt',
            'type' => 'text',
            'value' => $ci->form_validation->set_value('debt'),
            'class' => 'form-control form-control-paid',
            "readonly" => "readonly"
        ];
        $discount_type = [
			'name' => 'discount_type',
			'id' => 'discount_type',
			'value' => $ci->form_validation->set_value('discount_type'),
			'class' => 'select js_discount_type',
        ];
        $discount_value = [
			'name' => 'discount_value',
			'id' => 'discount_value',
			'type' => 'text',
			'value' => $ci->form_validation->set_value('discount_value', 0),
			'class' => 'form-control form-control-paid js_discount_value',
        ];



        $html = '';
        $html .= '<div class="row js_items_content">';
            $html .= '<div class="col-lg-6">';
                $html .= '<div class="card-box" style="min-height: 192px;">';
                    $html .= '<div class="row">';
                        $html .= '<div class="col-sm-12 js_selected_items_menu selected_items_menu">';
                            $html .= '<div class="yigindi_block d-none mb-4">
                                
                                <div class="js_selected_items_sum_total font-weight-bold mt-1 row">
                                    <div class="col-sm-3 text-danger">Жами: </div>
                                    <div class="col-sm-9">
                                        '.(form_input($total_sum)).'
                                    </div>
                                </div>';
                                if(is_null($doctor_id)){
                                    $html .='
                                    <div class="js_selected_items_sum_tulandi font-weight-bold mt-1 row">
                                        <div class="col-sm-3">Тўланди: </div>
                                        <div class="col-sm-9">'.(form_input($paid)).'</div>
                                    </div>
                                    <div class="js_selected_items_sum_qarzingiz font-weight-bold mt-1 row">
                                        <div class="col-sm-3">Қарзингиз: </div>
                                        <div class="col-sm-9">'.(form_input($debt)).'</div>
                                    </div>';
                                }
                                $html .='</div>';

                            $html .='<div class="js_selected_doctors d-none">
                                        <ul class="list-group list-group-flush custom-list-group js_selected_items--tulovga">
                                            <li class="list-group-item active bg-light text-dark font-weight-bold js_selected_items_title">Шифокорлар</li>
                                            <li class="list-group-item font-weight-bold js_total_sum--tulovga"></li>
                                        </ul>
                                    </div>';

                            $html .= '<div class="js_selected_labs mt-2 d-none">
                                        <ul class="list-group list-group-flush custom-list-group js_selected_items--tulovga">
                                            <li class="list-group-item active bg-light text-dark font-weight-bold js_selected_items_title">Лаборатория</li>
                                            <li class="list-group-item font-weight-bold js_total_sum--tulovga"></li>
                                        </ul>
                                    </div>';

                            $html .= '<div class="js_selected_uzis mt-2 d-none">
                                        <ul class="list-group list-group-flush custom-list-group js_selected_items--tulovga">
                                            <li class="list-group-item active bg-light text-dark font-weight-bold js_selected_items_title">УЗИ</li>
                                            <li class="list-group-item font-weight-bold js_total_sum--tulovga"></li>
                                        </ul>
                                    </div>';

							$html .= '<div class="js_selected_services mt-2 d-none">
                                            <ul class="list-group list-group-flush custom-list-group js_selected_items--tulovga">
                                                <li class="list-group-item active bg-light text-dark font-weight-bold js_selected_items_title">Қўшимча хизматлар</li>
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
                                    <a class="nav-item nav-link active" id="nav-doctor-tab" data-toggle="tab" href="#nav-doctor" role="tab" aria-controls="nav-doctor" aria-selected="true">Шифокорлар <span class="badge badge-warning js_nav_doctor_tab"></span></a>
                                    <a class="nav-item nav-link" id="nav-lab-tab" data-toggle="tab" href="#nav-lab" role="tab" aria-controls="nav-lab" aria-selected="false">Лаборатория <span class="badge badge-warning js_nav_lab_tab"></span></a>
                                    <a class="nav-item nav-link" id="nav-uzi-tab" data-toggle="tab" href="#nav-uzi" role="tab" aria-controls="nav-uzi" aria-selected="false">УЗИ <span class="badge badge-warning js_nav_uzi_tab"></span></a>
									<a class="nav-item nav-link" id="nav-service-tab" data-toggle="tab" href="#nav-service" role="tab" aria-controls="nav-service" aria-selected="false">Қўшимча хизматлар <span class="badge badge-warning js_nav_service_tab"></span></a>
								</div>
                            </nav>';
                            $html .='<div class="tab-content overflow-auto" id="nav-tabContent" style="max-height: 500px;">';
                                $html .='<div class="tab-pane fade show active js_form js_form_doctors" id="nav-doctor" role="tabpanel" aria-labelledby="nav-doctor-tab">
                                            <input id="doc_search" class="form-control search-items" name="search" placeholder="Қидириш..." type="text" data-list=".js_doc_search">
                                            <div class="js_doc_search">';
                                            foreach ($docs as $doc_id => $doc_name) {
                                                if($doc_id != $doctor_id){
                                                    $html .= '<div class="checkbox"><label><input type="checkbox" id="js_doctor_item_'.($doc_id).'" class="js_select_item" name="doctor_id[]" value="'.($doc_id).'" '.(set_checkbox('doctor_id', $doc_id)).' data-price="'.($docs_price[$doc_id]).'"> <span>'.($doc_name).'</span></label></div>';
                                                }
                                            }
                                    $html .='</div>
                                        </div>';

                                $html .='<div class="tab-pane fade js_form js_form_labs" id="nav-lab" role="tabpanel" aria-labelledby="nav-lab-tab">
                                            <input id="lab_search" class="form-control search-items" name="search" placeholder="Қидириш..." type="text" data-list=".js_lab_search">
                                            <div class="js_lab_search">';
                                        foreach ($laboratories as $category) {
                                            $html .= '<div class="p-1 pl-3 mb-2 bg-success text-white">' . ($category["name"]) . '</div>';
                                            foreach ($category["sub"] as $lab) {
                                                $html .= '<div class="checkbox"><label><input type="checkbox" id="js_lab_item_' . ($lab["id"]) . '" class="js_select_item" name="laboratory_id[]" value="' . ($lab["id"]) . '" '.(set_checkbox('laboratory_id', $lab["id"])).' data-price="'.($lab["price"]).'"> <span>'.($lab["name"]).'</span></label></div>';
                                            }
                                        }
                                    $html .= '</div>
                                        </div>';

                                $html .='<div class="tab-pane fade js_form js_form_uzis" id="nav-uzi" role="tabpanel" aria-labelledby="nav-uzi-tab">
                                            <input id="uzi_search" class="form-control search-items" name="search" placeholder="Қидириш..." type="text" data-list=".js_uzi_search">
                                            <div class="js_uzi_search">';
                                                foreach ($uzis as $uzi_id => $uzi_name) {
                                                    $html .='<div class="checkbox"><label><input type="checkbox" id="js_uzi_item_'.($uzi_id).'" class="js_select_item" name="uzi_id[]" value="'.($uzi_id).'" '.(set_checkbox('uzi_id', $uzi_id)).' data-price="'.($uzis_price[$uzi_id]).'"> <span>'.($uzi_name).'</span></label></div>';
                                                }
                                            $html .='</div>
                                        </div>';

								$html .='<div class="tab-pane fade js_form js_form_services" id="nav-service" role="tabpanel" aria-labelledby="nav-service-tab">
                                                <input id="service_search" class="form-control search-items" name="search" placeholder="Қидириш..." type="text" data-list=".js_service_search">
                                                <div class="js_service_search">';
                                                     foreach ($services as $service_id => $service_name) {
                                                        $html .='<div class="checkbox"><label><input type="checkbox" id="js_service_item_'.($service_id).'" class="js_select_item" name="service_id[]" value="'.($service_id).'" '.(set_checkbox('service_id', $service_id)).' data-price="'.($services_price[$service_id]).'"> <span>'.($service_name).'</span></label></div>';
                                                     }
											$html .='</div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        <div class="m-t-20 text-center">
            <button class="btn btn-primary submit-btn">'.(lang("general_save")).'</button>
            <button class="btn btn-secondary submit-btn" onclick="window.close();">'.(lang("general_cancel")).'</button>
        </div>
        ';

        return $html;

    }
}

