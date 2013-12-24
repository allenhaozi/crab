<?php
/**
 * dispatcher
 *
 * @author songqi
 * @modify hao.ma.se7en@gmail.com
 */
/**
 * Crab_Request
 */
require_once ('Crab/Request.php');
/**
 * Crab_View
 */
require_once ('Crab/View.php');
/**
 * Crab_Exception
 */
require_once ('Crab/Exception.php');

class Crab_Dispatcher {
    /**
     * singleton pattern
     *
     * @var Crab_Controller_Dispatcher 
     */
    protected static $instance;
    /**
     * configuration args 
     *
     * @var array 
     */
    protected static $_arrOption = array(
		/** default view class */
		'view' => 'Crab_View', 
		/** action dir */
		'mdir' => '', 
    );
    /**
     * object request 
     *
     * @var Crab_Request request object
     */
    protected $_objRequest = null;
    /**
     * @var object Crab_View
     */
    protected $_objView = null;
    /**
     * Controller execute output
     *
     * @var string
     */
    protected $_strOutput = '';
    /**
     * module、controller、action default values
     *
     * @var array
     */
    protected $_arrMvc = array('module' => 'default', 'controller' => 'index', 'action' => 'index',);
    /**
     * 构造函数
     */
    protected function __construct() {
    }
    /**
     * 设置配置参数，配置参数格式如下：
     * array(
     *  'view'	=> 'Crab_View'		//默认的视图类
     * 	'mdir'	=> '',				//modules目录的地址
     * );
     *
     * @param array arrOption    配置参数
     */
	public static function setOption( array $arrOption )
	{
        foreach( $arrOption as $k => $v ) {
            self::$_arrOption[$k] = $v;
        }
    }
    /**
     * distribute by the request
     *
     * @param Crab_Request objRequest  
     * @throws Crab_Exception
     */
	public function dispatch( Crab_Request $objRequest = null )
   {
		/** initialize request */
        if (!is_null($objRequest)) {
            $this->_objRequest = $objRequest;
        } else {
            $this->_objRequest = new Crab_Request();
        }

        $strViewName = self::$_arrOption['view'];

        if ( ! class_exists( $strViewName ) ) {
            throw new Crab_Exception("view class: {$strViewName} dosn`t exist");
        }
        $objView = new $strViewName();
        $this->_objRequest->setView( $objView );

        $this->_arrMvc['module'] = $this->_objRequest->getParam('module', 'default');
        $this->_arrMvc['controller'] = $this->_objRequest->getParam('controller', 'index');
        $this->_arrMvc['action'] = $this->_objRequest->getParam('action', 'index');
        /**
         * 过滤输入数据
         */
        $this->_arrMvc['module'] = preg_replace('/[^0-9a-zA-Z]/','',$this->_arrMvc['module']);
        $this->_arrMvc['controller'] = preg_replace('/[^0-9a-zA-Z]/','',$this->_arrMvc['controller']);
        $this->_arrMvc['action'] = preg_replace('/[^0-9a-zA-Z]/','',$this->_arrMvc['action']);
	
        do {
            $strModuleName = ucfirst($this->_arrMvc['module']);
            $strControllerName = $strModuleName . '_' . ucfirst( $this->_arrMvc['controller'] );
            $strActionName = $this->_arrMvc['action'] . "Action";
            $this->_objRequest->setParam('module', $strModuleName);
            $this->_objRequest->setParam('controller', ucfirst($this->_arrMvc['controller']));
            $this->_objRequest->setParam('action', $this->_arrMvc['action']);
            if ( ! class_exists($strControllerName) ) {
                $strModuleDir = self::$_arrOption['mdir'] . "/" . $strModuleName;
                $strControllerFile = $strControllerDir . "/" . ucfirst($this->_arrMvc['controller']) . ".php";
                if ( ! is_dir( $strModuleDir ) ) {
                    throw new Crab_Exception( "module dir: {$strModuleDir} doesn`t exist" );
                }
                if ( ! is_dir( $strControllerDir ) ) {
                    throw new Crab_Exception(" controller dir: {$strControllerDir} doesn`t exist ");
                }
                if ( ! is_file( $strControllerFile ) ) {
                    throw new Crab_Exception("controller file: {$strControllerFile} doesn`t exist");
                }
                require_once($strControllerFile);
                if ( ! class_exists( $strControllerName ) ) {
                    throw new Crab_Exception("controller class: {$strControllerName} doesn`t exist");
                }
            }

			/** initial controller Crab_Controller_Request */	
            $objController = new $strControllerName( $this->_objRequest );

            if ( ! method_exists( $objController, $strActionName ) ){
                throw new Crab_Exception("action: {$strActionName} doesn`t exist");
            }
			
            ob_start();
			/** predispatch pass request function */
            $objController->preDispatch( $strActionName );

			/** predispatch forward */
            $arrForward = $objController->getForward();
			/** unset objController _arrForward */
            $objController->forward( array() );

            /** $arrDiff difference between current action  and forward action */
            $arrDiff = array_diff_assoc( $arrForward, $this->_arrMvc );

			/** if $arrForward $arrDiff empty */
            if( ! $arrForward || ! $arrDiff ) {
                /** run the request action */
                call_user_func( array( $objController, $strActionName ) );
				/** postdispatch */
                $objController->postDispatch( $strActionName );
				/** get output */
                $this->_strOutput = ob_get_clean();
                $arrForward = $objController->getForward();
            }
			/** forward parameter */
            if( $arrForward['input'] ) {
                $this->_objRequest->setInput( $arrForward['input'] );
                unset( $arrForward['input'] );
            }
			/** difference forward action array and request action array */ 
            $arrDiff = array_diff_assoc( $arrForward, $this->_arrMvc );
            if( $arrDiff ){
                foreach($arrDiff as $k => $v){
                    $this->_arrMvc[$k] = $v;
                }
            }
        } while ( $arrDiff );

		/** judge the view necessary */
        if ($this->_objRequest->hasViewRender() === false) {
            echo $this->_strOutput;
        } else {
			$strSubTplKey = $this->_arrMvc['module'] . '_' 
						  . $this->_arrMvc['controller'] . '_' 
						  . $this->_arrMvc['action'];
        	$strSubTplName = $this->_objRequest->getSubTpl( $strSubTplKey );            
            if( strlen( $strSubTplName ) > 0 ){
            	$objView->setSubTpl($strSubTplKey, $strSubTplName);
            }
            $objView->setMvc($this->_arrMvc);
            $objView->output();
        }
    }
    /**
     * singleton pattern
     *
     * @return Crab_Controller_Dispatcher
     */
    public static function getInstance() {
        if( self::$instance instanceof self ) {
            return self::$instance;
        } else {
            self::$instance = new self( self::$_arrOption );
            return self::$instance;
        }
    }
    /**
     * application default entrance 
     */
    public function run() {
        $objRequest = new Crab_Request();
        $this->dispatch( $objRequest );        
    }
}

