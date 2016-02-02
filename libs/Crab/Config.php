<?php
/**
 * 
 * 配置管理类
 * 
 * @modify allen.mh@alibaba-inc.com
 */
class Crab_Config {
    /**
     * 配置项的值
     *
     * @var array
     */
    protected  static $_arrConfig = array();
    /**
     * @param mixed $mixConfig 配置项的值，可以是关联数组，也可以是其他值
     */
	public static function setConfig( $mixConfig )
	{ 
		self::$_arrConfig = $mixConfig;
    }
    /**
     * 合并配置项的值，相当于数组merge操作
     *
     * @param array $arrConfig 配置项的值，必须是关联数组
     * @param string $strTag 配置项的键，采用$strDelimiter分割，比如 P6600.0.host
     * @param string $strDelimiter 分隔符，默认为点号“.”
     */
    public static function mergeConfig( $arrConfig ) {
        if ( ! is_array( $arrConfig ) ){
            return;
        }
        self::$_arrConfig = array_merge( self::$_arrConfig, $arrConfig );
    }
    /**
     * 获取配置文件的 k => v 值 
     *
     * @param string $strKey 配置文件中数组Key
     * @return mixed
     */
    public static function getConfig( $strKey = null ){
        if ( null !== $strKey ) {
            return self::$_arrConfig[$strKey];
        } else {
            return self::$_arrConfig;
        }
    }
}

