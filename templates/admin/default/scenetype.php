<?php
trig_mvc_template::$title = "景区分类";
trig_mvc_template::$keywords = "景区分类";
trig_mvc_template::$description = "景区分类";
?>
<div class="pageTitle">
	<div class="pageTitle_left"></div>当前位置：<?php echo trig_mvc_template::$title;?> 
	<a href="<?php echo trig_mvc_route::get_uri("scenetype","add");?>">添加</a>
</div>
<div class="pageContent">  
	  <table width="100%">
		<tr>
		  <th width="200">分类名</th>
		  <th width="100">分类图片</th>
		  <th width="350">分类说明</th>
		  <th>添加时间</th>
		  <th width="100"><?php echo trig_func_common::lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center">
		  <a href="<?php echo trig_mvc_route::get_uri("scene","init","admin");?>&typeid=<?php echo $value['typeid']; ?>" >
		  <?php echo $value['name']; ?> <br />
		  <?php echo $value['enname'];?>
		  </a>
		  </td>
		  <td align="center">
    		  <?php if ($value['image']) {?>
    		  <a href="<?php echo UPLOAD_URI.'/'.$value['image']; ?>" target="_blank">
    		  		<img src="<?php echo UPLOAD_URI.'/thumb/'.$value['image']; ?>" width="50"   /> 
    		  </a>
    		  <?php } ?>
		  </td>
		  <td><?php echo $value['description']; ?></td>
		  <td align="center"><?php echo date("Y-m-d H:i:s",$value['dateline']);?></td>
		  <td align="center">
				<a href="<?= trig_mvc_route::get_uri("scenetype","edit","admin", array('typeid'=>$value['typeid'])) ?>" ><?php echo trig_func_common::lang("action","edit")?></a>
				<a href="<?= trig_mvc_route::get_uri("scenetype","delete","admin", array('typeid'=>$value['typeid'])) ?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
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