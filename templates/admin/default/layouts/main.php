<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo trig_mvc_template::$title; ?></title>
<meta content="<?php echo trig_mvc_template::$keywords; ?>" name="keywords">
<meta content="<?php echo trig_mvc_template::$description; ?>" name="description">
<link href="<?php echo TEMPLATE_URL?>/css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="<?php echo SITE_URL?>/statics/js/jquery.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo SITE_URL?>/statics/js/cookie.js" type="text/javascript"></script>
<?php if(isset($show_validator)) { ?>
<link href="<?php echo SITE_URL;?>/statics/css/validator.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo SITE_URL?>/statics/js/formvalidator.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="<?php echo SITE_URL?>/statics/js/formvalidatorregex.js" charset="utf-8"></script>
<?php } ?>
<?php if(isset($show_zone)) { ?>
<script language="javascript" src="<?php echo SITE_URL?>/statics/js/script_zone.js" type="text/javascript"></script>
<?php } ?>
<?php if(isset($show_editor)) { ?>
<script language="javascript" src="<?php echo SITE_URL?>/statics/js/tiny_mce/tiny_mce.js" type="text/javascript"></script>
<?php } ?>
<?php if(isset($show_date_js)) { ?>
<script language="javascript" src="<?php echo SITE_URL?>/statics/js/My97DatePicker/WdatePicker.js" type="text/javascript"></script>
<?php } ?>
<?php if(isset($show_map)) { ?>
<script src="http://app.mapabc.com/apis?&t=flashmap&v=2.2&key=<?php echo MAPABC_KEY; ?>" type="text/javascript"></script> 
<?php } ?>
<script language="javascript" src="<?php echo TEMPLATE_URL?>/js/common.js" type="text/javascript"></script>
<script language="javascript">
$().ready(function(){
	showleftmenu(getCookie("ctvalue"));
	fixMainHeight();
	$("#leftmunectrl").click(function() {
		var value = $("#leftmunectrl").attr("ctvalue");
		setCookie("ctvalue",value);
		showleftmenu(value);
	});
	function showleftmenu(value) {
		if(value==1) {
			$("#navbar").hide();
			$("#leftmunectrl").attr("ctvalue",2);
			$("#leftmunectrl").html("显示菜单");
			$(".pageMain").width("100%");
		} else {
			$("#navbar").show();
			$("#leftmunectrl").attr("ctvalue",1);
			$("#leftmunectrl").html("隐藏菜单");
			var bodywidth=document.body.offsetWidth;
			$(".pageMain").width((bodywidth-210)+"px");
		}
	}
	function fixMainHeight() {
		//var bodyheight=document.documentElement.offsetHeight;
		//var bodyheight=document.body.offsetHeight;
		
		//var bodyheight=document.documentElement.scrollHeight || document.body.scrollHeight;
		//$(".pageContent").height((bodyheight-21-90-20-29)+"px");
		//$("#navbar").height((bodyheight-21-90-20)+"px");
	}
});
</script>
</head>
<body>
<div id="main">

	<div id="topbar">
		<span style="float:right;margin-right:10px;">
			欢迎你：<?php echo $_SESSION['admin_username'];?> 你的身份是：<?php echo $_SESSION['usergroupname'];?>
		</span>
		<span style="width:500px;">	
		</span>	
	</div>

	<div id="top">
		<div id="banner">
			<span class="logo"><?php echo SITE_NAME;?></span>
		</div>
		<div id="banner_menu">
			<div class="login_status"></div>
			<div>
				<ul>
				<li><img src="<?php echo TEMPLATE_URL?>/images/top-fund.gif" /> <a href="javascript:void(0);" id="leftmunectrl" ctvalue="1">显示菜单</a></li>
				<li><img src="<?php echo TEMPLATE_URL?>/images/top-number.gif" /> <a href="<?php echo trig_mvc_route::get_uri('index','init');?>">后台首页</a></li>
				<li><img src="<?php echo TEMPLATE_URL?>/images/top-base.gif" /> <a href="<?php echo trig_mvc_route::get_uri('user','editpass');?>">修改密码</a></li>
				<li><img src="<?php echo TEMPLATE_URL?>/images/top-logout.gif" /> <a href="<?php echo trig_mvc_route::get_uri('login','admin_logout');?>">退出</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div id="navbar">
		<div class="lefttitle"></div>	
		<div class="leftmenu">
			<ul class="menubar">
				<?php 
				//一定要全局才行
				global $topmenus;
				foreach ($topmenus as $k=>$v) { ?>
					<li <?php if($v['is_active']) {?> class="active"<?php }?>>
					<img src="<?php echo TEMPLATE_URL?>/images/icon-<?php echo $v['ctrl']; ?>.gif" /> 
					<a href="<?php echo trig_mvc_route::get_uri($v['ctrl'],$v['act']);?>" ><?php echo $v['name']; ?></a>
					</li>
					<?php if(!empty($v['subs'])) { 
					?>
					<ul class="sub-menu">
					<?php	foreach($v['subs'] as $v2) {
						
					?>
						<li <?php if($v2['is_active']) {?> class="active"<?php }?>>
						<a href="<?php echo trig_mvc_route::get_uri($v2['ctrl'],$v2['act']);?>" ><?php echo $v2['name']; ?></a>
						</li>
					<?php 	} ?>
					</ul>
					<?php }?>
				<?php } ?>			
			</ul>
		</div>
	</div>

	<div class="pageMain"><?php echo $content; ?></div>

	<div id="footer"><?php echo SITE_NAME;?> &copy; 版权所有</div>
</div> <!-- end main -->

<script type="text/javascript" src="http://tajs.qq.com/stats?sId=16174212" charset="UTF-8"></script>
<body>
</html>