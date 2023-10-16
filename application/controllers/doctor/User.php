<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Doctor_Controller {

    private $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model(array("users_model"));
        $this->user_id = $this->session->userdata("user_id");
    }

    public function index()
    {
        $this->data["title"] = "Менинг профилим";

        $this->data["user"] = $this->users_model->get_user($this->user_id);


        $this->render('doctor/user/profile_view');
    }

    public function edit()
    {
        $this->data["title"] = "Тахрирлаш";
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">';

        $this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                       <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>';

        $was_validated = "";
        $user = $this->users_model->get_user($this->user_id);
        $this->data["user"] = $user;

        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        $this->form_validation->set_rules('surname', $this->lang->line('create_user_validation_surname_label'), 'trim');
        $this->form_validation->set_rules('gender', $this->lang->line('create_user_validation_gender'), 'trim');
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('dob', $this->lang->line('create_user_validation_department_label'), 'trim');

        if ($this->form_validation->run() === TRUE)
        {
            $additional_data = [
                'first_name'    => $this->input->post('first_name'),
                'last_name'     => $this->input->post('last_name'),
                'surname'       => $this->input->post('surname'),
                'dob'           => (empty($this->input->post('dob')) ? NULL:date("Y-m-d", strtotime($this->input->post('dob')))),
                'gender'        => $this->input->post('gender'),
                'phone'         => (empty($this->input->post('phone')) ? NULL:$this->input->post('phone')),
            ];



            //bemorni yangilash Users table
            $this->ion_auth->update_user($this->user_id, $additional_data);

            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("doctor/user", 'refresh');

        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            if(!empty($this->data['message'])) {
                $was_validated = "was-validated";
            }
            $this->data["was_validated"] = $was_validated;
            $this->data['last_name'] = [
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name', $user["last_name"]),
                "class" => "form-control floating",
                "required" => ""
            ];
            $this->data['first_name'] = [
                'name'  => 'first_name',
                'id'    => 'first_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('first_name', $user["first_name"]),
                "class" => "form-control floating",
                "required" => ""
            ];
            $this->data['surname'] = [
                'name'  => 'surname',
                'id'    => 'surname',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('surname', $user["surname"]),
                "class" => "form-control floating",
            ];

            $this->data['dob'] = [
                'name' => 'dob',
                'id' => 'dob',
                'type' => 'text',
                'value' => $this->form_validation->set_value('dob', date("d.m.Y", strtotime($user["dob"]))),
                'class' => 'form-control floating datetimepicker',
            ];

            $this->data['phone'] = [
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone', $user["phone"]),
                'class' => 'form-control floating',
            ];

            $this->render('doctor/user/edit_view');
        }
    }

    public function change_password()
    {
        $this->data['title'] = 'Паролни ўзгартириш';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/functions.js?".time()).'"></script>
                                        ';

        $was_validated = "";
        $user_id = $this->session->userdata("user_id");

        $this->form_validation->set_rules('password', $this->lang->line('general_enter_new_password'), 'trim|required');
        $this->form_validation->set_rules('confirm_password', $this->lang->line('general_confirm_password'), 'trim|required|matches[password]');

        if ($this->form_validation->run() === TRUE)
        {
            $this->ion_auth->update_user($user_id, array('password' => $this->input->post("password")));
            redirect("doctor/user", 'refresh');
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            if(!empty($this->data['message']))
            {
                $was_validated = "was-validated";
            }
            $this->data["was_validated"] = $was_validated;

            $this->data['password'] = [
                'name'  => 'password',
                'id'    => 'password',
                'type'  => 'password',
                'value' => $this->form_validation->set_select('password'),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['confirm_password'] = [
                'name' => 'confirm_password',
                'id' => 'confirm_password',
                'type' => 'password',
                'value' => $this->form_validation->set_value('confirm_password'),
                "class" => "form-control",
                "required" => ""
            ];
        }

        $this->render('doctor/user/change_password_view');
    }

}