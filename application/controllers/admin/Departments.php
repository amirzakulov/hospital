<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Departments extends Admin_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model(array("departments_model"));
        $this->load->language("departments");
    }

    public function index()
    {
        $this->data['title'] = 'Бўлимлар';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        ';


        $this->data["departments"] = $this->departments_model->get_departments();

        $this->render('admin/departments/index_view');
        //$this->render(NULL, 'json'); ....if we want to render a json string. Also, if a request is made using ajax, we can simply do $this->render()
    }


    public function add()
    {
        $this->data["title"] = "Бўлим қушиш";
        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">';

        $this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>';
        $was_validated = "";
        $departments = $this->departments_model->get_departments();
        $department_options = array(0 => "-- Танлаш --");
        foreach ($departments as $department) {
            $department_options[$department["id"]] = $department["name"];
        }
        $this->data["department_options"] = $department_options;
        // validate form input
        $this->form_validation->set_rules('name', $this->lang->line('departments_name'), 'trim|required');

        if ($this->form_validation->run() === TRUE)
        {
            $id = $this->departments_model->add($this->input->post());

            redirect("admin/departments", 'refresh');
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

            $this->data['parent_department'] = [
                'name'  => 'parent_id',
                'id'    => 'parent_id',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('parent_id'),
                "class" => "form-control",
            ];

            $this->data['name'] = [
                'name'  => 'name',
                'id'    => 'name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('name'),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['description'] = [
                'name' => 'description',
                'id' => 'description',
                'type' => 'text',
                'value' => $this->form_validation->set_value('description'),
                "class" => "form-control",
                "rows" => "5",
                "cols" => "30",
            ];
            $this->data['status'] = [
                'name'      => 'status',
//                'id'        => 'status',
                'value'     => $this->form_validation->set_value('status'),
                'class'     => 'form-check-input'
            ];

            $this->render("admin/departments/add_view");
        }
    }

    public function edit($id)
    {
        $this->data["title"] = "Бўлимни тахрирлаш";
        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">';

        $this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>';
        $was_validated = "";

        $department = $this->departments_model->get_department($id);
        $this->data["department"] = $department;

        $departments = $this->departments_model->get_departments();
        $department_options = array(0 => "-- Танлаш --");
        foreach ($departments as $dep) {
            $department_options[$dep["id"]] = $dep["name"];
        }
        $this->data["department_options"] = $department_options;
        // validate form input
        $this->form_validation->set_rules('name', $this->lang->line('departments_name'), 'trim|required');

        if ($this->form_validation->run() === TRUE)
        {
            $this->departments_model->update($id, $this->input->post());

            redirect("admin/departments", 'refresh');
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

            $this->data['parent_department'] = [
                'name'  => 'parent_id',
                'id'    => 'parent_id',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('parent_id'),
                "class" => "form-control",
            ];

            $this->data['name'] = [
                'name'  => 'name',
                'id'    => 'name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('name', $department["name"]),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['description'] = [
                'name' => 'description',
                'id' => 'description',
                'type' => 'text',
                'value' => $this->form_validation->set_value('description', $department["description"]),
                "class" => "form-control",
                "rows" => "5",
                "cols" => "30",
            ];
            $this->data['status'] = [
                'name'      => 'status',
                'id'        => 'status',
                'value'     => $this->form_validation->set_value('status', $department["status"]),
                'class'     => 'form-check-input'
            ];

            $this->render("admin/departments/edit_view");
        }

    }
}
