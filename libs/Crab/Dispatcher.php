<?php
/**
 * dispatcher
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
/**
 * Exception
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
    protected $_arrMvc = array('mod' => 'default', 'ctrl' => 'index', 'act' => 'index',);
    /**
     * 构造函数
     */
    protected function __construct() {
    }
    /**
     * 设置配置参数，配置参数格式如下：
     * array(
     *  'view'	=> 'Crab_View'		//默认的视图类
     * 	'mdir'	=> '',				//modles目录的地址
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
     * @throws Exception
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
		if( ! class_exists( $strViewName ) ) {
			throw new Exception("view class: {$strViewName} dosn`t exist");
		}
		$objView = new $strViewName();
		$this->_objRequest->setView( $objView );

        $this->_arrMvc['mod'] = $this->_objRequest->getParam('mod', 'default');
        $this->_arrMvc['ctrl'] = $this->_objRequest->getParam('ctrl', 'index');
        $this->_arrMvc['act'] = $this->_objRequest->getParam('act', 'index');
        /**
         * 过滤输入数据
         */
        $this->_arrMvc['mod'] = preg_replace('/[^0-9a-zA-Z]/','',$this->_arrMvc['mod']);
        $this->_arrMvc['ctrl'] = preg_replace('/[^0-9a-zA-Z]/','',$this->_arrMvc['ctrl']);
        $this->_arrMvc['act'] = preg_replace('/[^0-9a-zA-Z]/','',$this->_arrMvc['act']);
	
        do {
            $strModuleName = ucfirst($this->_arrMvc['mod']);
            $strControllerName = $strModuleName . '_' . ucfirst( $this->_arrMvc['ctrl'] );
            $strActionName = $this->_arrMvc['act'] . "Action";
            $this->_objRequest->setParam('mod', $strModuleName);
            $this->_objRequest->setParam('ctrl', ucfirst($this->_arrMvc['ctrl']));
            $this->_objRequest->setParam('act', $this->_arrMvc['act']);
            if ( ! class_exists( $strControllerName ) ){
                $strModuleDir = self::$_arrOption['mdir'] . "/" . $strModuleName;
                $strControllerFile = $strControllerDir . "/" . ucfirst($this->_arrMvc['ctrl']) . ".php";
                if ( ! is_dir( $strModuleDir ) ) {
                    throw new Exception( "modle dir: {$strModuleDir} doesn`t exist" );
                }
                if ( ! is_dir( $strControllerDir ) ) {
                    throw new Exception(" controller dir: {$strControllerDir} doesn`t exist ");
                }
                if ( ! is_file( $strControllerFile ) ) {
                    throw new Exception("controller file: {$strControllerFile} doesn`t exist");
                }
                require_once( $strControllerFile );
                if ( ! class_exists( $strControllerName ) ) {
                    throw new Exception("controller class: {$strControllerName} doesn`t exist");
                }
            }

			/** initial controller pass the Crab_Request */	
            $objController = new $strControllerName( $this->_objRequest );

            if ( ! method_exists( $objController, $strActionName ) ){
                throw new Exception("action: {$strActionName} doesn`t exist");
            }
			
            ob_start();
			/** predispatch pass pass the action name */
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
						$strSubTplKey = $this->_arrMvc['mod'] . '_' 
						  . $this->_arrMvc['ctrl'] . '_' 
						  . $this->_arrMvc['act'];
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
