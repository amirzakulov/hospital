<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments_debt_discount_model extends CI_Model {
    public function __construct(){
        parent::__construct();
    }


    public function get_payment_items($payment_id, $type = null)
    {
        $this->db->select("pdd.id, pdd.type, pdd.payment_id, pdd.service_type, pdd.doctor_id, SUM(pdd.amount) as amount, MAX(pdd.debt_off_type) as debt_off_type, pdd.created_date, 
                            u.last_name as doctor_last_name, u.first_name as doctor_first_name");

		if(is_null($type)) {
			$this->db->where("pdd.payment_id", $payment_id);
		} else {
			$this->db->where(array("pdd.payment_id" => $payment_id, "pdd.type" => $type));
		}
        $this->db->join("employees e", "e.id = pdd.doctor_id", "left");
        $this->db->join("users u", "u.id = e.user_id", "left");
        $this->db->group_by("pdd.service_type, doctor_id");
        $query = $this->db->get("payments_debt_discount pdd");

        return $query->result_array();
    }

	public function get_payment_items_unpaid($payment_id, $type)
	{
		$this->db->select("pdd.id, pdd.type, pdd.payment_id, pdd.service_type, pdd.doctor_id, pdd.amount, pdd.debt_off_type, pdd.created_date, 
                            u.last_name as doctor_last_name, u.first_name as doctor_first_name");
		$this->db->join("employees e", "e.id = pdd.doctor_id", "left");
		$this->db->join("users u", "u.id = e.user_id", "left");
		$this->db->where(array("pdd.payment_id" => $payment_id, "pdd.debt_off_type" => 0, "pdd.type" => $type));
		$query = $this->db->get("payments_debt_discount pdd");

		return $query->result_array();
	}

	//payment_id buyicha taqsimlangan qarzlar yigindisi
	public function get_debt_sum($payment_id)
	{
		$this->db->select_sum("pdd.amount");
		$this->db->where(array("pdd.payment_id" => $payment_id, "pdd.type" => 1));
		$result = $this->db->get("payments_debt_discount pdd")->row_array();

		return $result["amount"];
	}

	//payment_id buyicha taqsimlangan chegirmalar yigindisi
	public function get_discount_sum($payment_id)
	{
		$this->db->select_sum("pdd.amount");
		$this->db->where(array("pdd.payment_id" => $payment_id, "pdd.type" => 2));
		$result = $this->db->get("payments_debt_discount pdd")->row_array();

		return $result["amount"];
	}

	//payment_id buyicha tulangan qarzlar yigindisi
	public function get_debt_off_sum($payment_id)
	{
		$this->db->select_sum("pdd.amount");
		$this->db->where(array("pdd.payment_id" => $payment_id, "pdd.debt_off_type" => 1));
		$query = $this->db->get("payments_debt_discount pdd");

		return $query->row_array();
	}

    public function get_debt_off_status($payment_id)
    {
//        $this->db->select("MAX(pdd.debt_off_type) as debt_off_type");
//        $this->db->group_by("pdd.service_type, doctor_id");
//        $this->db->where(array("pdd.payment_id" => $payment_id));
//        $query = $this->db->get("payments_debt_discount pdd");
//        $result = $query->row_array();
//
//        if(isset($result["debt_off_type"]) && $result["debt_off_type"] == 1) {
//            return true;
//        } else {
//            return false;
//        }

		$this->db->select_sum("pdd.amount");
		$this->db->group_by("pdd.type");
		$this->db->where(array("pdd.payment_id" => $payment_id, "pdd.type" => 1));
		$result = $this->db->get("payments_debt_discount pdd")->row_array();

		$paid_status = false;
		if($result["amount"] == 0) {
			$paid_status = true;
		}

		return $paid_status;


    }

    /** qarzni tulash uchun birorta tulov buldimi-yuqmi tekshiradi **/
    public function check_payment_items($payment_id, $debt_off_type = 1)
    {
        $this->db->where(array("payment_id" => $payment_id, "debt_off_type" => $debt_off_type));
        $query = $this->db->get("payments_debt_discount");

        $paid_status = false;
        if($query->num_rows() > 0) {
            $paid_status = true;
        }

        return $paid_status;
    }

    public function add($arr)
    {
        $this->db->insert("payments_debt_discount", $arr);
        return $this->db->insert_id();
    }

    public function add_batch($arr)
    {
        return $this->db->insert_batch("payments_debt_discount", $arr);

    }

    public function delete_for_update($payment_id, $type)
    {
        $this->db->where(array("payment_id" => $payment_id, "type" => $type, "debt_off_type" => 0));
        return $this->db->delete("payments_debt_discount");
    }

    public function delete_by_payment_id($payment_id)
    {
        $this->db->where(array("payment_id" => $payment_id));
        return $this->db->delete("payments_debt_discount");
    }

	public function delete_by_type($payment_id, $type)
	{
		$this->db->where(array("payment_id" => $payment_id, "type" => $type));
		return $this->db->delete("payments_debt_discount");
	}

	//bugun tulangan eski qarzlar. Bugun qarz bulib ketib, qaytib kelib bugun tulagan bulsa uni
    //xisobga olmaymiz
    public function from_old_debts($start_date = null, $end_date = null)
    {
        if(is_null($start_date) && is_null($end_date)) {
            $start_date = $end_date = date("Y-m-d");
        }

        $query = $this->db->query("
                            SELECT d.*
                            FROM (
                                SELECT pdd.id, pdd.type, pdd.payment_id, pdd.service_type, pdd.doctor_id, (-1 * SUM(pdd.amount)) as amount, pdd.debt_off_type, pdd.created_date as payment_date
                                , pp.created_date, u.last_name, u.first_name, ppd.by_cash, ppd.by_card, ppd.by_bank
                                FROM `payments_debt_discount` pdd
                                LEFT JOIN patients_payments pp ON pp.id = pdd.payment_id
                                LEFT JOIN (
                                	SELECT * from patients_payments_details ppd 
									WHERE date(ppd.created_date) >= '".$start_date."' AND date(ppd.created_date) <= '".$end_date."'
                                ) ppd ON ppd.payment_id = pp.id
                                LEFT JOIN employees e ON e.id = pdd.doctor_id
                                LEFT JOIN users u ON u.id = e.user_id
                                WHERE pdd.type = 1 AND pdd.debt_off_type = 1 AND date(pdd.created_date) >= '".$start_date."' AND date(pdd.created_date) <= '".$end_date."'
                                GROUP BY pdd.payment_id, pdd.service_type
                            ) d
                            WHERE date(d.created_date) <> '".(date("Y-m-d"))."'
        ");

        return $query->result_array();
    }

}
