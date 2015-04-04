<?php
$pagetitle="系统配置管理";
include trig_mvc_template::admin_template("header");
?>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
【<a href="<?php echo trig_mvc_route::get_uri("system","add");?>">添加</a>】
【<a href="<?php echo trig_mvc_route::get_uri("system","cache");?>">生成缓存</a>】
</div>
<div class="pageContent">
  <form action="<?php echo trig_mvc_route::get_uri("","sortorder");?>" method="post">
	  <table width="100%">
		<tr>		  
		  <th>配置键</th>
		  <th>配置值</th>
		  <th>配置描述</th>
		  <th><?php echo trig_func_common::lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr class="td_hignlight">		  
		  <td align="center"><?php echo $value['config_key']; ?></td>
		  <td align="center"><?php echo $value['config_value']; ?></td>
		  <td align="center"><?php echo $value['name']; ?></td>	  
		  <td align="center">
				<a href="<?php echo trig_mvc_route::get_uri("system","edit","admin");?>&sid=<?php echo $value['sid']; ?>" ><?php echo trig_func_common::lang("action","edit")?></a>
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
</form>
</div>
</div>
<?php
include trig_mvc_template::admin_template("footer");
?>