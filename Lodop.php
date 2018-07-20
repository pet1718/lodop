<?php

namespace petcircle\lodop;

class Lodop
{
    protected $variableName = 'LODOP';

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
        $this->printSize = $this->getVariableName() . ".SET_PRINT_PAGESIZE($intOrient,$intPageWidth,$intPageHeight,$strPageName);";
        return $this;
    }

    public function getPrintSize()
    {
        return $this->printSize;
    }

    public function setPrintStyle($strStyleName, $varStyleValue)
    {
        $this->addPrint($this->getVariableName() . ".SET_PRINT_STYLE($strStyleName, $varStyleValue);");
        return $this;
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
        $this->addPrint($this->getVariableName() . ".ADD_PRINT_HTM($intTop,$intLeft,$intWidth,$intHeight,$strHtml);");
        return $this;
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
        $this->addPrint($this->getVariableName().".ADD_PRINT_TEXT($intTop,$intLeft,$intWidth,$intHeight,$strContent);");
        return $this;
    }

    public function addPrintTable($intTop,$intLeft,$intWidth,$intHeight,$strHtml)
    {
        $this->addPrint($this->getVariableName() . ".ADD_PRINT_TABLE($intTop,$intLeft,$intWidth,$intHeight,$strHtml);");
        return $this;
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
        $this->addPrint($this->getVariableName() . ".ADD_PRINT_SHAPE($intShapeType,$intTop,$intLeft,$intWidth,$intHeight,$intLineStyle,$intLineWidth,$intColor);");
        return $this;
    }

    public function addPrintBarcode($Top,$Left,$Width,$Height,$BarCodeType,$BarCodeValue)
    {
        $this->addPrint($this->getVariableName() . ".ADD_PRINT_BARCODE($Top,$Left,$Width,$Height,$BarCodeType,$BarCodeValue);");
        return $this;
    }

    public function addPrintUrl($intTop,$intLeft,$intWidth,$intHeight,$strURL)
    {
        $this->addPrint($this->getVariableName() . ".ADD_PRINT_URL($intTop,$intLeft,$intWidth,$intHeight,$strURL);");
        return $this;
    }

    public function addPrint($printContent)
    {
        $this->prints[] = $printContent;
        return $this;
    }

    /**
     * function(TaskID,Value){ alert("打印结果:"+Value);
     *
     * @param $func
     * @return $this
     */
    public function AddOnReturn($func)
    {
        $this->addPrint($this->getVariableName() . ".On_Return= $func;");
        return $this;
    }

    public function getPrints()
    {
        return implode("\n", $this->prints);
    }

    public function getInit()
    {
        $init = [$this->getVariableName() . '=getLodop();'];

        if ($this->getPrintSize()) {
            $init[] = $this->getPrintSize();
        }

        return implode("\n", $init);
    }

    /**
     * @param bool $withInit
     * @return string
     */
    public function preview($withInit = true)
    {
        return $this->output($withInit, 'PRIVIEW');
    }

    /**
     * @param bool $withInit
     * @return string
     */
    public function print($withInit = true)
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

        return $scripts;
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