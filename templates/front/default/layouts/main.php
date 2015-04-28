<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title><?php echo trig_mvc_template::$title; ?></title>
	<meta content="<?php echo trig_mvc_template::$keywords; ?>" name="keywords">
	<meta content="<?php echo trig_mvc_template::$description; ?>" name="description">
    <link rel="stylesheet" href="<?php echo TEMPLATE_URL?>/css/main.css">
    <script src="<?php echo TEMPLATE_URL?>/js/jquery.js"></script>
    <script src="<?php echo TEMPLATE_URL?>/js/main.js"></script>
    <script src="<?php echo TEMPLATE_URL?>/js/respond.js"></script>
    <link type="image/x-icon" href="<?php echo TEMPLATE_URL?>/images/favicon.ico" rel="shortcut icon" />
    <link href="<?php echo TEMPLATE_URL?>/images/favicon.ico" rel="bookmark icon" />
    <style>
		.demo-nav.fixed.fixed-top{z-index:8;background:#fff;width:100%;padding:0;border-bottom:solid 3px #0a8;-webkit-box-shadow:0 3px 6px rgba(0, 0, 0, .175);box-shadow:0 3px 6px rgba(0, 0, 0, .175);}
    </style>
</head>

<body>
  	<!--顶部-->
    <div class="layout bg-black bg-inverse">
      <div class="container height-large">
        <span class="float-right text-small text-gray hidden-l">
          <a class="text-main" href="/">注册</a><span> | </span><a class="text-main" href="/">登录</a><span> | </span><a class="text-main" href="/">积分兑换</a><span> | </span><a class="text-main" href="/">帮助中心</a>
        </span>
        <a href="/"></a>
      </div>
    </div>

	<!--导航-->
    <div class="demo-nav padding-big-top padding-big-bottom fixed">
    <div class="container padding-top padding-bottom">
    
      <div class="line">
        <div class="xl12 xs3 xm3 xb2"><button class="button icon-navicon float-right" data-target="#header-demo"></button><a  href="/"><img src="<?php echo TEMPLATE_URL?>/images/logo.png" alt="前端CCS框架" /></a></div>
        <div class=" xl12 xs9 xm9 xb10 nav-navicon" id="header-demo">
    
          <div class="xs8 xm6 xb5 padding-small">
            <ul class="nav nav-menu nav-inline nav-big">
              <li><a href="/">首页</a></li>
              <li><a href="/">CSS</a></li>
              <li class="active"><a href="/">元件<span class="arrow"></span></a>
                <ul class="drop-menu">
                  <li><a href="/">概述</a></li>
                  <li><a href="/">网格系统<span class="arrow"></span></a>
                    <ul><li><a href="/">响应式布局</a></li><li><a href="/">非响应式布局</a></li></ul>
                  </li>
                  <li><a href="/">图标</a></li>
                </ul>
              </li>
              <li><a href="/">更多<span class="arrow"></span></a>
                <ul class="drop-menu">
                  <li><a href="/">组件</a></li>
                  <li><a href="/">模块<span class="arrow"></span></a>
                    <ul><li><a href="/">头部</a></li><li><a href="/">导航</a></li><li><a href="/">底部</a></li></ul>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
          <div class="xs4 xm3 xb4">
            <form>
              <div class="input-group padding-little-top">
                <input type="text" class="input border-main" name="keywords" size="30" placeholder="关键词" />
                <span class="addbtn"><button type="button" class="button bg-main icon-search"></button></span>
              </div>
            </form>
          </div>
          <div class="hidden-l hidden-s xm3 xb3">
            <div class="text-red text-big icon-phone height-large text-right"> 热线 400 1234567</div>
          </div>
    
        </div>
    
      </div>
    
    </div>
    
    </div>
    
    <?php echo $content ?>
    
    <!--底部-->
    <br /><br />
    <div class="layout padding-big-top padding-big-bottom border-top bg">
      <div class="container padding">
        <div class="line">
          <div class="hidden-l xs9 table-responsive">
            <ul class="nav nav-sitemap">
              <li><a href="/">起步</a><ul><li><a href="/">下载前端框架</a></li><li><a href="/">框架包含的文件</a></li><li><a href="/">基本页面</a></li></ul></li>
              <li><a href="/">CSS</a><ul><li><a href="/">文本</a></li><li><a href="/">图片</a></li><li><a href="/">水平线</a></li></ul></li>
              <li><a href="/">元件</a><ul><li><a href="/">网格系统</a></li><li><a href="/">图标</a></li><li><a href="/">标签</a></li></ul></li>
              <li><a href="/">JS组件</a><ul><li><a href="/">窗口工具</a></li><li><a href="/">选项标签</a></li><li><a href="/">进度及区间值</a></li></ul></li>
              <li><a href="/">模块</a><ul><li><a href="/">框架</a></li><li><a href="/">头部</a></li><li><a href="/">导航条</a></li></ul></li>
            </ul>
          </div>
          <div class="xl12 xs3 padding-top">
            <div class="media media-x">
              <div class="float-left txt-border radius-circle border-main"><div class="txt radius-circle bg-main icon-phone text-large"></div></div>
              <div class="media-body"><strong class="text-big text-main">400-1234-567</strong>7*24小时客服电话</div>
            </div>
            <div class="media media-x">
              <div class="float-left txt-border radius-circle border-main"><div class="txt radius-circle bg-main icon-weibo text-large"></div></div>
              <div class="media-body"><strong class="text-big text-main">微博关注我们</strong><a class="button button-little bg-red shake-hover" href="http://www.weibo.com/pintuer">点击关注</a></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="layout bg-black bg-inverse">
      <div class="container">
        <div class="navbar">
          <div class="navbar-head"><button class="button bg-gray icon-navicon" data-target="#navbar-footer"></button><a href="/" target="_blank"><img class="logo" src="<?php echo TEMPLATE_URL?>/images/24-white.png" alt="拼图前端CSS框架" /></a></div>
          <div class="navbar-body nav-navicon" id="navbar-footer">
            <div class="navbar-text navbar-left hidden-s hidden-l">版权所有 &copy; <a href="/" target="_blank">Pintuer.com</a> All Rights Reserved，图ICP备：380959609</div>
            <ul class="nav nav-inline navbar-right"><li><a href="/">首页</a></li><li><a href="/">CSS</a></li><li><a href="/">元件</a></li><li><a href="/">更多</a></li></ul>
          </div>
        </div>
      </div>
    </div>
    <div class="hidden"></div>
</body>
</html>