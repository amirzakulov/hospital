<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laboratory_divisions_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_divisions()
	{
		$query 	= $this->db->get("laboratory_divisions");
		$result = $query->result_array();

		return $result;
	}

	public function get_division($id)
	{
		$query 	= $this->db->get_where("laboratory_divisions", array("id" => $id));
		$result = $query->row_array();

		return $result;
	}

	public function add($arr)
	{
		$this->db->insert("laboratory_divisions", $arr);

		return $this->db->insert_id();
	}

	public function update($id, $arr)
	{
		$this->db->where('id', $id);
		return $this->db->update("laboratory_divisions", $arr);
	}

	public function delete($id)
	{
		$this->db->where("id", $id);
		return $this->db->delete("laboratory_divisions");
	}


}
