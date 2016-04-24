<?php


class AF_ChinaBeverage extends AF_Beverage
{
    function __construct( $intNum )
    {
        $this->strKind = 'Coca Cola'; 
        $this->floatPrice = 7;
        $this->intNum = $intNum;
    }
}
