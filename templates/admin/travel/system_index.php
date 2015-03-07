<?php
$pagetitle="系统配置管理";
include admin_template("header");
?>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
【<a href="<?php echo get_uri("system","add");?>">添加</a>】
【<a href="<?php echo get_uri("system","cache");?>">生成缓存</a>】
</div>
<div class="pageContent">
  <form action="<?php echo get_uri("","sortorder");?>" method="post">
	  <table width="100%">
		<tr>		  
		  <th>配置键</th>
		  <th>配置值</th>
		  <th>配置描述</th>
		  <th><?php echo lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr class="td_hignlight">		  
		  <td align="center"><?php echo $value['config_key']; ?></td>
		  <td align="center"><?php echo $value['config_value']; ?></td>
		  <td align="center"><?php echo $value['name']; ?></td>	  
		  <td align="center">
				<a href="<?php echo get_uri("system","edit","admin");?>&sid=<?php echo $value['sid']; ?>" ><?php echo lang("action","edit")?></a>
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
</form>
</div>
</div>
<?php
include admin_template("footer");
?>