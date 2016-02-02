<?php
class Factory_Caocao implements Factory_ISwordFactory
{
	public function createSword(){
		return new Factory_QixingSword();
	}
}
