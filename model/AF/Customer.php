<?php

class AF_Customer
{
    public $objFactory = null;

    function __construct( $objFactory )
    {
        $this->objFactory = $objFactory; 
    }

    public function orderHamburg( $intNum )
    {
        $objHamburg = $this->objFactory->createHamburg( $intNum ); 
        $objHamburg->printMessage();
    }

    public function orderBeverage( $intNum )
    {
        $objVeverage = $this->objFactory->createBeverage( $intNum ); 
        $objVeverage->printMessage();
    }
}
