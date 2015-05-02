<?php
trig_mvc_template::$title = "菜单管理";
trig_mvc_template::$keywords = "菜单管理";
trig_mvc_template::$description = "菜单管理";
?>
	<form method="post" action="<?php echo trig_mvc_route::get_uri("","sortorder");?>">
    <div class="panel admin-panel">
    	<div class="panel-head"><strong>内容列表</strong></div>
        <div class="padding border-bottom">
            <input type="button" class="button button-small checkall" name="checkall" checkfor="id" value="全选" />
            <input type="button" class="button button-small border-green" value="添加菜单" />
            <input type="button" class="button button-small border-yellow" value="批量删除" />
        </div>
        <table class="table table-hover">
        <tr>
          <th width="45">选择</th>
		  <th width="180">菜单名</th>
		  <th>模型</th>
		  <th>控制器</th>
		  <th>动作</th>
		  <th>排序</th>
		  <th><?php echo trig_func_common::lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td><input type="checkbox" name="id" value="<?php echo $value['menuid']; ?>" /></td>		
		  <td><?php echo $value['name']; ?></td>
		  <td><?php echo $value['model']; ?></td>
		  <td><?php echo $value['ctrl']; ?></td>
		  <td><?php echo $value['act']; ?></td>
		  <td><input type="text" size="6" value="<?php echo $value['sort_order']; ?>" name="orderid_<?php echo $value['menuid']; ?>" /></td>
		  <td>
				<a class="button border-blue button-little" href="<?php echo trig_mvc_route::get_uri("menu","edit","admin",array('menuid'=>$value['menuid']));?>" ><?php echo trig_func_common::lang("action","edit")?></a>
				<a class="button border-yellow button-little" href="<?php echo trig_mvc_route::get_uri("menu","delete","admin",array('menuid'=>$value['menuid']));?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
				<a class="button button-small border-green" href="<?php echo trig_mvc_route::get_uri("menu","add","admin",array('parentid'=>$value['menuid']));?>" >添加子菜单</a>
		  </td>
		</tr>
		<?php if(!empty($value['subs'])) {
				foreach ($value['subs'] as $val2) {
		?>
				<tr>
				  <td><input type="checkbox" name="id" value="<?php echo $val2['menuid']; ?>" /></td>
				  <td>----<?php echo $val2['name']; ?></td>
				  <td><?php echo $val2['model']; ?></td>
				  <td><?php echo $val2['ctrl']; ?></td>
				  <td><?php echo $val2['act']; ?></td>
				  <td><input type="text" size="6" value="<?php echo $val2['sort_order']; ?>" name="orderid_<?php echo $val2['menuid']; ?>" /></td>
				  <td>
						<a class="button border-blue button-little" href="<?php echo trig_mvc_route::get_uri("menu","edit","admin",array('menuid'=>$val2['menuid']));?>" ><?php echo trig_func_common::lang("action","edit")?></a>
						<a class="button border-yellow button-little" href="<?php echo trig_mvc_route::get_uri("menu","delete","admin",array('menuid'=>$val2['menuid']));?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
						<a class="button button-small border-green" href="<?php echo trig_mvc_route::get_uri("menu","add","admin",array('parentid'=>$val2['menuid']));?>" >添加子菜单</a>
				  </td>
				</tr>
				<?php if(!empty($val2['subs'])) {
						foreach ($val2['subs'] as $val3) {
				?>						
						<tr>
						  <td><input type="checkbox" name="id" value="<?php echo $val3['menuid']; ?>" /></td>
						  <td>--------<?php echo $val3['name']; ?></td>
						  <td><?php echo $val3['model']; ?></td>
						  <td><?php echo $val3['ctrl']; ?></td>
						  <td><?php echo $val3['act']; ?></td>
						  <td></td>
						  <td>
								<a class="button border-blue button-little" href="<?php echo trig_mvc_route::get_uri("menu","edit","admin",array('menuid'=>$val3['menuid']));?>" ><?php echo trig_func_common::lang("action","edit")?></a>
								<a class="button border-yellow button-little" href="<?php echo trig_mvc_route::get_uri("menu","delete","admin",array('menuid'=>$val3['menuid']));?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
								<a class="button button-small border-green" href="<?php echo trig_mvc_route::get_uri("menu","add","admin",array('parentid'=>$val3['menuid']));?>" >添加子菜单</a>
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
				<input type="submit" name="" value="更新排序" class="button bg-main" />
			</td>
		</tr>        
        </table>
        <div class="panel-foot text-center">
        	<div class="page"><?= trig_helper_html::page_info($p) ?></div>
			<div class="run-info"><?= trig_helper_html::run_info(array('startTime' => START_TIME, 'endTime' => trig_func_common::mtime())) ?></div>
            
            <ul class="pagination"><li><a href="#">上一页</a></li></ul>
            <ul class="pagination pagination-group">
                <li><a href="#">1</a></li>
                <li class="active"><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
            </ul>
            <ul class="pagination"><li><a href="#">下一页</a></li></ul>
        </div>
    </div>
    </form>
    <br />
    