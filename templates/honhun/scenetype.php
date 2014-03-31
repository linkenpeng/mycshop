<?php
$pagetitle="景区分类";
include admin_template("header");
?>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo get_uri("scenetype","add");?>">添加</a>
</div>
<div class="pageContent">
  
	  <table width="100%">
		<tr>
		  <th width="200">分类名</th>
		  <th width="100">分类图片</th>
		  <th width="350">分类说明</th>
		  <th>添加时间</th>
		  <th width="100"><?=lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center">
		  <a href="<?php echo get_uri("scene","init","admin");?>&typeid=<?=$value['typeid']?>" >
		  <?=$value['name']?> <br />
		  <?php echo $value['enname'];?>
		  </a>
		  </td>
		  <td align="center">
    		  <?php if ($value['image']) {?>
    		  <a href="<?=UPLOAD_URI.'/'.$value['image']?>" target="_blank">
    		  		<img src="<?=UPLOAD_URI.'/thumb/'.$value['image']?>" width="50"   /> 
    		  </a>
    		  <?php } ?>
		  </td>
		  <td><?=$value['description']?></td>
		  <td align="center"><?php echo date("Y-m-d H:i:s",$value['dateline']);?></td>
		  <td align="center">
				<a href="<?php echo get_uri("scenetype","edit","admin");?>&typeid=<?=$value['typeid']?>" ><?=lang("action","edit")?></a>
				<a href="<?php echo get_uri("scenetype","delete","admin");?>&typeid=<?=$value['typeid']?>" onclick="return confirm('<?=lang("action","isdelete")?>?');"><?=lang("action","delete")?></a>
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