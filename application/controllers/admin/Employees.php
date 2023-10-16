<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees extends Admin_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            "employees_model",
            "regions_model",
            "cities_model",
            "job_titles_model",
            "doctors_types_model",
            "departments_model",
            )
        );
    }

    public function index()
    {
        $this->data['title'] = 'Ходимлар';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                            ';

        $this->data["employees"] = $this->employees_model->get_employees();


        $this->render('admin/employees/index_view');
        //$this->render(NULL, 'json'); ....if we want to render a json string. Also, if a request is made using ajax, we can simply do $this->render()
    }

    public function add()
    {
        $this->data["title"] = "Ходим қўшиш";
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

        //Xodimning Mutaxassisligi
        $job_titles = $this->job_titles_model->get_jobtitles();
        $job_titles_options = array("" => "--Танлаш--");
        foreach ($job_titles as $jt) {
            $job_titles_options[$jt["id"]] = $jt["name"];
        }
        $this->data["job_titles_options"] = $job_titles_options;

        //User groups
        $groups = $this->ion_auth->groups()->result_array();
        $groups_options = array("" => "--Танлаш--");
        foreach ($groups as $g) {
            if(!$g["hidden"]) $groups_options[$g["id"]] = $g["description"];
        }
        asort($groups_options);
        $this->data["groups_options"] = $groups_options;

        //Departments
        $departments = $this->departments_model->get_departments();
        $department_options = array(0 => "-- Танлаш --");
        foreach ($departments as $dep) {
            $department_options[$dep["id"]] = $dep["name"];
        }
        $this->data["department_options"] = $department_options;

        $was_validated = "";
        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;

        $prefix = "emp";
        $max_id = $this->employees_model->get_max_id();

        $this->load->helper("mix");
        $code = uniqe_code_genetrator($prefix, $max_id, 4);

        $this->data["username"] = $code;
        $image_name = "";

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        $this->form_validation->set_rules('surname', $this->lang->line('create_user_validation_surname'), 'trim');
        $this->form_validation->set_rules('gender', $this->lang->line('create_user_validation_gender'), 'required');
        if ($identity_column !== 'email')
        {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|valid_email');
        }
        else
        {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('dob', $this->lang->line("create_user_validation_dob"), 'trim|required');
        $this->form_validation->set_rules('address', $this->lang->line("create_user_validation_address"), 'trim');
        $this->form_validation->set_rules('job_title_id', $this->lang->line("create_user_validation_jobtitle"), 'trim');
        $this->form_validation->set_rules('group_id', $this->lang->line("create_user_validation_doctor_group"), 'required');

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
                'email'         => $this->input->post('email'),
                'photo'         => $image_name,
                'description'   => $this->input->post('description'),
                'active'        => $this->input->post('status'),
            ];

            $group = array($this->input->post("group_id"));

        }

        //1. Users table ga qushish
        if ($this->form_validation->run() === TRUE && ($user_id = $this->ion_auth->register($identity, $password, $email, $additional_data, $group)))
        {
            //2. Employees tablega qushish | job_title_id = 3 -- Шифокор
            $employee_id = $this->employees_model->add(
                array(
                    "user_id" => $user_id,
                    "job_title_id" => $this->input->post('job_title_id'),
                    "is_doctor" => 0,
                    "department_id" => $this->input->post("department_id")
                )
            );

            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("admin/employees", 'refresh');
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
            $this->data['job_title'] = [
                'name' => 'job_title_id',
                'id' => 'job_title_id',
                'class' => 'form-control',
                'value' => $this->form_validation->set_select('job_title_id')
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
            $this->data['groups'] = [
                'name' => 'group_id',
                'id' => 'group_id',
                'class' => 'form-control',
                "required" => "required"
            ];

            $this->data['departments'] = [
                'name' => 'department_id',
                'id' => 'department_id',
                'value' => $this->form_validation->set_value('department_id'),
                'class' => 'form-control',
                "required" => "required"
            ];


            $this->render("admin/employees/add_view");
        }
    }

    public function edit($id)
    {
        $this->data["title"] = "Ходимни тахрирлаш";
        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">';

        $this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>';

        $employee = $this->employees_model->get_employee($id);
        $this->data["employee"] = $employee;

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

        //Xodimning Mutaxassisligi
        $job_titles = $this->job_titles_model->get_jobtitles();
        $job_titles_options = array("" => "--Танлаш--");
        foreach ($job_titles as $jt) {
            $job_titles_options[$jt["id"]] = $jt["name"];
        }
        $this->data["job_titles_options"] = $job_titles_options;

        //User groups
        $groups = $this->ion_auth->groups()->result_array();
        $groups_options = array("" => "--Танлаш--");
        foreach ($groups as $g) {
            if(!$g["hidden"]) $groups_options[$g["id"]] = $g["description"];
        }
        asort($groups_options);
        $this->data["groups_options"] = $groups_options;

        //Departments
        $departments = $this->departments_model->get_departments();
        $department_options = array(0 => "-- Танлаш --");
        foreach ($departments as $dep) {
            $department_options[$dep["id"]] = $dep["name"];
        }
        $this->data["department_options"] = $department_options;

        $was_validated = "";
        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;

        $code = $employee["username"];

        $this->data["username"] = $code;
        $image_name = "";

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        $this->form_validation->set_rules('surname', $this->lang->line('create_user_validation_surname'), 'trim');
        $this->form_validation->set_rules('gender', $this->lang->line('create_user_validation_gender'), 'required');
        if ($identity_column !== 'email')
        {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|valid_email');
        }
        else
        {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('dob', $this->lang->line("create_user_validation_dob"), 'trim|required');
        $this->form_validation->set_rules('address', $this->lang->line("create_user_validation_address"), 'trim');
        $this->form_validation->set_rules('job_title_id', $this->lang->line("create_user_validation_jobtitle"), 'trim');
        $this->form_validation->set_rules('group_id', $this->lang->line("create_user_doctor_group_validation_label"), 'required');

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
                'email'         => $this->input->post('email'),
                'photo'         => $image_name,
                'description'   => $this->input->post('description'),
                'active'        => $this->input->post('status'),
            ];

        }

        //1. Users table ga qushish
        if ($this->form_validation->run() === TRUE)
        {
            //1. update user table
            $this->ion_auth->update($employee["user_id"], $additional_data);

            //2. update users_groups table
            if($employee["group_id"] != $this->input->post("group_id") && !empty($this->input->post("group_id"))) {
                $this->ion_auth->remove_from_group(NULL, $employee['user_id']);
                $this->ion_auth->add_to_group($this->input->post("group_id"), $employee['user_id']);
            }

            //3. update employee table
            $this->employees_model->update($employee["id"], array("job_title_id" => $this->input->post("job_title_id"), "department_id" => $this->input->post("department_id")));

            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("admin/employees", 'refresh');
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
                'value' => $this->form_validation->set_value('first_name', $employee["first_name"]),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['last_name'] = [
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name', $employee["last_name"]),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['surname'] = [
                'name' => 'surname',
                'id' => 'surname',
                'type' => 'text',
                'value' => $this->form_validation->set_value('surname', $employee["surname"]),
                "class" => "form-control"
            ];
            $this->data['email'] = [
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email', $employee["email"]),
                'class' => 'form-control'
            ];
            $this->data['dob'] = [
                'name' => 'dob',
                'id' => 'dob',
                'type' => 'text',
                'value' => $this->form_validation->set_value('dob', date("d.m.Y", strtotime($employee["dob"]))),
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
            $this->data['job_title'] = [
                'name' => 'job_title_id',
                'id' => 'job_title_id',
                'class' => 'form-control',
                'value' => $this->form_validation->set_select('job_title_id', $employee["job_title_id"])
            ];
            $this->data['address'] = [
                'name' => 'address',
                'id' => 'address',
                'type' => 'text',
                'value' => $this->form_validation->set_value('address', $employee["address"]),
                'class' => 'form-control'
            ];
            $this->data['region'] = [
                'name' => 'region_id',
                'id' => 'region_id',
                'type' => 'text',
                'value' => $this->form_validation->set_select('region_id', $employee["region_id"]),
                'class' => 'custom-select',
                "required" => "required",
                "data-url" => site_url("admin/doctors/ajax_get_cities")
            ];
            $this->data['city'] = [
                'name'  => 'city_id',
                'id'    => 'city_id',
                'value' => $this->form_validation->set_select('city_id', $employee["city_id"]),
                'class' => 'custom-select',
                "required" => ""
            ];
            $this->data['phone'] = [
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone', $employee["phone"]),
                'class' => 'form-control',
            ];
            $this->data['description'] = [
                'name' => 'description',
                'id' => 'description',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('description', $employee["description"]),
                'rows' => 5,
                'cols' => 30,
                'class' => 'form-control'
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
                'value' => $this->form_validation->set_value('department_id', $employee["department_id"]),
                'class' => 'form-control',
                "required" => "required"
            ];


            $this->render("admin/employees/edit_view");
        }
    }

    public function delete()
    {
        if(!is_null($this->input->post("confirm")))
        {
            $id = $this->input->post("id");
            $user_id = $this->employees_model->get_user_id($id);
            //1. Users table dan uchiramiz
            $this->ion_auth->delete_user($user_id);
            //2. Employees tabledan uchiramiz
            $deleted = $this->employees_model->delete($id);
            echo json_encode(array("deleted" => $deleted));
        } else {
            echo json_encode(false);
        }
    }
}
