<?php
/**
 *
 *
 */
abstract class Adapter_AbsBasePower
{
	private $power;
	private $unit = 'V';

	public function __construct( $power )
	{
		$this->power = $power;
	}
	public function getPower()
	{
		return $this->power;
	}
	public function setPower( $power )
	{
		$this->power = $power;	
	}
	public function getUnit()
	{
		return $this->unit;
	}
	public function setUnit( $unit )
	{
		$this->unit = $unit;
	}
}
