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
    <?php if(isset($show_zone)) { ?>
	<script src="<?php echo SITE_URL?>/statics/js/script_zone.js" type="text/javascript"></script>
	<?php } ?>
	<?php if(isset($show_editor)) { ?>
	<script src="<?php echo SITE_URL?>/statics/js/tiny_mce/tiny_mce.js" type="text/javascript"></script>
	<?php } ?>
	<?php if(isset($show_date_js)) { ?>
	<script src="<?php echo SITE_URL?>/statics/js/My97DatePicker/WdatePicker.js" type="text/javascript"></script>
	<?php } ?>    
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
				<?php 
				//一定要全局才行
				global $topmenus;
				foreach ($topmenus as $k=>$v) { ?>
				<li <?php if($v['is_active']) {?> class="active"<?php }?>>
					<a href="<?php echo trig_mvc_route::get_uri($v['ctrl'],$v['act']);?>" class="<?php echo $v['icon']; ?>"> <?php echo $v['name']; ?></a>
					<?php if(!empty($v['subs'])) {
					?>
					<ul>
					<?php foreach($v['subs'] as $v2) {						
					?>
						<li <?php if($v2['is_active']) {?> class="active"<?php }?>>
						<a href="<?php echo trig_mvc_route::get_uri($v2['ctrl'],$v2['act']);?>" ><?php echo $v2['name']; ?></a>
						</li>
					<?php 	} ?>
					</ul>
					<?php }?>
                </li>				
				<?php } ?>
            </ul>
        </div>
        <div class="admin-bread">
            <span>欢迎您：<?php echo $_SESSION['admin_username'];?>， 你的身份是：<?php echo $_SESSION['usergroupname'];?></span>
            <ul class="bread">
                <li><a href="<?php echo trig_mvc_route::get_uri('index','init');?>" class="icon-home"> 开始</a></li>
                <li><?php echo trig_mvc_template::$title; ?></li>
            </ul>
        </div>
    </div>
</div>

<div class="admin">
	<?php echo $content ?>
	<br />
    <p class="text-center text-gray"><?php echo SITE_NAME;?> &copy; 2015-2025 版权所有</p>    
</div>

<div class="hidden"></div>
</body>
</html>