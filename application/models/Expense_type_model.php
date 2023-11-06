<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense_type_model extends CI_Model
{
    public function __construct(){
        parent::__construct();
    }

	public function get_expense_types($active = null) {
		$this->db->order_by("name", "asc");
		if(!is_null($active)) {
			$this->db->where("active", $active);
		}
		$query = $this->db->get("expense_type");

		return $query->result_array();
	}

	public function get_expense_type($id) {
		$this->db->where("id", $id);
		$query = $this->db->get("expense_type");

		return $query->row_array();
	}

	public function add($arr)
	{
		$this->db->insert("expense_type", $arr);
		return $this->db->insert_id();
	}

	public function update($id, $arr)
	{
		$this->db->where("id", $id);
		$result = $this->db->update("expense_type", $arr);

		return $result;
	}

	public function delete($id)
	{
		$this->db->where("id", $id);
		return $this->db->delete("expense_type");
	}

}
