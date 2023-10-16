<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rooms extends Doctor_Controller {

    private $doctor_id;
    function __construct()
    {
        parent::__construct();

        $this->load->model(array("rooms_model", "room_types_model", "room_beds_model"));
        $this->load->language("rooms");
        $this->doctor_id = $this->session->userdata("employee_id");
    }

    public function index() {
        $this->data['title'] = 'Хоналар';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                            ';

        $this->data["room_types"] = $this->room_types_model->room_type_list();
        $this->data["rooms"] = $this->rooms_model->get_rooms();

        $this->render('doctor/rooms/index_view');
    }

    public function beds($room_id)
    {
        if(is_null($room_id)) { redirect("doctor/rooms", 'refresh');}

        $this->data['title'] = 'Хонадаги ётоқлар';
        $this->data['before_themeStyle'] = '
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/dataTables.bootstrap4.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/select2.min.css").'">
        <link rel="stylesheet" type="text/css" href="'.site_url("assets/admin/css/bootstrap-datetimepicker.min.css").'">
        ';

        $this->data['before_appjs'] = '
                                        <script src="'.site_url("assets/admin/js/jquery.dataTables.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/dataTables.bootstrap4.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/select2.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/jquery.autocomplete.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/moment.min.js").'"></script>
                                        <script src="'.site_url("assets/admin/js/bootstrap-datetimepicker.min.js").'"></script>
                                            ';


        $this->mybreadcrumb->add('Хоналар', site_url("doctor/rooms"));
        $this->mybreadcrumb->add('Ётоқлар', "doctor/rooms/room_beds");
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        if(is_null($room_id)) {
            echo "Хонани танланг!";
            die();
        }

        $this->data["room"] = $this->rooms_model->get_room($room_id);
        $this->data["room_id"] = $room_id;
        $this->data["beds"] = Room_beds_model::get_beds($room_id);

        $this->render("doctor/rooms/beds_index_view");
    }

}
