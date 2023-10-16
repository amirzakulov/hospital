<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Change_password extends Admin_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model(array("users_model"));

    }

    public function index()
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
            redirect("admin/settings", 'refresh');
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

        $this->render('admin/settings/change_password_view');
    }

}
