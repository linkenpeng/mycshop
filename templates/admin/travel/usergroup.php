<?php
$pagetitle="用户组管理";
include trig_func_common::admin_template("header");
?>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo trig_func_common::get_uri("usergroup","add");?>">添加</a>
</div>
<div class="pageContent">
  
	  <table width="100%">
		<tr>
		  <th width="200">用户类型</th>
		  <th width="150">备注</th>
		  <th>添加时间</th>
		  <th width="150"><?php echo trig_func_common::lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center"><?php echo $value['name']; ?></td>
		  <td align="center"><?php echo $value['description']; ?></td>
		  <td align="center"><?php echo date("Y-m-d H:i:s",$value['dateline']);?></td>
		  <td align="center">
				<a href="<?php echo trig_func_common::get_uri("usergroup","edit","admin");?>&ugid=<?php echo $value['ugid']; ?>" ><?php echo trig_func_common::lang("action","edit")?></a>
				<a href="<?php echo trig_func_common::get_uri("usergroup","delete","admin");?>&ugid=<?php echo $value['ugid']; ?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
				<a href="<?php echo trig_func_common::get_uri("usergroup","permission","admin");?>&ugid=<?php echo $value['ugid']; ?>" >权限管理</a>
		  </td>
		</tr>
		<?php }} ?>
		<tr>
			<td colspan=8 align="center">
				<div class="page">
					<?php echo trig_func_common::lang("page","total")?><b><?php echo $count?></b><?php echo trig_func_common::lang("page","item")?> <b><?php echo $nowpage?>/<?php echo $p->totalpage?></b><?php echo trig_func_common::lang("page","page")?> <?php echo $p->show(); ?>
				</div>
			<?php
				$endTime = trig_func_common::mtime();
				$totaltime = sprintf("%.3f",($endTime - START_TIME));
				echo trig_func_common::lang("page","thispage").trig_func_common::lang("common","excute").trig_func_common::lang("common","time").($totaltime).trig_func_common::lang("common","second");
			?>
			</td>
		</tr>
	</table>

</div>
</div>
<?php
include trig_func_common::admin_template("footer");
?>