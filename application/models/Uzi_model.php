<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uzi_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /***********************************************
     * Uzi
     * *********************************************/
    public function get_uzis()
    {
        $query = $this->db->get("uzi");

        return $query->result_array();
    }

    public function get_uzi($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get("uzi");

        return $query->row_array();
    }

    public function add($arr)
    {
        $this->db->insert("uzi", $arr);

        return $this->db->insert_id();
    }

    public function update($id, $arr)
    {
        $this->db->where('id', $id);
        return $this->db->update("uzi", $arr);

    }

    public function delete($id)
    {
        $this->db->where("id", $id);
        return $this->db->delete("uzi");
    }

    public function check_links($id)
    {
        $this->db->where("uzi_id", $id);
        return $this->db->get("patient_uzi")->num_rows();
    }

}
