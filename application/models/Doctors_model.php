<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctors_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_doctors() {
        $this->db->select("d.id, u.id as user_id, u.last_name, u.first_name, u.surname, u.dob, u.address, u.phone, u.email, u.username, u.active,
        u.region_id, r.name as region_name, u.city_id, c.name as city_name, u.gender, u.description, u.photo, dtl.price, dtl.agreement, 
        dt.id as department_id, dt.name as department_name,
        j.name as job_title, j.description as job_title_description, ug.group_id");
        $this->db->join("users u", "u.id = d.user_id", "left");
        $this->db->join("users_groups ug", "ug.user_id = u.id", "left");
//        $this->db->join("users_groups ug", "ug.group_id = ".$this->config->item("groups")["doctors"], "left");
        $this->db->join("doctors_types_link dtl", "dtl.employee_id = d.id", "left");
        $this->db->join("doctors_types dt", "dt.id = dtl.doctor_type_id", "left");
        $this->db->join("job_titles j", "j.id = d.job_title_id", "left");
        $this->db->join("regions r", "r.id = u.region_id");
        $this->db->join("cities c", "c.id = u.city_id");
        $this->db->where_in("ug.group_id", 4);
        $result = $this->db->get("employees d")->result_array();

        return $result;
    }

    public function get_doctor($employee_id) {
        $this->db->select("d.id, u.id as user_id, u.last_name, u.first_name, u.surname, u.dob, u.address, u.phone, u.email, u.username, u.active,
        u.region_id, r.name as region_name, u.city_id, c.name as city_name, u.gender, u.description, u.photo, dtl.price, dtl.agreement, dt.id as doctor_type_id, dt.name as doctor_type_name,
        j.name as job_title, j.description as job_title_description, dtl.id as doctors_types_link_id, ug.group_id, d.department_id");
        $this->db->join("users u", "u.id = d.user_id", "left");
        $this->db->join("users_groups ug", "ug.user_id = u.id", "left");
        $this->db->join("doctors_types_link dtl", "dtl.employee_id = d.id", "left");
        $this->db->join("doctors_types dt", "dt.id = dtl.doctor_type_id", "left");
        $this->db->join("job_titles j", "j.id = d.job_title_id", "left");
        $this->db->join("regions r", "r.id = u.region_id");
        $this->db->join("cities c", "c.id = u.city_id");
        $this->db->where("d.id", $employee_id);
        $result = $this->db->get("employees d")->row_array();

        return $result;
    }

    public function get_max_id()
    {
        $this->db->select_max('id');
        $query = $this->db->get('employees')->row_array();

        return $query["id"];
    }

    /**
     *  shifokorni boshqa bulimga boglanganligini tekshiramiz
     * $employee_id = $doctor_id
     * */
    public function check_links($employee_id)
    {
        $this->db->where('doctor_id', $employee_id);
        return $this->db->get("patient_doctor")->num_rows();
    }

    /***
     * Duxtirlarning ulushlari
     * @param $start_date
     * @return
     */
    public function doctors_shares($start_date, $end_date) {

        $query = $this->db->query("
                                    SELECT e.id as doctor_id, u.last_name, u.first_name, u.first_name, dtl.price, dtl.agreement
                                    FROM `employees` e
                                    LEFT JOIN users u ON u.id = e.user_id
                                    LEFT JOIN doctors_types_link dtl ON dtl.employee_id = e.id
                                    WHERE e.is_doctor = 1
                                    GROUP BY e.id
                              ");

        $doctors = $query->result_array();

        foreach ($doctors as $key => $doctor) {
            $doctors[$key]["debt"] = $this->oy_boshi_qarz($doctor["doctor_id"], $start_date);
            $doctors[$key]["earning"] = $this->doctor_earning($doctor["doctor_id"], $start_date, $end_date);
        }

        return $doctors;
    }

    public function doctor_earning($doctor_id, $start_date, $end_date) {
        $query = $this->db->query("
                                SELECT SUM(dtl.price) as total, ROUND((SUM(dtl.price) * (dtl.agreement/100))) as dShareSum, b.amount as paid_sum
                                FROM patient_doctor pd
                                LEFT JOIN doctors_types_link dtl ON dtl.employee_id = pd.doctor_id
                                LEFT JOIN patients_payments pp ON pp.id = pd.payment_id
                                LEFT JOIN (
                                    SELECT b.doctor_id, SUM(b.amount) as amount 
                                    FROM doctors_bill b 
                                    WHERE b.created_date >= '".$start_date."' AND b.created_date <= '".$end_date."'
                                    GROUP BY b.doctor_id
                                ) as b ON b.doctor_id = pd.doctor_id
                                WHERE pd.doctor_id = ".$doctor_id." AND date(pp.created_date) >= '".$start_date."' AND date(pp.created_date) <= '".$end_date."'
                                GROUP BY pd.doctor_id
                  ");

        return $query->row_array();
    }

    public function oy_boshi_qarz($doctor_id, $end_date = '2020-09-01')
    {
        $start_date = date("Y-01-01", strtotime($end_date));
        $query = $this->db->query("
                                SELECT t.doctor_id, (t.dShareSum - c.amount) as debt
                                FROM (
                                    SELECT pd.doctor_id, SUM(dtl.price) as total, ROUND((SUM(dtl.price) * (dtl.agreement/100))) as dShareSum
                                    FROM patient_doctor pd
                                    LEFT JOIN doctors_types_link dtl ON dtl.employee_id = pd.doctor_id
                                    LEFT JOIN patients_payments pp ON pp.id = pd.payment_id
                                    WHERE pd.doctor_id = ".$doctor_id." AND date(pp.created_date) >= '".$start_date."' AND date(pp.created_date) < '".$end_date."'
                                    GROUP BY pd.doctor_id
                                ) t
                                LEFT JOIN (
                                    SELECT e.id as doctor_id, (CASE WHEN SUM(bd.amount) IS NULL THEN 0 ELSE SUM(bd.amount) END) as amount
                                    FROM employees e 
                                    LEFT JOIN doctors_bill bd ON bd.doctor_id = e.id
                                    WHERE bd.doctor_id = ".$doctor_id." AND date(bd.created_date) >= '".$start_date."' AND date(bd.created_date) < '".$end_date."'
                                ) c ON t.doctor_id = c.doctor_id
                  ");

        $res = $query->row_array();
        $debt = 0;
        $debt = $res["debt"] > 0 ?$res["debt"] : $debt;
        return $debt;
    }

    public function doctor_cash($doctor_id, $start_date, $end_date) {
        $query = $this->db->query("
                        SELECT pd.doctor_id, pd.patient_id, pd.payment_id, pd.`status`, dtl.price, dtl.agreement as percent, pp.created_date as payment_date
                        FROM patient_doctor pd
                        LEFT JOIN doctors_types_link dtl ON dtl.employee_id = pd.doctor_id
                        LEFT JOIN patients_payments pp ON pp.id = pd.payment_id
                        WHERE pd.doctor_id = ".$doctor_id." AND date(pd.created_date) >= '".$start_date."' AND date(pd.created_date) <= '".$end_date."'
        ");

        return $query->result_array();
    }

    public function get_doctors_all() {
        $this->db->select("d.id, u.id as user_id, u.last_name, u.first_name, u.surname, u.dob, u.address, u.phone, u.email, u.username, u.active,
        u.region_id, r.name as region_name, u.city_id, c.name as city_name, u.gender, u.description, u.photo, dtl.price, dtl.agreement, 
        dt.id as department_id, dt.name as department_name,
        j.name as job_title, j.description as job_title_description, ug.group_id");
        $this->db->join("users u", "u.id = d.user_id", "left");
        $this->db->join("users_groups ug", "ug.user_id = u.id", "left");
//        $this->db->join("users_groups ug", "ug.group_id = ".$this->config->item("groups")["doctors"], "left");
        $this->db->join("doctors_types_link dtl", "dtl.employee_id = d.id", "left");
        $this->db->join("doctors_types dt", "dt.id = dtl.doctor_type_id", "left");
        $this->db->join("job_titles j", "j.id = d.job_title_id", "left");
        $this->db->join("regions r", "r.id = u.region_id");
        $this->db->join("cities c", "c.id = u.city_id");
        $this->db->where_in("ug.group_id", $this->config->item("doctor_groups_id"));
        $result = $this->db->get("employees d")->result_array();

        return $result;
    }

	public function get_doctors_by_type($doctor_group_id)
	{
		$this->db->select("d.id, u.id as user_id, u.last_name, u.first_name, u.surname, u.dob, u.address, u.phone, u.email, u.username, u.active,
        u.region_id, r.name as region_name, u.city_id, c.name as city_name, u.gender, u.description, u.photo, dtl.price, dtl.agreement, 
        dt.id as department_id, dt.name as department_name,
        j.name as job_title, j.description as job_title_description, ug.group_id");
		$this->db->join("users u", "u.id = d.user_id", "left");
		$this->db->join("users_groups ug", "ug.user_id = u.id", "left");
		$this->db->join("doctors_types_link dtl", "dtl.employee_id = d.id", "left");
		$this->db->join("doctors_types dt", "dt.id = dtl.doctor_type_id", "left");
		$this->db->join("job_titles j", "j.id = d.job_title_id", "left");
		$this->db->join("regions r", "r.id = u.region_id");
		$this->db->join("cities c", "c.id = u.city_id");
		$this->db->where("ug.group_id", $doctor_group_id);
		$result = $this->db->get("employees d")->result_array();

		return $result;
    }


}
