<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posprint extends Admin_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model(array("settings_model", "posprinters_model"));
    }

    public function index()
    {
        $this->data['title'] = 'POS Print';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = ' 
        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        ';
		$this->data["printers"] 		= $this->posprinters_model->get_printers();
		$this->data["printer_settings"] = $this->settings_model->get_group_settings("POS");



		$this->render('admin/settings/posprint_view');
    }

	public function ajax_update_setting_value() {
		if ($this->input->is_ajax_request()) {
			$status 			= $this->input->post("status");
			$settings_item_id 	= $this->input->post("settings_item_id");

			$result = $this->settings_model->update($settings_item_id, array("value"=>$status));
			echo json_encode($status);
		}
	}

    public function ajax_select_printer() {
        if ($this->input->is_ajax_request()) {
            $selected_pos_printer_id = $this->input->post("selected_pos_printer_id");
			//15 - pos_printer_id
            $result = $this->settings_model->update(15, array("value"=>$selected_pos_printer_id));

            echo json_encode($result);

        }
    }

}
