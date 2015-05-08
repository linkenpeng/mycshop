<?php
$cur_name = '产品';
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
			      <select id="typeid" name="typeid" class="input">
					<option value="">选择分类</option>
					<?php if(is_array($producttype_list)) { 
						foreach ($producttype_list as $k=>$v) {
					?>
					<option value="<?php echo $k;?>" <?php if($typeid==$k) { ?> selected="selected" <?php } ?>><?php echo $v;?></option>
					<?php }} ?>
				 </select>
			    </div>
			  </div>
			  <div class="form-group">
			    <div class="field">
			      <input type="text" class="input" id="model" name="model" size="20" placeholder="型号" value="<?php echo isset($_GET['model']) ? $_GET['model'] : ''; ?>" />
			    </div>
			  </div>
			  <div class="form-group">
			    <div class="field">
			      <input type="text" class="input" id="barcode" name="barcode" size="20" placeholder="条形码" value="<?php echo isset($_GET['barcode']) ? $_GET['barcode'] : ''; ?>" />
			    </div>
			  </div>
			  <div class="form-button"><button class="button bg-main" type="submit"><?php echo trig_func_common::lang("action","search")?></button></div>
			</form>			 
        </div>
        
        <table class="table table-hover">
			<tr>
			  <th width="100">产品型号</th>
			  <th width="100">产品图片</th>
			  <th width="80">产品类别</th>
			  <th width="80">库存</th>
			  <th width="80">体积</th>
			  <th width="80">重量</th>
			  <th width="100">条形码</th>
			  <th>单价</th>
			  <th width="150"><?php echo trig_func_common::lang("action","operation")?> </th>
			</tr>
			<?php if(is_array($list)) { 
				foreach ($list as $value) {
			?>
			<tr>
			  <td><?php echo $value['model']; ?></td>
			  <td>
	    		  <?php if ($value['image']) {?>
	    		  <a href="<?php echo UPLOAD_URI.'/'.$value['image']; ?>" target="_blank">
	    		  		<img src="<?php echo UPLOAD_URI.'/thumb/'.$value['image']; ?>" width="100" height="100"  /> 
	    		  </a>
	    		  <?php } ?>
			  </td>
			  <td><?php echo $producttype_list[$value['typeid']]; ?></td>
			  <td><?php echo $value['storage'];?></td>
			  <td><?php echo $value['volumn'];?></td>
			  <td><?php echo $value['weight'];?></td>
			  <td><?php echo $value['barcode'];?></td>
			  <td><?php echo $value['price'];?></td>
			  <td>
					<a href="<?php echo trig_mvc_route::get_uri("product","edit","admin",array('productid'=>$value['productid']));?>" ><span class="icon-edit text-blue"></span><?php echo trig_func_common::lang("action","edit")?></a>
					<a href="<?php echo trig_mvc_route::get_uri("product","delete","admin",array('productid'=>$value['productid']));?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><span class="icon-trash-o text-red"></span><?php echo trig_func_common::lang("action","delete")?></a>
			  </td>
			</tr>
			<?php } 
			} ?>
		</table>      
        
		<div class="panel-foot text-center">
        	<?php echo trig_helper_html::page_info($p); ?>
			<?php echo trig_helper_html::run_info(array('startTime' => START_TIME, 'endTime' => trig_func_common::mtime())) ?>
        </div>
    </div>