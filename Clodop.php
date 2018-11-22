<?php

namespace petcircle\lodop;

class Clodop
{
    protected $variableName = 'CLODOP';

    protected $printTaskName = '';

    protected $printSize;

    protected $prints = [];

    /**
     * Lodop constructor.
     *
     * @param string $printTaskName
     */
    public function __construct($printTaskName = '')
    {
        $this->printTaskName = $printTaskName;
    }

    /**
     * 设定纸张大小
     *
     * @param $intOrient
     * @param $intPageWidth
     * @param $intPageHeight
     * @param null $strPageName
     * @return $this;
     */
    public function setPrintSize($intOrient,$intPageWidth,$intPageHeight,$strPageName = null)
    {
        $this->printSize =  $this->getVariableName() . ".SET_PRINT_PAGESIZE('$intOrient','$intPageWidth','$intPageHeight','$strPageName');";
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrintSize()
    {
        return $this->printSize;
    }

    public function setPrintStyle($strStyleName, $varStyleValue)
    {
        return $this->addPrint("SET_PRINT_STYLE('$strStyleName', '$varStyleValue');");
    }

    public function setPrintStylea($varItemNameID, $strStyleName, $varStyleValue)
    {
        return $this->addPrint("SET_PRINT_STYLEA('$varItemNameID','$strStyleName','$varStyleValue');");
    }

    /**
     * 增加超文本项
     *
     * @param $intTop
     * @param $intLeft
     * @param $intWidth
     * @param $intHeight
     * @param $strHtml
     */
    public function addPrintHtml($intTop,$intLeft,$intWidth,$intHeight,$strHtml)
    {
        $strHtml = $this->jsNewLineString($strHtml);

        return $this->addPrint("ADD_PRINT_HTM('$intTop','$intLeft','$intWidth','$intHeight','$strHtml');");
    }

    /**
     * 增加纯文本项
     *
     * @param $intTop
     * @param $intLeft
     * @param $intWidth
     * @param $intHeight
     * @param $strContent
     */
    public function addPrintText($intTop,$intLeft,$intWidth,$intHeight,$strContent)
    {
        $strContent = $this->jsNewLineString($strContent);

        return $this->addPrint("ADD_PRINT_TEXT('$intTop','$intLeft','$intWidth','$intHeight','$strContent');");
    }

    public function addPrintTable($intTop,$intLeft,$intWidth,$intHeight,$strHtml)
    {
        $strHtml = $this->jsNewLineString($strHtml);

        return $this->addPrint("ADD_PRINT_TABLE('$intTop','$intLeft','$intWidth','$intHeight','$strHtml');");
    }

    /**
     * 画图形
     *
     * @param $intShapeType
     * @param $intTop
     * @param $intLeft
     * @param $intWidth
     * @param $intHeight
     * @param $intLineStyle
     * @param $intLineWidth
     * @param $intColor
     */
    public function addPrintShape($intShapeType,$intTop,$intLeft,$intWidth,$intHeight,$intLineStyle,$intLineWidth,$intColor)
    {
        return $this->addPrint("ADD_PRINT_SHAPE('$intShapeType','$intTop','$intLeft','$intWidth','$intHeight','$intLineStyle','$intLineWidth','$intColor');");
    }

    public function addPrintBarcode($Top,$Left,$Width,$Height,$BarCodeType,$BarCodeValue)
    {
        return $this->addPrint("ADD_PRINT_BARCODE('$Top','$Left','$Width','$Height','$BarCodeType','$BarCodeValue');");
    }

    public function addPrintUrl($intTop,$intLeft,$intWidth,$intHeight,$strURL)
    {
        return $this->addPrint("ADD_PRINT_URL('$intTop','$intLeft','$intWidth','$intHeight','$strURL');");
    }

    public function addPrintImage($Top,$Left,$Width,$Height,$strHtml)
    {
        return $this->addPrint("ADD_PRINT_IMAGE('$Top','$Left','$Width','$Height','$strHtml');");
    }

    public function addPrintLine($Top1,$Left1,$Top2,$Left2,$intLineStyle,$intLineWidth)
    {
        return $this->addPrint("ADD_PRINT_LINE('$Top1','$Left1','$Top2','$Left2','$intLineStyle','$intLineWidth');");
    }

    public function addPrintRect($Top,$Left,$Width,$Height,$intLineStyle,$intLineWidth)
    {
        return $this->addPrint("ADD_PRINT_RECT('$Top','$Left','$Width','$Height','$intLineStyle','$intLineWidth');");
    }

    public function addPrint($printContent, $withVariablePrefix = true)
    {
        if ($withVariablePrefix) {
            $printContent = $this->getVariableName() . '.' . $printContent;
        }

        $this->prints[] = $printContent;
        return $this;
    }

    public function jsNewLineString($string)
    {
        return str_replace("\n", "\\\n", $string);
    }

    /**
     * function(TaskID,Value){ alert("打印结果:"+Value);
     *
     * @param $func
     * @return $this
     */
    public function addOnReturn($func)
    {
        $this->addPrint($this->getVariableName() . ".On_Return= $func;");
        return $this;
    }

    public function getPrints()
    {
        return implode("", $this->prints);
    }

    public function getInit()
    {
        $init = [];

        if ($this->getPrintSize()) {
            $init[] = $this->getPrintSize();
        }

        return implode("", $init);
    }

    public function setPrinterIndexa($index)
    {
        return $this->addPrint( "SET_PRINTER_INDEXA($index);");
    }

    /**
     * 新分页
     *
     * @return Clodop
     */
    public function newPage()
    {
        return $this->addPrint('NEWPAGE();');
    }

    /**
     * @param bool $withInit
     * @return string
     */
    public function preview($withInit = true)
    {
        return $this->output($withInit, 'PREVIEW');
    }

    /**
     * @param bool $withInit
     * @return string
     */
    public function printNormal($withInit = true)
    {
        return $this->output($withInit, 'PRINT');
    }

    /**
     * 直接打印
     *
     * @param bool $withInit
     * @return string
     */
    public function printa($withInit = true)
    {
        return $this->output($withInit, 'PRINTA');
    }

    /**
     * @param bool $withInit
     * @return string
     */
    public function printSetup($withInit = true)
    {
        return $this->output($withInit, 'PRINT_SETUP');
    }

    public function printDesign($withInit = true)
    {
        return $this->output($withInit, 'PRINT_DESIGN');
    }

    /**
     * 輸出結果
     *
     * @param bool $withInit
     * @param string $methodName
     * @return string
     */
    protected function output($withInit = true, $methodName)
    {
        $scripts = '';
        if ($withInit) {
            $scripts .= $this->getInit();
        }

        $scripts .= $this->getPrints();

        $scripts .= $this->getVariableName() . ".$methodName();";

        return "(function(){ $scripts })";
    }

    /**
     * Get variableName
     *
     * @return string
     */
    public function getVariableName()
    {
        return $this->variableName;
    }

    /**
     * Set variableName
     *
     * @param  string $variableName
     * @return $this
     */
    public function setVariableName($variableName)
    {
        $this->variableName = $variableName;
        return $this;
    }
}