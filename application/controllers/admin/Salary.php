<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salary extends Admin_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model(
            array(
                "patients_payments_model",
                "doctors_model",
                "doctors_bill_model",
            ));

        $this->load->language("salary");
    }

    public function index() {

        $start_date = date("Y-m-01");
        $end_date   = date("Y-m-d", strtotime($start_date." +1 months"));

        $this->data['title'] = 'Шифокорларнинг хақлари';
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

        $this->form_validation->set_rules('start_date', 'start_date', 'trim');
        $this->form_validation->set_rules('end_date', 'end_date', 'trim');

        if ($this->form_validation->run() === TRUE) {

            $start_date = date("Y-m-d", strtotime($this->input->post("start_date")));
            $end_date = date("Y-m-d", strtotime($this->input->post("end_date")));
        }

        $this->data["sdate"]   = strtotime($start_date);
        $this->data["edate"]     = strtotime($end_date);

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

        $doctors = $this->doctors_model->doctors_shares($start_date, $end_date);
        $this->data["doctors"] = $doctors;

        $this->render('admin/salary/index_view');
    }

    public function do_doctor_payment() {
        if ($this->input->is_ajax_request()) {
            $doctor_id = $this->input->post("doctor_id");
            $amount = $this->input->post("amount");

            $arr = array("doctor_id" => $doctor_id, "amount" => $amount, "payment_type" => 1);
            $id = $this->doctors_bill_model->add($arr);
            $res = !$id ? false:true;

            echo json_encode($res);

        }
    }

    public function doctor_cash($doctor_id, $start_date, $end_date) {

        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        ';

        $doctor = $this->doctors_model->get_doctor($doctor_id);
        $sdate = date("Y-m-d", $start_date);
        $edate = date("Y-m-d", $end_date);
        $doctor_cash = $this->doctors_model->doctor_cash($doctor_id, $sdate, $edate);

        $this->data["doctor"] = $doctor;
        $this->data["doctor_cash"] = $doctor_cash;
        $this->data["start_date"] = $sdate;
        $this->data["end_date"] = $edate;

        $this->render('admin/salary/doctor_cash_view');
    }
}
