<?php
/**
 * @author allenhaozi
 */
class Crab_Exception extends Exception {
    /**
     * @var detail info contain file and info
     */
    private $strDetailMsg = '';

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
            $strMsg = Crab_ErrCode::getErrMsg( $intCode );	
        }

        if( ! empty( $mixValue ) )
            $strMsg = sprintf( $strMsg, $mixValue );
        /** 保证输出无 %s 信息 */	
        if( strchr( $strMsg, '%s' ) )
            $strMsg = str_replace( '%s', '', $strMsg );	

        $strFile = $this->getFile();
        $intLine = $this->getLine();

        $strDetail = $strFile . '#' . $intLine . '#' . $strMsg;
        $this->strDetailMsg = $strDetail;	

        parent::__construct( $strMsg, $intCode );
    }

    /**
     * @return string detail info
     */
    public function getDisMsg()
    {
        return $this->strDetailMsg;	
    }

}
