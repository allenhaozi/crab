<?php
/**
 *
 * generalize builder
 *
 */
class Builder_MobileBuilderImpl1 extends Builder_AbstractBasePackage implements Builder_IMobileBuilder
{
	public function buildMoney()
	{
		$this->objMobilePackage->setMoney( 20 );	
	}	
	public function buildMusic()
	{
		$this->objMobilePackage->setMusic( 'seasons in the sun' );
	}
	public function buildMsg()
	{
		$this->objMobilePackage->setMsg( 400 );
	}
	public function getMobilePackage()
	{
		return $this->objMobilePackage;
	}
	
}
