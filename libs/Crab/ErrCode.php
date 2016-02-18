<?php
/**
 *
 * @author allenhaozi
 */
class Crab_ErrCode
{
    /** default success code */
    const EC_SUCCESS = 0;
    /** request failure */
    const EC_RET_FAIL = 1;
    /** invalid request param */
    const EC_INVALID_PARAM = 103;
    /** reqeust failure */ 
    const EC_FAULT = 104;

    /**
     * 错误码对应错误信息
     */
    public static $arrError = array(
        self::EC_SUCCESS => 'success',
        self::EC_RET_FAIL => 'failure',
        self::EC_INVALID_PARAM => 'invalid request param %s',
        self::EC_FAULT => 'request failure %s',
    );

    /**
     * 错误码对应错误信息 中文
     */
    public static $arrCnError = array(
        self::EC_SUCCESS => '请求成功',
    );

    /**
     * 获取错误信息
     *
     * @param int $intErrorCode 错误码
     * 
     */
    public static function getErrMsg( $intErrorCode ){
        if( isset( self::$arrError[ $intErrorCode ]  ) ) {
            return self::$arrError[ $intErrorCode ];
        } else {
            return self::$arrError[ self::EC_RET_FAIL ];
        } 
    }
    /**
     * 获取传入参数后处理信息
     *
     * @param int $intErrorCode 错误码
     * @param mix 替换的参数值
     */
    public static function getMsg( $intErrorCode, $mixValue = null ){
        if( isset( self::$arrError[ $intErrorCode ]  ) ) {
            if( $mixValue !== null )
                $strMsg = sprintf( self::$arrError[ $intErrorCode ] , $mixValue );
            else 
                $strMsg = str_replace( '%s', '', self::$arrError[ $intErrorCode ] );	
            return $strMsg;
        } else {
            return self::$arrError[ self::EC_RET_FAIL];
        }
    } 

    /**
     * 获取传入参数后处理信息 中文提示信息
     *
     * @param int $intErrorCode 错误码
     * @param mix 替换的参数值
     */
    public static function getCnMsg( $intErrorCode ){
        if( isset( self::$arrError[ $intErrorCode ]  ) ) {
            return self::$arrCnError[ $intErrorCode ];
        } else {
            return self::$arrError[ self::EC_RET_FAIL ];
        }
    }
}
