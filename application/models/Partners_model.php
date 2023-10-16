<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partners_model extends CI_Model
{
    public function __construct(){
        parent::__construct();
    }

    public function get_partners()
    {
        $this->db->order_by("last_name", "asc");
        $result = $this->db->get("partners")->result_array();

        return $result;
    }

    public function get_partners_laboratory()
    {
        $this->db->order_by("company", "asc");
        $result = $this->db->get_where("partners", array("department"=>2))->result_array();

        $partners = array("" => "--Танлаш--");
        foreach ($result as $partner) {
            $partners[$partner["id"]] = $partner["company"];
        }

        return $partners;
    }

    public function get_partner($id)
    {
        $result = $this->db->get_where("partners", array("id" => $id))->row_array();

        return $result;
    }

    public function update($id, $arr)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("partners", $arr);

        return $result;
    }

    public function add($arr)
    {
        $this->db->insert("partners", $arr);
        return $this->db->insert_id();
    }

    public function delete($id)
    {
        $this->db->where("id", $id);
        return $this->db->delete("partners");
    }

	public function get_partners_share($start_date, $end_date)
	{
		$query = $this->db->query("
					SELECT pp.partner_id, p.last_name, p.first_name, p.company, (SUM(pp.total) - SUM(pp.discount)) as income, p.agreement, 
					       (((SUM(pp.total) - SUM(pp.discount)) * p.agreement) / 100) as amount, pb.amount as bill_amount
					FROM partners p
					LEFT JOIN patients_payments pp ON pp.partner_id = p.id
					LEFT JOIN (
						SELECT pb.partner_id, SUM(pb.amount) as amount
						FROM partners_bill pb
						GROUP BY pb.partner_id
					) as pb ON pb.partner_id = pp.partner_id
					WHERE pp.partner_id > 0
					GROUP BY pp.partner_id
		");

		return $query->result_array();
    }

	public function get_partners_share_by_month($date)
	{
		$month = date("m", strtotime($date));
		$query = $this->db->query("
					SELECT p.id, p.last_name, p.first_name, p.company, p.agreement, pb.bill_amount
					FROM partners p
					LEFT JOIN (
						SELECT   pb.partner_id, SUM(pb.amount) as bill_amount, pb.created_date
						from partners_bill pb 
						WHERE MONTH(pb.created_date) = '".$month."'
						GROUP BY pb.partner_id
					)pb ON pb.partner_id = p.id
		");

		$partners = array();
		foreach ($query->result_array() as $partner) {
			$partners[$partner["id"]] = $partner;
		}

		return $partners;
    }

	public function get_partner_share($partner_id)
	{
		$query = $this->db->query("
					SELECT pp.partner_id, p.last_name, p.first_name, (SUM(pp.total) - SUM(pp.discount)) as income, p.agreement, 
					       (((SUM(pp.total) - SUM(pp.discount)) * p.agreement) / 100) as amount, pb.amount as bill_amount
					FROM `patients_payments` pp
					LEFT JOIN partners p ON p.id = pp.partner_id
					LEFT JOIN (
						SELECT pb.partner_id, SUM(pb.amount) as amount
						FROM partners_bill pb
						GROUP BY pb.partner_id
					) as pb ON pb.partner_id = pp.partner_id
					WHERE pp.partner_id = ".$partner_id."
					GROUP BY pp.partner_id
		");

		return $query->row_array();
	}

    public function get_partner_share_details($partner_id, $start_date = null, $end_date = null) {
		if(is_null($start_date) && is_null($end_date)) {
			$start_date = $end_date = date("Y-m-d");
		}

		$query = $this->db->query("
			SELECT pp.id as payment_id, pp.partner_id, p.last_name as partner_last_name, p.first_name as partner_first_name, pp.total, pp.discount, (pp.total-pp.discount) as paid, p.agreement, (((pp.total - pp.discount) * p.agreement) / 100) as amount, 
			pp.patient_id, u.last_name as last_name, u.first_name as first_name, pp.created_date
			FROM `patients_payments` pp
			LEFT JOIN partners p ON p.id = pp.partner_id
			LEFT JOIN patients pa ON pa.id = pp.patient_id 
			LEFT JOIN users u ON u.id = pa.user_id
			WHERE pp.partner_id = ".$partner_id."
			ORDER BY pp.created_date DESC");


		return $query->result_array();
	}

}
