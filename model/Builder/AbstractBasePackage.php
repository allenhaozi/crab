<?php
/**
 *
 *
 * @author allenhaozi@gmail.com
 */
abstract class Builder_AbstractBasePackage
{
	protected $objMobilePackage = null;
	public function __construct()
	{
		$this->objMobilePackage = new Builder_MobilePackage();
	}
}
