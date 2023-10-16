<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AjaxFunctions extends Admin_Controller {
    private  $user_id;


    function __construct()
    {
        parent::__construct();

    }

    public function sidebar_control() {
    	if($this->session->userdata("sidebar_opened")) {
			$this->session->set_userdata("sidebar_opened", false);
		} else {
			$this->session->set_userdata("sidebar_opened", true);
		}

	}
}
