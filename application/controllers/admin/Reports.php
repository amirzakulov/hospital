<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//use GeneralDailyReport;

class Reports extends Admin_Controller {
    private  $user_id;

    function __construct()
    {
        parent::__construct();

        $this->load->model(
            array(
                "reports_model",
                "patients_model",
//                "users_model",
                "doctors_model",
                "patient_doctor_model",
                "patient_laboratories_model",
                "patient_uzi_model",
                "patient_room_model",
                "patient_service_model",
                "patients_payments_model",
//                "regions_model",
//                "cities_model",
                "expenses_model",
				"payment_types_model",
				"payments_debt_discount_model",
				"partners_model",
				"service_modules_model",
            ));

        $this->load->language("patients");
        $this->user_id = $this->session->userdata("user_id");

    }

	public function index($start_date = null, $end_date = null)
	{
		$this->data["title"] = "Умумий хисобот";

        $start_date = is_null($start_date) ? date("Y-m-d") : date("Y-m-d", strtotime($start_date));
        $end_date   = is_null($end_date) ? date("Y-m-d") : date("Y-m-d", strtotime($end_date));

        $start_date_param = date("Ymd", strtotime($start_date));
        $end_date_param   = date("Ymd", strtotime($end_date));

		$this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
        ';

		$this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.sticky.js").'"></script>
                                        ';

		$this->form_validation->set_rules('start_date', 'start_date', 'trim');
		$this->form_validation->set_rules('end_date', 'end_date', 'trim');

		if ($this->form_validation->run() === TRUE) {
			$start_date = date_formating(strtotime($this->input->post("start_date")), "db");
			$end_date 	= date_formating(strtotime($this->input->post("end_date")), "db");

            $start_date_param = date("Ymd", strtotime($start_date));
            $end_date_param   = date("Ymd", strtotime($end_date));
		}

		$this->data["cash"] 		= $this->show_cash($start_date, $end_date);

		$this->data["income"] 		= $this->patients_payments_model->income_by_items($start_date, $end_date);
        $this->data["from_old_debts"] = $this->payments_debt_discount_model->from_old_debts($start_date, $end_date);
        $this->data["expenditure"] 	= $this->expenses_model->get_expenses_by_type($start_date, $end_date);
        $this->data["expenditure_payment_types"] = $this->expenses_model->get_expenses_payment_type($start_date, $end_date);
		$this->data["last_payments"]= $this->patients_payments_model->get_payments(date("Y-m-d"), null, 5);

		$this->load->library('reports/generalDailyReport', ["start_date"=>$start_date, "end_date"=>$end_date]);
		$generalDailyReport = $this->generaldailyreport->show();

		$this->data["laboratory_total"]	= $generalDailyReport["laboratory_total"];
		$this->data["uzi_total"]		= $generalDailyReport["uzi_total"];
		$this->data["services_total"]	= $generalDailyReport["services_total"];
		$this->data["rooms_total"]		= $generalDailyReport["rooms_total"];
		$this->data["doctors_total"]	= $generalDailyReport["doctors_total"];



		$service_modules = $this->service_modules_model->get_service_modules_array();
		$now = new DateTime("now");
		$m_start_date = $now->format("Y-m-1");
		$m_end_date = $now->format("Y-m-t");



		$partners_array= [];
		$partners = $this->patients_payments_model->get_partners();
		foreach ($partners as $partner) {
			$partners_array[$partner["id"]] = $partner["last_name"]." ".$partner["first_name"];
		}
		$this->data["partners_array"] = $partners_array;

		//Xamkorlar report
		$this->load->library('reports/PartnersDailyReport');
		$partnersDailyReport = new PartnersDailyReport();

		//Xamkorlar daily report
		$partnersDailyReportParams = [
			"start_date" 	  => $start_date,
			"end_date"   	  => $end_date,
			"service_modules" => $service_modules,
			"partners" 		  => $partners,
			];
		$partner_modules = array();
		if(count($partners)) {
			$partner_modules = $partnersDailyReport->show($partnersDailyReportParams);
		}
		$this->data["partners_report"] = $partner_modules;

		//Xamkorlar monthly report
		$partnersMonthlyReportParams = [
			"start_date" 	  => $m_start_date,
			"end_date"   	  => $m_end_date,
			"service_modules" => $service_modules,
			"partners" 		  => $partners,
		];
		$partners_monthly_modules = array();
		if(count($partners)) {
			$partners_monthly_modules = $partnersDailyReport->show($partnersMonthlyReportParams);
		}
		$this->data["partners_monthly_report"] = $partners_monthly_modules;


		//Doctors Report
		$doctors = $this->doctors_model->get_doctors_all();

		$doctors_array= [];
		foreach ($doctors as $doctor) {
			$doctors_array[$doctor["id"]] = $doctor["last_name"]." ".$doctor["first_name"];
		}
		$this->data["doctors_array"] = $doctors_array;

		//Doctors Daily Report
		$this->load->library('reports/DoctorsReport');
		$doctorsReportObj = new DoctorsReport();

		$doctorsDailyReportParams = [
			"start_date" 	  => $start_date,
			"end_date"   	  => $end_date,
			"service_modules" => $service_modules,
			"doctors" 		  => $doctors,
		];
		$this->data["sender_doctors_report"] = $doctorsReportObj->show($doctorsDailyReportParams);


		//Doctors Monthly Report
		$doctorsMonthlyReportParams = [
			"start_date" 	  => $m_start_date,
			"end_date"   	  => $m_end_date,
			"service_modules" => $service_modules,
			"doctors" 		  => $doctors,
		];
		$this->data["sender_doctors_monthly_report"] = $doctorsReportObj->show($doctorsMonthlyReportParams);


		$this->data["start_date_param"] = $start_date_param;
        $this->data["end_date_param"]   = $end_date_param;

		$this->data["sdate"]   			= strtotime($start_date);
		$this->data["edate"]     		= strtotime($end_date);

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

		$this->render("admin/reports/index_view");
	}
/**
	public function doctors($start_date = null, $end_date = null)
	{
		$this->data["title"] = "Шифокорлар";

        $start_date = is_null($start_date) ? date("Y-m-d") : date("Y-m-d", strtotime($start_date));
        $end_date   = is_null($end_date) ? date("Y-m-d") : date("Y-m-d", strtotime($end_date));

        $start_date_param = date("Ymd", strtotime($start_date));
        $end_date_param   = date("Ymd", strtotime($end_date));

		$this->data['before_themeStyle'] = '
            <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
            <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
            <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
        ';

		$this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
                                        ';

		$this->form_validation->set_rules('start_date', 'start_date', 'trim');
		$this->form_validation->set_rules('end_date', 'end_date', 'trim');

		if ($this->form_validation->run() === TRUE) {
			$start_date = date_formating(strtotime($this->input->post("start_date")), "db");
			$end_date 	= date_formating(strtotime($this->input->post("end_date")), "db");

            $start_date_param = date("Ymd", strtotime($start_date));
            $end_date_param   = date("Ymd", strtotime($end_date));
		}

		$this->data["cash"]     = $this->show_cash($start_date, $end_date);
		$this->data["doctors"]  = $this->patient_doctor_model->get_doctors_by_date($start_date, $end_date);

        $this->data["start_date_param"] = $start_date_param;
        $this->data["end_date_param"]   = $end_date_param;

		$this->data["sdate"]= strtotime($start_date);
		$this->data["edate"]= strtotime($end_date);

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

		$this->render("admin/reports/doctors_view");
	}

    public function doctors_patients($doctor_id = null, $start_date = null, $end_date = null)
    {
        if(is_null($doctor_id)) {
            redirect("admin/reports");
        }

        $this->data["title"] = "Беморлар";

        $start_date = is_null($start_date) ? date("Y-m-d") : date("Y-m-d", strtotime($start_date));
        $end_date   = is_null($end_date) ? date("Y-m-d") : date("Y-m-d", strtotime($end_date));

        $start_date_param = date("Ymd", strtotime($start_date));
        $end_date_param   = date("Ymd", strtotime($end_date));

        $this->data["patients"] = $this->patient_doctor_model->get_doctor_patients_by_date($doctor_id, $start_date, $end_date);

        $this->mybreadcrumb->add('Шифокорлар', site_url("admin/reports/doctors/".$start_date_param."/".$end_date_param));
        $this->mybreadcrumb->add('Беморлар', "admin/reports/doctors_patients/");
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->render("admin/reports/doctors_patients_view");

	}
**/

	public function ajax_cash() {

		$start_date = date_formating(strtotime($this->input->post("start_date")), "db");
		$end_date 	= date_formating(strtotime($this->input->post("end_date")), "db");

		$data["income"] 		= $this->patients_payments_model->income_by_items($start_date, $end_date);
		$data["from_old_debts"] = $this->payments_debt_discount_model->from_old_debts($start_date, $end_date);
		$data["expenditure"] 	= $this->expenses_model->get_expenses_by_type($start_date, $end_date);
		$data["expenditure_payment_types"] = $this->expenses_model->get_expenses_payment_type($start_date, $end_date);

		$data["start_date_param"] = date("Ymd", strtotime($start_date));
		$data["end_date_param"]   = date("Ymd", strtotime($end_date));

		$view = $this->load->view("admin/reports/main_cash_tab_view", $data, true);

		echo json_encode($view);
	}

	public function ajax_doctors()
	{
		$start_date = date_formating(strtotime($this->input->post("start_date")), "db");
		$end_date 	= date_formating(strtotime($this->input->post("end_date")), "db");

		$start_date_param = date("Ymd", strtotime($start_date));
		$end_date_param   = date("Ymd", strtotime($end_date));

		$doctors  = $this->patient_doctor_model->get_doctors_by_date($start_date, $end_date);

		$data = [
			"start_date_param" => $start_date_param,
			"end_date_param" => $end_date_param,
			"doctors" => $doctors
		];
		$view = $this->load->view("admin/reports/doctors_view", $data, true);

		echo json_encode($view);
	}

	public function ajax_doctors_patients(){
		$start_date = date_formating(strtotime($this->input->post("start_date")), "db");
		$end_date 	= date_formating(strtotime($this->input->post("end_date")), "db");
		$doctor_id 	= $this->input->post("doctor_id");

		$patients = $this->patient_doctor_model->get_doctor_patients_by_date($doctor_id, $start_date, $end_date);

		$view = $this->load->view("admin/reports/doctors_patients_view", ["patients" => $patients], true);

		echo json_encode($view);
	}

	public function ajax_laboratory() {

		$start_date = date_formating(strtotime($this->input->post("start_date")), "db");
		$end_date 	= date_formating(strtotime($this->input->post("end_date")), "db");

		$laboratories = $this->patient_laboratories_model->get_laboratories_by_date($start_date, $end_date);

		$view = $this->load->view("admin/reports/laboratory_view", ["laboratories" => $laboratories], true);

		echo json_encode($view);
	}

	public function ajax_uzi() {

		$start_date = date_formating(strtotime($this->input->post("start_date")), "db");
		$end_date 	= date_formating(strtotime($this->input->post("end_date")), "db");

		$uzis = $this->patient_uzi_model->get_uzis_by_date($start_date, $end_date);

		$view = $this->load->view("admin/reports/uzi_view", ["uzis" => $uzis], true);

		echo json_encode($view);
	}

	public function ajax_services() {
		$start_date = date_formating(strtotime($this->input->post("start_date")), "db");
		$end_date 	= date_formating(strtotime($this->input->post("end_date")), "db");

		$services = $this->patient_service_model->get_service_by_date($start_date, $end_date);

		$view = $this->load->view("admin/reports/services_view", ["services" => $services], true);

		echo json_encode($view);

	}

	public function ajax_room() {
		$start_date = date_formating(strtotime($this->input->post("start_date")), "db");
		$end_date 	= date_formating(strtotime($this->input->post("end_date")), "db");

		$rooms = $this->patient_room_model->get_room_by_date($start_date, $end_date);

		$view = $this->load->view("admin/reports/room_view", ["rooms" => $rooms], true);

		echo json_encode($view);
	}

	public function ajax_expenses() {
		$start_date = date_formating(strtotime($this->input->post("start_date")), "db");
		$end_date 	= date_formating(strtotime($this->input->post("end_date")), "db");

		$expenditure  = $this->expenses_model->get_expenses($start_date, $end_date, true);

		$view = $this->load->view("admin/reports/expenses_view", ["expenditure" => $expenditure], true);

		echo json_encode($view);
	}

/**
	public function ajax_doctors_bill($start_date = null,  $end_date = null) {
		$start_date = date_formating(strtotime($this->input->post("start_date")), "db");
		$end_date 	= date_formating(strtotime($this->input->post("end_date")), "db");

		$doctors_bill  = $this->doctors_bill_model->get_doctors_bill($start_date, $end_date, true);

		$view = $this->load->view("admin/reports/doctors_bill_view", ["doctors_bill" => $doctors_bill], true);

		echo json_encode($view);
	}
**/

	public function ajax_from_old_debts(){
		$start_date = date_formating(strtotime($this->input->post("start_date")), "db");
		$end_date 	= date_formating(strtotime($this->input->post("end_date")), "db");

		$debitors = $this->patients_payments_model->get_debt_off_by_date($start_date, $end_date);

		$patients = array();
		foreach ($debitors as $debitor) {
			$patients[$debitor["payment_id"]]["payment_id"] 								= $debitor["payment_id"];
			$patients[$debitor["payment_id"]]["patient_name"] 								= $debitor["last_name"] . " " . $debitor["first_name"];
			$patients[$debitor["payment_id"]]["debt_off_date"] 								= $debitor["created_date"];
			$patients[$debitor["payment_id"]]["service_types"][$debitor["service_type"]] 	= $debitor["service_name"];
			$patients[$debitor["payment_id"]]["amount"][$debitor["service_type"]] 			= (-1) * $debitor["amount"];
		}

		$view = $this->load->view("admin/reports/from_old_debts_view", ["patients" => $patients], true);

		echo json_encode($view);
	}

	public function ajax_debts() {
		$start_date = date_formating(strtotime($this->input->post("start_date")), "db");
		$end_date 	= date_formating(strtotime($this->input->post("end_date")), "db");

		$patients  = $this->patients_model->get_debitor_patients_by_date($start_date, $end_date);

		$view = $this->load->view("admin/reports/debts_view", ["patients" => $patients], true);

		echo json_encode($view);
	}

    public function patients() {
        $start_date = date("Y-m-d");
        $end_date   = date("Y-m-d", strtotime($start_date." +1 days"));

        $this->data['title'] = 'Беморлар';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
                                        ';


        $this->form_validation->set_rules('start_date', 'start_date', 'trim');
        $this->form_validation->set_rules('end_date', 'end_date', 'trim');

        if ($this->form_validation->run() === TRUE) {

            $start_date = date("Y-m-d", strtotime($this->input->post("start_date")));
            $end_date = date("Y-m-d", strtotime($this->input->post("end_date")));
        }


        $this->data["reports"] = $this->reports_model->patients_by_departments($start_date, $end_date);

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

        $this->data["patients"] = array();

        $this->render('admin/reports/patients_view');
    }

    public function general2()
    {
        $start_date = date("Y-m-d");
        $end_date   = date("Y-m-d", strtotime($start_date." +1 days"));

        $this->data["title"] = "Касса";
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
                                        ';


        $this->form_validation->set_rules('start_date', 'oaudoafsd', 'trim');
        $this->form_validation->set_rules('end_date', 'end_date', 'trim');

        if ($this->form_validation->run() === TRUE) {
            $start_date = date("Y-m-d", strtotime($this->input->post("start_date")));
            $end_date = date("Y-m-d", strtotime($this->input->post("end_date")));
        }


        $this->data["payment_types"]    = array(1 => "Нақд", 2 => "Пластик");
        $this->data["total_income"]     = $this->reports_model->get_total_income_by_date($start_date, $end_date);
        $this->data["doctors_income"]   = $this->reports_model->doctors_income_total($start_date, $end_date);
        $this->data["expenses"]         = $this->reports_model->other_expenses_total($start_date, $end_date);

        $this->data["sdate"]   = strtotime($start_date);
        $this->data["edate"]   = strtotime($end_date);

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


        $this->render('admin/reports/general_view');
    }

    public function payments()
    {
        $start_date = date("Y-m-d");
        $end_date   = date("Y-m-d", strtotime($start_date." +1 days"));

        $this->data["title"] = "Касса";
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
                                        ';


        $this->form_validation->set_rules('start_date', 'oaudoafsd', 'trim');
        $this->form_validation->set_rules('end_date', 'end_date', 'trim');

        if ($this->form_validation->run() === TRUE) {
            $start_date = date("Y-m-d", strtotime($this->input->post("start_date")));
            $end_date = date("Y-m-d", strtotime($this->input->post("end_date")));
        }


        $this->data["payment_types"] = array(1 => "Нақд", 2 => "Пластик");
        $this->data["payments"] = $this->patients_payments_model->get_total_income_by_date($start_date, $end_date, true);


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


        $this->render('admin/reports/payments_view');

    }

	private function show_cash($start_date, $end_date) {
		$this->load->model(array("expenses_model", "doctors_bill_model"));
//		$income		  = $this->patients_payments_model->show_cash($start_date, $end_date);
        $total_payment = $this->patients_payments_model->total_payment($start_date, $end_date);
        $real_payment = $this->patients_payments_model->real_payment($start_date, $end_date);
        $debt         = $this->patients_payments_model->debt($start_date, $end_date);
		$expenditure  = $this->expenses_model->get_expenses($start_date, $end_date);
		$expenditure  = !empty($expenditure) ? $expenditure:0;
		$doctors_bill = $this->doctors_bill_model->get_doctors_bill($start_date, $end_date);
		$doctors_bill = !empty($doctors_bill) ? $doctors_bill:0;

		return array("total_payment" => $total_payment, "real_payment" => $real_payment, "debt" => $debt, "expenditure" => $expenditure, "doctors_bill" => $doctors_bill);
	}

	public function cash()
	{
		$start_date = date("Y-m-d");
		$end_date   = date("Y-m-d", strtotime($start_date." +1 days"));

		$this->data["title"] = "Касса";
		$this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
        ';

		$this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
                                        ';


		$this->form_validation->set_rules('start_date', 'oaudoafsd', 'trim');
		$this->form_validation->set_rules('end_date', 'end_date', 'trim');

		if ($this->form_validation->run() === TRUE) {
			$start_date = date("Y-m-d", strtotime($this->input->post("start_date")));
			$end_date = date("Y-m-d", strtotime($this->input->post("end_date")));
		}


		$this->data["payment_types"] = array(1 => "Нақд", 2 => "Пластик");
		$this->data["reports"] = $this->reports_model->show_cash($start_date, $end_date);

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


		$this->render('admin/reports/cash_view');

	}



	public function uzi2($start_date = null, $end_date = null)
	{
		$this->data["title"] = "УЗИ";

		$start_date = is_null($start_date) ? date("Y-m-d") : date("Y-m-d", strtotime($start_date));
		$end_date   = is_null($end_date) ? date("Y-m-d") : date("Y-m-d", strtotime($end_date));

		$start_date_param = date("Ymd", strtotime($start_date));
		$end_date_param   = date("Ymd", strtotime($end_date));

		$this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
        ';

		$this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
                                        ';

		$this->form_validation->set_rules('start_date', 'start_date', 'trim');
		$this->form_validation->set_rules('end_date', 'end_date', 'trim');

		if ($this->form_validation->run() === TRUE) {
			$start_date = date_formating(strtotime($this->input->post("start_date")), "db");
			$end_date 	= date_formating(strtotime($this->input->post("end_date")), "db");

			$start_date_param = date("Ymd", strtotime($start_date));
			$end_date_param   = date("Ymd", strtotime($end_date));
		}

		$this->data["uzis"] = $this->patient_uzi_model->get_uzis_by_date($start_date, $end_date);
		$this->data["start_date_param"] = $start_date_param;
		$this->data["end_date_param"]   = $end_date_param;

		$this->data["sdate"]   = strtotime($start_date);
		$this->data["edate"]   = strtotime($end_date);

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

		$this->render("admin/reports/uzi2_view");
	}



}
