<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Departments_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_departments($active = ""){
        if($active == "active") { $this->db->where("d.status", 1); }
        elseif($active == "inactive") { $this->db->where("d.status", 0); }

        $this->db->order_by("d.name", "asc");
        $this->db->select('d.*');
        $this->db->from('departments d');
//        $this->db->where('d.parent_id', 0);
        $parent = $this->db->get();

        $categories = $parent->result_array();
        foreach($categories as $i => $cat){//Categories with sub categories
            $categories[$i]["sub"] = $this->sub_departments($cat["id"]);
        }

        return $categories;
    }

    public function get_department($id)
    {
        $this->db->where("id", $id);
        $query = $this->db->get("departments");
        return $query->row_array();
    }

    public function add($arr)
    {
        $this->db->insert("departments", $arr);

        return $this->db->insert_id();
    }

    public function update($id, $arr)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("departments", $arr);

        return $result;
    }


    public function sub_departments($id) {

        $this->db->select('d.*');
        $this->db->from('departments d');
        $this->db->where('d.parent_id', $id);

        $child = $this->db->get();
        $categories = $child->result_array();
        $i=0;
        foreach($categories as $cat){
            $categories[$i]["sub"] = $this->sub_departments($cat["id"]);
            $i++;
        }
        return $categories;
    }
}