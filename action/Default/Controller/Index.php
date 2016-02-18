<?php
/**
 * index entrance
 * @author allenhaozi@gmail.com
 */
class Default_Controller_Index extends Controller
{
    public function indexAction()
    {
        $this->assign( 'test',$_GET['test'] );
    }
}
