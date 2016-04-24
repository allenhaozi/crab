<?php


class AF_ChinaKfcFactory implements AF_IKfcFactory
{
    public function createHamburg( $intNum )
    {
        return new AF_ChinaHamburg( $intNum ); 
    }
    public function createBeverage( $intNum )
    {
        return new AF_ChinaBeverage( $intNum ); 
    }
}
