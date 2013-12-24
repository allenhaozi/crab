<?php
/**
 * bridge debug log 
 *    
 * bridge-web端使用 
 *    1) 路径:  bw-source/inc/common.php 
 *    2) 引用:　在cfg.inc.php require_once('common.php')  
 *    3) 使用:  在代码中直接  TT( $var )
 *    4) 查看:  tailf /home/{$user}/var/bridge_debug.log
 * 
 * @author mahao01@baidu.com
 * @date Thu Jun 27 11:34:17 CST 2013
 */

/** 文件最大限制 M 默认256M */
$intMaxSize = 256;

/** 自定义日志路径 默认 /home/{$user}/var */
$strSelfDir = '';

/**
 * web端调试信息函数
 *
 * @param mix $mixMsg
 * @return boolean
 */
function TT( $mixMsg ){

	global $intMaxSize;
	global $strSelfDir;

	if( is_string( $mixMsg ) ){
		$mixMsg = $mixMsg;
	} else if ( is_array( $mixMsg ) ){
		$mixMsg = var_export( $mixMsg, true );
	} else if ( is_object( $mixMsg ) || is_resource( $mixMsg ) ){
		$mixMsg = serialize( $mixMsg );
	} else if ( is_bool( $mixMsg ) ) {
		if( $mixMsg )
			$mixMsg = 'true';
		else { 
			$mixMsg = 'false';
		}
	} else if( is_null( $mixMsg ) ){
		$mixMsg = 'null';
	}	

	if( empty( $strSelfDir ) ){
		$user = str_replace("\n",'',`echo \$USER` );
		if( empty( $user ) ){
			return false;
		}
		/** log 目录 */
		$dir = '/home/' . $user . '/var/';
	} else {
		$dir = $strSelfDir;
	}

	if( ! is_dir( $dir ) ){
		if( ! mkdir( $dir, 0777, true ) ){
			return false;
		} 		
	}
	/** log 文件名 */	
	$file = $dir . 'bridge_debug.log';

	if( ! is_file( $file ) ){
		`touch $file`;
	} else {
		/** bytes to M */
		$intSize = filesize( $file ) / 1048576;
		/** 超过最大大小限制 删除 */
		if( $intSize > $intMaxSize ){
			`echo '' > $file`;
		}
	} 

	if( ! is_file( $file ) )
		return false;

	$append = "\n" . 'start_' . date('Y-m-d H:i:s') . '################################################' . "\n";
	$mixMsg.= "\n" . 'end___' . date('Y-m-d H:i:s') . '################################################' . "\n";
	$mixMsg = $append . $mixMsg . "\n";

	file_put_contents( $file, $mixMsg, FILE_APPEND );
}

// 浏览器友好的变量输出
function dump($var, $echo=true,$label=null, $strict=true)
{
	echo '<div style="border:1px solid #dbdbdb; padding:5px; margin:5px; width:auto; color:#003300;background:#fff;text-align:left;">';
	$label = ($label===null) ? '' : rtrim($label) . ' ';
	if(!$strict) {
		if (ini_get('html_errors')) {
			$output = print_r($var, true);
			$output = "<pre>".$label.htmlspecialchars($output,ENT_QUOTES)."</pre>";
		} else {
			$output = $label . " : " . print_r($var, true);
		}
	}else {
		ob_start();
		var_dump($var);
		$output = ob_get_clean();
		if(!extension_loaded('xdebug')) {
			$output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
			$output = '<pre>'
				. $label
				. htmlspecialchars($output, ENT_QUOTES)
				. '</pre>';
		}
	}
	if ($echo) {
		echo($output);
		echo '</div>';
		return null;
	}else {
		return $output;
		echo '</div>';
	}
}
function get_client_ip()
{
	if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) &&
			$_SERVER['HTTP_X_FORWARDED_FOR'] != '127.0.0.1') {
				$ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
				$ip = $ips[0];
			} elseif (isset($_SERVER['REMOTE_ADDR'])) {
				$ip = $_SERVER['REMOTE_ADDR'];
			} else {
				$ip = '127.0.0.1';
			}   

		$pos = strpos($ip, ',');
		if ($pos > 0) {
			$ip = substr($ip, 0, $pos);
		}   

		return trim($ip);	
}
