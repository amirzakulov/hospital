<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctors_bill_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add($arr)
    {
        $this->db->insert("doctors_bill", $arr);

        return $this->db->insert_id();
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



}
