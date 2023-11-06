<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense_type extends Admin_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model(
            array("expense_type_model","expenses_model"));
    }

	public function index()
	{
		$this->data["title"] = "Чиқим турлари";
		$this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

		$this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        ';

		$this->data["expense_type"] = $this->expense_type_model->get_expense_types();

		$this->render("admin/expense_type/index_view");
    }

	public function add() {

		$this->data["title"] = "Чиқим тури қўшиш";
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

		$this->form_validation->set_rules('name', "Чиқим тури", 'trim|required');
		if ($this->form_validation->run() === TRUE) {

			$this->expense_type_model->add($this->input->post());
			$this->session->set_flashdata('message', $this->ion_auth->messages());

			redirect("admin/expense_type", 'refresh');
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			if(!empty($this->data['message'])) { $was_validated = "was-validated";}
			$this->data["was_validated"] = $was_validated;

			$this->data['name'] = [
				'name' => 'name',
				'id' => 'name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('name'),
				'class' => 'form-control',
			];

			$this->render("admin/expense_type/add_view");
		}
	}

	public function edit($id) {

		$this->data["title"] = "Чиқим тури тахрирлаш";
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

		$was_validated = "";
		$expense_type = $this->expense_type_model->get_expense_type($id);
		$this->data["expense_type"] = $expense_type;


		$this->form_validation->set_rules('name', "Чиқим тури", 'trim|required');
		if ($this->form_validation->run() === TRUE) {

			$this->expense_type_model->update($id, $this->input->post());
			$this->session->set_flashdata('message', $this->ion_auth->messages());

			redirect("admin/expense_type", 'refresh');
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			if(!empty($this->data['message'])) { $was_validated = "was-validated";}
			$this->data["was_validated"] = $was_validated;

			$this->data['name'] = [
				'name' => 'name',
				'id' => 'name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('name', $expense_type["name"]),
				'class' => 'form-control',
			];

			$this->render("admin/expense_type/edit_view");
		}
	}

	public function delete()
	{
		$id = $this->input->post("id");
		if(!is_null($this->input->post("confirm")))
		{
			$result = $this->expense_type_model->delete($id);

			echo json_encode(array("deleted" => $result));
		} else {
			echo json_encode(false);
		}
	}
}
