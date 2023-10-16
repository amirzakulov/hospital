<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_conditions extends Admin_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model(array("room_conditions_model"));
        $this->load->language("rooms");
    }

    public function index()
    {
        $this->data['title'] = 'Хона шароитлари';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                            ';


        $this->data["conditions"] = $this->room_conditions_model->get_room_conditions();

        $this->render('admin/room_conditions/index_view');
        //$this->render(NULL, 'json'); ....if we want to render a json string. Also, if a request is made using ajax, we can simply do $this->render()
    }

    public function add()
    {
        $this->data["title"] = "Шароит қўшиш";
        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '';
        $was_validated = "";

        // validate form input
        $this->form_validation->set_rules('title', $this->lang->line('room_condition_name'), 'trim|required');

        if ($this->form_validation->run() === TRUE)
        {
            $id = $this->room_conditions_model->add($this->input->post());

            redirect("admin/room_conditions", 'refresh');
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

            $this->data['name'] = [
                'name'  => 'title',
                'id'    => 'title',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('title'),
                "class" => "form-control",
                "tabindex" => 1,
                "required" => ""
            ];

            $this->render("admin/room_conditions/add_view");
        }
    }

    public function edit($id)
    {
        $this->data["title"] = "Шароитни тахрирлаш";
        $this->data['before_themeStyle'] = '';

        $this->data['before_appjs'] = '';
        $was_validated = "";

        $condition = $this->room_conditions_model->get_room_condition($id);
        $this->data["condition"] = $condition;

        // validate form input
        $this->form_validation->set_rules('title', $this->lang->line('room_condition_name'), 'trim|required');

        if ($this->form_validation->run() === TRUE)
        {
            $this->room_conditions_model->update($id, $this->input->post());

            redirect("admin/room_conditions", 'refresh');
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

            $this->data['name'] = [
                'name'  => 'title',
                'id'    => 'title',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('title', $condition["title"]),
                "class" => "form-control",
                "tabindex" => 1,
                "required" => ""
            ];

            $this->render("admin/room_conditions/edit_view");
        }
    }

    public function delete()
    {
        $id = $this->input->post("id");
        if(!is_null($this->input->post("confirm")))
        {
            $result = $this->room_conditions_model->delete($id);

            echo json_encode(array("deleted" => $result));
        } else {
            echo json_encode(false);
        }
    }
}
