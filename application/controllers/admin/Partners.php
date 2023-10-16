<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Partners extends Admin_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model(array(
            "partners_model",
            "partners_bill_model",
            "regions_model",
            "cities_model",
            "service_modules_model",
            "service_modules_shares_model",
        ));

        $this->load->language("patients");
    }

    public function index() {

        $this->data['title'] = 'Хамкорлар';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="' . site_url("assets/admin/css/dataTables.bootstrap4.min.css") . '">
        <link rel="stylesheet" type="text/css" href="' . site_url("assets/admin/css/select2.min.css") . '">
        ';

        $this->data['before_appjs'] = '
                                        <script src="' . site_url("assets/admin/js/jquery.dataTables.min.js") . '"></script>
                                        <script src="' . site_url("assets/admin/js/dataTables.bootstrap4.min.js") . '"></script>
                                        <script src="' . site_url("assets/admin/js/select2.min.js") . '"></script>
                                        ';

        $this->data["partners"] = $this->partners_model->get_partners();
        $this->data["type_options"] = array("" => "--Танлаш--", 1 => "Жисмоний шахс", 2 => "Ташкилот");

        $this->render('admin/partners/index_view');
    }

    public function add() {

        $this->data["title"] = "Хамкор қўшиш";
        $this->data['before_themeStyle'] = '
                                        <link rel="stylesheet" type="text/css" href="' . site_url("assets/admin/css/select2.min.css") . '">
                                        <link rel="stylesheet" type="text/css" href="' . site_url("assets/admin/css/bootstrap-datetimepicker.min.css") . '">
                                        <link rel="stylesheet" type="text/css" href="' . site_url("assets/admin/js/lou_multi_select/multi-select.css") . '">
                                        ';

        $this->data['before_appjs'] = '
                                        <script src="' . site_url("assets/admin/js/moment.min.js") . '"></script>
                                        <script src="' . site_url("assets/admin/js/bootstrap-datetimepicker.min.js") . '"></script>
                                        <script src="' . site_url("assets/admin/js/lou_multi_select/jquery.multi-select.js") . '"></script>
                                        <script src="' . site_url("assets/admin/js/select2.min.js") . '"></script>
                                        ';

        $was_validated = "";
        $this->data["type_options"] = array("" => "--Танлаш--", 1 => "Жисмоний шахс", 2 => "Ташкилот");

        //Viloyatlar
        $this->data['regions'] = $this->regions_model->get_regions_array();
        $selected_region_id = $this->config->item("default_region_id"); //defaul_region_id = 3 ga (farg'ona)
        if (!is_null($this->input->post("region_id"))) {
            $selected_region_id = $this->input->post("region_id");
        }
        $this->data['selected_region_id'] = $selected_region_id;

        //Shaxarlar
        $selected_city_id = $this->config->item("default_city_id"); //defaul_city_id = 2 ga (quqonning ID si)
        if (!is_null($this->input->post("city_id"))) {
            $selected_city_id = $this->input->post("city_id");
        }
        $this->data['cities'] = $this->cities_model->get_cities_by_region_id($selected_region_id);
        $this->data['selected_city_id'] = $selected_city_id;

		$this->data['service_module_options'] = $this->service_modules_model->get_service_modules_array();
		$this->data['service_module_options'][0] = "--Танлаш--";
		ksort($this->data['service_module_options']);

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        $this->form_validation->set_rules('type', $this->lang->line('create_user_partner_department_label'), 'required');
        $this->form_validation->set_rules('gender', $this->lang->line('create_user_validation_gender'), 'trim');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|valid_email');
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');

        if ($this->form_validation->run() === TRUE) {

            $_POST['dob'] = date("Y-m-d", strtotime($this->input->post('dob')));

			$partner = $this->input->post();
			unset($partner["service_module_id"]);
			unset($partner["share"]);

			$partner_modules["service_module_id"] = $this->input->post("service_module_id");
			$partner_modules["share"] = $this->input->post("share");
			$partner_id = $this->partners_model->add($partner);

			for($i=0; $i<count($partner_modules["service_module_id"]); $i++){
				if($partner_modules["service_module_id"][$i]) {
					$arr = [
						"service_module_id" => $partner_modules["service_module_id"][$i],
						"partner_id" => $partner_id,
						"partner_type" => 1,
						"share" => $partner_modules["share"][$i]
					];

					$this->service_modules_shares_model->add($arr);
				}
			}



            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());

            redirect("admin/partners", 'refresh');
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            if (!empty($this->data['message'])) {
                $was_validated = "was-validated";
            }
            $this->data["was_validated"] = $was_validated;

            $this->data['first_name'] = [
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
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
                'class' => 'form-control datetimepicker',
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
                'name' => 'city_id',
                'id' => 'city_id',
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
            $this->data['company'] = [
                'name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'value' => $this->form_validation->set_value('company'),
                'class' => 'form-control',
            ];
            $this->data['job_title'] = [
                'name' => 'job_title',
                'id' => 'job_title',
                'type' => 'text',
                'value' => $this->form_validation->set_value('job_title'),
                'class' => 'form-control',
            ];
            $this->data['agreement'] = [
                'name' => 'agreement',
                'id' => 'agreement',
                'type' => 'text',
                'value' => $this->form_validation->set_value('agreement'),
                'class' => 'form-control',
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

            $this->data['type'] = [
                'name' => 'type',
                'id' => 'type',
                'type' => 'text',
                'value' => $this->form_validation->set_value('type'),
                'class' => 'custom-select',
            ];

			$this->data['service_module_id'][0] = [
				'name' => 'service_module_id[]',
				'value' => $this->form_validation->set_value('service_module_id'),
				'class' => 'custom-select', "required" => ""
			];

			$this->data['share'][0] = [
				'name' => 'share[]',
				'type' => 'text',
				'value' => $this->form_validation->set_value('share'),
				'class' => 'form-control',
			];

            $this->render("admin/partners/add_view");
        }
    }

    public function edit($id) {

        $this->data["title"] = "Бемор қўшиш";
        $this->data['before_themeStyle'] = '
                                        <link rel="stylesheet" type="text/css" href="' . site_url("assets/admin/css/select2.min.css") . '">
                                        <link rel="stylesheet" type="text/css" href="' . site_url("assets/admin/css/bootstrap-datetimepicker.min.css") . '">
                                        <link rel="stylesheet" type="text/css" href="' . site_url("assets/admin/js/lou_multi_select/multi-select.css") . '">
                                        ';

        $this->data['before_appjs'] = '
                                        <script src="' . site_url("assets/admin/js/moment.min.js") . '"></script>
                                        <script src="' . site_url("assets/admin/js/bootstrap-datetimepicker.min.js") . '"></script>
                                        <script src="' . site_url("assets/admin/js/lou_multi_select/jquery.multi-select.js") . '"></script>
                                        <script src="' . site_url("assets/admin/js/select2.min.js") . '"></script>
                                        ';

        $was_validated = "";
        $this->data["type_options"] = array("" => "--Танлаш--", 1 => "Жисмоний шахс", 2 => "Ташкилот");
        $partner = $this->partners_model->get_partner($id);
        $this->data["partner"] = $partner;

        //Viloyatlar
        $this->data['regions'] = $this->regions_model->get_regions_array();

        //Shaxarlar
        $this->data['cities'] = $this->cities_model->get_cities_by_region_id($partner["region_id"]);

		$partner_service_module = $this->service_modules_shares_model->get_partner_service_modules($id);
		$this->data["partner_service_module"] = $partner_service_module;

        $this->data['service_module_options'] = $this->service_modules_model->get_service_modules_array();
		$this->data['service_module_options'][0] = "--Танлаш--";
		ksort($this->data['service_module_options']);


        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        $this->form_validation->set_rules('type', $this->lang->line('create_user_partner_type_label'), 'required');
        $this->form_validation->set_rules('gender', $this->lang->line('create_user_validation_gender'), 'trim');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|valid_email');
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');

        if ($this->form_validation->run() === TRUE) {

            $_POST['dob'] = date("Y-m-d", strtotime($this->input->post('dob')));
            $partner = $this->input->post();
			unset($partner["service_module_id"]);
			unset($partner["share"]);

			$partner_modules["service_module_id"] = $this->input->post("service_module_id");
			$partner_modules["share"] = $this->input->post("share");
			$this->partners_model->update($id, $partner);

			$this->service_modules_shares_model->deleteByPartner($id, 1);
			for($i=0; $i<count($partner_modules["service_module_id"]); $i++){
				if($partner_modules["service_module_id"][$i]) {
					$arr = [
						"service_module_id" => $partner_modules["service_module_id"][$i],
						"partner_id" => $id,
						"partner_type" => 1,
						"share" => $partner_modules["share"][$i]
					];

					$this->service_modules_shares_model->add($arr);
				}
			}

            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());

            redirect("admin/partners", 'refresh');
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            if (!empty($this->data['message'])) {
                $was_validated = "was-validated";
            }
            $this->data["was_validated"] = $was_validated;

            $this->data['first_name'] = [
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('first_name', $partner["first_name"]),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['last_name'] = [
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name', $partner["last_name"]),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['surname'] = [
                'name' => 'surname',
                'id' => 'surname',
                'type' => 'text',
                'value' => $this->form_validation->set_value('surname', $partner["surname"]),
                "class" => "form-control"
            ];
            $this->data['email'] = [
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email', $partner["email"]),
                'class' => 'form-control'
            ];
            $this->data['dob'] = [
                'name' => 'dob',
                'id' => 'dob',
                'type' => 'text',
                'value' => $this->form_validation->set_value('dob', date("d.m.Y", strtotime($partner["dob"]))),
                'class' => 'form-control datetimepicker',
            ];
            $this->data['address'] = [
                'name' => 'address',
                'id' => 'address',
                'type' => 'text',
                'value' => $this->form_validation->set_value('address', $partner["address"]),
                'class' => 'form-control'
            ];
            $this->data['region'] = [
                'name' => 'region_id',
                'id' => 'region_id',
                'type' => 'text',
                'value' => $this->form_validation->set_value('region_id', $partner["region_id"]),
                'class' => 'custom-select',
                "required" => "required",
                "data-url" => site_url("admin/doctors/ajax_get_cities")
            ];

            $this->data['city'] = [
                'name' => 'city_id',
                'id' => 'city_id',
                'value' => $this->form_validation->set_value('city_id', $partner["city_id"]),
                'class' => 'custom-select',
                "required" => ""
            ];
            $this->data['phone'] = [
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone', $partner["phone"]),
                'class' => 'form-control',
            ];
            $this->data['company'] = [
                'name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'value' => $this->form_validation->set_value('company', $partner["company"]),
                'class' => 'form-control',
            ];
            $this->data['job_title'] = [
                'name' => 'job_title',
                'id' => 'job_title',
                'type' => 'text',
                'value' => $this->form_validation->set_value('job_title', $partner["job_title"]),
                'class' => 'form-control',
            ];
            $this->data['agreement'] = [
                'name' => 'agreement',
                'id' => 'agreement',
                'type' => 'text',
                'value' => $this->form_validation->set_value('agreement', $partner["agreement"]),
                'class' => 'form-control',
            ];
            $this->data['description'] = [
                'name' => 'description',
                'id' => 'description',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('description', $partner["description"]),
                'rows' => 5,
                'cols' => 30,
                'class' => 'form-control'
            ];
            $this->data['type'] = [
                'name' => 'type',
                'id' => 'type',
                'type' => 'text',
                'value' => $this->form_validation->set_value('type', $partner["type"]),
                'class' => 'select custom-select',
            ];;

			if(count($partner_service_module) == 0) {
				$this->data['service_module_id'][0] = [
					'name' => 'service_module_id[]',
					'value' => $this->form_validation->set_value('service_module_id'),
					'class' => 'custom-select',
					"required" => ""
				];

				$this->data['share'][0] = [
					'name' => 'share[]',
					'type' => 'text',
					'value' => $this->form_validation->set_value('share'),
					'class' => 'form-control',
					"required" => ""
				];
			} else {

				foreach ($partner_service_module as $key => $service_module) {
					$this->data['service_module_id'][$key] = [
						'name' => "service_module_id[]",
						'value' => $this->form_validation->set_value('service_module_id', $service_module["service_module_id"]),
						'class' => 'custom-select',
						"required" => ""
					];

					$this->data['share'][$key] = [
						'name' => "share[]",
						'type' => 'text',
						'value' => $this->form_validation->set_value('share',$service_module["share"]),
						'class' => 'form-control',
						"required" => ""
					];
				}

			}

            $this->render("admin/partners/edit_view");
        }
    }

    public function delete() {
        $id = $this->input->post("id");
        if (!is_null($this->input->post("confirm"))) {
            $result = $this->partners_model->delete($id);

            echo json_encode(array("deleted" => $result));
        } else {
            echo json_encode(false);
        }
    }

    public function reports($start_date = null, $end_date = null) {
        $this->data["title"] = "Хисоботлар";
        $start_date = is_null($start_date) ? date("Y-m-d") : date("Y-m-d", strtotime($start_date));
        $end_date = is_null($end_date) ? date("Y-m-d") : date("Y-m-d", strtotime($end_date));

        $start_date_param = date("Ymd", strtotime($start_date));
        $end_date_param = date("Ymd", strtotime($end_date));

        $this->data['before_themeStyle'] = '
            <link rel="stylesheet" type="text/css" href="' . site_url("assets/admin/css/dataTables.bootstrap4.min.css") . '">
            <link rel="stylesheet" type="text/css" href="' . site_url("assets/admin/css/select2.min.css") . '">
            <link rel="stylesheet" type="text/css" href="' . site_url("assets/admin/css/bootstrap-datetimepicker.min.css") . '">
        ';

        $this->data['before_appjs'] = '
                                        <script src="' . site_url("assets/admin/js/jquery.dataTables.min.js") . '"></script>
                                        <script src="' . site_url("assets/admin/js/dataTables.bootstrap4.min.js") . '"></script>
                                        <script src="' . site_url("assets/admin/js/select2.min.js") . '"></script>
                                        <script src="' . site_url("assets/admin/js/moment.min.js") . '"></script>
                                        <script src="' . site_url("assets/admin/js/bootstrap-datetimepicker.min.js") . '"></script>
                                        ';

        $this->form_validation->set_rules('start_date', 'start_date', 'trim');
        $this->form_validation->set_rules('end_date', 'end_date', 'trim');

        if ($this->form_validation->run() === TRUE) {

            $start_date = date_formating(strtotime($this->input->post("start_date")), "db");
            $end_date = date_formating(strtotime($this->input->post("end_date")), "db");

            $start_date_param = date("Ymd", strtotime($start_date));
            $end_date_param = date("Ymd", strtotime($end_date));
        }

        $this->data["partners"] = $this->partners_model->get_partners_share($start_date_param, $end_date_param);
        $this->data["partners_income"] = $this->partners_model->get_partners_share_by_month(date("Y-m-d")); //xamkorlarga bir oyda tulangan oylik
//        $this->data["partners_bill"] = $this->partners_bill_model->get_partners_bill();

        $this->data["start_date_param"] = $start_date_param;
        $this->data["end_date_param"] = $end_date_param;

        $this->data["sdate"] = strtotime($start_date);
        $this->data["edate"] = strtotime($end_date);

        $this->data['start_date'] = [
            'name' => 'start_date',
            'id' => 'start_date',
            'type' => 'text',
            'value' => $this->form_validation->set_value('start_date', date("d.m.Y", strtotime($start_date))),
            "class" => "form-control mb-2 mr-sm-2 datetimepicker-salary",
        ];
        $this->data['end_date'] = [
            'name' => 'end_date',
            'id' => 'end_date',
            'type' => 'text',
            'value' => $this->form_validation->set_value('end_date', date("d.m.Y", strtotime($end_date))),
            "class" => "form-control mb-2 mr-sm-2 datetimepicker-salary",
        ];

        $this->render('admin/partners/reports_view');
    }

    public function share_details($partner_id = null, $start_date = null, $end_date = null) {
		$this->data['before_themeStyle'] = '
            <link rel="stylesheet" type="text/css" href="' . site_url("assets/admin/css/dataTables.bootstrap4.min.css") . '">
            <link rel="stylesheet" type="text/css" href="' . site_url("assets/admin/css/select2.min.css") . '">
            <link rel="stylesheet" type="text/css" href="' . site_url("assets/admin/css/bootstrap-datetimepicker.min.css") . '">
        ';

		$this->data['before_appjs'] = '
                                        <script src="' . site_url("assets/admin/js/jquery.dataTables.min.js") . '"></script>
                                        <script src="' . site_url("assets/admin/js/dataTables.bootstrap4.min.js") . '"></script>
                                        <script src="' . site_url("assets/admin/js/select2.min.js") . '"></script>
                                        <script src="' . site_url("assets/admin/js/moment.min.js") . '"></script>
                                        <script src="' . site_url("assets/admin/js/bootstrap-datetimepicker.min.js") . '"></script>
                                        ';

        if (is_null($partner_id)) {
            redirect("admin/partners/reports");
        }

        $this->data["title"] = "Хамкорлар";

        $start_date = is_null($start_date) ? date("Y-m-d") : date("Y-m-d", strtotime($start_date));
        $end_date = is_null($end_date) ? date("Y-m-d") : date("Y-m-d", strtotime($end_date));

        $start_date_param = date("Ymd", strtotime($start_date));
        $end_date_param = date("Ymd", strtotime($end_date));

        $this->data["partner"] = $this->partners_model->get_partner($partner_id);
        $this->data["patients"] = $this->partners_model->get_partner_share_details($partner_id, $start_date, $end_date);
        $this->data["bills"] = $this->partners_bill_model->get_partners_bill($partner_id);


        $this->mybreadcrumb->add('Хамкорлар', site_url("admin/partners/reports/" . $start_date_param . "/" . $end_date_param));
        $this->mybreadcrumb->add('Бемор', "admin/partners/reports/share_details/");
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->render("admin/partners/share_details_view");
    }

	public function ajax_partner_checkout()
	{
//		$aa = $this->partners_bill_model->get_partners_bill();

		if($this->input->is_ajax_request() ) {

			$partner_id = $this->input->post("partner_id");
			$amount = $this->input->post("amount");

			$partner_bill_id = $this->partners_bill_model->add(array("partner_id" => $partner_id, "amount" => $amount));

			$partner = $this->partners_model->get_partner_share($partner_id);


			echo json_encode($partner);
		}

    }

}
