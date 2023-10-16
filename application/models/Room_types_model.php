<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_types_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_room_types()
    {
        $this->db->order_by("r.sort", "asc");
        $query = $this->db->get("room_types r");
        return $query->result_array();
    }

    public function get_room_type($id)
    {
        $this->db->where('r.id', $id);
        $query = $this->db->get("room_types r");
        return $query->row_array();
    }

    public function add($arr)
    {
        $this->db->insert("room_types", $arr);
        return $this->db->insert_id();
    }

    public function update($id, $arr)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("room_types", $arr);

        return $result;
    }

    public function delete($id)
    {
        $this->db->where("id", $id);
        return $this->db->delete("room_types");
    }

    public function room_type_list()
    {
        $this->db->order_by("r.sort", "asc");
        $query = $this->db->get("room_types r");

        $types = array();
        foreach ($query->result_array() as $type) {
            $types[$type["id"]] = $type["name"];
        }

        return $types;
    }


}