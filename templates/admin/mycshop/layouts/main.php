<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title><?php echo trig_mvc_template::$title; ?></title>
    <link rel="stylesheet" href="<?php echo TEMPLATE_URL?>/css/main.css">
    <link rel="stylesheet" href="<?php echo TEMPLATE_URL?>/css/admin.css">
    <script src="<?php echo TEMPLATE_URL?>/js/jquery.js"></script>
    <script src="<?php echo TEMPLATE_URL?>/js/main.js"></script>
    <script src="<?php echo TEMPLATE_URL?>/js/respond.js"></script>
    <script src="<?php echo TEMPLATE_URL?>/js/admin.js"></script>
    <link type="image/x-icon" href="<?php echo TEMPLATE_URL?>/images/favicon.ico" rel="shortcut icon" />
    <link href="<?php echo TEMPLATE_URL?>/images/favicon.ico" rel="bookmark icon" />
</head>

<body>
<div class="lefter">
    <div class="logo"><a href="<?php echo trig_mvc_route::get_uri('index','init');?>" target="_blank"><img src="<?php echo TEMPLATE_URL?>/images/logo.png" alt="<?php echo SITE_NAME;?>" /></a></div>
</div>
<div class="righter nav-navicon" id="admin-nav">
    <div class="mainer">
        <div class="admin-navbar">
            <span class="float-right">
            	<a class="button button-little bg-main" href="/" target="_blank">前台首页</a>
                <a class="button button-little bg-yellow" href="<?php echo trig_mvc_route::get_uri('login','admin_logout');?>">注销登录</a>
            </span>
            <ul class="nav nav-inline admin-nav">
                <li class="active"><a href="index.html" class="icon-home"> 开始</a>
                    <ul><li><a href="system.html">系统设置</a></li><li><a href="content.html">内容管理</a></li><li><a href="#">订单管理</a></li><li class="active"><a href="#">会员管理</a></li><li><a href="#">文件管理</a></li><li><a href="#">栏目管理</a></li></ul>
                </li>
                <li><a href="system.html" class="icon-cog"> 系统</a>
            		<ul><li><a href="#">全局设置</a></li><li class="active"><a href="#">系统设置</a></li><li><a href="#">会员设置</a></li><li><a href="#">积分设置</a></li></ul>
                </li>
                <li><a href="content.html" class="icon-file-text"> 内容</a>
					<ul><li><a href="#">添加内容</a></li><li class="active"><a href="#">内容管理</a></li><li><a href="#">分类设置</a></li><li><a href="#">链接管理</a></li></ul>
                </li>
                <li><a href="#" class="icon-shopping-cart"> 订单</a></li>
                <li><a href="#" class="icon-user"> 会员</a></li>
                <li><a href="#" class="icon-file"> 文件</a></li>
                <li><a href="#" class="icon-th-list"> 栏目</a></li>
            </ul>
        </div>
        <div class="admin-bread">
            <span>欢迎你：<?php echo $_SESSION['admin_username'];?> 你的身份是：<?php echo $_SESSION['usergroupname'];?></span>
            <ul class="bread">
                <li><a href="<?php echo trig_mvc_route::get_uri('index','init');?>" class="icon-home"> 开始</a></li>
                <li>后台首页</li>
            </ul>
        </div>
    </div>
</div>

<div class="admin">
	<?php echo $content ?>	
    <p class="text-center text-gray"><?php echo SITE_NAME;?> &copy; 版权所有</p>    
</div>

<div class="hidden"></div>
</body>
</html>