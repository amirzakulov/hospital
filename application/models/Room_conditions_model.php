<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_conditions_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_room_conditions()
    {
        $this->db->order_by("r.title");
        $query = $this->db->get("room_conditions r");
        return $query->result_array();
    }

    public function get_room_condition($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get("room_conditions");
        return $query->row_array();
    }

    public function add($arr)
    {
        $this->db->insert("room_conditions", $arr);
        return $this->db->insert_id();
    }

    public function update($id, $arr)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("room_conditions", $arr);

        return $result;
    }

    public function delete($id)
    {
        $this->db->where("id", $id);
        return $this->db->delete("room_conditions");
    }
}