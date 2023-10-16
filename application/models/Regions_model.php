<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regions_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_regions()
    {
        $query = $this->db->get("regions");

        return $query->result_array();
    }

    public function get_regions_array()
    {
        $query = $this->db->get("regions");

        $regions = array();
        foreach ($query->result_array() as $region) {
            $regions[$region["id"]] = $region["name"];
        }

        return $regions;

    }

    public function get_region($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get("regions");

        return $query->row_array();
    }

}