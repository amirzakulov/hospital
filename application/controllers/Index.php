<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Index extends Public_Controller
{
	public function __construct()
	{
		parent::__construct();
        // first of all we need to make sure we are in a development environment or at least that this controller can be seen only by your IP address (you'll have to replace XXX.XXX.XXX with your IP address, of course)
        if(ENVIRONMENT!=='development' || $_SERVER['REMOTE_ADDR']=='XXX.XXX.XXX')
        {
            $this->load->helper('url');
            redirect('/');
        }


        if($this->ion_auth->logged_in()) {
            if($this->ion_auth->is_admin()) {
                redirect('/admin');
            } else {
                redirect('/doctor');
            }
        }

	}
	
	public function index()
	{


        // now we load the view, passing the data to it
		$this->load->view('index');
	}
}
/* End of file 'Verify' */
/* Location: ./application/controllers/Verify.php */
