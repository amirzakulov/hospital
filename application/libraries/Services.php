<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services {
    protected $ci;

    public function __construct() {
        $this->ci =& get_instance();

        $this->ci->load->model(array(
            "doctors_model",
            "uzi_model",
            "laboratory_model",
            "services_model",
            "partners_model",
        ));
    }

    /**
     * Generate the services
     *
     * @param	bool	form - form_open va form_close kerakmi yuqmi
     * @param	bool    submit_btn - submit button kerakmi yuqmi
     * @param   string  app_part - adminmi yoki doctor qisimmi
     * @param   string  page - sahifalarni nomi
     *
     * @return  html
     */
    public function generate($config)
    {

        $config["form"]         = isset($config["form"]) ? $config["form"] : true;
        $config["submit_btn"]   = isset($config["submit_btn"]) ? $config["submit_btn"] : true;
        $config["app_part"]     = isset($config["app_part"]) ? $config["app_part"] : 'admin';
        $config["page"]         = isset($config["page"]) ? $config["page"] : '';
        $config["doctor_id"]    = isset($config["doctor_id"]) ? $config["doctor_id"] : null;


        $service_items = $this->items_list();

        $message = "";
        $docs = $service_items["docs"];
        $docs_price = $service_items["docs_price"];
        $laboratories = $service_items["laboratories"];
        $uzis = $service_items["uzis"];
        $uzis_price = $service_items["uzis_price"];
        $services = $service_items["services"];
        $services_price = $service_items["services_price"];

        //Xamkorlar
        $partners_options[] = "-- Танлаш --";
        foreach ($this->ci->partners_model->get_partners() as $partner) {
            if($partner["type"] == 1) {
                $partners_options[$partner["id"]] = $partner["last_name"] ." ". $partner["first_name"];
            } else {
                $partners_options[$partner["id"]] = $partner["company"];
            }
        }

        //Sender Doctors
		$sender_doctors_options[] = "-- Танлаш --";
		foreach ($this->ci->doctors_model->get_doctors_all() as $sender_doctor) {
			$sender_doctors_options[$sender_doctor["id"]] = $sender_doctor["last_name"]." ".$sender_doctor["first_name"];
		}

        $discount_options = array("" => "-- Танланмаган --", 1 => "Сумма", 2 => "Фоиз");

        $partner_id = [
            'name' 	=> 'partner_id',
            'id' 	=> 'partner_id',
            'value' => $this->ci->form_validation->set_value('partner_id'),
            'class' => 'select select2_search',
        ];
		$sender_doctor_id = [
            'name' 	=> 'sender_doctor_id',
            'id' 	=> 'sender_doctor_id',
            'value' => $this->ci->form_validation->set_value('sender_doctor_id'),
            'class' => 'select select2_search',
        ];
        $total_sum = [
            'name' 	=> 'total',
            'id' 	=> 'total',
            'type' 	=> 'text',
            'value' => $this->ci->form_validation->set_value('total'),
            'class' => 'form-control form-control-paid',
            "readonly" => "readonly"
        ];
        $paid = [
            'name' => 'paid',
            'id' => 'paid',
            'type' => 'text',
            'value' => $this->ci->form_validation->set_value('paid'),
            'class' => 'form-control form-control-paid',
        ];
        $debt = [
            'name' => 'debt',
            'id' => 'debt',
            'type' => 'text',
            'value' => $this->ci->form_validation->set_value('debt'),
            'class' => 'form-control form-control-paid',
            "readonly" => "readonly"
        ];
        $discount_type = [
            'name' => 'discount_type',
            'id' => 'discount_type',
            'value' => $this->ci->form_validation->set_value('discount_type'),
            'class' => 'select js_discount_type',
        ];
        $discount_value = [
            'name' => 'discount_value',
            'id' => 'discount_value',
            'type' => 'text',
            'value' => $this->ci->form_validation->set_value('discount_value', 0),
            'class' => 'form-control form-control-paid js_discount_value',
        ];

        $by_cash = [
            'name' => 'by_cash',
            'id' => 'by_cash',
            'type' => 'text',
            'value' => $this->ci->form_validation->set_value('by_cash'),
            'class' => 'form-control form-control-paid',
            'placeholder' => 'Нақд',
            "readonly" => "readonly"
        ];
        $by_card = [
            'name' => 'by_card',
            'id' => 'by_card',
            'type' => 'text',
            'value' => $this->ci->form_validation->set_value('by_card'),
            'class' => 'form-control form-control-paid',
            'placeholder' => 'Пластик',
        ];
        $by_bank = [
            'name' => 'by_bank',
            'id' => 'by_bank',
            'type' => 'text',
            'value' => $this->ci->form_validation->set_value('by_bank'),
            'class' => 'form-control form-control-paid',
            'placeholder' => 'Терминал',
        ];

        $html = '<div class="text-danger js_error_message w-100 text-center pb-3">'.(!empty($message) ? notification_text("Камида битта хизматни танлашингиз керак!") : "").'</div>';
        if($config["form"] == true) {//agar form kerak bulsa
            $html .= '<div class="row">
                        <div class="col-lg-12">
                        
                        '.(form_open("", array("class"=>"needs-validation", "novalidate"=>"")));
        }

            $html .='<div class="row js_items_content">
                    <div class="js_items_content_left col-lg-'.($config["page"] != 'for_payment_patients' ? "5":"12").'">
                        <div class="card-box" style="min-height: 192px;">
                            <div class="row js_selected_items_menu '.((isset($print_total) && $print_total != 0) ? "":"selected_items_menu").'">
                                <div class="js_yigindi_block_container col-sm-'.($config["page"] != 'for_payment_patients' ? "12 mb-3":"6").'">
                                    <div class="yigindi_block '.((isset($print_total) && $print_total != 0) ? "":"d-none").'">
                                        <div class="font-weight-bold mt-1 row '.(($config["app_part"] == "doctor") ? "d-none":"").'">
                                            <div class="col-sm-4">Йулланма: </div>
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                '.(form_dropdown($partner_id, $partners_options)).'
                                                </div>
                                            </div>
                                        </div>
                                        <div class="font-weight-bold mt-1 row '.(($config["app_part"] == "doctor") ? "d-none":"").'">
                                            <div class="col-sm-4">Шифокор йўлланма: </div>
                                            <div class="col-sm-8">
                                                <div class="form-group">';
                                                ksort($sender_doctors_options);
                                                $html .= form_dropdown($sender_doctor_id, $sender_doctors_options);
                                                $html .= '</div>
                                            </div>
                                        </div>
                                        <div class="js_selected_items_sum_total font-weight-bold mt-1 row">
                                            <div class="col-sm-4 text-danger">Жами: </div>
                                            <div class="col-sm-8">
                                            '.(form_input($total_sum)).'
                                            </div>
                                        </div>
                                        <div class="js_selected_items_sum_tulandi font-weight-bold mt-1 row '.(($config["app_part"] == "doctor") ? "d-none":"").'">
                                            <div class="col-sm-4">Тўланди: </div>
                                            <div class="col-sm-8">'.(form_input($paid)).'</div>
                                        </div>
                                        <div class="js_selected_items_sum_qarzingiz font-weight-bold mt-1 row '.(($config["app_part"] == "doctor") ? "d-none":"").'">
                                            <div class="col-sm-4">Қарзингиз: </div>
                                            <div class="col-sm-8">
                                            '.(form_input($debt)).'
                                            </div>
                                        </div>
                                        <div class="js_selected_items_discount font-weight-bold mt-1 row '.(($config["app_part"] == "doctor") ? "d-none":"").'">
                                            <div class="col-sm-4">Чегирма: </div>
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                '.(form_dropdown($discount_type, $discount_options)).'
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 js_discount font-weight-bold row '.((!is_null($this->ci->input->post("discount_type"))) ? "":"d-none").'  '.(($config["app_part"] == "doctor") ? "d-none":"").'">
                                            <div class="col-sm-4"> </div>
                                            <div class="col-sm-8">'.(form_input($discount_value)).'</div>
                                            <small class="col-sm-8 offset-sm-4 text-danger pt-1 d-none js_discount_value_percent"></small>
                                        </div>
        
                                        <div class="mt-1 js_selected_items_payment_type font-weight-bold row '.(($config["app_part"] == "doctor") ? "d-none":"").'">
                                            <div class="col-sm-4">Тўлов тури</div>
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-sm-4 pr-0">
                                                        <label for="by_cash">Нақд</label>
                                                        '.(form_input($by_cash)).'
                                                        </div>
                                                    <div class="col-sm-4 p-0">
                                                        <label for="by_card">Пластик</label>
                                                    '.(form_input($by_card)).'
                                                    </div>
                                                    <div class="col-sm-4 pl-0">
                                                        <label for="by_bank">Терминал</label>
                                                        '.(form_input($by_bank)).'
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="js_items_list col-sm-'.($config["page"] != 'for_payment_patients' ? "12":"6").'">
                                    <div class="js_selected_doctors">
                                        <ul class="list-group list-group-flush custom-list-group js_selected_items--tulovga '.(isset($print_doctors) ? "":"d-none").'">
                                            <li class="list-group-item active bg-light text-dark font-weight-bold js_selected_items_title">Шифокорлар</li>';
                                                if(isset($print_doctors)) {
                                                    foreach ($print_doctors as $doc_id => $print_doctor) {
                                                        $html .= '<li class="list-group-item js_selected_item js_item_'.($doc_id).'" data-id="'.($doc_id).'">
                                                            <span>'.($print_doctor).'</span>
                                                            <button type="button" class="close js_close_selected_item" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                        </li>';
                                                     }
                                                }
                                            $html .= '<li class="list-group-item font-weight-bold js_total_sum--tulovga '.(isset($print_doctors) ? "":"d-none").'">Жами: '.(isset($print_doctors_price_total) ? $print_doctors_price_total:"").'</li>
                                        </ul>
                                    </div>
        
                                    <div class="js_selected_labs mt-2">
                                        <ul class="list-group list-group-flush custom-list-group js_selected_items--tulovga '.(isset($print_labs) ? "":"d-none").'">
                                            <li class="list-group-item active bg-light text-dark font-weight-bold js_selected_items_title">Лаборатория</li>';
                                             if(isset($print_labs)) {
                                                foreach ($print_labs as $lab_id => $print_lab) {
                                                    $html .= '<li class="list-group-item js_selected_item js_item_'.($lab_id).'" data-id="'.($lab_id).'">
                                                        <span>'.($print_lab).'</span>
                                                        <button type="button" class="close js_close_selected_item" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                    </li>';
                                                }
                                            }
                                            $html .= '<li class="list-group-item font-weight-bold js_total_sum--tulovga '.(isset($print_labs) ? "":"d-none").'">Жами: '.( isset($print_labs_price_total) ? $print_labs_price_total:"").'</li>
                                        </ul>
                                    </div>
        
                                    <div class="js_selected_uzis mt-2">
                                        <ul class="list-group list-group-flush custom-list-group js_selected_items--tulovga '.(isset($print_uzis) ? "":"d-none").'">
                                            <li class="list-group-item active bg-light text-dark font-weight-bold js_selected_items_title">УЗИ</li>';
                                             if(isset($print_uzis)) {
                                                foreach ($print_uzis as $uzi_id => $print_uzi) {
                                                    $html .= '<li class="list-group-item js_selected_item js_item_'.($uzi_id).'" data-id="'.($uzi_id).'">
                                                        <span>'.($print_uzi).'</span>
                                                        <button type="button" class="close js_close_selected_item" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                    </li>';
                                                }
                                            }
                                            $html .= '<li class="list-group-item font-weight-bold js_total_sum--tulovga '.(isset($print_uzis) ? "":"d-none").'">Жами: '.(isset($print_uzis_price_total) ? $print_uzis_price_total:"").'</li>
                                                    </ul>
                                                    </div>
                                                    
                                                    <div class="js_selected_services mt-2">
                                                    <ul class="list-group list-group-flush custom-list-group js_selected_items--tulovga '.(isset($print_services) ? "":"d-none").'">
                                                        <li class="list-group-item active bg-light text-dark font-weight-bold js_selected_items_title">Қўшимча хизматлар</li>';
                                                        if(isset($print_services)) {
                                                        foreach ($print_services as $service_id => $print_service) {
                                                            $html .= '<li class="list-group-item js_selected_item js_item_'.($service_id).'" data-id="'.($service_id).'">
                                                            <span>'.($print_service).'</span>
                                                            <button type="button" class="close js_close_selected_item" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                        </li>';
                                                        }
                                                    }
                                        $html .='<li class="list-group-item font-weight-bold js_total_sum--tulovga '.(isset($print_uzis) ? "":"d-none").'">Жами: '.(isset($print_uzis_price_total) ? $print_uzis_price_total:"").'</li>
                                        </ul>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="js_items_content_right col-lg-7 '.($config["page"] != 'for_payment_patients' ? "":"d-none").'">
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
                                    </nav>
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active js_form js_form_doctors" id="nav-doctor" role="tabpanel" aria-labelledby="nav-doctor-tab">
                                            <input id="doc_search" class="form-control search-items" name="search" placeholder="Қидириш..." type="text" data-list=".js_doc_search">
                                            <div class="js_doc_search overflow-auto" style="max-height: 500px;">';
                                            foreach ($docs as $doc_id => $doc_name) {
                                                if($doc_id != $config["doctor_id"]) {
                                                $html .= '<div class="checkbox position-relative mb-1 border bg-light">
															<label class="d-block w-100 border p-2 cursor-pointer">
																<input type="checkbox" id="js_doctor_item_'.($doc_id).'" class="js_select_item d-none" name="doctor_id[]" value="'.($doc_id).'" '.(set_checkbox('doctor_id', $doc_id)).' data-price="'.($docs_price[$doc_id]).'" data-title="'.($doc_name).'"> 
																<span>'.($doc_name ." - ".$docs_price[$doc_id]).'</span>
																<input  type="hidden" name="doc_count['.$doc_id.']" class="js_item_count_value_input" value="1" id="js_item_count_'.($doc_id).'">
															</label>
															<div class="btn-group btn-group-sm js_item_count_block d-none" role="group" style="position: absolute; right: 0; top: 1px">
															  <button type="button" class="btn btn-light js_item_count js_item_count_decrease border-light item_count"><span class="fa fa-minus"></span></button>
															  <button type="button" class="btn btn-light font-weight-bold js_item_count_value_text border-light"> 1 </button>
															  <button type="button" class="btn btn-light js_item_count js_item_count_increase border-light item_count"><span class="fa fa-plus"></span></button>
															</div>
														</div>';
                                                }
                                            }
                                            $html .='</div>
                                        </div>
                                        
                                        <div class="tab-pane fade js_form js_form_labs" id="nav-lab" role="tabpanel" aria-labelledby="nav-lab-tab">
                                            <input id="lab_search" data-list=".headers_list" class="form-control search-items" name="search" placeholder="Қидириш..." type="text" data-list=".js_lab_search">
                                            <div class="js_lab_search overflow-auto vertical headers_list" style="max-height: 500px;">';
                                            foreach ($laboratories as $category) {
                                                if($category["active"]) {
                                                    $html .= '<div class="item_selection_lab_caption mt-2 origin">'.($category["name"]).'</div>';
                                                    foreach ($category["sub"] as $lab) {
														if($lab["active"]) {
													$html .= '<div class="checkbox position-relative mb-1 border bg-light">
																<label class="d-block w-100 border p-2 cursor-pointer">
																	<input type="checkbox" id="js_lab_item_'.($lab["id"]).'" class="js_select_item d-none" name="laboratory_id[]" value="'.($lab["id"]).'" '.(set_checkbox("laboratory_id", $lab["id"])).' data-price="'.($lab["price"]).'" data-title="'.($lab["name"]).'"> 
																	<span>'.($lab["name"] ." - ".$lab["price"]).'</span>
																	<input  type="hidden" name="lab_count['.$lab["id"].']" class="js_item_count_value_input" value="1" id="js_item_count_'.($lab["id"]).'">
																</label>
																<div class="btn-group btn-group-sm js_item_count_block" role="group" style="position: absolute; right: 0; top: 1px">
																  <button type="button" class="btn btn-light js_item_count js_item_count_decrease border-light item_count"><span class="fa fa-minus"></span></button>
																  <button type="button" class="btn btn-light font-weight-bold js_item_count_value_text border-light"> 1 </button>
																  <button type="button" class="btn btn-light js_item_count js_item_count_increase border-light item_count"><span class="fa fa-plus"></span></button>
																</div>
															</div>';
														}
                                                    }
                                                }
                                            }
                                            $html .= '</div>
                                        </div>
                
                                        <div class="tab-pane fade js_form js_form_uzis" id="nav-uzi" role="tabpanel" aria-labelledby="nav-uzi-tab">
                                            <input id="uzi_search" class="form-control search-items" name="search" placeholder="Қидириш..." type="text" data-list=".js_uzi_search">
                                            <div class="js_uzi_search overflow-auto" style="max-height: 500px;">';
                                            foreach ($uzis as $uzi_id => $uzi_name) {
                                                $html .='<div class="checkbox position-relative mb-1 border bg-light">
															<label class="d-block w-100 border p-2 cursor-pointer">
																<input type="checkbox" id="js_uzi_item_'.($uzi_id).'" class="js_select_item d-none" name="uzi_id[]" value="'.($uzi_id).'" '.(set_checkbox("uzi_id", $uzi_id)).' data-price="'.($uzis_price[$uzi_id]).'" data-title="'.($uzi_name).'"> 
																<span>'.($uzi_name ." - ".$uzis_price[$uzi_id]).'</span>
																<input  type="hidden" name="uzi_count['.$uzi_id.']" class="js_item_count_value_input" value="1" id="js_item_count_'.($uzi_id).'">
															</label>
															<div class="btn-group btn-group-sm js_item_count_block" role="group" style="position: absolute; right: 0; top: 1px">
															  <button type="button" class="btn btn-light js_item_count js_item_count_decrease border-light item_count"><span class="fa fa-minus"></span></button>
															  <button type="button" class="btn btn-light font-weight-bold js_item_count_value_text border-light"> 1 </button>
															  <button type="button" class="btn btn-light js_item_count js_item_count_increase border-light item_count"><span class="fa fa-plus"></span></button>
															</div>
														</div>';
                                            }
                                            $html .='</div>
                                        </div>
                
                                        <div class="tab-pane fade js_form js_form_services" id="nav-service" role="tabpanel" aria-labelledby="nav-service-tab">
                                            <input id="service_search" class="form-control search-items" name="search" placeholder="Қидириш..." type="text" data-list=".js_service_search">
                                            <div class="js_service_search">';
                                            foreach ($services as $service_id => $service_name){
//                                                $html .= '<div class="checkbox"><label><input type="checkbox" id="js_service_item_'.($service_id).'" class="js_select_item" name="service_id[]" value="'.($service_id).'" '.(set_checkbox("service_id", $service_id)).' data-price="'.($services_price[$service_id]).'"> <span>'.($service_name).'</span></label></div>';
												$html .= '<div class="checkbox position-relative mb-1 border bg-light">
															<label class="d-block w-100 border p-2 cursor-pointer">
																<input type="checkbox" id="js_service_item_'.($service_id).'" class="js_select_item d-none" name="service_id[]" value="'.($service_id).'" '.(set_checkbox("service_id", $service_id)).' data-price="'.($services_price[$service_id]).'" data-title="'.($service_name).'"> 
																<span>'.($service_name ." - ".$services_price[$service_id]).'</span>
																<input  type="hidden" name="service_count['.$service_id.']" class="js_item_count_value_input" value="1" id="js_item_count_'.($service_id).'">
															</label>
															<div class="btn-group btn-group-sm js_item_count_block" role="group" style="position: absolute; right: 0; top: 1px">
															  <button type="button" class="btn btn-light js_item_count js_item_count_decrease border-light item_count item_count_decrease"><span class="fa fa-minus"></span></button>
															  <button type="button" class="btn btn-light font-weight-bold js_item_count_value_text border-light item_count_value"> 1 </button>
															  <button type="button" class="btn btn-light js_item_count js_item_count_increase border-light item_count item_count_increase"><span class="fa fa-plus"></span></button>
															</div>
														</div>';
                                            }
                                            $html .='</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';

        if($config["submit_btn"] == true) {
            $html .= '<div class="m-t-20 text-center">
                            <button class="btn btn-primary submit-btn" name="print_cheque">'.(lang("create_user_submit_btn")).'</button>
                            <button class="btn btn-primary submit-btn" tabindex="5">'.(lang("general_save")).'</button>
                            <a role="button" class="btn btn-secondary submit-btn" href="'.(site_url("admin/registry/archive_patients")).'">'.(lang("user_cancel_button")).'</a>
                        </div>';
        }

        if($config["form"] == true) {
            $html .= (form_close()).'
            </div>
        </div> ';
        }


     return $html;

    }

    public function items_list()
    {
        $docs = array();
        $docs_price = array();
        foreach ($this->ci->doctors_model->get_doctors() as $doctor) {
            if($doctor["price"] > 0) {
                $docs[$doctor["id"]]        = $doctor["last_name"] ." ". $doctor["first_name"] . " (".$doctor["department_name"].")";
                $docs_price[$doctor["id"]]  = $doctor["price"];
            }

        }

		$uzis = array();
		$uzis_price = array();
		foreach ($this->ci->uzi_model->get_uzis() as $uzi) {
			$uzis[$uzi["id"]]       = $uzi["name"];
			$uzis_price[$uzi["id"]] = $uzi["price"];
		}

		$services = array();
		$services_price = array();
		foreach ($this->ci->services_model->get_services() as $service) {
			$services[$service["id"]]       = $service["name"];
			$services_price[$service["id"]] = $service["price"];
		}

        $items = array(
			"docs" 			=> $docs,
			"docs_price" 	=> $docs_price,
			"laboratories" 	=> $this->ci->laboratory_model->get_categories(),
			"uzis" 			=> $uzis,
			"uzis_price" 	=> $uzis_price,
			"services"      => $services,
			"services_price"=> $services_price,
		);



        return $items;
    }


}
