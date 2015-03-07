<?php
$pagetitle="菜单管理";
include admin_template("header");
?>
<div class="pageMain">
<div class="pageTitle">当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo get_uri("menu","add");?>">添加</a>
</div>
<div class="pageContent">
  
	  <table width="100%">
		<tr>
		  <th width="100">id/父id</th>
		  <th width="150">菜单名</th>
		  <th width="150">模型</th>
		  <th width="100">控制器</th>
		  <th>动作</th>
		  <th><?php echo lang("action","operation")?> </th>
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
				<a href="<?php echo get_uri("menu","edit","admin");?>&menuid=<?php echo $value['menuid']; ?>" ><?php echo lang("action","edit")?></a>
				<a href="<?php echo get_uri("menu","delete","admin");?>&menuid=<?php echo $value['menuid']; ?>" onclick="return confirm('<?php echo lang("action","isdelete")?>?');"><?php echo lang("action","delete")?></a>
				<a href="<?php echo get_uri("menu","add","admin");?>&parentid=<?php echo $value['menuid']; ?>" >添加子菜单</a>
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
						<a href="<?php echo get_uri("menu","edit","admin");?>&menuid=<?php echo $val2['menuid']; ?>" ><?php echo lang("action","edit")?></a>
						<a href="<?php echo get_uri("menu","delete","admin");?>&menuid=<?php echo $val2['menuid']; ?>" onclick="return confirm('<?php echo lang("action","isdelete")?>?');"><?php echo lang("action","delete")?></a>
						<a href="<?php echo get_uri("menu","add","admin");?>&parentid=<?php echo $val2['menuid']; ?>" >添加子菜单</a>
				  </td>
				</tr>
		<?php 	} 
			  } ?>
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