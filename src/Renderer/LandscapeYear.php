<?php
namespace App\Renderer;

use App\Renderer\Pdf\Calender;
use App\Renderer\Pdf\CalenderAbstract;

class LandscapeYear extends CalenderAbstract {
    const COLOR_SA = 'FFDBDB';
    const COLOR_SO = 'FFA3B2';
    const COLOR_HOLIDAY = 'FFA3B2';

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
    protected $_marginTop = 8;
    /**
     * @var int
     */
    protected $_marginBottom = 7;
    /**
     * @var int
     */
    protected $_marginLeft = 5;
    /**
     * @var int
     */
    protected $_marginRight = 5;
    
    /**
     * @var float
     */
    protected $_calenderStartY = 20;
    
    /**
     * @var int
     */
    protected $_headerHeight = 4;


    public function initPdf($size='a4')
    {
        $this->setSize($size);
        $this->getPdfClass()->SetLeftMargin($this->_marginLeft);
        $this->getPdfClass()->SetRightMargin($this->_marginRight);
        $this->getPdfClass()->SetTopMargin($this->_marginTop);
        $this->getPdfClass()->SetAutoPageBreak(true, $this->_marginBottom);
        $this->getPdfClass()->initPdf(Calender::FPDF_ORIANTATION_LANDSCAPE, $this->getSize());
    }
    
    public function postRender()
    {
        $this->getPdfClass()->SetFont('','B');
        $this->getPdfClass()->SetFontSize(13);
        $this->getPdfClass()->Text($this->_marginLeft, $this->_marginTop, 'Jahreskalender ' . $this->headline);
    }
    
    protected function _drawHeader($startMonth, $startYear)
    {
        $this->headline = strval($startYear);
        $this->getPdfClass()->SetFontSize(9);        
        for($col=1; $col<=12; $col++) {
            if (($startMonth+$col)>12) {
                $calcMonth = ($col-12)+$startMonth;
                $calcYear = $startYear+1;
            } else {
                $calcMonth = ($startMonth+$col);
                $calcYear = $startYear;
            }
            
            $montTimestamp = mktime(0,0,0, ($calcMonth), 1, $calcYear); 
            $this->getPdfClass()->Cell($this->_colWidth, $this->_headerHeight , strftime('%B \'%y', $montTimestamp), 1, 0, 'C');
        }
        $this->getPdfClass()->Ln();
        
        if ($startYear<$calcYear) {
            $this->headline .= '/' . strval($calcYear);
        }
    }
    
    protected function _generateCalenderData($startMonth, $startYear, array $additionalData)
    {
        $calenderData = array();
        for ($month=1; $month<=12; $month++) {
            if (($startMonth+$month)>12) {
                $calcMonth = ($month-12)+$startMonth;
                $calcYear = $startYear+1;
            } else {
                $calcMonth = $month+$startMonth;
                $calcYear = $startYear;
            }
            
            for ($day=1; $day<=31; $day++) {
                $dayTimestamp = mktime(0,0,0, $calcMonth, $day, $calcYear);
                
                if ($day > (date("t", mktime(0,0,0, $calcMonth)))) {
                    $calenderData[$month][$day] = array();
                } else {
                    // DAY values
                    $dayName = strftime('%a', $dayTimestamp);
                    $calenderData[$month][$day]['date'] = sprintf('%02d ',$day) . $dayName;

                    // KW values
                    if ($this->showCalenderWeeks()) {
                        $weekNumber = strftime('%V', $dayTimestamp);
                        if ((strtoupper($dayName)==='MO') || ($day==1 && $month==1)) {
                            $calenderData[$month][$day]['kw'] = $weekNumber;
                        } else {
                            $calenderData[$month][$day]['kw'] = '';
                        }
                    }

                    // Weekend??
                    $isWeekend = false;
                    switch (strtoupper($dayName)) {
                        case 'SA':
                                $calenderData[$month][$day]['color'] = self::COLOR_SA;
                                $calenderData[$month][$day]['fontwidth'] = 'B';
                                $isWeekend = true;
                            break;
                        case 'SO':
                                $calenderData[$month][$day]['color'] = self::COLOR_SO;
                                $calenderData[$month][$day]['fontwidth'] = 'B';
                                $isWeekend = true;
                            break;
                        default:
                                $calenderData[$month][$day]['color'] = '';
                                $calenderData[$month][$day]['fontwidth'] = '';
                            break;
                    }
                    
                    // Holidays?
                    $isHoliday = false;
                    if ($this->showHolidays()) {
                        if (isset($this->_holidays[$calcYear][$calcMonth][$day])) {
                            $calenderData[$month][$day]['color'] = self::COLOR_SO;
                            $calenderData[$month][$day]['info'] = $this->_holidays[$calcYear][$calcMonth][$day];
                            $isHoliday = true;
                        }                        
                    }
                    
                    // Special Events?
                    if ($this->showSpecialEvents() && isset($this->_events[$calcYear][$calcMonth][$day])) {
                        if ($month==2 and $day==8) {
                            file_put_contents('/tmp/calender.log', $this->_events[$calcYear][$calcMonth][$day]['event'] . PHP_EOL);
                        }
//                        $calenderData[$month][$day]['date'].= '    ' . $this->_events[$calcYear][$calcMonth][$day]['event'];
                        $calenderData[$month][$day]['event'] = $this->_events[$calcYear][$calcMonth][$day]['event'];
                        if (!($isHoliday) && !($isWeekend) && isset($this->_events[$calcYear][$calcMonth][$day]['color'])) {
                            $calenderData[$month][$day]['color'] = $this->_events[$calcYear][$calcMonth][$day]['color'];
                        }
                    }
                }
            }
        }

        return $calenderData;
    }
    
    protected function _drawAdditionalData($cellData) 
    {
        if (isset($cellData['event']) && !empty($cellData['event'])) {
            $eventTextExploded = explode("\n", $cellData['event']);
            $y = $this->getPdfClass()->GetY() + $this->_rowHeight*0.6;
            $x = $this->getPdfClass()->GetX() - $this->_colWidth*0.96+7;
            foreach ($eventTextExploded as $text) { 
                $this->getPdfClass()->Text($x, $y, $text);
                $y = $y+2;
            } 
        }
        
        if (isset($cellData['kw']) && !empty($cellData['kw'])) {
            $this->getPdfClass()->SetFontSize(5);        
            $this->getPdfClass()->Text($this->getPdfClass()->GetX() + $this->getPdfClass()->getCellMargin()-7.3, 
                                          $this->getPdfClass()->GetY() + ($this->getPdfClass()->getCellMargin())*1.9, 'KW ' . $cellData['kw']);
        }      
        
        if (isset($cellData['info']) && !empty($cellData['info'])) {
            $this->getPdfClass()->SetFontSize(5);
            $this->getPdfClass()->SetFont('', '');
            $this->getPdfClass()->Text($this->getPdfClass()->GetX() - $this->_colWidth*0.98, 
                                          $this->getPdfClass()->GetY() + $this->_rowHeight*0.95, $cellData['info']);
        }
    }
    
    protected function _drawCalenderData($data)
    {
        //Data
        for($row=1; $row<=31; $row++)
        {
            for($col=1; $col<=12; $col++) {
                
                $dateText = '';
                if (isset($data[$col][$row]['date'])) {
                    $dateText = $data[$col][$row]['date'];
                }

                $fill = false;
                if (!empty($data[$col][$row]['color'])) {
                    if (!empty($dateText)) {
                        $this->getPdfClass()->SetFont('', $data[$col][$row]['fontwidth']);
                    }
                    $rgb = $this->getPdfClass()->hex2rgb($data[$col][$row]['color']);
                    $this->getPdfClass()->SetFillColor($rgb[0], $rgb[1], $rgb[2]);
                    $fill = true;
                }
                
                $this->getPdfClass()->SetFontSize(6);        
                $this->getPdfClass()->Cell($this->_colWidth, $this->_rowHeight , $dateText, 1, 0, '', $fill);
                $this->_drawAdditionalData($data[$col][$row]);
                
                $this->getPdfClass()->SetFont('', '');
            }
            $this->getPdfClass()->Ln();
        }
    }
    
    public function drawCalender()
    {
        setlocale(LC_TIME, 'de_DE');
        $this->getPdfClass()->SetLineWidth(0.1);
        $this->getPdfClass()->SetFont('','B');
        
        if (is_null($this->_startYear)) {
            $year = intval(date('Y'));
        } else {
            $year = $this->_startYear;
        }
        $canvasSizeX = $this->getPdfClass()->GetPageWidth();
        $canvasSizeY = $this->getPdfClass()->GetPageHeight();
        $this->_colWidth = round(($canvasSizeX-($this->_marginLeft+$this->_marginRight))/12, 3);
        $this->_rowHeight = round(($canvasSizeY-($this->_calenderStartY+$this->_marginBottom+$this->_headerHeight))/31, 3);
        
        $this->getPdfClass()->SetY($this->_calenderStartY);
    
        $this->_drawHeader($this->_startMonth-1, $year);
        $data = $this->_generateCalenderData($this->_startMonth-1, $year, array());
        $this->_drawCalenderData($data);
        
        // draw bigger boxes as outline
        $this->getPdfClass()->SetY($this->_calenderStartY);
        $this->getPdfClass()->SetLineWidth(0.4);
            for($col=0; $col<=11; $col++)
                $this->getPdfClass()->Cell($this->_colWidth, ($canvasSizeY-($this->_calenderStartY+$this->_marginBottom)), '', 1);
        
    }
}
