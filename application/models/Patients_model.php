<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patients_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_patients() {
        $this->db->select(
            "p.id, u.id as user_id, u.last_name, u.first_name, u.surname, u.dob, u.address, u.phone, u.email, u.username, u.active, u.occupation, 
            u.region_id, u.city_id, u.gender, u.description,
            r.name as region_name, c.name as city_name
        ");
        $this->db->order_by("u.created_date", "desc");
        $this->db->join("users u", "u.id = p.user_id", "left");
        $this->db->join("regions r", "r.id = u.region_id", "left");
        $this->db->join("cities c", "c.id = u.city_id", "left");
        return $this->db->get("patients p")->result_array();
    }
    
    public function get_patient($id)
    {
        $this->db->select(
            "p.id, u.id as user_id, u.last_name, u.first_name, u.surname, u.dob, u.address, u.phone, u.email, u.username, u.active, u.occupation, 
            u.region_id, u.city_id, u.gender, u.description, u.created_date,
            r.name as region_name, c.name as city_name
        ");
        $this->db->order_by("u.created_date", "desc");
        $this->db->where("p.id", $id);
        $this->db->join("users u", "u.id = p.user_id");
        $this->db->join("regions r", "r.id = u.region_id");
        $this->db->join("cities c", "c.id = u.city_id");
        $result = $this->db->get("patients p")->row_array();

        return $result;
    }

    public function get_paid_patients_by_date($date, $count = false, $limit = 0){

        $where = " WHERE date(pp.created_date) = '".$date."' AND pp.room_status = 0 AND pp.status != 2 ";
		$qlimit = '';
        if($count == true) {
            $where = " WHERE date(pp.created_date) = '".$date."' AND pp.room_status = 0 AND pp.status = 0 ";
        }

		if($limit > 0) {
			$qlimit = " LIMIT ".$limit;;
		}

        $query = $this->db->query("SELECT pp.patient_id as id, pp.id as payment_id, (pp.total - (ppd.paid + pp.discount)) as debt, pp.discount, pp.discount_type, pp.discount_value, ppd.paid, ppd.by_cash, ppd.by_card, ppd.by_bank, pp.total, 
                                pp.created_date as payment_date, pp.`status`, pp.order_status, pp.doctor_status, pp.laboratory_status, pp.uzi_status, pp.service_status, pp.partner_id, pt.last_name as partner_last_name, pt.first_name as partner_first_name, pp.doctor_id as sender_doctor_id, 
                                u.id as user_id, u.last_name, u.first_name, u.surname, u.username, 
                                u.address, r.`name` as region_name, c.`name` as city_name, u.dob, u.phone, 
                                d.last_name as sender_doctor_last_name,  d.first_name as sender_doctor_first_name
							FROM `patients_payments` pp
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
							LEFT JOIN employees e ON e.id = pp.doctor_id
							LEFT JOIN users d ON d.id = e.user_id
							".$where." 
							ORDER BY pp.id desc
							".$qlimit
						);

        if(!$count) {
            return $query->result_array();
        } else {
            return $query->num_rows();
        }
    }

    public function get_paid_patients_credit($count = false) {
        $date = date("Y-m-d");

//        $where = " WHERE (date(pp.created_date) < '".$date."' AND pp.`status` = 0 AND pp.room_status = 0 AND ppd.paid != 0) OR (date(pp.created_date) < '".$date."' AND pp.`status` = 1 AND pp.room_status = 0 AND ppd.paid != 0 AND date(pp.updated_date) = '".$date."') ";
        $where = " WHERE (date(pp.created_date) < '".$date."' AND pp.`status` = 0 AND pp.room_status = 0) OR (date(pp.created_date) < '".$date."' AND date(pp.updated_date) = '".$date."' AND pp.`status` = 1 AND pp.room_status = 0) ";
        if($count == true) {
//            $where = " WHERE (date(pp.created_date) < '".$date."' AND pp.`status` = 0 AND pp.room_status = 0 AND ppd.paid != 0) ";
            $where = " WHERE (date(pp.created_date) < '".$date."' AND pp.`status` = 0 AND pp.room_status = 0)";
        }

        $query = $this->db->query("SELECT pp.patient_id as id, pp.id as payment_id, (pp.total - (ppd.paid + pp.discount)) as debt, pp.discount, pp.discount_type, pp.discount_value, ppd.paid, pp.total, date(pp.created_date) as payment_date, date(pp.updated_date) as updated_date, 
                                pp.`status`, pp.order_status, pp.doctor_status, pp.laboratory_status, pp.uzi_status, pp.service_status, pp.created_date,
                                pp.partner_id, pt.last_name as partner_last_name, pt.first_name as partner_first_name,
                                u.id as user_id, u.last_name, u.first_name, u.surname, u.username, u.address, r.`name` as region_name, c.`name` as city_name, u.dob, u.phone
                                FROM `patients_payments` pp
                                LEFT JOIN (
									SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
									FROM patients_payments_details ppd 
									GROUP BY ppd.payment_id
								) ppd ON ppd.payment_id = pp.id
                                LEFT JOIN patients p ON p.id = pp.patient_id
                                LEFT JOIN users u ON u.id = p.user_id
                                LEFT JOIN regions r ON r.id = u.region_id
                                LEFT JOIN cities c ON c.id = u.city_id
                                LEFT JOIN partners pt ON pt.id = pp.partner_id ".$where);

        if(!$count) {
            return $query->result_array();
        } else {
            return $query->num_rows();
        }

    }

	/**
	 * agar $count = false bulsa, qarzdorlar sonini qaytaradi,
	 * aks holda qarzdorlarni ro'yxatini
	 ****/
	public function get_debitor_patients($count = false) {
		$query = $this->db->query("SELECT pp.patient_id as id, pp.id as payment_id, (pp.total - (ppd.paid + pp.discount)) as debt, pp.discount, pp.discount_type, pp.discount_value, ppd.paid, pp.total, pp.created_date as payment_date, date(pp.updated_date) as updated_date, pp.`status`, 
                                u.id as user_id, u.last_name, u.first_name, u.surname, u.username, u.address, r.`name` as region_name, c.`name` as city_name, u.dob, u.phone
                                FROM `patients_payments` pp
                                LEFT JOIN (
									SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
									FROM patients_payments_details ppd 
									GROUP BY ppd.payment_id
								) ppd ON ppd.payment_id = pp.id
                                LEFT JOIN patients p ON p.id = pp.patient_id
                                LEFT JOIN users u ON u.id = p.user_id
                                LEFT JOIN regions r ON r.id = u.region_id
                                LEFT JOIN cities c ON c.id = u.city_id
                                WHERE pp.total > (pp.discount + ppd.paid) AND pp.status <> 2
                                ORDER BY pp.created_date DESC
        ");

		if(!$count) {
			return $query->result_array();
		} else {
			return $query->num_rows();
		}

	}

	/**
	 * agar $count = false bulsa, qarzdorlar sonini qaytaradi,
	 * aks holda qarzdorlarni ro'yxatini
	 ****/
	public function get_debitor_patients_by_date($start_date = null, $end_date = null) {
		if(is_null($start_date) && is_null($end_date)) {
			$start_date = $end_date = date("Y-m-d");
		}


		$query = $this->db->query("SELECT pp.patient_id as id, pp.id as payment_id, (pp.total - (ppd.paid + pp.discount)) as debt, pp.discount, pp.discount_type, pp.discount_value, ppd.paid, pp.total, pp.created_date as payment_date, date(pp.updated_date) as updated_date, pp.`status`, 
                                u.id as user_id, u.last_name, u.first_name, u.surname, u.username, u.address, r.`name` as region_name, c.`name` as city_name, u.dob, u.phone
                                FROM `patients_payments` pp
                                LEFT JOIN (
									SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
									FROM patients_payments_details ppd 
									GROUP BY ppd.payment_id
								) ppd ON ppd.payment_id = pp.id
                                LEFT JOIN patients p ON p.id = pp.patient_id
                                LEFT JOIN users u ON u.id = p.user_id
                                LEFT JOIN regions r ON r.id = u.region_id
                                LEFT JOIN cities c ON c.id = u.city_id
                                WHERE DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.total > (pp.discount + ppd.paid) AND pp.status <> 2
                                ORDER BY pp.created_date DESC
        ");

		return $query->result_array();

	}

    public function get_patients_archive() {
        $query = $this->db->query("
                            SELECT p.* 
                            FROM (
                                SELECT (CASE WHEN pp.`status` IS NULL THEN 1 ELSE pp.`status` END) as `status`, p.id, pp.last_payment_date
                                , `u`.`last_name`, `u`.`first_name`, `u`.`surname`, `u`.`dob`, `u`.`address`, `u`.`phone`, `u`.`email`
                                , `u`.`username`, `u`.`active`, `u`.`occupation`, `u`.`region_id`, `u`.`city_id`, `u`.`gender`, `u`.`description`, u.created_date as user_created_date, `r`.`name` as `region_name`
                                , `c`.`name` as `city_name`
                                FROM `patients` `p` 
                                LEFT JOIN `users` `u` ON `u`.`id` = `p`.`user_id` 
                                LEFT JOIN `regions` `r` ON `r`.`id` = `u`.`region_id` 
                                LEFT JOIN `cities` `c` ON `c`.`id` = `u`.`city_id` 
                                LEFT JOIN (
                                    SELECT min(pp.`status`) as `status`, max(pp.created_date) as last_payment_date, pp.patient_id
                                    FROM patients_payments pp 
                                    GROUP BY pp.patient_id
                                ) pp ON pp.patient_id = p.id
                            ) p
                            /*WHERE p.`status` <> 0*/
                            ORDER BY p.last_name
        ");

        return $query->result_array();
    }

    public function get_for_payment_patients($count = false){

        $query = $this->db->query("SELECT pp.patient_id as id, pp.id as payment_id, (pp.total - (ppd.paid + pp.discount)) as debt, pp.discount, pp.discount_type, pp.discount_value, ppd.paid, pp.total, 
                                    date(pp.created_date) as payment_date, pp.`status`, pp.order_status, pp.doctor_status, pp.laboratory_status, pp.uzi_status, pp.service_status, pp.partner_id, pt.last_name as partner_last_name, pt.first_name as partner_first_name, 
                                    u.id as user_id, u.last_name, u.first_name, u.surname, u.username, 
                                    u.address, r.`name` as region_name, c.`name` as city_name, u.dob, u.phone
                                    FROM `patients_payments` pp
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
                                    WHERE pp.room_status = 0 AND pp.status = 2
                                    ORDER BY pp.id
                            ");


        if(!$count) {
            return $query->result_array();
        } else {
            return $query->num_rows();
        }
    }

    public function get_max_id() {
        $this->db->select_max('id');
        $query = $this->db->get('patients')->row_array();

        return $query["id"];
    }

    public function add($arr){
        $this->db->insert("patients", $arr);
        return $this->db->insert_id();
    }

    public function update($id, $arr) {
        $this->db->where("id", $id);
        $result = $this->db->update("patients", $arr);

        return $result;
    }
    
    public function delete($id) {
        $patient = $this->get_patient($id);
        $user_id = $patient["user_id"];

        $this->db->where("id", $id);
        $this->db->delete("patients");

        $this->db->where("user_id", $user_id);
        $this->db->delete("users_groups");

        $this->db->where("id", $user_id);
        $result = $this->db->delete("users");

        return $result;
    }

    public function search_patients($keyword) {
        $keywords = explode(" ", $keyword);

        $this->db->select("p.id, u.last_name, u.first_name, u.phone");
        $this->db->order_by("u.last_name", "asc");
        $this->db->join("users u", "u.id = p.user_id", "left");
        if(count($keywords) == 1) {
            $this->db->like("u.last_name", $keyword);
            $this->db->or_like("u.first_name", $keyword);
            $this->db->or_like("u.phone", $keyword);
        } elseif (count($keywords) == 2) {
            $this->db->like("u.last_name", $keywords[0]);
            $this->db->like("u.first_name", $keywords[1]);
        }

        return $this->db->get("patients p")->result_array();
    }
}
