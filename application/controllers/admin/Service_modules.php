<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_modules extends Admin_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model(
			array(
				"service_modules_model",
			));

	}

	public function index() {

		$this->data['title'] = 'Хизматлар турлари';
		$this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">';

		$this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>';

		$service_modules = $this->service_modules_model->get_service_modules();

		$this->data["service_modules"] = $service_modules;



		$this->render('admin/service_modules/index_view');
	}

	public function add()
	{
		$this->data["title"] = lang("add");

		$was_validated = "";
		$this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required');

		if ($this->form_validation->run() === TRUE)
		{
			$this->service_modules_model->add($this->input->post());

			redirect("admin/service_modules", 'refresh');
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


			$this->render('admin/service_modules/add_view');
		}
	}

	public function edit($id)
	{
		$this->data["title"] = lang("edit");
		$sevice_modules = $this->service_modules_model->get_service_module($id);

		$was_validated = "";
		$this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required');

		if ($this->form_validation->run() === TRUE)
		{
			$this->service_modules_model->update($id, $this->input->post());

			redirect("admin/service_modules", 'refresh');
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
				'value' => $this->form_validation->set_value('name', $sevice_modules["name"]),
				"class" => "form-control",
				"required" => ""
			];

			$this->render('admin/service_modules/edit_view');
		}
	}

	public function delete() {
		$id = $this->input->post("id");
		if (!is_null($this->input->post("confirm"))) {
			$result = $this->service_modules_model->delete($id);

			echo json_encode(array("deleted" => $result));
		} else {
			echo json_encode(false);
		}
	}
}
