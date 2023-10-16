<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_uzi_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function get_patient_uzi($payment_id)
    {
        $this->db->order_by("u.name");
        $this->db->select("pu.*, u.name, u.name_ru, u.price, pp.created_date as payment_date");
        $this->db->where("pu.payment_id", $payment_id);
        $this->db->where("pu.uzi_id !=", null);
        $this->db->join("uzi u", "u.id = pu.uzi_id", "left");
        $this->db->join("patients_payments pp", "pp.id = pu.payment_id", "left");
        $query = $this->db->get("patient_uzi pu");

        return $query->result_array();
    }

    public function get_patient_uzi_by_patient($patient_id)
    {
        $this->db->select("pu.*, u.name, u.price, pp.created_date as payment_date");
        $this->db->join("uzi u", "u.id = pu.uzi_id", "left");
        $this->db->join("patients_payments pp", "pp.id = pu.payment_id", "left");
        $this->db->where("pu.patient_id", $patient_id);
        $query = $this->db->get("patient_uzi pu");

        return $query->result_array();
    }

	public function get_patient_uzi_conclusion($payment_id)
	{
		$this->db->order_by("u.name");
		$this->db->select("pu.*, u.name, u.name_ru, u.price, pp.created_date as payment_date");
		$this->db->where("pu.payment_id", $payment_id);
		$this->db->where("pu.is_conclusion", 1);
		$this->db->join("uzi u", "u.id = pu.uzi_id", "left");
		$this->db->join("patients_payments pp", "pp.id = pu.payment_id", "left");
		$query = $this->db->get("patient_uzi pu");

		return $query->row_array();
	}

    public function get_uzi_result($payment_id)
    {
        $this->db->select("f.*");
        $this->db->where("f.payment_id", $payment_id);
        $query = $this->db->get("files f");

        return $query->row_array();
    }

    public function add($arr){
        $this->db->insert("patient_uzi", $arr);
        return $this->db->insert_id();
    }

    public function deleteAll($patient_id)
    {
        $this->db->where("patient_id", $patient_id);
        return $this->db->delete("patient_uzi");
    }

    public function remove_uzi($patient_id, $uzi_id)
    {
        $this->db->where(array("patient_id" => $patient_id, "uzi_id" => $uzi_id));

        return $this->db->delete("patient_uzi");
    }

    /**
     * Bemorning tanlanmagan uzilarini uchurib tashlash
     *
     * @param $payment_id
     * @param $uzis array
     * @return mixed
     */
    public function delete_not_selected($payment_id, $uzis) {
		$uzi_ids = array();
		foreach ($uzis as $uzi) {
			$uzi_ids[] = $uzi["id"];
		}

		$this->db->where("payment_id", $payment_id);
		$this->db->where_not_in("uzi_id", $uzi_ids);
		$res = $this->db->delete("patient_uzi");

		foreach ($uzis as $uzi) {
			$this->db->where(array("payment_id" => $payment_id, "uzi_id" => $uzi["id"]));
			$this->db->update("patient_uzi", array("count" => $uzi["count"]));
		}

		return $res;
    }

    /**
     * Bemorning payment_id buyicha uzilarini uchurib tashlash
     *
     * @param $payment_id
     * @return mixed
     */
    public function delete_by_paymentId($payment_id) {
        $this->db->where("payment_id", $payment_id);
        $res = $this->db->delete("patient_uzi");

        return $res;
    }

    /**
     * Barcha bemorlarning ruyhati
     * @return array
     */
    public function get_all_patients()
    {
        $this->db->select(
            "pu.*, u.id as user_id, u.last_name, u.first_name, u.surname, u.dob, u.address, u.phone, u.email, u.username, u.active, u.occupation,
            u.region_id, u.city_id, u.gender, u.description,
            r.name as region_name, c.name as city_name, pp.partner_id,
            ppd.paid, pp.discount, pp.discount_type, pp.discount_value, pp.total, pp.status as payment_status, pp.created_date as payment_date
        ");

        $this->db->group_by("p.id");
        $this->db->join("uzi z", "z.id = pu.uzi_id", "left");
        $this->db->join("patients p", "p.id = pu.patient_id", "left");
        $this->db->join("users u", "u.id = p.user_id", "left");
        $this->db->join("regions r", "r.id = u.region_id", "left");
        $this->db->join("cities c", "c.id = u.city_id", "left");
        $this->db->join("patients_payments pp", "pp.id = pu.payment_id", "left");
        $this->db->join("(
            SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
            FROM patients_payments_details ppd
            GROUP BY ppd.payment_id
        ) ppd", "ppd.payment_id = pp.id", "left");

        return $this->db->get("patient_uzi pu")->result_array();
    }

    //bermor malumotlari
    public function get_patient($payment_id)
    {
        $this->db->select(
            "pu.*, u.id as user_id, u.last_name, u.first_name, u.surname, u.dob, u.address, u.phone, u.email, u.username, u.active, u.occupation,
            u.region_id, u.city_id, u.gender, u.description, r.name as region_name, c.name as city_name,
            ppd.paid, pp.discount, pp.discount_type, pp.discount_value, pp.total, pp.status as payment_status, pp.order_status, pp.uzi_status, pp.created_date as payment_date
        ");

        $this->db->group_by("pu.payment_id");
        $this->db->join("patients p", "p.id = pu.patient_id");
        $this->db->join("users u", "u.id = p.user_id");
        $this->db->join("regions r", "r.id = u.region_id");
        $this->db->join("cities c", "c.id = u.city_id");
        $this->db->join("patients_payments pp", "pp.id = pu.payment_id");
        $this->db->join("(
            SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
            FROM patients_payments_details ppd
            GROUP BY ppd.payment_id
        ) ppd", "ppd.payment_id = pp.id", "left");

        $this->db->where(array("pu.payment_id" => $payment_id));
        return $this->db->get("patient_uzi pu")->row_array();
    }

    public function update($id, $arr)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("patient_uzi", $arr);

        return $result;
    }

    public function update_status($payment_id, $arr)
    {
        $this->db->where("payment_id", $payment_id);
        $result = $this->db->update("patient_uzi", $arr);

        return $result;
    }

	public function update_by_payment_id($payment_id, $templates)
	{
		foreach ($templates as $uzi_id => $template) {
			$this->db->where("payment_id", $payment_id);
			$this->db->where("uzi_id", $uzi_id);
			$result = $this->db->update("patient_uzi", $template);
		}

		return $result;
	}

	public function get_uzis_by_date ($start_date = null, $end_date = null) {
		if(is_null($start_date) && is_null($end_date)) {
			$start_date = $end_date = date("Y-m-d");
		}


        $query = $this->db->query("
    
                SELECT pp.id, pp.patient_id, u.last_name, u.first_name, pp.partner_id, pa.last_name as partner_last_name, 
                pa.first_name as partner_first_name, pp.created_date, pp.updated_date, pu.price, pu.count,
                (CASE WHEN SUM(pdd.debt) IS NULL THEN 0 ELSE SUM(pdd.debt) END) as debt, 
                (SUM(pu.price * pu.count) - (CASE WHEN SUM(pdd.debt) IS NULL THEN 0 ELSE SUM(pdd.debt) END)) as paid,
                pp.doctor_id as sender_doctor_id, d.last_name as sender_doctor_last_name, d.first_name as sender_doctor_first_name
                FROM `patients_payments` pp
                LEFT JOIN (
                    SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
                    FROM patients_payments_details ppd 
                    GROUP BY ppd.payment_id
                ) ppd ON ppd.payment_id = pp.id
                LEFT JOIN (
                    SELECT pu.payment_id, sum(u.price) as price, pu.count
                    FROM `patient_uzi` pu
                    LEFT JOIN uzi u ON u.id = pu.uzi_id
                    GROUP BY pu.payment_id
                    ORDER BY pu.created_date
                ) as pu ON pu.payment_id = pp.id
                LEFT JOIN (
                    SELECT pdd.payment_id, SUM(pdd.amount)as debt
                    FROM `payments_debt_discount` pdd
                    WHERE pdd.service_type = 3
                    GROUP BY pdd.payment_id
                ) pdd ON pdd.payment_id = pp.id
                LEFT JOIN patients p ON p.id = pp.patient_id
                LEFT JOIN users u ON u.id = p.user_id
                LEFT JOIN partners pa ON pa.id = pp.partner_id
                LEFT JOIN employees e ON e.id = pp.doctor_id
				LEFT JOIN users d ON d.id = e.user_id
                WHERE date(pp.created_date) >= '".$start_date."' AND date(pp.created_date) <= '".$end_date."' AND pp.uzi_status > 0
                GROUP BY pp.id
                ORDER BY pp.created_date DESC
                ");

		return $query->result_array();
	}

}
