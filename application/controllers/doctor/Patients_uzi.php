<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Patients_uzi extends Doctor_Controller {

    private $doctor_id;
    function __construct()
    {
        parent::__construct();

        if(!$this->ion_auth->in_group(9)) {
            show_404("", false);
            exit("sizning xuquqingiz yetarli emas!!!");
        }

        $this->load->model(
            array(
                "patients_model",
                "doctors_model",
                "patient_doctor_model",
                "uzi_model",
                "patient_uzi_model",
                "partners_model",
                "patients_payments_model",
                "templates_uzi_model",
                "files_model",
                "regions_model",
                "cities_model",
            ));

        $this->load->language("doctor_patients_uzi");
        $this->doctor_id = $this->session->userdata("employee_id");

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
                                        ';

        $patients = $this->patients_payments_model->get_uzi_patients();

        $this->data["incomplete_patients"] = $patients["incomplete"];
        $this->data["completed_patients"] = $patients["completed"];

        $this->render('doctor/patients_uzi/index_view');
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
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        ';

        $patients = $this->patient_uzi_model->get_all_patients();

        $this->data["patients"] = $patients;

        $this->render('doctor/patients_uzi/all_view');
    }

	public function patient($payment_id) {

		$this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/doctor/js/print.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/doctor/js/richtexteditor/richtext.min.css").'">
        ';

		$this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/doctor/js/print.min.js").'"></script>
                                        <script src="'.site_url("assets/doctor/js/richtexteditor/jquery.richtext.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.sticky.js").'"></script>
                                        ';

		$this->data["print_preview_url"] = site_url("doctor/patients_uzi/ajax_print_preview");

		//Bemor haqidagi ma'lumotlar
		$patient = $this->patient_uzi_model->get_patient($payment_id);
		$this->data["patient"]      = $patient;

		//Bemorning aktiv uzilari
		$patient_active_uzis = $this->patient_uzi_model->get_patient_uzi($payment_id);
		$this->data["patient_active_uzis"] = $patient_active_uzis;

		$payments = $this->patients_payments_model->get_patient_payment_by_patient($patient["patient_id"]);
		$this->data["payments"] = $payments;
		$was_validated = "";

		$this->form_validation->set_rules('userfile', $this->lang->line('doctor_patients_uzi_file'), 'callback_file_selected');

		if ($this->form_validation->run() === TRUE) {


			//upload qilingan fileni qayta ishlaymiz
			$file_name = 'uzi_'.date("d_m_Y_H_i");
			$config['upload_path']      = FCPATH."uploads/services";
			$config['allowed_types']    = 'docx';
			$config['max_size']         = '0';
			$config['file_name']        = $file_name;

			$this->load->library('upload',$config);

			if ( ! $this->upload->do_upload('userfile')) {
				$this->form_validation->set_message('file_selected', "Файл тури нотўғри");
				$this->data['message'] = "Файл тури нотўғри";

				if (!empty($this->data['message'])) {
					$was_validated = "was-validated";
				}
				$this->data["was_validated"] = $was_validated;
			} else {

				$file = $this->upload->data();

				//agar file upload qilingan bulsa, filedan malumotlarini olamiz
				if(!empty($file)) {
					$this->load->library("phpword");
					$template = $this->phpword->readWordDocx($file["full_path"]);
				}

				$file_array = array(
					"filename" => $file["file_name"],
					"service_type"=>3,
					"payment_id"=> $payment_id,
					"employee_id" => $this->doctor_id,
					"result" => $template
				);

				$this->files_model->add($file_array);
				$uzi_data = array("uzi_status" => 2, "order_status" => 0);
				$this->patients_payments_model->update($payment_id, $uzi_data);

				//barcha servicelar berkitilganligini tekshirib keyin status ni update qilamiz
				$status   = $this->patients_payments_model->check_payment_status($payment_id) == 'completed' ? 1:0;
				$this->patients_payments_model->update($payment_id, array("status" => $status));

				$this->session->set_flashdata('message', $this->ion_auth->messages());

				redirect("doctor/patients_uzi", 'refresh');
			}

		} else {
			// display the create user form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			if (!empty($this->data['message'])) {
				$was_validated = "was-validated";
			}
			$this->data["was_validated"] = $was_validated;

		}

		$this->render('doctor/patients_uzi/patient_view');
	}

	public function patient2($payment_id) {
		$this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/doctor/js/print.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/doctor/js/summernote-0.8.18/summernote-lite.min.css").'">
        ';

		$this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/doctor/js/print.min.js").'"></script>
                                        <script src="'.site_url("assets/doctor/js/summernote-0.8.18/summernote-lite.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.sticky.js").'"></script>
                                        ';


		$this->data["print_preview_url"] = site_url("doctor/patients_uzi/ajax_print_preview");

		//Bemor haqidagi ma'lumotlar
		$patient 				= $this->patient_uzi_model->get_patient($payment_id);
		$this->data["patient"]  = $patient;
		$this->data["lang"]		= 1;

		//Bemorning aktiv uzilari
		$patient_active_uzis = $this->patient_uzi_model->get_patient_uzi($payment_id);
		$this->data["patient_active_uzis"] = $patient_active_uzis;
		$this->data["patient_active_uzi_conclusion"] = $this->patient_uzi_model->get_patient_uzi_conclusion($payment_id);

		$payments = $this->patients_payments_model->get_patient_payment_by_patient($patient["patient_id"]);
		$this->data["payments"] = $payments;
		$was_validated = "";

		$this->form_validation->set_rules('result', "Natija", 'natija');

		if ($this->form_validation->run() === TRUE) {

			$uzi_data = array("uzi_status" => 2, "order_status" => 0);
			$this->patients_payments_model->update($payment_id, $uzi_data);

			//barcha servicelar berkitilganligini tekshirib keyin status ni update qilamiz
			$status   = $this->patients_payments_model->check_payment_status($payment_id) == 'completed' ? 1:0;
			$this->patients_payments_model->update($payment_id, array("status" => $status));

			$this->session->set_flashdata('message', $this->ion_auth->messages());

			redirect("doctor/patients_uzi", 'refresh');

		} else {
			// display the create user form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			if (!empty($this->data['message'])) {
				$was_validated = "was-validated";
			}
			$this->data["was_validated"] = $was_validated;
		}

		$this->render('doctor/patients_uzi/patient2_view');
	}

	public function get_patient_uzis()
	{
		$payment_id = $this->input->post("payment_id");
		$patient_active_uzis = $this->patient_uzi_model->get_patient_uzi($payment_id);

		$uzi_ids = array();
		foreach ($patient_active_uzis as $uzi) {
			$uzi_ids[] = $uzi["uzi_id"];
		}

		echo json_encode($uzi_ids);

	}

    function file_selected(){

        $this->form_validation->set_message('file_selected', 'Илтимос файлни танланг');
        if (empty($_FILES['userfile']['name'])) {
            return false;
        }else{
            return true;
        }
    }

    public function patient_info($payment_id)
    {
        $this->data["refer"] = site_url("doctor/patients_uzi");

        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/doctor/js/print.min.css").'">';
        $this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/divjs/divjs.js").'"></script>';

        //Bemor haqidagi ma'lumotlar
        $patient = $this->patient_uzi_model->get_patient($payment_id);
        $this->data["patient"]      = $patient;

        $payment = $this->patients_payments_model->get_patient_payment($payment_id);

        //Bemorning uzilari
        $uzi_results = $this->patient_uzi_model->get_patient_uzi($payment_id);
        $uzis = array();

        foreach ($uzi_results as $uzi) {
            $uzis[$uzi["payment_id"]][] = $uzi;
        }

        $this->data["uzis"]   = $uzis;


        $this->render('doctor/patients_uzi/patient_info_view');
    }

    public function patient_info_all($patient_id)
    {
        $this->data["refer"] = site_url("doctor/patients_uzi/all");

        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/doctor/js/print.min.css").'">';
        $this->data['before_appjs'] = '<script src="'.site_url("assets/doctor/js/print.min.js").'"></script>';

        //Bemor haqidagi ma'lumotlar
        $patient = $this->patients_model->get_patient($patient_id);
        $this->data["patient"]      = $patient;

        //Bemorning uzilari
        $uzi_results = $this->patient_uzi_model->get_patient_uzi_by_patient($patient_id);
        $uzis = array();

        foreach ($uzi_results as $uzi) {
            $uzis[$uzi["payment_id"]][] = $uzi;
        }

        $this->data["uzis"]   = $uzis;


        $this->render('doctor/patients_uzi/patient_info_all_view');
    }

    public function ajax_patient_uzi_status()
    {
        $payment_id         = $this->input->post("payment_id");
        $uzi_status         = $this->input->post("doctor_status");
//        $patient_doctor_id  = $this->input->post("patient_doctor_id");

        //order_status = 0-navbatda, 1-doctor qabulida, 2-laboratoriyada, 3-uzida, 4-finish
        //doctor_status = 0-tulov qilinmagan, 1-tulov qilingan, 2-tamomlangan, 3-qabul kirdi
        if($uzi_status == "qabulda") {
            $this->patients_payments_model->update($payment_id, array("order_status" => 3, "uzi_status" => 3));
        } elseif ($uzi_status == "qabul_tamom") {
            $this->patient_uzi_model->update_status($payment_id, array("status" => 1));
            $this->patients_payments_model->update($payment_id, array("uzi_status" => 2));

            $payment_status     = $this->patients_payments_model->check_payment_status($payment_id);
            if($payment_status == 'completed') {
                $this->patients_payments_model->update($payment_id, array("status" => 1, "order_status" => 4));
            } else {
                $this->patients_payments_model->update($payment_id, array("order_status" => 0));
            }
        }

        echo json_encode($uzi_status);
    }

    public function ajax_save_uzi_result() {
		$results 				= $this->input->post("result");
		$uzi_conclusion_result 	= $this->input->post("uzi_conclusion_result");
		$uzi_conclusion_id 		= $this->input->post("uzi_conclusion_id");
		$lang 					= 1;
		if($this->input->post("lang") == "ru") {
			$lang = 2;
		}

		foreach ($results as $patient_uzi_id => $uzi_result) {
			$this->patient_uzi_model->update($patient_uzi_id, array("result" => $uzi_result, "lang" => $lang));
		}

		$res = $this->patient_uzi_model->update($uzi_conclusion_id, array("result" => $uzi_conclusion_result, "lang" => $lang));

		$payment_id = 4441;
		$this->load->helper("lab_form");
		$result_html = build_uzi_results($payment_id);

        echo json_encode(["updated" => true]);
    }

    //preview button bosilganda payment id buyicha laboratoriyalar natijalarining tablitsasi
    public function ajax_print_preview() {
        $payment_id     = $this->input->post("payment_id");
        $this->load->helper("lab_form");
        $html = build_uzi_results($payment_id);

        echo json_encode($html);
    }

	public function ajax_print_preview2()
	{
		if($this->input->is_ajax_request()) {
			$payment_id = $this->input->post("payment_id");
			$uzi_results 	= $this->patient_uzi_model->get_patient_uzi($payment_id);
			$uzi_conclusion = $this->patient_uzi_model->get_patient_uzi_conclusion($payment_id);

			$result_html  = '<div class="uzi_print text-dark p-3">';
				foreach ($uzi_results as $result) {
				$result_html .= '<div class="font-weight-bold mt-3 mb-3 font-16">'.$result["name"].'</div>';
				$result_html .= '<div class="text-dark">'.$result["result"].'</div>';
				}

				$result_html .= '<div class="font-weight-bold mt-3 mb-3 font-16">Хулоса</div>';
				$result_html .= '<div class="text-dark">'.$uzi_conclusion["result"].'</div>';
			$result_html .= '</div>';

//			$this->load->helper("lab_form");
//			$result_html = build_uzi_results($payment_id);

			echo json_encode($result_html);
		}
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
			redirect("doctor/patients_uzi", 'refresh');

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

		$this->render('doctor/patients_uzi/patient_edit_view');
    }

	public function ajax_load_template_lang()
	{
		if ($this->input->is_ajax_request()) {
			$payment_id = $this->input->post("payment_id");
			$lang 		= $this->input->post("lang");

			$patient_active_uzis = $this->patient_uzi_model->get_patient_uzi($payment_id);
			$patient_active_uzi_ids = array();
			foreach ($patient_active_uzis as $uzi) {
				$patient_active_uzi_ids[] = $uzi["uzi_id"];
			}

			$templates_uzi 	= $this->templates_uzi_model->get_templates_by_uzi_ids($patient_active_uzi_ids);
			$templates 		= array();
			if($lang === 'uz') {
				foreach ($templates_uzi as $template) {
					$templates[$template["uzi_id"]]["result"] 	= $template["template"];
					$templates[$template["uzi_id"]]["lang"] 	= 1;
				}
			} elseif ($lang === 'ru') {
				foreach ($templates_uzi as $template) {
					$templates[$template["uzi_id"]]["result"] 	= $template["template_ru"];
					$templates[$template["uzi_id"]]["lang"] 	= 2;
				}
			}

//			$this->patient_uzi_model->update_by_payment_id($payment_id, $templates);

			$response = array("lang" => $lang, "templates" => $templates);

			echo json_encode($response);
		}
	}

	public function download_pdf($payment_id, $lang) {
		$this->load->model("settings_model");
    	$this->load->library('UZIPDF');

		$pdf = new UZIPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Abdurahmon Mirzakulov');
		$pdf->SetTitle('UZI Taxlili');
		$pdf->SetSubject('Ultravoushli Tekshiruv Natijasi');

		$payment = $this->patients_payments_model->get_patient_payment($payment_id);
		$patient = $this->patients_model->get_patient($payment["patient_id"]);
		$clinic_details = $this->settings_model->get_group_settings("LBP");

		//pdf header (logo va klinika malumotlar)
		$clinic_arr = array(
			"name" 			=> $clinic_details["name"],
			"orientation" 	=> $clinic_details["orientation"],
			"phone" 		=> $clinic_details["phone"],
			"telegram" 		=> $clinic_details["telegram"],
			"email" 		=> $clinic_details["email"],
			"web_address" 	=> $clinic_details["web_address"],
		);

		$clinic_prefix_arr = array(
			"name" 			=> "",
			"orientation" 	=> "Мўлжал: ",
			"phone" 		=> "Телефонлар: ",
			"telegram" 		=> "Телеграм: ",
			"email" 		=> "Email: ",
			"web_address" 	=> "Веб сайт: ",
		);

		$clinic_data = "";
		foreach ($clinic_arr as $key => $text) {
			if(!empty($text)) $clinic_data .=$clinic_prefix_arr[$key].$text."<br>";
		}

		$pdf->setClinicData($clinic_data);

		//Bemor malumotlari
		$patient_name = $patient['last_name'] . ' ' . $patient['first_name'];
		$patient_data = array(
			"name" 			=> $patient_name,
			"created_date" 	=> date_formating(strtotime($payment['created_date']), 'mt'),
			"dob" 			=> date("Y", strtotime($patient['dob'])),
			"printed" 		=> date("d.m.Y"),
			"phone" 		=> phone_number_format($patient['phone']),
		);
		$pdf->setPatientData($patient_data);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$PDF_MARGIN_LEFT = 10;
		$PDF_MARGIN_TOP = 50;
		$PDF_MARGIN_RIGHT = 10;
		$pdf->SetMargins($PDF_MARGIN_LEFT, $PDF_MARGIN_TOP, $PDF_MARGIN_RIGHT);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		$pdf->setLang($lang);
		$pdf->setDoctorName("Мирзакулов А.");

		//set font
		//Uzi malumotlari
		$pdf->AddPage();
		$pdf->SetFont('dejavusans', '', 9);
		$uzi_results 	= $this->patient_uzi_model->get_patient_uzi($payment_id);
		$uzi_conclusion = $this->patient_uzi_model->get_patient_uzi_conclusion($payment_id);

		$result_html  = '';
		foreach ($uzi_results as $result) {
			$result_html .= '<b>'.($lang == 1 ? $result["name"]:$result["name_ru"]).'</b><br>';
			$result_html .= $result["result"];
		}

		$result_html .= '<b>Хулоса</b><br>';
		$result_html .= $uzi_conclusion["result"];

		$tagvs = array('p' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 1)));
		$pdf->setHtmlVSpace($tagvs);

		$pdf->writeHTML($result_html, true, false, false, false, '');

		// ---------------------------------------------------------
		ob_clean();
		//Close and output PDF document
		$pdf->Output('uzi_natijasi_'.$patient_name.'_'.date("dmY").'.pdf', 'I');
	}
}
