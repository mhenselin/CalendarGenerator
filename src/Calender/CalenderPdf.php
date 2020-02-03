<?php
namespace App\Calender;

class CalenderPdf extends \FPDF
{

    const DEFAULT_PAGE_TEMPLATE = 'A4';
    
    const FPDF_ORIANTATION_PORTRAIT = 'portrait';
    const FPDF_ORIANTATION_LANDSCAPE = 'landscape';
    
    const FPDF_ZOOM_FULLWIDTH = 'fullwidth';
    const FPDF_ZOOM_FULLPAGE = 'fullpage';
    const FPDF_ZOOM_REAL = 'real';
    const FPDF_ZOOM_DEFAULT = 'default';
    
    const DEFAULT_FONT_FAMILY = 'Arial';
    const DEFAULT_FONT_SIZE = 14;

    /**
     * @var int
     */
    protected $_canvasHeight = 0;

    /**
     * @var int
     */
    protected $_canvasWidth = 0;

    /**
     * @var int
     */
    protected $_colWidth = 0;

    /**
     * @var int
     */
    protected $_rowHeight = 0;

    /**
     * @var int
     */
    protected $_border=10;

    /**
     * @var int
     */
    protected $_marginTop = 55;

    /**
     * @var int
     */
    protected $_lineWidthBorder = 1.5;

    /**
     * @var int
     */
    protected $_nameHeight = 13;


    public function setFontPath ($fontPath='font/')
    {
        if (file_exists($fontPath)) {
            $this->fontpath = $fontPath;
        }
        
        return $this;
    }
    
    /**
     * liefert die CoordinatenX auf der aktuellen PDF-Seite
     *
     * @return int
     */
    public function getCursorX() {
        return $this->_cursorX;
    }

    /**
     * liefert die CoordinatenY auf der aktuellen PDF-Seite
     *
     * @return int
     */
    public function getCursorY() {
        return $this->_cursorY;
    }

    /**
     * initalisiert ein Pdf - setzt die nötigen Parameter
     *
     * @return TTD_Pdf
     */
    public function initPdf($orientation, $size) {
        $this->SetFont(self::DEFAULT_FONT_FAMILY,'',self::DEFAULT_FONT_SIZE);
        $this->SetDisplayMode(self::FPDF_ZOOM_FULLPAGE);
        $this->AddPage($orientation, $size);        

        return $this;
    }

    public function hex2rgb($hex) {
        if(is_array($hex)) {
            var_dump($hex);
        }
       $hex = str_replace("#", "", $hex);
       
       if(strlen($hex) == 3) {
          $r = hexdec(substr($hex,0,1).substr($hex,0,1));
          $g = hexdec(substr($hex,1,1).substr($hex,1,1));
          $b = hexdec(substr($hex,2,1).substr($hex,2,1));
       } else {
          $r = hexdec(substr($hex,0,2));
          $g = hexdec(substr($hex,2,2));
          $b = hexdec(substr($hex,4,2));
       }
       $rgb = array($r, $g, $b);
       
       return $rgb; // returns an array with the rgb values
    }
    
    public function rgb2hex($rgb) {
       $hex = "#";
       $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
       $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
       $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);
    
       return $hex; // returns the hex value including the number sign (#)
    }
    
    public function getCellMargin()
    {
        return $this->cMargin;
    } 
}
