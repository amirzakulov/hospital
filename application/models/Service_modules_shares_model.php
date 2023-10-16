<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_modules_shares_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

	public function get_partner_service_modules($partner_id)
	{
		$this->db->where("partner_type", 1);
		$this->db->where("partner_id", $partner_id);

		return $this->db->get("service_module_shares")->result_array();
	}

	public function get_doctor_service_modules($doctor_id)
	{
		$this->db->where("partner_type", 2);
		$this->db->where("partner_id", $doctor_id);

		return $this->db->get("service_module_shares")->result_array();
	}

	public function add($arr)
	{
		$this->db->insert("service_module_shares", $arr);
		return $this->db->insert_id();
	}

	public function deleteByPartner($partner_id, $partner_type)
	{
		$this->db->where("partner_id", $partner_id);
		$this->db->where("partner_type", $partner_type);
		return $this->db->delete("service_module_shares");
	}
}
