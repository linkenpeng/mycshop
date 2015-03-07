<?php
$pagetitle="用户组管理";
include admin_template("header");
?>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo get_uri("usergroup","add");?>">添加</a>
</div>
<div class="pageContent">
  
	  <table width="100%">
		<tr>
		  <th width="200">用户类型</th>
		  <th width="150">备注</th>
		  <th>添加时间</th>
		  <th width="150"><?php echo lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center"><?php echo $value['name']; ?></td>
		  <td align="center"><?php echo $value['description']; ?></td>
		  <td align="center"><?php echo date("Y-m-d H:i:s",$value['dateline']);?></td>
		  <td align="center">
				<a href="<?php echo get_uri("usergroup","edit","admin");?>&ugid=<?php echo $value['ugid']; ?>" ><?php echo lang("action","edit")?></a>
				<a href="<?php echo get_uri("usergroup","delete","admin");?>&ugid=<?php echo $value['ugid']; ?>" onclick="return confirm('<?php echo lang("action","isdelete")?>?');"><?php echo lang("action","delete")?></a>
				<a href="<?php echo get_uri("usergroup","permission","admin");?>&ugid=<?php echo $value['ugid']; ?>" >权限管理</a>
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