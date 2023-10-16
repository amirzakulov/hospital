<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_doctor_model extends CI_Model
{
	public function __construct(){
		parent::__construct();
	}

	public function get_patient_doctor($payment_id) {
		$where = array("pd.payment_id" => $payment_id, "e.is_doctor" => 1);

		$this->db->select("pd.*, dl.price, u.last_name, u.first_name, dt.name as doctor_type");
		$this->db->group_by("pd.id");
		$this->db->where($where);
		$this->db->join("employees e", "e.id = pd.doctor_id", "left");
		$this->db->join("users u", "u.id = e.user_id", "left");
		$this->db->join("doctors_types_link dl", "dl.employee_id = e.id", "left");
		$this->db->join("doctors_types dt", "dt.id = dl.doctor_type_id", "left");
		$query = $this->db->get("patient_doctor pd");

		return $query->result_array();
	}

	//$id = patient_doctor id
	public function get_patient($id) {
		$this->db->select("pd.*, u.id as user_id, u.last_name, u.first_name, u.surname, u.dob, u.address, u.phone, u.email, u.username, u.active, u.occupation, 
            u.region_id, u.city_id, u.gender, u.description as user_description,
            r.name as region_name, c.name as city_name, pp.partner_id, 
            ppd.paid, pp.discount, pp.discount_type, pp.discount_value, pp.total, pp.status as payment_status, pp.order_status, pp.doctor_status, pp.created_date as payment_date
        ");
		$this->db->group_by("pd.id");
		$this->db->where("pd.id", $id);
		$this->db->join("patients p", "p.id = pd.patient_id", "left");
		$this->db->join("users u", "u.id = p.user_id", "left");
		$this->db->join("regions r", "r.id = u.region_id");
		$this->db->join("cities c", "c.id = u.city_id");
		$this->db->join("patients_payments pp", "pp.id = pd.payment_id");
		$this->db->join("(
            SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
            FROM patients_payments_details ppd
            GROUP BY ppd.payment_id
        ) ppd", "ppd.payment_id = pp.id", "left");
		$query = $this->db->get("patient_doctor pd");

		return $query->row_array();
	}


	public function add($arr){
		$query = $this->db->insert("patient_doctor", $arr);
		return $this->db->insert_id();
	}

	public function deleteAll($patient_id)
	{
		$this->db->where("patient_id", $patient_id);
		return $this->db->delete("patient_doctor");
	}

	public function remove_doctor($patient_id, $doctor_id)
	{
		$this->db->where(array("patient_id" => $patient_id, "doctor_id" => $doctor_id));
		return $this->db->delete("patient_doctor");
	}

	/**
	 * Bemorning tanlanmagan doctorlarini uchurib tashlash
	 *
	 * @param $payment_id
	 * @param $doctors array
	 * @return mixed
	 */
	public function delete_not_selected($payment_id, $doctors) {
		$doctor_ids = array();
		foreach ($doctors as $doctor) {
			$doctor_ids[] = $doctor["id"];
		}

		$this->db->where("payment_id", $payment_id);
		$this->db->where_not_in("doctor_id", $doctor_ids);
		$res = $this->db->delete("patient_doctor");

		foreach ($doctors as $doctor) {
			$this->db->where(array("payment_id" => $payment_id, "doctor_id" => $doctor["id"]));
			$this->db->update("patient_doctor", array("count" => $doctor["count"]));
		}

		return $res;
	}

	/**
	 * Bemorning payment_id buyicha doctorlarini uchurib tashlash
	 *
	 * @param $payment_id
	 * @return mixed
	 */
	public function delete_by_paymentId($payment_id) {
		$this->db->where("payment_id", $payment_id);
		$res = $this->db->delete("patient_doctor");

		return $res;
	}

	public function get_patients($doctor_id)
	{
		$this->db->select(
			"pd.*, u.id as user_id, u.last_name, u.first_name, u.surname, u.dob, u.address, u.phone, u.email, u.username, u.active, u.occupation, 
            u.region_id, u.city_id, u.gender, u.description,
            r.name as region_name, c.name as city_name
        ");

		$this->db->join("patients p", "p.id = pd.patient_id");
		$this->db->join("users u", "u.id = p.user_id");
		$this->db->join("regions r", "r.id = u.region_id");
		$this->db->join("cities c", "c.id = u.city_id");
		$this->db->order_by("u.last_name");
		$this->db->group_by("p.id");
		$this->db->where("pd.doctor_id", $doctor_id);
		return $this->db->get("patient_doctor pd")->result_array();
	}

	public function update($id, $arr)
	{
		$this->db->where("id", $id);
		$result = $this->db->update("patient_doctor", $arr);

		return $result;
	}

	public function get_patient_history($patient_id) {
		$this->db->select("pd.*");
		$this->db->where("pd.patient_id", $patient_id);
		$this->db->order_by("pd.created_date", "desc");
		$query = $this->db->get("patient_doctor pd");

		return $query->result_array();
	}

	public function get_doctor_cash() {
		$this->db->select("pd.*");
		$this->db->order_by("pd.created_date", "desc");
		$query = $this->db->get("patient_doctor pd");

		return $query->result_array();
	}

	public function get_doctors_by_date ($start_date = null, $end_date = null) {
		if(is_null($start_date) && is_null($end_date)) {
			$start_date = $end_date = date("Y-m-d");
		}


		$query = $this->db->query("
                                SELECT pp.id, pp.created_date, pp.updated_date, SUM(pd.price) as price, 
                                (CASE WHEN SUM(pdd.debt) IS NULL THEN 0 ELSE SUM(pdd.debt) END) as debt, 
                                (SUM(pd.price) - (CASE WHEN SUM(pdd.debt) IS NULL THEN 0 ELSE SUM(pdd.debt) END)) as paid,
                                pd.doctor_id, pd.last_name as doctor_last_name, pd.first_name as doctor_first_name
                                FROM `patients_payments` pp
                                LEFT JOIN (
                                         SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
                                         FROM patients_payments_details ppd 
                                         GROUP BY ppd.payment_id
                                ) ppd ON ppd.payment_id = pp.id
                                LEFT JOIN (
                                        SELECT pd.doctor_id, pd.payment_id, d.price, u.last_name, u.first_name
                                        FROM `patient_doctor` pd
                                        LEFT JOIN doctors_types_link d ON d.employee_id = pd.doctor_id
                                        LEFT JOIN employees e ON e.id = pd.doctor_id
                                        LEFT JOIN users u ON u.id = e.user_id
                                        ORDER BY pd.created_date
                                ) as pd ON pd.payment_id = pp.id
                                LEFT JOIN (
                                        SELECT pdd.payment_id, pdd.doctor_id, SUM(pdd.amount) as debt
                                        FROM `payments_debt_discount` pdd
                                        WHERE pdd.service_type = 1
                                        GROUP BY pdd.doctor_id, pdd.payment_id
                                ) pdd ON pdd.payment_id = pp.id AND pdd.doctor_id = pd.doctor_id
                                LEFT JOIN patients p ON p.id = pp.patient_id
                                LEFT JOIN users u ON u.id = p.user_id
                                LEFT JOIN partners pa ON pa.id = pp.partner_id
                                WHERE date(pp.created_date) >= '".$start_date."' AND date(pp.created_date) <= '".$end_date."' AND pp.doctor_status > 0
                                GROUP BY pd.doctor_id
                                ORDER BY pp.created_date DESC
        ");

		return $query->result_array();
	}

	public function get_doctor_patients_by_date($doctor_id, $start_date = null, $end_date = null)
	{
		if(is_null($start_date) && is_null($end_date)) {
			$start_date = $end_date = date("Y-m-d");
		}

		$query = $this->db->query("
                    SELECT pd.payment_id, pd.id, pd.doctor_id, pd.patient_id, pd.created_date,
					u.first_name as patient_first_name, u.last_name as patient_last_name, dl.price,
					pp.discount_value as discount, pp.partner_id,
					pdd.debt, 
					pa.last_name as partner_last_name, pa.first_name as partner_first_name, 
					pp.doctor_id as sender_doctor_id, d.last_name as sender_doctor_last_name, d.first_name as sender_doctor_first_name
					FROM patient_doctor pd
					LEFT JOIN doctors_types_link dl ON dl.employee_id = pd.doctor_id
					LEFT JOIN patients p ON p.id = pd.patient_id
					LEFT JOIN users u ON u.id = p.user_id
					LEFT JOIN patients_payments pp ON pp.id = pd.payment_id
					LEFT JOIN (
							SELECT pdd.payment_id, SUM(pdd.amount) as debt
							FROM `payments_debt_discount` pdd
							WHERE pdd.service_type = 1 AND pdd.type = 1 AND pdd.doctor_id = '".$doctor_id."'
							GROUP BY pdd.type, pdd.payment_id
					) pdd ON pdd.payment_id = pd.payment_id
					LEFT JOIN employees e ON e.id = pp.doctor_id
					LEFT JOIN users d ON d.id = e.user_id
					LEFT JOIN partners pa ON pa.id = pp.partner_id
					WHERE pd.doctor_id = '".$doctor_id."' AND date(pd.created_date) >= '".$start_date."' AND date(pd.created_date) <= '".$end_date."'
					GROUP BY pd.payment_id
					ORDER BY pd.payment_id DESC
                ");

		return $query->result_array();
	}

	public function get_doctors($start_date = null, $end_date = null)
	{
		if(is_null($start_date) && is_null($end_date)) {
			$start_date = $end_date = date("Y-m-d");
		}

		$this->db->select("pd.doctor_id, u.last_name, u.first_name");
		$this->db->join("patient_doctor pd", "pd.payment_id = pp.id", "left");
		$this->db->join("employees e", "e.id = pd.doctor_id", "left");
		$this->db->join("users u", "u.id = e.user_id", "left");
		$this->db->where("date(pp.created_date) >=", $start_date);
		$this->db->where("date(pp.created_date) <=", $end_date);
		$this->db->where("pp.doctor_status >", 0);
		$this->db->group_by("pd.doctor_id");

		$query = $this->db->get("patients_payments pp");
		return $query->result_array();

	}


}
