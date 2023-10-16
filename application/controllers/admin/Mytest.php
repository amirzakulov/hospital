<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include APPPATH.'third_party/escpos_php/autoload.php';
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;
use Mike42\Escpos\CapabilityProfile;

class Mytest extends Admin_Controller {
	private $CI;
	private $connector;
	private $printer;
	private $printer_width = 48;

    function __construct()
    {
        parent::__construct();
		$this->CI =& get_instance(); // This allows you to call models or other CI objects with $this->CI->...
		$this->CI->load->model(array('settings_model', 'posprinters_model'));
    }

    public function index() {

		$this->load->model(array(
			"patients_payments_model",
			"patients_model",
			"patient_doctor_model",
			"patient_laboratories_model",
			"",
			));
//		$this->load->helper(array("lab_form"));
//		$pr 	= print_receipt(2996);
//
//		$this->data["pr"] = $pr;

		$selected_pos_printer_id 	= $this->settings_model->get_selected_posprinter();
		$printer_settings 			= $this->posprinters_model->get_posprinter_settings($selected_pos_printer_id);
		$width 						= $printer_settings["width"];
		$encode 						= $printer_settings["encode"];


		echo "<pre>";
		print_r($printer_settings);
		echo "</pre>";





//		$this->connector = new WindowsPrintConnector($this->config->item("pos_printer_name"));
//		$profile = CapabilityProfile::load("default");
//
//		$this->printer = new Printer($this->connector,$profile);
//
//		if (!$this->connector OR !$this->printer OR !is_a($this->printer, 'Mike42\Escpos\Printer')) {
//			throw new Exception("Tried to create receipt without being connected to a printer.");
//		}

		$this->data["text"] 	= $this->doubleColumn("Жами:", 90000);
		$this->data["text2"] 	= $this->doubleColumn("Туланди:", 6000);
		$this->data["text3"] 	= $this->doubleColumn("Карзингиз:", 140000);
		$this->data["text4"] 	= $this->doubleColumn("Чегирма:", 56000);


		$this->render("admin/mytest/index_view");
    }

	public function doubleColumn($text = '', $price = 0, $width = 47)
	{
		$leftColLen		= mb_strlen($text);
		$left 			= str_pad($text, $leftColLen);
		$rightColLen 	= $width - $leftColLen;
		$right 			= str_pad($price, $rightColLen, '.', STR_PAD_LEFT);

		return "\n$left$right";
	}

    public function read_file_docx($filename){

        $striped_content = '';
        $content = '';

        if(!$filename || !file_exists($filename)) return false;

        $zip = zip_open($filename);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "word/document.xml") continue;

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        }// end while

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $striped_content = strip_tags($content);

        return $striped_content;
    }

    public function calc_fib_from_array($arr)
    {
        /* Step 1 */ sort($arr);
        /* Step 2 */ $remove_duplicate = array_unique($arr, SORT_NUMERIC);
        /* Step 3 */ $fib = array_values($remove_duplicate);

        /* Step 4 */ $sum = $fib[0] + $fib[1];
        /* Step 5 */ for($i=2; $i < count($fib); $i++) {
                     if(($fib[$i-2] + $fib[$i-1]) == $fib[$i]) {
                         $sum += $fib[$i];
                     }
                }

        return $sum;
    }

}
