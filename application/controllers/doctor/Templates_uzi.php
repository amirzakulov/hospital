<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Templates_uzi extends Doctor_Controller {

    private $doctor_id;
    function __construct()
    {
        parent::__construct();

        $this->load->model(array("patients_payments_model", "templates_uzi_model", "uzi_model"));
        $this->load->language("templates_uzi");
        $this->doctor_id = $this->session->userdata("employee_id");
    }

    public function index() {
        $this->data['title'] = 'Андозалар';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        ';

		$this->data["templates"] = $this->templates_uzi_model->get_templates($this->doctor_id);

        $this->render('doctor/templates_uzi/index_view');
    }

//    public function add()
//    {
//        $this->data["title"] = "Андоза қўшиш";
//        $this->data['before_themeStyle'] = '
//                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
//                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
//                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/js/chosen/chosen.css").'">
//                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/doctor/js/richtexteditor/richtext.min.css").'">
//                                        ';
//
//        $this->data['before_appjs'] = '
//                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
//                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
//                                        <script src="'.site_url("assets/admin/js/chosen/chosen.jquery.min.js").'"></script>
//                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
//                                        <script src="'.site_url("assets/doctor/js/richtexteditor/jquery.richtext.min.js").'"></script>
//                                        ';
//
//        $was_validated      = "";
//
//        $uzis = $this->uzi_model->get_uzis();
//        $uzi_id_options = array("" => "--Танланмаган--");
//        foreach ($uzis as $uzi) {
//            $uzi_id_options[$uzi["id"]] = $uzi["name"];
//        }
//
//        $this->data["uzi_id_options"] = $uzi_id_options;
//
//        // validate form input
//        $this->form_validation->set_rules('uzi_id', $this->lang->line('add_validation_uzi_id_label'), 'trim|required');
//        $this->form_validation->set_rules('title', $this->lang->line('add_validation_title_label'), 'trim|required');
//        $this->form_validation->set_rules('template', $this->lang->line('add_validation_template_label'), 'trim');
//
//        if ($this->form_validation->run() === TRUE) {
//            $_POST["doctor_id"] = $this->doctor_id;
//            $this->templates_uzi_model->add($this->input->post());
//            $this->session->set_flashdata('message', $this->ion_auth->messages());
//
//            redirect("doctor/templates_uzi", 'refresh');
//        } else {
//            // display the create user form
//            // set the flash data error message if there is one
//            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
//
//            if (!empty($this->data['message'])) {
//                $was_validated = "was-validated";
//            }
//            $this->data["was_validated"] = $was_validated;
//
//            $this->data['uzi_id'] = [
//                'name'  => 'uzi_id',
//                'id'    => 'uzi_id',
//                'value' => $this->form_validation->set_select('uzi_id'),
//                'class' => 'custom-select form-control-chosen',
//                "required" => ""
//            ];
//
//            $this->data['template_title'] = [
//                'name'  => 'title',
//                'id'    => 'title',
//                'type'  => 'text',
//                'value' => $this->form_validation->set_value('title'),
//                "class" => "form-control",
//                "required" => ""
//            ];
//            $this->data['template_text'] = [
//                'name' => 'template',
//                'id' => 'template',
//                'type' => 'textarea',
//                'value' => $this->form_validation->set_value('template'),
//                "class" => "form-control rich_text_content",
//                "required" => "",
//                'rows' => 10,
//                'cols' => 30,
//            ];
//
//        }
//
//        $this->render("doctor/templates_uzi/add_view");
//
//    }

    public function edit($id)
    {
        $this->data["title"] = "Тахрирлаш";
        $this->data['before_themeStyle'] = '
                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/js/lou_multi_select/multi-select.css").'">
                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/doctor/js/summernote-0.8.18/summernote-lite.min.css").'">
                                        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/lou_multi_select/jquery.multi-select.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/doctor/js/summernote-0.8.18/summernote-lite.min.js").'"></script>
                                        ';

        $was_validated      = "";

        $template = $this->templates_uzi_model->get_template($id);
        $this->data["user_template"] = $template;

        // validate form input
        $this->form_validation->set_rules('template', $this->lang->line('edit_validation_template_label'), 'trim');
        $this->form_validation->set_rules('template_ru', $this->lang->line('edit_validation_template_label'), 'trim');

        if ($this->form_validation->run() === TRUE) {

			$templates = array(
				"template" 		=> $this->input->post("template"),
				"template_ru" 	=> $this->input->post("template_ru"),
			);

            $this->templates_uzi_model->update($id, $templates);
            $this->session->set_flashdata('message', $this->ion_auth->messages());

            redirect("doctor/templates_uzi/tview/".$id, 'refresh');
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            if (!empty($this->data['message'])) {
                $was_validated = "was-validated";
            }
            $this->data["was_validated"] = $was_validated;

			$this->data['template'] = [
				'name'  => 'template',
				'id'    => 'template',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('template', $template["template"]),
				"class" => "form-control",
				"required" => ""
			];

			$this->data['template_ru'] = [
				'name'  => 'template_ru',
				'id'    => 'template_ru',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('template_ru', $template["template_ru"]),
				"class" => "form-control",
				"required" => ""
			];
        }

        $this->render("doctor/templates_uzi/edit_view");

    }

//	public function show($id)
//	{
//		$this->data["title"] = lang("uzi_edit_title");
//		$this->data['before_themeStyle'] = '';
//		$this->data['before_appjs'] = '';
//
//		$this->data["id"] = $id;
//		$this->data["template"] = $this->templates_uzi_model->get_template($id);
//		$this->render("doctor/uzi/template_show_view");
//	}

    public function tview($id)
    {
        $this->data["title"] = "Андоза";

        $this->data["template"] = $this->templates_uzi_model->get_template($id);

        $this->render("doctor/templates_uzi/view_view");

    }

    public function delete()
    {
        $id = $this->input->post("id");
        $result = $this->templates_uzi_model->delete($id);
        echo json_encode(array("action" => "deleted"));
    }

    public function add_batch()
    {
        $this->data["title"] = "Бемор қўшиш";
        $this->data['before_themeStyle'] = '
                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/js/lou_multi_select/multi-select.css").'">
                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/doctor/js/richtexteditor/richtext.min.css").'">
                                        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/lou_multi_select/jquery.multi-select.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/doctor/js/richtexteditor/jquery.richtext.min.js").'"></script>
                                        ';

        $was_validated      = "";


        $uzis = $this->uzi_model->get_uzis();
        $uzi_id_options = array("" => "--Танланмаган--");
        foreach ($uzis as $uzi) {
            $uzi_id_options[$uzi["id"]] = $uzi["name"];
        }

        $this->data["uzi_id_options"] = $uzi_id_options;
        $this->data["lang_options"] = array(1=>"Ўзб", 2=>"Рус", 3=>"Инг");

        // validate form input
        $this->form_validation->set_rules('uzi_id', $this->lang->line('add_validation_uzi_id_label'), 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $file = "";
            $template = "";

            //upload qilingan fileni qayta ishlaymiz
            $config['upload_path']      = FCPATH."uploads/temp";
            $config['allowed_types']    = 'docx';
            $config['max_size']         = '0';

            $this->load->library('upload',$config);

            if ( ! $this->upload->do_upload('userfile')) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $file = $this->upload->data();
            }

            //agar file upload qilingan bulsa, filedan malumotlarini olamiz
            if(!empty($file)) {
                $this->load->library("phpword");
                $template = $this->phpword->readWordDocx($file["full_path"]);
            }

            $_POST["doctor_id"] = $this->doctor_id;
            $_POST["title"] = str_replace($file["file_ext"], "", $file["client_name"]);
            $_POST["template"] = $template;

            //malumotlarni bazaga yozamiz
            $this->templates_uzi_model->add($this->input->post());

            //vaqtincha saqlangan fileni uchirib tashlaymiz
            $this->load->helper('file');
            delete_files($file["file_path"]);

            redirect("doctor/templates_uzi", 'refresh');
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            if (!empty($this->data['message'])) {
                $was_validated = "was-validated";
            }
            $this->data["was_validated"] = $was_validated;

            $this->data['lang'] = [
                'name'  => 'lang',
                'id'    => 'lang',
                'value' => $this->form_validation->set_select('lang'),
                'class' => 'custom-select',
                "required" => ""
            ];

            $this->data['uzi_id'] = [
                'name'  => 'uzi_id',
                'id'    => 'uzi_id',
                'value' => $this->form_validation->set_select('uzi_id'),
                'class' => 'custom-select',
                "required" => ""
            ];

            $this->data['template_file_upload'] = [
                'name'  => 'userfile',
                'id'    => 'userfile',
                'type'  => 'file',
                'value' => $this->form_validation->set_value('userfile'),
                "class" => "form-control",
                "required" => ""
            ];
        }

        $this->render("doctor/templates_uzi/add_batch_view");

    }

	/**
	 * Andozalarni kuchiramiz
	 */
	public function download()
	{
		//1. Shifokorga tegishli bulgan barcha andozalarni uchirib tashlaymiz
		$this->templates_uzi_model->delete_by_doctor_id($this->doctor_id);

		//2. Andozalarni templates_uzi tablega kuchiramiz
		$uzis = $this->uzi_model->get_uzis();
		foreach ($uzis as $uzi) {

			$template["uzi_id"] 	= $uzi["id"];
			$template["title"] 		= $uzi["name"];
			$template["title_ru"] 	= $uzi["name_ru"];
			$template["template"] 	= $uzi["template"];
			$template["template_ru"]= $uzi["template_ru"];
			$template["doctor_id"] 	= $this->doctor_id;
			$template["description"]= "";

            $this->templates_uzi_model->add($template);
		}
	}

	public function view_pdf($lang, $id)
	{
		$this->load->model("settings_model");
		$this->load->library('UZIPDF');

		$pdf = new UZIPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Abdurahmon Mirzakulov');
		$pdf->SetTitle('UZI Taxlili');
		$pdf->SetSubject('Ultravoushli Tekshiruv Natijasi');
		$pdf->setLang($lang);
		//pdf header (logo va klinika malumotlar)
		$clinic_details = $this->settings_model->get_group_settings("LBP");
		$clinic_arr = array(
			"name" 			=> $clinic_details["name"],
			"orientation" 	=> $clinic_details["orientation"],
			"phone" 		=> $clinic_details["phone"],
			"telegram" 		=> $clinic_details["telegram"],
			"email" 		=> $clinic_details["email"],
			"web_address" 	=> $clinic_details["web_address"],
		);

		$clinic_data = "";
		foreach ($clinic_arr as $text) {
			if(!empty($text)) $clinic_data .=$text."<br>";
		}

		$pdf->setClinicData($clinic_data);

		//Bemor malumotlari
		$patient_data = array(
			"name" 			=> "Эшматов, Тошматов",
			"created_date" 	=> date_formating(time(), 'mt'),
			"dob" 			=> date("Y", time()),
			"printed" 		=> date("d.m.Y"),
			"phone" 		=> phone_number_format("+998901234567"),
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
//		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		//Uzi malumotlari
		$pdf->AddPage();
		$pdf->SetFont('dejavusans', '', 10);
		$template 		= $this->templates_uzi_model->get_template($id);
		$uzi_conclusion = "";


//		$tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 0)));
//		$pdf->setHtmlVSpace($tagvs);
		$result_html  = '';
		$result_html .= '<b>'.$template["name"].'</b><br>';

		if($lang == 1) {
			$result_html .= $template["template"];
		} else {
			$result_html .= $template["template_ru"];
		}

		$result_html .= '<p><b>Хулоса</b></p>';
		$result_html .= $uzi_conclusion;

		$pdf->writeHTML($result_html, true, false, false, false, '');

		// ---------------------------------------------------------
		ob_clean();
		//Close and output PDF document
		$pdf->Output('template.pdf', 'I');
	}
}
