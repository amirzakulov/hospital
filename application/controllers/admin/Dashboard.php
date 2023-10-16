<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            "doctors_model",
            "patients_model",
        ));
        
    }

    public function index()
    {

		echo "<pre>";
		print_r(site_url("third_party/scpos_php/mike42/scpos-php/example/rawbt-receipt.php"));
		echo "</pre>";

        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/Chart.bundle.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/chart.js?".time()).'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        ';




        $this->data["doctors"] = $this->doctors_model->get_doctors_all();
        $this->data["patients"] = $this->patients_model->get_patients();

        $this->render('admin/dashboard_view');
    }
}
