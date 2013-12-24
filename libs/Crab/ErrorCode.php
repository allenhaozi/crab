<?php
/**
 * 
 *
 *
 */
class Crab_ErrorCode
{
	/** 未知错误 */
	const API_EC_UNKNOW = 1;
	/** 无效APP_ID */
	const API_INVALIDATE_APPID = 2;

	/**
	 * 错误码对应错误信息
	 */
	public static $arrApiError = array(
		self::API_EC_UNKNOW => 'Unknown error',
		self::API_EC_INVALID_APP_ID => 'App_Id %s is not invalid',
	);

	/**
	 * 错误码对应错误信息 中文
	 */
	public static $arrCnApiError = array(
		self::API_EC_UNKNOW => '未知错误',
	);

	/**
	 * 获取错误信息
	 *
	 * @param int $intErrorCode 错误码
	 * 
	 */
	public static function getErrMsg( $intErrorCode ){
		if( isset( self::$arrApiError[ $intErrorCode ]  ) ) {
			return self::$arrApiError[ $intErrorCode ];
		} else {
			return self::$arrApiError[ self::API_EC_UNKNOW ];
		} 
	}
	/**
	 * 获取传入参数后处理信息
	 *
	 * @param int $intErrorCode 错误码
	 * @param mix 替换的参数值
	 */
	public static function getMsg( $intErrorCode, $mixValue = null ){
		if( isset( self::$arrApiError[ $intErrorCode ]  ) ) {
			if( $mixValue !== null )
				$strMsg = sprintf( self::$arrApiError[ $intErrorCode ] , $mixValue );
			else 
				$strMsg = str_replace( '%s', '', self::$arrApiError[ $intErrorCode ] );	
			return $strMsg;
		} else {
			return self::$arrApiError[ self::API_EC_UNKNOW ];
		}
	} 

	/**
	 * 获取传入参数后处理信息 中文提示信息
	 *
	 * @param int $intErrorCode 错误码
	 * @param mix 替换的参数值
	 */
	public static function getCnMsg( $intErrorCode ){
		if( isset( self::$arrApiError[ $intErrorCode ]  ) ) {
			return self::$arrCnApiError[ $intErrorCode ];
		} else {
			return self::$arrApiError[ self::API_EC_UNKNOW ];
		}
	}
}/
