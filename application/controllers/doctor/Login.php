<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');

        if($this->ion_auth->logged_in()) {
            redirect('doctor', 'refresh');
        }

    }

    public function index()
    {
        $this->data['title'] = 'Login';
        if($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('identity', 'Identity', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules('remember','Remember me','integer');
            if($this->form_validation->run()===TRUE)  {
                $remember = (bool) $this->input->post('remember');
                if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {

                    if (!$this->ion_auth->in_group(array(4, 7, 9))) {
                        $this->ion_auth->logout();
                        $this->session->set_flashdata('message', lang("user_permission_denied"));
                    } else {
                        $this->load->model("employees_model");
                        $employee = $this->employees_model->get_employee_id($this->session->userdata("user_id"));
                        $this->session->set_userdata("employee_id", $employee["id"]);

                        if($this->ion_auth->in_group(7)) {
							redirect('doctor/patients_lab/dashboard', 'refresh');
						} elseif ($this->ion_auth->in_group(array(4, 9))) {
							redirect('doctor', 'refresh');
						}
                    }
                }
                else
                {
                    $this->session->set_flashdata('message',$this->ion_auth->errors());
//                    redirect('doctor/login', 'refresh');
					$this->data['identity'] = [
						'name' 	=> 'identity',
						'id' 	=> 'identity',
						'type' 	=> 'text',
						'value' => $this->form_validation->set_value('identity'),
						'class' => 'form-control',
						'required' => ''
					];
                }
            }
        } else {
			$this->data['identity'] = [
				'name' 	=> 'identity',
				'id' 	=> 'identity',
				'type' 	=> 'text',
				'value' => $this->form_validation->set_value('identity'),
				'class' => 'form-control',
			];
		}

        $this->load->helper('form');
        $this->render('doctor/login_view','doctor_master');
    }
}
