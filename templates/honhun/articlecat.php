<?php
$pagetitle="文章分类";
include admin_template("header");
?>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo get_uri("articlecat","add");?>">添加</a>
</div>
<div class="pageContent">
  
	  <table width="100%">
		<tr>
		  <th width="250">分类名</th>
		  <th width="100">所属景区</th>
		  <th width="100">信息类型</th>
		  <th>添加时间</th>
		  <th width="60">排序号</th>
		  <th width="150"><?=lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="left">
		  
		  <?php if($value['depth'] <= 0) { ?>
				<img src="<?=SITE_URL.'/statics/images/tree/t3.gif'?>" /> 
		  <?php } else { ?>
				<?php for ($i = 0; $i < $value['depth']; $i++) { ?>
				<img src="<?=SITE_URL.'/statics/images/tree/t0.gif'?>" /> 	
				<?php } ?>
				<img src="<?=SITE_URL.'/statics/images/tree/t2.gif'?>" /> 
		  <?php }?>
		  
		  <a href="<?php echo get_uri("article","init","admin");?>&catid=<?=$value['catid']?>" >
		  <?=$value['name']?>
		  </a>
		  </td>
		  <td align="center">
			<?php 
			//所属景区
			if(!empty($value['sceneid'])) {
				$sceneids = explode(',', $value['sceneid']);
				$scenes = array();				
				foreach ($sceneids as $scid) {
					$scenes[] = $sc_list[$scid];
				}
				echo implode(',', $scenes);
			}			
			?>
		  </td>
		  <td align="center"><?php echo $this->cattypes[$value['cattype']]; ?></td>
		  <td align="center"><?php echo date("Y-m-d H:i:s",$value['dateline']);?></td>
		  <td align="center"><?php echo $value['ordernum'];?></td>
		  <td align="center">
				<a href="<?php echo get_uri("articlecat","add","admin");?>&upid=<?=$value['catid']?>" >添加子类</a>
				
				<a href="<?php echo get_uri("articlecat","edit","admin");?>&catid=<?=$value['catid']?>&upid=<?=$value['upid']?>" ><?=lang("action","edit")?></a>
				
				<a href="<?php echo get_uri("articlecat","delete","admin");?>&catid=<?=$value['catid']?>" onclick="return confirm('<?=lang("action","isdelete")?>?');"><?=lang("action","delete")?></a>
		  </td>
		</tr>
		<?php }} ?>
		<tr>
			<td colspan=8 align="center">
				<div class="page">
					<?=lang("page","total")?><b><?=$count?></b><?=lang("page","item")?> <b><?=$nowpage?>/<?=$p->totalpage?></b><?=lang("page","page")?> <?php echo $p->show(); ?>
				</div>
			<?php
				$endTime = mtime();
				$totaltime = sprintf("%.3f",($endTime - START_TIME));
				echo lang("page","thispage").lang("common","excute").lang("common","time").($totaltime).lang("common","second");
			?>
			</td>
		</tr>
	</table>

</div>
</div>
<?php
include admin_template("footer");
?>