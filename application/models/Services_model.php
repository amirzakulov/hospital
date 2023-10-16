<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Services_model extends CI_Model
{
    public function __construct(){
        parent::__construct();
    }

    public function get_services()
    {
        $this->db->order_by("sort, name", "asc");
        $result = $this->db->get("services")->result_array();

        return $result;
    }

    public function get_service($id)
    {
        $result = $this->db->get_where("services", array("id" => $id))->row_array();

        return $result;
    }

    public function update($id, $arr)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("services", $arr);

        return $result;
    }

    public function add($arr)
    {
        $this->db->insert("services", $arr);
        return $this->db->insert_id();
    }

    public function delete($id)
    {
        $this->db->where("id", $id);
        return $this->db->delete("services");
    }


}