<?php
$pagetitle="游玩主题管理";
include admin_template("header");
?>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo get_uri("traveltopic","add");?>">添加</a>
</div>
<div class="pageContent">
  
	  <table width="100%">
		<tr>
		  <th width="200">主题名</th>
		  <th width="350">主题说明</th>
		  <th>添加时间</th>
		  <th width="100"><?php echo lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center">
		  <?php if ($value['image']) {?>
		  <a href="<?php echo UPLOAD_URI.'/'.$value['image']; ?>" target="_blank">
				<img src="<?php echo UPLOAD_URI.'/thumb/'.$value['image']; ?>" width="50"   /> 
		  </a>
		  <?php } ?>
		  <a href="<?php echo get_uri("scene","init","admin");?>&typeid=<?php echo $value['typeid']; ?>" ><?php echo $value['name']; ?></a>
		  </td>
		  <td><?php echo $value['description']; ?></td>
		  <td align="center"><?php echo date("Y-m-d H:i:s",$value['dateline']);?></td>
		  <td align="center">
				<a href="<?php echo get_uri("traveltopic","edit","admin");?>&typeid=<?php echo $value['typeid']; ?>" ><?php echo lang("action","edit")?></a>
				<a href="<?php echo get_uri("traveltopic","delete","admin");?>&typeid=<?php echo $value['typeid']; ?>" onclick="return confirm('<?php echo lang("action","isdelete")?>?');"><?php echo lang("action","delete")?></a>
		  </td>
		</tr>
		<?php }} ?>
		<tr>
			<td colspan=8 align="center">
				<div class="page">
					<?php echo lang("page","total")?><b><?php echo $count?></b><?php echo lang("page","item")?> <b><?php echo $nowpage?>/<?php echo $p->totalpage?></b><?php echo lang("page","page")?> <?php echo $p->show(); ?>
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