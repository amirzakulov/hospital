<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Services extends Admin_Controller
{
    function __construct() {
        parent::__construct();
        $this->load->model(array("services_model"));
        $this->load->language(array("services"));

    }

    public function index()
    {
        $this->data["title"] = 'Қўшимча хизматлар';

        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        ';

        $this->data["services"] = $this->services_model->get_services();
        $this->render('admin/services/index_view');
    }

    public function add() {

        $this->data["title"] = "Қўшимча хизмат қўшиш";
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
                                        ';

        $was_validated      = "";

        // validate form input
        $this->form_validation->set_rules('name', $this->lang->line('services_name'), 'trim|required');
        $this->form_validation->set_rules('price', $this->lang->line('services_price'), 'trim|required|integer');
        $this->form_validation->set_rules('sort', $this->lang->line('services_sort'), 'trim');
        $this->form_validation->set_rules('description', $this->lang->line('services_description'), 'trim');
        $this->form_validation->set_rules('active', $this->lang->line('services_active'), 'trim');

        if ($this->form_validation->run() === TRUE) {

            $this->services_model->add($this->input->post());

            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());

            redirect("admin/services", 'refresh');
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            if(!empty($this->data['message']))
            {
                $was_validated = "was-validated";
            }
            $this->data["was_validated"] = $was_validated;

            $this->data['name'] = [
                'name'  => 'name',
                'id'    => 'name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('name'),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['price'] = [
                'name' => 'price',
                'id' => 'price',
                'type' => 'text',
                'value' => $this->form_validation->set_value('price'),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['sort'] = [
                'name' => 'sort',
                'id' => 'sort',
                'type' => 'text',
                'value' => $this->form_validation->set_value('sort'),
                "class" => "form-control",
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


            $this->render("admin/services/add_view");
        }
    }

    public function edit($id) {

        $this->data["title"] = "Қўшимча хизмат қўшиш";
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
                                        ';

        $was_validated      = "";

        $service = $this->services_model->get_service($id);
        $this->data["service"] = $service;

        // validate form input
        $this->form_validation->set_rules('name', $this->lang->line('services_name'), 'trim|required');
        $this->form_validation->set_rules('price', $this->lang->line('services_price'), 'trim|required|integer');
        $this->form_validation->set_rules('sort', $this->lang->line('services_sort'), 'trim');
        $this->form_validation->set_rules('description', $this->lang->line('services_description'), 'trim');
        $this->form_validation->set_rules('active', $this->lang->line('services_active'), 'trim');

        if ($this->form_validation->run() === TRUE) {

            $this->services_model->update($id, $this->input->post());

            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());

            redirect("admin/services", 'refresh');
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            if(!empty($this->data['message']))
            {
                $was_validated = "was-validated";
            }
            $this->data["was_validated"] = $was_validated;

            $this->data['name'] = [
                'name'  => 'name',
                'id'    => 'name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('name', $service['name']),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['price'] = [
                'name' => 'price',
                'id' => 'price',
                'type' => 'text',
                'value' => $this->form_validation->set_value('price', $service['price']),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['sort'] = [
                'name' => 'sort',
                'id' => 'sort',
                'type' => 'text',
                'value' => $this->form_validation->set_value('sort', $service['sort']),
                "class" => "form-control",
            ];
            $this->data['description'] = [
                'name' => 'description',
                'id' => 'description',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('description', $service['description']),
                'rows' => 5,
                'cols' => 30,
                'class' => 'form-control'
            ];

            $this->render("admin/services/edit_view");
        }
    }

    public function delete()
    {
        $id = $this->input->post("id");
        if(!is_null($this->input->post("confirm")))
        {
            $result = $this->services_model->delete($id);

            echo json_encode(array("deleted" => $result));
        } else {
            echo json_encode(false);
        }
    }
}