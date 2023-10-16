<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lab_divisions extends Admin_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model(array("laboratory_divisions_model"));
        $this->load->language("laboratory");
    }

    public function index()
    {
        $this->data["title"] = "Бўлимлар";
        $this->data["lab_divisions"] = $this->laboratory_divisions_model->get_divisions();
        $this->render('admin/lab_divisions/index_view');
    }

    public function add()
    {
        $this->data["title"] = "Лаборатория бўлимини қўшиш";

        $was_validated = "";
        $this->data["active_options"] = array("Нофаол", "Фаол");

        // validate form input
        $this->form_validation->set_rules('name', $this->lang->line('lab_divisions_name'), 'trim|required');

        if ($this->form_validation->run() === TRUE)
        {
            $this->laboratory_divisions_model->add($this->input->post());

			redirect("admin/lab_divisions", 'refresh');
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

			$this->data['active'] = [
				'name' 	=> 'active',
				'id' 	=> 'active',
				'value' => $this->form_validation->set_select('active', 0),
				'class' => 'custom-select bg-light',
				"tabindex" => 3,
			];

			$this->render("admin/lab_divisions/add_view");
        }
    }

	public function edit($id)
	{
		$this->data["title"] = "Лаборатория бўлимини тахрирлаш";
		$lab_divisions = $this->laboratory_divisions_model->get_division($id);
		$this->data["lab_divisions"] = $lab_divisions;
		$was_validated = "";
		$this->data["active_options"] = array("Нофаол", "Фаол");

		// validate form input
		$this->form_validation->set_rules('name', $this->lang->line('lab_divisions_name'), 'trim|required');

		if ($this->form_validation->run() === TRUE)
		{
			$this->laboratory_divisions_model->update($id, $this->input->post());

			redirect("admin/lab_divisions", 'refresh');
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
				'value' => $this->form_validation->set_value('name', $lab_divisions["name"]),
				"class" => "form-control",
				"required" => "",
				"autofocus" => "",
				"tabindex" => 1
			];

			$this->data["sort"] = [
				'name'  => 'sort',
				'id'    => 'sort',
				'type'  => 'text',
				'value' => $this->form_validation->set_value("sort", $lab_divisions["sort"]),
				"class" => "form-control",
				"tabindex" => 2
			];

			$this->data['active'] = [
				'name' 	=> 'active',
				'id' 	=> 'active',
				'value' => $this->form_validation->set_select('active', $lab_divisions["active"]),
				'class' => 'custom-select bg-light',
				"tabindex" => 3,
			];

			$this->render("admin/lab_divisions/edit_view");
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
			$result = $this->laboratory_divisions_model->delete($id);

			echo json_encode(array("deleted" => $result));
		} else {
			echo json_encode(false);
		}
    }

}
