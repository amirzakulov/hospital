<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Print_scripts extends Admin_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library(array("WebClientPrint"));
    }

    public function index() {

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        ';

        $webClientPrintControllerAbsoluteURL = site_url('/WebClientPrintController');
        $printESCPOSControllerAbsoluteURL = site_url('/WebClientPrintController/PrintESCPOS');

        $this->data["wcppDetectionScript"] = WebClientPrint::createWcppDetectionScript($webClientPrintControllerAbsoluteURL, session_id());

        $this->data['wcppScript'] = WebClientPrint::createScript($webClientPrintControllerAbsoluteURL, $printESCPOSControllerAbsoluteURL, session_id());

        $this->render("admin/print_script/print_test_view");
    }

}
