<?php
namespace App\Renderer\Pdf;

interface CalenderInterface
{
    /**
     * @param string $size
     */
    public function initPdf($size='a4');
    
    /**
     * @param string $size
     */
    public function setSize($size);
    
    /**
     * @return string
     */
    public function getSize();
    
    /**
     * @return Calender_Pdf
     */
    public function getPdfClass();
    
    /**
     * @param Calender_Pdf $pdfClass
     */
    public function setPdfClass($pdfClass);
    
    /**
     * @param string $size
     * 
     * @return mixed
     */
    public function render(string $size='a4');
    
    /**
     * @return Calender_Pdf_Interface
     */
    public function drawCalender();
    
    /**
     * @return bool
     */
    public function showCalenderWeeks();
    /**
     * @param bool $enabled
     */
    public function setShowCalenderWeeks($enabled=false);

    /**
     * @return bool
     */
    public function showHolidays();
    /**
     * @param bool $enabled
     */
    public function setShowHolidays($enabled=false);
    
    /**
     * @return bool
     */
    public function showSpecialEvents();
    /**
     * @param bool $enabled
     */
    public function setShowSpecialEvents($enabled=false);

    /**
     * @param array $events
     */
    public function setSpecialEvents(array $events);
    
    /**
     * @param array $holidays
     */
    public function setHolidays(array $holidays);
    
    public function initCalender($startDay=1, $startMonth=1, $startYear=null);
    public function preRender();
    public function postRender();
}