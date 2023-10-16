<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posprinters_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_printers() {
        $result = $this->db->get("pos_printers")->result_array();

        return $result;
    }

    public function get_posprinter_settings($id) {
    	$this->db->where("id", $id);
        $result = $this->db->get("pos_printers")->row_array();

        return $result;
    }


}
