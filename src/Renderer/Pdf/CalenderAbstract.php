<?php

namespace App\Renderer\Pdf;

abstract class CalenderAbstract implements CalenderInterface
{
    /**
     * @var Calender
     */
    protected $_pdfClass = null;
    
    /**
     * @var bool
     */
    protected $_showCalenderWeek = false;
    
    /**
     * @var bool
     */
    protected $_showHolidays = false;

    /**
     * @var bool
     */
    protected $_showEvents= false;
    
    /**
     * @var array
     */
    protected $_events = array();
    /**
     * @var array
     */
    protected $_holidays = array();
        
    /**
     * @var string
     */
    protected $_size = 'a4';

    protected $_startDay = 1;
    protected $_startMonth = 1;
    protected $_startYear = null;
    
    public function __construct($size='a4', $pdfClass=null)
    {
        if (!is_null($pdfClass)) {
            $this->setPdfClass($pdfClass);
        }
    }
    
    /**
     * @return string
     */
    public function getSize() {
        return $this->_size;
    }

	/**
     * @param string $size
     */
    public function setSize($size) {
        $this->_size = $size;
    }

	/**
     * @return Calender
     */
    public function getPdfClass() {
        if (is_null($this->_pdfClass)) {
            $this->_pdfClass = new Calender();
        }
        return $this->_pdfClass;
    }

	/**
     * @param Calender $calenderPdf
     */
    public function setPdfClass($pdfClass) {
        $this->_pdfClass = $pdfClass;
    }
    
    /**
     * @return bool
     */
    public function showHolidays(): bool {
        return $this->_showHolidays;
    }
    
    /**
     * @param bool $yesNo
     */
    public function setShowHolidays($enabled=false)
    {
        $this->_showHolidays = $enabled;
    }

    /**
     * @return bool
     */
    public function showSpecialEvents(): bool {
        return $this->_showEvents;
    }
    
    /**
     * @param bool $yesNo
     */
    public function setShowSpecialEvents($enabled=false)
    {
        $this->_showEvents = $enabled;
    }
    
    /**
	 * the calendar week
	 * @return bool $_useCalenderWeek
     */
    public function showCalenderWeeks(): bool {
        return $this->_showCalenderWeek;
    }

	/**
     * @param bool $useCalenderWeek
     */
    public function setShowCalenderWeeks($enabled=false) {
        $this->_showCalenderWeek = $enabled;
    }


	/**
	 * @param string $size
	 * @return mixed|void
	 */
    public function render(string $size='a4', string $outputPath = '/tmp')
    {
        $this->initPdf($size);
        $this->preRender();
        $this->drawCalender();
        $this->postRender();

        $this->getPdfClass()
			->Output($outputPath . '/test_old.pdf', 'F');
    }
    
	/* (non-PHPdoc)
     * @see Calender_Pdf_Interface::setHolidays()
     */
    public function setHolidays(array $holidays) {
        $this->_holidays = $holidays;
    }

	/* (non-PHPdoc)
     * @see Calender_Pdf_Interface::setSpecialEvents()
     */
    public function setSpecialEvents(array $events) {
        $this->_events = $events;        
    }

    public function initCalender($startDay = 1, $startMonth = 1, $startYear = null) {
        $this->_startDay = $startDay;
        $this->_startMonth = $startMonth;        
        $this->_startYear = $startYear;
    }

	/* (non-PHPdoc)
     * @see Calender_Pdf_Interface::postRender()
     */
    public function postRender() {
        // generated method stub
    }

	/* (non-PHPdoc)
     * @see Calender_Pdf_Interface::preRender()
     */
    public function preRender() {
        // generated method stub
    }
}
