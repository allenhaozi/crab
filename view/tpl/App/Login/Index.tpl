<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>商桥开放平台后台管理</title>
<style type="text/css">
#all {margin-left:auto; margin-right:auto; text-align: center;width: 540px;}
body {text-align:center;}
#main {height:240px; text-align:center;}
#title {height:66px;margin-top: 120px;}
#login { margin-top: 32px; width: 420px; margin-left: auto; margin-right:auto;}
#btm_left {background:url(../../../oa_static/images/login_btm_left.gif) no-repeat; width:21px; float:left;}
#btm_mid {background:url(../../../oa_static/images/login_btm_mid.gif); width:498px; float:left;}
#btm_right {background:url(../../../oa_static/images/login_btm_right.gif) no-repeat; width:21px; float:left;}
</style>
	<script type="text/javascript" language="javascript">
function reset_form()
{
	document.getElementById('username').value = '';
	document.getElementById('password').value = '';
	return false;
}

</script>
</head>

<body>
<div id="all">
	<span style="font-size:50px;"> 商桥开放平台 </span>
<div id="main">
<form action="<{$strAction}>" method="post" id="login_form">
<table id="login">
	<tr>
		<td>用户名: </td>
		<td>
		<input type="text" name="username" id="username" size="32" style="background:url(../../../oa_static/images/username_bg.gif) left no-repeat #FFF; border:1px #ccc solid;height: 20px; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight: 800; margin:0; padding-left: 24px;" /></td>
</tr>
<tr><td></td><td></td></tr>
<tr><td></td><td></td></tr>
<tr>
<td>密码: </td>
<td><input type="password" name="password" id="password" size="32" style="background:url(../../../oa_static/images/password_bg.gif) left no-repeat #FFF; border: 1px #ccc solid; height: 20px; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight: 800; margin:0; padding-left: 24px;" /></td>
</tr>
<tr>
<td></td>
<td><span style="color:red"><{$strMsg}></span></td>
</tr>
<tr>
<td style="text-align: left; padding-top: 32px;">
<input type="image" src="../../oa_static/images/login.gif" name="submit" onclick="javascript:document.getElementById('login_form').submit();" />&nbsp;&nbsp;&nbsp;
<!--
<input type="image" src="images/cancel.gif" name="cancel" onclick="reset_form();" />
-->
</td>
</tr>
</table>
</div>

</div>
</body>
</html>

