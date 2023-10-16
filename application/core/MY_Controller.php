<?php

class MY_Controller extends CI_Controller {

    protected $data = array();
    function __construct() {
        parent::__construct();
        $this->data['title']        = 'Hospital ZM';
        $this->data['before_head']  = '';
        $this->data['before_body']  = '';
        $this->data['before_themeStyle'] = '';
        $this->data['before_appjs'] = '';
    }

    protected function render($the_view = NULL, $template = 'master'){

//        if($template == 'json' || $this->input->is_ajax_request()) {
        if($template == 'json') {
            header('Content-Type: application/json');
            echo json_encode($this->data);
        } elseif(is_null($template)) {
            $this->load->view($the_view,$this->data);
        } else {
            $this->data['view_content'] = (is_null($the_view)) ? '' : $this->load->view($the_view, $this->data, TRUE);
            $this->load->view('templates/'.$template.'_view', $this->data);
        }
    }
}

class Admin_Controller extends MY_Controller
{
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');

        if (!$this->ion_auth->logged_in()) {
            redirect('admin/login', 'refresh');
        }

        if (!$this->ion_auth->in_group(array(1,3))) {
            show_error(lang("user_permission_denied"). ": <a href='".site_url("doctor")."'>ортга қайтиш</a>") or die();

        }

        $this->data["title"] = "Hospital ZM";
    }

    protected function render($the_view = NULL, $template = 'admin_master') {
        parent::render($the_view, $template);
    }
}

class Doctor_Controller extends MY_Controller
{
    function __construct() {
        parent::__construct();

        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in()) {
            redirect('doctor/login', 'refresh');
        }

        if (!$this->ion_auth->in_group(array(4, 7, 9))) {
            show_error(lang("user_permission_denied"). " <a href='".site_url("admin")."'>админкага қайтиш</a>");
            die(lang("user_permission_denied"));
        }


        $this->data["title"] = "Hospital ZM | Doctor Panel";

    }

    protected function render($the_view = NULL, $template = 'doctor_master') {
        parent::render($the_view, $template);
    }
}

class Public_Controller extends MY_Controller
{
    function __construct() {
        parent::__construct();
    }
}
