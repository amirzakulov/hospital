<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laboratory extends Doctor_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model(array("laboratory_model", "partners_model"));
        $this->load->language("laboratory");
    }

    /*************************************************************************************************
     *                                  Laboratoriya                                                   *
     **************************************************************************************************/

    public function index()
    {
        $this->data['title'] = lang("laboratory_title");
        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">';

        $this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>';

        $laboratories = $this->laboratory_model->get_categories(2, "sort", "ASC");

        $this->data["laboratories"] = $laboratories;
        $this->render('doctor/laboratory/index_view');
    }

    public function add($category_id)
    {
        $this->data["title"] = "Лаборатория қўшиш";

        $was_validated = "";

        $prefix = "lab";
        $max_id = $this->laboratory_model->get_max_id();
        $this->load->helper("mix");
        $code = uniqe_code_genetrator($prefix, $max_id, 4);

        $partners = $this->partners_model->get_partners_laboratory();
        $this->data["partners"] = $partners;

        $this->data["category"] = $this->laboratory_model->get_laboratory($category_id);
        $sub_categories = $this->laboratory_model->sub_categories($category_id);
        krsort($sub_categories);
        $this->data["sub_categories"] = $sub_categories;

        // validate form input
        $this->form_validation->set_rules('name', $this->lang->line('laboratory_name'), 'trim|required');
        $this->form_validation->set_rules('price', $this->lang->line('laboratory_price'), 'trim|required');
        $this->form_validation->set_rules('norma', $this->lang->line('laboratory_norma'), 'trim');
        $this->form_validation->set_rules('partner_id', $this->lang->line('laboratory_partner'), 'trim');
        if(isset($_POST["partner_id"]) && !empty($_POST["partner_id"])) {
        $this->form_validation->set_rules('price_partner', $this->lang->line('laboratory_price_partner'), 'trim|required');
        }

        if ($this->form_validation->run() === TRUE) {
            $renew = is_null($this->input->post("renew")) ? false:true;
            unset($_POST["renew"]);

            $_POST["code"] = $code;
            $_POST["parent_id"] = $category_id;
            $id = $this->laboratory_model->add($this->input->post());

            if($renew) {
                redirect("doctor/laboratory/add/".$category_id, 'refresh');
            } else {
                redirect("doctor/laboratory", 'refresh');
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
                "tabindex" => 2,
            ];

            $this->data['price'] = [
                'name'  => 'price',
                'id'    => 'price',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('price'),
                "class" => "form-control",
                "required" => "",
                "tabindex" => 3,
            ];
            $this->data['sort'] = [
                'name'  => 'sort',
                'id'    => 'sort',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('sort'),
                "class" => "form-control",
                "tabindex" => 4,
            ];

            $this->data['norma'] = [
                'name'  => 'norma',
                'id'    => 'norma',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('norma'),
                "class" => "form-control",
                "rows" => "5",
                "cols" => "30",
                "tabindex" => 5,
            ];

            $this->data['mesurment'] = [
                'name'  => 'mesurment',
                'id'    => 'mesurment',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('mesurment'),
                "class" => "form-control",
                "tabindex" => 6,
            ];

            $this->data['default_value'] = [
                'name'  => 'default_value',
                'id'    => 'default_value',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('default_value'),
                "class" => "form-control",
                "tabindex" => 7,
            ];

            $this->data['partner_id'] = [
                'name' => 'partner_id',
                'id' => 'partner_id',
                'type' => 'text',
                'value' => $this->form_validation->set_value('partner_id'),
                'class' => 'custom-select js_lab_partner_id',
                "tabindex" => 8,
            ];

            $this->data['price_partner'] = [
                'name'  => 'price_partner',
                'id'    => 'price_partner',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('price_partner'),
                "class" => "form-control js_lab_partner_price",
                "required" => "",
                "tabindex" => 9,
            ];

            $this->data['recommendation'] = [
                'name'  => 'recommendation',
                'id'    => 'recommendation',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('recommendation'),
                "class" => "form-control",
                "rows" => "5",
                "cols" => "30",
                "tabindex" => 10,
            ];


            $this->render("doctor/laboratory/add_view");
        }
    }

    public function edit($id)
    {
        $this->data["title"] = "Лаборатория тахрирлаш";
        $was_validated = "";

        $laboratory = $this->laboratory_model->get_laboratory($id);
        $this->data["laboratory"] = $laboratory;
        $this->data["category"] = $this->laboratory_model->get_laboratory($laboratory["parent_id"]);
        $level = 1;

        $partners = $this->partners_model->get_partners_laboratory();
        $this->data["partners"] = $partners;

        $categories = $this->laboratory_model->get_categories($level);
        foreach ($categories as $category) {
            $this->data["categories"][$category["id"]] = $category["name"];
        }

        // validate form input
        $this->form_validation->set_rules('parent_id', $this->lang->line('laboratory_category'), 'required');
        $this->form_validation->set_rules('name', $this->lang->line('laboratory_name'), 'trim|required');
        $this->form_validation->set_rules('price', $this->lang->line('laboratory_price'), 'required');
        $this->form_validation->set_rules('norma', $this->lang->line('laboratory_norma'), 'trim');
        $this->form_validation->set_rules('partner_id', $this->lang->line('laboratory_partner'), 'trim');
        if(isset($_POST["partner_id"]) && !empty($_POST["partner_id"])) {
            $this->form_validation->set_rules('price_partner', $this->lang->line('laboratory_price_partner'), 'trim|required');
        }

        if ($this->form_validation->run() === TRUE)
        {
            if(empty($this->input->post("partner_id"))) {
                $_POST["partner_id"] = null;
                $_POST["price_partner"] = null;
            }

            $id = $this->laboratory_model->update($id, $this->input->post());

            redirect("doctor/laboratory", 'refresh');
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

            $this->data['parent_id'] = [
                'name' => 'parent_id',
                'id' => 'parent_id',
                'type' => 'text',
                'value' => $this->form_validation->set_select('parent_id'),
                'class' => 'custom-select bg-light',
                "required" => "required",
                "tabindex" => 10,

            ];

            $this->data['name'] = [
                'name'  => 'name',
                'id'    => 'name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('name', $laboratory["name"]),
                "class" => "form-control",
                "required" => "",
                "tabindex" => 20,
            ];

            $this->data['price'] = [
                'name'  => 'price',
                'id'    => 'price',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('price', $laboratory["price"]),
                "class" => "form-control",
                "required" => "",
                "tabindex" => 30,
            ];

            $this->data['sort'] = [
                'name'  => 'sort',
                'id'    => 'sort',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('sort', $laboratory["sort"]),
                "class" => "form-control",
                "tabindex" => 40,
            ];

            $this->data['norma'] = [
                'name'  => 'norma',
                'id'    => 'norma',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('norma', $laboratory["norma"]),
                "class" => "form-control",
                "required" => "",
                "tabindex" => 50,
            ];

            $this->data['mesurment'] = [
                'name'  => 'mesurment',
                'id'    => 'mesurment',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('mesurment', $laboratory["mesurment"]),
                "class" => "form-control",
                "tabindex" => 60,
            ];

            $this->data['default_value'] = [
                'name'  => 'default_value',
                'id'    => 'default_value',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('default_value', $laboratory["default_value"]),
                "class" => "form-control",
                "tabindex" => 70,
            ];

            $this->data['partner_id'] = [
                'name' => 'partner_id',
                'id' => 'partner_id',
                'type' => 'text',
                'value' => $this->form_validation->set_value('partner_id', $laboratory["partner_id"]),
                'class' => 'custom-select js_lab_partner_id',
                "tabindex" => 80,
            ];

            $this->data['price_partner'] = [
                'name'  => 'price_partner',
                'id'    => 'price_partner',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('price_partner', $laboratory["price_partner"]),
                "class" => "form-control js_lab_partner_price",
                "required" => "",
                "tabindex" => 90,
            ];

            $this->data['recommendation'] = [
                'name'  => 'recommendation',
                'id'    => 'recommendation',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('recommendation', $laboratory["recommendation"]),
                "class" => "form-control",
                "rows" => "5",
                "cols" => "30",
                "tabindex" => 95,
            ];

            $this->render("doctor/laboratory/edit_view");
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

    public function sublabs($lab_id) {
        $this->data["title"] = "Суб-лаборатория";
        $laboratory = $this->laboratory_model->get_laboratory($lab_id);
        $this->data["laboratory"] = $laboratory;
        $this->data["category"] = $this->laboratory_model->get_laboratory($laboratory["parent_id"]);
        $this->data["sublabs"] = $this->laboratory_model->sub_categories($lab_id);

        $this->render("doctor/laboratory/sublabs_view");
    }

    public function sublabs_add($lab_id) {
        $this->data["title"] = "Суб-лаборатория қўшиш";

        $was_validated = "";

        $this->data["laboratory"] = $this->laboratory_model->get_laboratory($lab_id);
        $this->data["subcategories"] = $this->laboratory_model->sub_categories($lab_id);
        $prefix = "lab";
        $max_id = $this->laboratory_model->get_max_id();
        $this->load->helper("mix");
        $code = uniqe_code_genetrator($prefix, $max_id);
        $renew = false;

        // validate form input
        $this->form_validation->set_rules('name', $this->lang->line('laboratory_name'), 'trim|required');
        $this->form_validation->set_rules('norma', $this->lang->line('laboratory_norma'), 'required');
        $this->form_validation->set_rules('mesurment', $this->lang->line('laboratory_mesurment'), 'trim');

        if ($this->form_validation->run() === TRUE)
        {
            if($this->input->post("renew") != null) {
                $renew = true;
                unset($_POST["renew"]);
            }

            $_POST["parent_id"] = $lab_id;
            $_POST["code"] = $code;
            $this->laboratory_model->add($this->input->post());

            if(!$renew) {
                redirect("doctor/laboratory/sublabs/".$lab_id, 'refresh');
            } else {
                redirect("doctor/laboratory/sublabs_add/".$lab_id, 'refresh');
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
                "tabindex" => 10,
            ];

            $this->data['sort'] = [
                'name'  => 'sort',
                'id'    => 'sort',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('sort'),
                "class" => "form-control",
                "tabindex" => 20,
            ];


            $this->data['norma'] = [
                'name'  => 'norma',
                'id'    => 'norma',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('norma'),
                "class" => "form-control",
                "rows" => "5",
                "cols" => "30",
                "tabindex" => 30,
                "required" => "",
            ];

            $this->data['mesurment'] = [
                'name'  => 'mesurment',
                'id'    => 'mesurment',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('mesurment'),
                "class" => "form-control",
                "tabindex" => 40,
            ];

            $this->data['default_value'] = [
                'name'  => 'default_value',
                'id'    => 'default_value',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('default_value'),
                "class" => "form-control",
                "tabindex" => 50,
            ];

            $this->data['recommendation'] = [
                'name'  => 'recommendation',
                'id'    => 'recommendation',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('recommendation'),
                "class" => "form-control",
                "rows" => "5",
                "cols" => "30",
                "tabindex" => 60,
            ];

            $this->render("doctor/laboratory/sublabs_add_view");
        }
    }

    public function sublabs_edit($sublab_id) {
        $this->data["title"] = "Суб-лаборатория қўшиш";

        $was_validated = "";

        $sublaboratory = $this->laboratory_model->get_laboratory($sublab_id);
        $this->data["sublaboratory"] = $sublaboratory;
        $laboratory = $this->laboratory_model->get_laboratory($sublaboratory["parent_id"]);
        $this->data["laboratory"] = $laboratory;

        // validate form input
        $this->form_validation->set_rules('name', $this->lang->line('laboratory_name'), 'trim|required');
        $this->form_validation->set_rules('norma', $this->lang->line('laboratory_norma'), 'required');
        $this->form_validation->set_rules('mesurment', $this->lang->line('laboratory_mesurment'), 'trim');

        if ($this->form_validation->run() === TRUE) {
            $this->laboratory_model->update($sublab_id, $this->input->post());
            redirect("doctor/laboratory/sublabs/".$laboratory["id"], 'refresh');
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
                'value' => $this->form_validation->set_value('name', $sublaboratory["name"]),
                "class" => "form-control",
                "required" => "",
                "tabindex" => 10,
            ];

            $this->data['sort'] = [
                'name'  => 'sort',
                'id'    => 'sort',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('sort', $sublaboratory["sort"]),
                "class" => "form-control",
                "tabindex" => 20,
            ];

            $this->data['norma'] = [
                'name'  => 'norma',
                'id'    => 'norma',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('norma', $sublaboratory["norma"]),
                "class" => "form-control",
                "rows" => "3",
                "cols" => "30",
                "tabindex" => 30,
            ];

            $this->data['mesurment'] = [
                'name'  => 'mesurment',
                'id'    => 'mesurment',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('mesurment', $sublaboratory["mesurment"]),
                "class" => "form-control",
                "tabindex" => 40,
            ];

            $this->data['default_value'] = [
                'name'  => 'default_value',
                'id'    => 'default_value',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('default_value', $sublaboratory["default_value"]),
                "class" => "form-control",
                "tabindex" => 50,
            ];

            $this->data['recommendation'] = [
                'name'  => 'recommendation',
                'id'    => 'recommendation',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('recommendation', $sublaboratory["recommendation"]),
                "class" => "form-control",
                "rows" => "5",
                "cols" => "30",
                "tabindex" => 60,
            ];

            $this->render("doctor/laboratory/sublabs_edit_view");
        }
    }

}
