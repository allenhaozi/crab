<?php
/**
 * builder 
 *
 * @author allenhaozi@gmail.com
 */
class Builder_MobilePackage
{
	private $money;
	private $msg;
	private $music;
	public function getMoney()
	{
		return $this->money;
	}
	public function setMoney( $money )
	{
		$this->money = $money;	
	}
	public function getMsg()
	{
		return $this->msg;	
	}
	public function setMsg( $msg )
	{
		$this->msg = $msg;
	}
	
	public function setMusic( $music )
	{
		$this->music = $music;
	}
	
	public function getMusic()
	{
		return $this->music;
	}

}
