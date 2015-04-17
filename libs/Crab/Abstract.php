<?php
/**
 * relay / proxy ... 基类
 *
 * author allenhaozi@gmail.com
 */
abstract class Crab_Abstract
{
	/** 
	 * @var request params 
	 */	
	private $arrParam = array();
	/**
	 * @var response params 
	 *
	 */
	private $arrResponse = array();
	/**
	 * arrParam set 
	 * @param array $arrRequest request args or property
	 */
	public function setParam( $arrRequest = array() )
	{
		$this->arrParam = array_merge( $this->arrParam, $arrRequest );	
	}
	/**
	 * arrParam get
	 * @param array
	 */
	public function getParam( $mixKey )
	{
		if( isset( $mixKey ) ){
			return $this->arrParam[$mixKey];	
		} else {
			return $this->arrParam;	
		}	
	}
	/**
	 * arrResponse set
	 * @param array
	 */
	public function setResponse( $arrParam = array() )
	{
		$this->arrResponse = array_merge( $this->arrResponse, $arrParam );	
	}
	/**
	 * arrResponse get
	 * @return array response
	 */
	public function getResponse( $mixKey )
	{
		if( isset( $mixKey ) ){
			return $this->arrResponse[$mixKey];	
		} else {
			return $this->arrResponse;	
		}
	}
}
