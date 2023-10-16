<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lab_categories extends Doctor_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model(array("laboratory_model"));
        $this->load->language("lab_category");
    }

    /*************************************************************************************************
    *                                  Laboratoriya Kategoriyalari                                   *
    **************************************************************************************************/

    public function index()
    {
        $this->data["title"] = "Категория";
        $this->data["lab_categories"] = $this->laboratory_model->get_categories();
        $this->render('doctor/lab_categories/index_view');
    }

    public function add()
    {
        $this->data["title"] = "Лаборатория категориясини кушиш";

        $was_validated = "";
        $this->data["lab_categories"] = $this->laboratory_model->get_categories("id");
        $prefix = "cat";
        $max_id = $this->laboratory_model->get_max_id();
        $this->load->helper("mix");
        $code = uniqe_code_genetrator($prefix, $max_id, 4);

        // validate form input
        $this->form_validation->set_rules('name', $this->lang->line('lab_category_name'), 'trim|required');

        if ($this->form_validation->run() === TRUE)
        {
            $renew = is_null($this->input->post("renew")) ? false:true;
            unset($_POST["renew"]);
            $_POST["parent_id"] = 0;
            $_POST["code"] = $code;
            $_POST["sort"] = (empty($_POST["sort"]) ? 1:$this->input->post("sort"));
            $this->laboratory_model->add($this->input->post());

            if($renew) {
                redirect("doctor/lab_categories/add", 'refresh');
            } else {
                redirect("doctor/lab_categories", 'refresh');
            }
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
                "required" => "",
                "autofocus" => "",
                "tabindex" => 1
            ];

            $this->data["sort"] = [
                'name'  => 'sort',
                'id'    => 'sort',
                'type'  => 'text',
                'value' => $this->form_validation->set_value("sort"),
                "class" => "form-control",
                "tabindex" => 2
            ];

            $this->render("doctor/lab_categories/add_view");
        }
    }

    public function edit($category_id)
    {
        $this->data["title"] = "Лаборатория категориясини тахрирлаш";
        $category = $this->laboratory_model->get_laboratory($category_id);

        $was_validated = "";

        // validate form input
        $this->form_validation->set_rules('name', $this->lang->line('laboratory_name'), 'trim|required');

        if ($this->form_validation->run() === TRUE)
        {
            $id = $this->laboratory_model->update($category_id, $this->input->post());

            redirect("doctor/lab_categories", 'refresh');
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
                'value' => $this->form_validation->set_value('name', $category["name"]),
                "class" => "form-control",
                "autofocus" => "",
                "tabindex" => 1
            ];

            $this->data["sort"] = [
                'name'  => 'sort',
                'id'    => 'sort',
                'type'  => 'text',
                'value' => $this->form_validation->set_value("sort", $category["sort"]),
                "class" => "form-control",
                "tabindex" => 2
            ];

            $this->render("doctor/lab_categories/edit_view");
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
            $deleted = $this->laboratory_model->delete($id);
            echo json_encode(array("deleted" => $deleted));
        }
        else
        {
            $linked = $this->laboratory_model->check_links($id);
            if($linked > 0) {
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }
        }
    }

}
