<?php
class Default_Curd extends Crab_Action
{
	public function indexAction()
	{
		$objAppModel = new Dao_App();
		$arrList = $objAppModel->demo();
		exit;
	}
}


