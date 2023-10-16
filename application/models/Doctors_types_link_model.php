<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctors_types_link_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  shifokorni bulimlarini uzgartirish
     * */
    public function update_doctor_type_link($id, $arr)
    {
        $this->db->where('id', $id);
        return $this->db->update("doctors_types_link", $arr);
    }

    /**
     *  shifokorni bulimlarga qushish
     * */
    public function assign_doctor_type($arr)
    {
        $this->db->insert("doctors_types_link", $arr);
        return $this->db->insert_id();
    }


    public function delete($doctor_id)
    {
        $this->db->where("employee_id", $doctor_id);
        return $this->db->delete("doctors_types_link");
    }

    public function check_links($doctor_type_id)
    {
        $this->db->where("id", $doctor_type_id);
        return $this->db->get("doctors_types_link")->num_rows();
    }
}