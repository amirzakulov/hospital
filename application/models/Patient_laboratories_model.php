<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_laboratories_model extends CI_Model
{

    private $ci;
    public function __construct() {
        parent::__construct();

        $this->ci =& get_instance();
    }

    public function get_patient_laboratory($id) {
        $this->db->where("pl.id", $id);
        $query = $this->db->get("patient_laboratories pl");

        return $query->row_array();
    }

    public function get_patient_laboratories($patient_id) {

        $this->db->select("pl.*, l.name, l.norma");
        $this->db->join("laboratories l", "l.id = pl.lab_id", "left");
        $this->db->where("pl.patient_id", $patient_id);
        $query = $this->db->get("patient_laboratories pl");

        return $query->result_array();
    }

    public function get_patient_laboratories_details($payment_id, $is_parent = null) {

        $this->db->select("pl.*, l.name, l.price, l.norma, l.parent_id, l.recommendation");
        $this->db->where(array("pl.payment_id" => $payment_id));
        if(!is_null($is_parent)) {
            $this->db->where(array("pl.is_parent" => $is_parent));
        }
        $this->db->join("laboratories l", "l.id = pl.lab_id", "left");
        $query = $this->db->get("patient_laboratories pl");

        $categories = $query->result_array();

        $active_labs_tree = $this->get_laboratories_tree($categories);

        return $active_labs_tree;
    }

	public function get_patient_labs($payment_id, $is_parent = null) {

		$this->db->select("pl.*, l.name, l.price, l.norma, l.default_value, l.template_id, l.parent_id, l.recommendation as recommendation_text");
		$this->db->where(array("pl.payment_id" => $payment_id));
		if(!is_null($is_parent)) {
			$this->db->where(array("pl.is_parent" => $is_parent));
		}
		$this->db->join("laboratories l", "l.id = pl.lab_id", "left");
		$query = $this->db->get("patient_laboratories pl");

		$categories = $query->result_array();
		return $categories;
	}

    private function get_laboratories_tree($laboratories) {
        $i=0;
        $labs = array();
        foreach($laboratories as $p_cat) {

            $labs[$i] = $this->parent_category($p_cat["lab_id"]);
            if(isset($labs[$i]["sub"]["sub"])) {
                $labs[$i]["sub"]["sub"] = $p_cat;
            } else {
                $labs[$i]["sub"] = $p_cat;
            }
            $i++;
        }

        $labs_tree = array();
        $tr_id = "";
        $tr_name = "";
        foreach ($labs as $tr) {
            if(isset($tr["id"])) {
                $tr_id = $tr["id"];
                $tr_name = $tr["name"];
            }
            $labs_tree[$tr_id]["name"] = $tr_name;

            $lev3 = array();
            if(isset($tr["sub"]["sub"])) {
                $labs_tree[$tr_id]["sub"][$tr["sub"]["id"]]["name"] = $tr["sub"]["name"];
                $labs_tree[$tr_id]["sub"][$tr["sub"]["id"]]["price"] = $tr["sub"]["price"];
                $lev3 = $tr["sub"]["sub"];
                unset($tr["sub"]["sub"]);
                $labs_tree[$tr_id]["sub"][$tr["sub"]["id"]]["sub"][$lev3["id"]] = $lev3;
            } else {
                $labs_tree[$tr_id]["sub"][$tr["sub"]["id"]] = $tr["sub"];
            }
        }

        return $labs_tree;
    }


    public function parent_category($lab_id) {

        $this->db->select('*');
        $this->db->from('laboratories');
        $this->db->where('id', $lab_id);
        $query = $this->db->get();

        $child = $query->row_array();

        if($child["parent_id"] != 0) {
            $parent = $this->parent_category($child["parent_id"]);
            if(!isset($parent["sub"])) {
                $parent["sub"] = $child;
            } else {
                $parent["sub"]["sub"] = $child;
            }
        } else {
            $parent = $child;
        }

        return $parent;
    }

    public function get_patient($payment_id)
    {
        $this->db->select(
            "pl.*, u.id as user_id, u.last_name, u.first_name, u.surname, u.dob, u.address, u.phone, u.email, u.username, u.active, u.occupation,
            u.region_id, u.city_id, u.gender, u.description,
            r.name as region_name, c.name as city_name,
            ppd.paid, pp.discount, pp.discount_type, pp.discount_value, pp.total, pp.status as payment_status, pp.order_status, pp.laboratory_status, pp.created_date as payment_date
        ");

        $this->db->group_by("pl.payment_id");
        $this->db->join("patients p", "p.id = pl.patient_id");
        $this->db->join("users u", "u.id = p.user_id");
        $this->db->join("regions r", "r.id = u.region_id");
        $this->db->join("cities c", "c.id = u.city_id");
        $this->db->join("patients_payments pp", "pp.id = pl.payment_id");
        $this->db->join("(
            SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
            FROM patients_payments_details ppd
            GROUP BY ppd.payment_id
        ) ppd", "ppd.payment_id = pp.id", "left");

        $this->db->where(array("pl.payment_id" => $payment_id));
        return $this->db->get("patient_laboratories pl")->row_array();
    }

    public function add($arr){
        $result = $this->db->insert("patient_laboratories", $arr);
        return $this->db->insert_id();
    }

    public function update($id, $arr)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("patient_laboratories", $arr);

        return $result;
    }

    public function deleteAll($patient_id)
    {
        $this->db->where("patient_id", $patient_id);
        return $this->db->delete("patient_laboratories");
    }

    public function remove_laboratory($payment_id, $laboratory_id)
    {
        $this->db->where(array("payment_id" => $payment_id, "lab_id" => $laboratory_id));
        $res[] = $this->db->delete("patient_laboratories");

        if(count($this->sub_categories($laboratory_id)) > 0) {
            foreach ($this->sub_categories($laboratory_id) as $sub_lab) {
                $this->remove_laboratory($payment_id, $sub_lab["id"]);
            }
        }

        return $res;
    }

	private function sub_categories($lab_id) {
		$this->db->select('l.*, p.first_name, p.last_name, p.company');
		$this->db->from('laboratories l');
		$this->db->where('l.parent_id', $lab_id);
		$this->db->join('partners p', "p.id = l.partner_id", "left");

		$child = $this->db->get();
		$categories = $child->result_array();
		$i=0;
		foreach($categories as $p_cat){
			$categories[$i]["sub"] = $this->sub_categories($p_cat["id"]);
			$i++;
		}
		return $categories;
	}

	public function sub_laboratories_by_payment($payment_id, $laboratory_id) {
    	$this->db->order_by("l.sort");
		$this->db->select('pl.*, l.name, l.norma, l.mesurment, l.default_value, l.template_id, l.recommendation as recommendation_text');
		$this->db->where(array("pl.payment_id" => $payment_id, "pl.parent_id" => $laboratory_id));
		$this->db->join('laboratories l', "l.id = pl.lab_id", "left");
		$query = $this->db->get('patient_laboratories pl');
		$sub_laboratories = $query->result_array();

		$i=0;
		foreach($sub_laboratories as $sl){
			$sub_laboratories[$i]["sub"] = $this->sub_categories($sl["lab_id"]);
			$i++;
		}

		return $sub_laboratories;
	}

	private function sub_categories_results($payment_id, $lab_id) {
		$this->db->select('pl.*, l.name');
		$this->db->where(array('pl.payment_id' => $payment_id, 'pl.parent_id' => $lab_id));
		$this->db->join('laboratories l', "l.id = pl.lab_id", "left");
		$this->db->from('patient_laboratories pl');

		$child = $this->db->get();
		$categories = $child->result_array();
		$i=0;
		foreach($categories as $p_cat){
			$categories[$i]["sub"] = $this->sub_categories_results($payment_id, $p_cat["lab_id"]);
			$i++;
		}
		return $categories;
	}

	public function sub_laboratories_by_payment_results($payment_id, $laboratory_id) {
    	$this->db->order_by("l.sort");
		$this->db->select('pl.*, l.name, l.norma, l.mesurment, l.default_value, l.template_id, l.recommendation as recommendation_text');
		$this->db->where(array("pl.payment_id" => $payment_id, "pl.parent_id" => $laboratory_id));
		$this->db->join('laboratories l', "l.id = pl.lab_id", "left");
		$query = $this->db->get('patient_laboratories pl');
		$sub_laboratories = $query->result_array();

		$i=0;
		foreach($sub_laboratories as $sl){
			$sub_laboratories[$i]["sub"] = $this->sub_categories_results($payment_id, $sl["lab_id"]);
			$i++;
		}

		return $sub_laboratories;
	}

    /**
     * Bemorning tanlanmagan laboratoriyalarini uchurib tashlash
     *
     * @param $payment_id
     * @param $laboratories array
     * @return mixed
     */
	public function delete_not_selected($payment_id, $laboratories) {

		$current_labs = $this->get_laboratories_by_payment($payment_id);
		$res = true;

		$lab_ids = array();
		foreach ($laboratories as $laboratory) {
			$lab_ids[] = $laboratory["id"];
		}

		foreach ($current_labs as $current_lab) {
			if(!in_array($current_lab["lab_id"], $lab_ids)) {
				$res = $this->remove_laboratory($payment_id, $current_lab["lab_id"]);
			}
		}

		foreach ($laboratories as $laboratory) {
			$this->db->where("lab_id", $laboratory["id"]);
			$result = $this->db->update("patient_laboratories", array("count" => $laboratory["count"]));
		}

		return $res;
	}

    /**
     * Bemorning payment_id buyicha laboratoriyalarini uchurib tashlash
     *
     * @param $payment_id
     * @return mixed
     */
    public function delete_by_paymentId($payment_id) {
        $this->db->where("payment_id", $payment_id);
        $res = $this->db->delete("patient_laboratories");

        return $res;
    }

	/**
	 * Barcha bemorlarning ruyhati
	 * @return array
	 */
	public function get_all_patients()
	{
		$this->db->select(
			"pl.*, u.id as user_id, u.last_name, u.first_name, u.surname, u.dob, u.address, u.phone, u.email, u.username, u.active, u.occupation,
            u.region_id, u.city_id, u.gender, u.description,
            r.name as region_name, c.name as city_name, pp.partner_id,
            ppd.paid, pp.discount, pp.discount_type, pp.discount_value, pp.total, pp.status as payment_status, pp.created_date as payment_date
        ");

		$this->db->group_by("p.id");
		$this->db->join("laboratories l", "l.id = pl.lab_id", "left");
		$this->db->join("patients p", "p.id = pl.patient_id", "left");
		$this->db->join("users u", "u.id = p.user_id", "left");
		$this->db->join("regions r", "r.id = u.region_id", "left");
		$this->db->join("cities c", "c.id = u.city_id", "left");
		$this->db->join("patients_payments pp", "pp.id = pl.payment_id", "left");
		$this->db->join("(
            SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
            FROM patients_payments_details ppd
            GROUP BY ppd.payment_id
        ) ppd", "ppd.payment_id = pp.id", "left");

		return $this->db->get("patient_laboratories pl")->result_array();
	}

	/**
	 * Barcha bemorlarning soni
	 * @return array
	 */
	public function get_all_patients_count()
	{
		$this->db->group_by("pl.patient_id");
		$this->db->from("patient_laboratories pl");
		return $this->db->count_all_results();
	}

	/**
	 * Barcha bemorlarning soni qidiruv bilan
	 * @return array
	 */
	public function get_searched_patients($keyword, $count = false, $columnName = null, $columnSortOrder = "ASC", $row = 0, $rowperpage = 0)
	{
		$search_columns = array(
			"username" 		=> $keyword,
			"last_name" 	=> $keyword,
			"first_name" 	=> $keyword,
			"phone" 		=> $keyword,
		);

		$columnName = is_null($columnName) ? "last_name" : $columnName;

		$this->db->select("pl.*, u.id as user_id, u.last_name, u.first_name, u.phone, u.username, u.address, u.dob");
		$this->db->group_by("pl.patient_id");
		$this->db->order_by("u.".$columnName, $columnSortOrder);
		$this->db->join("patients p", "p.id = pl.patient_id", "left");
		$this->db->join("users u", "u.id = p.user_id", "left");
		$this->db->from("patient_laboratories pl");
		$this->db->or_like($search_columns);
		$this->db->limit($rowperpage, $row);


		if($count) {
			return $this->db->count_all_results();
		} else {
			return $this->db->get()->result_array();
		}

	}


	public function check_results_complition($payment_id) {
        $this->db->where(array("payment_id" => $payment_id, "status" => 0, "is_parent" => 0));
        $query = $this->db->get("patient_laboratories pl");

        if($query->num_rows() > 0) {
            return false;//tugatilmagan
        } else {
            return true;//tugatilgan
        }
    }

    private function get_patient_laboratories_details2($payment_id, $clinic) {

		$this->db->select("pl.*, l.name, l.price, l.norma, l.mesurment, l.parent_id, l.recommendation as recommendation_text, l.template_id, c.name as parent_name");
		$this->db->order_by("l.sort");
		$this->db->where("pl.payment_id", $payment_id);
		if(!$clinic) {$this->db->where("l.partner_id !=", null);}
		else {$this->db->where("l.partner_id", null);}
		$this->db->join("laboratories l", "l.id = pl.lab_id", "left");
		$this->db->join("laboratories c", "c.id = l.parent_id", "left");
		$query = $this->db->get("patient_laboratories pl");

        return $query->result_array();
    }

    public function lab_tree($payment_id, $clinic = true)
    {
        $pl = $this->get_patient_laboratories_details2($payment_id, $clinic);

        $laboratories = array();
        $sub_laboratories = array();
        foreach ($pl as $lab) {
            if($lab["is_parent"] == 1) {
                $laboratories[$lab["parent_id"]]["name"] = $lab["parent_name"];
                $laboratories[$lab["parent_id"]]["sub"][$lab["id"]] = $lab;
                $sub_laboratories[$lab["lab_id"]]["sub"][$lab["id"]] = $lab;
            } elseif($lab["is_parent"] == 2) {
                $sub_laboratories[$lab["parent_id"]]["sub"][$lab["id"]] = $lab;
            } else {
                $sub_laboratories[$lab["parent_id"]]["sub"][$lab["id"]] = $lab;
            }
        }

        $result = array("laboratories" => $laboratories, "sub_laboratories" => $sub_laboratories);
        return $result;
    }

    public function get_laboratories_by_payment($payment_id) {

        $this->db->select("pl.*, l.name");
        $this->db->join("laboratories l", "l.id = pl.lab_id", "left");
        $this->db->where(array("pl.payment_id" => $payment_id, "pl.is_parent" => 1));
        $query = $this->db->get("patient_laboratories pl");

        return $query->result_array();
    }

    public function get_laboratories_by_date ($start_date = null, $end_date = null) {
    	if(is_null($start_date) && is_null($end_date)) {
			$start_date = $end_date = date("Y-m-d");
		}

		$query = $this->db->query("
                            SELECT pp.id, pp.patient_id, pp.doctor_id, u.last_name, u.first_name, pp.partner_id, 
                            pa.last_name as partner_last_name, pa.first_name as partner_first_name,  
                            pp.created_date, pp.updated_date, pl.price, pl.price_partner, pl.count,
                            (CASE WHEN SUM(pdd.debt) IS NULL THEN 0 ELSE SUM(pdd.debt) END) as debt, 
                            (SUM(pl.price * pl.count) - (CASE WHEN SUM(pdd.debt) IS NULL THEN 0 ELSE SUM(pdd.debt) END)) as paid,
                            pp.doctor_id as sender_doctor_id, d.last_name as sender_doctor_last_name, d.first_name as sender_doctor_first_name
                            FROM `patients_payments` pp
                            LEFT JOIN (
                                SELECT ppd.payment_id, SUM(ppd.paid) as paid, SUM(ppd.by_cash) as by_cash, SUM(ppd.by_card) as by_card, SUM(ppd.by_bank) as by_bank
                                FROM patients_payments_details ppd 
                                GROUP BY ppd.payment_id
                            ) ppd ON ppd.payment_id = pp.id
                            LEFT JOIN (
                                SELECT pl.payment_id, pl.is_parent, sum(l.price) as price, sum(l.price_partner) as price_partner, pl.count
                                FROM `patient_laboratories` pl
                                LEFT JOIN laboratories l ON l.id = pl.lab_id
                                WHERE pl.is_parent = 1
                                GROUP BY pl.payment_id
                                ORDER BY pl.created_date
                            ) as pl ON pl.payment_id = pp.id
                            LEFT JOIN (
                                SELECT pdd.payment_id, SUM(pdd.amount)as debt
                                FROM `payments_debt_discount` pdd
                                WHERE pdd.service_type = 2
                                GROUP BY pdd.payment_id
                            ) pdd ON pdd.payment_id = pp.id
                            LEFT JOIN patients p ON p.id = pp.patient_id
                            LEFT JOIN users u ON u.id = p.user_id
                            LEFT JOIN partners pa ON pa.id = pp.partner_id
                            LEFT JOIN employees e ON e.id = pp.doctor_id
                            LEFT JOIN users d ON d.id = e.user_id
                            WHERE date(pp.created_date) >= '".$start_date."' AND date(pp.created_date) <= '".$end_date."' AND pp.laboratory_status > 0
                            GROUP BY pp.id
                            ORDER BY pp.created_date DESC
		");

		return $query->result_array();
	}

	public function get_lab_result_report($lab_division_id = null, $start_date = null, $end_date = null)
	{
		$division_where = " AND l.lab_division_id IS NOT NULL";
		if(!is_null($lab_division_id)) {
			$division_where = " AND l.lab_division_id = ".$lab_division_id;
		}

		$start_date = is_null($start_date) ? date("Y-m-d"):date("Y-m-d", strtotime($start_date));
		$end_date   = is_null($end_date) ? date("Y-m-d"):date("Y-m-d", strtotime($end_date));

		$query = $this->db->query("
				SELECT pl.patient_id, pl.lab_id, pl.payment_id, pl.`status`, pl.result, pl.is_parent, pl.created_date,
				l.lab_division_id, l.parent_id, l.partner_id, u.last_name, u.first_name, u.dob, ld.name as division_name, ld.sort as division_sort, c.`name` as category_name, c.sort as category_sort, 
				l.`name` as laboratory_name, l.sort as lab_sort
				
				FROM `patient_laboratories` pl
				LEFT JOIN laboratories l ON l.id = pl.lab_id AND l.partner_id IS NULL
				LEFT JOIN laboratories c ON c.id = l.parent_id AND l.partner_id IS NULL
				LEFT JOIN laboratory_divisions ld ON ld.id = l.lab_division_id
				LEFT JOIN patients p ON p.id = pl.patient_id
				LEFT JOIN users u ON u.id = p.user_id
				WHERE date(pl.created_date) >= '".$start_date."' AND date(pl.created_date) <= '".$end_date."' AND pl.is_parent = 1 ".$division_where."
				GROUP BY pl.lab_id
				ORDER BY ld.sort ASC, c.sort ASC, l.sort ASC
			");

		$laboratories = $query->result_array();


		$query = $this->db->query("
				SELECT pl.patient_id, pl.lab_id, pl.payment_id, pl.`status`, pl.result, pl.is_parent, pl.created_date,
				l.lab_division_id, l.parent_id, l.partner_id, u.last_name, u.first_name, u.dob, ld.name as division_name, ld.sort as division_sort, c.`name` as category_name, c.sort as category_sort, 
				l.`name` as laboratory_name, l.sort as lab_sort
				
				FROM `patient_laboratories` pl
				LEFT JOIN laboratories l ON l.id = pl.lab_id AND l.partner_id IS NULL
				LEFT JOIN laboratories c ON c.id = l.parent_id AND l.partner_id IS NULL
				LEFT JOIN laboratory_divisions ld ON ld.id = l.lab_division_id
				LEFT JOIN patients p ON p.id = pl.patient_id
				LEFT JOIN users u ON u.id = p.user_id
				WHERE date(pl.created_date) >= '".$start_date."' AND date(pl.created_date) <= '".$end_date."' AND pl.is_parent > 0 ".$division_where."
				ORDER BY ld.sort ASC, c.sort ASC, l.sort ASC, pl.payment_id ASC
			");

		$patients_laboratories = $query->result_array();

		$patients = array();
		$patient_laboratories = array();
		foreach ($patients_laboratories as $patient_labs) {
			$patients[$patient_labs["payment_id"]] = array(
				"patient_id" => $patient_labs["patient_id"],
				"patient_name" => $patient_labs["last_name"].' '.$patient_labs["first_name"],
				"patient_date" => $patient_labs["created_date"],
			);
			$patient_laboratories[$patient_labs["patient_id"]][$patient_labs["lab_id"]] = $patient_labs;
		}

		return array("patients" => $patients, "laboratories" => $laboratories, "patient_laboratories" => $patient_laboratories);

	}

}
