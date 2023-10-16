<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /********************
     * Qaysi bulimga qancha bemor kelganligi
     * */
    public function patients_by_departments($start_date, $end_date)
    {
        $query = $this->db->query("
                SELECT d.id, d.`name` as department, count(pd.id) as count
                FROM `departments` d
                LEFT JOIN employees e on e.department_id = d.id
                LEFT JOIN patient_doctor pd on pd.doctor_id = e.id AND pd.created_date >= '".$start_date."' AND pd.created_date <= '".$end_date."'
                LEFT JOIN patients_payments pp on pp.id = pd.payment_id and pp.created_date IS NOT NULL
                where d.`status` = 1 and d.parent_id = 3  
                GROUP BY d.id
        ");


        return $query->result_array();
    }

    /********************
     * Kassaga tushgan pullar
     * */
    public function show_cash($start_date, $end_date)
    {
        $query = $this->db->query("
            SELECT sum(ppd.paid) as paid, SUM(pp.total - (ppd.paid + pp.discount)) as debt, SUM(pp.total) as total, pp.payment_type
            FROM patients_payments pp
            LEFT JOIN (
                SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
                FROM patients_payments_details ppd
                GROUP BY ppd.payment_id
            ) ppd ON ppd.payment_id = pp.id
            WHERE pp.created_date >= '".$start_date."' AND pp.created_date <= '".$end_date."'
            GROUP BY pp.payment_type
        ");


        return $query->result_array();
    }

    /**
     * kun buyicha tushum
     * @return int total_sum
     */
    public function get_total_income_by_date($start_date = null, $end_date = null) {

        $query = $this->db->query("
            (
                SELECT IFNULL(SUM(pp.total),0) as total, IFNULL(pp.payment_type,1) AS payment_type
                FROM patients_payments pp
                WHERE pp.created_date >= '".$start_date."' AND pp.created_date <= '".$end_date."' AND pp.payment_type = 1
            ) UNION (
                SELECT IFNULL(SUM(pp.total),0) as total, IFNULL(pp.payment_type,2) AS payment_type
                FROM patients_payments pp
                WHERE pp.created_date >= '".$start_date."' AND pp.created_date <= '".$end_date."' AND pp.payment_type = 2
            )
        ");

        return $query->result_array();

    }

    /********************
     * Malum vaqt oraligida doctorlarga tulangan pullarning umumiy hisobi
     * @param $start_date
     * @param $end_date
     * @return int
     */
    public function doctors_income_total($start_date, $end_date)
    {
        $query = $this->db->query("
            (
                SELECT IFNULL(SUM(b.amount),0) as total, IFNULL(b.payment_type,1) AS payment_type
                FROM `doctors_bill` b
                WHERE b.created_date >= '".$start_date."' AND b.created_date <= '".$end_date."' AND b.payment_type = 1
            ) UNION (
                SELECT IFNULL(SUM(b.amount),0) as total, IFNULL(b.payment_type,2) AS payment_type
                FROM `doctors_bill` b
                WHERE b.created_date >= '".$start_date."' AND b.created_date <= '".$end_date."' AND b.payment_type = 2
            )
        ");


        return $query->result_array();
    }

    /********************
     * Malum vaqt oraligida har-hil narsalarga sarflangan pullar xisobi
     * @param $start_date
     * @param $end_date
     * @return int
     */
    public function other_expenses_total($start_date, $end_date)
    {
        $query = $this->db->query("
            (
                SELECT IFNULL(SUM(e.amount),0) as total, IFNULL(e.payment_type,1) AS payment_type
                FROM expenses e
                WHERE e.created_date >= '".$start_date."' AND e.created_date <= '".$end_date."' AND e.payment_type = 1
            ) UNION (
                SELECT IFNULL(SUM(e.amount),0) as total, IFNULL(e.payment_type, 2) AS payment_type
                FROM expenses e
                WHERE e.created_date >= '".$start_date."' AND e.created_date <= '".$end_date."' AND e.payment_type = 2
            )
        ");

        return $query->result_array();
    }


	public function laboratory_total($start_date, $end_date)
	{
		$query = $this->db->query("
            SELECT pl.payment_id, l.name, l.price, l.price_partner, pp.partner_id, p.agreement AS partner_agreement, pp.created_date
			FROM patients_payments AS pp
			LEFT JOIN patient_laboratories pl ON pl.payment_id = pp.id
			LEFT JOIN laboratories l ON l.id = pl.lab_id
			LEFT JOIN partners p ON p.id = pp.partner_id
			WHERE DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.laboratory_status > 0 AND pl.is_parent = 1
        ");

		$total = array('total' => 0, 'share' => 0, 'partner' => 0);
		foreach ($query->result_array() as $laboratory) {
			$total["total"] += $laboratory["price"];

			$price_partner = $laboratory["price_partner"] ?: 0;
			$total["share"] += $price_partner;
			$total["partner"] += ($laboratory["price"] * $laboratory["partner_agreement"]) / 100 ;
		}

		return $total;

    }

	public function uzi_total($start_date, $end_date)
	{
		$query = $this->db->query("
            SELECT pu.payment_id, u.name, u.price, pp.partner_id, p.agreement AS partner_agreement, pp.created_date,
			(SELECT dtl.agreement FROM doctors_types_link dtl WHERE dtl.employee_id = 5) AS doctor_agreement
			FROM patients_payments AS pp
			LEFT JOIN patient_uzi pu ON pu.payment_id = pp.id
			LEFT JOIN uzi u ON u.id = pu.uzi_id
			LEFT JOIN partners p ON p.id = pp.partner_id
			WHERE DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.uzi_status > 0 AND pu.is_conclusion = 0
        ");

		$total = array('total' => 0, 'share' => 0, 'partner' => 0);
		foreach ($query->result_array() as $uzi) {
			$total["total"] += $uzi["price"];

			$doctor_agreement = $uzi["doctor_agreement"] ?: 0;
			$partner_agreement = $uzi["partner_agreement"] ?: 0;
			$total["share"] += ($uzi["price"] * $doctor_agreement) / 100;
			$total["partner"] += ($uzi["price"] * $partner_agreement) / 100; ;
		}

		return $total;

    }

	public function services_total($start_date, $end_date)
	{
		$query = $this->db->query("
            SELECT ps.payment_id, s.name, s.price, ps.count, pp.partner_id, p.agreement AS partner_agreement, pp.created_date
			FROM patients_payments AS pp                                                                                   
			LEFT JOIN patient_service ps ON ps.payment_id = pp.id                                                          
			LEFT JOIN services s ON s.id = ps.service_id                                                                   
			LEFT JOIN partners p ON p.id = pp.partner_id                                                                   
			WHERE DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.service_status > 0
        ");

		$total = array('total' => 0, 'share' => 0, 'partner' => 0);
		foreach ($query->result_array() as $service) {
			$cost = $service["price"] * $service["count"];
			$total["total"] += $cost;

			$partner_agreement = $service["partner_agreement"] ?: 0;
			$total["partner"] += ($cost * $partner_agreement) / 100; ;
		}

		return $total;

	}

	public function rooms_total($start_date, $end_date)
	{
		$query = $this->db->query("
            SELECT pr.payment_id, pr.bed_id, DATEDIFF(pr.end_date, pr.start_date) AS count, rb.name, rb.price, pp.partner_id, p.agreement AS partner_agreement, pp.created_date
			FROM patients_payments AS pp
			LEFT JOIN patient_room pr ON pr.payment_id = pp.id
			LEFT JOIN room_beds rb ON rb.id = pr.bed_id
			LEFT JOIN partners p ON p.id = pp.partner_id
			WHERE DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.room_status > 0
        ");

		$total = array('total' => 0, 'share' => 0, 'partner' => 0);
		foreach ($query->result_array() as $room) {
			$cost = $room["price"] * $room["count"];
			$total["total"] += $cost;

			$partner_agreement = $room["partner_agreement"] ?: 0;
			$total["partner"] += ($cost * $partner_agreement) / 100; ;
		}

		return $total;

	}

	public function doctors_total($start_date, $end_date)
	{
		$query = $this->db->query("
            SELECT pd.payment_id, pd.doctor_id, u.last_name, u.first_name, 
			dtl.price, dtl.agreement AS doctor_agreement, pp.partner_id, p.agreement AS partner_agreement, pp.created_date
			FROM patients_payments AS pp
			LEFT JOIN patient_doctor pd ON pd.payment_id = pp.id
			LEFT JOIN doctors_types_link dtl ON dtl.employee_id = pd.doctor_id
			LEFT JOIN employees e ON e.id = pd.doctor_id
			LEFT JOIN users u ON u.id = e.user_id
			LEFT JOIN partners p ON p.id = pp.partner_id
			WHERE DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.doctor_status > 0
        ");

		$doctors = [];
		foreach ($query->result_array() as $doctor) {
			if(!isset($doctors[$doctor["doctor_id"]])) {
				$doctors[$doctor["doctor_id"]] = [
					'name' => '',
					'total' => 0,
					'share' => 0,
					'partner' => 0
				];
			}

			$doctor_agreement = $doctor["doctor_agreement"] ?: 0;
			$partner_agreement = $doctor["partner_agreement"] ?: 0;

			$doctors[$doctor["doctor_id"]]["name"] = $doctor["last_name"] ." ". $doctor["first_name"];
			$doctors[$doctor["doctor_id"]]["total"] += $doctor["price"];
			$doctors[$doctor["doctor_id"]]["share"] += ($doctor["price"] * $doctor_agreement) / 100; ;
			$doctors[$doctor["doctor_id"]]["partner"] += ($doctor["price"] * $partner_agreement) / 100; ;
		}

		return $doctors;

	}


	//Xamkor buyicha
	public function partner_doctors_total($partner_id, $service_module_id, $start_date, $end_date)
	{
		$query = $this->db->query("
            SELECT pd.payment_id, pd.doctor_id, dtl.price, pp.partner_id, sm.share AS partner_agreement, pp.created_date
			FROM patients_payments AS pp
			LEFT JOIN patient_doctor pd ON pd.payment_id = pp.id
			LEFT JOIN doctors_types_link dtl ON dtl.employee_id = pd.doctor_id
			LEFT JOIN service_module_shares sm ON sm.partner_id = ".$partner_id." AND sm.service_module_id = ".$service_module_id." AND sm.partner_type = 1
			WHERE pp.partner_id = ".$partner_id." AND DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.doctor_status > 0
        ");

		$total = array('total' => 0, 'partner_share' => 0, 'partner_total' => 0);
		foreach ($query->result_array() as $doctor) {

			$total["partner_share"]  = $doctor["partner_agreement"];
			$total["total"] 		+= $doctor["price"];
			$total["partner_total"] += ($doctor["price"] * $doctor["partner_agreement"]) / 100 ;
		}

		return $total;

	}

	public function partner_laboratory_total($partner_id, $service_module_id, $start_date, $end_date)
	{
		$query = $this->db->query("
            SELECT pl.payment_id, l.name, l.price, l.price_partner, pp.partner_id, sm.share AS partner_agreement, pp.created_date
			FROM patients_payments AS pp
			LEFT JOIN patient_laboratories pl ON pl.payment_id = pp.id
			LEFT JOIN laboratories l ON l.id = pl.lab_id
			LEFT JOIN service_module_shares sm ON sm.partner_id = ".$partner_id." AND sm.service_module_id = ".$service_module_id." AND sm.partner_type = 1
			WHERE pp.partner_id = ".$partner_id." AND DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.laboratory_status > 0 AND pl.is_parent = 1
        ");

		$total = array('total' => 0, 'partner_share' => 0, 'partner_total' => 0);
		foreach ($query->result_array() as $laboratory) {

			$total["partner_share"] = $laboratory["partner_agreement"];
			$total["total"] += $laboratory["price"];
			$total["partner_total"] += ($laboratory["price"] * $laboratory["partner_agreement"]) / 100 ;
		}

		return $total;

	}

	public function partner_uzi_total($partner_id, $service_module_id, $start_date, $end_date)
	{
		$query = $this->db->query("
            SELECT pu.payment_id, u.name, u.price, pp.partner_id, sm.share AS partner_agreement, pp.created_date,
			(SELECT dtl.agreement FROM doctors_types_link dtl WHERE dtl.employee_id = 5) AS doctor_agreement
			FROM patients_payments AS pp
			LEFT JOIN patient_uzi pu ON pu.payment_id = pp.id
			LEFT JOIN uzi u ON u.id = pu.uzi_id
			LEFT JOIN service_module_shares sm ON sm.partner_id = ".$partner_id." AND sm.service_module_id = ".$service_module_id." AND sm.partner_type = 1
			WHERE pp.partner_id = ".$partner_id." AND DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.uzi_status > 0 AND pu.is_conclusion = 0
        ");

		$total = array('total' => 0, 'partner_share' => 0, 'partner_total' => 0);
		foreach ($query->result_array() as $uzi) {
			$total["partner_share"] = $uzi["partner_agreement"];
			$total["total"] += $uzi["price"];
			$total["partner_total"] += ($uzi["price"] * $uzi["partner_agreement"]) / 100 ;
		}

		return $total;

	}

	public function partner_services_total($partner_id, $service_module_id, $start_date, $end_date)
	{
		$query = $this->db->query("
            SELECT ps.payment_id, s.name, s.price, ps.count, pp.partner_id, sm.share AS partner_agreement, pp.created_date
			FROM patients_payments AS pp                                                                                   
			LEFT JOIN patient_service ps ON ps.payment_id = pp.id                                                          
			LEFT JOIN services s ON s.id = ps.service_id                                                                   
			LEFT JOIN service_module_shares sm ON sm.partner_id = ".$partner_id." AND sm.service_module_id = ".$service_module_id." AND sm.partner_type = 1
			WHERE pp.partner_id = ".$partner_id." AND DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.service_status > 0
			AND s.id NOT IN (3,4)
        ");

		$total = array('total' => 0, 'partner_share' => 0, 'partner_total' => 0);
		foreach ($query->result_array() as $service) {
			$partner_agreement = $service["partner_agreement"] ?: 0;
			$total["partner_share"] = $partner_agreement;
			$cost = $service["price"] * $service["count"];
			$total["total"] += $cost;

			$total["partner_total"] += ($cost * $partner_agreement) / 100; ;
		}

		return $total;
	}

	public function partner_holter_total($partner_id, $service_module_id, $start_date, $end_date)
	{
		$query = $this->db->query("
            SELECT ps.payment_id, s.name, s.price, ps.count, pp.partner_id, sm.share AS partner_agreement, pp.created_date
			FROM patients_payments AS pp                                                                                   
			LEFT JOIN patient_service ps ON ps.payment_id = pp.id                                                          
			LEFT JOIN services s ON s.id = ps.service_id                                                                   
			LEFT JOIN service_module_shares sm ON sm.partner_id = ".$partner_id." AND sm.service_module_id = ".$service_module_id." AND sm.partner_type = 1
			WHERE pp.partner_id = ".$partner_id." AND DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.service_status > 0
			AND s.id = 4
        ");

		$total = array('total' => 0, 'partner_share' => 0, 'partner_total' => 0);
		foreach ($query->result_array() as $service) {
			$partner_agreement = $service["partner_agreement"] ?: 0;
			$total["partner_share"] = $partner_agreement;
			$cost = $service["price"] * $service["count"];
			$total["total"] += $cost;

			$total["partner_total"] += ($cost * $partner_agreement) / 100; ;
		}

		return $total;

	}

	public function partner_ekg_total($partner_id, $service_module_id, $start_date, $end_date)
	{
		$query = $this->db->query("
            SELECT ps.payment_id, s.name, s.price, ps.count, pp.partner_id, sm.share AS partner_agreement, pp.created_date
			FROM patients_payments AS pp                                                                                   
			LEFT JOIN patient_service ps ON ps.payment_id = pp.id                                                          
			LEFT JOIN services s ON s.id = ps.service_id                                                                   
			LEFT JOIN service_module_shares sm ON sm.partner_id = ".$partner_id." AND sm.service_module_id = ".$service_module_id." AND sm.partner_type = 1
			WHERE pp.partner_id = ".$partner_id." AND DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.service_status > 0
			AND s.id = 3
        ");

		$total = array('total' => 0, 'partner_share' => 0, 'partner_total' => 0);
		foreach ($query->result_array() as $service) {
			$partner_agreement = $service["partner_agreement"] ?: 0;
			$total["partner_share"] = $partner_agreement;
			$cost = $service["price"] * $service["count"];
			$total["total"] += $cost;

			$total["partner_total"] += ($cost * $partner_agreement) / 100; ;
		}

		return $total;

	}


	//Shifokorlar buyicha
	public function doctors_laboratory_total($partner_id, $service_module_id, $start_date, $end_date)
	{
		$query = $this->db->query("
            SELECT pl.payment_id, l.name, l.price, l.price_partner, pp.doctor_id, sm.share AS partner_agreement, pp.created_date
			FROM patients_payments AS pp
			LEFT JOIN patient_laboratories pl ON pl.payment_id = pp.id
			LEFT JOIN laboratories l ON l.id = pl.lab_id
			LEFT JOIN employees p ON p.id = pp.doctor_id
			LEFT JOIN service_module_shares sm ON sm.partner_id = ".$partner_id." AND sm.service_module_id = ".$service_module_id." AND sm.partner_type = 2
			WHERE pp.doctor_id = ".$partner_id." AND DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.laboratory_status > 0 AND pl.is_parent = 1
        ");

		$total = array('total' => 0, 'partner_share' => 0, 'partner_total' => 0);
		foreach ($query->result_array() as $laboratory) {

			$total["partner_share"] = $laboratory["partner_agreement"];
			$total["total"] += $laboratory["price"];
			$total["partner_total"] += ($laboratory["price"] * $laboratory["partner_agreement"]) / 100 ;
		}

		return $total;
	}

	public function doctors_uzi_total($partner_id, $service_module_id, $start_date, $end_date)
	{
		$query = $this->db->query("
            SELECT pu.payment_id, u.name, u.price, pp.doctor_id, sm.share AS partner_agreement, pp.created_date,
			(SELECT dtl.agreement FROM doctors_types_link dtl WHERE dtl.employee_id = 5) AS doctor_agreement
			FROM patients_payments AS pp
			LEFT JOIN patient_uzi pu ON pu.payment_id = pp.id
			LEFT JOIN uzi u ON u.id = pu.uzi_id
			LEFT JOIN employees p ON p.id = pp.doctor_id
			LEFT JOIN service_module_shares sm ON sm.partner_id = ".$partner_id." AND sm.service_module_id = ".$service_module_id." AND sm.partner_type = 2
			WHERE pp.doctor_id = ".$partner_id." AND DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.uzi_status > 0 AND pu.is_conclusion = 0
        ");

		$total = array('total' => 0, 'partner_share' => 0, 'partner_total' => 0);
		foreach ($query->result_array() as $uzi) {
			$total["partner_share"] = $uzi["partner_agreement"];
			$total["total"] += $uzi["price"];
			$total["partner_total"] += ($uzi["price"] * $uzi["partner_agreement"]) / 100 ;
		}

		return $total;

	}

	public function doctors_services_total($partner_id, $service_module_id, $start_date, $end_date)
	{
		$query = $this->db->query("
            SELECT ps.payment_id, s.name, s.price, ps.count, pp.doctor_id, sm.share AS partner_agreement, pp.created_date
			FROM patients_payments AS pp                                                                                   
			LEFT JOIN patient_service ps ON ps.payment_id = pp.id                                                          
			LEFT JOIN services s ON s.id = ps.service_id                                                                   
			LEFT JOIN service_module_shares sm ON sm.partner_id = ".$partner_id." AND sm.service_module_id = ".$service_module_id." AND sm.partner_type = 2
			WHERE pp.doctor_id = ".$partner_id." AND DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.service_status > 0
			AND s.id NOT IN (3,4)
        ");

		$total = array('total' => 0, 'partner_share' => 0, 'partner_total' => 0);
		foreach ($query->result_array() as $service) {
			$partner_agreement = $service["partner_agreement"] ?: 0;
			$total["partner_share"] = $partner_agreement;
			$cost = $service["price"] * $service["count"];
			$total["total"] += $cost;

			$total["partner_total"] += ($cost * $partner_agreement) / 100; ;
		}

		return $total;
	}

	public function doctors_holter_total($partner_id, $service_module_id, $start_date, $end_date)
	{
		$query = $this->db->query("
            SELECT ps.payment_id, s.name, s.price, ps.count, pp.doctor_id, sm.share AS partner_agreement, pp.created_date
			FROM patients_payments AS pp                                                                                   
			LEFT JOIN patient_service ps ON ps.payment_id = pp.id                                                          
			LEFT JOIN services s ON s.id = ps.service_id                                                                   
			LEFT JOIN service_module_shares sm ON sm.partner_id = ".$partner_id." AND sm.service_module_id = ".$service_module_id." AND sm.partner_type = 2
			WHERE pp.doctor_id = ".$partner_id." AND DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.service_status > 0
			AND s.id = 4
        ");

		$total = array('total' => 0, 'partner_share' => 0, 'partner_total' => 0);
		foreach ($query->result_array() as $service) {
			$partner_agreement = $service["partner_agreement"] ?: 0;
			$total["partner_share"] = $partner_agreement;
			$cost = $service["price"] * $service["count"];
			$total["total"] += $cost;

			$total["partner_total"] += ($cost * $partner_agreement) / 100; ;
		}

		return $total;

	}

	public function doctors_ekg_total($partner_id, $service_module_id, $start_date, $end_date)
	{
		$query = $this->db->query("
            SELECT ps.payment_id, s.name, s.price, ps.count, pp.doctor_id, sm.share AS partner_agreement, pp.created_date
			FROM patients_payments AS pp                                                                                   
			LEFT JOIN patient_service ps ON ps.payment_id = pp.id                                                          
			LEFT JOIN services s ON s.id = ps.service_id                                                                   
			LEFT JOIN service_module_shares sm ON sm.partner_id = ".$partner_id." AND sm.service_module_id = ".$service_module_id." AND sm.partner_type = 2
			WHERE pp.doctor_id = ".$partner_id." AND DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.service_status > 0
			AND s.id = 3
        ");

		$total = array('total' => 0, 'partner_share' => 0, 'partner_total' => 0);
		foreach ($query->result_array() as $service) {
			$partner_agreement = $service["partner_agreement"] ?: 0;
			$total["partner_share"] = $partner_agreement;
			$cost = $service["price"] * $service["count"];
			$total["total"] += $cost;

			$total["partner_total"] += ($cost * $partner_agreement) / 100; ;
		}

		return $total;

	}

	public function doctors_rooms_total($partner_id, $service_module_id, $start_date, $end_date)
	{
		$query = $this->db->query("
            SELECT pr.payment_id, pr.bed_id, DATEDIFF(pr.end_date, pr.start_date) AS count, rb.name, rb.price, pp.doctor_id, sm.share AS partner_agreement, pp.created_date
			FROM patients_payments AS pp
			LEFT JOIN patient_room pr ON pr.payment_id = pp.id
			LEFT JOIN room_beds rb ON rb.id = pr.bed_id
			LEFT JOIN service_module_shares sm ON sm.partner_id = ".$partner_id." AND sm.service_module_id = ".$service_module_id." AND sm.partner_type = 2
			WHERE pp.doctor_id = ".$partner_id." AND DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.room_status > 0
        ");


		$total = array('total' => 0, 'partner_share' => 0, 'partner_total' => 0);
		foreach ($query->result_array() as $room) {
			$partner_agreement = $room["partner_agreement"] ?: 0;
			$total["partner_share"] = $partner_agreement;
			$cost = $room["price"] * $room["count"];
			$total["total"] += $cost;

			$total["partner_total"] += ($cost * $partner_agreement) / 100; ;
		}


		return $total;

	}

	public function doctors_consultation_total($doctor_id, $service_module_id, $start_date, $end_date)
	{
		$query = $this->db->query("
			SELECT pd.payment_id, dtl.price, pd.doctor_id, sm.share AS partner_agreement, pp.created_date
			FROM patients_payments AS pp
			LEFT JOIN patient_doctor pd ON pd.payment_id = pp.id
			LEFT JOIN doctors_types_link dtl ON dtl.employee_id = pd.doctor_id
			
			LEFT JOIN service_module_shares sm ON sm.partner_id = ".$doctor_id." AND sm.service_module_id = ".$service_module_id." AND sm.partner_type = 2
			WHERE pd.doctor_id = ".$doctor_id." AND DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.doctor_status > 0
        ");


		$total = array('total' => 0, 'partner_share' => 0, 'partner_total' => 0);
		foreach ($query->result_array() as $doctor) {
			$partner_agreement = $doctor["partner_agreement"] ?: 0;
			$total["partner_share"] = $partner_agreement;

			$total["total"] += $doctor["price"];

			$total["partner_total"] += ($doctor["price"] * $partner_agreement) / 100;
		}

		return $total;

	}

	public function uzi_consultation_total($doctor_id, $start_date, $end_date)
	{
		$query = $this->db->query("
			SELECT pu.payment_id, uz.price, dtl.agreement AS partner_agreement, pp.created_date, u.last_name, u.first_name
			FROM patients_payments AS pp
			LEFT JOIN patient_uzi pu ON pu.payment_id = pp.id
			LEFT JOIN uzi uz ON uz.id = pu.uzi_id
			LEFT JOIN employees e ON e.id = ".$doctor_id."
			LEFT JOIN users u ON u.id = e.user_id
			LEFT JOIN doctors_types_link dtl ON dtl.employee_id = ".$doctor_id."
			WHERE DATE(pp.created_date) >= '".$start_date."' AND DATE(pp.created_date) <= '".$end_date."' AND pp.uzi_status > 0 AND pu.uzi_id IS NOT NULL
        ");


		$total = array('total' => 0, 'partner_share' => 0, 'partner_total' => 0);
		foreach ($query->result_array() as $uzi) {
			$partner_agreement = $uzi["partner_agreement"] ?: 0;
			$total["partner_share"] = $partner_agreement;

			$total["total"] += $uzi["price"];

			$total["partner_total"] += ($uzi["price"] * $partner_agreement) / 100;
		}

		return $total;

	}

}
