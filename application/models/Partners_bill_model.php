<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partners_bill_model extends CI_Model
{
    public function __construct(){
        parent::__construct();
    }

	public function get_partner_bill($id)
	{
		$this->db->select("pb.id, pb.partner_id, pb.amount, pb.payment_type_id,
		p.last_name, p.first_name, 
		u.last_name as user_last_name, u.first_name as user_first_name, pb.created_date,
		pt.name as payment_type
		");
		$this->db->join("partners p", "p.id = pb.partner_id");
		$this->db->join("users u", "u.id = pb.user_id");
		$this->db->join("payment_types pt", "pt.id = pb.payment_type_id");
		$this->db->where("pb.id", $id);
		$query = $this->db->get("partners_bill pb");

		return $query->row_array();
    }

    public function add($arr)
    {
        $this->db->insert("partners_bill", $arr);
        $id = $this->db->insert_id();

        return $this->get_partner_bill($id);
    }

    public function update($id, $arr)
    {
        $this->db->where("id", $id);
        $this->db->update("partners_bill", $arr);

        return $this->get_partner_bill($id);
    }

    public function delete($id)
    {
        $this->db->where("id", $id);
        return $this->db->delete("partners_bill");
    }

	public function get_partner_bills($partner_id)
	{
		$this->db->select("pb.partner_id, pb.amount, pb.created_date, p.first_name, p.last_name");
		$this->db->join("partners p", "p.id = pb.partner_id");
		$this->db->where("partner_id", $partner_id);
		$this->db->order_by("pb.created_date", 'desc');
		$query = $this->db->get("partners_bill pb");

		return $query->result_array();

	}

	public function get_partner_bill_by_date($partner_id, $start_date = null, $end_date = null, $from_cash = null)
	{
		$this->db->select("pb.partner_id, SUM(pb.amount) as amount");
		$this->db->where("pb.partner_id", $partner_id);
		$this->db->where("date(pb.created_date) >=", $start_date);
		$this->db->where("date(pb.created_date) <=", $end_date);
		$this->db->group_by("pb.partner_id");
		if (!is_null($from_cash)) {
			$this->db->where("pb.from_cash", $from_cash);
		}

		$query = $this->db->get("partners_bill pb");

		return $query->row_array();
	}

	public function get_partner_bills_by_date($partner_id, $start_date = null, $end_date = null, $from_cash = null)
	{
		$this->db->select("pb.id, pb.partner_id, pb.amount, pb.payment_type_id,
		p.last_name, p.first_name, 
		u.last_name as user_last_name, u.first_name as user_first_name, pb.created_date,
		pt.name as payment_type
		");
		$this->db->join("partners p", "p.id = pb.partner_id");
		$this->db->join("users u", "u.id = pb.user_id");
		$this->db->join("payment_types pt", "pt.id = pb.payment_type_id");
		$this->db->where("pb.partner_id", $partner_id);
		$this->db->where("date(pb.created_date) >=", $start_date);
		$this->db->where("date(pb.created_date) <=", $end_date);
		$this->db->order_by("pb.created_date", "desc");
		if (!is_null($from_cash)) {
			$this->db->where("pb.from_cash", $from_cash);
		}

		$query = $this->db->get("partners_bill pb");

		return $query->result_array();
	}

	public function get_partners_bills($start_date = null, $end_date = null, $from_cash = null)
	{
		$this->db->select("pb.id, pb.partner_id, pb.amount, pb.payment_type_id, pb.from_cash,
		p.last_name, p.first_name, 
		u.last_name as user_last_name, u.first_name as user_first_name, pb.created_date,
		pt.name as payment_type
		");
		$this->db->join("partners p", "p.id = pb.partner_id");
		$this->db->join("users u", "u.id = pb.user_id");
		$this->db->join("payment_types pt", "pt.id = pb.payment_type_id");
		$this->db->where("date(pb.created_date) >=", $start_date);
		$this->db->where("date(pb.created_date) <=", $end_date);
		$this->db->order_by("pb.created_date", "desc");
		if (!is_null($from_cash)) {
			$this->db->where("pb.from_cash", $from_cash);
		}

		$query = $this->db->get("partners_bill pb");

		return $query->result_array();

	}

	public function get_partners_bills_total($start_date = null, $end_date = null, $from_cash = null)
	{
		if(is_null($start_date) && is_null($end_date)) {
			$start_date = $end_date = date("Y-m-d");
		}

		$this->db->select_sum("pb.amount");
		$this->db->where("date(pb.created_date) >=", $start_date);
		$this->db->where("date(pb.created_date) <=", $end_date);
		$this->db->where("pb.from_cash", 1);
		if (!is_null($from_cash)) {
			$this->db->where("pb.from_cash", $from_cash);
		}

		$query = $this->db->get("partners_bill pb")->row_array();

		return $query['amount'];

	}

}
