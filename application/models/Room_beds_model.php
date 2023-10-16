<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_beds_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add($arr)
    {
        $this->db->insert("room_beds", $arr);
        return $this->db->insert_id();
    }

    public static function get_beds($room_id = null) {
		$ci =& get_instance();
		$today = date("Y-m-d 11:00:00");
		$where = "";
		if(!is_null($room_id)) {
			$where = " WHERE b.room_id = ". $room_id ." ";
		}

		$query = $ci->db->query("
				SELECT `b`.*, `pp`.*
				FROM `room_beds` `b` 
				LEFT JOIN 
				(
					SELECT	pr.payment_id, pr.bed_id, pr.patient_id, pr.start_date, pr.end_date, pr.busy, pr.doctor_id, pp.total, ppd.paid, (pp.total - (ppd.paid + pp.discount)) as debt, 
					u.username, u.last_name, u.first_name, u.address, r.`name` as region_name, c.`name` as city_name, d.last_name as doctor_last_name, d.first_name as doctor_first_name
					FROM `patient_room` `pr` 
					LEFT JOIN `patients` `p` ON `p`.`id`=`pr`.`patient_id` 
					LEFT JOIN `users` `u` ON `u`.`id`=`p`.`user_id` 
					LEFT JOIN regions r ON r.id = u.region_id
                    LEFT JOIN cities c ON c.id = u.city_id
					LEFT JOIN `patients_payments` `pp` ON `pp`.`id`=`pr`.`payment_id` 
					LEFT JOIN (
						SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
						FROM `patients_payments_details` `ppd` 
						GROUP BY ppd.payment_id
					) as ppd ON `ppd`.`payment_id` = `pp`.`id` 
					LEFT JOIN employees e ON e.id = pr.doctor_id
					LEFT JOIN users d ON d.id = e.user_id 
					WHERE pr.end_date > '".($today)."' 
				) as pp ON pp.bed_id = b.id
				".$where."
				ORDER BY `b`.`id`		
		");

		return $query->result_array();

    }

    public function get_bed($id)
    {
        $this->db->where("b.id", $id);
        $query = $this->db->get("room_beds b");

        return $query->row_array();
    }

    public function update($id, $arr)
    {
        $this->db->where("b.id", $id);
        $result = $this->db->update("room_beds b", $arr);

        return $result;
    }

    public function delete($id)
    {
        $this->db->where("id", $id);
        return $this->db->delete("room_beds");
    }

    public function get_beds_patients() {
        $today = date("Y-m-d");
        $this->db->select("b.*, pr.busy, pr.start_date, pr.end_date, u.last_name, u.first_name, r.number");
        $this->db->order_by("b.id");
        $this->db->join("rooms r", "r.id = b.room_id", "left");
        $this->db->join("patient_room pr", "pr.bed_id=b.id AND pr.end_date >='". $today."'", "left");
        $this->db->join("patients p", "p.id=pr.patient_id", "left");
        $this->db->join("users u", "u.id=p.user_id", "left");
        $query = $this->db->get("room_beds b");

        return $query->result_array();
    }

	public function get_beds_by_id($beds_id_arr)
	{
		$this->db->where_in('b.id', $beds_id_arr);
		$query = $this->db->get("room_beds b");
		$result = $query->result_array();

		return $result;
	}

    public function get_rooms()
    {
        $this->db->order_by("r.sort");
        $this->db->select("r.*, t.name as rtype_name, t.conditions");
        $this->db->join("room_types t", "t.id = r.room_type_id", "left");
        $rooms = $this->db->get("rooms r")->result_array();

        foreach ($rooms as $index => $room) {
            $rooms[$index]["beds"] = self::get_beds($room["id"]);
        }

        return $rooms;
    }
}
