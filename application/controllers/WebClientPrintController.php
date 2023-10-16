<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WebClientPrintController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library("WebClientPrint");
    }

    public function index() {

        WebClientPrint::$wcpCacheFolder = APPPATH.'/third_party/webclientprint/wcpcache/';

        if (file_exists(WebClientPrint::$wcpCacheFolder) == false) {
            //create wcpcache folder
            $old_umask = umask(0);
            mkdir(WebClientPrint::$wcpCacheFolder, 0777);
            umask($old_umask);
        }

        WebClientPrint::cacheClean(30); //in minutes

        $urlParts = parse_url($_SERVER['REQUEST_URI']);
        if (isset($urlParts['query'])){
            $query = $urlParts['query'];
            parse_str($query, $qs);

            //get session id from querystring if any
            $sid = NULL;
            if (isset($qs[WebClientPrint::SID])){
                $sid = $qs[WebClientPrint::SID];
            }

            try{
                //get request type
                $reqType = WebClientPrint::GetProcessRequestType($query);

                if($reqType == WebClientPrint::GenPrintScript ||
                    $reqType == WebClientPrint::GenWcppDetectScript){
                    //Let WebClientPrint to generate the requested script

                    //Get Absolute URL of this file
                    $currentAbsoluteURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
                    $currentAbsoluteURL .= $_SERVER["SERVER_NAME"];
                    if($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443")
                    {
                        $currentAbsoluteURL .= ":".$_SERVER["SERVER_PORT"];
                    }
                    $currentAbsoluteURL .= $_SERVER["REQUEST_URI"];
                    $currentAbsoluteURL = substr($currentAbsoluteURL, 0, strrpos($currentAbsoluteURL, '?'));

                    ob_start();
                    ob_clean();
                    header('Content-type: text/javascript');
                    echo WebClientPrint::generateScript($currentAbsoluteURL, $query);
                    return;
                }
                else if ($reqType == WebClientPrint::ClientSetWcppVersion)
                {
                    //This request is a ping from the WCPP utility
                    //so store the session ID indicating this user has the WCPP installed
                    //also store the WCPP Version if available
                    if(isset($qs[WebClientPrint::WCPP_SET_VERSION]) && strlen($qs[WebClientPrint::WCPP_SET_VERSION]) > 0){
                        WebClientPrint::cacheAdd($sid, WebClientPrint::WCP_CACHE_WCPP_VER, $qs[WebClientPrint::WCPP_SET_VERSION]);
                    }
                    return;
                }
                else if ($reqType == WebClientPrint::ClientSetInstalledPrinters)
                {
                    //WCPP Utility is sending the installed printers at client side
                    //so store this info with the specified session ID
                    WebClientPrint::cacheAdd($sid, WebClientPrint::WCP_CACHE_PRINTERS, strlen($qs[WebClientPrint::WCPP_SET_PRINTERS]) > 0 ? $qs[WebClientPrint::WCPP_SET_PRINTERS] : '');
                    return;
                }
                else if ($reqType == WebClientPrint::ClientSetInstalledPrintersInfo)
                {
                    //WCPP Utility is sending the installed printers at client side with detailed info
                    //so store this info with the specified session ID
                    //Printers Info is in JSON format
                    $printersInfo = $_POST['printersInfoContent'];

                    WebClientPrint::cacheAdd($sid, WebClientPrint::WCP_CACHE_PRINTERSINFO, $printersInfo);
                    return;
                }
                else if ($reqType == WebClientPrint::ClientGetWcppVersion)
                {
                    //return the WCPP version for the specified Session ID (sid) if any
                    ob_start();
                    ob_clean();
                    header('Content-type: text/plain');
                    echo WebClientPrint::cacheGet($sid, WebClientPrint::WCP_CACHE_WCPP_VER);
                    return;
                }
                else if ($reqType == WebClientPrint::ClientGetInstalledPrinters)
                {
                    //return the installed printers for the specified Session ID (sid) if any
                    ob_start();
                    ob_clean();
                    header('Content-type: text/plain');
                    echo base64_decode(WebClientPrint::cacheGet($sid, WebClientPrint::WCP_CACHE_PRINTERS));
                    return;
                }
                else if ($reqType == WebClientPrint::ClientGetInstalledPrintersInfo)
                {
                    //return the installed printers with detailed info for the specified Session ID (sid) if any
                    ob_start();
                    ob_clean();
                    header('Content-type: text/plain');
                    echo base64_decode(WebClientPrint::cacheGet($sid, WebClientPrint::WCP_CACHE_PRINTERSINFO));
                    return;
                }
            }
            catch (Exception $ex)
            {
                throw $ex;
            }

        }

    }

    public function printESCPOS()
    {
        $this->load->model(array(
            "patients_payments_model",
            "patients_model",
            "patient_doctor_model",
            "patient_laboratories_model",
            "patient_uzi_model",
            ));


        $urlParts = parse_url($_SERVER['REQUEST_URI']);

        if (isset($urlParts['query'])) {
            $rawQuery = $urlParts['query'];
            parse_str($rawQuery, $qs);

            if (isset($qs[WebClientPrint::CLIENT_PRINT_JOB])) {

                $printerName = urldecode($qs['printerName']);
                $payment_id = urldecode($qs['payment_id']);

                $this->load->helper(array("lab_form", 'printer'));
                $pr = print_receipt($payment_id);

//                $patient = "Abdurahmon Mirzakulov";
                $patient        = $pr["patient_data"];
                $payment        = $pr["payment_data"];
                $doctors        = $pr["doctor_items"];
                $laboratories   = $pr["laboratory_items"];
                $uzis           = $pr["uzi_items"];

                //Create ESC/POS commands for sample receipt
                $esc = '0x1B'; //ESC byte in hex notation
                $newLine = '0x0A'; //LF byte in hex notation
                $endOfText = '0x03'; //End of Text byte in hex notation

                $aa = 'Chek: '.$patient["payment_id"].'Alimova F';
                $aa_len = strlen($aa);
                $str_pad_space_len = 42-$aa_len;

                $cmds = '';
                $cmds = $esc . "@";

                $cmds .= $esc . '!' . '0x48';
                $cmds .= $patient["name"];
                $cmds .= $esc . '!' . '0x00';
                $cmds .= $newLine;

                $cmds .= $esc . '!' . '0x01';
                $cmds .= 'Chek: '.$patient["payment_id"];
                $cmds .= str_pad('', $str_pad_space_len, ' ', STR_PAD_RIGHT);
                $cmds .= 'Alimova F';
                $cmds .= $esc . '!' . '0x00';

//                $cmds .= $esc . '!' . '0x2D';
//                $cmds .= str_pad('', 20, '_', STR_PAD_LEFT);
//                $cmds .= $esc . '!' . '0x00';
//                $cmds .= $newLine;
                $cmds .= $newLine;

                $cmds .= $this->setLine($esc, $newLine);


//                $cmds .= $esc . '!' . '0x00';

                $cmds .= "0x1B!D0B0";
                $cmds .= "0x1B!0x00";


                $cmds .= $newLine;
                $cmds .= 'Emphasized2';
                $cmds .= $newLine;

//                if(count($doctors) > 0) {
//                    $cmds .= $esc . '!' . '0x01';
//                    foreach ($doctors as $k => $doctor) {
//                        if($k === 'total') {
//                            $cmds .= $newLine;
//                            $cmds .= mb_convert_encoding($doctor["text"], "UTF-8", "auto") .'           '.$doctor["price"];
//                            $cmds .= $newLine;
//                        } else if($k == 0) {
//                            $cmds .= $doctor["text"];
//                            $cmds .= $newLine;
//                        } else {
//                            $cmds .= $doctor["text"];
//                            $cmds .= $newLine;
//                            $cmds .= '                       1 x '.$doctor["price"].' = '.$doctor["price"];
//                            $cmds .= $newLine;
//                        }
//                    }
//                    $cmds .= $esc . '!' . '0x00';
//                    $cmds .= $this->setLine($esc, $newLine);
//
//                }
//
//                if(count($laboratories) > 0) {
//                    $cmds .= $esc . '!' . '0x01';
//                    foreach ($laboratories as $k => $laboratory) {
//                        if($k === 'total') {
//                            $cmds .= $newLine;
////                            $cmds .= iconv('ASCII', 'UTF-8//TRANSLIT', $laboratory["text"]) .'           '.$laboratory["price"];
//                            $cmds .= $newLine;
//                        } else if($k == 0) {
//                            $cmds .= utf8_encode($laboratory["text"]);
//                            $cmds .= $newLine;
//                        } else {
//                            $cmds .= utf8_encode($laboratory["text"]);
//                            $cmds .= $newLine;
//                            $cmds .= '                       1 x '.$laboratory["price"].' = '.$laboratory["price"];
//                            $cmds .= $newLine;
//                        }
//                    }
//                    $cmds .= $esc . '!' . '0x00';
//                    $cmds .= $this->setLine($esc, $newLine);
//
//                }

                $cmds .= $esc . '!' . '0x00';
                $cmds .= $newLine;
                $cmds .= date("d.m.Y H:i:s");
                $cmds .= $newLine;
                $cmds .= 'SIZGA SOG\'LIQ TILAYMIZ!!!';
//                $cmds .= $newLine . $newLine;

                //Create a ClientPrintJob obj that will be processed at the client side by the WCPP
                $cpj = new ClientPrintJob();
                //set ESCPOS commands to print...
                $cpj->printerCommands = $cmds;
                $cpj->formatHexValues = true;

                $cpj->clientPrinter = new InstalledPrinter($printerName);

                //Send ClientPrintJob back to the client
                ob_start();
                ob_clean();
                header('Content-type: application/octet-stream');
                echo $cpj->sendToClient();
                ob_end_flush();
                exit();

            }
        }
    }

    private function setLine($esc, $newLine)
    {
        $cmds = '';
        $cmds .= $esc . '!' . '0x2D';
        $cmds .= str_pad('', 20, '_', STR_PAD_LEFT);
        $cmds .= $esc . '!' . '0x00';
        $cmds .= $newLine;
        $cmds .= $newLine;

        return $cmds;

    }
}