<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
?>
<div id="navbar">
	<div class="lefttitle">
	管理菜单
	</div>
	<div class="leftmenu">
		<ul class="menubar">
			<?php 
			//一定要全局才行
			global $topmenus;
			foreach ($topmenus as $k=>$v) { ?>
				<li>
				<img src="<?php echo ADMIN_TEMPLATE_URL?>/images/icon-<?php echo $v['ctrl']; ?>.gif" />
				<a href="<?php echo trig_mvc_route::get_uri($v['ctrl'],$v['act']);?>" <?php if($_GET[C]==$v['ctrl']) {?> class="active"<?php }?>><?php echo $v['name']; ?>
				</a>
				</li>
			<?php } ?>			
		</ul>
	</div>
	
</div>