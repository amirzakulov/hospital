<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_service_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_patient_service($payment_id)
    {
        $this->db->order_by("s.name");
        $this->db->select("ps.*, s.name, s.price, pp.created_date as payment_date");
        $this->db->where("ps.payment_id", $payment_id);
        $this->db->join("services s", "s.id = ps.service_id", "left");
        $this->db->join("patients_payments pp", "pp.id = ps.payment_id", "left");
        $query = $this->db->get("patient_service ps");

        return $query->result_array();
    }

    public function add($arr){
        $this->db->insert("patient_service", $arr);
        return $this->db->insert_id();
    }

    /**
     * Bemorning tanlanmagan uzilarini uchurib tashlash
     *
     * @param $payment_id
     * @param $uzis array
     * @return mixed
     */
    public function delete_not_selected($payment_id, $services) {
		$service_ids = array();
		foreach ($services as $service) {
			$service_ids[] = $service["id"];
		}

		$this->db->where("payment_id", $payment_id);
		$this->db->where_not_in("service_id", $service_ids);
		$res = $this->db->delete("patient_service");

		foreach ($services as $service) {
			$this->db->where(array("payment_id" => $payment_id, "service_id" => $service["id"]));
			$this->db->update("patient_service", array("count" => $service["count"]));
    	}

        return $res;
    }

    /**
     * Bemorning payment_id buyicha servicelarini uchurib tashlash
     *
     * @param $payment_id
     * @return mixed
     */
    public function delete_by_paymentId($payment_id) {
        $this->db->where("payment_id", $payment_id);
        $res = $this->db->delete("patient_service");

        return $res;
    }

    public function update($id, $arr)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("patient_service", $arr);

        return $result;
    }

	public function get_service_by_date ($start_date = null, $end_date = null) {
		if(is_null($start_date) && is_null($end_date)) {
			$start_date = $end_date = date("Y-m-d");
		}

		$query = $this->db->query("
                            SELECT pp.id, pp.patient_id, u.last_name, u.first_name, pp.partner_id, pa.last_name as partner_last_name,
                            pa.first_name as partner_first_name, pp.created_date, pp.updated_date, ps.price,
                            (CASE WHEN SUM(pdd.debt) IS NULL THEN 0 ELSE SUM(pdd.debt) END) as debt,
                            (SUM(ps.price) - (CASE WHEN SUM(pdd.debt) IS NULL THEN 0 ELSE SUM(pdd.debt) END)) as paid,	
			                pp.doctor_id as sender_doctor_id, d.last_name as sender_doctor_last_name, d.first_name as sender_doctor_first_name
                            FROM `patients_payments` pp
                            LEFT JOIN (
                                    SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
                                    FROM patients_payments_details ppd
                                    GROUP BY ppd.payment_id
                            ) ppd ON ppd.payment_id = pp.id
                            LEFT JOIN (
                                    SELECT ps.payment_id, sum(s.price * ps.count) as price
                                    FROM `patient_service` ps
                                    LEFT JOIN services s ON s.id = ps.service_id
                                    GROUP BY ps.payment_id
                                    ORDER BY ps.created_date
                            ) as ps ON ps.payment_id = pp.id
                            LEFT JOIN (
                                    SELECT pdd.payment_id, SUM(pdd.amount)as debt
                                    FROM `payments_debt_discount` pdd
                                    WHERE pdd.service_type = 4
                                    GROUP BY pdd.payment_id
                            ) pdd ON pdd.payment_id = pp.id
                            LEFT JOIN patients p ON p.id = pp.patient_id
                            LEFT JOIN users u ON u.id = p.user_id
                            LEFT JOIN partners pa ON pa.id = pp.partner_id
                            LEFT JOIN employees e ON e.id = pp.doctor_id
							LEFT JOIN users d ON d.id = e.user_id
                            WHERE date(pp.created_date) >= '".$start_date."' AND date(pp.created_date) <= '".$end_date."' AND pp.service_status > 0
                            GROUP BY pp.id
                            ORDER BY pp.created_date DESC
                  ");

		return $query->result_array();
	}

}
