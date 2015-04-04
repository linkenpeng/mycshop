<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
?>
<div id="top">
	<div id="banner">
		<span class="logo"><?php echo SITE_NAME;?></span>
	</div>
	<div id="banner_menu">
		<div class="login_status">欢迎你：<?php echo $_SESSION['admin_username'];?> 你的身份是：<?php echo $_SESSION['usergroupname'];?></div>
		<div>
			<ul>
    		<li><a href="javascript:void(0);" id="leftmunectrl" ctvalue="1">显示菜单</a></li>
    		<li><a href="<?php echo trig_mvc_route::get_uri('index','init');?>">后台首页</a></li>
    		<li><a href="<?php echo trig_mvc_route::get_uri('user','editpass');?>">修改密码</a></li>
    		<li><a href="<?php echo trig_mvc_route::get_uri('login','admin_logout');?>">退出</a></li>
    		</ul>
		</div>
	</div>
</div>