<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class DoctorsReport
{
	protected $ci;

	protected $start_date;
	protected $end_date;
	protected $service_modules;
	protected $doctors;

	/**
	 * GeneralDailyReport constructor.
	 * @param $start_date
	 * @param $end_date
	 * @param $service_modules
	 * @param $doctors
	 */
	public function __construct()
	{
		$this->ci =& get_instance();
	}

	public function show($array)
	{
		$this->start_date 		= $array["start_date"];
		$this->end_date 		= $array["end_date"];
		$this->service_modules 	= $array["service_modules"];
		$this->doctors 	    	= $array["doctors"];

		$this->ci->load->model("reports_model");

		$sender_doctors_modules = array();
		foreach ($this->doctors as $doctor) {
			if($doctor["id"]){
				foreach ($this->service_modules as $service_module_id => $service_module_name) {
					if($service_module_id == 1) {//Konsultatsiya
						$sender_doctors_modules[$doctor["id"]][$service_module_name]= $this->ci->reports_model->doctors_consultation_total($doctor["id"], $service_module_id, $this->start_date, $this->end_date);
					}
					if($service_module_id == 2) {//laboratoria
						$sender_doctors_modules[$doctor["id"]][$service_module_name]= $this->ci->reports_model->doctors_laboratory_total($doctor["id"], $service_module_id, $this->start_date, $this->end_date);
					}
					if($service_module_id == 3) {//uzi
						$sender_doctors_modules[$doctor["id"]][$service_module_name]= $this->ci->reports_model->doctors_uzi_total($doctor["id"], $service_module_id, $this->start_date, $this->end_date);
					}
					if($service_module_id == 4) { //Muolaja xizmati
						$sender_doctors_modules[$doctor["id"]][$service_module_name]= $this->ci->reports_model->doctors_services_total($doctor["id"], $service_module_id, $this->start_date, $this->end_date);
					}
					if($service_module_id == 5) { //EKG
						$sender_doctors_modules[$doctor["id"]][$service_module_name]= $this->ci->reports_model->doctors_ekg_total($doctor["id"], $service_module_id, $this->start_date, $this->end_date);
					}
					if($service_module_id == 6) { //Xolter
						$sender_doctors_modules[$doctor["id"]][$service_module_name]= $this->ci->reports_model->doctors_holter_total($doctor["id"], $service_module_id, $this->start_date, $this->end_date);
					}
					if($service_module_id == 7) { //Yotoq
						$sender_doctors_modules[$doctor["id"]][$service_module_name]= $this->ci->reports_model->doctors_rooms_total($doctor["id"], $service_module_id, $this->start_date, $this->end_date);
					}
				}

				if($doctor["id"] == 5) {
					$sender_doctors_modules[$doctor["id"]]["УЗИ"]= $this->ci->reports_model->uzi_consultation_total($doctor["id"], $this->start_date, $this->end_date);
				}

			}
		}

		return $sender_doctors_modules;
	}
}
