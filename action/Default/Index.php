<?php
/**
 * index entrance
 * @author allenhaozi@gmail.com
 */
class Default_IndexController extends Controller
{
    public function indexAction()
    {
        $this->assign( 'test',$_GET['test'] );
    }
}
