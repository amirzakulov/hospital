<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lab_print extends Admin_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model("settings_model");
    }

    public function index()
    {
        $this->data['title'] = 'Чоп этиш созламалари';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = ' 
        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        ';
        $this->data["print_details"] = $this->settings_model->get_group_settings("LBP");

        $this->load->model("doctors_model");
        $this->data["laboratorists"] = $this->doctors_model->get_doctors_by_type(7);

        $this->render('admin/settings/printer/printer_view');
    }

    public function ajax_save_data() {
        if ($this->input->is_ajax_request()) {
            $post_data = $this->input->post("data");
            $result = $this->settings_model->update_lab_print_details($post_data);

            echo json_encode($result);
        }
    }

}
