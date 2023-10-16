<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
    }

    public function get_employees() {
        $this->db->select("e.id, u.last_name, u.first_name, u.surname, u.dob, u.username, u.email, u.phone, u.photo, e.user_id, e.job_title_id, e.is_doctor, 
        dt.name as doctor_type, dt.description as doctor_type_description, jt.name as job_title, u.created_date");
        $this->db->join("users u", "u.id = e.user_id");
        $this->db->join("doctors_types_link dtl", "dtl.employee_id = e.id", "left");
        $this->db->join("doctors_types dt", "dt.id = dtl.doctor_type_id", "left");
        $this->db->join("job_titles jt", "jt.id = e.job_title_id", "left");
        $result = $this->db->get("employees e")->result_array();

        return $result;
    }

    public function get_employee($id) {
        $this->db->select("e.id, u.last_name, u.first_name, u.surname, u.dob, u.username, u.email, u.phone, u.photo, u.address, u.description, u.gender, e.user_id, e.job_title_id, e.is_doctor, 
        dt.name as doctor_type, dt.description as doctor_type_description, jt.name as job_title, u.created_date, u.region_id, r.name as region_name, u.city_id, c.name as city_name,
        ug.group_id, e.department_id");
        $this->db->join("users u", "u.id = e.user_id");
        $this->db->join("users_groups ug", "ug.user_id = u.id", "left");
        $this->db->join("doctors_types_link dtl", "dtl.employee_id = e.id", "left");
        $this->db->join("doctors_types dt", "dt.id = dtl.doctor_type_id", "left");
        $this->db->join("job_titles jt", "jt.id = e.job_title_id", "left");
        $this->db->join("regions r", "r.id = u.region_id", "left");
        $this->db->join("cities c", "c.id = u.city_id", "left");
        $this->db->where("e.id", $id);

        $result = $this->db->get("employees e")->row_array();

        return $result;
    }

    public function add($arr) {
        $this->db->insert("employees", $arr);

        return $this->db->insert_id();
    }

    public function update($id, $arr)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("employees", $arr);

        return $result;
    }

    public function get_user_id($id)
    {
        $this->db->where("id", $id);
        $employee = $this->db->get("employees")->row_array();

        return $employee["user_id"];
    }

    public function delete($id)
    {
        $this->db->where("id", $id);
        return $this->db->delete("employees");
    }

    public function get_employee_id($user_id)
    {
        $this->db->where("user_id", $user_id);
        $employee = $this->db->get("employees")->row_array();

        return $employee;
    }

    public function get_max_id()
    {
        $this->db->select_max('id');
        $query = $this->db->get('employees')->row_array();

        return $query["id"];
    }
}