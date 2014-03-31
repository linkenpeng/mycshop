<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理登陆</title>
<meta content="管理登陆" name="keywords">
<meta content="管理登陆" name="description">
</head>
<body>

<script language="javascript">
<!--
function checkvalid(obj)
{
  if (obj.username.value=="")
 {
	 window.alert("请输入用户名!");
	 obj.username.focus();
	 return false;
 }
 else if (obj.password.value=="")
 {
 	window.alert("请输入用户密码!");
	 obj.password.focus();
	 return false;
 }
  else if (obj.checkcode.value=="")
 {
 	window.alert("请输入验证码!");
	 obj.checkcode.focus();
	 return false;
 }
 return true;
}
function pressCaptcha(obj)//字母变大写
  {
    obj.value = obj.value.toUpperCase();
  }
//-->
</script>
<style type="text/css">
<!--
body
{
	margin: 0;
	padding: 0;
	font: 12px Verdana;
	color: #000000;
	background: #f5f5f5;
	text-align: center;
}
form
{
	margin: 0;
	padding: 0;
}
a {
color:#0000FF;
text-decoration:none;
}
a:hover {
color:#990000;
text-decoration:underline;
}
div
{
	font: 12px Verdana;
	text-align: left;
}
.text
{
	border: 1px solid #ffffff;
	width: 156px;
	font: 12px Verdana;
	color: #1065ac;
}
.inp
{
	border: 1px solid #ffffff;
	font: 12px Verdana;
	color: #1065ac;
	width: 70px;
}
.button
{
	font: bold 12px Verdana;
	color: #ffffff;
	background: #1065ac;
	border: 1px solid #1065ac;
	cursor: pointer;
}
#main
{
	margin: 80px auto 0 auto;
	width: 600px;
	height: 400px;
	background: #e0e0e0;
}
#header
{
	padding: 3px;
	color: #ffffff;
	background: #1065ac;
	font: bold 17px Verdana;
	text-align:center;
}
#title
{
	padding: 10px 0 10px 10px;
	color: #1065ac;
	background: #ffffff;
	font-weight: bold;
	border-bottom: 1px solid #c0c0c0;
}
#footer
{
	padding: 10px 10px 10px 0;
	border-top: 1px solid #ffffff;
	font: bold 11px Verdana;
	text-align: right;
	color: #1065ac;
}
#content
{
	height: 300px !important;
	height: 305px;
	border-top: 1px solid #ffffff;
	border-bottom: 1px solid #c0c0c0;
}
#content_left
{
	float: left;
	width: 200px;
	height: 250px;
}
#content_left img
{
	margin: 10px;
}
#content_right
{
	float: left;
	margin-top: 10px;
	width: 390px !important;
	width: 380px;
	height: 230px;
	background: #a8a8a8;
}
#content_right table
{
	margin: 50px 0 0 10px;
	color: #ffffff;
	font: 12px Verdana;
}
-->
</style>
<div id="main">
	<div id="header">河北红色旅游景区景点管理系统</div>
	<div id="title">User Login</div>
	<div id="content">
			<div id="content_left"><img src="<?php echo ADMIN_TEMPLATE_URL;?>/images/splash.jpg" width="179" height="229" /></div>
			<div id="content_right">
	<form action="<?php echo ROUTE;?>?<?php echo M;?>=admin&<?php echo C;?>=login&<?php echo A;?>=check_user_login" method="post" name="admin_login" onsubmit="return checkvalid(document.admin_login);">
		<input type="hidden" name="usertype" value="<?php echo ADMIN_USER_TYPE;?>" />
		<table cellpadding="2" cellspacing="0" border="0" width="100%">
		<tbody>
		<tr>
		<td colspan="2" height="30">请输登录帐号及密码：</td>
		</tr>
		<tr>
		<td align="right"><strong>用户名:</strong></td>
		<td><input type="text" name="username" class="text" id="username"/></td>
		</tr>
		<tr>
		<td align="right"><strong>密码:</strong></td>
		<td><input type="password" name="password" class="text" id="password"/></td>
		</tr>
		<tr>
		<td align="right"><strong>验证码:</strong></td>
		<td><input name="checkcode"  type="text" class="inp" id="checkcode" size="6" maxlength="8" />
		<img align="absbottom" src="<?php echo ROUTE;?>?<?php echo M;?>=admin&<?php echo C;?>=login&<?php echo A;?>=showcode"  onclick=this.src="<?php echo ROUTE;?>?<?php echo M;?>=admin&<?php echo C;?>=login&<?php echo A;?>=showcode&"+Math.random() style="cursor: pointer;" title="看不清？点击更换验证码。"/>
		</td>
		</tr>
		<tr>
		<td align="right"><strong></strong></td>
		<td><input type="checkbox" value="2592000" id="ls_cookietime" name="cookietime"> 下次自动登录</td>
		</tr>
		<tr>
		<td></td>
		<td><input type="submit" value="Login" class="button"/></td>
		</tr>
		</tbody>
		</table>
		</form>
			</div>
	</div>
	<div id="footer"><a href="<?php echo SITE_URL;?>" target="_blank">Powered by <?php echo SITE_NAME;?> </a> </div>
</div>
</body>
</html>