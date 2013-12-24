<?php
/**
 * Crab/Action.php
 *
 * Action 基类文件
 *
 * @author songqi
 * @modify allenhaozi@gmail.com
 */
/**
 * Crab_Request
 */
require_once ('Crab/Request.php');
/**
 * Crab_View
 */
require_once ('Crab/View.php');

class Crab_Action
{
	/**
	 * 请求对象
	 *
	 * @var Crab_Request
	 */
	protected $_objRequest = null;
	/**
	 * 是否forward
	 * array( 'action'=>'actionName','controller'=>'controllerName','module'=>'moduleName','input'=>'input');
	 *
	 * @var array
	 */
	protected $_arrForward = array();
	/**
	 * 视图对象
	 *
	 * @var Crab_View_Interface
	 */
	protected $_objView;

	/**
	 * 请求的 GET POST 及 Forward 后的 Input 值
	 *
	 */
	public $_arrRequest = array();
	/**
	 * 构造函数，在子类中不要覆盖构造函数，如果有需要在初始化时做的，请覆盖init函数
	 *    
	 *　声明函数时传入 Crab_Request 对象　
	 *  保证没有调用setRequest 时即可以拿到Crab_Request对象
	 */
	public function __construct( Crab_Request $objRequest ) {
		$this->_objRequest = $objRequest;
		$this->_objView = $objRequest->getView();
		/** 初始化参数 必须放在_objRequest赋值后*/
		$this->init();
	}
	/**
	 * 初始化函数
	 */
	public function init() {
		$this->initSysParam();
	}
	/**
	 * 1) 初始化GET,POST,Forward参数
	 * 
	 */
	public function initSysParam(){
		$this->_arrRequest = $this->_objRequest->getAllParam();
	}

	/**
	 * 封装模版 assign
	 */
	public function assign( $strKey, $mixVal ){
		$this->_objView->assign( $strKey, $mixVal );
	}
	/**
	 * 获得请求对象
	 *
	 * @return Crab_Request
	 */
	public function getRequest() {
		if ( ! $this->_objRequest instanceof Crab_Request ) {
			$this->_objRequest = new Crab_Request();
		}
		return $this->_objRequest;
	}
	/**
	 * 获取请求参数
	 *
	 * @param string $strKey 参数名
	 * @param mixed $mixVal 参数默认值
	 * @return mixed 参数值
	 */
	public function getParam($strKey, $mixVal = null) {
		$objRequest = $this->getRequest();
		return $objRequest->getParam($strKey, $mixVal);
	}
	/**
	 * forward到某个action，不会造成redirect，最后一个forward到的action的模板才会被render。
	 *
	 * @param array $arrForward array('module'=>'...','controller'=>'...','action'=>'...','input'=>array())
	 */
	public function forward( $arrForward ) {
		$this->_arrForward = $arrForward;
	}
	/**
	 * 获取当前forward到的action
	 *
	 * @return array
	 */
	public function getForward() {
		return $this->_arrForward;
	}
	/**
	 * 页面重定向，调用header然后exit
	 *
	 * @param string $strUrl 重定向到的地址
	 */
	public function redirect($strUrl) {
		ob_end_clean();
		header("Location:" . $strUrl);
		exit;
	}
	/**
	 * Dispatch之前要执行的函数，基类的preDispatch什么都不做，是留给子类实现的
	 *
	 * @param string $strActionName 当前执行的Action，由Dispatcher传入
	 */
	public function preDispatch($strActionName) {
	}
	/**
	 * Dispatch之后要执行的函数，积累的postDispatch什么都不做，留给子类实现
	 *
	 * @param string $strActionName 当前执行的Action，由Dispatcher传入
	 */
	public function postDispatch($strActionName) {
	}

	/** 
	 * 输出Json格式的数据
	 */
	public function sendJson( $arrData ){
		header( "Content-Type: application/json" );
		header( "Cache-Control: no-store" );
		echo json_encode( $arrData );  
		exit;
	}
}
