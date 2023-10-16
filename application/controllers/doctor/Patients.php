<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Patients extends Doctor_Controller {

    private $doctor_id;
    function __construct()
    {
        parent::__construct();

        if(!$this->ion_auth->in_group(4)) {
            show_404("", false);
            exit("sizning xuquqingiz yetarli emas!!!");
        }

        $this->load->model(
            array(
                "patients_model",
                "users_model",
                "doctors_model",
                "patient_doctor_model",
                "laboratory_model",
                "patient_laboratories_model",
                "uzi_model",
                "patient_uzi_model",
                "partners_model",
                "patients_payments_model",
                "patients_payments_details_model",
                "regions_model",
                "cities_model",
                "expenses_model",
                "expense_type_model",
                "payment_types_model",
                "services_model",
                "patient_service_model",
                "rooms_model",
                "room_beds_model",
                "patient_room_model",
            ));

        $this->load->language("patients");
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
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        ';

        $patients = $this->patients_payments_model->get_doctor_patients($this->doctor_id);

        $this->data["incomplete_patients"] = $patients["incomplete"];
        $this->data["completed_patients"] = $patients["completed"];

        $this->render('doctor/patients/index_view');
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

        $patients = $this->patient_doctor_model->get_patients($this->doctor_id);

        $this->data["patients"] = $patients;

        $this->render('doctor/patients/all_view');
    }

    public function patient($patient_doctor_id)
    {
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/doctor/js/richtexteditor/richtext.min.css").'">
        ';

        $this->data['before_appjs'] = '
        <script src="'.site_url("assets/doctor/js/richtexteditor/jquery.richtext.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/jquery.hideseek.min.js").'"></script>
        <script src="'.site_url("assets/admin/js/functions_patients.js?".time()).'"></script>
        ';

        $this->load->library("services");
        $service_config["form"]         = false;
        $service_config["submit_btn"]   = false;
        $service_config["app_part"]     = "doctor";
        $service_config["doctor_id"]    = $this->doctor_id;
        $this->data["services_template"]= $this->services->generate($service_config);
        $this->data["patient_doctor_id"] = $patient_doctor_id;

        //Bemor haqidagi ma'lumotlar
        $patient                    = $this->patient_doctor_model->get_patient($patient_doctor_id);
        $this->data["patient"]      = $patient;
        $patient_items_for_payment_id = $this->patients_payments_model->get_patient_items_for_payment($patient["patient_id"], $this->doctor_id);

		$patient_items_for_payment_id["id"] = is_null($patient_items_for_payment_id) ? 1:$patient_items_for_payment_id;

        $this->data["payment_items"] = $this->patients_payments_model->get_patient_payment($patient_items_for_payment_id["id"]);

        //Bemorning tashxislari
        $patient_pds                = $this->patient_doctor_model->get_patient_history($patient["patient_id"]);
        $this->data["patient_pds"]  = $patient_pds;

        //Bemorga tashxis quyilganmi yuqmi tekshiramiz
        $patient_pds_check = false;
        foreach ($patient_pds as $patient_pd) {
            if(!empty($patient_pd["diagnosis"])) {
                $patient_pds_check = true;
                continue;
            }
        }
        $this->data["patient_pds_check"] = $patient_pds_check;

        //Bemorning laboratoriyalari
        $payments = $this->patients_payments_model->get_patient_payment_by_patient($patient["patient_id"]);
        $laboratories = array();
        $this->load->helper("lab_form");
        foreach ($payments as $k => $payment) {
            if($payment["laboratory_status"] > 0) {
                $html = build_laboratory_results_table($payment["id"], $payment["created_date"], false);
                if($html != false) {
                    $laboratories[$payment["created_date"]]["html"] = $html;
                }
            }
        }

        $this->data["laboratories"]   = $laboratories;

        //Bemorning UZI lari
        $uzi = array();
        $uzis = $this->patient_uzi_model->get_patient_uzi_by_patient($patient["patient_id"]);
        foreach ($uzis as $uz) {
            $uzi_result = $this->patient_uzi_model->get_uzi_result($uz["payment_id"]);
            $uzi[$uz["payment_id"]]["date"] = $uz["created_date"];
            $uzi[$uz["payment_id"]]["uzi_result"] = $uzi_result["result"];
            $uzi[$uz["payment_id"]]["data"][] = $uz;
        }
        $this->data["uzis"]   = $uzi;

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

            $this->data['patient_complaint'] = [
                'name' => 'patient_complaint',
                'id' => 'patient_complaint',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('patient_complaint', $patient["patient_complaint"]),
                'rows' => 3,
                'cols' => 5,
                'class' => 'form-control rich_text_content',
//				'disabled'=> "disabled"
            ];
            $this->data['anamnesis_morbi'] = [
                'name' => 'anamnesis_morbi',
                'id' => 'anamnesis_morbi',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('anamnesis_morbi', $patient["anamnesis_morbi"]),
                'rows' => 3,
                'cols' => 5,
                'class' => 'form-control rich_text_content'
            ];
            $this->data['anamnesis_vitae'] = [
                'name' => 'anamnesis_vitae',
                'id' => 'anamnesis_vitae',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('anamnesis_vitae', $patient["anamnesis_vitae"]),
                'rows' => 3,
                'cols' => 5,
                'class' => 'form-control rich_text_content'
            ];
            $this->data['status_praesens'] = [
                'name' => 'status_praesens',
                'id' => 'status_praesens',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('status_praesens', $patient["status_praesens"]),
                'rows' => 3,
                'cols' => 5,
                'class' => 'form-control rich_text_content'
            ];
            $this->data['diagnosis'] = [
                'name' => 'diagnosis',
                'id' => 'diagnosis',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('diagnosis', $patient["diagnosis"]),
                'rows' => 3,
                'cols' => 5,
                'class' => 'form-control rich_text_content'
            ];
            $this->data['description'] = [
                'name' => 'description',
                'id' => 'description',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('description', $patient["description"]),
                'rows' => 3,
                'cols' => 5,
                'class' => 'form-control rich_text_content'
            ];
        }
        
        $this->render('doctor/patients/patient_view');
    }

    public function patient_info($patient_doctor_id)
    {
        $this->data["refer"] = site_url("doctor/patients");

        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/doctor/js/print.min.css").'">';
        $this->data['before_appjs'] = '<script src="'.site_url("assets/doctor/js/print.min.js").'"></script>';


        //Bemor haqidagi ma'lumotlar
        $patient = $this->patient_doctor_model->get_patient($patient_doctor_id);
        $this->data["patient"] = $patient;

        //Bemorning laboratoriyalari
        $payments = $this->patients_payments_model->get_patient_payment_by_patient($patient["patient_id"]);
        $laboratories = array();
        $this->load->helper("lab_form");
        foreach ($payments as $k => $payment) {
            $html = build_laboratory_results_table($payment["id"], $payment["created_date"], false);
            if($html != false) {
                $laboratories[]["html"] = $html;
            }
        }

        $this->data["laboratories"]   = $laboratories;


        $this->render('doctor/patients/patient_info_view');
    }

    public function patient_info_all($patient_id)
    {
        $this->data["refer"] = site_url("doctor/patients/all");

        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/doctor/js/print.min.css").'">';
        $this->data['before_appjs'] = '<script src="'.site_url("assets/doctor/js/print.min.js").'"></script>';

        //Bemor haqidagi ma'lumotlar
        $this->data["patient"] = $this->patients_model->get_patient($patient_id);

        //Bemorning tashxislari
        $patient_pds = $this->patient_doctor_model->get_patient_history($patient_id);
        $this->data["patient_pds"]  = $patient_pds;

        //Bemorning laboratoriyalari
        $payments = $this->patients_payments_model->get_patient_payment_by_patient($patient_id);
        $laboratories = array();
        $this->load->helper("lab_form");
        foreach ($payments as $k => $payment) {
            $html = build_laboratory_results_table($payment["id"], $payment["created_date"], false);
            if($html != false) {
                $laboratories[]["html"] = $html;
            }
        }

        $this->data["laboratories"]   = $laboratories;


        $this->render('doctor/patients/patient_info_all_view');
    }

    public function services($patient_id, $old_payment_id) {

        ///////////////////////////////////////////////////////////////////////////////////
        $this->data["title"] = "Тўлов қўшиш";
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.hideseek.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/functions_patients.js?".time()).'"></script>
                                        ';

        $this->data["patient"] = $this->patients_model->get_patient($patient_id);
        $this->load->helper("services");

        //Bemorning tulovlari
        $payments = $this->patients_payments_model->get_patient_payment_by_patient($patient_id);
        $this->data["payments"] = $payments;

        $this->form_validation->set_rules('total', "Жами", 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            //tulov qushish
            if(!empty($this->input->post("doctor_id")[0]) || !empty($this->input->post('laboratory_id')) || !empty($this->input->post('uzi_id'))) {
                $total 			= $this->input->post("total");
//				$discount_type 	= $this->input->post("discount_type");
//				$discount_value = $this->input->post("discount_value");
//				$discount 		= $this->get_discount($discount_type, $discount_value, $total);

				$payment_arr = array(
					"doctor_status"     => (empty($this->input->post("doctor_id")[0]) ? 0:1),
					"laboratory_status" => (empty($this->input->post('laboratory_id')) ? 0:1),
					"uzi_status"        => (empty($this->input->post('uzi_id')) ? 0:1),
					"service_status"    => (empty($this->input->post('service_id')) ? 0:1),
					"room_status"       => 0,
					"order_status"      => 0,
					"patient_id"        => $patient_id,
					"paid"              => $this->input->post("paid"),
					"debt"              => $this->input->post("debt"),
					"discount_type"     => 0,
					"discount_value"    => 0,
					"discount"	    	=> 0,
					"total"             => $total,
					"status"            => 0,
					'partner_id'        => $this->input->post('partner_id'),
					'payment_type'      => $this->input->post('payment_type'),
				);
				$payment_id = $this->patients_payments_model->add($payment_arr);

                $payment_details_arr = array(
                    'payment_id'      	=> $payment_id,
                    "paid"              => $this->input->post("paid"),
                    'by_cash'      		=> $this->input->post('by_cash'),
                    'by_card'      		=> $this->input->post('by_card'),
                    'by_bank'      		=> $this->input->post('by_bank'),
                );
                $this->patients_payments_details_model->add($payment_details_arr);
            }

            //agar tulov bajarilgan bulsa
            if(isset($payment_id)) {
                //doctorlar
                if(!empty($this->input->post("doctor_id")[0])) {
                    foreach ($this->input->post('doctor_id') as $index => $doctor_id) {
                        $doc_arr = array(
                            "patient_id" => $patient_id,
                            "doctor_id" => $doctor_id,
                            "payment_id" => $payment_id,
                            "status" => 0
                        );
                        $this->patient_doctor_model->add($doc_arr);
                    }
                }

                //laboratoriyalar
                if(!empty($this->input->post('laboratory_id'))) {
                    foreach ($this->input->post('laboratory_id') as $laboratory_id) {
                        $lab_value = $this->laboratory_model->get_laboratory($laboratory_id);
                        $lab_arr = array(
                            "patient_id"    => $patient_id,
                            "lab_id"        => $laboratory_id,
                            "result"        => $lab_value["default_value"],
                            "payment_id"    => $payment_id,
                            "status"        => 0,
                            "recommendation"=> 0,
                            "is_parent"     => 1,
                        );
                        $this->patient_laboratories_model->add($lab_arr);

                        $laboratory = $this->laboratory_model->sub_categories($laboratory_id);
                        if(count($laboratory) > 0) {
                            foreach ($laboratory as $sublab) {
                                $lab_arr = array(
                                    "patient_id"    => $patient_id,
                                    "lab_id"        => $sublab["id"],
                                    "result"        => $sublab["default_value"],
                                    "payment_id"    => $payment_id,
                                    "status"        => 0,
                                    "recommendation"=> 0,
                                    "is_parent"     => 0,
                                );

                                $this->patient_laboratories_model->add($lab_arr);
                            }
                        }
                    }
                }

                //uzilar
                if(isset($payment_id) && !empty($this->input->post('uzi_id'))) {
                    foreach ($this->input->post('uzi_id') as $uzi_id) {
                        $uzi_arr = array(
                            "patient_id" => $patient_id,
                            "uzi_id" => $uzi_id,
                            "payment_id" => $payment_id,
                            "status" => 0
                        );
                        $this->patient_uzi_model->add($uzi_arr);
                    }
                }
            }
            echo "<script>window.close();</script>";
            redirect("doctor/patients/patient/".$old_payment_id, 'refresh');
        } else {

            $this->data['message'] = validation_errors();
            $this->data["services"] = services_list($this->doctor_id, $old_payment_id);
        }

        $this->render('doctor/patients/services_view');
    }

    /**
     * Bemor navbatini "Doctor Navbatida" ga uzgatirish
     * **/
    public function ajax_patient_order_status() {
        $payment_id = $this->input->post("payment_id");
        $payment_status = $this->patients_payments_model->update($payment_id, array("order_status" => 1));

        echo json_encode($payment_status);
    }


    /*****************************************/
    public function ajax_patient_doctor_status()
    {
        $payment_id         = $this->input->post("payment_id");
        $doctor_status      = $this->input->post("doctor_status");
        $patient_doctor_id  = $this->input->post("patient_doctor_id");

        //order_status = 0-navbatda, 1-doctor qabulida, 2-laboratoriyada, 3-uzida, 4-finish
        //doctor_status = 0-tulov qilinmagan, 1-tulov qilingan, 2-tamomlangan, 3-qabul kirdi
        if($doctor_status == "qabulda") {
            $this->patients_payments_model->update($payment_id, array("order_status" => 1, "doctor_status" => 3));
        } elseif ($doctor_status == "qabul_tamom") {
            $this->patient_doctor_model->update($patient_doctor_id, array("status" => 1));
            $this->patients_payments_model->update($payment_id, array("doctor_status" => 2));
            $payment_status     = $this->patients_payments_model->check_payment_status($payment_id);
            if($payment_status == 'completed') {
                $this->patients_payments_model->update($payment_id, array("order_status" => 4, "status" => 1));
            } else {
                $this->patients_payments_model->update($payment_id, array("order_status" => 0));
            }
        }

        echo json_encode($doctor_status);
    }

    /**
     * Bemor tashxisini saqlash
     * */
    public function ajax_patient_diagnos_save() {

        foreach ($this->input->post() as $k => $d) {
            if($d == "<div><br></div>" || $d == "<br>") {
                $_POST[$k] = "";
            }
        }

        $pd_id  = $this->input->post("patient_doctor_id");
        $patient_id  = $this->input->post("patient_id");
        unset($_POST["patient_doctor_id"]);
        unset($_POST["patient_id"]);

        $this->form_validation->set_rules('patient_complaint', $this->lang->line("patients_patient_complaint"), 'required');

        if ($this->form_validation->run() == FALSE) {
            $result = array("error" => true, "message" => form_error('patient_complaint'));
        } else {
            $this->patient_doctor_model->update($pd_id, $this->input->post());
            $patient_pds = $this->patient_doctor_model->get_patient_history($patient_id);

            $diagnos_html = "";
            foreach ($patient_pds as $diagnos) {
                $diagnos_html .= '<div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <div class="card-title"><i class="fa fa-calendar"></i> '.date("d.m.Y", strtotime($diagnos["updated_date"])).'</div>
                                        <p class="card-text">'.$diagnos["diagnosis"].'</p>
                                    </div>
                                  </div>';
            }

            $result = array("error" => false, "data" => $this->input->post(), "html" => $diagnos_html);
        }

        echo json_encode($result);
        
    }





}
