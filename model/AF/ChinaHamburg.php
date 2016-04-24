<?php


class AF_ChinaHamburg extends AF_Hamburg
{
    function __construct( $intNum )
    {
        $this->strKind = 'Spicy'; 
        $this->floatPrice = 10;
        $this->intNum = $intNum;
    }
}
