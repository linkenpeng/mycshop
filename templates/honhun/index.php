<?php
$pagetitle="管理后台首页";
include admin_template("header");
?>
<div class="pageMain">

<div class="indexpageContent">
<div class="index_block">
	<div class="pageTitle">
		<div class="pageTitle_left"></div>
		最新景区
	</div>
	<ul class="index_ul">
		<?php if(is_array($scene_list)) { 
			foreach ($scene_list as $value) {
		?>
		<li><?=$value['scenename']?></li>
		<?php }} ?>
	</ul>
</div>
<div class="index_block">
	<div class="pageTitle">
	<div class="pageTitle_left"></div>最新景点</div>
			<ul class="index_ul">
				<?php if(is_array($scenespot_list)) { 
					foreach ($scenespot_list as $value) {
				?>
				<li><font color="#CCCCCC">[<?=$value['scenespotname']?>]</font> <?=$value['scenespotname']?></li>
				<?php }} ?>
			</ul>
</div>
<div class="index_block">
	<div class="pageTitle">
<div class="pageTitle_left"></div>最新评论</div>
			<ul class="index_ul">
				<?php if(is_array($comment_list)) { 
					foreach ($comment_list as $value) {
				?>
				<li>
				<font color="#CCCCCC">[<?php echo date("Y-m-d",$value['dateline']);?>]</font>
				<?=$value['commented_title']?></li>
				<?php }} ?>
			</ul>
</div>
<div class="index_block">
	<div class="pageTitle">
<div class="pageTitle_left"></div>最新签到</div>
			<ul class="index_ul">
				<?php if(is_array($signin_list)) { 
					foreach ($signin_list as $value) {
				?>
				<li>
				<font color="#CCCCCC">[<?php echo date("Y-m-d",$value['dateline']);?>]</font>
				<?=$value['signined_title']?>
				</li>
				<?php }} ?>
			</ul>
</div>
</div>
</div>
<?php
include admin_template("footer");
?>