<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registry extends Admin_Controller {
    private  $user_id;


    function __construct()
    {
        parent::__construct();

        $this->load->model(
            array(
                "patients_model",
                "users_model",
                "doctors_model",
                "patient_doctor_model",
                "laboratory_model",
                "patient_laboratories_model",
                "uzi_model",
                "patient_uzi_model",
                "partners_model",
                "patients_payments_model",
                "patients_payments_details_model",
                "regions_model",
                "cities_model",
                "expenses_model",
                "expense_type_model",
                "payment_types_model",
                "services_model",
                "patient_service_model",
                "rooms_model",
                "room_beds_model",
                "patient_room_model",
                "payments_debt_discount_model",
            ));

//        $this->load->library(array("WebClientPrint"));
        $this->load->language(array("patients", "rooms"));
        $this->user_id = $this->session->userdata("user_id");

    }

    public function index() {

        $this->data['title'] = 'Қабулхона';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.autocomplete.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.hideseek.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/divjs/divjs.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/functions_patients.js?".time()).'"></script>
                                        ';
        $this->data["page"] = 'index';

        $patients = $this->patients_list("today");
        $this->data["completed_patients"]       = $patients["completed_patients"];
        $this->data["incomplete_patients"]      = $patients["incomplete_patients"];
        $this->data["completed_patients_count"] = count($patients["completed_patients"]);
        $this->data["incomplete_patients_count"]= count($patients["incomplete_patients"]);

        $this->load->library("services");
        $service_config["form"]         = false;
        $service_config["submit_btn"]   = false;
        $this->data["services_template"]= $this->services->generate($service_config);
        $this->data["patients_counts"]  = $this->patients_counts();

        $this->data["payment_type_options"] = $this->payment_types_model->get_payment_types();
        $expense_type_options = ["Boshqa chiqimlar"];
		foreach ($this->expense_type_model->get_expense_types() as $etype) {
			$expense_type_options[$etype["id"]] = $etype["name"];
        }
        $this->data["expense_type_options"] = $expense_type_options;

        $this->data['payment_type'] = [
            'name' => 'payment_type',
            'id' => 'payment_type',
            'type' => 'text',
            'value' => $this->form_validation->set_select('payment_type'),
            'class' => 'select',
        ];

        $this->render('admin/registry/index_view');
    }

	public function add() {
		$this->data["title"] = "Бемор қўшиш";
		$this->data['before_themeStyle'] = '
                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/js/lou_multi_select/multi-select.css").'">
                                        ';

		$this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/lou_multi_select/jquery.multi-select.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.hideseek.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/functions_patients.js?".time()).'"></script>
                                        ';

		$print_cheque = false;
		$this->load->library("services");
		$service_config["form"]         = false;
		$service_config["submit_btn"]   = false;
		$this->data["services_template"]= $this->services->generate($service_config);

		$was_validated      = "";
		$tables             = $this->config->item('tables', 'ion_auth');
		$identity_column    = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		//Viloyatlar
		$this->data['regions'] = $this->regions_model->get_regions_array();
		$selected_region_id = $this->config->item("default_region_id"); //defaul_region_id = 3 ga (farg'ona)
		if(!is_null($this->input->post("region_id"))) {
			$selected_region_id = $this->input->post("region_id");
		}
		$this->data['selected_region_id'] = $selected_region_id;

		//Shaxarlar
		$selected_city_id = $this->config->item("default_city_id"); //defaul_city_id = 2 ga (quqonning ID si)
		if(!is_null($this->input->post("city_id"))) {
			$selected_city_id = $this->input->post("city_id");
		}
		$this->data['cities'] = $this->cities_model->get_cities_by_region_id($selected_region_id);
		$this->data['selected_city_id'] = $selected_city_id;

		$prefix = "bem";
		$max_id = $this->patients_model->get_max_id();

		$this->load->helper("mix");
		$code = uniqe_code_genetrator($prefix, $max_id);
		$this->data["username"] = $code;

		$partners[] = "-- Танлаш --";
		foreach ($this->partners_model->get_partners() as $partner) {
			if($partner["type"] == 1) {
				$partners[$partner["id"]] = $partner["last_name"] ." ". $partner["first_name"];
			} else {
				$partners[$partner["id"]] = $partner["company"];
			}
		}
		$this->data["partners_options"] = $partners;

		$this->data["payment_type_options"] = $this->payment_types_model->get_payment_types();

		$this->data["discount_options"] = array("" => "-- Танланмаган --", 1 => "Сумма", 2 => "Фоиз");

		$items = $this->items_list();
		$this->data["docs"]             = $items["docs"];
		$this->data["docs_price"]       = $items["docs_price"];
		$this->data["laboratories"]     = $items["laboratories"];
		$this->data["uzis"]             = $items["uzis"];
		$this->data["uzis_price"]       = $items["uzis_price"];
		$this->data["services"]         = $items["services"];
		$this->data["services_price"]   = $items["services_price"];
		$this->data["partners"]         = $partners;

		// validate form input
		$this->form_validation->set_rules('doctor_id[]', "Шифокор куриги", 'trim');
		$this->form_validation->set_rules('laboratory_id[]', "Лаборатория", 'trim');
		$this->form_validation->set_rules('uzi_id[]', "УЗИ", 'trim');
		$this->form_validation->set_rules('service_id[]', "Қўшимча хизматлар", 'trim');
		$this->form_validation->set_rules('lab_payment[]', "Лаборатория Тулов", 'trim');
		$this->form_validation->set_rules('uzi_payment[]', "УЗИ Тулов", 'trim');

		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
		$this->form_validation->set_rules('gender', $this->lang->line('create_user_validation_gender'), 'trim');
		$this->form_validation->set_rules('address', $this->lang->line('create_user_validation_address'), 'trim');
		if ($identity_column !== 'email') {
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|valid_email');
		} else {
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
		}

		$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
		$this->form_validation->set_rules('dob', $this->lang->line('create_user_validation_department_label'), 'trim');
		$this->form_validation->set_rules('partner_id', $this->lang->line('create_user_sender_label'), 'trim');
		$this->form_validation->set_rules('paid', "Тўланди", 'trim');
		$this->form_validation->set_rules('debt', "Қарзингиз", 'trim');
		$this->form_validation->set_rules('total', "Жами", 'trim');
		$this->form_validation->set_rules('discount_type', "Чегирма", 'trim');
		$this->form_validation->set_rules('discount_value', "Қиймат", 'trim');
		$this->form_validation->set_rules('by_cash', "Нақд", 'trim');
		$this->form_validation->set_rules('by_card', "Пластик", 'trim');
		$this->form_validation->set_rules('by_bank', "Терминал", 'trim');

		if ($this->form_validation->run() === TRUE) {

			$print_cheque = is_null($this->input->post("print_cheque")) ? false:true;
			unset($_POST["print_cheque"]);

			$email = strtolower($this->input->post('email'));
			$identity = ($identity_column === 'email') ? $email : $code;
			$password = "123!@";

			$additional_data = [
				'first_name'    => $this->input->post('first_name'),
				'last_name'     => $this->input->post('last_name'),
				'surname'       => $this->input->post('surname'),
				'dob'           => (empty($this->input->post('dob')) ? NULL:$this->input->post('dob')."-01-01"),
				'gender'        => $this->input->post('gender'),
				'address'       => $this->input->post('address'),
				'region_id'     => $this->input->post("region_id"),
				'city_id'       => $this->input->post("city_id"),
				'phone'         => (empty($this->input->post('phone')) ? NULL:("+998".$this->input->post('phone'))),
				'description'   => $this->input->post('description'),
				'active'        => $this->input->post('user_status'),
			];

			$group = array('5'); // Sets user to bemor.
		}

		if ($this->form_validation->run() === TRUE && ($user_id = $this->ion_auth->register($identity, $password, $email, $additional_data, $group))) {
			//bemorlarga qushish
			$patient_id = $this->patients_model->add(array("user_id" => $user_id));
			if(!empty($this->input->post("doctor_id")[0]) || !empty($this->input->post('laboratory_id')) || !empty($this->input->post('uzi_id')) || !empty($this->input->post('service_id'))) {
				$discount_type 	= $this->input->post("discount_type");
				$discount_value = $this->input->post("discount_value");
				$total 			= $this->input->post("total");
				$discount 		= $this->get_discount($discount_type, $discount_value, $total);

				$payment_arr = array(
					"doctor_status"     => (empty($this->input->post("doctor_id")[0]) ? 0:1),
					"laboratory_status" => (empty($this->input->post('laboratory_id')) ? 0:1),
					"uzi_status"        => (empty($this->input->post('uzi_id')) ? 0:1),
					"service_status"    => (empty($this->input->post('service_id')) ? 0:1),
					"room_status"       => 0,
					"order_status"      => 0,
					"patient_id"        => $patient_id,
					"discount_type"     => $discount_type,
					"discount_value"    => $discount_value,
					"discount"	    	=> $discount,
					"total"             => $total,
					"status"            => 0,
					'partner_id'        => $this->input->post('partner_id'),
				);
				$payment_id = $this->patients_payments_model->add($payment_arr);

				$payment_details_arr = array(
					'payment_id'      	=> $payment_id,
					"paid"              => $this->input->post("paid"),
					'by_cash'      		=> $this->input->post('by_cash'),
					'by_card'      		=> $this->input->post('by_card'),
					'by_bank'      		=> $this->input->post('by_bank'),
				);
				$this->patients_payments_details_model->add($payment_details_arr);
			}

			//agar tulov qilingan bulsa
			if(isset($payment_id)) {
				//doctor
				if(!empty($this->input->post("doctor_id")[0]))
				{
					foreach ($this->input->post('doctor_id') as $index => $doctor_id) {
						$doc_arr = array(
							"patient_id" 	=> $patient_id,
							"doctor_id" 	=> $doctor_id,
							"payment_id" 	=> $payment_id,
							"status" 		=> 0,
							"count" 		=> $this->input->post('doc_count')[$doctor_id]
						);
						$this->patient_doctor_model->add($doc_arr);
					}
				}

				//loboratory
				if(!empty($this->input->post('laboratory_id'))) {
					foreach ($this->input->post('laboratory_id') as $laboratory_id) {
						//parent labni qushish
						$lab_value = $this->laboratory_model->get_laboratory($laboratory_id);
						$lab_arr = array(
							"patient_id"    => $patient_id,
							"lab_id"        => $laboratory_id,
							"result"        => $lab_value["default_value"],
							"payment_id"    => $payment_id,
							"status"        => 0,
							"recommendation"=> 0,
							"is_parent"     => 1,
							"count" 		=> $this->input->post('lab_count')[$laboratory_id]
						);
						$this->patient_laboratories_model->add($lab_arr);

						//sub labni qushish
						$laboratory = $this->laboratory_model->sub_categories($laboratory_id);
						if(count($laboratory) > 0) {
							foreach ($laboratory as $sublab) {
								//agar $sublabning sub laboratoriyasi bulsa
								$is_parent = empty($sublab["sub"]) ? 0:2;
								$lab_arr = array(
									"patient_id"    => $patient_id,
									"lab_id"        => $sublab["id"],
									"result"        => $sublab["default_value"],
									"payment_id"    => $payment_id,
									"status"        => 0,
									"recommendation"=> 0,
									"is_parent"     => $is_parent,
									"parent_id"     => $laboratory_id,
									"count" 		=> null
								);
								$this->patient_laboratories_model->add($lab_arr);

								if(!empty($sublab["sub"])) {
									foreach ($sublab["sub"] as $sub_sub_lab) {
										$lab_arr = array(
											"patient_id"    => $patient_id,
											"lab_id"        => $sub_sub_lab["id"],
											"result"        => $sub_sub_lab["default_value"],
											"payment_id"    => $payment_id,
											"status"        => 0,
											"recommendation"=> 0,
											"is_parent"     => 0,
											"parent_id"     => $sublab["id"],
											"count" 		=> null
										);
										$this->patient_laboratories_model->add($lab_arr);
									}
								}
							}
						}
					}
				}

				//uzi
				if(!empty($this->input->post('uzi_id'))) {
					foreach ($this->input->post('uzi_id') as $uzi_id) {
						$uzi_arr = array(
							"patient_id" 	=> $patient_id,
							"uzi_id" 		=> $uzi_id,
							"payment_id" 	=> $payment_id,
							"status" 		=> 0,
							"count" 		=> $this->input->post('uzi_count')[$uzi_id],
							"is_conclusion"	=> 0
						);

						$this->patient_uzi_model->add($uzi_arr);
					}

					//xulosa uchun bitta qator qushib quyamiz
					$uzi_conclusion = array(
						"patient_id" 	=> $patient_id,
						"uzi_id" 		=> null,
						"payment_id" 	=> $payment_id,
						"status" 		=> 0,
						"count" 		=> 1,
						"is_conclusion"	=> 1
					);

					$this->patient_uzi_model->add($uzi_conclusion);
				}

				if(!empty($this->input->post('service_id'))) {
					foreach ($this->input->post('service_id') as $service_id) {
						$service_arr = array(
							"patient_id" => $patient_id,
							"service_id" => $service_id,
							"payment_id" => $payment_id,
							"status" 	=> 0,
							"count" 	=> $this->input->post('service_count')[$service_id]
						);
						$this->patient_service_model->add($service_arr);
					}
				}

				//Check chiqaramiz
				if(pos_print() && $print_cheque) {
					try {
						$this->load->helper(array("lab_form"));
						$pr = print_receipt($payment_id);
						$this->load->library('ReceiptPrint');
						$user = $this->ion_auth->user()->row();
						$this->receiptprint->connect($this->config->item("pos_printer_name"));
						$this->receiptprint->print_receipt($pr["patient_data"], $pr["payment_data"], $pr["doctor_items"], $pr["laboratory_items"], $pr["uzi_items"], $pr["service_items"], $user);
					} catch (Exception $e) {
						log_message("error", "Error: Could not print. Message ".$e->getMessage());
						$this->receiptprint->close_after_exception();
					}
				}
			}


			// check to see if we are creating the user
			// redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());

			redirect("admin/registry", 'refresh');
		} else {

			$docs             = $items["docs"];
			$docs_price       = $items["docs_price"];
			$laboratories     = $this->laboratory_model->get_laboratories($this->input->post("laboratory_id"));
			$uzis             = $items["uzis"];
			$uzis_price       = $items["uzis_price"];
			$services         = $items["services"];
			$services_price   = $items["services_price"];

			$print_total = 0;
			if($this->input->post("doctor_id") != null)
			{
				//tanlangan doctorlarni printer uchun saqlab qolish
				$print_doctors = array();
				$print_doctors_price_total = 0;
				foreach ($this->input->post("doctor_id") as $doc_id) {
					$print_doctors[$doc_id] = $docs[$doc_id];
					$print_doctors_price_total += $docs_price[$doc_id];
				}

				$this->data["print_doctors"] = $print_doctors;
				$this->data["print_doctors_price_total"] = $print_doctors_price_total;
				$print_total += $print_doctors_price_total;
			}

			if($this->input->post("laboratory_id") != null) {
				//tanlangan laboratoriyalarni printer uchun saqlab qolish
				$print_labs = array();
				$print_labs_price_total = 0;
				foreach ($laboratories as $laboratory) {
					$print_labs[$laboratory["id"]] = $laboratory["name"] ." - ". $laboratory["price"];
					$print_labs_price_total += $laboratory["price"];
				}

				$this->data["print_labs"] = $print_labs;
				$this->data["print_labs_price_total"] = $print_labs_price_total;

				$print_total += $print_labs_price_total;
			}

			if($this->input->post("uzi_id") != null)
			{
				//tanlangan doctorlarni printer uchun saqlab qolish
				$print_uzis = array();
				$print_uzis_price_total = 0;
				foreach ($this->input->post("uzi_id") as $uzi_id) {
					$print_uzis[$uzi_id] = $uzis[$uzi_id];
					$print_uzis_price_total += $uzis_price[$uzi_id];
				}

				$this->data["print_uzis"] = $print_uzis;
				$this->data["print_uzis_price_total"] = $print_uzis_price_total;
				$print_total += $print_uzis_price_total;
			}

			if($this->input->post("service_id") != null)
			{
				//tanlangan qushimcha servicelarni printer uchun saqlab qolish
				$print_services = array();
				$print_services_price_total = 0;
				foreach ($this->input->post("service_id") as $service_id) {
					$print_services[$service_id] = $services[$service_id];
					$print_services_price_total += $services_price[$service_id];
				}

				$this->data["print_services"] = $print_services;
				$this->data["print_services_price_total"] = $print_services_price_total;
				$print_total += $print_services_price_total;
			}

			$this->data["print_total"] = $print_total;


			// display the create user form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			if(!empty($this->data['message']))
			{
				$was_validated = "was-validated";
			}
			$this->data["was_validated"] = $was_validated;

			$this->data["payment_type_options"] = $this->payment_types_model->get_payment_types();

			$this->data['first_name'] = [
				'name'  => 'first_name',
				'id'    => 'first_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('first_name'),
				"class" => "form-control",
				"required" => ""
			];
			$this->data['last_name'] = [
				'name' => 'last_name',
				'id' => 'last_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('last_name'),
				"class" => "form-control",
				"required" => ""
			];
			$this->data['surname'] = [
				'name' => 'surname',
				'id' => 'surname',
				'type' => 'text',
				'value' => $this->form_validation->set_value('surname'),
				"class" => "form-control"
			];
			$this->data['email'] = [
				'name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email'),
				'class' => 'form-control'
			];
			$this->data['dob'] = [
				'name' => 'dob',
				'id' => 'dob',
				'type' => 'text',
				'value' => $this->form_validation->set_value('dob'),
				'class' => 'form-control',
			];
			$this->data['address'] = [
				'name' => 'address',
				'id' => 'address',
				'type' => 'text',
				'value' => $this->form_validation->set_value('address'),
				'class' => 'form-control'
			];
			$this->data['region'] = [
				'name' => 'region_id',
				'id' => 'region_id',
				'type' => 'text',
				'value' => $this->form_validation->set_value('region_id'),
				'class' => 'custom-select',
				"required" => "",
				"data-url" => site_url("admin/doctors/ajax_get_cities")
			];
			$this->data['city'] = [
				'name'  => 'city_id',
				'id'    => 'city_id',
				'value' => $this->form_validation->set_select('city_id'),
				'class' => 'select! custom-select',
				"required" => ""
			];
			$this->data['phone'] = [
				'name' => 'phone',
				'id' => 'phone',
				'type' => 'text',
				'value' => $this->form_validation->set_value('phone'),
				'class' => 'form-control',
			];
			$this->data['partner_id'] = [
				'name' => 'partner_id',
				'id' => 'partner_id',
				'value' => $this->form_validation->set_value('partner_id'),
				'class' => 'select',
			];
			$this->data['description'] = [
				'name' => 'description',
				'id' => 'description',
				'type' => 'textarea',
				'value' => $this->form_validation->set_value('description'),
				'rows' => 5,
				'cols' => 30,
				'class' => 'form-control'
			];

			$this->data['doctors'] = [
				'name' => 'doctor_id[]',
				'id' => 'doctor_id',
				'type' => 'text',
				'value' => $this->form_validation->set_value('doctor_id[]'),
				'class' => 'form-control js_doctor_price',
				"required" => ""
			];
			$this->data['laboratory'] = [
				'name' => 'laboratory_id[]',
				'id' => 'laboratory_id',
				'type' => 'text',
				'value' => $this->form_validation->set_value('laboratory_id[]'),
				'class' => 'my-select form-control',
				"required" => ""
			];
			$this->data['uzi'] = [
				'name' => 'uzi_id[]',
				'id' => 'uzi_id',
				'type' => 'text',
				'value' => $this->form_validation->set_value('uzi_id[]'),
				'class' => 'my-select form-control',
				"required" => ""
			];
			$this->data['paid'] = [
				'name' => 'paid',
				'id' => 'paid',
				'type' => 'text',
				'value' => $this->form_validation->set_value('paid', 0),
				'class' => 'form-control form-control-paid',
			];
			$this->data['total_sum'] = [
				'name' => 'total',
				'id' => 'total',
				'type' => 'text',
				'value' => $this->form_validation->set_value('total', 0),
				'class' => 'form-control form-control-paid',
				"readonly" => "readonly"
			];
			$this->data['debt'] = [
				'name' => 'debt',
				'id' => 'debt',
				'type' => 'text',
				'value' => $this->form_validation->set_value('debt', 0),
				'class' => 'form-control form-control-paid',
				"readonly" => "readonly"
			];
			$this->data['discount_type'] = [
				'name' => 'discount_type',
				'id' => 'discount_type',
				'value' => $this->form_validation->set_value('discount_type'),
				'class' => 'select js_discount_type',
			];
			$this->data['discount_value'] = [
				'name' => 'discount_value',
				'id' => 'discount_value',
				'type' => 'text',
				'value' => $this->form_validation->set_value('discount_value', 0),
				'class' => 'form-control form-control-paid js_discount_value',
			];

			$this->data['by_cash'] = [
				'name' => 'by_cash',
				'id' => 'by_cash',
				'type' => 'text',
				'value' => $this->form_validation->set_value('by_cash'),
				'class' => 'form-control form-control-paid',
				'placeholder' => 'Нақд',
				"readonly" => "readonly"
			];
			$this->data['by_card'] = [
				'name' => 'by_card',
				'id' => 'by_card',
				'type' => 'text',
				'value' => $this->form_validation->set_value('by_card'),
				'class' => 'form-control form-control-paid',
				'placeholder' => 'Пластик',
			];
			$this->data['by_bank'] = [
				'name' => 'by_bank',
				'id' => 'by_bank',
				'type' => 'text',
				'value' => $this->form_validation->set_value('by_bank'),
				'class' => 'form-control form-control-paid',
				'placeholder' => 'Терминал',
			];

			$this->render("admin/registry/add_view");
		}
	}

    public function edit($id, $page = null) {
        $this->data["title"] = "Бемор тахрирлаш";
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/js/lou_multi_select/multi-select.css").'">
        ';

        $this->data['before_appjs'] = '
        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/lou_multi_select/jquery.multi-select.js").'"></script>
                                        ';

        if($page == "archive") {
            $page = "archive_patients";
        } if($page == "credit") {
            $page = "credit_patients";
        }

        $this->data["page"] = $page;

        $patient_id = $id;
        $patient    = $this->patients_model->get_patient($patient_id);
        $user_id    = $patient["user_id"];
        $this->data["patient"] = $patient;

        //Viloyatlar
        $this->data['regions'] = $this->regions_model->get_regions_array();

        //Shaxarlar
        $this->data['cities'] = $this->cities_model->get_cities_by_region_id($patient["region_id"]);

        $was_validated = "";
        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;

        $senders = array("" => "Танлаш");
        foreach ($this->partners_model->get_partners() as $sender) {
            $senders[$sender["id"]] = $sender["last_name"] ." ". $sender["first_name"];
        }

        $this->data["senders"] = $senders;
        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        $this->form_validation->set_rules('gender', $this->lang->line('create_user_validation_gender'), 'trim');
        if ($identity_column !== 'email') {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|valid_email');
        } else {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('dob', $this->lang->line('create_user_validation_department_label'), 'trim');

        if ($this->form_validation->run() === TRUE)
        {
            strtolower($this->input->post('email'));
            $additional_data = [
                'first_name'    => $this->input->post('first_name'),
                'last_name'     => $this->input->post('last_name'),
                'surname'       => $this->input->post('surname'),
                'dob'           => (empty($this->input->post('dob')) ? NULL:$this->input->post('dob')."-01-01"),
                'gender'        => $this->input->post('gender'),
                'address'       => $this->input->post('address'),
                'region_id'     => $this->input->post('region_id'),
                'city_id'       => $this->input->post('city_id'),
                'phone'         => (empty($this->input->post('phone')) ? NULL:$this->input->post('phone')),
                'photo'         => $this->input->post('photo'),
                'description'   => $this->input->post('description'),
                'active'        => $this->input->post('user_status'),
            ];

            //bemorni yangilash Users table
            $this->ion_auth->update($user_id, $additional_data);

            //bemorni yangilash Patients table
//            $this->patients_model->update($patient_id, array('partner_id' => $this->input->post('partner_id')));

            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("admin/registry/".$page, 'refresh');

        }
        else
        {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            if(!empty($this->data['message'])) {
                $was_validated = "was-validated";
            }
            $this->data["was_validated"] = $was_validated;
            $this->data['first_name'] = [
                'name'  => 'first_name',
                'id'    => 'first_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('first_name', $patient["first_name"]),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['last_name'] = [
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name', $patient["last_name"]),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['surname'] = [
                'name' => 'surname',
                'id' => 'surname',
                'type' => 'text',
                'value' => $this->form_validation->set_value('surname', $patient["surname"]),
                "class" => "form-control"
            ];
            $this->data['email'] = [
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email', $patient["email"]),
                'class' => 'form-control'
            ];
            $this->data['dob'] = [
                'name' => 'dob',
                'id' => 'dob',
                'type' => 'text',
                'value' => $this->form_validation->set_value('dob', date("Y", strtotime($patient["dob"]))),
                'class' => 'form-control',
            ];
            $this->data['address'] = [
                'name' => 'address',
                'id' => 'address',
                'type' => 'text',
                'value' => $this->form_validation->set_value('address', $patient["address"]),
                'class' => 'form-control'
            ];
            $this->data['region'] = [
                'name' => 'region_id',
                'id' => 'region_id',
                'type' => 'text',
                'value' => $this->form_validation->set_value('region_id', $patient["region_id"]),
                'class' => 'custom-select',
                "required" => "required",
                "data-url" => site_url("admin/doctors/ajax_get_cities")

            ];
            $this->data['city'] = [
                'name'  => 'city_id',
                'id'    => 'city_id',
                'value' => $this->form_validation->set_value('city_id', $patient["city_id"]),
                'class' => 'custom-select',
                "required" => ""
            ];
            $this->data['phone'] = [
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone', $patient["phone"]),
                'class' => 'form-control',
            ];
            $this->data['description'] = [
                'name' => 'description',
                'id' => 'description',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('description', $patient["description"]),
                'rows' => 5,
                'cols' => 30,
                'class' => 'form-control'
            ];

            $this->render("admin/registry/edit_view");
        }
    }

    public function get_doctor_price()
    {
        $type = $this->input->post("type");
        $doctor_id = $this->input->post("id");

        if($type == "doctors")
        {
            $this->load->model("doctors_model");
            $data = $this->doctors_model->get_doctor($doctor_id);
        }

        if($type == "laboratory")
        {
            $this->load->model("laboratory_model");
            $data = $this->laboratory_model->get_laboratory($doctor_id);
        }

        if($type == "uzi")
        {
            $this->load->model("uzi_model");
            $data = $this->uzi_model->get_uzi($doctor_id);
        }

        echo json_encode(array("type" => $type, "price" => $data["price"]));

    }

    public function create_new_doctors_field(){
        $docs = array(''=>"--- Танланг ---");
        foreach ($this->doctors_model->get_doctors_departments() as $doctor) {
            $docs[$doctor["doctor_department_id"]] = $doctor["last_name"] ." ". $doctor["first_name"] . " (".$doctor["department_name"].")";
        }
        $options = $docs;

        $doctors = [
            'name' => 'doctor_id[]',
            'id' => 'doctor_id',
            'type' => 'text',
            'value' => $this->form_validation->set_value('doctor_id[]'),
            'class' => 'form-control js_doctor_price',
            "required" => ""
        ];

        $html = "";
        $html .='<div class="row js_doctor_block">';
            $html .= '<div class="col-sm-12 col-md-6 col-lg-6">';
                $html .= '<div class="form-group">'.form_dropdown($doctors, $options);
        $html .= '<div class="invalid-feedback">'.form_error('doctor_id[]') .'</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-6">';
        $html .= '<div class="input-group mb-3">';
        $html .= '<div class="input-group-prepend">';
        $html .= '<span class="input-group-text js_docs_total_price" id="basic-addon1">0</span>';
        $html .= '</div>';
//        $html .= '<input type="text" placeholder="Туланган сумма" class="form-control js_doctor_payment" name="doctor_payment[]" aria-describedby="basic-addon1" value="">';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        echo json_encode($html);
    }

    public function credit_patients() {

        $this->data['title'] = 'Беморлар';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.autocomplete.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.hideseek.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/functions_patients.js?".time()).'"></script>
                                        ';

        $this->data["page"] = 'credit';
        $patients = $this->patients_list("credit");
        $this->data["patients_counts"]  = $this->patients_counts();

        $this->data["completed_patients"] = $patients["completed_patients"];
        $this->data["incomplete_patients"] = $patients["incomplete_patients"];
        $this->data["completed_patients_count"] = count($patients["completed_patients"]);
        $this->data["incomplete_patients_count"] = count($patients["incomplete_patients"]);

        $this->load->library("services");
        $service_config["form"]         = false;
        $service_config["submit_btn"]   = false;
        $this->data["services_template"]= $this->services->generate($service_config);
        
        /* ***************************************************************************** */

//        $items = $this->items_list();
//        $this->data["docs"]             = $items["docs"];
//        $this->data["docs_price"]       = $items["docs_price"];
//        $this->data["laboratories"]     = $items["laboratories"];
//        $this->data["uzis"]             = $items["uzis"];
//        $this->data["uzis_price"]       = $items["uzis_price"];
//        $this->data["services"]         = $items["services"];
//        $this->data["services_price"]   = $items["services_price"];

        /* ***************************************************************************** */

//        $this->data["discount_options"] = array("" => "-- Танланмаган --", 1 => "Сумма", 2 => "Фоиз");
        $this->data["payment_type_options"] = $this->payment_types_model->get_payment_types();
		$expense_type_options = ["Boshqa chiqimlar"];
		foreach ($this->expense_type_model->get_expense_types() as $etype) {
			$expense_type_options[$etype["id"]] = $etype["name"];
		}
		$this->data["expense_type_options"] = $expense_type_options;

        $this->data['payment_type'] = [
            'name' => 'payment_type',
            'id' => 'payment_type',
            'type' => 'text',
            'value' => $this->form_validation->set_select('payment_type'),
            'class' => 'select select2',
        ];

        $this->render('admin/registry/credit_patients_view');
    }

    public function debitor_patients() {

        $this->data['title'] = 'Қарздорлар';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.autocomplete.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/functions_patients.js?".time()).'"></script>
                                        ';

        $this->data["page"] = 'debitor';
        $patients = $this->patients_list("debitor");
        $this->data["patients_counts"]  = $this->patients_counts();

        $this->data["debitor_patients"] = $patients;
        $this->data["completed_patients_count"] = count($patients);

        /* ***************************************************************************** */
        $items = $this->items_list();
        $this->data["docs"]             = $items["docs"];
        $this->data["docs_price"]       = $items["docs_price"];
        $this->data["laboratories"]     = $items["laboratories"];
        $this->data["uzis"]             = $items["uzis"];
        $this->data["uzis_price"]       = $items["uzis_price"];

        /* ***************************************************************************** */

        $this->data["discount_options"] = array("" => "-- Танланмаган --", 1 => "Сумма");

        $this->data['paid'] = [
            'name' => 'paid',
            'id' => 'paid',
            'type' => 'text',
            'value' => $this->form_validation->set_value('paid'),
            'class' => 'form-control form-control-paid',
        ];
        $this->data['total_sum'] = [
            'name' => 'total',
            'id' => 'total',
            'type' => 'text',
            'value' => $this->form_validation->set_value('total'),
            'class' => 'form-control form-control-paid',
            "readonly" => "readonly"
        ];
        $this->data['debt'] = [
            'name' => 'debt',
            'id' => 'debt',
            'type' => 'text',
            'value' => $this->form_validation->set_value('debt'),
            'class' => 'form-control form-control-paid',
            "readonly" => "readonly"
        ];
		$this->data['discount_type'] = [
			'name' => 'discount_type',
			'id' => 'discount_type',
			'value' => $this->form_validation->set_value('discount_type'),
			'class' => 'select js_discount_type',
		];
		$this->data['discount_value'] = [
			'name' => 'discount_value',
			'id' => 'discount_value',
			'type' => 'text',
			'value' => $this->form_validation->set_value('discount_value', 0),
			'class' => 'form-control form-control-paid js_discount_value',
		];

        $this->render('admin/registry/debitor_patients_view');
    }

    public function archive_patients() {
        $this->data['title'] = 'Беморлар';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '
        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/jquery.autocomplete.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/functions_patients.js?".time()).'"></script>
        ';

        $this->data["page"] = "archive_patients";
        $items = $this->items_list();
        $this->data["docs"]             = $items["docs"];
        $this->data["docs_price"]       = $items["docs_price"];
        $this->data["laboratories"]     = $items["laboratories"];
        $this->data["uzis"]             = $items["uzis"];
        $this->data["uzis_price"]       = $items["uzis_price"];

        $patients = $this->patients_list("archive");
        $this->data["patients"] = $patients;
        $this->data["patients_counts"]  = $this->patients_counts();

        $this->data["discount_options"] = array("" => "-- Танланмаган --", 1 => "Сумма");

        $this->data['paid'] = [
            'name' => 'paid',
            'id' => 'paid',
            'type' => 'text',
            'value' => $this->form_validation->set_value('paid'),
            'class' => 'form-control form-control-paid',
        ];
        $this->data['total_sum'] = [
            'name' => 'total',
            'id' => 'total',
            'type' => 'text',
            'value' => $this->form_validation->set_value('total'),
            'class' => 'form-control form-control-paid',
            "readonly" => "readonly"
        ];
        $this->data['debt'] = [
            'name' => 'debt',
            'id' => 'debt',
            'type' => 'text',
            'value' => $this->form_validation->set_value('debt'),
            'class' => 'form-control form-control-paid',
            "readonly" => "readonly"
        ];
		$this->data['discount_type'] = [
			'name' => 'discount_type',
			'id' => 'discount_type',
			'value' => $this->form_validation->set_value('discount_type'),
			'class' => 'select js_discount_type',
		];
		$this->data['discount_value'] = [
			'name' => 'discount_value',
			'id' => 'discount_value',
			'type' => 'text',
			'value' => $this->form_validation->set_value('discount_value', 0),
			'class' => 'form-control form-control-paid js_discount_value',
		];

        $this->render('admin/registry/archive_patients_view');
    }

	public function payments_history()
	{
		$this->data["title"] = "Тўловлар тарихи";
		$this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

		$this->data['before_appjs'] = '
        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/jquery.autocomplete.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/jquery.hideseek.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/functions_patients.js?".time()).'"></script>
        ';

        $this->data["page"] = "payments_history";
		$payments = $this->patients_payments_model->get_payments();
		$this->data["payments"] = $payments;
		$this->data["patients_counts"]  = $this->patients_counts();

		$this->data["payment_type_options"] = $this->payment_types_model->get_payment_types();
		$expense_type_options = ["Boshqa chiqimlar"];
		foreach ($this->expense_type_model->get_expense_types() as $etype) {
			$expense_type_options[$etype["id"]] = $etype["name"];
		}
		$this->data["expense_type_options"] = $expense_type_options;

		$this->load->library("services");
		$service_config["form"]         = false;
		$service_config["submit_btn"]   = false;
		$this->data["services_template"]= $this->services->generate($service_config);

		$this->render('admin/registry/payments_history_view');
    }

    /**
     * shifokor registratsionniyga yuborgan bemorlar
     *******************************************/
    public function for_payment_patients() {
        $this->data['title'] = 'Беморлар';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '
        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/jquery.autocomplete.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/jquery.hideseek.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/functions_patients.js?".time()).'"></script>
        ';

        $this->load->library("services");
        $service_config["form"]         = false;
        $service_config["submit_btn"]   = false;
        $service_config["page"]         = "for_payment_patients";
        $this->data["services_template"]= $this->services->generate($service_config);

        $this->data["page"] = "for_payment_patients";
        $items = $this->items_list();
        $this->data["docs"]             = $items["docs"];
        $this->data["docs_price"]       = $items["docs_price"];
        $this->data["laboratories"]     = $items["laboratories"];
        $this->data["uzis"]             = $items["uzis"];
        $this->data["uzis_price"]       = $items["uzis_price"];

        $patients = $this->patients_list("for_payment");
        $this->data["patients"] = $patients;
        $this->data["patients_counts"]  = $this->patients_counts();

        $this->render('admin/registry/for_payment_patients_view');
    }

    /**
     * Yangi tulov uchun
     * */
    public function add_items($patient_id) {
        $this->data["title"] = "Тўлов қўшиш";
        $this->data['before_themeStyle'] = '
                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/js/lou_multi_select/multi-select.css").'">
                                        ';
		$print_cheque = false;
        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/lou_multi_select/jquery.multi-select.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.hideseek.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/functions_patients.js?".time()).'"></script>
                                        ';

        $this->load->library("services");
        $service_config["form"]         = true;
        $service_config["submit_btn"]   = true;
        $this->data["services_template"]= $this->services->generate($service_config);

        $partners[] = "-- Танлаш --";
        foreach ($this->partners_model->get_partners() as $partner) {
            if($partner["type"] == 1) {
                $partners[$partner["id"]] = $partner["last_name"] ." ". $partner["first_name"];
            } else {
                $partners[$partner["id"]] = $partner["company"];
            }
        }
        $this->data["partners_options"] = $partners;

        $patient = $this->patients_model->get_patient($patient_id);
        $this->data["patient"] = $patient;

        //Bemorning tulovlari
        $payments = $this->patients_payments_model->get_patient_payment_by_patient($patient_id);
        $this->data["payments"] = $payments;

		$this->data["discount_options"] = array("" => "-- Танланмаган --", 1 => "Сумма", 2 => "Фоиз");

        $items = $this->items_list();
        $this->data["docs"]             = $items["docs"];
        $this->data["docs_price"]       = $items["docs_price"];
        $this->data["laboratories"]     = $items["laboratories"];
        $this->data["uzis"]             = $items["uzis"];
        $this->data["uzis_price"]       = $items["uzis_price"];
        $this->data["services"]         = $items["services"];
        $this->data["services_price"]   = $items["services_price"];

        $this->form_validation->set_rules('total', "Жами", 'trim|required');

        if ($this->form_validation->run() === TRUE) {
			$print_cheque = is_null($this->input->post("print_cheque")) ? false:true;
			unset($_POST["print_cheque"]);

            //tulov qushish
            if(!empty($this->input->post("doctor_id")[0]) || !empty($this->input->post('laboratory_id')) || !empty($this->input->post('uzi_id')) || !empty($this->input->post('service_id'))) {
				$discount_type 	= $this->input->post("discount_type");
				$discount_value = $this->input->post("discount_value");
				$total 			= $this->input->post("total");
				$discount 		= $this->get_discount($discount_type, $discount_value, $total);

				$payment_arr = array(
					"doctor_status"     => (empty($this->input->post("doctor_id")[0]) ? 0:1),
					"laboratory_status" => (empty($this->input->post('laboratory_id')) ? 0:1),
					"uzi_status"        => (empty($this->input->post('uzi_id')) ? 0:1),
					"service_status"    => (empty($this->input->post('service_id')) ? 0:1),
					"room_status"       => 0,
					"order_status"      => 0,
					"patient_id"        => $patient_id,

					"discount_type"     => $discount_type,
					"discount_value"    => $discount_value,
					"discount"	    	=> $discount,
					"total"             => $total,
					"status"            => 0,
					'doctor_id'        => $this->input->post('sender_doctor_id'),
					'partner_id'        => $this->input->post('partner_id'),
                );

				$payment_id = $this->patients_payments_model->add($payment_arr);


				$payment_details_arr = array(
					'payment_id'      	=> $payment_id,
					"paid"              => $this->input->post("paid"),
					'by_cash'      		=> $this->input->post('by_cash'),
					'by_card'      		=> $this->input->post('by_card'),
					'by_bank'      		=> $this->input->post('by_bank'),
				);
				$this->patients_payments_details_model->add($payment_details_arr);
            }

            //agar tulov bajarilgan bulsa
            if(isset($payment_id)) {
                //doctorlar
                if(!empty($this->input->post("doctor_id")[0])) {
                    foreach ($this->input->post('doctor_id') as $index => $doctor_id) {
                        $doc_arr = array(
                            "patient_id" 	=> $patient_id,
                            "doctor_id" 	=> $doctor_id,
                            "payment_id" 	=> $payment_id,
                            "status" 		=> 0,
							"count" 		=> $this->input->post('doc_count')[$doctor_id]
                        );
                        $this->patient_doctor_model->add($doc_arr);
                    }
                }

                //laboratoriyalar
				if(!empty($this->input->post('laboratory_id'))) {
					foreach ($this->input->post('laboratory_id') as $laboratory_id) {
						$lab_value = $this->laboratory_model->get_laboratory($laboratory_id);
						$lab_arr = array(
							"patient_id"    => $patient_id,
							"lab_id"        => $laboratory_id,
							"payment_id"    => $payment_id,
							"result"        => $lab_value["default_value"],
							"status"        => 0,
							"recommendation"=> 0,
							"is_parent"     => 1,
							"parent_id"     => null,
							"count" 		=> $this->input->post('lab_count')[$laboratory_id]
						);

						$this->patient_laboratories_model->add($lab_arr);

						$laboratory = $this->laboratory_model->sub_categories($laboratory_id);
						if(count($laboratory) > 0) {
							foreach ($laboratory as $sublab) {
								//agar $sublabning sub laboratoriyasi bulsa
								$is_parent = empty($sublab["sub"]) ? 0:2;
								$lab_arr = array(
									"patient_id"    => $patient_id,
									"lab_id"        => $sublab["id"],
									"payment_id"    => $payment_id,
									"result"        => $sublab["default_value"],
									"status"        => 0,
									"recommendation"=> 0,
									"is_parent"     => $is_parent,
									"parent_id"     => $laboratory_id,
									"count" 		=> null
								);

								$this->patient_laboratories_model->add($lab_arr);

								if(!empty($sublab["sub"])) {
									foreach ($sublab["sub"] as $sub_sub_lab) {
										$lab_arr = array(
											"patient_id"    => $patient_id,
											"lab_id"        => $sub_sub_lab["id"],
											"result"        => $sub_sub_lab["default_value"],
											"payment_id"    => $payment_id,
											"status"        => 0,
											"recommendation"=> 0,
											"is_parent"     => 0,
											"parent_id"     => $sublab["id"],
											"count" 		=> null
										);
										$this->patient_laboratories_model->add($lab_arr);
									}
								}
							}
						}
					}
				}

                //uzilar
                if(isset($payment_id) && !empty($this->input->post('uzi_id'))) {
                    foreach ($this->input->post('uzi_id') as $uzi_id) {
                        $uzi_arr = array(
                            "patient_id" 	=> $patient_id,
                            "uzi_id" 		=> $uzi_id,
                            "payment_id" 	=> $payment_id,
                            "status" 		=> 0,
							"count" 		=> $this->input->post('uzi_count')[$uzi_id],
							"is_conclusion"	=> 0
                        );
                        $this->patient_uzi_model->add($uzi_arr);
                    }

					//xulosa uchun bitta qator qushib quyamiz
					$uzi_conclusion = array(
						"patient_id" 	=> $patient_id,
						"uzi_id" 		=> null,
						"payment_id" 	=> $payment_id,
						"status" 		=> 0,
						"count" 		=> 1,
						"is_conclusion"	=> 1
					);

					$this->patient_uzi_model->add($uzi_conclusion);
                }

                if(isset($payment_id) && !empty($this->input->post('service_id'))) {
                    foreach ($this->input->post('service_id') as $service_id) {
                        $service_arr = array(
                            "patient_id" => $patient_id,
                            "service_id" => $service_id,
                            "payment_id" => $payment_id,
                            "status" 	=> 0,
							"count" 	=> $this->input->post('service_count')[$service_id]
                        );
                        $this->patient_service_model->add($service_arr);
                    }
                }

                //Check chiqaramiz
                $this->load->helper(array("lab_form", 'printer'));
                $pr = print_receipt($payment_id);

                if(pos_print() && $print_cheque) {
                    try {
                        $this->load->library('ReceiptPrint');
                        $user = $this->ion_auth->user()->row();
                        $this->receiptprint->connect($this->config->item("pos_printer_name"));
                        $this->receiptprint->print_receipt($pr["patient_data"], $pr["payment_data"], $pr["doctor_items"], $pr["laboratory_items"], $pr["uzi_items"], $pr["service_items"], $user);
                    } catch (Exception $e) {
                        log_message("error", "Error: Could not print. Message ".$e->getMessage());
                        $this->receiptprint->close_after_exception();
                    }
                }
            }

            redirect("admin/registry", 'refresh');
        } else {

            $this->data['message'] = validation_errors();
            $this->data["payment_type_options"] = $this->payment_types_model->get_payment_types();

            $this->data['partner_id'] = [
                'name'  => 'partner_id',
                'id'    => 'partner_id',
                'value' => $this->form_validation->set_value('partner_id'),
                'class' => 'select select2',
            ];
			$this->data['sender_doctor_id'] = [
				'name' 	=> 'sender_doctor_id',
				'id' 	=> 'sender_doctor_id',
				'value' => $this->form_validation->set_value('sender_doctor_id'),
				'class' => 'select select2_search',
			];
            $this->data['description'] = [
                'name' => 'description',
                'id'   => 'description',
                'type' => 'textarea',
                'value'=> $this->form_validation->set_value('description'),
                'rows' => 5,
                'cols' => 30,
                'class'=> 'form-control'
            ];
            $this->data['doctors'] = [
                'name'  => 'doctor_id[]',
                'id'    => 'doctor_id',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('doctor_id[]'),
                'class' => 'form-control js_doctor_price',
                "required" => ""
            ];

            $this->data['laboratory'] = [
                'name'  => 'laboratory_id[]',
                'id'    => 'laboratory_id',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('laboratory_id[]'),
                'class' => 'my-select form-control',
                "required" => ""
            ];

            $this->data['uzi'] = [
                'name' 	=> 'uzi_id[]',
                'id' 	=> 'uzi_id',
                'type' 	=> 'text',
                'value' => $this->form_validation->set_value('uzi_id[]'),
                'class' => 'my-select form-control',
                "required" => ""
            ];
            $this->data['paid'] = [
                'name' 	=> 'paid',
                'id' 	=> 'paid',
                'type' 	=> 'text',
                'value' => $this->form_validation->set_value('paid'),
                'class' => 'form-control form-control-paid',
            ];
            $this->data['total_sum'] = [
                'name' 	=> 'total',
                'id' 	=> 'total',
                'type' 	=> 'text',
                'value' => $this->form_validation->set_value('total'),
                'class' => 'form-control form-control-paid',
                "readonly" => "readonly"
            ];
            $this->data['debt'] = [
                'name' => 'debt',
                'id' => 'debt',
                'type' => 'text',
                'value' => $this->form_validation->set_value('debt'),
                'class' => 'form-control form-control-paid',
                "readonly" => "readonly"
            ];
			$this->data['discount_type'] = [
				'name' => 'discount_type',
				'id' => 'discount_type',
				'value' => $this->form_validation->set_value('discount_type'),
				'class' => 'select js_discount_type',
			];
			$this->data['discount_value'] = [
				'name' => 'discount_value',
				'id' => 'discount_value',
				'type' => 'text',
				'value' => $this->form_validation->set_value('discount_value', 0),
				'class' => 'form-control form-control-paid js_discount_value',
			];

			$this->data['by_cash'] = [
				'name' => 'by_cash',
				'id' => 'by_cash',
				'type' => 'text',
				'value' => $this->form_validation->set_value('by_cash'),
				'class' => 'form-control form-control-paid',
				'placeholder' => 'Нақд',
				"readonly" => "readonly"
			];
			$this->data['by_card'] = [
				'name' => 'by_card',
				'id' => 'by_card',
				'type' => 'text',
				'value' => $this->form_validation->set_value('by_card'),
				'class' => 'form-control form-control-paid',
				'placeholder' => 'Пластик',
			];
			$this->data['by_bank'] = [
				'name' => 'by_bank',
				'id' => 'by_bank',
				'type' => 'text',
				'value' => $this->form_validation->set_value('by_bank'),
				'class' => 'form-control form-control-paid',
				'placeholder' => 'Терминал',
			];

		}

        $this->render("admin/registry/add_items_view");
    }

    private function patients_list($day_type) {
        $patients = array();
        if($day_type == "today") {
            $patients = $this->patients_model->get_paid_patients_by_date(date("Y-m-d"));
        } elseif ($day_type == "archive") {
            $patients = $this->patients_model->get_patients_archive();
        } elseif ($day_type == "credit") {
            $patients = $this->patients_model->get_paid_patients_credit();
        } elseif ($day_type == "debitor") {
            $patients = $this->patients_model->get_debitor_patients();
        } elseif ($day_type == "for_payment") {
            $patients = $this->patients_model->get_for_payment_patients();
        }

        if($day_type != "archive" && $day_type != "debitor" && $day_type != "for_payment") {
            $incomplete_patients = array();
            $completed_patients = array();
            foreach ($patients as $patient) {
                if($patient["status"] == 1) {
                    $completed_patients[$patient["payment_id"]] = $patient;
                } else {
                    $incomplete_patients[$patient["payment_id"]] = $patient;
                }
            }

            $result = array("completed_patients" => $completed_patients, "incomplete_patients" => $incomplete_patients);
        } else {
            $result = $patients;
        }


        return $result;
    }

    private function patients_counts() {
        $today_count 	= $this->patients_model->get_paid_patients_by_date(date("Y-m-d"), true);
        $credit_count 	= $this->patients_model->get_paid_patients_credit(true);
        $debitor_count 	= $this->patients_model->get_debitor_patients(true);
        $for_payment_count = $this->patients_model->get_for_payment_patients(true);

        $counts = array("today_count" => $today_count, "credit_count" => $credit_count, "debitor_count" => $debitor_count, "for_payment_count" => $for_payment_count,);

        return $counts;
    }

    public function ajax_selected_items() {

        $payment_id         = $this->input->post("payment_id");
        $table_type  		= $this->input->post("table_type");
        $selected_doctors   = $selected_uzi = $selected_services = $selected_labs = $selected_rooms = array();
		$hide_services_col  = true;
        //qarz tulov qilindimi yuqmi
        $debt_off_sum = $this->payments_debt_discount_model->get_debt_off_sum($payment_id);
        if(is_null($debt_off_sum["amount"]) && $table_type == 'incompleted') {
        	$hide_services_col = false;
		}

        $payment 			= $this->patients_payments_model->get_patient_payment($payment_id);
        $patient 			= $this->patients_model->get_patient($payment["patient_id"]);

        if(!$payment["room_status"]) {
            $selected_doctors   = $this->patient_doctor_model->get_patient_doctor($payment_id);
            $selected_uzi       = $this->patient_uzi_model->get_patient_uzi($payment_id);
            $selected_services  = $this->patient_service_model->get_patient_service($payment_id);

            $laboratories      	= $this->patient_laboratories_model->get_patient_laboratories_details($payment_id, 1);
            $selected_labs     	= $this->formatting_selected_laboratories($laboratories);
            $selected_labs 		= (array) $selected_labs;
        } else {
            $selected_rooms["this_room"] = $this->patient_room_model->get_bed_by_payment($payment_id);
            $selected_rooms["selected_rooms"] = $this->patient_room_model->get_busy_beds();
        }

        echo json_encode(array("payment_id" => $payment_id, "doctors" => $selected_doctors, "labs" => $selected_labs, "uzis" => $selected_uzi, "services" => $selected_services, "rooms" => $selected_rooms, "payments" => $payment, "patient" => $patient, "hide_services_col" => $hide_services_col));
    }

    private function formatting_selected_laboratories($selected_laboratories)
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

                    $selected_labs[$lab_id]["name"] = $labs["name"];
                    $selected_labs[$lab_id]["price"] = $labs["price"];
                    $selected_labs[$lab_id]["lab_id"] = $lab_id;
                    $selected_labs[$lab_id]["status"] = $status;
                    $selected_labs[$lab_id]["count"] = $labs["count"];
                } else {
                    $selected_labs[$lab_id]["name"] = $labs["name"];
                    $selected_labs[$lab_id]["price"] = $labs["price"];
                    $selected_labs[$lab_id]["lab_id"] = $labs["lab_id"];
                    $selected_labs[$lab_id]["status"] = $labs["status"];
					$selected_labs[$lab_id]["count"] = $labs["count"];
                }
            }
        }

        return $selected_labs;
    }

    public function ajax_update_selected_items() {

        if ($this->input->is_ajax_request()) {

            $payment_details = $this->input->post("selected_items");
            $pdata = $this->patients_payments_model->get_patient_payment($payment_details["payment_id"]);

            $payment = array();
            if(isset($payment_details["doctors"]) && $pdata["doctor_status"] == 0)    {$payment["doctor_status"]      = 1;}
            elseif (!isset($payment_details["doctors"]))                              {$payment["doctor_status"]      = 0;}

            if(isset($payment_details["labs"]) && $pdata["laboratory_status"] == 0)  {$payment["laboratory_status"]  = 1;}
            elseif (!isset($payment_details["labs"]))                                {$payment["laboratory_status"]  = 0;}

            if(isset($payment_details["uzis"]) && $pdata["uzi_status"] == 0)         {$payment["uzi_status"]         = 1;}
            elseif (!isset($payment_details["uzis"]))                                {$payment["uzi_status"]         = 0;}

            if(isset($payment_details["services"]) && $pdata["service_status"] == 0) {$payment["service_status"]     = 1;}
            elseif (!isset($payment_details["services"]))                            {$payment["service_status"]     = 0;}

            if(isset($payment_details["beds_id"]) && $pdata["room_status"] == 0)        {$payment["room_status"]        = 1;}
            elseif (!isset($payment_details["beds_id"]))                                {$payment["room_status"]        = 0;}

			$total 				= $payment_details["total"];
			$discount_type		= $payment_details["discount_type"];
			$discount_value 	= $payment_details["discount_value"];
            $discount 			= $this->get_discount($discount_type, $discount_value, $total);;

            $patient_id                 = $payment_details["patient_id"];
            $payment_id                 = $payment_details["payment_id"];

            $payment["total"]           = $total;

            $payment["discount_type"]   = $discount_type;
            $payment["discount_value"]  = $discount_value;
            $payment["discount"]  		= $discount;
            $payment["room_status"]     = (isset($payment_details["beds_id"]) ? 1:0);
			$payment["partner_id"]		= $payment_details["partner_id"];
			$payment["doctor_id"]		= $payment_details["sender_doctor_id"];

            //1. update patients_payments and patients_payments_details tables
            $payment_update_status 	= $this->patients_payments_model->update($payment_id, $payment);
			$payment_debt_off_sum 	= $this->payments_debt_discount_model->get_debt_off_sum($payment_id);
            $patients_payments_details["paid"]    = $payment_details["paid"] + $payment_debt_off_sum["amount"];
            $patients_payments_details["by_cash"] = $payment_details["by_cash"] + $payment_debt_off_sum["amount"];
            $patients_payments_details["by_card"] = $payment_details["by_card"];
            $patients_payments_details["by_bank"] = $payment_details["by_bank"];

            $this->patients_payments_details_model->update($payment_id, $patients_payments_details);

			/** agar qarz uzgarsa **/
//			$payment_debt = $this->payments_debt_discount_model->get_debt_sum($payment_id);
//			if($payment_debt != ($total - $discount)) {
//				$this->payments_debt_discount_model->delete_by_type($payment_id, 1);
//			}

			/** agar chegirma uzgarsa **/
			$payment_discount = $this->payments_debt_discount_model->get_discount_sum($payment_id);
			if($payment_discount != $discount) {
				$this->payments_debt_discount_model->delete_by_type($payment_id, 2);
			}

			//2. doctorlar tanlangan bulsa ularni uchiradiganini uchirib, qoladiganini qoldiramiz
            $selected_doctors = false;
            if(!isset($payment_details["doctors"])) {
                //2.1 patient_doctor payment_id buyicha hamma doktorlarni uchirib tashlaymiz
                $this->patient_doctor_model->delete_by_paymentId($payment_id);
            } else {
                $this->doctors_actions($payment_details["doctors"], $payment_id, $patient_id);
                $selected_doctors = true;
            }

            //3. laboratoriyalar tanlangan bulsa ularni uchiradiganini uchirib, qoladiganini qoldiramiz
            $selected_laboratories = false;
            if(!isset($payment_details["labs"])) {
                //3.1 patient_laboratories tableda payment_id buyicha hamma laboratoriyalarni uchirib tashlaymiz
                $this->patient_laboratories_model->delete_by_paymentId($payment_id);
            } else {
                $this->laboratories_actions($payment_details["labs"], $payment_id, $patient_id);
                $selected_laboratories = true;
            }

            //4. uzilar tanlangan bulsa ularni uchiradiganini uchirib, qoladiganini qoldiramiz
            $selected_uzis = false;
            if(!isset($payment_details["uzis"])) {
                //4.1 patient_uzi tableda payment_id buyicha hamma uzilarni uchirib tashlaymiz
                $this->patient_uzi_model->delete_by_paymentId($payment_id);
            } else {
                $this->uzis_actions($payment_details["uzis"], $payment_id, $patient_id);
                $selected_uzis = true;
            }

            //5. servicelar tanlangan bulsa ularni uchiradiganini uchirib, qoladiganini qoldiramiz
            $selected_services = false;
            if(!isset($payment_details["services"])) {
                //5.1 patient_service tableda payment_id buyicha hamma uzilarni uchirib tashlaymiz
                $this->patient_service_model->delete_by_paymentId($payment_id);
            } else {
                $this->services_actions($payment_details["services"], $payment_id, $patient_id);
                $selected_services = true;
            }

            $selected_rooms = false;
            if(isset($payment_details["beds_id"])) {
				//agar yotoqdagi bermorni tulab bulgan qarzlarining summasi hozirgi tulayotgan summasidan kichik bulsa
				//(masalan: koykani 10 kunga olib 8 kunga tulov qilib quygan bulsa, lekin 5 kunda chiqib ketsa)
				$debt = $payment_details["total"] - $payment_details["paid"];
				$this->patients_payments_details_model->delete_by_payment_id($payment_id);
				$patients_payments_details["payment_id"] = $payment_id;
				$this->patients_payments_details_model->add($patients_payments_details);
				unset($patients_payments_details["payment_id"]);

				//qarzi yoki chegirmasi bulsa qushib quyamiz
				$this->payments_debt_discount_model->delete_by_payment_id($payment_id);
				if($debt > 0) {
					$arr = array(
						"type"          => 1,
						"payment_id"    => $payment_id,
						"service_type"  => 5,
						"doctor_id"     => null,
						"amount"        => $debt,
						"debt_off_type" => 0,
						"created_date"	=> $pdata["created_date"]
					);

					$this->payments_debt_discount_model->add($arr);
				}

				if($discount > 0) {
					$arr = array(
						"type"          => 2,
						"payment_id"    => $payment_id,
						"service_type"  => 5,
						"doctor_id"     => null,
						"amount"        => $discount,
						"debt_off_type" => 0,
						"created_date"	=> $pdata["created_date"]
					);

					$this->payments_debt_discount_model->add($arr);
				}

                $this->patient_room_model->update_by_payment_id(
                    $payment_id,
                    array(
                        "bed_id"        => $payment_details["beds_id"][0],
                        "start_date"    => date_formating(strtotime($payment_details["room_start_date"]), "db_datetime"),
                        "end_date"      => date("Y-m-d 11:00:00", strtotime($payment_details["room_end_date"]))
                    )
                );
                $selected_rooms = true;
            }

            $payment_status = $this->patients_payments_model->check_payment_status($payment_id);
            if($payment_status == 'completed') {
                $this->patients_payments_model->update($payment_id, array("status" => 1, "order_status" => 4));
            }

            $result = false;
            if(!$selected_doctors && !$selected_laboratories && !$selected_uzis && !$selected_services && !$selected_rooms) {
                $result = $this->patients_payments_model->delete($payment_id);
            }

            $print_cheque = $this->input->post("print_cheque");
            if(pos_print() && ($print_cheque == "print")) {
                try {
                    $this->load->helper(array("lab_form"));
                    $pr 	= print_receipt($payment_id);
                    $user 	= $this->ion_auth->user()->row();
                    $this->load->library('ReceiptPrint');
                    $this->receiptprint->connect($this->config->item("pos_printer_name"));
                    $this->receiptprint->print_receipt($pr["patient_data"], $pr["payment_data"], $pr["doctor_items"], $pr["laboratory_items"], $pr["uzi_items"], $pr["service_items"], $user);
                } catch (Exception $e) {
                    log_message("error", "Error: Could not print. Message ".$e->getMessage());
                    $this->receiptprint->close_after_exception();
                }
            }

			$payment = $this->patients_payments_model->get_patient_payment($payment_id);

            echo json_encode(array("result" => $result, 'payment_status' => $payment_status, 'payment' => $payment));
        }
    }

    /**
     * Tanlangan yoki tanlanmagan doctorlar ustida amallar
     *
     * @param $doctors array
     * @param $payment_id int
     * @param $patient_id int
     * @return mixed
     */
    private function doctors_actions($doctors, $payment_id, $patient_id) {

        //patient_doctor tabledan keraksiz doktorlarni uchirib tashlaymiz
        //2.2 payment id buyicha bazada bor doctorlarni tekshiramiz
        $existed_doctors = array();
        foreach ($this->patient_doctor_model->get_patient_doctor($payment_id) as $e_doc) {
            $existed_doctors[] = $e_doc["doctor_id"];
        }

        //2.3 agar doctorlar bulsa, uchirilishi mumkin bulmaganlarini va bazaga kiritilishi kerak bulganlarini ajratib olamiz.
        $doctors_id_for_nodelete    = array();
        $doctors_id_for_insert      = array();
        if(count($existed_doctors) > 0) {
            foreach ($doctors as $doctor) {
                if(in_array($doctor["id"], $existed_doctors)) {
                    $doctors_id_for_nodelete[] = $doctor;
                } else {
                    $doctors_id_for_insert[] = $doctor;
                }
            }
        } else {
            $doctors_id_for_insert = $doctors;
        }

        if(count($doctors_id_for_nodelete) > 0) {
            $this->patient_doctor_model->delete_not_selected($payment_id, $doctors_id_for_nodelete);
        } else {
            $this->patient_doctor_model->delete_by_paymentId($payment_id);
        }

        //2.4 doctorlarni bazaga kiritamiz
        if(count($doctors_id_for_insert) > 0) {
            foreach ($doctors_id_for_insert as $doctor_for_insert) {
                $arr = array(
                    "patient_id" => $patient_id,
                    "payment_id" => $payment_id,
                    "doctor_id"  => $doctor_for_insert["id"],
                    "status" 	 => 0,
					"count"		 => $doctor_for_insert["count"],
                );

                $this->patient_doctor_model->add($arr);
            }
        }
    }

    /**
     * Tanlangan yoki tanlanmagan laboratoriyalar ustida amallar
     *
     * @param $laboratories_id array
     * @param $payment_id int
     * @param $patient_id int
     * @return mixed
     */
    private function laboratories_actions($laboratories, $payment_id, $patient_id) {

        //patient_$laboratories tabledan keraksiz laboratoriyalarni uchirib tashlaymiz
        //3.2 payment id buyicha bazada bor laboratoriyalarni tekshiramiz
        $existed_laboratories = array();
        foreach ($this->patient_laboratories_model->get_laboratories_by_payment($payment_id) as $laboratory) {
            $existed_laboratories[] = $laboratory["lab_id"];
        }

        //3.3 agar laboratoriyalar bulsa, uchirilishi mumkin bulmaganlarini va bazaga kiritilishi kerak bulganlarini ajratib olamiz.
        $laboratories_id_for_nodelete    = array();
        $laboratories_id_for_insert      = array();
        if(count($existed_laboratories) > 0) {
            foreach ($laboratories as $lab) {
                if(in_array($lab["id"], $existed_laboratories)) {
                    $laboratories_id_for_nodelete[] = $lab;
                } else {
                    $laboratories_id_for_insert[] = $lab;
                }
            }
        } else {
            $laboratories_id_for_insert = $laboratories;
        }

        if(count($laboratories_id_for_nodelete) > 0) {
            $this->patient_laboratories_model->delete_not_selected($payment_id, $laboratories_id_for_nodelete);
        } else {
            $this->patient_laboratories_model->delete_by_paymentId($payment_id);
        }

        //3.4 laboratoriyalarni bazaga kiritamiz
        if(count($laboratories_id_for_insert) > 0) {
            foreach ($laboratories_id_for_insert as $laboratory_for_insert) {
                $lab_value = $this->laboratory_model->get_laboratory($laboratory_for_insert["id"]);
                $lab_arr = array(
                    "patient_id"    => $patient_id,
                    "lab_id"        => $laboratory_for_insert["id"],
                    "payment_id"    => $payment_id,
                    "result"        => $lab_value["default_value"],
                    "status"        => 0,
                    "recommendation"=> 0,
                    "is_parent"     => 1,
					"count"			=> $laboratory_for_insert["count"]
                );

                $this->patient_laboratories_model->add($lab_arr);

                $laboratory = $this->laboratory_model->sub_categories($laboratory_for_insert["id"]);
                if(count($laboratory) > 0) {
                    foreach ($laboratory as $slab) {
						//agar $sublabning sub laboratoriyasi bulsa
						$is_parent = empty($slab["sub"]) ? 0:2;
                        $lab_arr = array(
                            "patient_id"    => $patient_id,
                            "lab_id"        => $slab["id"],
                            "payment_id"    => $payment_id,
                            "result"        => $lab_value["default_value"],
                            "status"        => 0,
                            "recommendation"=> 0,
                            "is_parent"     => $is_parent,
                            "parent_id"     => $slab["parent_id"],
							"count"			=> null
                        );
                        $this->patient_laboratories_model->add($lab_arr);

						if(!empty($slab["sub"])) {
							foreach ($slab["sub"] as $sub_sub_lab) {
								$lab_arr = array(
									"patient_id"    => $patient_id,
									"lab_id"        => $sub_sub_lab["id"],
									"result"        => $sub_sub_lab["default_value"],
									"payment_id"    => $payment_id,
									"status"        => 0,
									"recommendation"=> 0,
									"is_parent"     => 0,
									"parent_id"     => $slab["id"],
									"count"			=> null
								);
								$this->patient_laboratories_model->add($lab_arr);
							}
						}
                    }
                }
            }
        }
    }

    /**
     * Tanlangan yoki tanlanmagan uzilar ustida amallar
     *
     * @param $uzis_id array
     * @return mixed
     */
    private function uzis_actions($uzis, $payment_id, $patient_id) {

        //patient_uzis tabledan keraksiz uzilarni uchirib tashlaymiz
        //3.2 payment_id buyicha bazada bor uzilarni tekshiramiz
        $existed_uzis = array();
        foreach ($this->patient_uzi_model->get_patient_uzi($payment_id) as $e_uzi) {
            $existed_uzis[] = $e_uzi["uzi_id"];
        }

        //3.3 agar uzilar bulsa, uchirilishi mumkin bulmaganlarini va bazaga kiritilishi kerak bulganlarini ajratib olamiz.
        $uzis_id_for_nodelete    = array();
        $uzis_id_for_insert      = array();
        if(count($existed_uzis) > 0) {
            foreach ($uzis as $uzi) {
                if(in_array($uzi["id"], $existed_uzis)) {
                    $uzis_id_for_nodelete[] = $uzi;
                } else {
                    $uzis_id_for_insert[] = $uzi;
                }
            }
        } else {
            $uzis_id_for_insert = $uzis;
        }

        if(count($uzis_id_for_nodelete) > 0) {
            $this->patient_uzi_model->delete_not_selected($payment_id, $uzis_id_for_nodelete);
        } else {
            $this->patient_uzi_model->delete_by_paymentId($payment_id);
        }

        //3.4 laboratoriyalarni bazaga kiritamiz
        if(count($uzis_id_for_insert) > 0) {
            foreach ($uzis_id_for_insert as $uzi_id_for_insert) {
                $arr = array(
                    "patient_id" => $patient_id,
                    "payment_id" => $payment_id,
                    "uzi_id" 	=> $uzi_id_for_insert["id"],
                    "status" 	=> 0,
					"count"  	=> $uzi_id_for_insert["count"],
                );

                $this->patient_uzi_model->add($arr);
            }
        }
    }

    /**
     * Tanlangan yoki tanlanmagan uzilar ustida amallar
     *
     * @param $services_id array
     * @return mixed
     */
    private function services_actions($services, $payment_id, $patient_id) {

        //patient_services tabledan keraksiz uzilarni uchirib tashlaymiz
        //3.2 payment_id buyicha bazada bor servicelarni tekshiramiz
        $existed_services = array();
        foreach ($this->patient_service_model->get_patient_service($payment_id) as $e_service) {
            $existed_services[] = $e_service["service_id"];
        }

        //3.3 agar servicelar bulsa, uchirilishi mumkin bulmaganlarini va bazaga kiritilishi kerak bulganlarini ajratib olamiz.
        $services_id_for_nodelete    = array();
        $services_id_for_insert      = array();
        if(count($existed_services) > 0) {
            foreach ($services as $service) {
                if(in_array($service["id"], $existed_services)) {
                    $services_id_for_nodelete[] = $service;
                } else {
                    $services_id_for_insert[] = $service;
                }
            }
        } else {
            $services_id_for_insert = $services;
        }

        if(count($services_id_for_nodelete) > 0) {
            $this->patient_service_model->delete_not_selected($payment_id, $services_id_for_nodelete);
        } else {
            $this->patient_service_model->delete_by_paymentId($payment_id);
        }

        //3.4 laboratoriyalarni bazaga kiritamiz
        if(count($services_id_for_insert) > 0) {
            foreach ($services_id_for_insert as $service_for_insert) {
                $arr = array(
                    "patient_id" => $patient_id,
                    "payment_id" => $payment_id,
                    "service_id" => $service_for_insert["id"],
                    "status" 	 => 0,
					"count" 	 => $service_for_insert["count"],
                );

                $this->patient_service_model->add($arr);
            }
        }
    }

	/**
	 * Barcha doctorlar, laboratoriyalar va uzilar
	 */
	public function items_list()
	{

		$docs = array();
		$docs_price = array();
//		foreach ($this->doctors_model->get_doctors() as $doctor) {
		foreach ($this->doctors_model->get_doctors_all() as $doctor) {
			$docs[$doctor["id"]]        = $doctor["last_name"] ." ". $doctor["first_name"] . " (".$doctor["department_name"].") - ".$doctor["price"];
			$docs_price[$doctor["id"]]  = $doctor["price"];
		}

		$uzis = array();
		$uzis_price = array();
		foreach ($this->uzi_model->get_uzis() as $uzi) {
			$uzis[$uzi["id"]]       = $uzi["name"] ." - ".$uzi["price"];
			$uzis_price[$uzi["id"]] = $uzi["price"];
		}

		$services = array();
		$services_price = array();
		foreach ($this->services_model->get_services() as $service) {
			$services[$service["id"]]       = $service["name"] ." - ".$service["price"];
			$services_price[$service["id"]] = $service["price"];
		}

		$items = array(
			"docs"          => $docs,
			"docs_price"    => $docs_price,
			"laboratories"  => $this->laboratory_model->get_categories(),
			"uzis"          => $uzis,
			"uzis_price"    => $uzis_price,
			"services"      => $services,
			"services_price"=> $services_price
		);

		return $items;
	}

	/**
	 * Barcha хоналар ва ётоқлар
	 */
	public function rooms_list()
	{
        $items = array("rooms" => $this->rooms_model->get_rooms());

		return $items;
	}

    /**
     * Bemorni tarih-parixi bilan uchirib tashlash
     **/
    public function delete() {
        $patient_id = $this->input->post("patient_id");
        $this->patient_doctor_model->deleteAll($patient_id);
        $this->patient_laboratories_model->deleteAll($patient_id);
        $this->patient_uzi_model->deleteAll($patient_id);
        $this->patients_payments_model->deleteAll($patient_id);
        $this->patients_model->delete($patient_id);

        echo json_encode(array("action" => "deleted"));
    }

    /**
     * tulovni bekor qilish
     * ****/
    public function ajax_cancel_payment()
    {
        if ($this->input->is_ajax_request()) {
            $payment_id = $this->input->post("payment_id");

            $this->patients_payments_model->delete($payment_id);

            $action = "canceled";
            $cancel = true;

            echo json_encode(array("action" => $action, "cancel" => $cancel));

        }
    }

    public function profile($id, $profile_view = null) {

    	$this->load->helper("lab_form");
        $this->data["result_html"] = build_laboratory_results_table(2996);
        $backUrl = site_url("admin/registry");
        $page = "";
        if($profile_view == "archive") {
            $backUrl = site_url("admin/registry/archive_patients");
            $page = "archive";
        } else if($profile_view == "credit") {
            $backUrl = site_url("admin/registry/credit_patients");
            $page = "credit";
        } else if($profile_view == "history") {
            $backUrl = site_url("admin/registry/payments_history");
            $page = "history";
        }
        $this->data["page"] = $page;

        $this->mybreadcrumb->add('Қабулхона', $backUrl);
        $this->mybreadcrumb->add('Бемор', "admin/registry/profile");
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->data["title"] = "Бемор";
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = ' 
        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/divjs/divjs.js").'"></script>
        <script src="'.site_url("assets/admin/js/functions_patients.js?".time()).'"></script>
                                        ';

        $this->data["payment_type_options"] = array();
        $this->data["expense_type_options"] = array();
        $this->data["profile_view"] = $profile_view;

        $result = $this->patients_model->get_patient($id);
        $this->data["patient"] = $result;

        $gender = array(1=>"Эркак", 2 => "Аёл");
        $this->data["gender"] = $gender;

        $payments = $this->patients_payments_model->get_patient_payment_by_patient($id);

		$history = array();
		$payment_dates = array();
		foreach ($payments as $payment) {
			$payment_date = date("Y-m-d", strtotime($payment["created_date"]));
			$payment_dates[$payment_date] = date("d.m.Y H:i", strtotime($payment["created_date"]));
			$history[$payment_date][] = $payment;
		}

		$this->data["history"]          = $history;
		$this->data["payment_dates"]    = $payment_dates;
		$this->data["partners"] 		= $this->patients_payments_model->get_patient_partners($id);
		$this->data["total_payment"] 	= $this->patients_payments_model->get_patient_debt($id);

        $this->render("admin/registry/profile_view");
    }

	public function ajax_medical_history()
	{
		if ($this->input->is_ajax_request()) {

			$patient_id = $this->input->post("patient_id");
			$payment_id = $this->input->post("payment_id");
			$payment_date = date("Y-m-d", $this->input->post("payment_date"));
			$item_type  = $this->input->post("item_type");
			$button_type= $this->input->post("button_type");
			$result_html = "";

			if($item_type == "doctor") {
				$result = $this->patient_doctor_model->get_patient_doctor($payment_id);
				$result_html = '<div class="table-responsive">';
//                $result_html .= '<button type="button" class="btn btn-primary printBtn float-right mb-3"><span class="fa fa-print font-18"></span></button>';
				$result_html .= '<table class="table table-bordered table-sm mb-3"><thead class="bg-light">';
				$result_html .= '<tr><th width="50%">Шифокор</th><th width="25%">Ташхис</th><th width="25%">Тўлов</th></tr></thead>';
				foreach ($result as $item) {
					$result_html .= '<tr><td> '.$item["last_name"].' '.$item["first_name"].'</td><td> '.$item["diagnosis"].'</td><td> '.money_formatting($item["price"]).'</td></tr>';
				}
				$result_html .= '</table>';
				$result_html .= '</div>';

			} elseif ($item_type == "laboratory") {
				//Bemorning laboratoriyalari
				if($button_type == "print") {
					//var $type: null = barchasi, 1-doctor, 2-laboratory, 3-uzi
					$this->load->helper("lab_form");
					$result_html = "<div>";
					$result_html .= build_laboratory_results_table($payment_id);
					$result_html .= build_laboratory_results_table($payment_id, false, true, false);
					$result_html .= "</div>";
				} else {
					$lab_total = 0;
					$selected_labs = $this->patient_laboratories_model->get_patient_labs($payment_id, 1);
					$result_html = '<ul class="list-group">';
					$result_html .= '<li class="list-group-item d-flex justify-content-between align-items-center h5 font-weight-bold active">Лаборатория</li>';
					foreach ($selected_labs as $selected_lab) {
						$result_html .= '<li class="list-group-item d-flex justify-content-between align-items-center"><span>'.($selected_lab["name"]).'</span><span>'.(money_formatting($selected_lab["price"])).'</span></li>';
						$lab_total += $selected_lab["price"];
					}
					$result_html .= '<li class="list-group-item d-flex justify-content-between align-items-center h5 font-weight-bold active">Жами: <span>'.money_formatting($lab_total).'</span></li>';

					$result_html .= '</ul>';
				}

			} elseif ($item_type == "uzi") {

				//Bemorning uzilari
				if($button_type == "print") {

					$this->load->helper("lab_form");
					$result_html = build_uzi_results($payment_id);

				} else {
					$uzi_total = 0;
					$selected_uzis = $this->patient_uzi_model->get_patient_uzi($payment_id);
					$result_html = '<ul class="list-group">';
					$result_html .= '<li class="list-group-item d-flex justify-content-between align-items-center h5 font-weight-bold active">УЗИ</li>';
					foreach ($selected_uzis as $selected_uzi) {
						$result_html .= '<li class="list-group-item d-flex justify-content-between align-items-center"><span>'.($selected_uzi["name"]).'</span><span>'.(money_formatting($selected_uzi["price"])).'</span></li>';
						$uzi_total += $selected_uzi["price"];
					}
					$result_html .= '<li class="list-group-item d-flex justify-content-between align-items-center h5 font-weight-bold active">Жами: <span>'.money_formatting($uzi_total).'</span></li>';
					$result_html .= '</ul>';
				}
			} elseif($item_type == "service") {
				if($button_type == "list") {
					$service_total = 0;
					$selected_services = $this->patient_service_model->get_patient_service($payment_id);
					$result_html = '<ul class="list-group">';
					$result_html .= '<li class="list-group-item d-flex justify-content-between align-items-center h5 font-weight-bold active">Қўшимча хизматлар</li>';
					foreach ($selected_services as $selected_service) {
						$result_html .= '<li class="list-group-item d-flex justify-content-between align-items-center"><span>'.($selected_service["name"]).'</span><span>'.(money_formatting($selected_service["price"])).'</span></li>';
						$service_total += $selected_service["price"];
					}
					$result_html .= '<li class="list-group-item d-flex justify-content-between align-items-center h5 font-weight-bold active">Жами: <span>'.money_formatting($service_total).'</li>';
					$result_html .= '</ul>';
				}



			} elseif ($item_type == "room") {
				$this->load->model("patient_room_model");
				$result = $this->patient_room_model->get_bed_by_patient($patient_id);
				$result_html = '<div class="table-responsive">';
//                $result_html .= '<button type="button" class="btn btn-primary printBtn float-right mb-3"><span class="fa fa-print font-18"></span></button>';
				$result_html .= '<table class="table table-bordered table-sm mb-3"><thead class="bg-light">';
				$result_html .= '<tr><th>Шифокор:&nbsp;</th><th>Хона:&nbsp;</th><th>Ётоқ:&nbsp;</th><th>Кундан:&nbsp;</th><th>Кунгача:&nbsp;</th></tr></thead>';
				foreach ($result as $item) {
					$result_html .= '<tr><td> '.$item["doctor_last_name"].' '.$item["doctor_first_name"].' </td><td>'.$item["room_number"].'</td><td>'.$item["bed_name"].'</td><td>'.date("d.m.Y", strtotime($item["start_date"])).'</td><td>'.date("d.m.Y", strtotime($item["end_date"])).'</td></tr>';
				}
				$result_html .= '</table>';
				$result_html .= '</div>';
			}

			echo json_encode($result_html);
		}

	}

    public function ajax_show_expenses() {

		$expense_types 	= ["Boshqa chiqimlar"];
		foreach ($this->expense_type_model->get_expense_types() as $expense_type) {
			$expense_types[$expense_type["id"]] = $expense_type["name"];
		}

        $payment_types 	= $this->payment_types_model->get_payment_types();
        $expenses 		= $this->expenses_model->get_expenses(false, false, true);
        $data = array();
        foreach ($expenses as $key => $expens) {
            $data[$key]["DT_RowId"] = "expense_row_".$expens["id"];
            $data[$key][0] = '<div>
                                <div class="expense_date">'.date("d.m.Y H:i", strtotime($expens["created_date"])).'</div>
                                <div class="expenser">'.$expens["last_name"].' '.$expens["first_name"].'</div>
                            </div>
                    ';
            $data[$key][1] = '<div class="js_expense_cell">
                                    <div class="js_expense_cell_text">'.number_format($expens["amount"], 0, ',', ' ').'</div>
                                    <div class="js_expense_cell_input d-none"><input type="text" class="form-control" value="'.$expens["amount"].'" name="amount[]" id="amount_'.$expens["id"].'" /></div>
                                </div>';
            $data[$key][2] = $payment_types[$expens["payment_type_id"]];
            $data[$key][3] = $expense_types[$expens["expense_type_id"]];
            $data[$key][4] = '<div class="js_expense_cell">
                                    <div class="js_expense_cell_text">'.$expens["reason"].'</div>
                                    <div class="js_expense_cell_input d-none"><input type="text" class="form-control" value="'.$expens["reason"].'" name="reason[]" id="reason_'.$expens["id"].'" /></div>
                                </div>';
            $data[$key][5] = '<a class="js_expense_edit_field js_expense_edit" href="javascript:void(0);" data-id="'.$expens["id"].'"><span class="fa fa-edit"></span></a>
                                <a class="pl-3 js_expense_remove" href="javascript:void(0);" data-id="'.$expens["id"].'" data-url="'.site_url("admin/registry/ajax_remove_expense").'"><span class="fa fa-window-close-o text-danger"></span></a>
                                <div class="js_expense_update_box d-none">
                                    <a class="js_expense_edit_field js_expense_apply" href="javascript:void(0);" data-id="'.$expens["id"].'" data-url="'.site_url("admin/registry/ajax_update_expense").'"><span class="fa fa-check-square-o text-success"></span></a>
                                    <a class="js_expense_edit_field js_expense_cancel ml-3" href="javascript:void(0);" data-id="'.$expens["id"].'"><span class="fa fa-minus-square-o text-danger"></span></a>
                                </div>
                                ';
        }

        $response = array("data" => $data);
        echo json_encode($response);
    }

    /****
     * Chiqimlarni bazaga kiritamiz
     * */
    public function ajax_get_cash_today()
    {
        if ($this->input->is_ajax_request()) {

            //Bugungi kun uchun barcha kirimlarni xisoblaymiz
            $real_payment = $this->patients_payments_model->real_payment();
            $result["real_payment"] = (count($real_payment) > 0) ? $real_payment["paid"]:0;

            //Bugungi kun uchun barcha chiqimlarni xisoblaymiz
            $total_expenses = $this->expenses_model->get_expenses();
            $total_expenses = is_null($total_expenses) ? 0:$total_expenses;
            $result["total_expenses"] = $total_expenses;

            echo json_encode($result);
        }
    }

    /****
     * Chiqimlarni bazaga kiritamiz
     * */
    public function ajax_add_expenses()
    {
        if ($this->input->is_ajax_request()) {
            $result = array();
            $this->form_validation->set_rules('amount', "Суммани киритинг", 'trim|required|integer');
            $this->form_validation->set_rules('reason', "Сабабини ёзинг", 'trim|required');

            if ($this->form_validation->run() === TRUE) {
                $result["errors"] = false;
                $_POST["user_id"] = $this->user_id;

                $id = $this->expenses_model->add($this->input->post());
                $result["success"] = $id;
            } else {
                $result["errors"] = $this->form_validation->error_array();
            }

            echo json_encode($result);
        }

    }

    /****
     * Chiqimlarni bazaga kiritamiz
     * */
    public function ajax_update_expense()
    {
        if ($this->input->is_ajax_request()) {
            $result = array();
            $this->form_validation->set_rules('amount', "Суммани киритинг", 'trim|required|integer');
            $this->form_validation->set_rules('reason', "Сабабини ёзинг", 'trim|required');

            if ($this->form_validation->run() === TRUE) {
                $result["errors"] = false;
                $expense_id = $this->input->post("expense_id");
                $amount = $this->input->post("amount");
                $reason = $this->input->post("reason");

                $id = $this->expenses_model->update($expense_id, array("amount"=>$amount, "reason" => $reason));
                $result["success"] = $id;

            } else {
                $result["errors"] = $this->form_validation->error_array();
            }

            echo json_encode($result);
        }

    }

    /****
     * Chiqimlarni bazaga kiritamiz
     * */
    public function ajax_remove_expense()
    {
        if ($this->input->is_ajax_request()) {
            $expense_id = $this->input->post("expense_id");
            $result = $this->expenses_model->delete($expense_id);

            echo json_encode($result);
        }
    }

    /**
     * @return array
     */
    public function rooms()
    {
        $this->load->language("rooms");
        $this->load->model(array("rooms_model", "room_types_model", "room_beds_model"));

        $this->data['title'] = 'Хоналар';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.hideseek.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.autocomplete.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/functions_patients.js?".time()).'"></script>
                                            ';
		$this->data["page"] = "rooms";
        $this->data["patients_counts"]  = $this->patients_counts();

        $this->data["rooms"] = $this->room_beds_model->get_rooms();
//        $this->data["beds"] = $this->room_beds_model->get_beds_patients();


        $this->data["discount_options"] = array("" => "-- Танланмаган --", 1 => "Сумма", 2 => "Фоиз");

        $partners[] = "-- Танлаш --";
        foreach ($this->partners_model->get_partners() as $partner) {
            if($partner["type"] == 1) {
                $partners[$partner["id"]] = $partner["last_name"] ." ". $partner["first_name"];
            } else {
                $partners[$partner["id"]] = $partner["company"];
            }
        }
        $this->data["partners_options"] = $partners;

		$doctors = array(0=> "-- Танлаш --");
		foreach ($this->doctors_model->get_doctors_all() as $doctor) {
			$doctors[$doctor["id"]] = $doctor["last_name"] ." ". $doctor["first_name"] . " (".$doctor["department_name"].")";
		}
		$this->data["doctors_options"] = $doctors;


        $this->data["payment_type_options"] = $this->payment_types_model->get_payment_types();
        $expense_type_options = ["Boshqa chiqimlar"];
        foreach ($this->expense_type_model->get_expense_types() as $etype) {
            $expense_type_options[$etype["id"]] = $etype["name"];
        }
        $this->data["expense_type_options"] = $expense_type_options;

        $this->data['partner_id'] = [
            'name' => 'partner_id',
            'id' => 'partner_id',
            'value' => $this->form_validation->set_value('partner_id'),
            'class' => 'select',
        ];

		$this->data['sender_doctor_id'] = [
			'name' 	=> 'sender_doctor_id',
			'id' 	=> 'sender_doctor_id',
			'value' => $this->form_validation->set_value('sender_doctor_id'),
			'class' => 'select select2_search',
		];

        $start_date = date("Y-m-d");
        $end_date = date("Y-m-d", strtotime($start_date . "+1 days"));
        if ($this->form_validation->run() === TRUE) {
            $start_date = date_formating(strtotime($this->input->post("start_date")), "db");
            $end_date 	= date_formating(strtotime($this->input->post("end_date")), "db");
        }
        $this->data['start_date'] = [
            'name'  => 'start_date',
            'id'    => 'start_date',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('start_date', date("d.m.Y", strtotime($start_date))),
            "class" => "form-control mb-2 mr-sm-2 js_room_date js_room_date_start",
        ];
        $this->data['end_date'] = [
            'name' => 'end_date',
            'id' => 'end_date',
            'type' => 'text',
            'value' => $this->form_validation->set_value('end_date', date("d.m.Y", strtotime($end_date))),
            "class" => "form-control mb-2 mr-sm-2 js_room_date js_room_date_end",
        ];

        $this->data['paid'] = [
            'name' => 'paid',
            'id' => 'paid',
            'type' => 'text',
            'value' => $this->form_validation->set_value('paid', 0),
            'class' => 'form-control form-control-paid',
        ];
        $this->data['total_sum'] = [
            'name' => 'total',
            'id' => 'total',
            'type' => 'text',
            'value' => $this->form_validation->set_value('total', 0),
            'class' => 'form-control form-control-paid',
            "readonly" => "readonly"
        ];
        $this->data['debt'] = [
            'name' => 'debt',
            'id' => 'debt',
            'type' => 'text',
            'value' => $this->form_validation->set_value('debt', 0),
            'class' => 'form-control form-control-paid',
            "readonly" => "readonly"
        ];
        $this->data['discount_type'] = [
            'name' => 'discount_type',
            'id' => 'discount_type',
            'value' => $this->form_validation->set_value('discount_type'),
            'class' => 'select js_discount_type',
        ];
        $this->data['discount_value'] = [
            'name' => 'discount_value',
            'id' => 'discount_value',
            'type' => 'text',
            'value' => $this->form_validation->set_value('discount_value', 0),
            'class' => 'form-control form-control-paid js_discount_value',
        ];

        $this->data['by_cash'] = [
            'name' => 'by_cash',
            'id' => 'by_cash',
            'type' => 'text',
            'value' => $this->form_validation->set_value('by_cash'),
            'class' => 'form-control form-control-paid',
            'placeholder' => 'Нақд',
            "readonly" => "readonly"
        ];
        $this->data['by_card'] = [
            'name' => 'by_card',
            'id' => 'by_card',
            'type' => 'text',
            'value' => $this->form_validation->set_value('by_card'),
            'class' => 'form-control form-control-paid',
            'placeholder' => 'Пластик',
        ];
        $this->data['by_bank'] = [
            'name' => 'by_bank',
            'id' => 'by_bank',
            'type' => 'text',
            'value' => $this->form_validation->set_value('by_bank'),
            'class' => 'form-control form-control-paid',
            'placeholder' => 'Терминал',
        ];

        $this->render('admin/registry/rooms_view');
    }

    /************************
     * 
     * **********************/
	public function assign_to_room($patient_id = null)
	{
		$this->data["title"] = "Беморни хонага боғлаш";
		$this->data['before_themeStyle'] = '
                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/js/lou_multi_select/multi-select.css").'">
                                        ';

		$this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/lou_multi_select/jquery.multi-select.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.hideseek.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/functions_patients.js?".time()).'"></script>
                                        ';

		$print_cheque = false;
        $this->data["patient_id"] = $patient_id;

		$was_validated      = "";
		$tables             = $this->config->item('tables', 'ion_auth');
		$identity_column    = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		//Viloyatlar
		$this->data['regions'] = $this->regions_model->get_regions_array();
		$selected_region_id = $this->config->item("default_region_id"); //defaul_region_id = 3 ga (farg'ona)
		if(!is_null($this->input->post("region_id"))) {
			$selected_region_id = $this->input->post("region_id");
		}
		$this->data['selected_region_id'] = $selected_region_id;

		//Shaxarlar
		$selected_city_id = $this->config->item("default_city_id"); //defaul_city_id = 2 ga (quqonning ID si)
		if(!is_null($this->input->post("city_id"))) {
			$selected_city_id = $this->input->post("city_id");
		}
		$this->data['cities'] = $this->cities_model->get_cities_by_region_id($selected_region_id);
		$this->data['selected_city_id'] = $selected_city_id;

        if(is_null($patient_id)) {
            $prefix = "bem";
            $max_id = $this->patients_model->get_max_id();

            $this->load->helper("mix");
            $code = uniqe_code_genetrator($prefix, $max_id);
            $this->data["username"] = $code;
        } else {
            $patient = $this->patients_model->get_patient($patient_id);
            $this->data["patient"] = $patient;
        }

		$partners[] = "-- Танлаш --";
		foreach ($this->partners_model->get_partners() as $partner) {
			if($partner["type"] == 1) {
				$partners[$partner["id"]] = $partner["last_name"] ." ". $partner["first_name"];
			} else {
				$partners[$partner["id"]] = $partner["company"];
			}
		}
		$this->data["partners_options"]     = $partners;
		$this->data["payment_type_options"] = $this->payment_types_model->get_payment_types();
		$this->data["discount_options"]     = array("" => "-- Танланмаган --", 1 => "Сумма", 2 => "Фоиз");

		$rooms = $this->rooms_list();
		$this->data["rooms"] 			= $rooms["rooms"];
		$this->data["partners"]         = $partners;


		$doctors = array(0=> "-- Танлаш --");
		foreach ($this->doctors_model->get_doctors_all() as $doctor) {
			$doctors[$doctor["id"]] = $doctor["last_name"] ." ". $doctor["first_name"] . " (".$doctor["department_name"].")";
		}


		$this->data["doctors_options"] = $doctors;


        if(is_null($patient_id)) {
            $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
            $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
            $this->form_validation->set_rules('gender', $this->lang->line('create_user_validation_gender'), 'trim');
            $this->form_validation->set_rules('address', $this->lang->line('create_user_validation_address'), 'trim');
            if ($identity_column !== 'email') {
                $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|valid_email');
            } else {
                $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
            }

            $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
            $this->form_validation->set_rules('dob', $this->lang->line('create_user_validation_department_label'), 'trim');

        }
		$this->form_validation->set_rules('partner_id', $this->lang->line('create_user_sender_label'), 'trim');
		$this->form_validation->set_rules('paid', "Тўланди", 'trim');
		$this->form_validation->set_rules('start_date', "Бошланғич сана", 'trim');
		$this->form_validation->set_rules('end_date', "Якуний сана", 'trim');
		$this->form_validation->set_rules('debt', "Қарзингиз", 'trim');
		$this->form_validation->set_rules('total', "Жами", 'trim');
		$this->form_validation->set_rules('discount_type', "Чегирма", 'trim');
		$this->form_validation->set_rules('discount_value', "Қиймат", 'trim');
		$this->form_validation->set_rules('by_cash', "Нақд", 'trim');
		$this->form_validation->set_rules('by_card', "Пластик", 'trim');
		$this->form_validation->set_rules('by_bank', "Терминал", 'trim');

        if(is_null($patient_id)) {
            if ($this->form_validation->run() === TRUE) {
				$print_cheque = is_null($this->input->post("print_cheque")) ? false:true;
				unset($_POST["print_cheque"]);

                $email = strtolower($this->input->post('email'));
                $identity = ($identity_column === 'email') ? $email : $code;
                $password = "123!@";

                $additional_data = [
                    'first_name'    => $this->input->post('first_name'),
                    'last_name'     => $this->input->post('last_name'),
                    'surname'       => $this->input->post('surname'),
                    'dob'           => (empty($this->input->post('dob')) ? NULL:$this->input->post('dob')."-01-01"),
                    'gender'        => $this->input->post('gender'),
                    'address'       => $this->input->post('address'),
                    'region_id'     => 3,
                    'city_id'       => 2,
                    'phone'         => (empty($this->input->post('phone')) ? NULL:("+998".$this->input->post('phone'))),
                    'active'        => $this->input->post('user_status'),
                ];

                $group = array('5'); // Sets user to bemor.
            }

            if ($this->form_validation->run() === TRUE && ($user_id = $this->ion_auth->register($identity, $password, $email, $additional_data, $group))) {
                //bemorlarga qushish
                $patient_id = $this->patients_model->add(array("user_id" => $user_id));
            }
        }

        if ($this->form_validation->run() === TRUE) {
			if(!empty($this->input->post('bed_id'))) {

				$discount_type 	= $this->input->post("discount_type");
				$discount_value = $this->input->post("discount_value");
				$total 			= $this->input->post("total");
				$discount 		= $this->get_discount($discount_type, $discount_value, $total);

				$payment_arr = array(
					"doctor_status"     => 0,
					"laboratory_status" => 0,
					"uzi_status"        => 0,
					"service_status"    => 0,
					"room_status"       => 1,
					"order_status"      => 0,
					"patient_id"        => $patient_id,
					"discount_type"     => $discount_type,
					"discount_value"    => $discount_value,
					"discount"	    	=> $discount,
					"total"             => $total,
					"status"            => 0,
					'partner_id'        => $this->input->post("partner_id"),
					'doctor_id'        => $this->input->post("sender_doctor_id"),
				);

				$payment_id = $this->patients_payments_model->add($payment_arr);

				$payment_details_arr = array(
					'payment_id'      	=> $payment_id,
					"paid"              => $this->input->post("paid"),
					'by_cash'      		=> $this->input->post('by_cash'),
					'by_card'      		=> $this->input->post('by_card'),
					'by_bank'      		=> $this->input->post('by_bank'),
				);
				$this->patients_payments_details_model->add($payment_details_arr);

				//qarzi yoki chegirmasi bulsa qushib quyamiz
				if($this->input->post("debt") > 0) {
					$arr = array(
						"type"          => 1,
						"payment_id"    => $payment_id,
						"service_type"  => 5,
						"doctor_id"     => null,
						"amount"        => $this->input->post("debt"),
						"debt_off_type" => 0,
					);

					$debt_discount_id = $this->payments_debt_discount_model->add($arr);
				}

				if($discount > 0) {
					$arr = array(
						"type"          => 2,
						"payment_id"    => $payment_id,
						"service_type"  => 5,
						"doctor_id"     => null,
						"amount"        => $discount,
						"debt_off_type" => 0,
					);

					$debt_discount_id = $this->payments_debt_discount_model->add($arr);
				}

			}

			//agar tulov qilingan bulsa
			if(isset($payment_id)) {
				$start_date = date("Y-m-d H:i:s", strtotime($this->input->post("start_date")));
				$end_date   = date("Y-m-d 11:00:00", strtotime($this->input->post("end_date")));

				foreach ($this->input->post('bed_id') as $index => $bed_id) {
					$patient_bed = array(
						"patient_id"    => $patient_id,
						"bed_id"        => $bed_id,
						"start_date"    => $start_date,
						"end_date"      => $end_date,
						"payment_id"    => $payment_id,
						"busy"          => 1,
					);

					$this->patient_room_model->add($patient_bed);
				}

				//Check chiqaramiz
				if(pos_print() && $print_cheque) {
					try {
						$this->load->helper(array("lab_form"));
						$pr = print_receipt($payment_id);
						$this->load->library('ReceiptPrint');
						$user = $this->ion_auth->user()->row();
						$this->receiptprint->connect($this->config->item("pos_printer_name"));
						$this->receiptprint->print_receipt($pr["patient_data"], $pr["payment_data"], false, false, false, false, $user);
					} catch (Exception $e) {
						log_message("error", "Error: Could not print. Message ".$e->getMessage());
						$this->receiptprint->close_after_exception();
					}
				}
			}


			// check to see if we are creating the user
			// redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());

			redirect("admin/registry/rooms", 'refresh');
		} else {

			$beds 	= $this->room_beds_model->get_beds_by_id($this->input->post("bed_id"));
			$print_total = 0;
			if($this->input->post("bed_id") != null) {
				//tanlangan yotoqlarni printer uchun saqlab qolish
				$print_beds = array();
				$print_beds_price_total = 0;
				foreach ($beds as $bed) {
					$print_beds[$bed["id"]] = $bed["name"] ." - ". $bed["price"];
					$print_beds_price_total += $bed["price"];
				}

				$this->data["print_beds"] = $print_beds;
				$this->data["print_beds_price_total"] = $print_beds_price_total;

				$print_total += $print_beds_price_total;
			}

			$this->data["print_total"] = $print_total;


			// display the create user form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			if(!empty($this->data['message']))
			{
				$was_validated = "was-validated";
			}
			$this->data["was_validated"] = $was_validated;

			$this->data['first_name'] = [
				'name'  => 'first_name',
				'id'    => 'first_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('first_name'),
				"class" => "form-control",
				"required" => ""
			];
			$this->data['last_name'] = [
				'name' => 'last_name',
				'id' => 'last_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('last_name'),
				"class" => "form-control",
				"required" => ""
			];
			$this->data['surname'] = [
				'name' => 'surname',
				'id' => 'surname',
				'type' => 'text',
				'value' => $this->form_validation->set_value('surname'),
				"class" => "form-control"
			];
			$this->data['email'] = [
				'name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email'),
				'class' => 'form-control'
			];
			$this->data['dob'] = [
				'name' => 'dob',
				'id' => 'dob',
				'type' => 'text',
				'value' => $this->form_validation->set_value('dob'),
				'class' => 'form-control',
			];
			$this->data['address'] = [
				'name' => 'address',
				'id' => 'address',
				'type' => 'text',
				'value' => $this->form_validation->set_value('address'),
				'class' => 'form-control'
			];
			$this->data['region'] = [
				'name' => 'region_id',
				'id' => 'region_id',
				'type' => 'text',
				'value' => $this->form_validation->set_value('region_id'),
				'class' => 'custom-select',
				"required" => "",
				"data-url" => site_url("admin/doctors/ajax_get_cities")
			];
			$this->data['city'] = [
				'name'  => 'city_id',
				'id'    => 'city_id',
				'value' => $this->form_validation->set_select('city_id'),
				'class' => 'select! custom-select',
				"required" => ""
			];
			$this->data['phone'] = [
				'name' => 'phone',
				'id' => 'phone',
				'type' => 'text',
				'value' => $this->form_validation->set_value('phone'),
				'class' => 'form-control',
			];
			$this->data['partner_id'] = [
				'name' => 'partner_id',
				'id' => 'partner_id',
				'value' => $this->form_validation->set_value('partner_id'),
				'class' => 'select',
			];

			$this->data['sender_doctor_id'] = [
				'name' 	=> 'sender_doctor_id',
				'id' 	=> 'sender_doctor_id',
				'value' => $this->form_validation->set_value('sender_doctor_id'),
				'class' => 'select select2_search',
			];

			$start_date = date("Y-m-d");
			$end_date = date("Y-m-d", strtotime($start_date . "+1 days"));
			if ($this->form_validation->run() === TRUE) {
				$start_date = date_formating(strtotime($this->input->post("start_date")), "db");
				$end_date 	= date_formating(strtotime($this->input->post("end_date")), "db");
			}
			$this->data['start_date'] = [
				'name'  => 'start_date',
				'id'    => 'start_date',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('start_date', date("d.m.Y", strtotime($start_date))),
				"class" => "form-control mb-2 mr-sm-2 js_room_date js_room_date_start",
			];
			$this->data['end_date'] = [
				'name' => 'end_date',
				'id' => 'end_date',
				'type' => 'text',
				'value' => $this->form_validation->set_value('end_date', date("d.m.Y", strtotime($end_date))),
				"class" => "form-control mb-2 mr-sm-2 js_room_date js_room_date_end",
			];

			$this->data['paid'] = [
				'name' => 'paid',
				'id' => 'paid',
				'type' => 'text',
				'value' => $this->form_validation->set_value('paid', 0),
				'class' => 'form-control form-control-paid',
			];
			$this->data['total_sum'] = [
				'name' => 'total',
				'id' => 'total',
				'type' => 'text',
				'value' => $this->form_validation->set_value('total', 0),
				'class' => 'form-control form-control-paid',
				"readonly" => "readonly"
			];
			$this->data['debt'] = [
				'name' => 'debt',
				'id' => 'debt',
				'type' => 'text',
				'value' => $this->form_validation->set_value('debt', 0),
				'class' => 'form-control form-control-paid',
				"readonly" => "readonly"
			];
			$this->data['discount_type'] = [
				'name' => 'discount_type',
				'id' => 'discount_type',
				'value' => $this->form_validation->set_value('discount_type'),
				'class' => 'select js_discount_type',
			];
			$this->data['discount_value'] = [
				'name' => 'discount_value',
				'id' => 'discount_value',
				'type' => 'text',
				'value' => $this->form_validation->set_value('discount_value', 0),
				'class' => 'form-control form-control-paid js_discount_value',
			];

			$this->data['by_cash'] = [
				'name' => 'by_cash',
				'id' => 'by_cash',
				'type' => 'text',
				'value' => $this->form_validation->set_value('by_cash'),
				'class' => 'form-control form-control-paid',
				'placeholder' => 'Нақд',
				"readonly" => "readonly"
			];
			$this->data['by_card'] = [
				'name' => 'by_card',
				'id' => 'by_card',
				'type' => 'text',
				'value' => $this->form_validation->set_value('by_card'),
				'class' => 'form-control form-control-paid',
				'placeholder' => 'Пластик',
			];
			$this->data['by_bank'] = [
				'name' => 'by_bank',
				'id' => 'by_bank',
				'type' => 'text',
				'value' => $this->form_validation->set_value('by_bank'),
				'class' => 'form-control form-control-paid',
				'placeholder' => 'Терминал',
			];

			$this->render("admin/registry/rooms_assign_view");
		}
	}
    /************************
     * shifokorlar bemorni registratsionniyga yuborganda, tulovni amalga oshirish
     ************************ */
    public function ajax_do_payment() {
        $payment_id = $this->input->post("payment_id");

        $payment_data = array("created_date" => date("Y-m-d H:i:s"), "status" => 0);
        $success = $this->patients_payments_model->update($payment_id, $payment_data);
        $this->patients_payments_details_model->update($payment_id, array("created_date" => date("Y-m-d H:i:s")));

        if($success) {
            $backUrl = site_url("admin/registry");
            echo json_encode($backUrl);
        } else {
            echo json_encode($success);
        }

    }

    public function ajax_update_payments() {
        $page = $this->input->post("page") == 'index' ? 'today':$this->input->post("page");
        $patients = $this->patients_list($page);

        $payment["incomplete_patients"] = $patients["incomplete_patients"];
        $payment["completed_patients"] = $patients["completed_patients"];
        $payment["patients_counts"]     = $this->patients_counts();

        echo json_encode($payment);

    }

    public function ajax_patient_service_status()
    {
        $payment_id         = $this->input->post("payment_id");
        $service_status     = $this->input->post("service_status");

        //order_status = 0-navbatda, 1-doctor qabulida, 2-laboratoriyada, 3-uzida, 4-finish
        //service_status = 0-tulov qilinmagan, 1-tulov qilingan, 2-tamomlangan
        if ($service_status == "qabul_tamom") {

            $this->patient_service_model->update($payment_id, array("status" => 1));
            $this->patients_payments_model->update($payment_id, array("service_status" => 2));

            $payment_status     = $this->patients_payments_model->check_payment_status($payment_id);
            if($payment_status == 'completed') {
                $this->patients_payments_model->update($payment_id, array("order_status" => 4, "status" => 1));
            } else {
                $this->patients_payments_model->update($payment_id, array("order_status" => 0));
            }
        }

        echo json_encode($service_status);
    }

    private function get_discount($discount_type, $discount_value, $total) {
    	$discount = $discount_value;
    	if($discount_type == 2) $discount = ($discount_value / 100) * $total;

    	return $discount;
	}

    public function ajax_patients_list()
    {
        if($this->input->is_ajax_request()) {
            $keyword = $this->input->get("query");
            $patients_arr = $this->patients_model->search_patients($keyword);
            $patients = array();
            foreach ($patients_arr as $k => $p) {
                $patients[$k]["value"] = $p["last_name"]." ".$p["first_name"]." ".$p["phone"];
                $patients[$k]["patient_id"] = $p["id"];
            }

            echo json_encode(array("suggestions" => $patients));
        }
    }

	public function ajax_patient_details()
	{
		if($this->input->is_ajax_request()) {
			$patient_id = $this->input->post("patient_id");
//			$patient_id = 1322;
			$patient = $this->patients_model->get_patient($patient_id);
			$partners = $this->patients_payments_model->get_patient_partners($patient_id);
			$total_payment = $this->patients_payments_model->get_patient_debt($patient_id);
			$service_url = site_url("admin/registry/add_items/".$patient_id);
			$assign_room_url = site_url("admin/registry/assign_to_room/".$patient_id);
			$profile_room_url = site_url("admin/registry/profile/".$patient_id);

			$html = '<table class="table table-sm">';
			$html .= '<tr><th>Исм:</th><td class="">'.$patient["last_name"].' '.$patient["first_name"].'</td></tr>
                    <tr><th>Манзил:</th><td>'.$patient["region_name"].', '.$patient["city_name"].', '.$patient["address"].'</td></tr>
                    <tr><th>Телефон:</th><td>'.phone_number_format($patient["phone"]).'</td></tr>
                    <tr><th>Рўйхатдан ўтган сана:</th><td>'.date("d.m.Y", strtotime($patient["created_date"])).'</td></tr>
                    <tr><th>Қарз:</th><td>';
                    if($total_payment["debt"] > 0) {
                    	$html .= '<span class="badge badge-danger">'.($total_payment["debt"]).'</span>';
					}
                    $html .='</td></tr>
                    <tr><th valign="top">Йўлланма берувчи:</th><td valign="top">';
			foreach ($partners as $partner) {
				$html .= '<div>'.($partner["last_name"] ." ".$partner["first_name"]).'</div>';
			}
			$html .= '</td></tr>';
			$html .= '</table>';
			$html .= '<div class="row mt-2">
                        <div class="col-md-4">
                            <a role="button" href="'.$service_url.'" class="btn btn-primary btn-lg btn-block"><span class="fa fa-plus"></span> Хизматлар</a>
                        </div>
                        <div class="col-md-4">
                            <a role="button" href="'.$assign_room_url.'" class="btn btn-primary btn-lg btn-block"><span class="fa fa-plus"></span> Хона</a>
                        </div>
                        <div class="col-md-4">
                            <a role="button" href="'.$profile_room_url.'" class="btn btn-primary btn-lg btn-block"><span class="fa fa-eye"></span> Тарих</a>
                        </div>
                    </div>
                    ';

			echo json_encode($html);
		}
	}

    public function ajax_payment_debt_discount_form()
    {
        if($this->input->is_ajax_request()) {

            $debt_discount_type = $this->input->post("debt_discount_type");
            $payment_id         = $this->input->post("payment_id");

            //step 1 - tulov malumotlarini olvolamiz
            $payment = $this->patients_payments_model->get_patient_payment($payment_id);

            //step 2 - html forma uchun kerakli malumotlarni tayyorlab olamiz
            $payment_gap                = $payment["total"] - ($payment["paid"] + $payment["discount"]);
            $debt_discount_type_title   = 'Қарз';
            $debt_discount_type_class   = 'badge-danger';
            $type = 1;//qarz bulsa 1 ga teng
            if($debt_discount_type == "discount") {
                $payment_gap                = $payment["discount"];
                $debt_discount_type_title   = 'Чегирма';
                $debt_discount_type_class   = 'badge-warning';
                $type = 2;//chegirma bulsa 2 ga teng
            }


            //qarz va chegirmalarni taqsimladimi yuqmi tekshiramiz
            $payment_services = $this->payments_debt_discount_model->get_payment_items_unpaid($payment_id, $type);
            $services_paid_summa = 0;
            $doctors = array();
            $laboratory = $uzi = $xizmatlar = $rooms = $dentist = 0;
            foreach ($payment_services as $service) {
                if($service["service_type"] == 1) {
                    $doctors[$service["doctor_id"]] = $service["amount"];
                } elseif ($service["service_type"] == 2) {
                    $laboratory = $service["amount"];
                } elseif ($service["service_type"] == 3) {
                    $uzi = $service["amount"];
                } elseif ($service["service_type"] == 4) {
                    $xizmatlar = $service["amount"];
                } elseif ($service["service_type"] == 5) {
                    $rooms = $service["amount"];
                } elseif ($service["service_type"] == 6) {
                    $dentist = $service["amount"];
                }

                $services_paid_summa += $service["amount"];

            }

//            $payment_gap = $services_paid_summa > 0 ? ($payment_gap - $services_paid_summa) : $payment_gap;

            $html = '
                        <div class="row text-white">
                            <div class="col-sm-6 bg-success p-3 font-weight-bold font-18">
                                Чек №: '.($payment_id).'
                            </div>
                            <div class="col-sm-6 bg-info p-3">
                                <span class="badge '.($debt_discount_type_class).' font-weight-bold font-18 text-uppercase">'.($debt_discount_type_title).'</span> 
                                <span class="pl-2 font-18 font-weight-bold js_payment_gap"> '.($payment_gap - $services_paid_summa).'</span> sum
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-6 p-3 border-right">
                                <table class="table table-borderless border-0">
                                    <tbody>
                                    <tr>
                                        <th width="5%" class="text-right">ФИШ: </th>
                                        <td class="text-left"><span>'.($payment["last_name"].' '.$payment["first_name"]).'</span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Манзил: </th>
                                        <td class="text-left"><span>'.($payment["address"]).'</span></td>
                                    </tr>
                                   
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-6 p-3">
                                <form action="'.(site_url("admin/registry/ajax_payment_debt_discount_add")).'">
                                    <input type="hidden" name="payment_id" value="'.($payment_id).'">
                                    <input type="hidden" name="debt_discount_type" value="'.($debt_discount_type).'">
                                    <input type="hidden" name="payment_gap" value="'.($payment_gap).'">
                                    ';

                                if($payment["doctor_status"] > 0) {
                                    foreach ($this->patient_doctor_model->get_patient_doctor($payment_id) as $doctor) {

                                        if(array_key_exists($doctor["doctor_id"], $doctors)) {$damount = $doctors[$doctor["doctor_id"]]; }
                                        else {$damount = "";}
                                    $html .= '<div class="form-group row">
                                                <label for="doctor_id_'.($doctor["doctor_id"]).'" class="col-sm-5 col-form-label text-dark font-weight-bold">'.($doctor["last_name"] .' '.$doctor["first_name"]).'</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control js_debt_discount_field" id="doctor_id_'.($doctor["doctor_id"]).'" name="doctor_id['.($doctor["doctor_id"]).']" value="'.($damount).'">
                                                </div>
                                            </div>';
                                    }
                                }

                                if($payment["laboratory_status"] > 0 ) {
                                    if(!isset($laboratory_debt_off_type) || (isset($laboratory_debt_off_type) && $laboratory_debt_off_type == 0)) {
                                    $html .= '<div class="form-group row">
                                        <label for="laboratory" class="col-sm-5 col-form-label text-dark font-weight-bold">Laboratoriya</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control js_debt_discount_field" id="laboratory" name="laboratory" value="'.($laboratory > 0 ? $laboratory:"").'">
                                        </div>
                                    </div>';
                                    }

                                }

                                if($payment["uzi_status"] > 0) {
                                    if(!isset($uzi_debt_paid) || (isset($uzi_debt_paid) && $uzi_debt_paid == 0)) {
                                        $html .= '<div class="form-group row">
                                            <label for="uzi" class="col-sm-5 col-form-label text-dark font-weight-bold">Uzi</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control js_debt_discount_field" id="uzi" name="uzi" value="'.($uzi > 0 ? $uzi:"").'">
                                            </div>
                                        </div>';
                                    }
                                }

                                if($payment["service_status"] > 0) {
                                    if(!isset($xizmatlar_debt_paid) || (isset($xizmatlar_debt_paid) && $xizmatlar_debt_paid == 0)) {
                                    $html .= '<div class="form-group row">
                                                    <label for="service" class="col-sm-5 col-form-label text-dark font-weight-bold">Қўшимча хизматлар</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control js_debt_discount_field" id="service" name="service" value="'.($xizmatlar > 0 ? $xizmatlar:"").'">
                                                    </div>
                                                </div>';
                                    }
                                }
                        $html .= '<div class="text-center text-danger js_payment_gap_error_message"></div>';
                        $html .= '</form>
                            </div>
                        </div>                        
                    ';



            echo json_encode(array("html" => $html));
        }//end is_ajax
    }

    public function ajax_payment_debt_discount_add() {

        $type = $this->input->post("debt_discount_type") == "debt" ? 1:2;
        $payment_id = $this->input->post("payment_id");


        $arr = array();
        //step 1 - doctorlar borligini tekshiramiz
        if(isset($_POST["doctor_id"])) {
            foreach ($this->input->post("doctor_id") as $doctor_id => $amount) {
                if(!empty($amount)) {
                    $arr[$doctor_id] = array(
                        "type" => $type,
                        "payment_id" => $payment_id,
                        "service_type" => 1,
                        "doctor_id" => $doctor_id,
                        "amount" => $amount,
                        "debt_off_type" => 0,
                        );
                }
            }
        }

        //step 2 - laboratoriya borligini tekshiramiz
        if(isset($_POST["laboratory"])) {
            $arr[] = array(
                "type"          => $type,
                "payment_id"    => $payment_id,
                "service_type"  => 2,
                "doctor_id"     => null,
                "amount"        => $_POST["laboratory"],
                "debt_off_type" => 0,
            );
        }

        //step 3 - uzi borligini tekshiramiz
        if(isset($_POST["uzi"])) {
            $arr[] = array(
                "type"          => $type,
                "payment_id"    => $payment_id,
                "service_type"  => 3,
                "doctor_id"     => null,
                "amount"        => $_POST["uzi"],
                "debt_off_type" => 0,
            );
        }

        //step 4 - qushimcha xizmatlar borligini tekshiramiz
        if(isset($_POST["service"])) {
            $arr[] = array(
                "type"          => $type,
                "payment_id"    => $payment_id,
                "service_type"  => 4,
                "doctor_id"     => null,
                "amount"        => $_POST["service"],
                "debt_off_type" => 0,
            );
        }

        //debt_off_type 0 ga teng bulganlarini uchirib tashlaymiz,
        $this->payments_debt_discount_model->delete_for_update($payment_id, $type);
        //va boshidan kiritamiz, yani update qilamiz
        $this->payments_debt_discount_model->add_batch($arr);

        echo json_encode($arr);
    }

    //
    public function ajax_show_debt()
    {
        $payment_id = $this->input->post("payment_id");
        $payments = $this->patients_payments_model->get_patient_payment_details($payment_id);

        $paid = 0;
        foreach ($payments["details"] as $details) {
            $paid += $details["paid"];
        }
        $debt = $payments["total"] - ($paid + $payments["discount"]);

        $payment_services = $this->payments_debt_discount_model->get_payment_items($payment_id, 1);

        $html = '
		<div id="debt_content">
            <table class="table table-sm js_payment_table">
                <thead class="bg-dark text-white p-0 m-0">
                <tr>
                    <th><strong>Чек №: </strong></th>
                    <th><strong>ID: </strong></th>
                    <th><strong>ФИШ: </strong></th>
                    <th><strong>Жами: </strong></th>
                    <th><strong>Тўланди: </strong></th>
                    <th><strong>Қарз: </strong></th>
                </tr>
                </thead>
                <tbody class="bg-dark text-white">
                <tr>
                    <td>'.($payments["id"]).'</td>
                    <td>'.($payments["user_code"]).'</td>
                    <td>'.($payments["last_name"] .' '.$payments["first_name"]).'</td>
                    <td>'.(money_formatting($payments["total"])).'</td>
                    <td class="js_patient_paid">'.(money_formatting($paid)).'</td>
                    <td class="text-danger font-weight-bold font-18 js_patient_debt"><span class="badge badge-danger">'.(money_formatting($debt)).'</span></td>
                </tr>
                </tbody>
            </table>

            <table class="table table-bordered table-sm js_payment_details_table">
                <thead class="bg-dark text-white p-0 m-0">
                <tr>
                    <th>Тўлов санаси:</th>
                    <th>Нақд:</th>
                    <th>Пластик:</th>
                    <th>Терминал:</th>
                </tr>
                </thead>
                <tbody class="bg-dark text-white">';
        foreach ($payments["details"] as $payment) {
            $html .='
                    <tr>
                        <td>'.(date("d.m.Y H:i", strtotime($payment["created_date"]))).'</td>
                        <td>'.(money_formatting($payment["by_cash"])).'</td>
                        <td>'.(money_formatting($payment["by_card"])).'</td>
                        <td>'.(money_formatting($payment["by_bank"])).'</td>
                    </tr>
                    ';
        }
        $html .= '</tbody>
                </table>';


        if(count($payment_services) > 0) {
            $html .= '<h4 class="card-title text-center">Қарзни тўлаш</h4>
                <div class="p-4 pb-4">
                    
                    <form action="'.(site_url("admin/registry/ajax_payoff_debt")).'" method="post">
                        <input type="hidden" name="payment_id" value="'.($payments["id"]).'">
                        <input type="hidden" name="patient_debt" id="patient_debt" value="'.($debt).'">
                        <input type="hidden" name="paid_old" id="paid_old" value="'.($paid).'">';

            $html .= '<div class="row">
                    <div class="col-md-3 pl-0 pr-0"></div>
                    <div class="col-md-2 pl-0 pr-0">Нақд</div>
                    <div class="col-md-2 pl-0 pr-0">Пластик</div>
                    <div class="col-sm-2 pl-0 pr-0">Терминал</div>
                    <div class="col-sm-3 pl-0 pr-0"></div>
                </div>';
            foreach ($payment_services as $pservice) {
                if($pservice["service_type"] == 1 && $pservice["amount"] > 0) {

                    $html .= '
                        <div class="js_service_debt_row_block">
                            <div class="row js_service_debt_row">
                                <div class="col-md-3 pl-0 pr-0">
                                    <div class="form-group text-right pr-2">
                                        <strong class="text-primary">'.($pservice["doctor_last_name"] .' '.$pservice["doctor_first_name"]).'</strong>:
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" name="doctors['.($pservice["doctor_id"]).'][doctor_id]" value="'.($pservice["doctor_id"]).'">
                                <div class="col-md-2 pl-0 pr-0">
                                    <div class="form-group">
                                        <input type="text" class="form-control js_by_cash" name="doctors['.($pservice["doctor_id"]).'][by_cash]" value="">
                                    </div>
                                </div>
                                <div class="col-md-2 pl-0 pr-0">
                                    <div class="form-group">
                                        <input type="text" class="form-control js_by_card" name="doctors['.($pservice["doctor_id"]).'][by_card]" value="">
                                    </div>
                                </div>
                                <div class="col-sm-2 pl-0 pr-0">
                                    <div class="form-group">
                                        <input type="text" class="form-control js_by_bank" name="doctors['.($pservice["doctor_id"]).'][by_bank]" value="">
                                    </div>
                                </div>
                                <div class="offset-sm-1 col-sm-2 pl-0 pr-0">
                                    <div class="form-group js_service_debt" data-service-debt="'.($pservice["amount"]) .'"><strong class="font-18 text-danger">'.($pservice["amount"]) .'</strong> </div>
                                </div>
                            </div>
                            <div class="text-center pt-0 pb-3 "><small class="text-danger"></small></div>
                        </div>';

                }

                else if($pservice["service_type"] == 2 && $pservice["amount"] > 0) {//laboratoriya

                    $html .= '
                        <div class="js_service_debt_row_block">
                            <div class="row js_service_debt_row">
                                <div class="col-md-3 pl-0 pr-0">
                                    <div class="form-group text-right pr-2">
                                        <strong class="text-primary">Laboratoriya</strong>:
                                    </div>
                                </div>
                                
                                <div class="col-md-2 pl-0 pr-0">
                                    <div class="form-group">
                                        <input type="text" class="form-control js_by_cash" name="laboratory[by_cash]" value="">
                                    </div>
                                </div>
                                <div class="col-md-2 pl-0 pr-0">
                                    <div class="form-group">
                                        <input type="text" class="form-control js_by_card" name="laboratory[by_card]" value="">
                                    </div>
                                </div>
                                <div class="col-sm-2 pl-0 pr-0">
                                    <div class="form-group">
                                        <input type="text" class="form-control js_by_bank" name="laboratory[by_bank]" value="">
                                    </div>
                                </div>
                                <div class="offset-sm-1 col-sm-2 pl-0 pr-0">
                                    <div class="form-group js_service_debt" data-service-debt="'.($pservice["amount"]) .'"><strong class="font-18 text-danger">'.($pservice["amount"]) .'</strong></div>
                                </div>
                            </div>
                            <div class="text-center pt-0 pb-3 "><small class="text-danger"></small></div>
                        </div>';

                }


                else if($pservice["service_type"] == 3 && $pservice["amount"] > 0) {

                    $html .= '<div class="js_service_debt_row_block">
                            <div class="row js_service_debt_row">
                                <div class="col-md-3 pl-0 pr-0">
                                    <div class="form-group text-right pr-2">
                                        <strong class="text-primary">UZI</strong>:
                                    </div>
                                </div>
                                <div class="col-md-2 pl-0 pr-0">
                                    <div class="form-group">
                                        <input type="text" class="form-control js_by_cash" name="uzi[by_cash]" value="">
                                    </div>
                                </div>
                                <div class="col-md-2 pl-0 pr-0">
                                    <div class="form-group">
                                        <input type="text" class="form-control js_by_card" name="uzi[by_card]" value="">
                                    </div>
                                </div>
                                <div class="col-sm-2 pl-0 pr-0">
                                    <div class="form-group">
                                        <input type="text" class="form-control js_by_bank" name="uzi[by_bank]" value="">
                                    </div>
                                </div>
                                <div class="offset-sm-1 col-sm-2 pl-0 pr-0">
                                    <div class="form-group js_service_debt" data-service-debt="'.($pservice["amount"]) .'"><strong class="font-18 text-danger">'.($pservice["amount"]) .'</strong></div>
                                </div>
                            </div>
                            <div class="text-center pt-0 pb-3 "><small class="text-danger"></small></div>
                        </div>';

                }


				else if($pservice["service_type"] == 4 && $pservice["amount"] > 0) {

					$html .= '<div class="js_service_debt_row_block">
                            <div class="row js_service_debt_row">
                                <div class="col-md-3 pl-0 pr-0">
                                    <div class="form-group text-right pr-2">
                                        <strong class="text-primary">Xizmatlar</strong>:
                                    </div>
                                </div>
                                <div class="col-md-2 pl-0 pr-0">
                                    <div class="form-group">
                                        <input type="text" class="form-control js_by_cash" name="services[by_cash]" value="">
                                    </div>
                                </div>
                                <div class="col-md-2 pl-0 pr-0">
                                    <div class="form-group">
                                        <input type="text" class="form-control js_by_card" name="services[by_card]" value="">
                                    </div>
                                </div>
                                <div class="col-sm-2 pl-0 pr-0">
                                    <div class="form-group">
                                        <input type="text" class="form-control js_by_bank" name="services[by_bank]" value="">
                                    </div>
                                </div>
                                <div class="offset-sm-1 col-sm-2 pl-0 pr-0">
                                    <div class="form-group js_service_debt" data-service-debt="'.($pservice["amount"]) .'"><strong class="font-18 text-danger">'.($pservice["amount"]) .'</strong></div>
                                </div>
                            </div>
                            <div class="text-center pt-0 pb-3 "><small class="text-danger"></small></div>
                        </div>';

				}

				else if($pservice["service_type"] == 5 && $pservice["amount"] > 0) {

					$html .= '<div class="js_service_debt_row_block">
                            <div class="row js_service_debt_row">
                                <div class="col-md-3 pl-0 pr-0">
                                    <div class="form-group text-right pr-2">
                                        <strong class="text-primary">Xona Xizmati</strong>:
                                    </div>
                                </div>
                                <div class="col-md-2 pl-0 pr-0">
                                    <div class="form-group">
                                        <input type="text" class="form-control js_by_cash" name="room[by_cash]" value="">
                                    </div>
                                </div>
                                <div class="col-md-2 pl-0 pr-0">
                                    <div class="form-group">
                                        <input type="text" class="form-control js_by_card" name="room[by_card]" value="">
                                    </div>
                                </div>
                                <div class="col-sm-2 pl-0 pr-0">
                                    <div class="form-group">
                                        <input type="text" class="form-control js_by_bank" name="room[by_bank]" value="">
                                    </div>
                                </div>
                                <div class="offset-sm-1 col-sm-2 pl-0 pr-0">
                                    <div class="form-group js_service_debt" data-service-debt="'.($pservice["amount"]) .'"><strong class="font-18 text-danger">'.($pservice["amount"]) .'</strong></div>
                                </div>
                            </div>
                            <div class="text-center pt-0 pb-3 "><small class="text-danger"></small></div>
                        </div>';

				}
            }

            $html .= '<div class="text-center pt-0 pb-3 js_total_debt_error_message"><small class="text-danger"></small></div>';
            $html .= '<div class="offset-sm-6 col-sm-3 pl-0 pr-0">
                                            <div class="form-group">
                                                <button class="btn btn-primary debt_submit_btn js_payoff_debt_btn">Сақлаш ва ойнани ёпиш</button>
                                            </div>
                                        </div>';

            $html .= '<div class="row"><div class="col-sm-9 text-center"><small class="text-danger"></small></div></div>
								</form>
							</div>
						</div>
		';
        } else {
            $html .= '<div class="text-center p-4">Qarz taqsimlanmagan</div>';
        }

        echo json_encode($html);
    }


    /**
     * Bemor qarzini tulaganda
     * */
    public function ajax_payoff_debt() {
        if($this->input->is_ajax_request()){

            $payment_id     = $this->input->post("payment_id");
            $paid_old       = $this->input->post("paid_old");
            $patient_debt   = $this->input->post("patient_debt");

            //service_type: 1-doctor, 2-lab, 3-uzi, 4-services, 5-room, 6-dentist
            $counter = $by_cash = $by_card = $by_bank = 0;
            foreach ($this->input->post() as $service_name => $service_data) {
                if(is_array($service_data)) {

                    //agar doctor bulmasa
                    if($service_name == "doctors") {
                        foreach ($service_data as $doctor_id => $doctor_payment_data) {
                            $by_cash += (int)$doctor_payment_data["by_cash"];
                            $by_card += (int)$doctor_payment_data["by_card"];
                            $by_bank += (int)$doctor_payment_data["by_bank"];

                            $amount = (int)$doctor_payment_data["by_cash"] + (int)$doctor_payment_data["by_card"] + (int)$doctor_payment_data["by_bank"];

                            $debt_discount_arr[$counter] = array(
                                "type"          => 1,
                                "payment_id"    => $payment_id,
                                "service_type"  => 1,
                                "debt_off_type" => 1, // 1 degani bu qarz tulandi
                                "doctor_id"     => $doctor_id,
                                "amount"        => (-1 * $amount)
                            );

                            $counter++;
                        }
                    } else {

                        $by_cash += (int)$service_data["by_cash"];
                        $by_card += (int)$service_data["by_card"];
                        $by_bank += (int)$service_data["by_bank"];

                        $amount = (int)$service_data["by_cash"] + (int)$service_data["by_card"] + (int)$service_data["by_bank"];

                        $debt_discount_arr[$counter] = array(
                            "type"          => 1,
                            "payment_id"    => $payment_id,
                            "doctor_id"     => null,
                            "debt_off_type" => 1, // 1 degani bu qarz tulandi
                            "amount"        => (-1 * $amount), // 1 degani bu qarz tulandi
                        );

                    }

                    if ($service_name == "laboratory") {
                        $debt_discount_arr[$counter]["service_type"] = 2;
                    } else if ($service_name == "uzi") {
                        $debt_discount_arr[$counter]["service_type"] = 3;
                    } else if ($service_name == "services") {
                        $debt_discount_arr[$counter]["service_type"] = 4;
                    } else if ($service_name == "room") {
                        $debt_discount_arr[$counter]["service_type"] = 5;
                    }

                    //agar tuldirilmagan bulsa
                    if($amount == 0) {
                        unset($debt_discount_arr[$counter]);
                    }

                    $counter++;
                }
            }

            //barcha tuplangan malumotlarni birma-bir payments_debt_discount tablega qushamiz
            $this->payments_debt_discount_model->add_batch($debt_discount_arr);

            //tulangan qarz summasi
            $paid = $by_cash + $by_card + $by_bank;

            //tulangan qarzni patients_payments_details tablega kiritib qiyamiz
            $patients_payments_details = array(
                "payment_id"=> $payment_id,
                "paid"      => $paid,
                "by_cash"   => $by_cash,
                "by_card"   => $by_card,
                "by_bank"   => $by_bank,

            );

            $this->patients_payments_details_model->add($patients_payments_details);

            $paid_total = money_formatting($paid_old + $paid);
            $debt 		= money_formatting($patient_debt - $paid);

            $html ='
				<tr>
					<td>'.(date("d.m.Y H:i")).'</td>
					<td>'.(money_formatting($by_cash)).'</td>
					<td>'.(money_formatting($by_card)).'</td>
					<td>'.(money_formatting($by_bank)).'</td>
				</tr>
				';

            echo json_encode(array("html" => $html, "paid_total" => $paid_total, "debt" => $debt));
        }
    }



	function build_laboratory_results_table($payment_id, $created_date = false, $print = true, $clinic = true)
	{
		$this->load->model(array("patient_laboratories_model", "patients_model", "patients_payments_model"));
		$patient_active_labs = $this->patient_laboratories_model->lab_tree($payment_id, $clinic);

		if(count($patient_active_labs["laboratories"]) == 0) {
			return "";
		} else {
			$payment = $this->patients_payments_model->get_patient_payment($payment_id);
			$patient = $this->patients_model->get_patient($payment["patient_id"]);

			if(!$patient_active_labs) {
				$html = false;
			} else {
				$html = '';
				if($print) {
					$html = '
                        <div class="table table-responsive zmlogo w-100 page-header">
                            <!--<img src="'.site_url("assets/images/zmlogo.jpg").'" class="img-fluid w-100" alt="Responsive image">-->
                            <div class="lab_blank_header">
                                <div class="lab_blank_header_top">
                                    <div class="lab_blank_logo">
                                        <img src="'.site_url("assets/images/logoX300.png").'" alt="Logo" class="w-100">
                                    </div>
                                    <div class="lab_blank_header_text">
                                        <p class="mb-0 pb-1">Қўқон шахар "Hospital ZM" клиникаси</p>
                                        <p class="mb-0 pb-1">Мўлжал: Қўқон шахар ИИБ идораси орқасида</p>
                                        <p class="mb-0 pb-1">Телефонлар: +99890 3660003,      +998975660003</p>
                                        <p class="mb-0 pb-1">Веб сайт: <a class="text-info">www.andrology.uz</a> </p>
                                        <p class="mb-0 pb-1">Телеграм: <a class="text-info">@andrology_uz</a></p>
                                    </div>
                                </div>
                                
                                <div class="lab_blank_header_bottom mt-4">
                                    <table class="table table-bordered table-sm mb-3">
                                        <thead class="bg-light">
                                        <tr>
                                            <td width="25%">Исм</td>
                                            <td width="25%">Рўйхатга олинди</td>
                                            <td width="25%">Туғилган сана</td>
                                            <td width="25%">Чоп этилган сана</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <th width="25%">'.($patient['last_name'] . ' ' . $patient['first_name']).'</th>
                                            <th width="25%">'.(date_formating(strtotime($payment['created_date']), 'mt')).'</th>
                                            <th width="25%">'.(date("Y", strtotime($patient['dob']))).'</th>
                                            <th width="25%">'.(date("d.m.Y")).'</th>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>';

				}

				$html .=
					(!$clinic ? "<h4 class='w-100 text-center text-dark ".(!$clinic ? "hide_from_print":"")."'>Хамкор Лаборатория</h4>":"").
					'<table class="table border-0 table_page_header">
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


				$html .= '<div class="table-responsive1">';
				if($print && $clinic && ($payment["laboratory_status"] == 2)) {
					$html .= '<button type="button" class="btn btn-primary printBtn float-right mb-3"><span class="fa fa-print font-18"></span></button>';
				}

				if($created_date != false) {
					$html .= '<span class="time font-18">'.date("d.m.Y", strtotime($created_date)).'</span><hr>';
				}

//            $html .= '<h3 class="font-weight-bold text-dark">Лаборатория натижалари</h3>';
				foreach ($patient_active_labs["laboratories"] as $cat_id => $category) {

					foreach ($category["sub"] as $lab) {

						$isset_sub_labs = $patient_active_labs["sub_laboratories"][$lab["lab_id"]]["sub"];

						$html .= '<table class="table table-bordered table-sm mb-3 d-print-table print_page_break '.(!$clinic ? "hide_from_print":"").'">
                                <thead class="bg-light">
                                <tr>
                                    <th class="text-center" width="50%">Номи</th>
                                    <th class="text-center" width="25%">Натижа</th>
                                    <th class="text-center" width="25%">Норма</th>
                                </tr>
                                </thead>
                            <tbody>';
						if($lab["result"] != "#") {
							$html .='
                        <tr>
                            <td class="text-center"><strong>'.$lab["name"].'</strong></td>
                            <td class="text-center print_border result_cell">'.(count($isset_sub_labs) > 1 ? "":$lab["result"]).'</td>
                            <td class="text-center text-secondary">'.(count($isset_sub_labs) > 1 ? "":$lab["norma"]).'</td>
                        </tr>';
						}

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
								</tr>';
								}

							}
						}

						if(count($recommendation) > 0) {
							foreach ($recommendation as $rmd) {
								$html .= '<tr><td colspan="3" class="p-3 text-center">'.$rmd.'</td></tr>';
							}
						}
					}

					$html .= '</tbody> </table>';

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
                 </table>
                ';



			}

			return $html;
		}
	}

	public function reprint_cheque()
	{
		if($this->input->is_ajax_request()) {
			$payment_id = $this->input->post("payment_id");
			//Check chiqaramiz
			if(pos_print()) {
				try {
					$this->load->helper(array("lab_form"));
					$pr = print_receipt($payment_id);
					$this->load->library('ReceiptPrint');
					$user = $this->ion_auth->user()->row();
					$this->receiptprint->connect($this->config->item("pos_printer_name"));
					$this->receiptprint->print_receipt($pr["patient_data"], $pr["payment_data"], $pr["doctor_items"], $pr["laboratory_items"], $pr["uzi_items"], $pr["service_items"], $user);
				} catch (Exception $e) {
					log_message("error", "Error: Could not print. Message ".$e->getMessage());
					$this->receiptprint->close_after_exception();
				}
			}
		}
	}
}
