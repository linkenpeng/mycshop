<?php
trig_mvc_template::$title = "菜单管理";
?>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo trig_mvc_template::$title;?> 
<a href="<?php echo trig_mvc_route::get_uri("menu","add");?>">添加</a>
</div>
<div class="pageContent">
  <form action="<?php echo trig_mvc_route::get_uri("","sortorder");?>" method="post">
	  <table width="100%">
		<tr>
		  <th width="100">id/父id</th>
		  <th width="150">菜单名</th>
		  <th width="150">模型</th>
		  <th width="100">控制器</th>
		  <th>动作</th>
		  <th width="60">排序</th>
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
		  <td align="center"><input type="text" size="6" value="<?php echo $value['sort_order']; ?>" name="orderid_<?php echo $value['menuid']; ?>" /></td>
		  <td align="center">
				<a href="<?php echo trig_mvc_route::get_uri("menu","edit","admin",array('menuid'=>$value['menuid']));?>" ><?php echo trig_func_common::lang("action","edit")?></a>
				<a href="<?php echo trig_mvc_route::get_uri("menu","delete","admin",array('menuid'=>$value['menuid']));?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
				<a href="<?php echo trig_mvc_route::get_uri("menu","add","admin",array('parentid'=>$value['menuid']));?>" >添加子菜单</a>
		  </td>
		</tr>
		<?php if(!empty($value['subs'])) {
				foreach ($value['subs'] as $val2) {
		?>
				<tr>
				  <td align="center"><?php echo $val2['menuid']; ?>/<?php echo $val2['parentid']; ?></td>
				  <td>----<?php echo $val2['name']; ?></td>
				  <td align="center"><?php echo $val2['model']; ?></td>
				  <td align="center"><?php echo $val2['ctrl']; ?></td>
				  <td align="center"><?php echo $val2['act']; ?></td>
				  <td align="center"></td>
				  <td align="center">
						<a href="<?php echo trig_mvc_route::get_uri("menu","edit","admin",array('menuid'=>$val2['menuid']));?>" ><?php echo trig_func_common::lang("action","edit")?></a>
						<a href="<?php echo trig_mvc_route::get_uri("menu","delete","admin",array('menuid'=>$val2['menuid']));?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
						<a href="<?php echo trig_mvc_route::get_uri("menu","add","admin",array('parentid'=>$val2['menuid']));?>" >添加子菜单</a>
				  </td>
				</tr>
				<?php if(!empty($val2['subs'])) {
						foreach ($val2['subs'] as $val3) {
				?>						
						<tr>
						  <td align="center"><?php echo $val3['menuid']; ?>/<?php echo $val3['parentid']; ?></td>
						  <td>--------<?php echo $val3['name']; ?></td>
						  <td align="center"><?php echo $val3['model']; ?></td>
						  <td align="center"><?php echo $val3['ctrl']; ?></td>
						  <td align="center"><?php echo $val3['act']; ?></td>
						  <td align="center"></td>
						  <td align="center">
								<a href="<?php echo trig_mvc_route::get_uri("menu","edit","admin",array('menuid'=>$val3['menuid']));?>" ><?php echo trig_func_common::lang("action","edit")?></a>
								<a href="<?php echo trig_mvc_route::get_uri("menu","delete","admin",array('menuid'=>$val3['menuid']));?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
								<a href="<?php echo trig_mvc_route::get_uri("menu","add","admin",array('parentid'=>$val3['menuid']));?>" >添加子菜单</a>
						  </td>
						</tr>				
		<?php 	
						} // end level 3
					} // end level 3 if
					
				} //end level 2 
			  } // end level 2 if
			  
		  } // end level 1
		} // end level 1 if		
		?>
		<tr>
			<td colspan=8 align="right">
				<input type="submit" name="" value="更新排序" />
			</td>
		</tr>
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