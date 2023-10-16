<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// IMPORTANT - Replace the following line with your path to the escpos-php autoload script
require_once __DIR__ . '/../third_party/escpos_php/autoload.php';
require_once APPPATH.'third_party/phpqrcode/qrlib.php';

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;
use Mike42\Escpos\CapabilityProfile;

class ReceiptPrint {

    private $CI;
    private $connector;
    private $printer;
	private $printer_config;
	private $printer_width;
	private $printer_encode;

    function __construct()
    {
        $this->CI =& get_instance(); // This allows you to call models or other CI objects with $this->CI->...
		$this->CI->load->model(array('settings_model', 'posprinters_model'));
		$printer_config 			= $this->CI->settings_model->get_group_settings("POS");
		$this->printer_config		= $printer_config;
		$printer_settings 			= $this->CI->posprinters_model->get_posprinter_settings($printer_config["selected_pos_printer_id"]);
		$this->printer_width		= $printer_settings["width"];
		$this->printer_encode		= $printer_settings["encode"];

    }

    function connect($connector_name) {
        $this->connector = new WindowsPrintConnector($connector_name);
        $profile 		 = CapabilityProfile::load("default");

        $this->printer 	 = new Printer($this->connector,$profile);
    }

    private function check_connection()
    {
        if (!$this->connector OR !$this->printer OR !is_a($this->printer, 'Mike42\Escpos\Printer')) {
            throw new Exception("Tried to create receipt without being connected to a printer.");
        }
    }

    public function close_after_exception()
    {
        if (isset($this->printer) && is_a($this->printer, 'Mike42\Escpos\Printer')) {
            $this->printer->close();
        }
        $this->connector = null;
        $this->printer = null;
        $this->emc_printer = null;
    }

    // Calls printer->text and adds new line
    private function add_line($text = "", $should_wordwrap = false)
    {
        $text = $should_wordwrap ? wordwrap($text, $this->printer_width) : $text;
		if($this->printer_encode == 'chinese') {
			$this->printer->textChinese($text."\n");
		} else {
			$this->printer->text($text."\n");
		}
    }

    public function print_receipt($patient_data, $payment_data, $doctor_items = false, $laboratory_items = false, $uzis_items = false, $services_items = false, $registrator = false, $room = false) {
        $this->check_connection();

        if(!$room) {
            $doc_items = array();
            if(is_array($doctor_items)) {
                foreach ($doctor_items as $k => $ditem) {
                    if($k === "total") {
						$doc_items["total"] = new doubleColumn($ditem["text"], $ditem["price"], $this->printer_width, $this->printer_encode);
                    } elseif($k === 0) {
						$doc_items[0] = new singleColumn($ditem["text"], STR_PAD_RIGHT, $this->printer_width);
                    } else {
                        $doc_items[] = new singleColumn($ditem["text"], STR_PAD_RIGHT, $this->printer_width);
                        $doc_items[] = new singleColumn( $ditem["count"]." x ".$ditem["price"]. " = ".($ditem["count"] * $ditem["price"]), STR_PAD_LEFT, $this->printer_width);
                    }
                }
            }

            $lab_items = array();
            if(is_array($laboratory_items)) {
                foreach ($laboratory_items as $k => $litem) {

					if($k === "total") {
						$lab_items["total"] = new doubleColumn($litem["text"], $litem["price"], $this->printer_width, $this->printer_encode);
					} elseif ($k === 0) {
						$lab_items[0] = new singleColumn($litem["text"], STR_PAD_RIGHT, $this->printer_width);
					} else {
						$lab_items[] = new singleColumn($litem["text"], STR_PAD_RIGHT, $this->printer_width);
						$lab_items[] = new singleColumn( $litem["count"]." x ".$litem["price"]. " = ".($litem["count"] * $litem["price"]), STR_PAD_LEFT, $this->printer_width);
					}
                }
            }

            $uzi_items = array();
            if(is_array($uzis_items)) {
                foreach ($uzis_items as $k => $uitem) {
					if($k === "total") {
						$uzi_items["total"] = new doubleColumn($uitem["text"], $uitem["price"], $this->printer_width, $this->printer_encode);
					} elseif($k === 0) {
						$uzi_items[0] = new singleColumn($uitem["text"], STR_PAD_RIGHT, $this->printer_width);
					} else {
						$uzi_items[] = new singleColumn($uitem["text"], STR_PAD_RIGHT, $this->printer_width);
						$uzi_items[] = new singleColumn( $uitem["count"]." x ".$uitem["price"]. " = ".($uitem["count"] * $uitem["price"]), STR_PAD_LEFT, $this->printer_width);
					}
                }
            }

            $service_items = array();
            if(is_array($services_items)) {
                foreach ($services_items as $k => $sitem) {
					if($k === "total") {
						$service_items["total"] = new doubleColumn($sitem["text"], $sitem["price"], $this->printer_width, $this->printer_encode);
					} elseif($k === 0) {
						$service_items[0] = new singleColumn($sitem["text"], STR_PAD_RIGHT, $this->printer_width);
					} else {
						$service_items[] = new singleColumn($sitem["text"], STR_PAD_RIGHT, $this->printer_width);
						$service_items[] = new singleColumn( $sitem["count"]." x ".$sitem["price"]. " = ".($sitem["count"] * $sitem["price"]), STR_PAD_LEFT, $this->printer_width);
					}
                }
            }

        } else {
            $room_items = array();
            if(is_array($room)) {
                foreach ($room as $k => $ritem) {
					if($k === "total") {
						$room_items["total"] = new doubleColumn($ritem["text"], $ritem["price"], $this->printer_width, $this->printer_encode);
					} elseif($k === 0) {
						$room_items[0] = new singleColumn($ritem["text"], STR_PAD_RIGHT, $this->printer_width);
					} else {
						$room_items[] = new singleColumn($ritem["text"], STR_PAD_RIGHT, $this->printer_width);
						$room_items[] = new singleColumn( $ritem["count"]." x ".$ritem["price"]. " = ".($ritem["count"] * $ritem["price"]), STR_PAD_LEFT, $this->printer_width);
					}
                }
            }
        }

        /* Print top logo */
		if($this->printer_config["pos_printer_logo_print"]) {
			$this->printer -> setJustification(Printer::JUSTIFY_CENTER);
			/* Start the printer */
			$logo = EscposImage::load(__DIR__."/../../assets/images/receipt_logo.png", false);
			$this->printer -> bitImageColumnFormat($logo);
			$this->printer->feed();
		}


        /* Name of shop */
        $this->printer -> setJustification(Printer::JUSTIFY_LEFT);
        $this->printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
        $this->printer -> setEmphasis(true);
        $this->add_line($patient_data["name"], false);
        $this->printer -> selectPrintMode();
        $this->add_line("Чек No. ".$patient_data["payment_id"], false);
        $this->printer -> setEmphasis(false);


        $this->printer-> setFont(Printer::FONT_A);
        $this->printer-> setTextSize(1, 1);
        $registerer = $registrator->last_name.' '.substr($registrator->first_name, 0,1);
        $reg_text = 'Чек No. '.$patient_data["payment_id"].$registerer;
        $str_pad_space_len = 30-strlen($reg_text);
        $space = str_pad('', $str_pad_space_len, ' ', STR_PAD_RIGHT);

        $this->add_line("Регистр: ".$space.$registerer);
        $payment_date = date("d.m.Y H:i", strtotime($payment_data["payment_date"]["date"]));
        $this->add_line($payment_data["payment_date"]["text"].$space.$payment_date);
        $this->add_line(str_pad('', $this->printer_width, '_', STR_PAD_RIGHT));

        if(!$room) {
            /* Doctors */
            if(count($doc_items) > 0) {
                foreach ($doc_items as $k => $item) {
                    if($k === 0) {
                        $this->printer->setEmphasis(true);
                        $this->add_line($item);
                        $this->printer->setEmphasis(false);
                    } elseif($k === "total") {
						$this->add_line($item);
                    } else {
                        $this->add_line($item);
                    }
                }
                $this->add_line(str_pad('', $this->printer_width, '_', STR_PAD_RIGHT));
//                $active_services_count++;
            }

            /* Laboratory */
            if(count($lab_items) > 0) {
                foreach ($lab_items as $k => $item) {
                    if($k === 0) {
						$this->printer->setEmphasis(true);
						$this->add_line($item);
						$this->printer->setEmphasis(false);
					} elseif($k === "total") {
                        $this->add_line($item);
                    } else {
                        $this->add_line($item);
                    }
                }
                $this->add_line(str_pad('', $this->printer_width, '_', STR_PAD_RIGHT));
            }

            /* UZI */
            if(count($uzi_items) > 0) {
                foreach ($uzi_items as $k => $item) {
                    if($k === 0) {
                        $this->printer->setEmphasis(true);
                        $this->add_line($item);
                        $this->printer->setEmphasis(false);
                    } elseif($k === "total") {
                        $this->add_line($item);
                    } else {
                        $this->add_line($item);
                    }
                }
                $this->add_line(str_pad('', $this->printer_width, '_', STR_PAD_RIGHT));
            }

            /* SERVICES */
            if(count($service_items) > 0) {
                foreach ($service_items as $k => $item) {
                    if($k === 0) {
                        $this->printer->setEmphasis(true);
                        $this->add_line($item);
                        $this->printer->setEmphasis(false);
                    } elseif($k === "total") {
                        $this->add_line($item);
                    } else {
                        $this->add_line($item);
                    }
                }
                $this->add_line(str_pad('', $this->printer_width, '_', STR_PAD_RIGHT));
            }
        } else {
            /* Rooms */
            if(count($room_items) > 0) {
                foreach ($room_items as $k => $item) {
                    if($k === 0) {
                        $this->printer->setEmphasis(true);
                        $this->add_line($item);
                        $this->printer->setEmphasis(false);
                    } else {
                        $this->add_line($item);
                    }
                }
                $this->add_line(str_pad('', $this->printer_width, '_', STR_PAD_RIGHT));
            }
        }

		$paid       = new doubleColumn($payment_data["paid"]["text"], $payment_data["paid"]["price"], $this->printer_width, $this->printer_encode);
		$debt       = new doubleColumn($payment_data["debt"]["text"], $payment_data["debt"]["price"], $this->printer_width, $this->printer_encode);
		$total      = new doubleColumn($payment_data["total"]["text"], $payment_data["total"]["price"], $this->printer_width, $this->printer_encode);
		$discount   = new doubleColumn($payment_data["discount"]["text"], $payment_data["discount"]["price"], $this->printer_width, $this->printer_encode);

        $this->printer->feed(1);
        if($payment_data["total"]["price"] != $payment_data["paid"]["price"]) {$this->add_line($total); }
        if($payment_data["discount"]["price"] > 0) {$this->add_line($discount); }
        if($payment_data["debt"]["price"] > 0) {$this->add_line($debt);}
        $this->add_line($paid);
        $this->printer -> feed(1);

        $this->printer -> setJustification(Printer::JUSTIFY_CENTER);
        $this->add_line(date("d.m.Y H:i"));
        $this->add_line("SIZGA SOG'LIQ TILAYMIZ!");
        $this->printer -> feed(1);

        //QR Code generatsiya qilamiz
		if($this->printer_config["pos_printer_qrcode"]) {
			$qrText = 'Бемор: '.$patient_data["name"] ." \n ".
				'Чек №: '. $patient_data["payment_id"];
			QRcode::png($qrText, __DIR__."/qrcode.png", 'L', 5, 0);
			$img = EscposImage::load(__DIR__."/qrcode.png"); // Load image
			$this->printer -> bitImageColumnFormat($img);
		}

		$this->printer -> feed(3);

        /* Cut the receipt and open the cash drawer */
        $this->printer -> cut();
        $this->printer -> pulse();

        $this->printer -> close();
    }
}

/* A wrapper to do organise item names & prices into columns */
class singleColumn
{
    private $text;
	private $width;
	private $pad_type;

    public function __construct($text = '', $pad_type = STR_PAD_RIGHT, $width = 32)
    {
        $this->text 		= $text;
		$this->width 		= $width;
		$this->pad_type 	= $pad_type;
	}

    public function __toString()
    {
        $str = str_pad($this->text, $this->width, ' ', $this->pad_type);
        return "$str";
    }
}

class doubleColumn
{
    private $text;
    private $price;
    private $width;
    private $encode;

    public function __construct($text = '', $price = 0, $width = 32, $encode = "utf8")
    {
        $this->text 	= $text;
        $this->price 	= $price;
		$this->width 	= $width;
		$this->encode 	= $encode;
    }

    public function __toString()
    {
		$leftColLen		= mb_strlen($this->text);
		if($this->encode == 'chinese') {$leftColLen *= 2;}
		$left 			= str_pad($this->text, $leftColLen);
		$rightColLen 	= $this->width - $leftColLen;
		$right 			= str_pad($this->price, $rightColLen, ' ', STR_PAD_LEFT);

		return "$left$right";
    }
}
