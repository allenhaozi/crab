<?php

abstract class AF_Hamburg extends AF_BaseFood implements AF_IFood
{
    public function printMessage()
    {
        $msg = 'kind is ' . $this->strKind . ' price ' . $this->floatPrice . 
               ' num is ' . $this->intNum . ' total is ' . $this->totalPrice(); 

        dump( $msg );
    }
}
