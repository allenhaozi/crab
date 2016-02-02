<?php
/**
 *
 * generalize builder
 *
 */
class Builder_MobileBuilderImpl2 extends Builder_AbstractBasePackage implements Builder_IMobileBuilder
{
	public function buildMoney()
	{
		$this->objMobilePackage->setMoney( 40 );	
	}	
	public function buildMusic()
	{
		$this->objMobilePackage->setMusic( 'justin bieber baby baby' );
	}
	public function buildMsg()
	{
		$this->objMobilePackage->setMsg( 600 );
	}
	public function getMobilePackage()
	{
		return $this->objMobilePackage;
	}
	
}
