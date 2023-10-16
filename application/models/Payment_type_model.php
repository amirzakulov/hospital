<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_type_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_payment_types()
    {
        $query = $this->db->get("payment_types");

        return $query->result_array();
    }
}