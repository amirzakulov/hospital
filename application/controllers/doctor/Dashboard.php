<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Doctor_Controller
{
    private $doctor_id;
    function __construct()
    {
        parent::__construct();

        $this->load->model(array("patient_doctor_model", "patients_payments_model"));
        $this->load->language("patients");
        $this->doctor_id = $this->session->userdata("employee_id");
    }

    public function Index()
    {
        $this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/Chart.bundle.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/chart.js").'"></script>';

        $new_patients = array();
        if ($this->ion_auth->in_group(4)) {
            $patients = $this->patients_payments_model->get_doctor_patients($this->doctor_id);
            $new_patients = $patients["incomplete"];
            $title = "Шифокор қабули";
        } elseif ($this->ion_auth->in_group(7)) {
            $patients = $this->patients_payments_model->get_laboratory_patients();
            $new_patients = $patients["incomplete"];
            $title = "Лаборатория";
        } elseif ($this->ion_auth->in_group(9)) {
            $patients = $this->patients_payments_model->get_uzi_patients($this->doctor_id);
            $new_patients = $patients["incomplete"];
            $title = "УЗИ";
        }

        $this->data["patients"] = $new_patients;
        $this->data["title"] = $title;

		if($this->ion_auth->in_group(9)) {
			redirect("doctor/patients_uzi", 'refresh');
		}

		if($this->ion_auth->in_group(7)) {
			redirect("doctor/patients_lab/dashboard/", 'refresh');
		}

        $this->render('doctor/dashboard_view');
    }
}
/* End of file 'Verify' */
/* Location: ./application/controllers/Verify.php */
