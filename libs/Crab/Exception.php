<?php
/**
 * @author allenhaozi@gmail.com
 */
class Crab_Exception extends Exception {

	/**
	 * @param int $intCode 错误代码
	 * @param mix $mixValue 
	 *
	 */	 
	public function __construct( $intCode, $mixValue = null ){

		/** 传入的参数 */ 
		$arrArgv = func_get_args();
		if( ! empty( $arrArgv[2] ) ){
			$strMsg = $arrArgv[2]; 
		} else {
			$strMsg = Crab_ErrorCode::getErrMsg( $intCode );	
		}
		
		if( ! empty( $mixValue ) )
			$strMsg = sprintf( $strMsg, $mixValue );
		/** 保证输出无 %s 信息 */	
		if( strchr( $strMsg, '%s' ) )
			$strMsg = str_replace( '%s', '', $strMsg );	

		parent::__construct( $strMsg, $intCode );
	}

}
