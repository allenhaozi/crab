<?php
/**
 * Crab/View/Interface.php
 *
 * 视图接口文件
 *
 * @category Crab
 * @package Crab_View
 * @author songqi<songqi@baidu.com>
 * @version 1.0
 *
 */
/**
 * Crab_View_Interface
 *
 * 视图接口
 *
 * @category Crab
 * @package Crab_View
 * @author songqi<songqi@baidu.com>
 * @version 1.0
 */
interface Crab_View_Interface {
    /**
     * 获取视图中的数据部分，任何视图都需要实现此功能
     *
     * @return array
     */
    public function getData();
    /**
     * 设置module、controller、action，格式
     * array(
     *  'module' => '',
     *  'controller' => '',
     *  'action' -> '',
     * );
     *
     * @param array $arrMvc
     */
    public function setMvc(array $arrMvc);
    /**
     * @author fushiguang
     * 为当前模板设置多样的输出
     * @param string $strKey = module_controller_action
     * @param string $strSubTpl 子模板名
     * 如果主模板名为Index.tpl，若$strSubTpl="json",则模板名为Index-json.tpl
     */
    public function setSubTpl($strKey, $strSubTpl);
}