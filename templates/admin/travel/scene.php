<?php
$pagetitle="景区管理";
include trig_mvc_template::admin_template("header");
?>

<script type="text/javascript"> 
<!--
	$(function(){	
		showallzone(document.getElementById('zone'), 'province', 'city', 'country', '<?php echo $province?>', '<?php echo $city?>', '<?php echo $country?>');
	});
	
//-->
</script>

<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo trig_mvc_route::get_uri("scene","add");?>">添加</a>
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
				<option value="">选择景区级别</option>
				<?php if(is_array($level_list)) { 
					foreach ($level_list as $k=>$v) {
				?>
				<option value="<?php echo $k;?>" <?php if($level==$k) { ?> selected="selected" <?php } ?>><?php echo $v;?></option>
				<?php }} ?>
			 </select>
		景区编号:<input name="scenenum" type="text" size="10" value="<?php echo $scenenum?>" />	 
		名称:<input name="scenename" type="text" size="20" value="<?php echo $scenename?>" />
		<input type="submit" name="Submit" value="<?php echo trig_func_common::lang("action","search")?>" /></td>
	  </tr>
	 </table>
  </form>
	  <table width="100%">
		<tr>
		  <th width="50">ID</th>
		  <th width="100">景区名称</th>
		  <th width="80">景区编号</th>
		  <th width="100">景区图片</th>
		  <th width="80">景区类别</th>
		  <th width="80">游玩主题</th>
		  <th width="80">景区级别</th>
		  <th width="100">省市区</th>
		  <th>详细地址</th>
		  <th width="100"><?php echo trig_func_common::lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center"><?php echo $value['sceneid']; ?></td>
		  <td align="center">
		  <?php echo $value['scenename']; ?> <br />
		  <?php echo $value['scene_enname'];?>
		  </td>
		  <td align="center"><?php echo $value['scenenum']; ?></td>
		  <td align="center">
    		  <?php if ($value['image']) {?>
    		  <a href="<?php echo UPLOAD_URI.'/'.$value['image']; ?>" target="_blank">
    		  		<img src="<?php echo UPLOAD_URI.'/thumb/'.$value['image']; ?>" width="100" height="100"  /> 
    		  </a>
    		  <?php } ?>
		  </td>
		  <td align="center"><?php echo isset($scenetype_list[$value['typeid']]) ? : $scenetype_list[$value['typeid']]; ?></td>
		  <td align="center"><?php echo isset($traveltopic_list[$value['traveltopicid']]) ? $traveltopic_list[$value['traveltopicid']] : ''; ?></td>
		  <td align="center"><?php echo $value['level']; ?>A</td>
		  <td align="center"><?php echo $value['province'];?><br /> <?php echo $value['city'];?><br /> <?php echo $value['country'];?></td>
		  <td><?php echo $value['address'];?></td>
		  <td align="center">
				<a href="<?php echo trig_mvc_route::get_uri("scene","edit","admin",array('sceneid'=>$value['sceneid']));?>" ><?php echo trig_func_common::lang("action","edit")?></a>
				<a href="<?php echo trig_mvc_route::get_uri("scene","delete","admin",array('sceneid'=>$value['sceneid']));?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
				
				<br /> [<a href="<?php echo trig_mvc_route::get_uri("scenespot","add","admin",array('sceneid'=>$value['sceneid']));?>">添加景点</a>]
				<br /> [<a href="<?php echo trig_mvc_route::get_uri("article","add","admin",array('sceneid'=>$value['sceneid']));?>">添加文章</a>]
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