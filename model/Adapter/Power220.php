<?php

class Adapter_Power220 extends Adapter_AbsBasePower implements Adapter_IPower220
{
	public function __construct()
	{
		parent::__construct( 220 );
	}
	public function output220v()
	{
		$str =  'This is ' . $this->getPower() . "\t" . $this->getUnit() . " power ";
		dump( $str );
		exit;
	}
}
