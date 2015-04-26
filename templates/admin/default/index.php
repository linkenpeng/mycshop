<?php
trig_mvc_template::$title = "管理后台首页";
trig_mvc_template::$keywords = "管理后台首页";
trig_mvc_template::$description = "管理后台首页";
?>
<div class="pageMain">
	<div class="indexpageContent">
		<div class="index_block">
			<div class="pageTitle"><div class="pageTitle_left"></div>最新景区</div>
			<ul class="index_ul">
				<?php if(is_array($scene_list)) { 
					foreach ($scene_list as $value) {
				?>
				<li><?php echo $value['scenename']; ?></li>
				<?php }} ?>
			</ul>
		</div>
		<div class="index_block">
			<div class="pageTitle"><div class="pageTitle_left"></div>最新景点</div>
			<ul class="index_ul">
				<?php if(is_array($scenespot_list)) { 
					foreach ($scenespot_list as $value) {
				?>
				<li><font color="#CCCCCC">[<?php echo $value['scenespotname']; ?>]</font> <?php echo $value['scenespotname']; ?></li>
				<?php }} ?>
			</ul>
		</div>
		<div class="index_block">
			<div class="pageTitle"><div class="pageTitle_left"></div>最新评论</div>
			<ul class="index_ul">
				<?php if(is_array($comment_list)) { 
					foreach ($comment_list as $value) {
				?>
				<li>
				<font color="#CCCCCC">[<?php echo date("Y-m-d",$value['dateline']);?>]</font>
				<?php echo $value['commented_title']; ?></li>
				<?php }} ?>
			</ul>
		</div>
		<div class="index_block">
			<div class="pageTitle"><div class="pageTitle_left"></div>最新签到</div>
			<ul class="index_ul">
				<?php if(is_array($signin_list)) {
					foreach ($signin_list as $value) {
				?>
				<li>
				<font color="#CCCCCC">[<?php echo date("Y-m-d",$value['dateline']);?>]</font>
				<?php echo $value['signined_title']; ?>
				</li>
				<?php }} ?>
			</ul>
		</div>
	</div>
</div>