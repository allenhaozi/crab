<?php
/**
 * User model Demo类
 *
 * @author hao.ma.se7en@gmail.com
 */
class Dao_App extends Crab_Model
{
	public function demo()
	{
		/** select */
		$this->getAppList();
		/** insert */
		//$this->save();
		/** update */
		//$this->update();
		dump( $this->getLastSql() );
		exit;
	}

	public function getAppList()
	{
		/** 查询全部数据 */
		//$arrList = $this->select();
		/** 连贯操作 */
		$arrList = $this->where('id>2004')->order('id')->limit(3)->select();
		/** 指定field */
		//$arrList = $this->field('id,app_secret,app_name')->where('id>2004')->select();

		dump( $arrList );
		$sql = $this->getLastSql();
		dump( $sql );
		exit;
		return $arrList;
	}
	
	public function update()
	{
		$arrRow['contact'] = 'mahao' . time();
		$arrRow['app_secret'] = 'aaf58b5ef3ab3b90e2fa8b3d42c7efe5';
		$arrRow['desc'] = date('Ymd H:i:s') . ' test demo '; 
		$arrRow['c_time'] = time();
		$arrRow['app_name'] = 'demo';
		$arrRow['product'] = 'demo_product';
		/** 存储数据 */
		$r = $this->data( $arrRow )->where('id=2017')->save();
		dump( $r );
		$list = $this->where("contact='{$arrRow['contact']}'")->select();
		dump( $list );
	}
	public function save()
	{
		$arrRow['contact'] = 'mahao' . time();
		$arrRow['app_secret'] = 'aaf58b5ef3ab3b90e2fa8b3d42c7efe5';
		$arrRow['desc'] = date('Ymd H:i:s') . ' test demo '; 
		$arrRow['c_time'] = time();
		$arrRow['app_name'] = 'demo';
		$arrRow['product'] = 'demo_product';
		/** 存储数据 */
		$r = $this->data( $arrRow )->add();
		$this->add( $arrRow );
		dump( $r );
		$list = $this->where("contact='mahao01@baidu.com'")->select();
		dump( $list );
	}

	/**
	 * 没有实现此函数默认配置 
	 * 
	 * DB_NAME 类名映射/通过__define定义
	 * DB_SERV DB服务器配置 包括主从,端口,用户名,密码等
	 * DB_NAME 数据库DataBase的选择
	 */
	function __define()
	{
		list( $intId ) = func_get_args();
		return array(
			self::DB_SERV => 'CRAB_DB_6602',
			self::DB_NAME => 'brg_openapi',
			self::DB_TABLE => 'api_app',
		);
	}	
}	

