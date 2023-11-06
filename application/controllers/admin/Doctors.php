<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctors extends Admin_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model(
            array(
                "doctors_model",
                "doctors_types_model",
                "doctors_types_link_model",
                "regions_model",
                "cities_model",
                "employees_model",
                "departments_model",
                "service_modules_model",
                "service_modules_shares_model",
                "doctors_bill_model",
            )
        );
    }

    public function index() {
        $this->data['title'] = 'Шифокорлар';
        $result = $this->doctors_model->get_doctors_all();

        $doctors = array();
        $doctors_types = array();
        foreach ($result as $doctor) {
            $doctors[$doctor["id"]] = $doctor;
            $doctors_types[$doctor["id"]][$doctor["department_id"]] = $doctor["department_name"];
        }
        $this->data["doctors"]      = $doctors;
        $this->data["doctors_types"]  = $doctors_types;

        $this->render('admin/doctors/index_view');
        //$this->render(NULL, 'json'); ....if we want to render a json string. Also, if a request is made using ajax, we can simply do $this->render()
    }


    public function add()
    {
        $this->data["title"] = "Шифокор қўшиш";
        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">';

        $this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>';

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

        //Doktorlarning Mutaxassisligi
        $doctors_types = $this->doctors_types_model->get_doctors_types();
        $doctors_types_options = array("" => "--Танлаш--");
        foreach ($doctors_types as $dep) {
            $doctors_types_options[$dep["id"]] = $dep["name"];
        }
        $this->data["doctors_types_options"] = $doctors_types_options;

        //Departments
        $departments = $this->departments_model->get_departments();
        $department_options = array(0 => "-- Танлаш --");
        foreach ($departments as $dep) {
            $department_options[$dep["id"]] = $dep["name"];
        }
        $this->data["department_options"] = $department_options;

        //User groups
        $this->data["group_options"] = $this->config->item("doctor_groups_label");

        $was_validated = "";
        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;

        $prefix = "dr";
        $max_id = $this->doctors_model->get_max_id();

        $this->load->helper("mix");
        $code = uniqe_code_genetrator($prefix, $max_id, 4);

        $this->data["username"] = $code;
        $image_name = "";

		$this->data['service_module_options'] = $this->service_modules_model->get_service_modules_array();
		$this->data['service_module_options'][0] = "--Танлаш--";
		ksort($this->data['service_module_options']);

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        $this->form_validation->set_rules('surname', $this->lang->line('create_user_validation_surname'), 'trim');
        $this->form_validation->set_rules('gender', $this->lang->line('create_user_validation_gender'), 'required');
        if ($identity_column !== 'email')
        {
//            $this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|valid_email');
        }
        else
        {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('doctor_type_id', $this->lang->line('create_user_validation_doctors_types'), 'required');
        $this->form_validation->set_rules('dob', $this->lang->line("create_user_validation_dob"), 'trim|required');
        $this->form_validation->set_rules('price', $this->lang->line("create_user_validation_doctor_price"), 'required|integer');
        $this->form_validation->set_rules('agreement', $this->lang->line("create_user_validation_doctor_agreement"), 'required|integer');
        $this->form_validation->set_rules('address', $this->lang->line("create_user_validation_address"), 'trim');

        if ($this->form_validation->run() === TRUE)
        {
            $config['upload_path']          = EMPLOYEE_PHOTO_PATH;
            $config['allowed_types']        = 'gif|jpg|png';
            $config['max_size']             = 2048;
//            $config['max_width']            = 1024;
//            $config['max_height']           = 768;
            $config['file_name']            = "employee_".time();

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('userfile'))
            {
                $error = array('error' => $this->upload->display_errors());
            }
            else
            {
                $image = $this->upload->data();
                $image_name = $image["file_name"];
            }


            $email = strtolower($this->input->post('email'));
            $identity = ($identity_column === 'email') ? $email : $code;
            $password = "hospitalzm123";

            $additional_data = [
                'first_name'    => $this->input->post('first_name'),
                'last_name'     => $this->input->post('last_name'),
                'surname'       => $this->input->post('surname'),
                'dob'           => date("Y-m-d", strtotime($this->input->post('dob'))),
                'gender'        => $this->input->post('gender'),
                'address'       => $this->input->post('address'),
                'region_id'     => $this->input->post('region_id'),
                'city_id'       => $this->input->post('city_id'),
                'phone'         => $this->input->post('phone'),
                'photo'         => $image_name,
                'description'   => $this->input->post('description'),
                'active'        => $this->input->post('active'),
            ];

            $group = $this->input->post('group_id[]'); // Sets user to doctors.

        }


        //1. Users table ga qushish
        if ($this->form_validation->run() === TRUE && ($user_id = $this->ion_auth->register($identity, $password, $email, $additional_data, $group)))
        {
            //2. Employees tablega qushish | job_title_id = 3 -- Шифокор
            $employee_id = $this->employees_model->add(
            	[
            		"user_id" => $user_id,
					"job_title_id" => 3,
					"is_doctor" => 1,
					"department_id" => $this->input->post('department_id'),
					"active" => $this->input->post('active')
				]
			);

            //3. doctors_types_link Table ga qushish
            $this->doctors_types_link_model->assign_doctor_type(array("employee_id" => $employee_id, "doctor_type_id" => $this->input->post("doctor_type_id"), "price" => $this->input->post("price"), "agreement" => $this->input->post("agreement")));


			$partner = $this->input->post();
			unset($partner["service_module_id"]);
			unset($partner["share"]);

			$partner_modules["service_module_id"] = $this->input->post("service_module_id");
			$partner_modules["share"] = $this->input->post("share");
//			$partner_id = $this->partners_model->add($partner);

			for($i=0; $i<count($partner_modules["service_module_id"]); $i++){
				if($partner_modules["service_module_id"][$i]) {
					$arr = [
						"service_module_id" => $partner_modules["service_module_id"][$i],
						"partner_id" => $employee_id,
						"partner_type" => 2,
						"share" => $partner_modules["share"][$i]
					];

					$this->service_modules_shares_model->add($arr);
				}
			}


            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("admin/doctors", 'refresh');
        }
        else
        {
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
            $this->data['price'] = [
                'name' => 'price',
                'id' => 'price',
                'type' => 'text',
                'value' => $this->form_validation->set_value('price'),
                'class' => 'form-control',
                "required" => "required"
            ];
            $this->data['dob'] = [
                'name' => 'dob',
                'id' => 'dob',
                'type' => 'text',
                'value' => $this->form_validation->set_value('dob'),
                'class' => 'form-control datetimepicker',
                "required" => "required"
            ];
            $this->data['gender_male'] = [
                'name'  => 'gender',
                'id'    => 'gender_male',
                'class' => "form-check-input",
                "required" => "required"
            ];
            $this->data['gender_female'] = [
                'name'  => 'gender',
                'id'    => 'gender_female',
                'class' => "form-check-input",
                "required" => "required"
            ];
            $this->data['doctors_types'] = [
                'name' => 'doctor_type_id',
                'id' => 'doctor_type_id',
                'class' => 'form-control',
                "required" => "required"
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
                'value' => $this->form_validation->set_select('region_id'),
                'class' => 'select! custom-select',
                "required" => "required",
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
            $this->data['description'] = [
                'name' => 'description',
                'id' => 'description',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('description'),
                'rows' => 5,
                'cols' => 30,
                'class' => 'form-control'
            ];

            $this->data['agreement'] = [
                'name' => 'agreement',
                'id' => 'agreement',
                'type' => 'text',
                'value' => $this->form_validation->set_value('agreement'),
                'class' => 'form-control',
                "required" => "required"
            ];

            $this->data['groups'] = [
                'name' => 'group_id[]',
                'id' => 'group_id',
                'class' => 'form-control',
                "required" => "required"
            ];

            $this->data['departments'] = [
                'name' => 'department_id',
                'id' => 'department_id',
                'value' => $this->form_validation->set_value('department_id'),
                'class' => 'form-control',
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

            $this->render("admin/doctors/add_view");
        }
    }

    public function edit($employee_id)
    {
        $this->data["title"] = "Шифокорни маълумотларини тахрирлаш";
        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">';

        $this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>';

        $doctor = $this->doctors_model->get_doctor($employee_id);
        $this->data["doctor"] = $doctor;
        //Viloyatlar
        $this->data['regions'] = $this->regions_model->get_regions_array();

        //Shaxarlar
        $this->data['cities'] = $this->cities_model->get_cities_by_region_id($doctor["region_id"]);

        //Doktorlarning Mutaxassisligi
        $doctors_types = $this->doctors_types_model->get_doctors_types();
        $doctors_types_options = array("" => "--Танлаш--");
        foreach ($doctors_types as $dep) {
            $doctors_types_options[$dep["id"]] = $dep["name"];
        }
        $this->data["doctors_types_options"] = $doctors_types_options;

        //Departments
        $departments = $this->departments_model->get_departments();
        $department_options = array(0 => "-- Танлаш --");
        foreach ($departments as $dep) {
            $department_options[$dep["id"]] = $dep["name"];
        }
        $this->data["department_options"] = $department_options;

        //User groups
        $this->data["group_options"] = $this->config->item("doctor_groups_label");

        $was_validated = "";
        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;
        $image_name = "";


		$partner_service_module = $this->service_modules_shares_model->get_doctor_service_modules($employee_id);
		$this->data["partner_service_module"] = $partner_service_module;

		$this->data['service_module_options'] = $this->service_modules_model->get_service_modules_array();
		$this->data['service_module_options'][0] = "--Танлаш--";
		ksort($this->data['service_module_options']);


        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        $this->form_validation->set_rules('surname', $this->lang->line('create_user_validation_surname'), 'trim');
        $this->form_validation->set_rules('gender', $this->lang->line('create_user_validation_gender'), 'required');
        if ($identity_column !== 'email')
        {
//            $this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|valid_email');
        }
        else
        {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('doctor_type_id', $this->lang->line('create_user_validation_doctors_types'), 'required');
        $this->form_validation->set_rules('dob', $this->lang->line("create_user_validation_dob"), 'trim|required');
        $this->form_validation->set_rules('price', $this->lang->line("create_user_validation_doctor_price"), 'required|integer');
        $this->form_validation->set_rules('address', $this->lang->line("create_user_validation_address"), 'trim');

        if ($this->form_validation->run() === TRUE)
        {
            $config['upload_path']          = EMPLOYEE_PHOTO_PATH;
            $config['allowed_types']        = 'gif|jpg|png';
            $config['max_size']             = 2048;
//            $config['max_width']            = 1024;
//            $config['max_height']           = 768;
            $config['file_name']            = "employee_".time();

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('userfile'))
            {
                $error = array('error' => $this->upload->display_errors());
            }
            else
            {
                $image = $this->upload->data();
                $image_name = $image["file_name"];
            }

            $additional_data = [
                'first_name'    => $this->input->post('first_name'),
                'last_name'     => $this->input->post('last_name'),
                'surname'       => $this->input->post('surname'),
                'dob'           => date("Y-m-d", strtotime($this->input->post('dob'))),
                'gender'        => $this->input->post('gender'),
                'address'       => $this->input->post('address'),
                'region_id'     => $this->input->post('region_id'),
                'city_id'       => $this->input->post('city_id'),
                'phone'         => $this->input->post('phone'),
                'description'   => $this->input->post('description'),
                'active'        => $this->input->post('active'),
                'email'         => strtolower($this->input->post('email'))
            ];

            if(!empty($image_name))
            {
                $additional_data['photo'] = $image_name;
            }

            // pass an array of group ID's and user ID
            $this->ion_auth->remove_from_group(NULL, $doctor["user_id"]);
            $this->ion_auth->add_to_group($this->input->post('group_id'), $doctor["user_id"]);
        }


        if ($this->form_validation->run() === TRUE)
        {
            //1. Users table ni tahrirlash
            $this->ion_auth->update($doctor["user_id"], $additional_data);
            //2. doctors_types_link Table da tahrirlash
            $this->doctors_types_link_model->update_doctor_type_link($doctor["doctors_types_link_id"], array("doctor_type_id" => $this->input->post("doctor_type_id"), "price" => $this->input->post("price"), "agreement" => $this->input->post("agreement")));
            //3. employee table update
            $this->employees_model->update($employee_id, array("department_id" => $this->input->post('department_id'), "active" => $this->input->post('active')));



			$partner_modules["service_module_id"] = $this->input->post("service_module_id");
			$partner_modules["share"] = $this->input->post("share");

			$this->service_modules_shares_model->deleteByPartner($employee_id, 2);
			for($i=0; $i<count($partner_modules["service_module_id"]); $i++){
				if($partner_modules["service_module_id"][$i]) {
					$arr = [
						"service_module_id" => $partner_modules["service_module_id"][$i],
						"partner_id" => $employee_id,
						"partner_type" => 2,//sender doctor id
						"share" => $partner_modules["share"][$i]
					];

					$this->service_modules_shares_model->add($arr);
				}
			}


            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("admin/doctors", 'refresh');
        }
        else
        {
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
                'value' => $this->form_validation->set_value('first_name', $doctor["first_name"]),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['last_name'] = [
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name', $doctor["last_name"]),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['surname'] = [
                'name' => 'surname',
                'id' => 'surname',
                'type' => 'text',
                'value' => $this->form_validation->set_value('surname', $doctor["surname"]),
                "class" => "form-control"
            ];
            $this->data['email'] = [
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email', $doctor["email"]),
                'class' => 'form-control'
            ];
            $this->data['price'] = [
                'name' => 'price',
                'id' => 'price',
                'type' => 'text',
                'value' => $this->form_validation->set_value('price', $doctor["price"]),
                'class' => 'form-control',
//                "required" => "required"
            ];
            $this->data['dob'] = [
                'name' => 'dob',
                'id' => 'dob',
                'type' => 'text',
                'value' => $this->form_validation->set_value('dob', date("d.m.Y", strtotime($doctor["dob"]))),
                'class' => 'form-control datetimepicker',
                "required" => "required"
            ];
            $this->data['gender_male'] = [
                'name'  => 'gender',
                'id'    => 'gender_male',
                'class' => "form-check-input"
            ];
            $this->data['gender_female'] = [
                'name'  => 'gender',
                'id'    => 'gender_female',
                'class' => "form-check-input"
            ];
            $this->data['doctors_types'] = [
                'name' => 'doctor_type_id',
                'id' => 'doctor_type_id',
                'value' => $this->form_validation->set_value('doctor_type_id', $doctor["doctor_type_id"]),
                'class' => 'form-control',
                "required" => "required"
            ];
            $this->data['address'] = [
                'name' => 'address',
                'id' => 'address',
                'type' => 'text',
                'value' => $this->form_validation->set_value('address', $doctor["address"]),
                'class' => 'form-control'
            ];
            $this->data['region'] = [
                'name' => 'region_id',
                'id' => 'region_id',
                'type' => 'text',
                'value' => $this->form_validation->set_select('region_id'),
                'class' => 'custom-select',
                "required" => "required",
                "data-url" => site_url("admin/doctors/ajax_get_cities")
            ];
            $this->data['city'] = [
                'name'  => 'city_id',
                'id'    => 'city_id',
                'value' => $this->form_validation->set_select('city_id'),
                'class' => 'custom-select',
                "required" => ""
            ];
            $this->data['phone'] = [
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone', $doctor["phone"]),
                'class' => 'form-control',
            ];
            $this->data['description'] = [
                'name' => 'description',
                'id' => 'description',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('description', $doctor["description"]),
                'rows' => 5,
                'cols' => 30,
                'class' => 'form-control'
            ];
            $this->data['agreement'] = [
                'name' => 'agreement',
                'id' => 'agreement',
                'type' => 'text',
                'value' => $this->form_validation->set_value('agreement', $doctor["agreement"]),
                'class' => 'form-control',
                "required" => "required"
            ];
            $this->data['groups'] = [
                'name' => 'group_id',
                'id' => 'group_id',
                'class' => 'form-control',
                "required" => "required"
            ];

            $this->data['departments'] = [
                'name' => 'department_id',
                'id' => 'department_id',
                'value' => $this->form_validation->set_value('department_id', $doctor["department_id"]),
                'class' => 'form-control',
                "required" => "required"
            ];

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

            $this->render("admin/doctors/edit_view");
        }
    }

    public function profile($id) {
        $this->mybreadcrumb->add('Шифокорлар', site_url("admin/doctors"));
        $this->mybreadcrumb->add('Шифокор', "admin/doctors/profile");
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->data["title"] = "Шифокор";
        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">';

        $this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>';

        $result = $this->doctors_model->get_doctor($id);
        $this->data["doctor"] = $result;

        $gender = array(1=>"Эркак", 2 => "Аёл");
        $this->data["gender"] = $gender;

        $this->render("admin/doctors/profile_view");
    }

    public function ajax_get_cities()
    {
        $region_id = $this->input->post("region_id");

        $cities = $this->cities_model->get_cities_by_region_id($region_id);

        $cities_options = "";
        foreach ($cities as $city_id => $city) {
            $cities_options .= '<option value="'.$city_id.'">'.$city.'</option>';
        }

        echo json_encode($cities_options);
    }

    /**
     * @var boolean linked - usbu item boshqa table larga bog'langanmi yoqmiligini bildiradi (true - bog'langan, false - bog'lanmagan)
     **/
    public function delete()
    {
        $employee_id = $this->input->post("id");
        if(!is_null($this->input->post("confirm")))
        {
            $user_id = $this->employees_model->get_user_id($employee_id);

            //1. Users table dan uchiramiz
            $this->ion_auth->delete_user($user_id);
            //2. Doctors_types_link table dan uchiramiz
            $this->doctors_types_link_model->delete($employee_id);
            //3.Employees tabledan uchiramiz
            $deleted = $this->employees_model->delete($employee_id);
            echo json_encode(array("deleted" => $deleted));
        }
        else
        {
            $linked = $this->doctors_model->check_links($employee_id);
            if($linked > 0) {
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }
        }
    }

	public function ajax_doctor_checkout()
	{
		if($this->input->is_ajax_request() ) {

			$result = array();
			$this->form_validation->set_rules('amount', "Суммани киритиш", 'trim|required|integer');
			$this->form_validation->set_rules('payment_type_id', "Тўлов турини танлаш", 'trim|required');
			$this->form_validation->set_rules('doctor_id', "Шифокорни танлаш", 'trim|required');

			if ($this->form_validation->run() === TRUE) {

				$result["errors"] = false;

				$this->user_id = $this->session->userdata("user_id");
				$doctor_id    = $this->input->post("doctor_id");

				$arr = [
					"doctor_id" 		=> $doctor_id,
					"amount" 			=> $this->input->post("amount"),
					"payment_type_id" 	=> $this->input->post("payment_type_id"),
					"user_id" 			=> $this->user_id,
					"from_cash" 		=> $this->input->post("from_cash"),
				];

				$doctor_bill = $this->doctors_bill_model->add($arr);

				$result["success"] = $doctor_bill;
			} else {
				$result["errors"] = $this->form_validation->error_array();
			}

			echo json_encode($result);

		}

	}

	public function ajax_show_doctor_bills()
	{
		$doctor_id  = $this->input->post("partner_id");
		$startDate 	= $this->input->post("start_date");
		$endDate 	= $this->input->post("end_date");

		$result = $this->doctors_bill_model->get_doctor_bills_by_date($doctor_id, $startDate, $endDate, 1);

		$name = "";
		$html = "";
		$total = 0;
		foreach ($result as $payment) {
			$total +=$payment["amount"];
			$name = $payment["doctor_last_name"]." ".$payment["doctor_first_name"];
			$html .= "
			<tr data-id='".$payment["id"]."' data-partner-id='".$doctor_id."'>
						<td>".date("d.m.Y H:s", strtotime($payment["created_date"]))."</td> 
						<td class='js_partners_checkout__amount' style='width: 300px'>
							<span>".$payment["amount"]."</span>
							<input type='text' class='d-none' value='".$payment["amount"]."'>
						</td>
						<td>".$payment["payment_type"]."</td>
						<td>".$payment["user_last_name"]." ".$payment["user_first_name"]."</td>
						<td class='text-right'>
							<div class='btn-group'>
								<button type='button' class='btn btn-primary btn-sm d-none js_partners_checkout_btn js_partners_checkout__save'
								data-url='".site_url("admin/doctors/ajax_doctor_bill_update")."'
								>
									<span class='fa fa-check'></span>
								</button>
								<button type='button' class='btn btn-danger btn-sm d-none js_partners_checkout_btn js_partners_checkout__cancel'>
									<span class='fa fa-minus'></span>
								</button>
								<button type='button' class='btn btn-primary btn-sm js_partners_checkout_btn js_partners_checkout__edit'>
									<span class='fa fa-pencil'></span>
								</button>
								<button type='button' class='btn btn-danger btn-sm js_partners_checkout_btn js_partners_checkout__remove'
								data-url='".site_url("admin/doctors/ajax_doctor_bill_delete")."'
								>
									<span class='fa fa-remove'></span>
								</button>
							</div>
						</td>
					</tr>
			";
		}

		echo json_encode(["name" => $name, "html" => $html, "total" => $total]);
	}

	public function ajax_doctor_bill_update() {
		$id = $this->input->post("bill_id");
		$amount = $this->input->post("amount");

		$this->doctors_bill_model->update($id, ['amount' => $amount]);

		/** ***********************************  **/

		$view = $this->doctors_monthly_report();

		/** ***********************************  **/

		echo json_encode(["view" => $view, "report" => "doctors"]);
	}

	public function ajax_doctor_bill_delete() {
		$id = $this->input->post("bill_id");
		$this->doctors_bill_model->delete($id);
		$view = $this->doctors_monthly_report();

		echo json_encode(["view" => $view, "report" => "doctors"]);

	}

	public function doctors_monthly_report()
	{
		$now = new DateTime("now");
		$m_start_date = $now->format("Y-m-1");
		$m_end_date = $now->format("Y-m-t");
		$this->data['m_start_date'] = $m_start_date;
		$this->data['m_end_date'] 	= $m_end_date;

		$service_modules = $this->service_modules_model->get_service_modules_array();

		$doctors = $this->doctors_model->get_doctors_all();

		$doctors_array= [];
		foreach ($doctors as $doctor) {
			$doctors_array[$doctor["id"]] = $doctor["last_name"]." ".$doctor["first_name"];
		}
		$this->data["doctors_array"] = $doctors_array;

		//Doctors Report
		$this->load->library('reports/DoctorsReport');
		$doctorsReportObj = new DoctorsReport();

		$doctorsMonthlyReportParams = [
			"start_date" 	  => $m_start_date,
			"end_date"   	  => $m_end_date,
			"service_modules" => $service_modules,
			"doctors" 		  => $doctors,
		];
		$this->data["sender_doctors_monthly_report"] = $doctorsReportObj->show($doctorsMonthlyReportParams);

		$view = $this->load->view("admin/reports/monthly_reports/doctors", $this->data, true);

		return $view;
	}

}
