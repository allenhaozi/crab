<?php

abstract class AF_BaseFood
{
    public $strKind;
    public $intNum;
    public $floatPrice;

    public function totalPrice()
    {
        return $this->intNum * $this->floatPrice; 
    }
}
