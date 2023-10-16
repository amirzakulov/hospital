<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('uniqe_code_genetrator'))
{
    function uniqe_code_genetrator($prefix, $last_id, $nulls = 8)
    {
        if(is_null($last_id)) {$last_id = 0;}

        $last_id = ++$last_id;

        $code = str_pad($last_id,   $nulls, '0', STR_PAD_LEFT);
        return $prefix.$code;
    }
}

if ( ! function_exists('max_id'))
{
    function max_id($db_table_name)
    {
        $CI =& get_instance();
        $CI->db->select_max('id');
        $query = $CI->db->get($db_table_name)->row_array();
        return $query["id"];
    }
}


if ( ! function_exists('phone_number_format'))
{
    function phone_number_format($number) {
        // Allow only Digits, remove all other characters.
        $number = preg_replace("/[^\d]/","",$number);

        // get number length.
        $length = strlen($number);

        // if number = 10
        if($length == 12) {
            $number = preg_replace("/^1?(\d{3})(\d{2})(\d{3})(\d{2})(\d{2})$/", "+$1($2) $3-$4-$5", $number);
        }

        if($length == 9) {
            $number = preg_replace("/^1?(\d{2})(\d{3})(\d{2})(\d{2})$/", "($1) $2-$3-$4", $number);
        }

        return $number;

    }
}

if ( ! function_exists('notification_text'))
{
    function notification_text($text) {

        $error_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<span class="fa fa-exclamation-circle"></span> '.$text.'
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>';

        return $error_message;

    }
}

if ( ! function_exists('money_formatting'))
{
	function money_formatting($number) {

		return number_format($number, 0, ".", " ");

	}
}

//if ( ! function_exists('date_format'))
//{
//	//number: 01.01.2021; mt: 01 Mart 2021; db:2021-01-01
//	function date_format($date, $format = 'number') {
//
//		switch ($format) {
//			case 'mt':
//				$date = date("d", strtotime($date))." ".date("F", strtotime($date))." ".date("Y", strtotime($date));
//				break;
//			case 'db':
//				$date = date("Y-m-d", strtotime($date));
//				break;
//			default:
//				$date = date("d.m.Y", strtotime($date));
//		}
//
//		return $date;
//
//	}
//}

if ( ! function_exists('date_formating'))
{
	//number: 01.01.2021;
    // mt: 01 Mart 2021;
    // dt: 01.01.2021 11:00;
    // db:2021-01-01
	function date_formating($time, $format = 'number') {
		$monthes = [
			'January' => 'Январь',
			'February' => 'Февраль',
			'March' => 'Март',
			'April' => 'Апрель',
			'May' => 'Май',
			'June' => 'Июнь',
			'July' => 'Июль',
			'August' => 'Август',
			'September' => 'Сентябрь',
			'October' => 'Октябрь',
			'November' => 'Ноябрь',
			'December' => 'Декабрь',
		];
		switch ($format) {
			case 'mt':
				$date = date("d", $time)." ".$monthes[date("F", $time)]." ".date("Y", $time);
				break;
			case 'db':
				$date = date("Y-m-d", $time);
				break;
            case 'db_datetime':
                $date = date("Y-m-d ", $time) . date("H:i:s");
                break;
            case 'dt':
                $date = date("d.m.Y H:i", $time);
                break;
			default:
				$date = date("d.m.Y", $time);
		}

		return $date;

	}
}

if ( ! function_exists('days_of_month'))
{
	function days_of_month() {

		$Ns = array(
			'01', '02', '03', '04', '05', '06', '07', '08', '09', '10',
			'11', '12', '13', '14', '15', '16', '17', '18', '19', '20',
			'21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'
		);

		$days = array();
		for ($n = 0; ; $n++) {
			if ($n >= 31) break;
			$days[$Ns[$n]] = $Ns[$n];
		}

		return $days;
	}
}

if ( ! function_exists('months_of_year'))
{
	function months_of_year($type = 'number') {
		$months = [
			'01' 	=> 'Январь',
			'02' 	=> 'Февраль',
			'03' 	=> 'Март',
			'04' 	=> 'Апрель',
			'05' 	=> 'Май',
			'06' 	=> 'Июнь',
			'07' 	=> 'Июль',
			'08'	=> 'Август',
			'09' 	=> 'Сентябрь',
			'10' 	=> 'Октябрь',
			'11' 	=> 'Ноябрь',
			'12' 	=> 'Декабрь',
		];

		return $months;
	}
}

if ( ! function_exists('get_years'))
{
	function get_years() {

		$years 	= array();
		$begin 	= 1900;
		$end	= intval(date("Y"));

		for ($i = $begin; $i <= $end; $i++) {
			$years[$i] = $i;
		}

		return $years;

	}
}

