<?php

namespace Magenta\Bundle\CBookModelBundle\Service\Spreadsheet;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SpreadsheetWriter
{
    // PHPExcel_Worksheet
    private $workSheet;
    private $cursorRow;
    private $cursorColumn;
    private $lastColumn;
    private $lastRow;
    private $questionColumnArray = array();

    function __construct(Worksheet $workSheet)
    {
        $this->workSheet = $workSheet;
        $this->cursorRow = '1';
        $this->lastRow = '1';
        $this->cursorColumn = 'A';
        $this->lastColumn = 'A';
    }

    public function getCurrentCellStyle()
    {
        return $this->workSheet->getStyle($this->cursorColumn . $this->cursorRow);
    }

    public function writeCellAndGoRight($value = '')
    {
        $this->writeCell($value);
        $this->goRight();

        return $this;
    }

    public function writeCell($value)
    {
        $ws = $this->workSheet;
        $coordinate = $this->cursorColumn . $this->cursorRow;
        $ws->setCellValue($coordinate, $value);

        return $this;
    }

    public function goRight()
    {
        $this->cursorColumn++;
        if ($this->cursorColumn > $this->lastColumn) {
            $this->lastColumn++;
        }

        return $this;
    }

    public function goLeft()
    {
        $this->cursorColumn--;

        return $this;
    }

    public function goDown()
    {
        $this->cursorRow++;
        if ($this->cursorRow > $this->lastRow) {
            $this->lastRow++;
        }

        return $this;
    }

    public function goUp()
    {
        $this->cursorRow--;

        return $this;
    }

    public function getCursor()
    {
        return array($this->cursorColumn, $this->cursorRow);
    }

    public function setCursor($cursor)
    {
        $this->cursorColumn = $cursor[0];
        $this->cursorRow = $cursor[1];
    }

    public function goFirstColumn()
    {
        $this->setCursor(array('A', $this->getCursorRow()));

        return $this;
    }

    public function goFirstRow()
    {
        $this->setCursor(array($this->getCursorColumn(), '1'));

        return $this;
    }

    public function goLastColumn()
    {
        $this->setCursor(array($this->getLastColumn(), $this->getCursorRow()));

        return $this;
    }

    public function goLastRow()
    {
        $this->setCursor(array($this->getCursorColumn(), $this->getLastRow()));

        return $this;
    }

    /**
     * @param string $cursorColumn
     */
    public function setCursorColumn($cursorColumn)
    {
        $this->cursorColumn = $cursorColumn;

        return $this;
    }

    /**
     * @return string
     */
    public function getCursorColumn()
    {
        return $this->cursorColumn;
    }

    /**
     * @param string $cursorRow
     */
    public function setCursorRow($cursorRow)
    {
        $this->cursorRow = $cursorRow;

        return $this;
    }

    /**
     * @return string
     */
    public function getCursorRow()
    {
        return $this->cursorRow;
    }

    /**
     * @param string $lastColumn
     */
    public function setLastColumn($lastColumn)
    {
        $this->lastColumn = $lastColumn;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastColumn()
    {
        return $this->lastColumn;
    }

    /**
     * @param string $lastRow
     */
    public function setLastRow($lastRow)
    {
        $this->lastRow = $lastRow;
    }

    /**
     * @return string
     */
    public function getLastRow()
    {
        return $this->lastRow;
    }

    /**
     * @param Worksheet $workSheet
     */
    public function setWorkSheet($workSheet)
    {
        $this->workSheet = $workSheet;
    }

    /**
     * @return Worksheet
     */
    public function getWorkSheet()
    {
        return $this->workSheet;
    }

    public function writeHeading()
    {
//        $categoryList = $this->categoryList;
        $this->writeCell("Employee's Id");
        $this->goRight();
        $this->writeCell("Employee's Name");
//        foreach ($categoryList as $index_category => $category) {
//            $questionList = $category->getQuestionList();
//            foreach ($questionList as $index_question => $question) {
//                $this->goRight();
//                $this->writeCell('#' . ($index_category + 1) . ':' . $question->getName());
//                $this->questionColumnArray[$question->getId()] = $this->getCursorColumn();
//            }
//        }
        $this->goRight();
        $this->writeCell("Comments");
    }


    public function getSheet()
    {
        return $this->workSheet;
    }

}
