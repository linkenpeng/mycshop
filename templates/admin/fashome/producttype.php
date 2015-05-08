<?php
$cur_name = '产品分类';
trig_mvc_template::$title = $cur_name."管理";
trig_mvc_template::$keywords = $cur_name."管理";
trig_mvc_template::$description = $cur_name."管理";
?>
    <div class="panel admin-panel">
    	<div class="panel-head">
    	<strong><?php echo $cur_name; ?>列表</strong>
    	 <a  href="<?php echo trig_mvc_route::get_uri('', 'add');?>" class="button border-blue">
    	 <span class="icon-plus text-blue"></span>
    	 添加<?php echo $cur_name; ?></a>
    	</div>
    	
        <div class="padding border-bottom">
        	<form class="form-inline" action="<?php echo trig_mvc_route::get_uri();?>" method="get">
        	  <input name="<?php echo M;?>" type="hidden" value="<?php echo $_GET[M];?>" />
			  <input name="<?php echo C;?>" type="hidden" value="<?php echo $_GET[C];?>" />
			  <input name="<?php echo A;?>" type="hidden" value="<?php echo $_GET[A];?>" />	  
			  <div class="form-group">
			    <div class="field">
			      <input type="text" class="input" id="name" name="name" size="20" placeholder="分类名" value="<?php echo isset($_GET['name']) ? $_GET['name'] : ''; ?>" />
			    </div>
			  </div>
			  <div class="form-button"><button class="button bg-main" type="submit"><?php echo trig_func_common::lang("action","search")?></button></div>
			</form>			 
        </div>
        
        <table class="table table-hover">
			<tr>
		  <th width="200">分类名</th>
		  <th width="100">分类图片</th>
		  <th width="150">分类说明</th>
		  <th>添加时间</th>
		  <th width="100"><?php echo trig_func_common::lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center">
		  <a href="<?php echo trig_mvc_route::get_uri("product","init","admin",array('typeid'=>$value['typeid']));?>" ><?php echo $value['name']; ?></a>
		  </td>
		  <td align="center">
    		  <?php if ($value['image']) {?>
    		  <a href="<?php echo UPLOAD_URI.'/'.$value['image']; ?>" target="_blank">
    		  		<img src="<?php echo UPLOAD_URI.'/thumb/'.$value['image']; ?>" width="100" height="100"  /> 
    		  </a>
    		  <?php } ?>
		  </td>
		  <td align="center"><?php echo $value['description']; ?></td>
		  <td align="center"><?php echo date("Y-m-d H:i:s",$value['dateline']);?></td>
		  <td align="center">
				<a href="<?php echo trig_mvc_route::get_uri("producttype","edit","admin",array('typeid'=>$value['typeid']));?>" ><?php echo trig_func_common::lang("action","edit")?></a>
				<a href="<?php echo trig_mvc_route::get_uri("producttype","delete","admin",array('typeid'=>$value['typeid']));?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
		  </td>
		</tr>
		<?php }} ?>
		</table>      
        
		<div class="panel-foot text-center">
        	<?php echo trig_helper_html::page_info($p); ?>
			<?php echo trig_helper_html::run_info(array('startTime' => START_TIME, 'endTime' => trig_func_common::mtime())) ?>
        </div>
    </div>
