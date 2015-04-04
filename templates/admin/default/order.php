<?php
$pagetitle="订单管理";
include trig_mvc_template::admin_template("header");
?>
<div class="pageMain">
<div class="pageTitle">当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo trig_mvc_route::get_uri("order","add");?>">添加</a>
</div>
<div class="pageContent">
  
	  <table width="100%">
		<tr>
		  <th width="200">订单名</th>
		  <th width="150">备注</th>
		  <th>添加时间</th>
		  <th width="100"><?php echo trig_func_common::lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center"><?php echo $value['ordername']; ?></td>
		  <td align="center"><?php echo $value['description']; ?></td>
		  <td align="center"><?php echo date("Y-m-d H:i:s",$value['dateline']);?></td>
		  <td align="center">
				<a href="<?php echo trig_mvc_route::get_uri("order","edit","admin");?>&orderid=<?php echo $value['orderid']; ?>" ><?php echo trig_func_common::lang("action","edit")?></a>
				<a href="<?php echo trig_mvc_route::get_uri("order","delete","admin");?>&orderid=<?php echo $value['orderid']; ?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
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
include trig_mvc_template::admin_template("footer");
?>