<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Files_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function add($arr)
    {
        $this->db->insert("files", $arr);

        return $this->db->insert_id();
    }

    public function get_file($payment_id)
    {
        $query = $this->db->get_where("files", array("payment_id" => $payment_id));

        return $query->row_array();
    }

}