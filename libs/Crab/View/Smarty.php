<?php
/**
 * Crab/View/Smarty.php
 *
 * Smarty视图类文件
 *
 * @author songqi
 *
 */
/**
 * Crab_View_Interface
 */
require_once ('Crab/View/Interface.php');
/**
 * Smarty
 */
require_once ('Smarty/Smarty.class.php');
/**
 * Crab_View_Exception
 */
require_once ('Crab/View/Exception.php');
/**
 * Crab_Controller_Request
 */
require_once ('Crab/Request.php');
/**
 * Crab_Controller_Dispatcher
 */
require_once ('Crab/Dispatcher.php');

class Crab_View_Smarty extends Smarty implements Crab_View_Interface {
    /**
     * 当前访问的Action
     *
     * @var array
     */
    protected $_arrMvc;
    
    /**
     * 子模板，满足一个action有多个输出的需求
     * $arrKeySubTplMap = array("module_controller_action"=>subtplname, );
     * 模板文件名为：原文件名+$_strSubTpl.扩展名
     * @author mod by fushiguang
     * @var array
     */
    protected $_arrKeySubTplMap; 
    /**
     * 配置参数
     *
     * @var array
     */
    protected static $_arrOption = array('template_dir' => '', 'compile_dir' => '', 'left_delimiter' => '<{', 'right_delimiter' => '}>',);
    /**
     * 构造函数，进行Smarty参数配置
     *
     */
    public function __construct() {
        $this->template_dir = self::$_arrOption['template_dir'];
        $this->compile_dir = self::$_arrOption['compile_dir'];
        $this->left_delimiter = self::$_arrOption['left_delimiter'];
        $this->right_delimiter = self::$_arrOption['right_delimiter'];
        $this->register_block("block", array($this, "showBlock"));
        //$this->debugging = true;
    }
    /**
     * 获得当前注册到模板的所有数据
     *
     * @return array
     */
    public function getData() {
        return $this->_tpl_vars;
    }
    /**
     * 设置当前访问的Action
     *
     * @param array $arrMvc
     */
    public function setMvc(array$arrMvc) {
        $this->_arrMvc = $arrMvc;
    }
    /**
     * 渲染并输出模板
     * @author mod by fushiguang
     */
    public function output() {
    	$strSubTplName = '';
    	if (is_array($this->_arrKeySubTplMap)) {
    		$key = $this->_arrMvc['mod'] . "_" . $this->_arrMvc['ctrl'] . "_" . $this->_arrMvc['act'];
    		$key = strtolower($key); 
    		if ($this->_arrKeySubTplMap[$key]) {
    			$strSubTplName = $this->_arrKeySubTplMap[$key];
    		}
    	}
        $strViewFile = ucfirst($this->_arrMvc['mod']) . "/" . ucfirst($this->_arrMvc['ctrl']) . "/" . ucfirst($this->_arrMvc['act']);
        if ($strSubTplName) {
        	$strViewFile .= "-{$strSubTplName}";
        }
        $strViewFile .= ".tpl";
        $this->display($strViewFile);
    }
    /**
     * 设置配置参数
     *
     * @param array $arrOption
     */
    public static function setOption($arrOption) {
        foreach($arrOption as $k => $v) {
            self::$_arrOption[$k] = $v;
        }
    }
    /**
     * block功能支持，使用示例：
     * 在模板中加入：
     * <{block module='front' controller='index' action='header' userid='1234'}>
     * .open {
     *  font-size:12px;
     * }
     * <{/block}>
     * 其中module、controller、header表示该block对应的action，userid是要传给该action的参数，可以有任意多个
     * block中间的内容将被加入到最终block输出内容的前面，因此可以在这里为block自定义样式。如果上面的例子中action渲染的结果是：
     * <span class='open'>just example</span>
     * 那么最终输出的结果是
     * .open {
     *  font-size:12px;
     * }
     * <span class='open'>just example</span>
     * 
     * @param array $arrParams
     * @param string $strContent
     * @param Smarty $objSmarty
     * @param boolean $bolRepeat
     */
    public function showBlock($arrParams, $strContent, &$objSmarty, &$bolRepeat){
        $objRequest = new Crab_Request();
        $objRequest->setInput($arrParams);
        $objDispatcher = Crab_Dispatcher::getInstance();
        ob_start();
        $objDispatcher->dispatch($objRequest);
        $strOutput = ob_get_clean();
        $bolRepeat = false;
        echo  $strContent."\n".$strOutput;
    }
    
    /**
     * @author fushiguang
     * 为当前模板设置多样的输出
     * @param string $strKey = module_controller_action
     * @param string $strSubTpl 子模板名
     * 如果主模板名为Index.tpl，若$strSubTpl="json",则模板名为Index-json.tpl
     */
    public function setSubTpl($strKey, $strSubTpl) {
    	$strKey = strtolower($strKey); 
    	$this->_arrKeySubTplMap = array($strKey=>$strSubTpl);
    }
    
}
