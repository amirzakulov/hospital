<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Patients_lab extends Doctor_Controller {

    private $doctor_id;
    function __construct()
    {
        parent::__construct();

        if(!$this->ion_auth->in_group(7)) {
            show_404("", false);
            exit("sizning xuquqingiz yetarli emas!!!");
        }

        $this->load->model(
            array(
                "patients_model",
                "doctors_model",
                "patient_doctor_model",
                "laboratory_model",
                "patient_laboratories_model",
                "patient_uzi_model",
                "patients_payments_model",
				"regions_model",
				"cities_model",
				"laboratory_divisions_model",
            ));

        $this->load->language("patients");
        $this->doctor_id = $this->session->userdata("employee_id");
    }

    private function is_payment_exist($payment_id){
    	$payment = $this->patients_payments_model->get_patient_payment($payment_id);

    	if(!is_null($payment))
    		return true;
    	else
    		return false;
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
                                        ';

        $patients = $this->patients_payments_model->get_laboratory_patients();

        $this->data["incomplete_patients"] = $patients["incomplete"];
        $this->data["completed_patients"] = $patients["completed"];

        $this->render('doctor/patients_lab/index_view');
    }

    /**
     * doctorning barcha bemorlari ruyhati
     * **/
    public function all()
    {
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';
        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        ';

        $patients = $this->patient_laboratories_model->get_all_patients();

        $this->data["patients"] = $patients;

        $this->render('doctor/patients_lab/all_view');
    }

	public function ajax_load_all()
	{

		$draw 				= $this->input->post('draw');
		$row 				= $this->input->post('start');
		$rowperpage 		= $this->input->post('length'); // Rows display per page
		$order				= $this->input->post('order');
		$columnIndex 		= $order[0]['column']; // Column index
		$columns 			= $this->input->post('columns');
		$columnName 		= $columns[$columnIndex]['data']; // Column name
		$columnSortOrder 	= $order[0]['dir']; // asc or desc
		$search				= $this->input->post('search');
		$searchValue 		= $search['value']; // Search value

		## Total number of records without filtering
		$totalRecords = $this->patient_laboratories_model->get_all_patients_count();

		## Total number of record with filtering
		$totalRecordwithFilter = $this->patient_laboratories_model->get_searched_patients($searchValue, true, $columnName, $columnSortOrder, $row, $totalRecords);

		## Fetch records with filter
		$patients 				= $this->patient_laboratories_model->get_searched_patients($searchValue, false, $columnName, $columnSortOrder, $row, $rowperpage);

		$data = array();
		foreach ($patients as $key => $patient) {

			$data[$key]["DT_RowId"] = "expense_row_".$patient["patient_id"];
			$data[$key]["last_name"] = '<a href="'.site_url("doctor/patients_lab/patient_info_all/".$patient["patient_id"]).'">'.$patient["last_name"] ." ". $patient["first_name"].'</a>
								';
			$data[$key]["address"] = '<div class="doc-prof doc-prof--mb0">
										<i class="fa fa-map-marker text-danger"></i> '.$patient["address"].'
									</div>';
			$data[$key]["username"] = $patient['username'];
			$data[$key]["dob"] = is_null($patient["dob"]) ? "":date("Y", strtotime($patient['dob']));
			$data[$key]["phone"] = is_null($patient["phone"]) ? "":phone_number_format($patient["phone"]);
			$data[$key]["created_date"] = date("d M Y", strtotime($patient["created_date"]));

		}

		## Response
		$response = array(
			"draw" 				=> intval($draw),
			"recordsTotal" 		=> $totalRecords,
			"recordsFiltered" 	=> $totalRecordwithFilter,
			"data" 				=> $data
		);

		echo json_encode($response);

    }

    public function patient($payment_id = null, $refer = null) {

		if(!$this->is_payment_exist($payment_id)) show_404();

        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/doctor/js/print.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/doctor/js/print.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.sticky.js").'"></script>
                                        ';

        $this->data["print_preview_url"] = site_url("doctor/patients_lab/ajax_print_preview");

        //Bemor haqidagi ma'lumotlar
        $patient = $this->patient_laboratories_model->get_patient($payment_id);
        $this->data["patient"]      = $patient;

		$this->data["back_url"] = site_url("doctor/patients_lab");
		if($refer == "info_all") $this->data["back_url"] = site_url("doctor/patients_lab/patient_info_all/".$patient["patient_id"]);

        //Bemorning aktiv laboratoriyasi
        $lab_tree = $this->patient_laboratories_model->lab_tree($payment_id);
        $this->data["active_labs_tree"] = $lab_tree;

        //Bemorning level 1 laboratoriyalari
		$this->data["laboratories_level1"] = $this->patient_laboratories_model->get_patient_labs($payment_id, 1);

        //Bemorning laboratoriyalari
        $laboratory = array();
        $laboratories = $this->patient_laboratories_model->get_patient_laboratories($patient["patient_id"]);
        foreach ($laboratories as $lab) {
            $laboratory[$lab["payment_id"]]["date"]     = $lab["created_date"];
            $laboratory[$lab["payment_id"]]["data"][]   = $lab;
        }
        $this->data["laboratory"]   = $laboratory;

        //Bemorning tulovlari
        $payments = $this->patients_payments_model->get_patient_payment_by_patient($patient["patient_id"]);
        $this->data["payments"] = $payments;
        $was_validated = "";

        $this->form_validation->set_rules('patient_complaint', $this->lang->line("patients_patient_complaint"), 'required');
        $this->form_validation->set_rules('anamnesis_morbi', $this->lang->line("patients_anamnesis_morbi"), 'trim');
        $this->form_validation->set_rules('anamnesis_vitae', $this->lang->line("patients_anamnesis_vitae"), 'trim');
        $this->form_validation->set_rules('status_praesens', $this->lang->line("patients_status_praesens"), 'trim');

        if ($this->form_validation->run() === TRUE)
        {

            $this->session->set_flashdata('message', $this->ion_auth->messages());
        }
        else
        {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            if(!empty($this->data['message']))
            {
                $was_validated = "was-validated";
            }
            $this->data["was_validated"] = $was_validated;


            $this->data['description'] = [
                'name' => 'description',
                'id' => 'description',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('description'),
                'rows' => 3,
                'cols' => 5,
                'class' => 'form-control'
            ];
        }

        $this->render('doctor/patients_lab/patient_view');
    }

    public function patient_info($payment_id)
    {
        $this->data["refer"] = site_url("doctor/patients_lab");

        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/doctor/js/print.min.css").'">';
        $this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/doctor/js/print.min.js").'"></script>
                                        ';

        //Bemor haqidagi ma'lumotlar
        $patient = $this->patient_laboratories_model->get_patient($payment_id);
        $this->data["patient"]      = $patient;

        $payment = $this->patients_payments_model->get_patient_payment($payment_id);

        //Bemorning laboratoriyasi
        $this->load->helper("lab_form");
        $laboratory = build_laboratory_results_table($payment_id, $payment["created_date"], false);
        $this->data["laboratory"]   = $laboratory;


        $this->render('doctor/patients_lab/patient_info_view');
    }

    public function patient_info_all($patient_id)
    {
        $this->data["refer"] = site_url("doctor/patients_lab/all");

        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/doctor/js/print.min.css").'">';
        $this->data['before_appjs'] = '<script src="'.site_url("assets/doctor/js/print.min.js").'"></script>';

        //Bemor haqidagi ma'lumotlar
//        $patient = $this->patient_laboratories_model->get_patient($patient_id);
        $patient = $this->patients_model->get_patient($patient_id);
        $this->data["patient"]      = $patient;

        //Bemorning laboratoriyalari
        //var $type: null = barchasi, 1-doctor, 2-laboratory, 3-uzi
        $payments = $this->patients_payments_model->get_patient_payment_by_patient($patient_id, 2);
        $laboratories = array();
        $this->load->helper("lab_form");
        foreach ($payments as $payment) {
            $laboratories[$payment["id"]]["html"] = build_laboratory_results_table($payment["id"], $payment["created_date"], false);
        }

        $this->data["laboratories"]   = $laboratories;


        $this->render('doctor/patients_lab/patient_info_all_view');
    }


    public function ajax_patient_laboratory_status()
    {
        $payment_id = $this->input->post("payment_id");
        $laboratory_status = $this->input->post("laboratory_status");
        //order_status = 0-navbatda, 1-doctor qabulida, 2-laboratoriyada, 3-uzida, 4-finish
        //laboratory_status = 0-tulov qilinmagan, 1-tulov qilingan, 2-tamomlangan, 3-qabul kirdi, 4-qabulni tamomladi
        if($laboratory_status == "qabulda") {
            $this->patients_payments_model->update($payment_id, array("order_status" => 2, "laboratory_status" => 3));
        } elseif ($laboratory_status == "qabul_tamom") {
            $this->patients_payments_model->update($payment_id, array("order_status" => 0, "laboratory_status" => 4));
        } elseif ($laboratory_status == "natija_tayyor") {
            $natija = $this->finish_patient_laboratory($payment_id);
            $laboratory_status = !$natija ? false:$laboratory_status;
        }

        echo json_encode($laboratory_status);
    }

    /**
     * Bemor laboratoriyalarini tugatish
     **/
    private function finish_patient_laboratory($payment_id) {
        $res = false;
        if($this->patient_laboratories_model->check_results_complition($payment_id)) {//hamma laboratoriyalarning resultatlari tuldirilganligini tekshiramiz
            //agar barcha resultlar tuldirilgan bulsa "laboratory_status" 2 ga uzgartiramiz
            $this->patients_payments_model->update($payment_id, array("laboratory_status" => 2));
            $res = true;

            //tulov qilingan barcha itemlar bajarilgan bulsa payment statusni 1 ga uzgartiramiz
            $payment_status     = $this->patients_payments_model->check_payment_status($payment_id);
            if($payment_status == 'completed') {
                $this->patients_payments_model->update($payment_id, array("status" => 1, "order_status" => 4));
            }
        }

        return $res;
    }

    //laboratoriya natijalarini saqlash
//    public function ajax_lab_result_save2() {
//        $type = $this->input->post("type");
//        $results = $this->input->post("result");
//        $retion = $this->input->post("recommendation");
//        echo json_encode($retion);
//    }

    public function ajax_lab_result_save() {
    	$type = $this->input->post("type");
		$payment_id = $this->input->post("payment_id");

        if($type == "single")
        {
            $pl_id = $this->input->post("lp_id");
            $result = $this->input->post("result");

            $update     = false;
            $completed  = false;
            if(!empty($result)) {
                $update = $this->patient_laboratories_model->update($pl_id, array("result" => $result, "status" => 1));

                //hamma laboratoriyalarning resultatlari tuldirilganligini tekshiramiz
                $completed = $this->check_lab_completion($pl_id);
            }

            echo json_encode(array("res" => $update, "completed" => $completed));
        }
        else
        {
        	$incompled_labs = array();
            foreach ($this->input->post("result") as $patient_lab_id => $result) {
				$patient_lab_status = 1;

				$lab = $this->patient_laboratories_model->get_patient_laboratory($patient_lab_id);
				if($result !== '0' && empty($result)){
					if(!$lab["is_parent"]) {
						$incompled_labs[$lab["parent_id"]][] = $patient_lab_id;
					} else {
						$incompled_labs[$lab["lab_id"]][] = $patient_lab_id;
					}

					$patient_lab_status = 0;
				}

				$this->patient_laboratories_model->update($patient_lab_id, array("result" => trim($result), "status" => $patient_lab_status));

            }

			$is_parents = $this->input->post("is_parents");
			foreach ($is_parents as $lab_parent_id => $status) {
				$this->db->where(array("lab_id" => $lab_parent_id, "payment_id" => $payment_id, "is_parent" => 1));
				$this->db->update("patient_laboratories", array("status" => $status));
            }


            foreach ($this->input->post("recommendation") as $patient_lab_id => $r_val) {
                $this->patient_laboratories_model->update($patient_lab_id, array("recommendation" => $r_val));
            }

            $file_count = 0;
            if(isset($_FILES['lab_shots'])) {
                $file_count = count($_FILES['lab_shots']['name']);
            }

            $errors = array();
            if($file_count > 0) {
                $lab_shots = $_FILES['lab_shots']['name'];
                foreach ($lab_shots as $patient_lab_id => $files) {
                    $images_name = array();
                    for ($i = 0; $i <  count($files); $i++) {
                        $_FILES['userfile']['name']     = $_FILES['lab_shots']['name'][$patient_lab_id][$i];
                        $_FILES['userfile']['type']     = $_FILES['lab_shots']['type'][$patient_lab_id][$i];
                        $_FILES['userfile']['tmp_name'] = $_FILES['lab_shots']['tmp_name'][$patient_lab_id][$i];
                        $_FILES['userfile']['error']    = $_FILES['lab_shots']['error'][$patient_lab_id][$i];
                        $_FILES['userfile']['size']     = $_FILES['lab_shots']['size'][$patient_lab_id][$i];
                        //configuration for upload your images
                        $config = array(
                            'file_name'     => "lab_".time(),
                            'allowed_types' => 'jpg|jpeg|png|gif',
                            'overwrite'     => FALSE,
                            'upload_path'   => LAB_RESULT_IMAGES_PATH
                        );

                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('userfile')) {
                            $errors[] = array('error' => $this->upload->display_errors());
                        }
                        else
                        {
                            $filename = $this->upload->data();
                            $images_name[] = $filename["file_name"];
                        }
                    }

					$lab_images = implode(";",$images_name);
                    $this->patient_laboratories_model->update($patient_lab_id, array("images" => $lab_images));
                }

            }

            echo json_encode(array("success" => true, "errors"=>$errors, "incompled_labs" => $incompled_labs));
        }

    }


    //bitta laboratoryga tegishli bulgan hamma sub laboratoriyalar natijalarni kiritilganligini tekshiradi
    private function check_lab_completion($payment_id, $parent_id)
    {
        $this->db->where(array("parent_id" => $parent_id, "payment_id" => $payment_id, "status" => 0));
		$result = $this->db->get("patient_laboratories")->result_array();

		if(count($result) > 0) {
			return false;
		} else {
			return true;
		}
    }

    //preview button bosilganda payment id buyicha laboratoriyalar natijalarining tablitsasi
    public function ajax_print_preview() {
        $payment_id     = $this->input->post("payment_id");
        $this->load->helper("lab_form");
        $html = build_laboratory_results_table($payment_id, false, false);
        echo json_encode($html);
    }

    public function reports() {
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

        $start_date = $end_date = date("Y-m-d");
        $this->form_validation->set_rules('start_date', 'start_date', 'trim');
        $this->form_validation->set_rules('end_date', 'end_date', 'trim');

        if ($this->form_validation->run() === TRUE) {

            $start_date = date("Y-m-d", strtotime($this->input->post("start_date")));
            $end_date = date("Y-m-d", strtotime($this->input->post("end_date")));
        }

        $this->data["sdate"]   = strtotime($start_date);
        $this->data["edate"]     = strtotime($end_date);

        $this->data["patients"] = $this->patients_payments_model->get_laboratory_payments($start_date, $end_date);

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

        $this->render('doctor/patients_lab/reports_view');
    }

	public function patient_edit($patient_id)
	{
		$this->data["title"] = "Бемор тахрирлаш";
		$this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/js/lou_multi_select/multi-select.css").'">
        ';

		$this->data['before_appjs'] = '
        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/lou_multi_select/jquery.multi-select.js").'"></script>
                                        ';

		$patient    = $this->patients_model->get_patient($patient_id);
		$user_id    = $patient["user_id"];
		$this->data["patient"] = $patient;

		//Viloyatlar
		$this->data['regions'] = $this->regions_model->get_regions_array();

		//Shaxarlar
		$this->data['cities'] = $this->cities_model->get_cities_by_region_id($patient["region_id"]);

		$this->data["days"] 	= days_of_month();
		$this->data["months"] 	= months_of_year();
		$this->data["years"] 	= get_years();

		$was_validated = "";

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
		$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
		$this->form_validation->set_rules('dob', $this->lang->line('create_user_validation_department_label'), 'trim');

		if ($this->form_validation->run() === TRUE)
		{
			$additional_data = [
				'first_name'    => $this->input->post('first_name'),
				'last_name'     => $this->input->post('last_name'),
				'dob'           => $this->input->post('dob_year').'-'.$this->input->post('dob_month').'-'.$this->input->post('dob_day'),
			];

			//bemorni yangilash Users table
			$this->ion_auth->update($user_id, $additional_data);

			// check to see if we are creating the user
			// redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("doctor/patients_lab", 'refresh');

		}
		else
		{
			// display the create user form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			if(!empty($this->data['message'])) {
				$was_validated = "was-validated";
			}
			$this->data["was_validated"] = $was_validated;
			$this->data['first_name'] = [
				'name'  => 'first_name',
				'id'    => 'first_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('first_name', $patient["first_name"]),
				"class" => "form-control",
				"required" => ""
			];
			$this->data['last_name'] = [
				'name' => 'last_name',
				'id' => 'last_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('last_name', $patient["last_name"]),
				"class" => "form-control",
				"required" => ""
			];
			$this->data['dob_day'] = [
				'name' => 'dob_day',
				'id' => 'dob_day',
				'type' => 'text',
				'value' => $this->form_validation->set_value('dob_day', intval(date("d", strtotime($patient["dob"])))),
				'class' => 'form-control select select2_search',
			];
			$this->data['dob_month'] = [
				'name' => 'dob_month',
				'id' => 'dob_month',
				'type' => 'text',
				'value' => $this->form_validation->set_value('dob_month', date("m", strtotime($patient["dob"]))),
				'class' => 'form-control select select2_search',
			];
			$this->data['dob_year'] = [
				'name' => 'dob_year',
				'id' => 'dob_year',
				'type' => 'text',
				'value' => $this->form_validation->set_value('dob_year', date("Y", strtotime($patient["dob"]))),
				'class' => 'form-control select select2_search',
			];
			$this->data['region'] = [
				'name' => 'region_id',
				'id' => 'region_id',
				'type' => 'text',
				'value' => $this->form_validation->set_value('region_id', $patient["region_id"]),
				'class' => 'custom-select',
				"required" => "required",
				"data-url" => site_url("admin/doctors/ajax_get_cities")

			];
			$this->data['city'] = [
				'name'  => 'city_id',
				'id'    => 'city_id',
				'value' => $this->form_validation->set_value('city_id', $patient["city_id"]),
				'class' => 'custom-select',
				"required" => ""
			];
			$this->data['phone'] = [
				'name' => 'phone',
				'id' => 'phone',
				'type' => 'text',
				'value' => $this->form_validation->set_value('phone', $patient["phone"]),
				'class' => 'form-control',
			];
		}

		$this->render('doctor/patients_lab/patient_edit_view');
	}

	public function dashboard($division_id = null, $start_date = null, $end_date = null)
	{
		$this->data["title"] = "Лаборатория маълумотлари";
		$this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/fixedColumns.bootstrap4.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
        ';

		$this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.fixedColumns.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
                                            ';

		$this->data["lab_divisions"] 		= $this->laboratory_divisions_model->get_divisions();
		$patients_laboratories 				= $this->patient_laboratories_model->get_lab_result_report();
		ksort($patients_laboratories["patients"]);
		$this->data["patients"] 			= $patients_laboratories["patients"];
		$this->data["laboratories"] 		= $patients_laboratories["laboratories"];
		$this->data["patient_laboratories"] = $patients_laboratories["patient_laboratories"];

		$this->render('doctor/patients_lab/dashboard_view');
	}

	public function ajax_division_patient_laboratories()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");

		if(empty($start_date)) {
			$start_date = date("Y-m-d");
		} else {
			$start_date = date("Y-m-d", strtotime($start_date));
		}

		if(empty($end_date)) {
			$end_date = date("Y-m-d");
		} else {
			$end_date = date("Y-m-d", strtotime($end_date));
		}

		$division_id = $this->input->post("division_id") == 0 ? null : $this->input->post("division_id");

		$patients_laboratories 	= $this->patient_laboratories_model->get_lab_result_report($division_id, $start_date, $end_date);

		ksort($patients_laboratories["patients"]);
		$patients 				= $patients_laboratories["patients"];
		$laboratories 			= $patients_laboratories["laboratories"];
		$patient_laboratories 	= $patients_laboratories["patient_laboratories"];

		if(count($laboratories) == 0) {
			$html = "<div class='p-5 text-center'>Malumot yuq</div>";
		} else {
			$html = '
		<table class="table table-striped table-bordered dataTable lab_dashboard_dt" id="lab_dashboard_dt">
				<thead>
				<tr class="bg-dark text-white first_row">
					<th style="width: 200px;" class="fixed_col text-center">Беморлар</th>
					<th style="width: 80px;" class="fixed_col text-center">Чек</th>
					<th style="width: 100px;" class="fixed_col text-center">Сана</th>';
			foreach ($laboratories as $laboratory) {
				$html .= '<th width="100px">'.$laboratory["laboratory_name"].'</th>';
			}
			$html .= '</tr>
				</thead>
				<tbody>';
			foreach ($patients as $payment_id => $patient) {
				$html .= '<tr>
					<td style="width: 200px;" class="fixed_col text-left">
						<a class="text-white font-weight-bold" href="'.site_url("doctor/patients_lab/patient/".$payment_id).'">'.$patient["patient_name"].'</a><br>
					</td>
					<td style="width: 80px;" class="fixed_col text-center"><span class="text-warning">'.$payment_id.'</span></td>
					<td style="width: 100px;" class="fixed_col text-center">'.date("d.m.Y H:i", strtotime($patient["patient_date"])).'</td>					
					';
				foreach ($laboratories as $laboratory) {
					$class = $result = $status = "";
					if(isset($patient_laboratories[$patient["patient_id"]][$laboratory["lab_id"]])) {
						$status = $patient_laboratories[$patient["patient_id"]][$laboratory["lab_id"]]["status"];
						$result = $patient_laboratories[$patient["patient_id"]][$laboratory["lab_id"]]["result"];
						$class = !$status ? "bg-info":"bg-success";
					}
					$html .= '<td width="100px" class="'.$class.'">'.$result.'</td>';
				}
				$html .= '</tr>';
			}
			$html .= '</tbody>
			</table>
		';
		}

		echo json_encode($html);
	}


}
