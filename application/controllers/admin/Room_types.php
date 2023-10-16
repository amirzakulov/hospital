<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_types extends Admin_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model(array("room_types_model", "room_conditions_model"));
        $this->load->language("rooms");
    }

    public function index()
    {
        $this->data['title'] = 'Хона турлари';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                            ';

        $this->data["room_types"] = $this->room_types_model->get_room_types();

        $this->render('admin/room_types/index_view');
    }

    public function add() {
        $this->data["title"] = "Хона турини қўшиш";
        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/select2.min.js").'"></script>';
        $was_validated = "";

        $room_conditions = $this->room_conditions_model->get_room_conditions();
        $conditions_options = array();
        foreach ($room_conditions as $condition) {
            $conditions_options[$condition["title"]] = $condition["title"];
        }

        $this->data["conditions_options"] = $conditions_options;

        // validate form input
        $this->form_validation->set_rules('name', $this->lang->line('room_types_name'), 'trim|required');

        if ($this->form_validation->run() === TRUE)
        {
            if(!is_null($this->input->post("conditions"))) {
                $_POST["conditions"] = implode(", ",$this->input->post("conditions"));
            }

            $this->room_types_model->add($this->input->post());

            redirect("admin/room_types", 'refresh');
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
                'name'  => 'name',
                'id'    => 'name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('name'),
                "class" => "form-control",
                "required" => ""
            ];

            $this->data['price'] = [
                'name'  => 'price',
                'id'    => 'price',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('price'),
                "class" => "form-control",
            ];

            $this->data['conditions'] = [
                'name'  => 'conditions[]',
                'id'    => 'conditions',
                'value' => $this->form_validation->set_select('conditions'),
                'class' => 'custom-select select2',
                'size' => 8,
                "required" => ""
            ];

            $this->data['sort'] = [
                'name'  => 'sort',
                'id'    => 'sort',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('sort'),
                "class" => "form-control",
            ];

            $this->render("admin/room_types/add_view");
        }
    }

    public function edit($id) {
        $this->data["title"] = "Хона турини қўшиш";
        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/select2.min.js").'"></script>';
        $was_validated = "";

        $room_types  = $this->room_types_model->get_room_type($id);
        $this->data["selected_conditions"] = explode(", ", $room_types["conditions"]);

        $room_conditions = $this->room_conditions_model->get_room_conditions();
        $conditions_options = array();
        foreach ($room_conditions as $condition) {
            $conditions_options[$condition["title"]] = $condition["title"];
        }

        $this->data["conditions_options"] = $conditions_options;

        // validate form input
        $this->form_validation->set_rules('name', $this->lang->line('room_types_name'), 'trim|required');

        if ($this->form_validation->run() === TRUE)
        {
            if(!is_null($this->input->post("conditions"))) {
                $_POST["conditions"] = implode(", ",$this->input->post("conditions"));
            }
            $this->room_types_model->update($id, $this->input->post());

            redirect("admin/room_types", 'refresh');
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
                'name'  => 'name',
                'id'    => 'name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('name', $room_types["name"]),
                "class" => "form-control",
                "required" => ""
            ];

            $this->data['price'] = [
                'name'  => 'price',
                'id'    => 'price',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('price', $room_types["price"]),
                "class" => "form-control",
            ];

            $this->data['conditions'] = [
                'name'  => 'conditions[]',
                'id'    => 'conditions',
                'value' => $this->form_validation->set_select('conditions'),
                'class' => 'custom-select select2',
                'size' => 8,
                "required" => ""
            ];

            $this->data['sort'] = [
                'name'  => 'sort',
                'id'    => 'sort',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('sort', $room_types["sort"]),
                "class" => "form-control",
            ];

            $this->render("admin/room_types/edit_view");
        }
    }

    public function delete()
    {
        if ($this->input->is_ajax_request()) {
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
}
