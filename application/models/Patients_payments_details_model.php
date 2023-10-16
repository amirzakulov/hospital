<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patients_payments_details_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function add($arr) {
        $this->db->insert("patients_payments_details", $arr);
        return $this->db->insert_id();
    }

    public function update($payment_id, $arr)
    {
    	$this->db->select("id");
    	$this->db->where("payment_id", $payment_id);
    	$this->db->limit(1);
    	$this->db->order_by("created_date", "asc");
    	$query = $this->db->get("patients_payments_details");
		$row = $query->row_array();

		$this->db->where("ppd.id", $row["id"]);
        $result = $this->db->update("patients_payments_details ppd", $arr);

        return $result;
    }

	public function get_payment_sum($payment_id)
	{
		$this->db->select_sum("ppd.paid");
		$this->db->where("ppd.payment_id", $payment_id);
		$query = $this->db->get("patients_payments_details ppd");

		return $query->row_array();
    }

	public function delete_by_payment_id($payment_id)
	{
		$this->db->where("payment_id", $payment_id);
		return $this->db->delete("patients_payments_details");
    }


}
