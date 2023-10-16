<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_modules_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_service_modules() {
        $this->db->order_by("name");
        $result = $this->db->get("service_modules")->result_array();

        return $result;
    }

    public function get_service_module($id) {
		$this->db->where("id", $id);
        $result = $this->db->get("service_modules")->row_array();

        return $result;
    }

	public function add($arr)
	{
		$this->db->insert("service_modules", $arr);
		return $this->db->insert_id();
	}

	public function update($id, $arr)
	{
		$this->db->where("id", $id);
		$result = $this->db->update("service_modules", $arr);

		return $result;
	}

	public function delete($id)
	{
		$this->db->where("id", $id);
		return $this->db->delete("service_modules");
	}

	public function get_service_modules_array() {
		$this->db->order_by("name");
		$result = $this->db->get("service_modules")->result_array();

		$service_modules = array();
		foreach ($result as $service_module) {
			$service_modules[$service_module["id"]] = $service_module["name"];
		}

		return $service_modules;
	}

}
