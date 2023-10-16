<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laboratory_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /***********************************************
     * Berilgan id lar buyich laboratoriyalar ruyhati
     * *********************************************/
    public function get_laboratories($labs_id_arr)
    {
        $this->db->where_in('id', $labs_id_arr);
        $query = $this->db->get("laboratories l");
        $result = $query->result_array();

        return $result;
    }

    public function get_laboratory($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get("laboratories");

        return $query->row_array();
    }

    public function add($arr)
    {
        $this->db->insert("laboratories", $arr);

        return $this->db->insert_id();
    }

    public function update($id, $arr)
    {
        $this->db->where('id', $id);
        return $this->db->update("laboratories", $arr);
    }

    public function delete($id)
    {
        $this->db->where("id", $id);
        return $this->db->delete("laboratories");
    }

    public function get_max_id()
    {
        $this->db->select_max('id');
        $query = $this->db->get('laboratories')->row_array();

        return $query["id"];
    }

    public function check_links($lab_category_id)
    {
        $this->db->where("parent_id", $lab_category_id);
        return $this->db->get("laboratories")->num_rows();
    }

    public function get_categories($level = 0, $order_field = null, $order_type = "DESC"){

        $this->db->select('l.*, ld.name as division_name');
        if(is_null($order_field)){
            $this->db->order_by("l.sort, l.name");
        } else {
            $this->db->order_by("l.".$order_field." ".$order_type);
        }
        $this->db->from('laboratories l');
        $this->db->join('laboratory_divisions ld', "ld.id = l.lab_division_id", "left");
        $this->db->where('l.parent_id', 0);

        $parent = $this->db->get();

        $categories = $parent->result_array();
        //Categories only
        if($level == 1) {
            return $categories;
        } else {
        //Categories with sub categories
            $i=0;
            foreach($categories as $p_cat){

                $categories[$i]["sub"] = $this->sub_categories($p_cat["id"]);
                $i++;
            }
        }

        return $categories;
    }

    public function sub_categories($id) {

        $this->db->select('l.*, p.first_name, p.last_name, p.company');
        $this->db->from('laboratories l');
        $this->db->order_by('l.sort');
        $this->db->where('l.parent_id', $id);
        $this->db->join('partners p', "p.id = l.partner_id", "left");

        $child = $this->db->get();
        $categories = $child->result_array();
        $i=0;
        foreach($categories as $p_cat){
            $categories[$i]["sub"] = $this->sub_categories($p_cat["id"]);
            $i++;
        }
        return $categories;
    }


}
