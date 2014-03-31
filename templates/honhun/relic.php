<?php
$pagetitle="文物管理";
include admin_template("header");
?>

<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
</div>
<div class="pageContent">
  <form action="<?php echo get_uri();?>" method="get">
	  <input name="<?php echo M;?>" type="hidden" value="<?php echo $_GET[M];?>" />
	  <input name="<?php echo C;?>" type="hidden" value="<?php echo $_GET[C];?>" />
	  <input name="<?php echo A;?>" type="hidden" value="<?php echo $_GET[A];?>" />	  
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
	  <tr>
		
		<td align="right" >
		文物编号:<input name="relicnum" type="text" size="12" value="<?=$relicnum?>" />
		景点名称:<input name="scenespotname" type="text" size="12" value="<?=$scenespotname?>" />	 
		文物名称:<input name="relicname" type="text" size="12" value="<?=$relicname?>" />
		<input type="submit" name="Submit" value="<?=lang("action","search")?>" /></td>
	  </tr>
	 </table>
  </form>
	  <table width="100%">
		<tr>
		  <th width="50">ID</th>
		  <th width="100">文物名称</th>
		  <th width="100">文物编号</th>
		  <th width="50">文物级别</th>
		  <th width="100">文物图片</th>
		  <th width="80">所属景点</th>
		  <th width="100"><?=lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center"><?=$value['relicid']?></td>
		  <td align="center">
		  <?=$value['relicname']?> <br />
		  <?=$value['relic_enname']?>
		  </td>
		  <td align="center"><?=$value['relicnum']?></td>
		  <td align="center"><?=$levels[$value['level']]?></td>
		  <td align="center">
    		  <?php if ($value['image']) {?>
    		  <a href="<?=UPLOAD_URI.'/'.$value['image']?>" target="_blank">
    		  		<img src="<?=UPLOAD_URI.'/thumb/'.$value['image']?>" width="100" height="100"  /> 
    		  </a>
    		  <?php } ?>
		  </td>
		  <td align="center">
		  <?=$value['scenespotname']?> <br />
		  [<a href="<?php echo get_uri("relic","add","admin","scenespotid=".$value['scenespotid']);?>">添加文物</a>]
		  </td>
		  <td align="center">
				<a href="<?php echo get_uri("relic","edit","admin");?>&relicid=<?=$value['relicid']?>" ><?=lang("action","edit")?></a>
				<a href="<?php echo get_uri("relic","delete","admin");?>&relicid=<?=$value['relicid']?>" onclick="return confirm('<?=lang("action","isdelete")?>?');"><?=lang("action","delete")?></a>
		  </td>
		</tr>
		<?php }} ?>
		<tr>
			<td colspan=9 align="center">
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