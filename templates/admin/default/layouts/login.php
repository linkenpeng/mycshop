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
	<div id="header"><?php echo SITE_NAME;?></div>
	<div id="title">管理员登录</div>
	<?php echo $content; ?>
	<div id="footer"><a href="<?php echo SITE_URL;?>" target="_blank">Powered by <?php echo SITE_NAME;?> </a> </div>
</div>
</body>
</html>