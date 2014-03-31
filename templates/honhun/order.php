<?php
$pagetitle="订单管理";
include admin_template("header");
?>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo get_uri("order","add");?>">添加</a>
</div>
<div class="pageContent">
  
	  <table width="100%">
		<tr>
		  <th width="200">订单名</th>
		  <th width="150">备注</th>
		  <th>添加时间</th>
		  <th width="100"><?=lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center"><?=$value['ordername']?></td>
		  <td align="center"><?=$value['description']?></td>
		  <td align="center"><?php echo date("Y-m-d H:i:s",$value['dateline']);?></td>
		  <td align="center">
				<a href="<?php echo get_uri("order","edit","admin");?>&orderid=<?=$value['orderid']?>" ><?=lang("action","edit")?></a>
				<a href="<?php echo get_uri("order","delete","admin");?>&orderid=<?=$value['orderid']?>" onclick="return confirm('<?=lang("action","isdelete")?>?');"><?=lang("action","delete")?></a>
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