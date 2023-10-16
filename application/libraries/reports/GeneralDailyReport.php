<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class GeneralDailyReport
{
	protected $ci;

	protected $start_date;
	protected $end_date;

	/**
	 * GeneralDailyReport constructor.
	 * @param $start_date
	 * @param $end_date
	 */
	public function __construct($array)
	{
		$this->ci =& get_instance();

		$this->start_date = $array["start_date"];
		$this->end_date = $array["end_date"];
	}

	public function show()
	{
		$this->ci->load->model("reports_model");

		$data = array();

		$data["laboratory_total"]	= $this->ci->reports_model->laboratory_total($this->start_date, $this->end_date);
		$data["uzi_total"]			= $this->ci->reports_model->uzi_total($this->start_date, $this->end_date);
		$data["services_total"]		= $this->ci->reports_model->services_total($this->start_date, $this->end_date);
		$data["rooms_total"]		= $this->ci->reports_model->rooms_total($this->start_date, $this->end_date);
		$data["doctors_total"]		= $this->ci->reports_model->doctors_total($this->start_date, $this->end_date);

		return $data;
	}

}
