<?php
$pagetitle="系统配置管理";
include trig_mvc_template::view_file("header");
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
				<a href="<?php echo trig_mvc_route::get_uri("system","edit","admin",array('sid'=>$value['sid']));?>" ><?php echo trig_func_common::lang("action","edit")?></a>
		  </td>
		</tr>
		<?php }} ?>
		<tr>
			<td colspan=8 align="center">
				<div class="page"><?= trig_helper_html::page_info($p) ?></div>
				<div class="run-info"><?= trig_helper_html::run_info(array('startTime' => START_TIME, 'endTime' => trig_func_common::mtime())) ?></div>
			</td>
		</tr>
	</table>
</form>
</div>
</div>
<?php
include trig_mvc_template::view_file("footer");
?>