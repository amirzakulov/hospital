<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cities_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_cities()
    {
        $query = $this->db->get("cities");

        return $query->result_array();
    }

    public function get_cities_array()
    {
        $query = $this->db->get("cities");

        $cities = array();
        foreach ($query->result_array() as $city) {
            $cities[$city["id"]] = $city["name"];
        }

        return $cities;
    }

    public function get_cities_by_region_id($region_id)
    {
        $query = $this->db->get_where("cities", array("region_id" => $region_id));

        $cities = array();
        foreach ($query->result_array() as $city) {
            $cities[$city["id"]] = $city["name"].($city["type"]==1?" (Шахар)":" (Туман)");
        }

        return $cities;
    }

    public function get_city($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get("cities");

        return $query->row_array();
    }

}