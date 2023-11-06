<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctors_bill_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

	public function get_doctor_bill($id)
	{
		$this->db->select("db.id, db.doctor_id, db.amount, db.payment_type_id, db.created_date, 
		d.last_name as doctor_last_name, d.first_name as doctor_first_name,
		u.last_name as user_last_name, u.first_name as user_first_name,
		pt.name as payment_type
		");
		$this->db->join("employees e", "e.id = db.doctor_id");
		$this->db->join("users d", "d.id = e.user_id");
		$this->db->join("users u", "u.id = db.user_id");
		$this->db->join("payment_types pt", "pt.id = db.payment_type_id");
		$this->db->where("db.id", $id);
		$query = $this->db->get("doctors_bill db");

		return $query->row_array();
    }

    public function add($arr)
    {
        $this->db->insert("doctors_bill", $arr);

        $id = $this->db->insert_id();

        return $this->get_doctor_bill($id);
    }

	public function update($id, $arr)
	{
		$this->db->where("id", $id);
		$this->db->update("doctors_bill", $arr);

		return $this->get_doctor_bill($id);
	}

	public function delete($id)
	{
		$this->db->where("id", $id);
		return $this->db->delete("doctors_bill");
	}

    public function get_doctors_bill($start_date = false, $end_date = false, $expenses_list = false) {
		$start_date = !$start_date ? date("Y-m-d") : $start_date;
		$end_date   = !$end_date ? date("Y-m-d") : $end_date;

		if($expenses_list) {
			$this->db->select("d.*, u.last_name, u.first_name");
			$this->db->order_by("d.created_date", "desc");
			$this->db->join("employees e", "e.id = d.doctor_id");
			$this->db->join("users u", "u.id = e.user_id");
		} else {
			$this->db->select_sum("d.amount");
		}

		$this->db->where(array("date(d.created_date) >=" => $start_date, "date(d.created_date) <=" => $end_date));
		$query = $this->db->get("doctors_bill d");

		return $expenses_list ? $query->result_array() : $query->row_array()["amount"];
	}

	public function get_doctor_bill_by_date($doctor_id, $start_date = null, $end_date = null, $from_cash = null)
	{
		$this->db->select("db.doctor_id, SUM(db.amount) as amount");
		$this->db->where("db.doctor_id", $doctor_id);
		$this->db->where("date(db.created_date) >=", $start_date);
		$this->db->where("date(db.created_date) <=", $end_date);
		$this->db->group_by("db.doctor_id");
		if (!is_null($from_cash)) {
			$this->db->where("db.from_cash", $from_cash);
		}

		$query = $this->db->get("doctors_bill db");

		return $query->row_array();

	}


	public function get_doctor_bills_by_date($doctor_id, $start_date = null, $end_date = null, $from_cash = null)
	{
		$this->db->select("db.id, db.doctor_id, db.amount, db.payment_type_id, db.created_date,
		d.last_name as doctor_last_name, d.first_name as doctor_first_name,
		u.last_name as user_last_name, u.first_name as user_first_name,
		pt.name as payment_type
		");
		$this->db->join("employees e", "e.id = db.doctor_id");
		$this->db->join("users d", "d.id = e.user_id");
		$this->db->join("users u", "u.id = db.user_id");
		$this->db->join("payment_types pt", "pt.id = db.payment_type_id");
		$this->db->where("db.doctor_id", $doctor_id);
		$this->db->where("date(db.created_date) >=", $start_date);
		$this->db->where("date(db.created_date) <=", $end_date);
		if (!is_null($from_cash)) {
			$this->db->where("db.from_cash", $from_cash);
		}

		$query = $this->db->get("doctors_bill db");

		return $query->result_array();

	}

	public function get_doctors_bills_by_date($start_date = null, $end_date = null, $from_cash = null)
	{
		if(is_null($start_date) && is_null($end_date)) {
			$start_date = $end_date = date("Y-m-d");
		}

		$this->db->select("db.id, db.doctor_id, db.amount, db.payment_type_id, db.created_date, db.from_cash,
		d.last_name as doctor_last_name, d.first_name as doctor_first_name,
		u.last_name as user_last_name, u.first_name as user_first_name,
		pt.name as payment_type
		");
		$this->db->join("employees e", "e.id = db.doctor_id");
		$this->db->join("users d", "d.id = e.user_id");
		$this->db->join("users u", "u.id = db.user_id");
		$this->db->join("payment_types pt", "pt.id = db.payment_type_id");
		$this->db->where("date(db.created_date) >=", $start_date);
		$this->db->where("date(db.created_date) <=", $end_date);
		if (!is_null($from_cash)) {
			$this->db->where("db.from_cash", $from_cash);
		}

		$query = $this->db->get("doctors_bill db");

		return $query->result_array();

	}

	public function get_doctors_bills_total($start_date = null, $end_date = null, $from_cash = null)
	{
		if(is_null($start_date) && is_null($end_date)) {
			$start_date = $end_date = date("Y-m-d");
		}

		$this->db->select_sum("db.amount");
		$this->db->where("date(db.created_date) >=", $start_date);
		$this->db->where("date(db.created_date) <=", $end_date);
		if (!is_null($from_cash)) {
			$this->db->where("db.from_cash", $from_cash);
		}

		$query = $this->db->get("doctors_bill db")->row_array();

		return $query['amount'];

	}


}
