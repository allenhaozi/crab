<?php
/**
 * builder 
 *
 *
 */
class Builder_MobileDirector
{
	public function createMobilePackage( $objMoibleBuilder )
	{
		if( $objMoibleBuilder instanceof Builder_IMobileBuilder ){
			$objMoibleBuilder->buildMoney();
			$objMoibleBuilder->buildMsg();
			$objMoibleBuilder->buildMusic();
			return $objMoibleBuilder->getMobilePackage();
		} else {
			return null;
		}
	}
}

