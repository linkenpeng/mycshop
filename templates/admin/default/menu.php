<?php
$pagetitle="菜单管理";
include trig_mvc_template::view("header");
?>
<div class="pageMain">
<div class="pageTitle">当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo trig_mvc_route::get_uri("menu","add");?>">添加</a>
</div>
<div class="pageContent">
  
	  <table width="100%">
		<tr>
		  <th width="100">id/父id</th>
		  <th width="150">菜单名</th>
		  <th width="150">模型</th>
		  <th width="100">控制器</th>
		  <th>动作</th>
		  <th><?php echo trig_func_common::lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr class="td_hignlight">
		  <td align="center"><?php echo $value['menuid']; ?>/<?php echo $value['parentid']; ?></td>
		  <td><?php echo $value['name']; ?></td>
		  <td align="center"><?php echo $value['model']; ?></td>
		  <td align="center"><?php echo $value['ctrl']; ?></td>
		  <td align="center"><?php echo $value['act']; ?></td>
		  <td align="center">
				<a href="<?php echo trig_mvc_route::get_uri("menu","edit","admin");?>&menuid=<?php echo $value['menuid']; ?>" ><?php echo trig_func_common::lang("action","edit")?></a>
				<a href="<?php echo trig_mvc_route::get_uri("menu","delete","admin");?>&menuid=<?php echo $value['menuid']; ?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
				<a href="<?php echo trig_mvc_route::get_uri("menu","add","admin");?>&parentid=<?php echo $value['menuid']; ?>" >添加子菜单</a>
		  </td>
		</tr>
		<?php if(is_array($value['subs'])) {
				foreach ($value['subs'] as $val2) {
		?>
				<tr>
				  <td align="center"><?php echo $val2['menuid']; ?>/<?php echo $val2['parentid']; ?></td>
				  <td>----<?php echo $val2['name']; ?></td>
				  <td align="center"><?php echo $val2['model']; ?></td>
				  <td align="center"><?php echo $val2['ctrl']; ?></td>
				  <td align="center"><?php echo $val2['act']; ?></td>
				  <td align="center">
						<a href="<?php echo trig_mvc_route::get_uri("menu","edit","admin");?>&menuid=<?php echo $val2['menuid']; ?>" ><?php echo trig_func_common::lang("action","edit")?></a>
						<a href="<?php echo trig_mvc_route::get_uri("menu","delete","admin");?>&menuid=<?php echo $val2['menuid']; ?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
						<a href="<?php echo trig_mvc_route::get_uri("menu","add","admin");?>&parentid=<?php echo $val2['menuid']; ?>" >添加子菜单</a>
				  </td>
				</tr>
		<?php 	} 
			  } ?>
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
include trig_mvc_template::view("footer");
?>