<?php

namespace App\Renderer\Pdf;

class CalendarDimension
{
    /** @var float */
    private $top;

    /** @var float */
    private $left;

    /** @var float */
    private $headerHeight;

    /** @var float */
    private $columnWidth;

    /** @var float */
    private $rowHeight;

    /**
     * @return float
     */
    public function getTop(): float
    {
        return $this->top;
    }

    /**
     * @param float $top
     * @return CalendarDimension
     */
    public function setTop(float $top): CalendarDimension
    {
        $this->top = $top;
        return $this;
    }

    /**
     * @return float
     */
    public function getLeft(): float
    {
        return $this->left;
    }

    /**
     * @param float $left
     * @return CalendarDimension
     */
    public function setLeft(float $left): CalendarDimension
    {
        $this->left = $left;
        return $this;
    }

    /**
     * @return float
     */
    public function getHeaderHeight(): float
    {
        return $this->headerHeight;
    }

    /**
     * @param float $headerHeight
     * @return CalendarDimension
     */
    public function setHeaderHeight(float $headerHeight): CalendarDimension
    {
        $this->headerHeight = $headerHeight;
        return $this;
    }

    /**
     * @return float
     */
    public function getColumnWidth(): float
    {
        return $this->columnWidth;
    }

    /**
     * @param float $columnWidth
     * @return CalendarDimension
     */
    public function setColumnWidth(float $columnWidth): CalendarDimension
    {
        $this->columnWidth = $columnWidth;
        return $this;
    }

    /**
     * @return float
     */
    public function getRowHeight(): float
    {
        return $this->rowHeight;
    }

    /**
     * @param float $rowHeight
     * @return CalendarDimension
     */
    public function setRowHeight(float $rowHeight): CalendarDimension
    {
        $this->rowHeight = $rowHeight;
        return $this;
    }
}