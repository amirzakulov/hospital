<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends Doctor_Controller {

    private $doctor_id;
    function __construct()
    {
        parent::__construct();

        $this->load->model(array("patients_payments_model", "doctors_model"));
        $this->load->language("patients");
        $this->doctor_id = $this->session->userdata("employee_id");
    }

    public function index() {
        $this->data['title'] = 'Беморлар';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
                                        ';

        $this->data["doctor"] = $this->doctors_model->get_doctor($this->doctor_id);

        $start_date = $end_date = date("Y-m-d");
        $this->form_validation->set_rules('start_date', 'start_date', 'trim');
        $this->form_validation->set_rules('end_date', 'end_date', 'trim');

        if ($this->form_validation->run() === TRUE) {

            $start_date = date("Y-m-d", strtotime($this->input->post("start_date")));
            $end_date = date("Y-m-d", strtotime($this->input->post("end_date")));
        }

        $this->data["earning"] = $this->doctors_model->doctor_earning($this->doctor_id, $start_date, $end_date);

        $this->data["sdate"]   = strtotime($start_date);
        $this->data["edate"]     = strtotime($end_date);

        $this->data["patients"] = $this->patients_payments_model->get_doctor_patients_by_date($this->doctor_id, $start_date, $end_date);

        $this->data['start_date'] = [
            'name'  => 'start_date',
            'id'    => 'start_date',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('start_date', date("d.m.Y", strtotime($start_date))),
            "class" => "form-control mb-2 mr-sm-2 datetimepicker-salary",
        ];
        $this->data['end_date'] = [
            'name' => 'end_date',
            'id' => 'end_date',
            'type' => 'text',
            'value' => $this->form_validation->set_value('end_date', date("d.m.Y", strtotime($end_date))),
            "class" => "form-control mb-2 mr-sm-2 datetimepicker-salary",
        ];

        $this->render('doctor/reports/index_view');
    }


}
