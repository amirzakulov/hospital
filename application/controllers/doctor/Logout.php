<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
    }

    public function Index()
    {
        $this->ion_auth->logout();
        redirect('doctor/login', 'refresh');
    }
}