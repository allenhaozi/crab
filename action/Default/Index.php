<?php
/**
 * index entrance
 * @author allenhaozi@gmail.com
 */
class Default_IndexController extends Controller
{
    public function indexAction()
    {
        Log_Build::test( 'test' );
        $this->assign( 'test',$_GET['test'] );
    }
}
