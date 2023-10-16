<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends Admin_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model(array());
    }

    public function index()
    {
        $this->data['title'] = 'Ташкилот хақида';

        $this->render('admin/settings/company_view');
        //$this->render(NULL, 'json'); ....if we want to render a json string. Also, if a request is made using ajax, we can simply do $this->render()
    }

}
