<?php
$pagetitle="文物管理";
include trig_mvc_template::admin_template("header");
?>

<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
</div>
<div class="pageContent">
  <form action="<?php echo trig_mvc_route::get_uri();?>" method="get">
	  <input name="<?php echo M;?>" type="hidden" value="<?php echo $_GET[M];?>" />
	  <input name="<?php echo C;?>" type="hidden" value="<?php echo $_GET[C];?>" />
	  <input name="<?php echo A;?>" type="hidden" value="<?php echo $_GET[A];?>" />	  
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
	  <tr>
		
		<td align="right" >
		文物编号:<input name="relicnum" type="text" size="12" value="<?php echo $relicnum?>" />
		景点名称:<input name="scenespotname" type="text" size="12" value="<?php echo $scenespotname?>" />	 
		文物名称:<input name="relicname" type="text" size="12" value="<?php echo $relicname?>" />
		<input type="submit" name="Submit" value="<?php echo trig_func_common::lang("action","search")?>" /></td>
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
		  <th width="100"><?php echo trig_func_common::lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center"><?php echo $value['relicid']; ?></td>
		  <td align="center">
		  <?php echo $value['relicname']; ?> <br />
		  <?php echo $value['relic_enname']; ?>
		  </td>
		  <td align="center"><?php echo $value['relicnum']; ?></td>
		  <td align="center"><?php echo $levels[$value['level']]; ?></td>
		  <td align="center">
    		  <?php if ($value['image']) {?>
    		  <a href="<?php echo UPLOAD_URI.'/'.$value['image']; ?>" target="_blank">
    		  		<img src="<?php echo UPLOAD_URI.'/thumb/'.$value['image']; ?>" width="100" height="100"  /> 
    		  </a>
    		  <?php } ?>
		  </td>
		  <td align="center">
		  <?php echo $value['scenespotname']; ?> <br />
		  [<a href="<?php echo trig_mvc_route::get_uri("relic","add","admin","scenespotid=".$value['scenespotid']);?>">添加文物</a>]
		  </td>
		  <td align="center">
				<a href="<?php echo trig_mvc_route::get_uri("relic","edit","admin");?>&relicid=<?php echo $value['relicid']; ?>" ><?php echo trig_func_common::lang("action","edit")?></a>
				<a href="<?php echo trig_mvc_route::get_uri("relic","delete","admin");?>&relicid=<?php echo $value['relicid']; ?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
		  </td>
		</tr>
		<?php }} ?>
		<tr>
			<td colspan=9 align="center">
				<div class="page"><?= trig_helper_html::page_info($p) ?></div>
				<div class="run-info"><?= trig_helper_html::run_info(array('startTime' => START_TIME, 'endTime' => trig_func_common::mtime())) ?></div>
			</td>
		</tr>
	</table>

</div>
</div>
<?php
include trig_mvc_template::admin_template("footer");
?>