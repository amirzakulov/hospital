<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_types extends Admin_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model(
            array("payment_types_model"));
    }

	public function index()
	{
		$this->data["title"] = "Тўлов турлари";
		$this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

		$this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        ';

		$this->data["payment_types"] = $this->payment_types_model->get_payment_types();

		$this->render("admin/payment_types/index_view");
    }

	public function add() {

		$this->data["title"] = "Тўлов тури қўшиш";
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

		$this->form_validation->set_rules('name', "Тўлов тури", 'trim|required');
		if ($this->form_validation->run() === TRUE) {

			$this->payment_types_model->add($this->input->post());
			$this->session->set_flashdata('message', $this->ion_auth->messages());

			redirect("admin/payment_types", 'refresh');
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

			$this->render("admin/payment_types/add_view");
		}
	}

	public function edit($id) {

		$this->data["title"] = "Тўлов тури тахрирлаш";
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
		$payment_type = $this->payment_types_model->get_payment_type($id);

		$this->form_validation->set_rules('name', "Чиқим тури", 'trim|required');
		if ($this->form_validation->run() === TRUE) {

			$this->payment_types_model->update($id, $this->input->post());
			$this->session->set_flashdata('message', $this->ion_auth->messages());

			redirect("admin/payment_types", 'refresh');
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			if(!empty($this->data['message'])) { $was_validated = "was-validated";}
			$this->data["was_validated"] = $was_validated;

			$this->data['name'] = [
				'name' => 'name',
				'id' => 'name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('name', $payment_type["name"]),
				'class' => 'form-control',
			];

			$this->render("admin/payment_types/edit_view");
		}
	}

	public function delete()
	{
		$id = $this->input->post("id");
		if(!is_null($this->input->post("confirm")))
		{
			$result = $this->payment_types_model->delete($id);

			echo json_encode(array("deleted" => $result));
		} else {
			echo json_encode(false);
		}
	}

}
