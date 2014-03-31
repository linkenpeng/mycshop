<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
?>
<div id="topbar">
	<span style="float:right;margin-right:10px;">
		欢迎你：<?php echo $_SESSION['admin_username'];?> 你的身份是：<?php echo $_SESSION['usergroupname'];?>
	</span>
	<img src="<?php echo ADMIN_TEMPLATE_URL?>/images/Announce.gif" />
	<span style="width:500px;">
	<marquee onMouseOver="this.stop()" onMouseOut="this.start()" scrollamount="1" scrolldelay="4" align="left" width="500">欢迎使用<?=SITE_NAME?>！</marquee>
	</span>
	
</div>
<div id="top">
	<div id="banner">
		
	</div>
	<div id="banner_menu">
		<div class="login_status"></div>
		<div>
			<ul>
    		<li><img src="<?php echo ADMIN_TEMPLATE_URL?>/images/top-fund.gif" /> <a href="javascript:void(0);" id="leftmunectrl" ctvalue="1">显示菜单</a></li>
    		<li><img src="<?php echo ADMIN_TEMPLATE_URL?>/images/top-number.gif" /> <a href="<?php echo get_uri('index','init');?>">后台首页</a></li>
    		<li><img src="<?php echo ADMIN_TEMPLATE_URL?>/images/top-base.gif" /> <a href="<?php echo get_uri('user','editpass');?>">修改密码</a></li>
    		<li><img src="<?php echo ADMIN_TEMPLATE_URL?>/images/top-logout.gif" /> <a href="<?php echo get_uri('login','admin_logout');?>">退出</a></li>
    		</ul>
		</div>
	</div>
</div>