<?php
/**
 * abstract factory class 
 *
 * abstract product
 *
 * @author allenhaozi@gmail.com
 */
abstract class Factory_AbstractSword
{
	private $name;	

	public function getName()
	{
		return $this->name;
	}	
	public function setName( $name )
	{
		$this->name = $name;
	}
}
