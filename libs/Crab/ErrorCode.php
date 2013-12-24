<?php
/**
 *
 * @author allenhaozi@gmail.com
 */
class Crab_ErrorCode
{
	/** 未知错误 */
	const CRAB_EC_UNKNOW = 1;
	/** 无效APP_ID */
	const CRAB_INVALIDATE_APPID = 2;

	/**
	 * 错误码对应错误信息
	 */
	public static $arrError = array(
		self::CRAB_EC_UNKNOW => 'Unknown error',
		self::CRAB_EC_INVALID_APP_ID => 'App_Id %s is not invalid',
	);

	/**
	 * 错误码对应错误信息 中文
	 */
	public static $arrCnError = array(
		self::CRAB_EC_UNKNOW => '未知错误',
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
			return self::$arrError[ self::CRAB_EC_UNKNOW ];
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
			return self::$arrError[ self::CRAB_EC_UNKNOW ];
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
			return self::$arrError[ self::CRAB_EC_UNKNOW ];
		}
	}
}
