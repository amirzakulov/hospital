<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Services extends Doctor_Controller {

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

        $this->doctor_id = $this->session->userdata("employee_id");
    }


    /*********************************************************/
    public function ajax_selected_items(){

        $payment_id         = $this->input->post("payment_id");
        $selected_doctors   = $selected_uzi = $selected_services = $selected_labs = $selected_rooms = array();

        $payment 			= $this->patients_payments_model->get_patient_payment($payment_id);
        $patient 			= $this->patients_model->get_patient($payment["patient_id"]);

        if(!$payment["room_status"]) {
            $selected_doctors   = $this->patient_doctor_model->get_patient_doctor($payment_id);
            $selected_uzi       = $this->patient_uzi_model->get_patient_uzi($payment_id);
            $selected_services  = $this->patient_service_model->get_patient_service($payment_id);

            $laboratories      	= $this->patient_laboratories_model->get_patient_laboratories_details($payment_id, 1);
            $selected_labs     	= $this->formatting_selected_laboratories($laboratories);
            $selected_labs 		= (array) $selected_labs;
        } else {
            $selected_rooms     = $this->patient_room_model->get_bed_by_payment($payment_id);
        }

        echo json_encode(array("payment_id" => $payment_id, "doctors" => $selected_doctors, "labs" => $selected_labs, "uzis" => $selected_uzi, "services" => $selected_services, "rooms" => $selected_rooms, "payments" => $payment, "patient" => $patient));
    }

    private function formatting_selected_laboratories($selected_laboratories)
    {
        $selected_labs = array();
        foreach ($selected_laboratories as $category) {
            foreach ($category["sub"] as $lab_id => $labs) {
                if(isset($labs["sub"])) {
                    $status = 1;
                    foreach ($labs["sub"] as $sub) {
                        if($sub["status"] == 0) {
                            $status = 0;
                            continue;
                        }
                    }

                    $selected_labs[$lab_id]["name"] = $labs["name"];
                    $selected_labs[$lab_id]["price"] = $labs["price"];
                    $selected_labs[$lab_id]["lab_id"] = $lab_id;
                    $selected_labs[$lab_id]["status"] = $status;
                } else {
                    $selected_labs[$lab_id]["name"] = $labs["name"];
                    $selected_labs[$lab_id]["price"] = $labs["price"];
                    $selected_labs[$lab_id]["lab_id"] = $labs["lab_id"];
                    $selected_labs[$lab_id]["status"] = $labs["status"];
                }
            }
        }

        return $selected_labs;
    }

    public function ajax_add_selected_items() {
        if ($this->input->is_ajax_request()) {
            $payment_details = $this->input->post("selected_items");

//            $pdata = $this->patients_payments_model->get_patient_payment($payment_details["payment_id"]);


            $errors = false;
            if(!isset($payment_details["doctor_id"])
                && !isset($payment_details["labs_id"])
                && !isset($payment_details["uzis_id"])
                && !isset($payment_details["services_id"])
                && !isset($payment_details["beds_id"])) {

                $errors = true;
            } else {
                $payment_arr = array();
                if(isset($payment_details["doctor_id"]))        {$payment_arr["doctor_status"]      = 1;}
                elseif (!isset($payment_details["doctor_id"]))  {$payment_arr["doctor_status"]      = 0;}

                if(isset($payment_details["labs_id"]))          {$payment_arr["laboratory_status"]  = 1;}
                elseif (!isset($payment_details["labs_id"]))    {$payment_arr["laboratory_status"]  = 0;}

                if(isset($payment_details["uzis_id"]))          {$payment_arr["uzi_status"]         = 1;}
                elseif (!isset($payment_details["uzis_id"]))    {$payment_arr["uzi_status"]         = 0;}

                if(isset($payment_details["services_id"]))      {$payment_arr["service_status"]     = 1;}
                elseif (!isset($payment_details["services_id"])){$payment_arr["service_status"]     = 0;}

                if(isset($payment_details["beds_id"]))          {$payment_arr["room_status"]        = 1;}
                elseif (!isset($payment_details["beds_id"]))    {$payment_arr["room_status"]        = 0;}

                $total 				= $payment_details["total"];
                $discount_type		= $payment_details["discount_type"];
                $discount_value 	= $payment_details["discount_value"];
                $discount 			= $this->get_discount($discount_type, $discount_value, $total);
                $patient_id         = $payment_details["patient_id"];

                //1. add patients_payments and patients_payments_details tables

                ////////////////////////////////////////////////////////////////////////

                $payment_arr["room_status"]     = 0;
                $payment_arr["order_status"]    = 0;
                $payment_arr["patient_id"]      = $patient_id;
                $payment_arr["discount_type"]   = $discount_type;
                $payment_arr["discount_value"]  = $discount_value;
                $payment_arr["discount"]	    = $discount;
                $payment_arr["total"]           = $total;
                $payment_arr["status"]          = 2;
                $payment_arr['partner_id']      = $payment_details['partner_id'];
                $payment_arr['doctor_id']       = $this->doctor_id;

                $payment_id = $this->patients_payments_model->add($payment_arr);

                $payment_details_arr = array(
                    'payment_id'      	=> $payment_id,
                    "paid"              => $payment_details["paid"],
                    'by_cash'      		=> $payment_details["by_cash"],
                    'by_card'      		=> $payment_details["by_card"],
                    'by_bank'      		=> $payment_details["by_bank"],
                );
                $this->patients_payments_details_model->add($payment_details_arr);
                ////////////////////////////////////////////////////////////////////////

                //2. doctorlar tanlangan bulsa ularni uchiradiganini uchirib, qoladiganini qoldiramiz
                $selected_doctors = false;
                if(isset($payment_details["doctor_id"])) {
                    //2.1 patient_doctor payment_id buyicha hamma doktorlarni uchirib tashlaymiz
                    $this->doctors_actions($payment_details["doctor_id"], $payment_id, $patient_id);
                    $selected_doctors = true;
                }

                //3. laboratoriyalar tanlangan bulsa ularni uchiradiganini uchirib, qoladiganini qoldiramiz
                $selected_laboratories = false;
                if(isset($payment_details["labs_id"])) {
                    //3.1 patient_laboratories tableda payment_id buyicha hamma laboratoriyalarni uchirib tashlaymiz
                    $this->laboratories_actions($payment_details["labs_id"], $payment_id, $patient_id);
                    $selected_laboratories = true;
                }

                //4. uzilar tanlangan bulsa ularni uchiradiganini uchirib, qoladiganini qoldiramiz
                $selected_uzis = false;
                if(isset($payment_details["uzis_id"])) {
                    //4.1 patient_uzi tableda payment_id buyicha hamma uzilarni uchirib tashlaymiz
                    $this->uzis_actions($payment_details["uzis_id"], $payment_id, $patient_id);
                    $selected_uzis = true;
                }
                //5. servicelar tanlangan bulsa ularni uchiradiganini uchirib, qoladiganini qoldiramiz
                $selected_services = false;
                if(isset($payment_details["services_id"])) {
                    //5.1 patient_service tableda payment_id buyicha hamma uzilarni uchirib tashlaymiz
                    $this->services_actions($payment_details["services_id"], $payment_id, $patient_id);
                    $selected_services = true;
                }

                $selected_rooms = false;
                if(isset($payment_details["beds_id"])) {
                    $this->patient_room_model->update_by_payment_id(
                        $payment_id,
                        array(
                            "bed_id"        => $payment_details["beds_id"][0],
                            "start_date"    => date_formating(strtotime($payment_details["room_start_date"]), "db_datetime"),
                            "end_date"      => date("Y-m-d 11:00:00", strtotime($payment_details["room_end_date"]))
                        )
                    );
                    $selected_rooms = true;
                }

                if(!$selected_doctors && !$selected_laboratories && !$selected_uzis && !$selected_services && !$selected_rooms) {
                    $errors = true;
                }
            }

            echo json_encode(array("errors" => $errors, 'payment_id' => $payment_id));
        }
    }

    /**
     * Tanlangan yoki tanlanmagan doctorlar ustida amallar
     *
     * @param $doctors_id array
     * @param $payment_id int
     * @param $patient_id int
     * @return mixed
     */
    private function doctors_actions($doctors_id, $payment_id, $patient_id) {

        //patient_doctor tabledan keraksiz doktorlarni uchirib tashlaymiz
        //2.2 payment id buyicha bazada bor doctorlarni tekshiramiz
        $existed_doctors = array();
        foreach ($this->patient_doctor_model->get_patient_doctor($payment_id) as $e_doc) {
            $existed_doctors[] = $e_doc["doctor_id"];
        }

        //2.3 agar doctorlar bulsa, uchirilishi mumkin bulmaganlarini va bazaga kiritilishi kerak bulganlarini ajratib olamiz.
        $doctors_id_for_nodelete    = array();
        $doctors_id_for_insert      = array();
        if(count($existed_doctors) > 0) {
            foreach ($doctors_id as $doctor_id) {
                if(in_array($doctor_id, $existed_doctors)) {
                    $doctors_id_for_nodelete[] = $doctor_id;
                } else {
                    $doctors_id_for_insert[] = $doctor_id;
                }
            }
        } else {
            $doctors_id_for_insert = $doctors_id;
        }

        if(count($doctors_id_for_nodelete) > 0) {
            $this->patient_doctor_model->delete_not_selected($payment_id, $doctors_id_for_nodelete);
        } else {
            $this->patient_doctor_model->delete_by_paymentId($payment_id);
        }

        //2.4 doctorlarni bazaga kiritamiz
        if(count($doctors_id_for_insert) > 0) {
            foreach ($doctors_id_for_insert as $doc_id) {
                $arr = array(
                    "patient_id" => $patient_id,
                    "payment_id" => $payment_id,
                    "doctor_id" => $doc_id,
                    "status" => 0
                );

                $this->patient_doctor_model->add($arr);
            }
        }
    }

    /**
     * Tanlangan yoki tanlanmagan laboratoriyalar ustida amallar
     *
     * @param $laboratories_id array
     * @param $payment_id int
     * @param $patient_id int
     * @return mixed
     */
    private function laboratories_actions($laboratories_id, $payment_id, $patient_id) {

        //patient_$laboratories tabledan keraksiz laboratoriyalarni uchirib tashlaymiz
        //3.2 payment id buyicha bazada bor laboratoriyalarni tekshiramiz
        $existed_laboratories = array();
        foreach ($this->patient_laboratories_model->get_laboratories_by_payment($payment_id) as $laboratory) {
            $existed_laboratories[] = $laboratory["lab_id"];
        }

        //3.3 agar laboratoriyalar bulsa, uchirilishi mumkin bulmaganlarini va bazaga kiritilishi kerak bulganlarini ajratib olamiz.
        $laboratories_id_for_nodelete    = array();
        $laboratories_id_for_insert      = array();
        if(count($existed_laboratories) > 0) {
            foreach ($laboratories_id as $lab_id) {
                if(in_array($lab_id, $existed_laboratories)) {
                    $laboratories_id_for_nodelete[] = $lab_id;
                } else {
                    $laboratories_id_for_insert[] = $lab_id;
                }
            }
        } else {
            $laboratories_id_for_insert = $laboratories_id;
        }

        if(count($laboratories_id_for_nodelete) > 0) {
            $this->patient_laboratories_model->delete_not_selected($payment_id, $laboratories_id_for_nodelete);
        } else {
            $this->patient_laboratories_model->delete_by_paymentId($payment_id);
        }

        //3.4 laboratoriyalarni bazaga kiritamiz
        if(count($laboratories_id_for_insert) > 0) {
            foreach ($laboratories_id_for_insert as $labb_id) {
                $lab_value = $this->laboratory_model->get_laboratory($labb_id);
                $lab_arr = array(
                    "patient_id"    => $patient_id,
                    "lab_id"        => $labb_id,
                    "payment_id"    => $payment_id,
                    "result"        => $lab_value["default_value"],
                    "status"        => 0,
                    "recommendation"=> 0,
                    "is_parent"     => 1,
                );

                $this->patient_laboratories_model->add($lab_arr);

                $laboratory = $this->laboratory_model->sub_categories($labb_id);
                if(count($laboratory) > 0) {
                    foreach ($laboratory as $slab) {
                        $lab_arr = array(
                            "patient_id"    => $patient_id,
                            "lab_id"        => $slab["id"],
                            "payment_id"    => $payment_id,
                            "result"        => $lab_value["default_value"],
                            "status"        => 0,
                            "recommendation"=> 0,
                            "is_parent"     => 0,
                            "parent_id"     => $slab["parent_id"],
                        );
                        $this->patient_laboratories_model->add($lab_arr);
                    }
                }
            }
        }
    }

    /**
     * Tanlangan yoki tanlanmagan uzilar ustida amallar
     *
     * @param $uzis_id array
     * @return mixed
     */
    private function uzis_actions($uzis_id, $payment_id, $patient_id) {

        //patient_uzis tabledan keraksiz uzilarni uchirib tashlaymiz
        //3.2 payment_id buyicha bazada bor uzilarni tekshiramiz
        $existed_uzis = array();
        foreach ($this->patient_uzi_model->get_patient_uzi($payment_id) as $e_uzi) {
            $existed_uzis[] = $e_uzi["uzi_id"];
        }

        //3.3 agar uzilar bulsa, uchirilishi mumkin bulmaganlarini va bazaga kiritilishi kerak bulganlarini ajratib olamiz.
        $uzis_id_for_nodelete    = array();
        $uzis_id_for_insert      = array();
        if(count($existed_uzis) > 0) {
            foreach ($uzis_id as $uzi_id) {
                if(in_array($uzi_id, $existed_uzis)) {
                    $uzis_id_for_nodelete[] = $uzi_id;
                } else {
                    $uzis_id_for_insert[] = $uzi_id;
                }
            }
        } else {
            $uzis_id_for_insert = $uzis_id;
        }

        if(count($uzis_id_for_nodelete) > 0) {
            $this->patient_uzi_model->delete_not_selected($payment_id, $uzis_id_for_nodelete);
        } else {
            $this->patient_uzi_model->delete_by_paymentId($payment_id);
        }

        //3.4 laboratoriyalarni bazaga kiritamiz
        if(count($uzis_id_for_insert) > 0) {
            foreach ($uzis_id_for_insert as $uzii_id) {
                $arr = array(
                    "patient_id" => $patient_id,
                    "payment_id" => $payment_id,
                    "uzi_id" => $uzii_id,
                    "status" => 0
                );

                $this->patient_uzi_model->add($arr);
            }
        }
    }

    /**
     * Tanlangan yoki tanlanmagan uzilar ustida amallar
     *
     * @param $services_id array
     * @return mixed
     */
    private function services_actions($services_id, $payment_id, $patient_id) {

        //patient_services tabledan keraksiz uzilarni uchirib tashlaymiz
        //3.2 payment_id buyicha bazada bor servicelarni tekshiramiz
        $existed_services = array();
        foreach ($this->patient_service_model->get_patient_service($payment_id) as $e_service) {
            $existed_services[] = $e_service["service_id"];
        }

        //3.3 agar servicelar bulsa, uchirilishi mumkin bulmaganlarini va bazaga kiritilishi kerak bulganlarini ajratib olamiz.
        $services_id_for_nodelete    = array();
        $services_id_for_insert      = array();
        if(count($existed_services) > 0) {
            foreach ($services_id as $service_id) {
                if(in_array($service_id, $existed_services)) {
                    $services_id_for_nodelete[] = $service_id;
                } else {
                    $services_id_for_insert[] = $service_id;
                }
            }
        } else {
            $services_id_for_insert = $services_id;
        }

        if(count($services_id_for_nodelete) > 0) {
            $this->patient_service_model->delete_not_selected($payment_id, $services_id_for_nodelete);
        } else {
            $this->patient_service_model->delete_by_paymentId($payment_id);
        }

        //3.4 laboratoriyalarni bazaga kiritamiz
        if(count($services_id_for_insert) > 0) {
            foreach ($services_id_for_insert as $service_id) {
                $arr = array(
                    "patient_id" => $patient_id,
                    "payment_id" => $payment_id,
                    "service_id" => $service_id,
                    "status" => 0
                );

                $this->patient_service_model->add($arr);
            }
        }
    }

    private function get_discount($discount_type, $discount_value, $total) {
        $discount = $discount_value;
        if($discount_type == 2) $discount = ($discount_value / 100) * $total;

        return $discount;
    }
    /*********************************************************/







}
