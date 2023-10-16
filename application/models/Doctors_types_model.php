<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctors_types_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_doctors_types() {
        $this->db->order_by("name");
        $result = $this->db->get("doctors_types dt")->result_array();

        return $result;
    }

    public function get_doctors_type($id) {
        $this->db->where("id", $id);
        $result = $this->db->get("doctors_types dt")->row_array();

        return $result;
    }

    public function add($arr)
    {
        $this->db->insert("doctors_types", $arr);
        return $this->db->insert_id();
    }

    public function update($id, $arr)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("doctors_types", $arr);

        return $result;
    }

    public function delete($id)
    {
        $this->db->where("id", $id);
        return $this->db->delete("doctors_types");
    }
}