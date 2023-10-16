<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Templates_uzi_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /***********************************************
     * Templates Uzi
     * *********************************************/
    public function get_templates($doctor_id = null)
    {
        if(!is_null($doctor_id)) {
            $this->db->where("doctor_id", $doctor_id);
        }

        $this->db->order_by("u.id", "asc");
        $this->db->select("tu.*, u.name");
        $this->db->join("uzi u", "u.id = tu.uzi_id");
        $query = $this->db->get("templates_uzi tu");

        return $query->result_array();
    }

    public function get_template($id)
    {
        $this->db->select("tu.*, u.name, u.name_ru");
        $this->db->join("uzi u", "u.id = tu.uzi_id");
        $this->db->where('tu.id', $id);
        $query = $this->db->get("templates_uzi tu");

        return $query->row_array();
    }

    public function add($arr)
    {
        $this->db->insert("templates_uzi", $arr);

        return $this->db->insert_id();
    }

	public function add_batch($arr)
    {
        $res = $this->db->insert_batch("templates_uzi", $arr);

        return $res;
    }

    public function update($id, $arr) {
        $this->db->where('id', $id);
        return $this->db->update("templates_uzi", $arr);

    }

	public function delete($id) {
		$this->db->where("id", $id);
		return $this->db->delete("templates_uzi");
	}

	public function delete_by_uzi_id($uzi_id) {
		$this->db->where("uzi_id", $uzi_id);
		return $this->db->delete("templates_uzi");
	}

    //
	public function delete_by_doctor_id($doctor_id) {
		$this->db->where("doctor_id", $doctor_id);
		return $this->db->delete("templates_uzi");
    }

    //Shifokor andozalarini uzi_id lar buyicha olamiz
	public function get_doctor_tempalates_by_ids($doctor_id, $templates_ids) {
		$this->db->select("tu.uzi_id, tu.template, tu.template_ru");
		$this->db->where("tu.doctor_id", $doctor_id);
		$this->db->where_in("tu.uzi_id", $templates_ids);
		$templates = $this->db->get("templates_uzi tu");

		$seleted_templates = array();
		foreach ($templates->result_array() as $template) {
			$seleted_templates[$template["uzi_id"]]["uz"] = $template["template"];
			$seleted_templates[$template["uzi_id"]]["ru"] = $template["template_ru"];
		}

		return $seleted_templates;
    }

    //$arr bemorga biriktirilgan uzilarning id lari
	public function get_templates_by_uzi_ids($uzi_ids)
	{
		$this->db->where_in("tu.uzi_id", $uzi_ids);
		$query = $this->db->get("templates_uzi tu");

		return $query->result_array();

    }

}
