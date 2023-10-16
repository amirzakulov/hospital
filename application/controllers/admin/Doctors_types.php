<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctors_types extends Admin_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model(array("doctors_types_model"));
        $this->load->language("doctors_types");
    }

    public function index()
    {
        $this->data['title'] = 'Шифокорлар Мутахасислиги';
        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">';

        $this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>';

        $doctors_types = $this->doctors_types_model->get_doctors_types();

        $this->data["doctors_types"] = $doctors_types;

        $this->render('admin/doctors_types/index_view');
    }

    public function add()
    {
        $this->data["title"] = lang("add");

        $was_validated = "";
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required');

        if ($this->form_validation->run() === TRUE)
        {
            $this->doctors_types_model->add($this->input->post());

            redirect("admin/doctors_types", 'refresh');
        }
        else
        {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

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
            $this->data['description'] = [
                'name' => 'description',
                'id' => 'description',
                'type' => 'text',
                'value' => $this->form_validation->set_value('description'),
                "class" => "form-control",
                "rows" => "5",
                "cols" => "30",
            ];

            $this->render('admin/doctors_types/add_view');
        }
    }

    public function edit($id)
    {
        $this->data["title"] = lang("edit");
        $doctors_types = $this->doctors_types_model->get_doctors_type($id);

        $was_validated = "";
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required');

        if ($this->form_validation->run() === TRUE)
        {
            $this->doctors_types_model->update($id, $this->input->post());

            redirect("admin/doctors_types", 'refresh');
        }
        else
        {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            if(!empty($this->data['message']))
            {
                $was_validated = "was-validated";
            }
            $this->data["was_validated"] = $was_validated;

            $this->data['name'] = [
                'name'  => 'name',
                'id'    => 'name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('name', $doctors_types["name"]),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['description'] = [
                'name' => 'description',
                'id' => 'description',
                'type' => 'text',
                'value' => $this->form_validation->set_value('description', $doctors_types["description"]),
                "class" => "form-control",
                "rows" => "5",
                "cols" => "30",
            ];

            $this->render('admin/doctors_types/edit_view');
        }
    }

    /**
    * @var boolean linked - usbu item boshqa table larga bog'langanmi yoqmiligini bildiradi (true - bog'langan, false - bog'lanmagan)
    **/
    public function delete()
    {
        $id = $this->input->post("id");
        if(!is_null($this->input->post("confirm")))
        {
            $deleted = $this->doctors_types_model->delete($id);
            echo json_encode(array("deleted" => $deleted));
        }
        else
        {
            $linked = $this->doctors_types_link_model->check_links($id);
            if($linked > 0) {
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }
        }
    }
}
