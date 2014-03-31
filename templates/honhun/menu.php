<?php
$pagetitle="菜单管理";
include admin_template("header");
?>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo get_uri("menu","add");?>">添加</a>
</div>
<div class="pageContent">
  <form action="<?php echo get_uri("","sortorder");?>" method="post">
	  <table width="100%">
		<tr>
		  <th width="100">id/父id</th>
		  <th width="150">菜单名</th>
		  <th width="150">模型</th>
		  <th width="100">控制器</th>
		  <th>动作</th>
		  <th width="60">排序</th>
		  <th><?=lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr class="td_hignlight">
		  <td align="center"><?=$value['menuid']?>/<?=$value['parentid']?></td>
		  <td><?=$value['name']?></td>
		  <td align="center"><?=$value['model']?></td>
		  <td align="center"><?=$value['ctrl']?></td>
		  <td align="center"><?=$value['act']?></td>
		  <td align="center"><input type="text" size="6" value="<?=$value['sort_order']?>" name="orderid_<?=$value['menuid']?>" /></td>
		  <td align="center">
				<a href="<?php echo get_uri("menu","edit","admin");?>&menuid=<?=$value['menuid']?>" ><?=lang("action","edit")?></a>
				<a href="<?php echo get_uri("menu","delete","admin");?>&menuid=<?=$value['menuid']?>" onclick="return confirm('<?=lang("action","isdelete")?>?');"><?=lang("action","delete")?></a>
				<a href="<?php echo get_uri("menu","add","admin");?>&parentid=<?=$value['menuid']?>" >添加子菜单</a>
		  </td>
		</tr>
		<?php if(is_array($value['subs'])) {
				foreach ($value['subs'] as $val2) {
		?>
				<tr>
				  <td align="center"><?=$val2['menuid']?>/<?=$val2['parentid']?></td>
				  <td>----<?=$val2['name']?></td>
				  <td align="center"><?=$val2['model']?></td>
				  <td align="center"><?=$val2['ctrl']?></td>
				  <td align="center"><?=$val2['act']?></td>
				  <td align="center"></td>
				  <td align="center">
						<a href="<?php echo get_uri("menu","edit","admin");?>&menuid=<?=$val2['menuid']?>" ><?=lang("action","edit")?></a>
						<a href="<?php echo get_uri("menu","delete","admin");?>&menuid=<?=$val2['menuid']?>" onclick="return confirm('<?=lang("action","isdelete")?>?');"><?=lang("action","delete")?></a>
						<a href="<?php echo get_uri("menu","add","admin");?>&parentid=<?=$val2['menuid']?>" >添加子菜单</a>
				  </td>
				</tr>
		<?php 	} 
			  } ?>
		<?php }} ?>
		<tr>
			<td colspan=8 align="right">
				<input type="submit" name="" value="更新排序" />
			</td>
		</tr>
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
</form>
</div>
</div>
<?php
include admin_template("footer");
?>