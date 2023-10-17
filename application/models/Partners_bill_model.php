<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partners_bill_model extends CI_Model
{
    public function __construct(){
        parent::__construct();
    }

    public function add($arr)
    {
        $this->db->insert("partners_bill", $arr);
        return $this->db->insert_id();
    }

//    public function delete($id)
//    {
//        $this->db->where("id", $id);
//        return $this->db->delete("partners");
//    }

	public function get_partners_bill($partner_id)
	{
		$this->db->select("pb.partner_id, pb.amount, pb.created_date, p.first_name, p.last_name");
		$this->db->join("partners p", "p.id = pb.partner_id");
		$this->db->where("partner_id", $partner_id);
		$this->db->order_by("pb.created_date", 'desc');
		$query = $this->db->get("partners_bill pb");

		return $query->result_array();

	}

	public function get_partner_bill_by_date($partner_id, $start_date = null, $end_date = null)
	{
		$this->db->select("pb.partner_id, SUM(pb.amount) as amount");
		$this->db->where("partner_id", $partner_id);
		$this->db->where("date(pb.created_date) >=", $start_date);
		$this->db->where("date(pb.created_date) <=", $end_date);
		$this->db->group_by("pb.partner_id");
		$query = $this->db->get("partners_bill pb");

		return $query->row_array();

	}

}
