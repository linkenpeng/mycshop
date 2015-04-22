<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $pagetitle?></title>
<meta content="<?php echo $pagetitle?>" name="keywords">
<meta content="<?php echo $pagetitle?>" name="description">
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
<script language="javascript" src="<?php echo TEMPLATE_URL?>/js/common.js" type="text/javascript"></script>
<script language="javascript">
$().ready(function(){
	showleftmenu(getCookie("ctvalue"));
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
			$(".pageMain").width("81%");
		}
	}
});
</script>
</head>
<body>
<div id="main">
<?php include 'menu_top.php'; ?>
<?php include 'menu_left.php'; ?>