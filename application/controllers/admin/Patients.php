<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patients extends Admin_Controller {
    private  $user_id;


    function __construct()
    {
        parent::__construct();

        $this->load->model(
            array(
                "patients_model",
                "users_model",
                "regions_model",
                "cities_model",
                "patients_payments_model",
            ));

        $this->load->language("patients");
        $this->user_id = $this->session->userdata("user_id");

    }

    public function index() {
        $this->data['title'] = 'Беморлар';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.hideseek.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/functions_patients.js?".time()).'"></script>
                                        ';

        $patients = $this->patients_model->get_patients_archive();
        $this->data["patients"] = $patients;

        $this->render('admin/patients/index_view');
    }

    public function profile($id) {
        $backUrl = site_url("admin/patients");
        $this->mybreadcrumb->add('Беморлар', $backUrl);
        $this->mybreadcrumb->add('Бемор', "admin/patients/profile");
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->data["title"] = "Бемор";
		$this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

		$this->data['before_appjs'] = ' 
        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/divjs/divjs.js").'"></script>
        <script src="'.site_url("assets/admin/js/functions_patients.js?".time()).'"></script>
                                        ';

        $result = $this->patients_model->get_patient($id);
        $this->data["patient"] = $result;

        $gender = array(1=>"Эркак", 2 => "Аёл");
        $this->data["gender"] = $gender;

        $payments = $this->patients_payments_model->get_patient_payment_by_patient($id);
        $history = array();
        foreach ($payments as $payment) {
            $payment_date = date("Y-m-d", strtotime($payment["created_date"]));
            $history[$payment_date][] = $payment;
        }

        $this->data["history"] = $history;

        $this->render("admin/patients/profile_view");
    }

}
