<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_room_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_patient_room($bed_id)
    {
        $today = date("Y-m-d");
        $this->db->select("pr.*, u.last_name, u.first_name, pp.total, ppd.paid");
        $this->db->where("pr.bed_id", $bed_id);
        $this->db->where(array("pr.start_date <=" => $today, "pr.end_date >=" => $today));
        $this->db->or_where(array("pr.start_date >" => $today));
        $this->db->join("patients p", "p.id = pr.patient_id", "left");
        $this->db->join("users u", "u.id = p.user_id", "left");
        $this->db->join("patients_payments pp", "pp.id=pr.payment_id", "left");
        $this->db->join("(
            SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
            FROM patients_payments_details ppd
            GROUP BY ppd.payment_id
        ) ppd", "ppd.payment_id = pp.id", "left");
        $query = $this->db->get("patient_room pr");

        return $query->row_array();
    }

    public function add($arr){
        $this->db->insert("patient_room", $arr);
        return $this->db->insert_id();
    }

    public function update($id, $arr)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("patient_room", $arr);

        return $result;
    }

    public function update_by_payment_id($payment_id, $arr)
    {
        $this->db->where("payment_id", $payment_id);
        $result = $this->db->update("patient_room", $arr);

        return $result;
    }


    public function get_bed_by_patient($patient_id) {
        $this->db->select("pr.patient_id, pr.payment_id, rb.name as bed_name, pr.start_date, pr.end_date, u.last_name, u.first_name, r.number as room_number, 
        d.last_name as doctor_last_name, d.first_name as doctor_first_name");
        $this->db->order_by("pr.created_date");
        $this->db->join("room_beds rb", "rb.id = pr.bed_id", "left");
        $this->db->join("rooms r", "r.id = rb.room_id", "left");
        $this->db->join("patients p", "p.id = pr.patient_id", "left");
        $this->db->join("users u", "u.id = p.user_id", "left");
        $this->db->join("employees e", "e.id = pr.doctor_id", "left");
        $this->db->join("users d", "d.id = e.user_id", "left");
        $this->db->where("pr.patient_id", $patient_id);
        $query = $this->db->get("patient_room pr");

        return $query->result_array();
    }

    public function get_bed_by_payment($payment_id) {
        $this->db->select("pr.patient_id, pr.payment_id, rb.name as bed_name, rb.price as bed_price, pr.start_date, pr.end_date, 
        u.last_name, u.first_name, r.number as room_number, r.id as room_id, rb.id as bed_id,
        d.last_name as doctor_last_name, d.first_name as doctor_first_name");
        $this->db->order_by("pr.created_date");
        $this->db->join("room_beds rb", "rb.id = pr.bed_id", "left");
        $this->db->join("rooms r", "r.id = rb.room_id", "left");
        $this->db->join("patients p", "p.id = pr.patient_id", "left");
        $this->db->join("users u", "u.id = p.user_id", "left");
        $this->db->join("employees e", "e.id = pr.doctor_id", "left");
        $this->db->join("users d", "d.id = e.user_id", "left");
        $this->db->where("pr.payment_id", $payment_id);
        $query = $this->db->get("patient_room pr");

//        return $query->result_array();
        return $query->row_array();
    }



	public function get_room_by_date ($start_date = null, $end_date = null) {
		if(is_null($start_date) && is_null($end_date)) {
			$start_date = $end_date = date("Y-m-d");
		}

		$query = $this->db->query("
                            SELECT pp.id, pp.patient_id, u.last_name, u.first_name, pp.partner_id, 
                            pa.last_name as partner_last_name, pa.first_name as partner_first_name, 
                            pp.created_date, pp.updated_date, pr.price,
							(CASE WHEN SUM(pdd.debt) IS NULL THEN 0 ELSE SUM(pdd.debt) END) as debt, pr.number,
							(SUM(pr.price) - (CASE WHEN SUM(pdd.debt) IS NULL THEN 0 ELSE SUM(pdd.debt) END)) as paid,
							pp.doctor_id as sender_doctor_id, d.last_name as sender_doctor_last_name, d.first_name as sender_doctor_first_name
							FROM `patients_payments` pp
							LEFT JOIN (
								SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
								FROM patients_payments_details ppd 
								GROUP BY ppd.payment_id
							) ppd ON ppd.payment_id = pp.id
							LEFT JOIN (
								SELECT  pr.payment_id, ((DATEDIFF(pr.end_date, pr.start_date)) * rb.price) as price, r.number, r.id as room_id, rb.id as bed_id
								FROM `patient_room` pr
								LEFT JOIN room_beds rb ON rb.id = pr.bed_id
								LEFT JOIN rooms r ON r.id = rb.room_id
								ORDER BY pr.created_date
							) as pr ON pr.payment_id = pp.id
							LEFT JOIN (
								SELECT pdd.payment_id, SUM(pdd.amount)as debt
								FROM `payments_debt_discount` pdd
								WHERE pdd.service_type = 5
								GROUP BY pdd.payment_id
							) pdd ON pdd.payment_id = pp.id
							LEFT JOIN patients p ON p.id = pp.patient_id
							LEFT JOIN users u ON u.id = p.user_id
							LEFT JOIN partners pa ON pa.id = pp.partner_id
							LEFT JOIN employees e ON e.id = pp.doctor_id
							LEFT JOIN users d ON d.id = e.user_id
							WHERE date(pp.created_date) >= '".$start_date."' AND date(pp.created_date) <= '".$end_date."' AND pp.room_status > 0
							GROUP BY pp.id
							ORDER BY pp.created_date DESC
                  ");


		return $query->result_array();
	}

	public function get_busy_beds()
	{
		$this->db->where("end_date >", date("Y-m-d H:i:s"));
		$query = $this->db->get("patient_room pr");

		return $query->result_array();
	}

}
