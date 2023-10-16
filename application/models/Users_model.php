<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_max_id()
    {
        $this->db->select_max('id');
        $query = $this->db->get('users')->row_array();

        return $query["id"];
    }

    public function get_user($id)
    {
        $this->db->select("u.last_name, u.first_name, u.surname, u.email, u.phone, u.address, u.dob, u.region_id, u.city_id, u.gender,  r.name as region_name, c.name as city_name");
        $this->db->join("regions r", "r.id = u.region_id", "left");
        $this->db->join("cities c", "c.id = u.city_id", "left");
        $this->db->where("u.id", $id);

        $query = $this->db->get("users u");

        return $query->row_array();

    }




}