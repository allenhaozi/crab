<?php
/**
 * im api restful Action 基类
 *
 * @author hao.ma.se7en@gmail.com
 */
class Controller extends Crab_Action
{
	/**
	 * 拦截器
	 *   1) 根据服务类型做 Forward 内部跳转 
	 * 
	 */
	public function preDispatch( $strAction )
	{
		
	}
	/**
	 * 根据相关参数确定显示 pc im / mobile im
	 *     eg: user-agent / 根据调用的接口等信息
	 *     目前优先级策略: 
	 *          1) 指定im eg: 前端直接指定im 
	 *          2) 根据siteinfo site_type字段 确定显示的webim  
	 */
	public function setImType( $strServiceType )
	{
		if( Im_Service::IM_M_API == $strServiceType ){
			$this->_arrRequest['im_type'] = Im_Service::IM_M;
		} else {
			$this->_arrRequest['im_type'] = $strServiceType;
		}
	}

	/**
	 * 请求参数过滤
	 *     1) 过滤掉参数中含有 / 特殊字符
	 */
	public function filterRequestParam(){
		$this->_arrRequest = $this->_objRequest->getAllParam();
		foreach( $this->_arrRequest as $k => $v ){
			if( strchr( $k , '/' ) ) 
				unset( $this->_arrRequest[$k] );
		}   
	}	
	/**
	 * 根据请求确定 Forward 到对应 Action
	 */
	public function getServiceType()
	{
		$strPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$objService = Im_Service::getInstance();
		$this->objService = Im_Service::getInstance();
		return $objService->getServiceType( $strPath );
	}	

}
