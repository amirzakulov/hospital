<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once  APPPATH . '/../vendor/autoload.php';
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\IOFactory;

class Phpword {
    private $source;

    public function __construct()
    {
        $this->source = __DIR__.'\..\..\assets\Исакова Олимахон  1997й Умумий.docx';
    }

    function readWordDocx($source) {
        $objReader = IOFactory::createReader("Word2007");
        $phpWord = $objReader->load($source);
        $body = "";
        foreach ($phpWord->getSections() as $section) {
            $arrays = $section->getElements();
            foreach ($arrays as $e) {
                if(get_class($e) === "PhpOffice\PhpWord\Element\TextRun") {
                    $paragraph  = $e->getParagraphStyle();
                    $align      = $paragraph->getAlignment();
                    $body .= "<div style='text-align: ".$align."'>";
                    foreach ($e->getElements() as $text) {
                        if(get_class($text) === "PhpOffice\PhpWord\Element\Text") {

                            $font       = $text->getFontStyle();
                            $size       = $font->getSize() == 0 ? "":($font->getSize()/10)."em";
                            $bold       = $font->isBold() ? "font-weight: 700;":"";
                            $color      = $font->getColor() ? $font->getColor() : "#000000";
                            $fontFamily = $font->getName() ? "font-family:".$font->getName().";" : "";

                            $body .="<span style='text-align:".$align."; font-size:".$size.";".$fontFamily.";".$bold."; color:".$color.";'>".$text->getText()."</span>";
                        }
                        elseif (get_class($text) === "PhpOffice\PhpWord\Element\TextBreak")
                        {
                            $body .="<br />";
                        }
                    }
                    $body .= "</div>";
                }
                elseif (get_class($e) === "PhpOffice\PhpWord\Element\Text")
                {
                    $body .="<span>".$e->getText()."</span>";
                }
                elseif (get_class($e) === "PhpOffice\PhpWord\Element\TextBreak")
                {
                    $body .= "<br />";
                }
            }
        }

        return $body;
    }
}
