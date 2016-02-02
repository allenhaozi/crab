<?php
/**
 * Crab/Controller/Request.php
 *
 * 请求处理类文件
 *
 * @category Crab
 * @package Crab_Controller
 * @author songqi
 * @modify allenhaozi
 */
/**
 * Crab_View_Interface
 */
require_once ('Crab/View/Interface.php');
/**
 * Crab_View
 */
require_once ('Crab/View.php');
class Crab_Request {
    /**
     * 对象构造时传入的输入参数
     *
     * @var array
     */
    protected $_arrInput = array();
    /**
     * 该请求对应的响应的视图对象
     *
     * @var Crab_View_Interface
     */
    protected $_objView = null;

    /**
     * 设置arrInput参数，当在处理输入参数时，优先处理arrInput，之后是$_POST、$_GET
     *
     * @param array $arrInput input参数，可用于覆盖POST、GET参数
     */
    public function setInput(array$arrInput) {
        $this->_arrInput = $arrInput;
    }
    /**
     * 是否有view类来展示数据
     *
     * @var bool
     */
    private $_bolHasViewRender = true;
    /**
	*子模板设置
	* key=》val，key必须是module_controller_action
	*/
    private $_arrSubTplMap;
    /**
     * 传入参数名，获得参数值。拥有可选的默认值
     *
     * @param string strKey    参数名
     * @param mixed mixVal    默认参数值，当参数不存在时会取该值。
     * @return mixed
     */
    public function getParam($strKey, $mixVal = null) {
        if (!is_null($this->_arrInput[$strKey])) {
            return $this->_arrInput[$strKey];
        }
        if (!is_null($_POST[$strKey])) {
            return $_POST[$strKey];
        }
        if (!is_null($_GET[$strKey])) {
            return $_GET[$strKey];
        }
        if (!is_null($mixVal)) {
            return $mixVal;
        }
        return null;
    }
	/**
	 * 取得Request参数
	 *
	 * @return array
	 */
	public function getAllParam(){
		if( ! is_array( $this->_arrInput ) ) {
			$this->_arrInput = array();
		}
		return array_merge( $_GET, $_POST, $this->_arrInput );		
	}
	 
    
	public function setParam($strKey, $mixVal = null) {
        if (!$this->_arrInput) {
        	$this->_arrInput = array();
        }
        $this->_arrInput[$strKey] = $mixVal;
    }
    /**
     * 设置视图对象
     *
     * @param Crab_View_Interface $objView 视图对象
     */
    public function setView(Crab_View_Interface $objView) {
        $this->_objView = $objView;
    }
    /**
     * 获得当前请求的响应对象
     *
     * @return Crab_View 视图对象
     */
    public function getView() {
        if ($this->_objView == null) {
            $this->_objView = new Crab_View();
        }
        return $this->_objView;
    }
    /**
     * 设置当前request不需要视图类
     *
     */
    public function setNoViewRender() {
        $this->_bolHasViewRender = false;
    }
    /**
     * 获得当前请求是否需要视图类
     *
     * @return bool true表示有， false表示没有
     */
    public function hasViewRender() {
        return $this->_bolHasViewRender;
    }
    
    public function addSubTpl($strKey, $strVal) {
    	if (!$this->_arrSubTplMap) {
    		$this->_arrSubTplMap = array();
    	}
    	$strKey = strtolower($strKey);
    	list($strM, $strC, $strA) = split("_", $strKey);
    	if (empty($strM) || empty($strC) || empty($strA)) {
    		throw new Crab_Exception("the subtpl key is Malformated, should be module_controller_action");
    	}
    	$strVal = trim($strVal);
    	$this->_arrSubTplMap[$strKey] = $strVal; 
    }
    
	public function getSubTpl($strKey) {
		$strRet = '';
		$strKey = strtolower($strKey);
    	if ($this->_arrSubTplMap) {
    		$strRet = $this->_arrSubTplMap[$strKey];
    		$strRet = ($strRet===null?'':$strRet);
    	}
    	return $strRet;
    }
}

