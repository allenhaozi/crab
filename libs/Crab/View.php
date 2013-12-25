<?php
/**
 * Crab/View.php
 *
 * 视图类文件
 *
 * @category Crab
 * @package Crab_View
 * @author songqi
 * @version 1.0
 *
 */
/**
 * Crab_View_Exception
 */
require_once ('Crab/View/Exception.php');
 
class Crab_View implements Crab_View_Interface {
    /**
     * module、controller、action名
     *
     * @var array
     */
    protected $_arrMvc;
    /**
     * 配置参数
     *
     * @var array
     */
    protected static $_arrOption = array('tdir' => '');
    /**
     * 返回数据
     *
     * @var array
     */
    protected $_arrData = array();
    /**
     * 设置Option
     *
     * @param array $arrOption
     */
    public static function setOption($arrOption) {
        foreach($arrOption as $k => $v) {
            self::$_arrOption[$k] = $v;
        }
    }
    /**
     * 设置变量值
     *
     * @param string $strKey
     * @param mixed $mixVal
     */
    public function assign($strKey, $mixVal) {
        $this->_arrData[$strKey] = $mixVal;
    }
    /**
     * 获取值
     *
     * @param string $strKey
     * @return array
     */
    public function __get($strKey) {
        return $this->_arrData[$strKey];
    }
    /**
     * 设置当前Action
     *
     * @param array $arrMvc
     */
    public function setMvc(array$arrMvc) {
        $this->_arrMvc = $arrMvc;
    }
    /**
     * 获取注册到视图的数据
     *
     * @return array
     */
    public function getData() {
        return $this->_arrData;
    }
    /**
     * 渲染并输出
     *
     */
    public function output() {
        $strViewFile = self::$_arrOption['tdir'] . "/" . ucfirst($this->_arrMvc['mod']) . "/" . ucfirst($this->_arrMvc['ctrl']) . "/" . ucfirst($this->_arrMvc['act']) . ".php";
        if (!is_file($strViewFile)) {
            throw new Crab_View_Exception("view file: {$strViewFile} doesn`t exist");
        }
        require_once ($strViewFile);
    }
	/**
     * @author fushiguang
     * 为当前模板设置多样的输出
     * @param string $strKey = module_controller_action
     * @param string $strSubTpl 子模板名
     * 如果主模板名为Index.tpl，若$strSubTpl="json",则模板名为Index-json.tpl
     */
    public function setSubTpl($strKey, $strSubTpl) {
    	throw new Crab_Exception("function setSubTpl is not support by Crab_View");
    }
}
