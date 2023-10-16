<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// IMPORTANT - Replace the following line with your path to the escpos-php autoload script
require_once __DIR__ . '/../third_party/escpos_php/autoload.php';
require_once APPPATH.'third_party/phpqrcode/qrlib.php';

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
//use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;
use Mike42\Escpos\CapabilityProfile;

class ReceiptPrint {
adasdas
    private $CI;
    private $connector;
    private $printer;

    // TODO: printer settings
    // Make this configurable by printer (32 or 48 probably)
    private $printer_width = 48;

    function __construct()
    {
        $this->CI =& get_instance(); // This allows you to call models or other CI objects with $this->CI->...
    }

    function connect($connector_name) {
        $this->connector = new WindowsPrintConnector($connector_name);
        $profile = CapabilityProfile::load("default");

//        $this->printer = new Printer($this->connector);
        $this->printer = new Printer($this->connector,$profile);
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
        $this->printer->text($text."\n");
    }

    public function print_receipt($patient_data, $payment_data, $doctor_items = false, $laboratory_items = false, $uzis_items = false, $services_items = false, $registrator = false, $room = false) {
        $this->check_connection();
		$leftColPad = 25;
        if(!$room) {
            $doc_items = array();
            if(is_array($doctor_items)) {
                foreach ($doctor_items as $k => $ditem) {
                    if($k == 0 || $k == "total") {
                        $doc_items[] = new item2($ditem["text"], $ditem["price"], $leftColPad, "\n");
                    } else {
                        $doc_items[] = new item($ditem["text"], $ditem["count"]." x ".$ditem["price"]. " = ".($ditem["count"] * $ditem["price"]));
                    }

                }
            }

            $lab_items = array();
            if(is_array($laboratory_items)) {
                foreach ($laboratory_items as $k => $litem) {
                    if($k == 0 || $k == "total") {
                        $lab_items[] = new item2($litem["text"], $litem["price"], $leftColPad, "\n");
                    } else {
                        $lab_items[] = new item($litem["text"], $litem["count"]." x ".$litem["price"]." = ".($litem["count"] * $litem["price"]));
                    }
                }
            }

            $uzi_items = array();
            if(is_array($uzis_items)) {
                foreach ($uzis_items as $k => $uitem) {
                    if($k == 0 || $k == "total") {
                        $uzi_items[] = new item2($uitem["text"], $uitem["price"], $leftColPad, "\n");
                    } else {
                        $uzi_items[] = new item($uitem["text"], $uitem["count"]." x ".$uitem["price"]." = ".($uitem["count"] * $uitem["price"]));
                    }
                }
            }

            $service_items = array();
            if(is_array($services_items)) {
                foreach ($services_items as $k => $sitem) {
                    if($k == 0 || $k == "total") {
                        $service_items[] = new item2($sitem["text"], $sitem["price"], $leftColPad, "\n");
                    } else {
                        $service_items[] = new item($sitem["text"], $sitem["count"]." x ".$sitem["price"]." = ".($sitem["count"] * $sitem["price"]));
                    }
                }
            }

        } else {
            $room_items = array();
            if(is_array($room)) {
                foreach ($room as $k => $ritem) {
                    if($k == 0 || $k == "total") {
                        $room_items[] = new item2($ritem["text"], $ritem["price"], $leftColPad, "\n");
                    } else {
                        $room_items[] = new item($ritem["text"], $ritem["count"]." x ".$ritem["price"]. " = ".($ritem["count"]*$ritem["price"]));
                    }

                }
            }
        }

        /* Print top logo */
        $this->printer -> setJustification(Printer::JUSTIFY_CENTER);
        /* Start the printer */
        $logo = EscposImage::load(__DIR__."/logo.png", false);
        $this->printer -> bitImageColumnFormat($logo);
        $this->printer->feed();

        /* Name of shop */
        $this->printer -> setJustification(Printer::JUSTIFY_LEFT);
        $this->printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
        $this->printer -> setEmphasis(true);
        $this->add_line($patient_data["name"]);
        $this->printer -> selectPrintMode();
        $this->add_line("Чек No. ".$patient_data["payment_id"]);
        $this->printer -> setEmphasis(false);


        $this->printer-> setFont(Printer::FONT_A);
        $this->printer-> setTextSize(1, 1);
        $registerer = $registrator->last_name.' '.substr($registrator->first_name, 0,1);
        $reg_text = 'Чек No. '.$patient_data["payment_id"].$registerer;
        $str_pad_space_len = 30-strlen($reg_text);
        $space = str_pad('', $str_pad_space_len, ' ', STR_PAD_RIGHT);

        $this->add_line("Регистр: ".$space.$registerer);
        $this->add_line(str_pad('', 30, '_', STR_PAD_RIGHT));

//        $active_services_count = 0;

        if(!$room) {
            /* Doctors */
            if(count($doc_items) > 0) {
                foreach ($doc_items as $k => $item) {
                    if($k == 0) {
                        $this->printer->setEmphasis(true);
                        $this->add_line($item);
                        $this->printer->setEmphasis(false);

                    } elseif($k == "total") {
                        $this->add_line($item);
                    } else {
                        $this->add_line($item);
                    }
                }
                $this->add_line(str_pad('', 20, '_', STR_PAD_RIGHT));
//                $active_services_count++;
            }

            /* Laboratory */
            if(count($lab_items) > 0) {
                foreach ($lab_items as $k => $item) {
                    if($k == 0) {
                        $this->printer->setEmphasis(true);
                        $this->add_line($item);
                        $this->printer->setEmphasis(false);
                    } elseif($k == "total") {
                        $this->printer->setDoubleStrike(1);
                        $this->add_line($item);
                        $this->printer->setDoubleStrike(false);
                    } else {
                        $this->add_line($item);
                    }
                }
                $this->add_line(str_pad('', 20, '_', STR_PAD_RIGHT));
//                $active_services_count++;
            }

            /* UZI */
            if(count($uzi_items) > 0) {
                foreach ($uzi_items as $k => $item) {
                    if($k == 0) {
                        $this->printer->setEmphasis(true);
                        $this->add_line($item);
                        $this->printer->setEmphasis(false);
                    } elseif($k == "total") {
                        $this->printer->setDoubleStrike(1);
                        $this->add_line($item);
                        $this->printer->setDoubleStrike(false);
                    } else {
                        $this->add_line($item);
                    }
                }
                $this->add_line(str_pad('', 20, '_', STR_PAD_RIGHT));
//                $active_services_count++;
            }

            /* SERVICES */
            if(count($service_items) > 0) {
                foreach ($service_items as $k => $item) {
                    if($k == 0) {
                        $this->printer->setEmphasis(true);
                        $this->add_line($item);
                        $this->printer->setEmphasis(false);
                    } elseif($k == "total") {
                        $this->printer->setDoubleStrike(1);
                        $this->add_line($item);
                        $this->printer->setDoubleStrike(false);
                    } else {
                        $this->add_line($item);
                    }
                }
                $this->add_line(str_pad('', 20, '_', STR_PAD_RIGHT));
//                $active_services_count++;
            }

        } else {
            /* Rooms */
            if(count($room_items) > 0) {
                foreach ($room_items as $k => $item) {
                    if($k == 0) {
                        $this->printer->setEmphasis(true);
                        $this->add_line($item);
                        $this->printer->setEmphasis(false);
                    } else {
                        $this->add_line($item);
                    }
                }
                $this->add_line(str_pad('', 20, '_', STR_PAD_RIGHT));
            }
        }

		$paid       = new item2($payment_data["paid"]["text"], $payment_data["paid"]["price"], 28);
		$debt       = new item2($payment_data["debt"]["text"], $payment_data["debt"]["price"], 30);
		$total      = new item2($payment_data["total"]["text"], $payment_data["total"]["price"], 25);
		$discount   = new item2($payment_data["discount"]["text"], $payment_data["discount"]["price"], 28);

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
        $qrText = 'Бемор: '.$patient_data["name"] ." \n ".
            'Чек №: '. $patient_data["payment_id"];
        QRcode::png($qrText, __DIR__."/qrcode.png", 'L', 5, 0);
        $img = EscposImage::load(__DIR__."/qrcode.png"); // Load image
        $this->printer -> bitImageColumnFormat($img);
        $this->printer -> feed(3);


        /* Cut the receipt and open the cash drawer */
        $this->printer -> cut();
        $this->printer -> pulse();

        $this->printer -> close();
    }
}

/* A wrapper to do organise item names & prices into columns */
class item
{
    private $name;
    private $price;

    public function __construct($name = '', $price = '')
    {
        $this -> name = $name;
        $this -> price = $price;
    }

    public function __toString()
    {
        $rightCols = 30;
        $leftCols = 28;
        $left = str_pad($this -> name, $leftCols);

        $right = str_pad($this -> price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left\n$right";
    }
}

class item2
{
    private $name;
    private $price;
    private $leftCols;
    private $parag;

    public function __construct($name = '', $price = '', $leftCols = 28, $parag = "")
    {
        $this -> name = $name;
        $this -> price = $price;
        $this -> leftCols = $leftCols;
        $this -> parag = $parag;
    }

    public function __toString()
    {
        $rightCols = 10;
        $left = str_pad($this -> name, $this->leftCols) ;

        $right = str_pad($this -> price, $rightCols, ' ', STR_PAD_LEFT);

        $par = $this -> parag;
        return "$par$left$right";
    }
}
