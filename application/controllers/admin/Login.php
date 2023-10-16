<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        if($this->ion_auth->logged_in()) {
            redirect('admin', 'refresh');
        }
    }

    public function index()
    {
        $this->data['title'] = 'Login';
        if($this->input->post())
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('identity', 'Identity', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules('remember','Remember me','integer');
            if($this->form_validation->run()===TRUE)  {
                $remember = (bool) $this->input->post('remember');
                if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                    if (!$this->ion_auth->in_group(array(1,3))) {
                        $this->ion_auth->logout();
                        $this->session->set_flashdata('message', lang("user_permission_denied"));
                    } else {
						$this->session->set_userdata("sidebar_opened", false);
                        redirect('admin', 'refresh');
                    }
                }
                else
                {
                    $this->session->set_flashdata('message',$this->ion_auth->errors());
                    redirect('admin/login', 'refresh');
                }
            }
        }

        $this->load->helper('form');
        $this->render('admin/login_view','admin_master');
    }

    public function logout()
    {
        $this->ion_auth->logout();
        redirect('admin/login', 'refresh');
    }
}
