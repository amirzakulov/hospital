<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uzi extends Admin_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model(array("uzi_model", "employees_model", "templates_uzi_model"));
        $this->load->language(array("uzi", "templates_uzi"));
    }

    /*************************************************************************************************
     *                                  Uzi                                                          *
     **************************************************************************************************/

    public function index()
    {
        $this->data['title'] = lang("uzi_title");
        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">';

        $this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>';

        $uzis = $this->uzi_model->get_uzis();

        $this->data["uzis"] = $uzis;
        $this->render('admin/uzi/index_view');
    }

    public function add()
    {
        $this->data["title"] = lang("uzi_add_title");

        $was_validated = "";

        $prefix = "uzi";
        $max_id = max_id("uzi");
        $code = uniqe_code_genetrator($prefix, $max_id, 4);

        // validate form input
        $this->form_validation->set_rules('name', $this->lang->line('uzi_name'), 'trim|required');
        $this->form_validation->set_rules('price', $this->lang->line('uzi_price'), 'trim');

        if ($this->form_validation->run() === TRUE)
        {
            $renew = is_null($this->input->post("renew")) ? false:true;
            unset($_POST["renew"]);

            $_POST["code"] = $code;
            $uzi_id = $this->uzi_model->add($this->input->post());
            $new_uzi = $this->uzi_model->get_uzi($uzi_id);

            //get all uzi doctors user info
			$users = $this->ion_auth->users(9)->result();
			//add to templates_uzi table with doctor_id
			foreach ($users as $user) {
				$doctor = $this->employees_model->get_employee_id($user->id);
				$arr = array(
					"uzi_id" 		=> $uzi_id,
					"title" 		=> $new_uzi["name"],
					"title_ru" 		=> $new_uzi["name_ru"],
					"template" 		=> null,
					"template_ru" 	=> null,
					"doctor_id" 	=> $doctor["id"],
					"description" 	=> null,
				);

				$this->templates_uzi_model->add($arr);
			}

            if($renew) {
                redirect("admin/uzi/add/", 'refresh');
            } else {
                redirect("admin/uzi", 'refresh');
            }

        }
        else
        {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            if(!empty($this->data['message']))
            {
                $was_validated = "was-validated";
            }
            $this->data["was_validated"] = $was_validated;

            $this->data['name'] = [
                'name'  => 'name',
                'id'    => 'name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('name'),
                "class" => "form-control",
                "required" => "",
                "tabindex" => 1
            ];

			$this->data['name_ru'] = [
				'name'  => 'name_ru',
				'id'    => 'name_ru',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('name_ru'),
				"class" => "form-control",
				"required" => "",
				"tabindex" => 2
			];

            $this->data['price'] = [
                'name'  => 'price',
                'id'    => 'price',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('price'),
                "class" => "form-control",
                "required" => "",
                "tabindex" => 3
            ];

            $this->render("admin/uzi/add_view");
        }
    }

    public function edit($id)
    {
        $this->data["title"] = lang("uzi_edit_title");

        $uzi = $this->uzi_model->get_uzi($id);
        $was_validated = "";

        // validate form input
        $this->form_validation->set_rules('name', $this->lang->line('uzi_name'), 'trim|required');
        // $this->form_validation->set_rules('name_ru', $this->lang->line('uzi_name_ru'), 'trim');
        $this->form_validation->set_rules('price', $this->lang->line('uzi_price'), 'trim');

        if ($this->form_validation->run() === TRUE)
        {

        	// var_dump($this->input->post());
        	// die();

            $this->uzi_model->update($id, $this->input->post());

			$new_uzi = $this->uzi_model->get_uzi($id);

			//get all uzi doctors user info
			$users = $this->ion_auth->users(9)->result();
			//add to templates_uzi table with doctor_id
			foreach ($users as $user) {
				$doctor = $this->employees_model->get_employee_id($user->id);
				$arr = array(
					"title" 		=> $new_uzi["name"],
					"title_ru" 		=> $new_uzi["name_ru"],
				);

				$this->db->where(array("uzi_id" => $id, "doctor_id" => $doctor["id"]));
				$this->db->update("templates_uzi", $arr);
			}



            redirect("admin/uzi", 'refresh');
        }
        else
        {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            if(!empty($this->data['message']))
            {
                $was_validated = "was-validated";
            }
            $this->data["was_validated"] = $was_validated;

            $this->data['name'] = [
                'name'  => 'name',
                'id'    => 'name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('name', $uzi["name"]),
                "class" => "form-control",
                "required" => ""
            ];

            $this->data['name_ru'] = [
                'name'  => 'name_ru',
                'id'    => 'name_ru',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('name_ru', $uzi["name_ru"]),
                "class" => "form-control",
                "required" => ""
            ];

            $this->data['price'] = [
                'name'  => 'price',
                'id'    => 'price',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('price', $uzi["price"]),
                "class" => "form-control",
                "required" => ""
            ];

            $this->render("admin/uzi/edit_view");
        }
    }


    /**
     * @var boolean linked - ushbu item boshqa table larga bog'langanmi yoqmiligini bildiradi (true - bog'langan, false - bog'lanmagan)
     **/
    public function delete()
    {
        $id = $this->input->post("id");
        if(!is_null($this->input->post("confirm")))
        {
        	$this->load->model("templates_uzi_model");
        	$this->templates_uzi_model->delete_by_uzi_id($id);
            $deleted = $this->uzi_model->delete($id);
            echo json_encode(array("deleted" => $deleted));
        }
        else
        {
            $linked = $this->uzi_model->check_links($id);
            if($linked > 0) {
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }
        }
    }

    public function templates() {
		$this->data['title'] = "Шаблонлар";
		$this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">';

		$this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>';

		$this->data["uzis"] = $this->uzi_model->get_uzis();

		$this->render('admin/uzi/templates_view');
	}

	public function template_edit($id)
	{
		$this->data["title"] = lang("uzi_edit_title");

		$this->data['before_themeStyle'] = '
                                        <link rel="stylesheet" type="text/css" href="'.site_url("assets/doctor/js/summernote-0.8.18/summernote-lite.min.css").'">
                                        ';

		$this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/doctor/js/summernote-0.8.18/summernote-lite.min.js").'"></script>
                                        ';

		$uzi = $this->uzi_model->get_uzi($id);

		$this->data["uzi"] = $uzi;
		$was_validated = "";

		// validate form input
		$this->form_validation->set_rules('template', "Андоза", 'trim');
		$this->form_validation->set_rules('template_ru', "Шаблон", 'trim');

		if ($this->form_validation->run() === TRUE)
		{

			$template = array(
				"template" 		=> $this->input->post("template"),
				"template_ru" 	=> $this->input->post("template_ru"),
			);

			$id = $this->uzi_model->update($id, $template);

			redirect("admin/uzi/template_show/".$id, 'refresh');
		}
		else
		{
			// display the create user form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			if(!empty($this->data['message']))
			{
				$was_validated = "was-validated";
			}
			$this->data["was_validated"] = $was_validated;

			$this->data['template'] = [
				'name'  => 'template',
				'id'    => 'template',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('template', $uzi["template"]),
				"class" => "form-control rich_text_content",
				"required" => ""
			];

			$this->data['template_ru'] = [
				'name'  => 'template_ru',
				'id'    => 'template_ru',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('template_ru', $uzi["template_ru"]),
				"class" => "form-control rich_text_content",
				"required" => ""
			];

			$this->render("admin/uzi/template_edit_view");
		}
	}

	public function template_show($id)
	{
		$this->data["title"] = lang("uzi_edit_title");
		$this->data['before_themeStyle'] = '';
		$this->data['before_appjs'] = '';

		$this->data["id"] = $id;
		$this->data["template"] = $this->uzi_model->get_uzi($id);
		$this->render("admin/uzi/template_show_view");
	}

	public function template_view_pdf($lang, $id)
	{
		$this->load->model("settings_model");
		$this->load->library('UZIPDF');

		$pdf = new UZIPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Abdurahmon Mirzakulov');
		$pdf->SetTitle('UZI Taxlili');
		$pdf->SetSubject('Ultravoushli Tekshiruv Natijasi');
		$pdf->setLang($lang);
		$pdf->setDoctorName("Мирзакулов А.");
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

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		//Uzi malumotlari
		$pdf->AddPage();
		$pdf->SetFont('dejavusans', '', 10);
		$template 		= $this->uzi_model->get_uzi($id);
		$uzi_conclusion = "<p><b>Хулоса</b></p> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.";
		$uzi_conclusion_ru = "<p><b>Вывод</b></p> Lorem Ipsum является текст-заполнитель обычно используется в графических, печать и издательской индустрии для предварительного просмотра макета и визуальных макетах.";

		$result_html  = '';

		if($lang == 1) {
			$result_html .= '<b>'.$template["name"].'</b><br>';
			$result_html .= $template["template"];
		} else {
			$result_html .= '<b>'.$template["name_ru"].'</b><br>';
			$result_html .= $template["template_ru"];
		}

		$result_html .= ($lang == 1 ? $uzi_conclusion : $uzi_conclusion_ru);

		$pdf->writeHTML($result_html, true, false, false, false, '');

		// ---------------------------------------------------------
		ob_clean();
		//Close and output PDF document
		$pdf->Output('template.pdf', 'I');
	}

}
