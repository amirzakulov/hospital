<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patients_payments_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

	public function get_payments($start_date = null, $end_date = null, $limit = 0) {
		$start_date = is_null($start_date) ? date("Y-m-01") : $start_date;
		$end_date = is_null($end_date) ? date("Y-m-t") : $end_date;

		$qlimit = "";
		if($limit > 0) {
			$qlimit = " LIMIT ".$limit;;
		}

    	$query = $this->db->query("
							SELECT pp.id, pp.patient_id, ppd.paid, (pp.total - (ppd.paid + pp.discount)) as debt, pp.total, pp.discount, ppd.by_cash, ppd.by_card, ppd.by_bank, pp.partner_id, 
							pt.last_name as partner_last_name, pt.first_name as partner_first_name, pp.doctor_id, pp.created_date as payment_date,
							u.last_name, u.first_name, u.surname, u.dob, u.username, u.phone, r.name as region_name, c.name as city_name, u.address,
							pp.doctor_status, pp.laboratory_status, pp.uzi_status, pp.service_status, pp.room_status
							FROM patients_payments pp
							LEFT JOIN (
										SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
										FROM patients_payments_details ppd 
										GROUP BY ppd.payment_id
									) ppd ON ppd.payment_id = pp.id
							LEFT JOIN patients p ON p.id = pp.patient_id
							LEFT JOIN users u ON u.id = p.user_id
							LEFT JOIN regions r ON r.id = u.region_id
							LEFT JOIN cities c ON c.id = u.city_id
							LEFT JOIN partners pt ON pt.id = pp.partner_id
							WHERE DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."'
							ORDER BY pp.created_date DESC".$qlimit
						);

    	return $query->result_array();
    }

    public function get_patient_payment($payment_id) {

		$query = $this->db->query("
							SELECT pp.*, ppd.paid, ppd.by_cash, ppd.by_card, ppd.by_bank, (pp.total - (ppd.paid + pp.discount)) as debt, pp.discount, 
							u.last_name, u.first_name, u.surname, u.username as user_code, u.address,
							pt.last_name as partner_last_name, pt.first_name as partner_first_name, 
							d.last_name as sender_doctor_last_name, d.first_name as sender_doctor_first_name 
							FROM patients_payments pp
							LEFT JOIN (
										SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
										FROM patients_payments_details ppd 
										GROUP BY ppd.payment_id
									) ppd ON ppd.payment_id = pp.id
							LEFT JOIN patients p ON p.id = pp.patient_id
							LEFT JOIN users u ON u.id = p.user_id
							LEFT JOIN partners pt ON pt.id = pp.partner_id
							LEFT JOIN employees e ON e.id = pp.doctor_id
							LEFT JOIN users d ON d.id = e.user_id
							WHERE pp.id = '".$payment_id."'
							ORDER BY pp.created_date DESC
    					");


        return $query->row_array();
    }

	public function get_patient_payment_details($payment_id) {
		//Step 1
		$this->db->select("pp.*, u.last_name, u.first_name, u.surname, u.username as user_code");
		$this->db->join("patients p", "p.id = pp.patient_id", "left");
		$this->db->join("users u", "u.id = p.user_id", "left");
		$this->db->where("pp.id", $payment_id);

		$query = $this->db->get("patients_payments pp");
		$payments = $query->row_array();

		//Step 2
		$query2 = $this->db->get_where("patients_payments_details ppd", array("ppd.payment_id" => $payment_id));

		$payments["details"] = $query2->result_array();

		return $payments;


	}

    public function get_patient_payment_by_patient($patient_id, $type = null) {
        $this->db->order_by("pp.created_date", "desc");
        //var $type: null = barchasi, 1-doctor, 2-laboratory, 3-uzi
        if ($type == 1) { $this->db->where("pp.doctor_status >", 0); }
        elseif ($type == 2) { $this->db->where("pp.laboratory_status >", 0); }
        elseif ($type == 3) { $this->db->where("pp.uzi_status >", 0); }
        elseif ($type == 4) { $this->db->where("pp.room_status >", 0); }

        $this->db->where(array("pp.patient_id" => $patient_id));
        $query  = $this->db->get("patients_payments pp");
        $result = $query->result_array();

        return  $result;
    }

    public function add($arr) {
        $this->db->insert("patients_payments", $arr);
        return $this->db->insert_id();
    }

    public function update($id, $arr)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("patients_payments", $arr);

        return $result;
    }

    /**
     * @param $id = payment_id
     * @return mixed
     */
    public function delete($id) {

        //delete doctors
        $this->db->where("payment_id", $id);
        $this->db->delete("patient_doctor");

        //delete labs
        $this->db->where("payment_id", $id);
        $this->db->delete("patient_laboratories");

        //delete uzis
        $this->db->where("payment_id", $id);
        $this->db->delete("patient_uzi");

        //delete services
        $this->db->where("payment_id", $id);
        $this->db->delete("patient_service");

        //delete services
        $this->db->where("payment_id", $id);
        $this->db->delete("patient_room");

        //delete payments_debt_discount table data
		$this->db->where("payment_id", $id);
		$this->db->delete("payments_debt_discount");

		//delete patients_payments_details
        $this->db->where("payment_id", $id);
        $this->db->delete("patients_payments_details");

        $this->db->where("id", $id);
        return $this->db->delete("patients_payments");
    }

    public function deleteAll($patient_id)
    {
        $this->db->where("patient_id", $patient_id);
        return $this->db->delete("patients_payments");
    }

    /**
     * Bemorning tulov qilgan hamma itemlari tugatilganligini tekshiradi
     * masalan: bemor doctor kurigi va laboratoriyaga tulov qilgan bulsa ko'rig va laboratoriyalarning barchasining status lari 1 bulgan bulsa
     * True qaytaradi, aks qolda false
     *
     * @param $payment_id
     * @return boolean
     */
    public function check_payment_status($payment_id) {
        //'notstarted', 'inprocess', 'completed'
        $this->db->select("pp.doctor_status, pp.laboratory_status, pp.uzi_status, pp.service_status");
        $this->db->where("id", $payment_id);
        $query = $this->db->get("patients_payments pp");
        $row = $query->row_array();

        if(max($row) == 1){
            return 'notstarted';
        } elseif (in_array(1, $row) || in_array(3, $row)) {
            return 'inprocess';
        } elseif(!in_array(1, $row)) {
            return 'completed';
        }

    }


    /**
     * Bugun doctor kurigiga kelgan bemorlar ruyhati
     * @return array
     */
    public function get_doctor_patients($doctor_id)
    {
        $this->db->select(
            "pp.*, ppd.paid, r.name as region_name, c.name as city_name, 
            pd.id as patient_doctor_id, pd.doctor_id, u.id as user_id, u.last_name, u.first_name, u.surname, u.dob, u.address, u.phone, u.email, 
            u.username, u.active, u.occupation, u.region_id, u.city_id, u.gender, u.description
        ");

        $this->db->join("patient_doctor pd", "pd.payment_id = pp.id", "left");
        $this->db->join("patients p", "p.id = pp.patient_id", "left");
        $this->db->join("users u", "u.id = p.user_id", "left");
        $this->db->join("regions r", "r.id = u.region_id", "left");
        $this->db->join("cities c", "c.id = u.city_id", "left");
        $this->db->join("(
            SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
            FROM patients_payments_details ppd
            GROUP BY ppd.payment_id
        ) ppd", "ppd.payment_id = pp.id", "left");
        $this->db->where(array("pd.doctor_id" => $doctor_id));
        $this->db->where_in(array("pp.doctor_status", array(1,2,3)));

        $query = $this->db->get("patients_payments pp");
        $result = $query->result_array();

        $patients = array("completed" => array(), "incomplete" => array());
        foreach ($result as $patient) {
            if($patient["doctor_status"] == 2) {
                if(date("Y-m-d", strtotime($patient["updated_date"])) == date("Y-m-d")) {
                    $patients["completed"][] = $patient;
                }
            } else {
                $patients["incomplete"][] = $patient;
            }
        }

        return $patients;
    }

    /**
     * Bugun laboratoriya analiz topshirib bulganlar ruyhati
     * @return array
     */
    public function get_laboratory_patients() {
        $this->db->select(
            "pp.*, ppd.paid, r.name as region_name, c.name as city_name, pp.partner_id,
            u.id as user_id, u.last_name, u.first_name, u.surname, u.dob, u.address, u.phone, u.email, u.username, u.active, u.occupation, 
            u.region_id, u.city_id, u.gender, u.description
        ");

        $this->db->join("patients p", "p.id = pp.patient_id", "left");
        $this->db->join("users u", "u.id = p.user_id", "left");
        $this->db->join("regions r", "r.id = u.region_id", "left");
        $this->db->join("cities c", "c.id = u.city_id", "left");
        $this->db->join("(
            SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
            FROM patients_payments_details ppd
            GROUP BY ppd.payment_id
        ) ppd", "ppd.payment_id = pp.id", "left");
        $this->db->where(array("date(pp.updated_date)" => date("Y-m-d"), "pp.laboratory_status" => 2));
        $this->db->or_where_in("pp.laboratory_status", array(1,3,4));
        $this->db->where(array("pp.status !=" => 2));
        $query = $this->db->get("patients_payments pp");
        $result = $query->result_array();

        $patients = array("completed" => array(), "incomplete" => array());
        foreach ($result as $patient) {
            if($patient["laboratory_status"] == 2) {
                $patients["completed"][] = $patient;
            } else {
                $patients["incomplete"][] = $patient;
            }
        }

        return $patients;
    }

    /**
     * Bugun uzi topshirib bulganlar ruyhati
     * @return array
     */
    public function get_uzi_patients() {
        $this->db->select(
            "pp.*, ppd.paid, r.name as region_name, c.name as city_name, pp.partner_id,
            u.id as user_id, u.last_name, u.first_name, u.surname, u.dob, u.address, u.phone, u.email, u.username, u.active, u.occupation, 
            u.region_id, u.city_id, u.gender, u.description
        ");
		$this->db->order_by("pp.created_date", "desc");
        $this->db->join("patients p", "p.id = pp.patient_id", "left");
        $this->db->join("users u", "u.id = p.user_id", "left");
        $this->db->join("regions r", "r.id = u.region_id", "left");
        $this->db->join("cities c", "c.id = u.city_id", "left");
        $this->db->join("(
            SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
            FROM patients_payments_details ppd
            GROUP BY ppd.payment_id
        ) ppd", "ppd.payment_id = pp.id", "left");
        $this->db->where(array("date(pp.updated_date)" => date("Y-m-d"), "pp.uzi_status" => 2));
        $this->db->or_where_in("pp.uzi_status", array(1,3));
        $query = $this->db->get("patients_payments pp");
        $result = $query->result_array();

        $patients = array("completed" => array(), "incomplete" => array());
        foreach ($result as $patient) {
            if($patient["uzi_status"] == 2) {
                $patients["completed"][] = $patient;
            } else {
                $patients["incomplete"][] = $patient;
            }
        }

        return $patients;
    }

    /**
     * Doctorning kurigiga kelgan bemorlar ruyhati, kun buyicha
     * @return array
     */
    public function get_doctor_patients_by_date($doctor_id, $start_date, $end_date) {
        $this->db->select(
            "pp.*, ppd.paid, u.id as user_id, u.last_name, u.first_name, u.surname, u.dob, u.address, u.phone, u.email, u.username, 
            u.active, u.occupation, u.region_id, u.city_id, u.gender, u.description
        ");

        $this->db->join("patient_doctor pd", "pd.payment_id = pp.id", "left");
        $this->db->join("patients p", "p.id = pp.patient_id", "left");
        $this->db->join("users u", "u.id = p.user_id", "left");
        $this->db->join("(
            SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
            FROM patients_payments_details ppd
            GROUP BY ppd.payment_id
        ) ppd", "ppd.payment_id = pp.id", "left");
        $this->db->where(array("pp.doctor_status" => 2, "pd.doctor_id" => $doctor_id));
        $this->db->where(array("date(pp.created_date) >=" => $start_date, "date(pp.created_date) <=" => $end_date));
        $query = $this->db->get("patients_payments pp");
        $patients = $query->result_array();

        return $patients;
    }

    /**
     * kun buyicha tushum
     * @return int total_sum
     */
    public function get_total_income_by_date($start_date = null, $end_date = null, $payment_list = false) {
        $start_date = is_null($start_date) ? date("Y-m-d") : $start_date;
        $end_date = is_null($end_date) ? date("Y-m-d") : $end_date;

//        $select_query = $payment_list ? " SELECT pp.*, ppd.*, u.last_name, u.first_name " : " SELECT SUM(ppd.paid) as paid ";

        $query = $this->db->query("
                        SELECT pp.*, ppd.*, u.last_name, u.first_name 
						FROM `patients_payments` `pp` 
						LEFT JOIN (
							SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
							FROM patients_payments_details ppd 
							GROUP BY ppd.payment_id
						) ppd ON ppd.payment_id = pp.id
						LEFT JOIN `patients` `p` ON `p`.`id`=`pp`.`patient_id` 
						LEFT JOIN `users` `u` ON `u`.`id`=`p`.`user_id` 
						WHERE date(pp.created_date) >= '".$start_date."' AND date(pp.created_date) <= '".$end_date."' 
						ORDER BY `pp`.`created_date`
        ");

//        return $payment_list ? $query->result_array() : $query->row_array()["paid"];
        return $query->result_array();
    }

    public function get_laboratory_payments($start_date = null, $end_date = null) {
        $start_date = is_null($start_date) ? date("Y-m-d"):date("Y-m-d", strtotime($start_date));
        $end_date   = is_null($end_date) ? date("Y-m-d"):date("Y-m-d", strtotime($end_date));

        $query = $this->db->query("SELECT pp.id as payment_id, u.last_name, u.first_name, pp.patient_id, pp.laboratory_status, pp.partner_id, 
                                    pl.lab_id, pl.is_parent, SUM(l.price) as total, SUM(l.price_partner) as total_partner, (SUM(l.price) - SUM(l.price_partner)) as clinic, date(pp.created_date) as created_date
                                    FROM `patients_payments` pp
                                    LEFT JOIN patients p ON p.id = pp.patient_id
                                    LEFT JOIN users u ON u.id = p.user_id
                                    LEFT JOIN patient_laboratories pl ON (pl.payment_id = pp.id AND pl.is_parent = 1)
                                    LEFT JOIN laboratories l ON l.id = pl.lab_id
                                    WHERE pp.laboratory_status <> 0 AND date(pp.created_date) >= '".$start_date."' AND date(pp.created_date) <= '".$end_date."'
                                    GROUP BY pp.id
                                    ORDER BY pp.created_date ASC");

        return $query->result_array();

    }

    /********************
     * Berilgan muddat oraligida ruyxatga kiritilgan va tulanishi kerak bulgan pullar
     * */
    public function debt($start_date = null, $end_date = null)
    {
        if(is_null($start_date) && is_null($end_date)) {
            $start_date = $end_date = date("Y-m-d");
        }

        //SELECT (CASE WHEN SUM(pp.total - (ppd.paid + SUM(pp.discount))) IS NULL THEN 0 ELSE SUM(pp.total - (ppd.paid + SUM(pp.discount))) END) as debt
		$query = $this->db->query("
            SELECT SUM(pp.total) as total, SUM(ppd.paid) as paid, SUM(pp.discount) as discount, 
            (CASE WHEN SUM(pp.total - (ppd.paid + pp.discount)) IS NULL THEN 0 ELSE SUM(pp.total - (ppd.paid + pp.discount)) END) as debt
            FROM patients_payments pp
            LEFT JOIN (
							SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
							FROM patients_payments_details ppd
							GROUP BY ppd.payment_id
						) ppd ON ppd.payment_id = pp.id
            WHERE date(pp.created_date) >= '".$start_date."' AND date(pp.created_date) <= '".$end_date."' AND pp.status <> 2
        ");

        return $query->row_array();
    }

    /************************************
     * Malum sanalar orasida tulangan qarzlar
	 * type = 1 - qarz, 2 - chegirma
     * */
	public function get_debt_off_by_date($start_date = null, $end_date = null)
	{
		if(is_null($start_date) && is_null($end_date)) {
			$start_date = $end_date = date("Y-m-d");
		}

		$query = $this->db->query("
					SELECT pdd.*, u2.last_name, u2.first_name, u.last_name as doctor_last_name, u.first_name as doctor_first_name, st.name as service_name, st.name_ru as service_name_ru
					FROM payments_debt_discount pdd
					LEFT JOIN employees e ON e.id = pdd.doctor_id
					LEFT JOIN users u ON u.id = e.user_id
					LEFT JOIN patients_payments pp ON pp.id = pdd.payment_id
					LEFT JOIN patients p ON p.id = pp.patient_id
					LEFT JOIN users u2 ON u2.id = p.user_id
					LEFT JOIN service_types st ON st.id = pdd.service_type
					WHERE (DATE(pdd.created_date) >= '".$start_date."' AND DATE(pdd.created_date) <= '".$end_date."') AND DATE(pp.created_date) < DATE(pdd.created_date) AND pdd.type = 1 AND pdd.debt_off_type = 1
					ORDER BY pdd.created_date DESC
				");

		return $query->result_array();
	}

    /********************
     * Berilgan muddat oraligida ruyxatga kiritilgan va tulanishi kerak bulgan pullar
     * */
    public function total_payment($start_date = null, $end_date = null)
    {
        if(is_null($start_date) && is_null($end_date)) {
            $start_date = $end_date = date("Y-m-d");
        }

        $query = $this->db->query("
            SELECT SUM(pp.total) as total, SUM(pp.discount) as discount
            FROM patients_payments pp
            WHERE date(pp.created_date) >= '".$start_date."' AND date(pp.created_date) <= '".$end_date."' AND pp.status <> 2
        ");

        return $query->row_array();
    }


    /********************
     * Berilgan muddat oraligida realniy tulangan pullar
     * */
    public function real_payment($start_date = null, $end_date = null)
    {
        if(is_null($start_date) && is_null($end_date)) {
            $start_date = $end_date = date("Y-m-d");
        }

        $query = $this->db->query("
            SELECT SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank, ppd.created_date
            FROM patients_payments_details ppd 
            LEFT JOIN patients_payments pp ON pp.id = ppd.payment_id
            WHERE date(ppd.created_date) >= '".$start_date."' AND date(ppd.created_date) <= '".$end_date."'  AND pp.`status` <> 2
        ");

        return $query->row_array();
    }

	/********************
	 * malum kunlar oraligidagi tulovlarni servicelar buyicha xisoboti, yani qaysi servicedan qancha pul tushdi
	 * */
	public function income_by_items($start_date = null, $end_date = null)
	{
		if(is_null($start_date) && is_null($end_date)) {
			$start_date = $end_date = date("Y-m-d");
		}

		$query = $this->db->query("
			SELECT SUM(p.paid) as paid, SUM(p.total) as total, SUM(p.by_cash) as by_cash, SUM(p.by_card) as by_card, SUM(p.by_bank) as by_bank, 
			(CASE WHEN SUM(p.doctor_price) IS NULL THEN 0 ELSE SUM(p.doctor_price) END) as doctor, 
			(CASE WHEN SUM(p.lab_price) IS NULL THEN 0 ELSE SUM(p.lab_price) END) as lab, 
			(CASE WHEN SUM(p.uzi_price) IS NULL THEN 0 ELSE SUM(p.uzi_price) END) as uzi, 
			(CASE WHEN SUM(p.room_price) IS NULL THEN 0 ELSE SUM(p.room_price) END) as room, 
			(CASE WHEN SUM(p.service_price) IS NULL THEN 0 ELSE SUM(p.service_price) END) as service
			FROM (
				SELECT pp.id, pp.patient_id, pp.total, ppd.paid, ppd.by_cash, ppd.by_card, ppd.by_bank, pp.created_date, 
				SUM(pd.price) as doctor_price, pl.price as lab_price, pu.price as uzi_price, pr.price as room_price, ps.price as service_price
				FROM `patients_payments` pp
				LEFT JOIN (
                    SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
                    FROM patients_payments_details ppd 
                    GROUP BY ppd.payment_id
                ) ppd ON ppd.payment_id = pp.id
				LEFT JOIN (
					SELECT pd.payment_id, (d.price - (CASE WHEN pdd.amount IS NULL THEN 0 ELSE pdd.amount END)) as price
					FROM patient_doctor pd
					LEFT JOIN doctors_types_link d ON d.employee_id = pd.doctor_id
					LEFT JOIN (
							SELECT pdd.payment_id, pdd.doctor_id, SUM(pdd.amount) as amount
							FROM `payments_debt_discount` pdd
							WHERE pdd.service_type = 1
							GROUP BY pdd.type, pdd.payment_id
					) as pdd ON pdd.payment_id = pd.payment_id AND pdd.doctor_id = pd.doctor_id
                    WHERE date(pd.created_date) >= '".$start_date."' AND date(pd.created_date) <= '".$end_date."'
				) AS pd ON pd.payment_id = pp.id
			
				LEFT JOIN (
					SELECT l.*, (l.Lprice - (CASE WHEN SUM(pdd.amount) IS NULL THEN 0 ELSE SUM(pdd.amount) END)) as price
					FROM (
                        SELECT pl.payment_id, SUM(l.price) as Lprice
                        FROM patient_laboratories pl
                        LEFT JOIN laboratories l ON l.id = pl.lab_id
                        WHERE pl.is_parent = 1 AND date(pl.created_date) >= '".$start_date."' AND date(pl.created_date) <= '".$end_date."'
                        GROUP BY pl.payment_id
					) l
					LEFT JOIN patients_payments pp on pp.id = l.payment_id
					LEFT JOIN payments_debt_discount pdd ON pdd.payment_id = l.payment_id AND pdd.service_type = 2
					GROUP BY l.payment_id 
				) AS pl ON pl.payment_id = pp.id
			
				LEFT JOIN (
					SELECT u.payment_id, (u.price - (CASE WHEN SUM(pdd.amount) IS NULL THEN 0 ELSE SUM(pdd.amount) END)) as price
                    FROM (
                            SELECT pu.payment_id, SUM(u.price) as price
                            FROM patient_uzi pu
                            LEFT JOIN uzi u ON u.id = pu.uzi_id
                            WHERE date(pu.created_date) >= '".$start_date."' AND date(pu.created_date) <= '".$end_date."'
                            GROUP BY pu.payment_id
                    ) u
                    LEFT JOIN payments_debt_discount pdd ON pdd.payment_id = u.payment_id AND pdd.service_type = 3
                    GROUP BY u.payment_id
				) AS pu ON pu.payment_id = pp.id
			
			    LEFT JOIN (
					SELECT s.payment_id, (s.price - (CASE WHEN SUM(pdd.amount) IS NULL THEN 0 ELSE SUM(pdd.amount) END)) as price
					FROM (
									SELECT ps.payment_id, SUM(s.price * ps.count) as price
									FROM patient_service ps
									LEFT JOIN services s ON s.id = ps.service_id
									WHERE date(ps.created_date) >= '".$start_date."' AND date(ps.created_date) <= '".$end_date."'
									GROUP BY ps.payment_id
					) s
					LEFT JOIN payments_debt_discount pdd ON pdd.payment_id = s.payment_id AND pdd.service_type = 4
					GROUP BY s.payment_id
				) AS ps ON ps.payment_id = pp.id
				
				LEFT JOIN (
					SELECT r.payment_id, (r.price - (CASE WHEN SUM(pdd.amount) IS NULL THEN 0 ELSE SUM(pdd.amount) END)) as price
					FROM (
						SELECT pr.payment_id, ((DATEDIFF(pr.end_date, pr.start_date)) * rb.price) as price
						FROM patient_room pr
						LEFT JOIN room_beds rb ON rb.id = pr.bed_id
						WHERE date(pr.created_date) >= '".$start_date."' AND date(pr.created_date) <= '".$end_date."'
						GROUP BY pr.payment_id
					) r
					LEFT JOIN payments_debt_discount pdd ON pdd.payment_id = r.payment_id AND pdd.service_type = 5
					GROUP BY r.payment_id
				) AS pr ON pr.payment_id = pp.id
			    
			    WHERE date(pp.created_date) >= '".$start_date."' AND date(pp.created_date) <= '".$end_date."' AND pp.status <> 2
				GROUP BY pp.id		
				ORDER BY pp.created_date
			) as p 
        ");

		return $query->row_array();
	}

    public function get_patient_items_for_payment($patient_id, $doctor_id) {

        $query = $this->db->query('
                    SELECT pp.id
                    FROM patients_payments pp
                    WHERE pp.patient_id = '.$patient_id.' AND pp.`status` = 2 AND pp.doctor_id = '.$doctor_id.'
        ');

        return $query->row_array();
    }

	public function get_patient_partners($patient_id)
	{
		$this->db->select("p.id, p.last_name, p.first_name");
		$this->db->join("partners p", "p.id = pp.partner_id", "left");
		$this->db->where("pp.patient_id", $patient_id);
		$this->db->group_by("pp.partner_id");

		$query = $this->db->get("patients_payments pp");
		$partners = $query->result_array();

		return $partners;
	}

	public function get_patient_debt($patient_id)
	{
		$query = $this->db->query("
            SELECT SUM(pp.total) as total, SUM(ppd.paid) as paid, SUM(pp.discount) as discount, 
            (CASE WHEN SUM(total - (paid + discount)) IS NULL THEN 0 ELSE SUM(total - (paid + discount)) END) as debt
            FROM patients_payments pp
            LEFT JOIN (
							SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
							FROM patients_payments_details ppd
							GROUP BY ppd.payment_id
						) ppd ON ppd.payment_id = pp.id
            WHERE pp.patient_id = ".$patient_id
        );

		return $query->row_array();

	}

	//yullanma bilan kelgan bemorlarning Yullanma bergan xamkorlari
	public function get_partners($start_date = null, $end_date = null)
	{
		if(is_null($start_date) && is_null($end_date)) {
			$start_date = $end_date = date("Y-m-d");
		}

		$this->db->select("p.id, p.last_name, p.first_name");
		$this->db->join("partners p", "p.id = pp.partner_id", "left");

		$this->db->where("date(pp.created_date) >=", $start_date);
		$this->db->where("date(pp.created_date) <=", $end_date);
		$this->db->where("pp.partner_id >", 0);

		$query = $this->db->get("patients_payments pp");
		return $query->result_array();

	}

	public function get_sender_doctors($start_date = null, $end_date = null)
	{
		if(is_null($start_date) && is_null($end_date)) {
			$start_date = $end_date = date("Y-m-d");
		}

		$this->db->select("e.id, u.last_name, u.first_name");
		$this->db->join("employees e", "e.id = pp.doctor_id", "left");
		$this->db->join("users u", "u.id = e.user_id", "left");

		$this->db->where("date(pp.created_date) >=", $start_date);
		$this->db->where("date(pp.created_date) <=", $end_date);
		$this->db->where("pp.doctor_id >", 0);

		$query = $this->db->get("patients_payments pp");
		return $query->result_array();

	}
}
