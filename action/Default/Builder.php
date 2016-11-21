<?php
/**
 * @author allenhaozi@gmail.com
 */
class Default_BuilderController extends Controller
{
	public function indexAction()
	{
		$objMobileDirector = new Builder_MobileDirector();

		$objMobileBuilderImpl1 = new Builder_MobileBuilderImpl1();

		$objMobilePackage = $objMobileDirector->createMobilePackage( $objMobileBuilderImpl1 );

		dump( ' money is ' . $objMobilePackage->getMoney() );
		dump( ' msg is ' . $objMobilePackage->getMsg() );
		dump( ' music is ' . $objMobilePackage->getMusic() );

		$objMobileBuilderImpl2 = new Builder_MobileBuilderImpl2();

		$objMobilePackage2 = $objMobileDirector->createMobilePackage( $objMobileBuilderImpl2 );

		dump( ' money is ' . $objMobilePackage2->getMoney() );
		dump( ' msg is ' . $objMobilePackage2->getMsg() );
		dump( ' music is ' . $objMobilePackage2->getMusic() );
		exit;
	}
}

