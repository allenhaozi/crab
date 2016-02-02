<?php
class Factory_Caocao2 implements Factory_ISwordFactory
{
	public function createSword(){
		return new Factory_BaxingSword();
	}
}
