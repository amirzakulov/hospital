<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job_titles_model extends CI_Model
{
    public function __construct(){
        parent::__construct();
    }

    public function get_jobtitles()
    {
        $this->db->order_by("name", "asc");
        $result = $this->db->get("job_titles")->result_array();

        return $result;
    }

    public function get_jobtitle($id)
    {
        $result = $this->db->get_where("job_titles", array("id" => $id))->row_array();

        return $result;
    }

    public function update($id, $arr)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("job_titles", $arr);

        return $result;
    }

    public function add($arr)
    {
        $this->db->insert("job_titles", $arr);
        return $this->db->insert_id();
    }

    public function delete($id)
    {
        $this->db->where("id", $id);
        return $this->db->delete("job_titles");
    }

}