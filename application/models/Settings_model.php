<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

	public function get_group_settings($group) {
		$this->db->select("s.*");
		$this->db->where(array("group" => $group));
		$query = $this->db->get("settings s");

		$print_details = array();
		foreach ($query->result_array() as $printer) {
			$print_details[$printer["name"]] = $printer["value"];
		}

		return $print_details;
	}

	public function get_posprinter_status() {
        $result = $this->db->get_where("settings", array("name" => "pos_printer_status"))->row_array();

        return $result["value"];
    }

	public function get_selected_posprinter()
	{
		$result = $this->db->get_where("settings", array("name" => "selected_pos_printer_id"))->row_array();

		return $result["value"];
    }

    public function update($id, $arr) {
        $this->db->where("id", $id);
        $result = $this->db->update("settings", $arr);

        return $result;
    }

//	public function get_lab_print()
//	{
//		$this->db->select("s.*");
//		$this->db->where(array("group" => "LBP"));
//		$query = $this->db->get("settings s");
//
//		$print_details = array();
//		foreach ($query->result_array() as $printer) {
//			$print_details[$printer["name"]] = $printer;
//		}
//
//		return $print_details;
//	}

	public function update_lab_print_details($clinic_details)
	{
		foreach ($clinic_details as $detail) {
			$this->db->where(array("name" => $detail["name"], "group" => "LBP"));
			$result = $this->db->update("settings", array("value" => $detail["value"]));
		}

		return $result;
	}


}
