<?php
$pagetitle="景点管理";
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
			 <span id="zone"></span>
			 <select id="typeid" name="typeid">
				<option value="">选择分类</option>
				<?php if(is_array($scenetype_list)) { 
					foreach ($scenetype_list as $k=>$v) {
				?>
				<option value="<?php echo $k;?>" <?php if($typeid==$k) { ?> selected="selected" <?php } ?>><?php echo $v;?></option>
				<?php }} ?>
			 </select>
			 
			 <select id="traveltopicid" name="traveltopicid">
				<option value="">选择游玩主题</option>
				<?php if(is_array($traveltopic_list)) { 
					foreach ($traveltopic_list as $k=>$v) {
				?>
				<option value="<?php echo $k;?>" <?php if($traveltopicid==$k) { ?> selected="selected" <?php } ?>><?php echo $v;?></option>
				<?php }} ?>
			 </select>
			 
			 <select id="level" name="level">
				<option value="">选择景点级别</option>
				<?php if(is_array($level_list)) { 
					foreach ($level_list as $k=>$v) {
				?>
				<option value="<?php echo $k;?>" <?php if($level==$k) { ?> selected="selected" <?php } ?>><?php echo $v;?></option>
				<?php }} ?>
			 </select>		
		景区名称:<input name="scenename" type="text" size="12" value="<?php echo $scenename?>" />
		景点编号:<input name="infocards" type="text" size="12" value="<?php echo $infocards?>" />
		景点名称:<input name="scenespotname" type="text" size="12" value="<?php echo $scenespotname?>" />
		<input type="submit" name="Submit" value="<?php echo trig_func_common::lang("action","search")?>" /></td>
	  </tr>
	 </table>
  </form>
	  <table width="100%">
		<tr>
		  <th width="50">ID</th>
		  <th width="100">景点名称</th>
		  <th width="100">景点编号</th>
		  <th width="100">景点图片</th>
		  <th width="80">所属景区</th>
		  <th width="80">景区类别</th>
		  <th width="80">游玩主题</th>
		  <th width="80">景区级别</th>
		  <th width="100"><?php echo trig_func_common::lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center"><?php echo $value['scenespotid']; ?></td>
		  <td align="center">
		  <?php echo $value['scenespotname']; ?> <br />
		  <?php echo $value['scenespot_enname']; ?>
		  </td>
		  <td align="center"><?php echo $value['infocards']; ?></td>
		  <td align="center">
    		  <?php if ($value['image']) {?>
    		  <a href="<?php echo UPLOAD_URI.'/'.$value['image']; ?>" target="_blank">
    		  		<img src="<?php echo UPLOAD_URI.'/thumb/'.$value['image']; ?>" width="100" height="100"  /> 
    		  </a>
    		  <?php } ?>
		  </td>
		  <td align="center">
		  <?php echo $value['scenename']; ?> <br />
		  [<a href="<?php echo trig_mvc_route::get_uri("scenespot","add","admin","sceneid=".$value['sceneid']);?>">添加景点</a>]
		  </td>
		  <td align="center"><?php echo $scenetype_list[$value['typeid']]; ?></td>
		  <td align="center"><?php echo $traveltopic_list[$value['traveltopicid']]; ?></td>
		  <td align="center"><?php echo $value['level']; ?>A</td>
		  <td align="center">
				<a href="<?php echo trig_mvc_route::get_uri("scenespot","edit","admin");?>&scenespotid=<?php echo $value['scenespotid']; ?>" ><?php echo trig_func_common::lang("action","edit")?></a>
				<a href="<?php echo trig_mvc_route::get_uri("scenespot","delete","admin");?>&scenespotid=<?php echo $value['scenespotid']; ?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
				 <br />
				[<a href="<?php echo trig_mvc_route::get_uri("relic","add","admin","scenespotid=".$value['scenespotid']);?>">添加文物</a>]
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