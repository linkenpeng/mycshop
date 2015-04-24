<?php
$pagetitle="游玩主题管理";
include trig_mvc_template::view_file("header");
?>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo trig_mvc_route::get_uri("traveltopic","add");?>">添加</a>
</div>
<div class="pageContent">
  
	  <table width="100%">
		<tr>
		  <th width="200">主题名</th>
		  <th width="350">主题说明</th>
		  <th>添加时间</th>
		  <th width="100"><?php echo trig_func_common::lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center">
		  <?php if ($value['image']) {?>
		  <a href="<?php echo UPLOAD_URI.'/'.$value['image']; ?>" target="_blank">
				<img src="<?php echo UPLOAD_URI.'/thumb/'.$value['image']; ?>" width="50"   /> 
		  </a>
		  <?php } ?>
		  <a href="<?php echo trig_mvc_route::get_uri("scene","init","admin",array('typeid'=>$value['typeid']));?>" ><?php echo $value['name']; ?></a>
		  </td>
		  <td><?php echo $value['description']; ?></td>
		  <td align="center"><?php echo date("Y-m-d H:i:s",$value['dateline']);?></td>
		  <td align="center">
				<a href="<?php echo trig_mvc_route::get_uri("traveltopic","edit","admin",array('typeid'=>$value['typeid']));?>" ><?php echo trig_func_common::lang("action","edit")?></a>
				<a href="<?php echo trig_mvc_route::get_uri("traveltopic","delete","admin",array('typeid'=>$value['typeid']));?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
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

</div>
</div>
<?php
include trig_mvc_template::view_file("footer");
?>