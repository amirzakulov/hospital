<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles_permissions extends Admin_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model(array());

    }

    public function index()
    {
        $this->data['title'] = 'Гурухлар ва уларнинг хуқуқлари';

        $role_id = $this->input->get("role_id");
        if(empty($role_id)) $role_id = 1;
        $result = $this->ion_auth->groups()->result_array();
        $roles  = array();
        foreach ($result as $res) {
            $roles[] = $res["id"];
        }

        if(!in_array($role_id, $roles)) show_404();

        $this->data["roles"] = $result;
        $this->data["role_id"] = $role_id;

        $this->render('admin/settings/roles_permissions_view');
        //$this->render(NULL, 'json'); ....if we want to render a json string. Also, if a request is made using ajax, we can simply do $this->render()
    }

}
