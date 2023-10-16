<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rooms extends Admin_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model(
            array(
                "rooms_model",
                "room_types_model",
                "room_beds_model",
                "regions_model",
                "cities_model",
                "patients_model",
                "payment_types_model",
                "patients_payments_model",
                "patient_room_model",
                "partners_model"
            ));
        $this->load->language("rooms");
    }

    public function index()
    {
        $this->data['title'] = 'Хоналар';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                            ';

        $this->data["room_types"] = $this->room_types_model->room_type_list();
        $this->data["rooms"] = $this->rooms_model->get_rooms();

        $this->render('admin/rooms/index_view');
    }


    public function add()
    {
        $this->data["title"] = "Хона қўшиш";
        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">';

        $this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>';
        $was_validated = "";

        $room_types = $this->room_types_model->get_room_types();
        $room_type_options = array(""=>"--Танлаш--");
        foreach ($room_types as $room_type) {
            $room_type_options[$room_type["id"]] = $room_type["name"];
        }
        $this->data["room_type_options"] = $room_type_options;

        // validate form input
        $this->form_validation->set_rules('room_type_id', $this->lang->line('rooms_type'), 'trim|required');
        $this->form_validation->set_rules('number', $this->lang->line('rooms_number'), 'trim|required');
        $this->form_validation->set_rules('price', $this->lang->line('rooms_price'), 'trim|required');

        if ($this->form_validation->run() === TRUE)
        {
            $bed_amount = $this->input->post("bed_amount");
            unset($_POST["bed_amount"]);
            $room_id = $this->rooms_model->add($this->input->post());

            if(!empty($bed_amount)) {
                for ($i = 1; $i <= $bed_amount; $i++) {
                    $arr = array("name" => $i, "room_id" => $room_id, "price" => $this->input->post("price"));
                    $this->room_beds_model->add($arr);
                }
            }

            redirect("admin/rooms", 'refresh');
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

            $this->data['room_type_id'] = [
                'name'  => 'room_type_id',
                'id'    => 'room_type_id',
                'type'  => 'text',
                'value' => $this->form_validation->set_select('room_type_id'),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['number'] = [
                'name' => 'number',
                'id' => 'number',
                'type' => 'text',
                'value' => $this->form_validation->set_value('number'),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['price'] = [
                'name'      => 'price',
                'id'        => 'price',
                'value'     => $this->form_validation->set_value('price'),
                'class'     => 'form-control',
                "required" => ""
            ];

            $this->data['bed_amount'] = [
                'name'      => 'bed_amount',
                'id'        => 'bed_amount',
                'value'     => $this->form_validation->set_value('bed_amount'),
                'class'     => 'form-control'
            ];
            $this->render("admin/rooms/add_view");
        }
    }

    public function edit($id)
    {
        $this->data["title"] = "Хона қўшиш";
        $this->data['before_themeStyle'] = '<link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">';

        $this->data['before_appjs'] = '<script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>';
        $was_validated = "";

        $this->data["room_type_options"] = $this->room_types_model->room_type_list();
        $room = $this->rooms_model->get_room($id);
        $this->data["room"] = $room;
        $this->data["beds"] = Room_beds_model::get_beds($id);

        // validate form input
        $this->form_validation->set_rules('room_type_id', $this->lang->line('rooms_type'), 'trim|required');
        $this->form_validation->set_rules('number', $this->lang->line('rooms_number'), 'trim|required');
        $this->form_validation->set_rules('price', $this->lang->line('rooms_price'), 'trim|required');

        if ($this->form_validation->run() === TRUE)
        {
            $bed_amount = $this->input->post("bed_amount");
            unset($_POST["bed_amount"]);

            $this->rooms_model->update($id, $this->input->post());
            redirect("admin/rooms", 'refresh');
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

            $this->data['room_type_id'] = [
                'name'  => 'room_type_id',
                'id'    => 'room_type_id',
                'type'  => 'text',
                'value' => $this->form_validation->set_select('room_type_id', $room["room_type_id"]),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['number'] = [
                'name' => 'number',
                'id' => 'number',
                'type' => 'text',
                'value' => $this->form_validation->set_value('number', $room["number"]),
                "class" => "form-control",
                "required" => ""
            ];
            $this->data['price'] = [
                'name'      => 'price',
                'id'        => 'price',
                'value'     => $this->form_validation->set_value('price', $room["price"]),
                'class'     => 'form-control',
                "required" => ""
            ];

            $this->render("admin/rooms/edit_view");
        }
    }

    /**
     * @param null $room_id
     * @return array
     */
    public function room_beds($room_id = null)
    {
        if(is_null($room_id)) { redirect("admin/rooms", 'refresh');}

        $this->data['title'] = 'Хонадаги ётоқлар';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.autocomplete.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
                                            ';

        $was_validated = "";
        //Viloyatlar
        $this->data['regions'] = $this->regions_model->get_regions_array();
        $selected_region_id = $this->config->item("default_region_id"); //defaul_region_id = 3 ga (farg'ona)
        if(!is_null($this->input->post("region_id"))) {
            $selected_region_id = $this->input->post("region_id");
        }
        $this->data['selected_region_id'] = $selected_region_id;

        //Shaxarlar
        $selected_city_id = $this->config->item("default_city_id"); //defaul_city_id = 2 ga (quqonning ID si)
        if(!is_null($this->input->post("city_id"))) {
            $selected_city_id = $this->input->post("city_id");
        }

		$this->data["discount_options"] = array("" => "-- Танланмаган --", 1 => "Сумма", 2 => "Фоиз");

        $this->data['cities'] = $this->cities_model->get_cities_by_region_id($selected_region_id);
        $this->data['selected_city_id'] = $selected_city_id;

        $this->mybreadcrumb->add('Хоналар', site_url("admin/rooms"));
        $this->mybreadcrumb->add('Ётоқлар', "admin/rooms/room_beds");
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->data["room"] = $this->rooms_model->get_room($room_id);
        $this->data["room_id"] = $room_id;
        $this->data["beds"] = Room_beds_model::get_beds($room_id);

		$partners[] = "-- Танлаш --";
		foreach ($this->partners_model->get_partners() as $partner) {
			if($partner["type"] == 1) {
				$partners[$partner["id"]] = $partner["last_name"] ." ". $partner["first_name"];
			} else {
				$partners[$partner["id"]] = $partner["company"];
			}
		}
		$this->data["partners_options"] = $partners;


//        if($this->form_validation->run() === TRUE) {
//
//        } else {

            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            if(!empty($this->data['message']))
            {
                $was_validated = "was-validated";
            }
            $this->data["was_validated"] = $was_validated;


//        }

        /**************************/
        $this->data['patient'] = [
            'name'  => 'room_autocomplete',
            'id'    => 'room_autocomplete',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('room_autocomplete'),
            "class" => "form-control",
            "required" => "",
            "data-url" => site_url("admin/rooms/ajax_patiens_list")
        ];
        $this->data['start_date'] = [
            'name'  => 'start_date',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('start_date'),
            "class" => "form-control datetimepicker_minDate",
            "required" => "",
        ];
        $this->data['end_date'] = [
            'name'  => 'end_date',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('end_date'),
            "class" => "form-control datetimepicker_minDate",
            "required" => "",
        ];
        $this->data['patient2'] = [
            'name'  => 'room_autocomplete',
            'id'    => 'patient_id2',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('room_autocomplete'),
            "class" => "form-control",
            "required" => "",
            "data-url" => site_url("admin/rooms/ajax_patiens_list")
        ];

        $this->data['total'] = [
            'name'  => 'total',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('total'),
            "class" => "form-control",
            "readonly" => "readonly",
        ];

        //update bed
        $this->data['paid'] = [
            'name'  => 'paid',
//            'id'  => 'paid',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('paid'),
            "class" => "form-control",
        ];

        $this->data['debt'] = [
            'name'  => 'debt',
            'id'  => 'debt',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('debt'),
            "class" => "form-control",
			"readonly" => "readonly",
        ];

		$this->data['discount_type'] = [
			'name' => 'discount_type',
			'id' => 'discount_type',
			'value' => $this->form_validation->set_value('discount_type'),
			'class' => 'select js_discount_type',
		];
		$this->data['discount_value'] = [
			'name' => 'discount_value',
			'id' => 'discount_value',
			'type' => 'text',
			'value' => $this->form_validation->set_value('discount_value', 0),
			'class' => 'form-control form-control-paid js_discount_value',
		];

		$this->data['by_cash'] = [
			'name' => 'by_cash',
			'id' => 'by_cash',
			'type' => 'text',
			'value' => $this->form_validation->set_value('by_cash'),
			'class' => 'form-control form-control-paid',
			'placeholder' => 'Нақд',
			"readonly" => "readonly"
		];
		$this->data['by_card'] = [
			'name' => 'by_card',
			'id' => 'by_card',
			'type' => 'text',
			'value' => $this->form_validation->set_value('by_card'),
			'class' => 'form-control form-control-paid',
			'placeholder' => 'Пластик',
		];
		$this->data['by_bank'] = [
			'name' => 'by_bank',
			'id' => 'by_bank',
			'type' => 'text',
			'value' => $this->form_validation->set_value('by_bank'),
			'class' => 'form-control form-control-paid',
			'placeholder' => 'Терминал',
		];


        /**************************/


		$this->data['partners'] = [
			'name' => 'partner_id',
			'id' => 'partner_id',
			'value' => $this->form_validation->set_value('partner_id'),
			'class' => 'select',
		];
        $this->data['first_name'] = [
            'name'  => 'first_name',
            'id'    => 'first_name',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('first_name'),
            "class" => "form-control",
            "required" => ""
        ];
        $this->data['last_name'] = [
            'name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('last_name'),
            "class" => "form-control",
            "required" => ""
        ];
        $this->data['surname'] = [
            'name' => 'surname',
            'id' => 'surname',
            'type' => 'text',
            'value' => $this->form_validation->set_value('surname'),
            "class" => "form-control"
        ];
        $this->data['dob'] = [
            'name' => 'dob',
            'id' => 'dob',
            'type' => 'text',
            'value' => $this->form_validation->set_value('dob'),
            'class' => 'form-control',
        ];
        $this->data['address'] = [
            'name' => 'address',
            'id' => 'address',
            'type' => 'text',
            'value' => $this->form_validation->set_value('address'),
            'class' => 'form-control'
        ];
        $this->data['region'] = [
            'name' => 'region_id',
            'id' => 'region_id',
            'type' => 'text',
            'value' => $this->form_validation->set_value('region_id'),
            'class' => 'custom-select',
            "required" => "",
            "data-url" => site_url("admin/doctors/ajax_get_cities")
        ];
        $this->data['city'] = [
            'name'  => 'city_id',
            'id'    => 'city_id',
            'value' => $this->form_validation->set_select('city_id'),
            'class' => 'select! custom-select',
            "required" => ""
        ];
        $this->data['phone'] = [
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone'),
            'class' => 'form-control',
        ];
        $this->data['partner_id'] = [
            'name' => 'partner_id',
            'id' => 'partner_id',
            'value' => $this->form_validation->set_value('partner_id'),
            'class' => 'custom-select',
        ];
        $this->data['description'] = [
            'name' => 'description',
            'id' => 'description',
            'type' => 'textarea',
            'value' => $this->form_validation->set_value('description'),
            'rows' => 5,
            'cols' => 30,
            'class' => 'form-control'
        ];


        $this->render("admin/rooms/beds_index_view");
    }

    /**
     * @param null $room_id
     * @return array
     */
    public function room_beds_add($room_id = null)
    {
        $this->data["title"] = "Ётоқ қўшиш";
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                            ';
        $was_validated = "";
        $this->data["room_id"] = $room_id;
        $this->form_validation->set_rules('bed_amount', $this->lang->line('rooms_bed_amount'), 'trim|required');

        if ($this->form_validation->run() === TRUE && !is_null($room_id))
        {
            $room = $this->rooms_model->get_room($room_id);
            $beds = Room_beds_model::get_beds($room_id);

            $last = (count($beds) > 0) ? (count($beds)+1):1;
            $count = count($beds)+$this->input->post("bed_amount");

            for ($i = $last; $i <= $count; $i++) {
                $arr = array("name" => $i, "room_id" => $room_id, "price" => $room["price"]);
                $this->room_beds_model->add($arr);
            }

            redirect("admin/rooms/room_beds/".$room_id, 'refresh');
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

            $this->data['bed_amount'] = [
                'name'      => 'bed_amount',
                'id'        => 'bed_amount',
                'value'     => $this->form_validation->set_value('bed_amount'),
                'class'     => 'form-control',
                "required" => ""
            ];

            $this->render("admin/rooms/beds_add_view");
        }
    }

    /**
     * @param null $room_id
     * @return array
     */
    public function room_beds_edit($bed_id)
    {
        $this->data["title"] = "Ётоқни тахрирлаш";
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                            ';
        $was_validated = "";
        $bed = $this->room_beds_model->get_bed($bed_id);
        $this->data["bed"] = $bed;
        $this->form_validation->set_rules('name', $this->lang->line('rooms_bed_name'), 'trim|required');
        $this->form_validation->set_rules('price', $this->lang->line('rooms_bed_price'), 'trim|required');

        if ($this->form_validation->run() === TRUE)
        {
            $this->room_beds_model->update($bed_id, $this->input->post());

            redirect("admin/rooms/room_beds/".$bed["room_id"], 'refresh');
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
                'name'      => 'name',
                'id'        => 'name',
                'value'     => $this->form_validation->set_value('name', $bed["name"]),
                'class'     => 'form-control',
                "required" => ""
            ];
            $this->data['price'] = [
                'name'      => 'price',
                'id'        => 'price',
                'value'     => $this->form_validation->set_value('price', $bed["price"]),
                'class'     => 'form-control',
                "required" => ""
            ];

            $this->render("admin/rooms/beds_edit_view");
        }
    }

    /**
     * @param null $room_id
     * @return array
     */
    public function room_beds_delete() {

        if ($this->input->is_ajax_request()) {
//            $id = $this->input->post("id");
            if(!is_null($this->input->post("confirm")))
            {
                $result = $this->room_beds_model->delete($this->input->post("id"));

                echo json_encode(array("deleted" => $result));
            } else {
                echo json_encode(false);
            }
        }
    }

    /**
     * @return array
     */
    public function ajax_patiens_list() {
        if($this->input->is_ajax_request()) {
            $keyword = $this->input->get("query");
            $this->load->model("patients_model");
            $patients_arr = $this->patients_model->search_patients($keyword);
            $patients = array();
            foreach ($patients_arr as $k => $p) {
                $patients[$k]["value"] = $p["last_name"]." ".$p["first_name"];
                $patients[$k]["data"] = $p["id"];
            }

            echo json_encode(array("suggestions" => $patients));
        }
    }

    public function ajax_assign_old_patient_to_room()
    {
        if($this->input->is_ajax_request()){

            $this->form_validation->set_rules('room_autocomplete', $this->lang->line("room_patient"), 'trim|required');
            $this->form_validation->set_rules('start_date', $this->lang->line("room_start_date"), 'required');
            $this->form_validation->set_rules('end_date', $this->lang->line("room_end_date"), 'required');

            if ($this->form_validation->run() === TRUE) {
                $result["errors"] = false;
                $patient_id = $this->input->post("patient_id");
                $start_date = date("Y-m-d", strtotime($this->input->post("start_date")));
                $end_date   = date("Y-m-d", strtotime($this->input->post("end_date")));
                $bed_id     = $this->input->post("bed_id");
                $payment_type = $this->input->post("payment_type");
                $total      = $this->input->post("total");
                $paid       = empty($this->input->post("paid")) ? 0 : $this->input->post("paid");

                $sdate = new DateTime($start_date);
                $edate = new DateTime($end_date);
                $days  = $edate->diff($sdate)->format('%a');

                $payment = array(
                    "patient_id" => $patient_id,
                    "paid" => $paid,
                    "debt" => ($total - $paid),
                    "debt_payoff_date" => null,
                    "discount" => 0,
                    "discount_type" => 0,
                    "discount_value" => 0,
                    "total" => $total,
                    "status" => 0,
                    "order_status" => 0,
                    "doctor_status" => 0,
                    "laboratory_status" => 0,
                    "uzi_status" => 0,
                    "room_status" => 1,
                    "partner_id" => 0,
                    "by_cash" => $paid,
                    "by_card" => 0,
                    "by_bank" => 0,
                );

                $payment_id = $this->patients_payments_model->add($payment);
                $patient_bed = array(
                    "patient_id"    => $patient_id,
                    "bed_id"        => $bed_id,
                    "start_date"    => $start_date,
                    "end_date"      => $end_date,
                    "payment_id"    => $payment_id,
                    "busy"          => 1,
                );

                $this->patient_room_model->add($patient_bed);
                $beds = $this->patient_room_model->get_patient_room($bed_id);
                $result["data"] = array(
                    "start_date"    => $this->input->post("start_date"),
                    "end_date"      => $this->input->post("end_date"),
                    "patient_name"  => $beds["last_name"]." ". $beds["first_name"],
                    "bed_id"        => $bed_id,
                );

                if(pos_print()) {
                    try {
                        $this->load->helper(array("lab_form"));
                        $pr = print_receipt($payment_id);
                        $this->load->library('ReceiptPrint');
                        $user = $this->ion_auth->user()->row();
                        $this->receiptprint->connect($this->config->item("pos_printer_name"));
                        $this->receiptprint->print_receipt($pr["patient_data"], $pr["payment_data"], false, false, false, false, $user, $pr["room_items"]);
                    } catch (Exception $e) {
                        log_message("error", "Error: Could not print. Message ".$e->getMessage());
                        $this->receiptprint->close_after_exception();
                    }
                }

                echo json_encode($result);
            } else {
                $result["errors"] = $this->form_validation->error_array();
                echo json_encode($result);
            }
        }
    }

    public function ajax_assign_new_patient_to_room()
    {
        if($this->input->is_ajax_request()){

            $this->form_validation->set_rules('last_name', $this->lang->line("room_last_name"), 'trim|required');
            $this->form_validation->set_rules('first_name', $this->lang->line("room_first_name"), 'trim|required');
            $this->form_validation->set_rules('start_date', $this->lang->line("room_start_date"), 'required');
            $this->form_validation->set_rules('end_date', $this->lang->line("room_end_date"), 'required');
            $this->form_validation->set_rules('phone', $this->lang->line("room_phone"), 'trim|required');

            if ($this->form_validation->run() === TRUE) {
                $result["errors"] = false;

                $prefix = "bem";
                $max_id = $this->patients_model->get_max_id();

                $this->load->helper("mix");
                $code = uniqe_code_genetrator($prefix, $max_id);
                $this->data["username"] = $code;

                $identity = $code;
                $password = "123!@";

                $additional_data = [
                    'first_name'    => $this->input->post('first_name'),
                    'last_name'     => $this->input->post('last_name'),
                    'surname'       => $this->input->post('surname'),
                    'dob'           => (empty($this->input->post('dob')) ? NULL:$this->input->post('dob')."-01-01"),
                    'gender'        => $this->input->post('gender'),
                    'address'       => $this->input->post('address'),
                    'region_id'     => $this->input->post('region_id'),
                    'city_id'       => $this->input->post('city_id'),
                    'phone'         => (empty($this->input->post('phone')) ? NULL:("+998".$this->input->post('phone'))),
                    'active'        => 0,
                ];

                $group = array('5'); // Sets user to bemor.

                $user_id = $this->ion_auth->register($identity, $password, "", $additional_data, $group);
                $patient_id = $this->patients_model->add(array("user_id" => $user_id));

                $start_date = date("Y-m-d", strtotime($this->input->post("start_date")));
                $end_date   = date("Y-m-d", strtotime($this->input->post("end_date")));
                $bed_id     = $this->input->post("bed_id");
                $payment_type = $this->input->post("payment_type");
                $total      = $this->input->post("total");
                $paid       = $this->input->post("paid");

                $this->load->model(array("patients_payments_model", "patient_room_model") );
                $payment = array(
                    "patient_id" => $patient_id,
                    "paid" => $paid,
                    "debt" => 0,
                    "debt_payoff_date" => null,
                    "discount_sum" => 0,
                    "discount_percent" => 0,
                    "total" => $total,
                    "status" => 0,
                    "order_status" => 0,
                    "doctor_status" => 0,
                    "laboratory_status" => 0,
                    "uzi_status" => 0,
                    "room_status" => 1,
                    "partner_id" => 0,
                    "payment_type" => $payment_type,
                );

                $payment_id = $this->patients_payments_model->add($payment);
                $patient_bed = array(
                    "patient_id"    => $patient_id,
                    "bed_id"        => $bed_id,
                    "start_date"    => $start_date,
                    "end_date"      => $end_date,
                    "payment_id"    => $payment_id,
                    "busy"          => 1,
                );

                $this->patient_room_model->add($patient_bed);
                $beds = $this->patient_room_model->get_patient_room($bed_id);
                $result["data"] = array(
                    "start_date"    => $this->input->post("start_date"),
                    "end_date"      => $this->input->post("end_date"),
                    "patient_name"  => $beds["last_name"]." ". $beds["first_name"],
                    "bed_id"        => $bed_id,
                    "total"         => $total,
                    "paid"          => $paid,
                );

                if(pos_print()) {
                    try {
                        $this->load->helper(array("lab_form"));
                        $pr = print_receipt($payment_id);
                        $this->load->library('ReceiptPrint');
                        $user = $this->ion_auth->user()->row();
                        $this->receiptprint->connect($this->config->item("pos_printer_name"));
						$this->receiptprint->print_receipt($pr["patient_data"], $pr["payment_data"], false, false, false, false, $user, $pr["room_items"]);
                    } catch (Exception $e) {
                        log_message("error", "Error: Could not print. Message ".$e->getMessage());
                        $this->receiptprint->close_after_exception();
                    }
                }

                echo json_encode($result);

            } else {
                $result["errors"] = $this->form_validation->error_array();
                echo json_encode($result);
            }
        }
    }

    public function ajax_show_patient_room()
    {
        if($this->input->is_ajax_request()) {
            $this->load->model(array("patient_room_model") );
            $bed_id             = $this->input->post("bed_id");
            $bed                = $this->patient_room_model->get_patient_room($bed_id);
            $bed["start_date"]  = date("d.m.Y", strtotime($bed["start_date"]));
            $bed["start_date_time"]= strtotime(date("d.m.Y", strtotime($bed["start_date"])));
            $bed["end_date"]    = date("d.m.Y", strtotime($bed["end_date"]));
            $bed["end_date_time"]= strtotime(date("d.m.Y", strtotime($bed["end_date"])));
            $bed["today"]       = time();

            echo json_encode($bed);
        }
    }

    public function ajax_update_patient_room()
    {
        if($this->input->is_ajax_request()){

            $this->form_validation->set_rules('patient_id', $this->lang->line("room_patient"), 'trim|required');
            $this->form_validation->set_rules('start_date', $this->lang->line("room_start_date"), 'required');
            $this->form_validation->set_rules('end_date', $this->lang->line("room_end_date"), 'required');

            if ($this->form_validation->run() === TRUE) {
                $result["errors"] = false;

                $debt_off       = !empty($this->input->post("debt_off")) ? $this->input->post("debt_off") : 0;
                $patient_id     = $this->input->post("patient_id");
                $start_date     = date("Y-m-d", strtotime($this->input->post("start_date")));
                $end_date       = date("Y-m-d", strtotime($this->input->post("end_date")));
                $bed_id         = $this->input->post("bed_id");
                $payment_type   = $this->input->post("payment_type");
                $total          = $this->input->post("total");
                $paid           = $this->input->post("paid") + $debt_off;
                $patient_room_id= $this->input->post("patient_room_id");
                $payment_id     = $this->input->post("payment_id");


                $this->load->model(array("patients_payments_model", "patient_room_model") );
                $payment = array(
                    "patient_id" => $patient_id,
                    "paid" => $paid,
                    "debt" => ($total-$paid),
                    "debt_payoff_date" => null,
                    "discount_sum" => 0,
                    "discount_percent" => 0,
                    "total" => $total,
                    "status" => 0,
                    "order_status" => 0,
                    "doctor_status" => 0,
                    "laboratory_status" => 0,
                    "uzi_status" => 0,
                    "room_status" => 1,
                    "partner_id" => 0,
                    "payment_type" => $payment_type,
                );

                $this->patients_payments_model->update($payment_id, $payment);
                $patient_bed = array(
                    "patient_id"    => $patient_id,
                    "bed_id"        => $bed_id,
                    "start_date"    => $start_date,
                    "end_date"      => $end_date,
                    "busy"          => 1,
                );

                $this->patient_room_model->update($patient_room_id, $patient_bed);
                $beds = $this->patient_room_model->get_patient_room($bed_id);
                $result["data"] = array(
                    "start_date"    => $this->input->post("start_date"),
                    "end_date"      => $this->input->post("end_date"),
                    "patient_name"  => $beds["last_name"]." ". $beds["first_name"],
                    "bed_id"        => $bed_id,
                    "paid"          => $paid,
                    "total"          => $total,
                );

                echo json_encode($result);
            } else {
                $result["errors"] = $this->form_validation->error_array();
                echo json_encode($result);
            }
        }
    }
}
