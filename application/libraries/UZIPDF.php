<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'TCPDF/tcpdf.php';

// Extend the TCPDF class to create custom Header and Footer
class UZIPDF extends TCPDF {
	protected $clinic_data;
	protected $patient_data;
	protected $lang;

	public function setClinicData($txt){
		$this->clinic_data = $txt;
	}

	public function setPatientData($arr){
		$this->patient_data = $arr;
	}

	public function setLang($lang_id){
		$this->lang = $lang_id;
	}

    //Page header
    public function Header() {
        // Logo
		$image_file = K_PATH_IMAGES.'logo.png';
		$this->Image($image_file, 10, 5, '', 22, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $this->SetY(10);
        $this->SetFont('dejavusans', '', 9);
		$this->setCellHeightRatio(1.5);
		$this->writeHTMLCell(203, 0, 1, 5, $this->clinic_data, 0, 1, 0, true, 'R', false);

		$patient_data 		= $this->patient_data;
		$lang_arr			= array(
			1 => array(
				"name" 			=> "Фамилияси Исми",
				"created_date" 	=> "Рўйхатга олинди",
				"dob" 			=> "Туғилган сана",
				"printed" 		=> "Чоп этилди",
				"phone" 		=> "Телефон",
			),
			2 => array(
				"name" 			=> "Фамилия Имя",
				"created_date" 	=> "Дата регистрации",
				"dob" 			=> "Дата рождения",
				"printed" 		=> "Дата печати",
				"phone" 		=> "Телефон",
			)
		);
		$user_details_html 	= <<<EOD
		<table cellspacing="0" cellpadding="3" border="1">
			<tr style="background-color:#e3e0e0; font-weight: bold;">
				<th align="center" width="150">{$lang_arr[$this->lang]["name"]}</th>
				<th align="center">{$lang_arr[$this->lang]["created_date"]}</th>
				<th align="center">{$lang_arr[$this->lang]["dob"]}</th>
				<th align="center">{$lang_arr[$this->lang]["printed"]}</th>
				<th align="center">{$lang_arr[$this->lang]["phone"]}</th>
			</tr>
			<tr>
				<td align="center">{$patient_data["name"]}</td>
				<td align="center">{$patient_data["created_date"]}</td>
				<td align="center">{$patient_data["dob"]}</td>
				<td align="center">{$patient_data["printed"]}</td>
				<td align="center">{$patient_data["phone"]}</td>
			</tr>		
		</table>
EOD;

		$this->writeHTML($user_details_html, true, false, false, false, '');

    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('dejavusans', '',9);
        // Page number

        $html = <<<EOD
        <div><strong>Врач: </strong><span>Рустамкулов Б</span></div>
EOD;


        $this->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        // print a block of text using Write()
        //$this->Write(-60, $FooterText , '', 0, 'C', true, 0, false, false, 12);
    }
}


?>
