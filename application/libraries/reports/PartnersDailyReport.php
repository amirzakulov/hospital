<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class PartnersDailyReport
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
	 * @param $partners
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
		$this->partners 	    = $array["partners"];

		$this->ci->load->model("reports_model");

		$partner_modules = array();

		if(count($this->partners)) {
			foreach ($this->partners as $partner) {
				foreach ($this->service_modules as $service_module_id => $service_module_name) {
					if($service_module_id == 1) {//doctor
						$partner_modules[$partner["id"]][$service_module_name]= $this->ci->reports_model->partner_doctors_total($partner["id"], $service_module_id, $this->start_date, $this->end_date);
					}
					if($service_module_id == 2) {//laboratoria
						$partner_modules[$partner["id"]][$service_module_name]= $this->ci->reports_model->partner_laboratory_total($partner["id"], $service_module_id, $this->start_date, $this->end_date);
					}
					if($service_module_id == 3) {//uzi
						$partner_modules[$partner["id"]][$service_module_name]= $this->ci->reports_model->partner_uzi_total($partner["id"], $service_module_id, $this->start_date, $this->end_date);
					}
					if($service_module_id == 4) { //Muolaja xizmati
						$partner_modules[$partner["id"]][$service_module_name]= $this->ci->reports_model->partner_services_total($partner["id"], $service_module_id, $this->start_date, $this->end_date);
					}
					if($service_module_id == 5) { //EKG
						$partner_modules[$partner["id"]][$service_module_name]= $this->ci->reports_model->partner_ekg_total($partner["id"], $service_module_id, $this->start_date, $this->end_date);
					}
					if($service_module_id == 6) { //Xolter
						$partner_modules[$partner["id"]][$service_module_name]= $this->ci->reports_model->partner_holter_total($partner["id"], $service_module_id, $this->start_date, $this->end_date);
					}

				}
			}
		}

		return $partner_modules;
	}
}
