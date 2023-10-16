<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rooms_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_rooms()
    {
        $this->db->order_by("r.sort", "asc");
        $this->db->select("r.*, t.name as rtype_name, t.conditions");
        $this->db->join("room_types t", "t.id = r.room_type_id", "left");
        $rooms = $this->db->get("rooms r")->result_array();

        foreach ($rooms as $index => $room) {
            $rooms[$index]["beds"] = Room_beds_model::get_beds($room["id"]);
        }

        return $rooms;
    }

    public function get_room($id)
    {
        $this->db->select("r.*, t.name as rtype_name, t.conditions");
        $this->db->join("room_types t", "t.id = r.room_type_id", "left");
        $this->db->where("r.id", $id);
        $query = $this->db->get("rooms r");

        return $query->row_array();
    }

    public function add($arr)
    {
        $this->db->insert("rooms", $arr);
        return $this->db->insert_id();
    }

    public function update($id, $arr)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("rooms", $arr);

        return $result;
    }



}
