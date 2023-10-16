<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_types_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

	public function get_payment_types()
	{
		$query = $this->db->get("payment_types");

		$types = array();
		foreach ($query->result_array() as $type) {
			$types[$type["id"]] = $type["name"];
		}

		return $types;
	}

	public function get_payment_type($id)
	{
		$this->db->where("id", $id);
		$query = $this->db->get("payment_types");

		return $query->row_array();
	}

	public function add($arr)
	{
		$this->db->insert("payment_types", $arr);
		return $this->db->insert_id();
	}

	public function update($id, $arr)
	{
		$this->db->where("id", $id);
		$result = $this->db->update("payment_types", $arr);

		return $result;
	}

	public function delete($id)
	{
		$this->db->where("id", $id);
		return $this->db->delete("payment_types");
	}
}
