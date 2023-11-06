<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expenses_model extends CI_Model
{
    public function __construct(){
        parent::__construct();
    }

    /**
     * kun buyicha chiqim
     * @return int total_sum
     */
    public function get_expenses($start_date = false, $end_date = false, $expenses_list = false) {
        $start_date = !$start_date ? date("Y-m-d") : $start_date;
        $end_date   = !$end_date ? date("Y-m-d") : $end_date;

        if($expenses_list) {
            $this->db->select("e.*, u.last_name, u.first_name, t.name as expense_type");
            $this->db->order_by("e.created_date", "desc");
            $this->db->join("users u", "u.id = e.user_id");
			$this->db->join("expense_type t", "t.id = e.expense_type_id");
        } else {
            $this->db->select_sum("e.amount");
        }

        $this->db->where(array("date(e.created_date) >=" => $start_date, "date(e.created_date) <=" => $end_date));
        $query = $this->db->get("expenses e");

        return $expenses_list ? $query->result_array() : $query->row_array()["amount"];
    }

    public function get_expenses_by_type($start_date = null, $end_date = null)
    {
		if(is_null($start_date) && is_null($end_date)) {
			$start_date = $end_date = date("Y-m-d");
		}

        $this->db->select("e.expense_type_id, SUM(e.amount) as amount, t.name");
        $this->db->join("expense_type t", "t.id = e.expense_type_id");
		$this->db->where(array("date(e.created_date) >=" => $start_date, "date(e.created_date) <=" => $end_date));
		$this->db->group_by("e.expense_type_id");
        $result = $this->db->get("expenses e")->result_array();

        return $result;
    }

    public function get_expense($id)
    {
    	$this->db->select("e.*, pt.name as payment_type");
    	$this->db->join("payment_types pt", "pt.id = e.payment_type_id", "left");
		return $this->db->get_where("expenses e", array("e.id" => $id))->row_array();
    }

    public function update($id, $arr)
    {
        $this->db->where("id", $id);
        $this->db->update("expenses", $arr);

        return $this->get_expense($id);
    }

    public function add($arr)
    {
        $this->db->insert("expenses", $arr);
        return $this->db->insert_id();
    }

    public function delete($id)
    {
        $this->db->where("id", $id);
        return $this->db->delete("expenses");
    }

	public function get_expenses_payment_type($start_date = null, $end_date = null)
	{
		if(is_null($start_date) && is_null($end_date)) {
			$start_date = $end_date = date("Y-m-d");
		}

		$query = $this->db->query("
									SELECT SUM(CASE WHEN e.payment_type_id = 1 THEN e.amount END) as by_cash,
									SUM(CASE WHEN e.payment_type_id = 2 THEN e.amount END) as by_card,
									SUM(CASE WHEN e.payment_type_id = 3 THEN e.amount END) as by_bank
									FROM expenses e
									LEFT JOIN payment_types p ON p.id = e.payment_type_id
									WHERE DATE(e.created_date) >= '".$start_date."' AND DATE(e.created_date) <= '".$end_date."'
								");

		return $query->row_array();
	}


}
