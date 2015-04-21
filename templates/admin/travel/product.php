<?php
$pagetitle="产品管理";
include trig_mvc_template::admin_template("header");
?>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo trig_mvc_route::get_uri("product","add");?>">添加</a>
</div>
<div class="pageContent">
  <form action="<?php echo trig_mvc_route::get_uri();?>" method="get">
	  <input name="<?php echo M;?>" type="hidden" value="<?php echo $_GET[M];?>" />
	  <input name="<?php echo C;?>" type="hidden" value="<?php echo $_GET[C];?>" />
	  <input name="<?php echo A;?>" type="hidden" value="<?php echo $_GET[A];?>" />	  
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
	  <tr>
		
		<td align="right" >
			 <select id="typeid" name="typeid">
				<option value="">选择分类</option>
				<?php if(is_array($producttype_list)) { 
					foreach ($producttype_list as $k=>$v) {
				?>
				<option value="<?php echo $k;?>" <?php if($typeid==$k) { ?> selected="selected" <?php } ?>><?php echo $v;?></option>
				<?php }} ?>
			 </select>
		型号:<input name="model" type="text" size="20" value="<?php echo $model?>" />
		条形码:<input name="barcode" type="text" size="20" value="<?php echo $barcode?>" />
		<input type="submit" name="Submit" value="<?php echo trig_func_common::lang("action","search")?>" /></td>
	  </tr>
	 </table>
  </form>
	  <table width="100%">
		<tr>
		  <th width="100">产品型号</th>
		  <th width="100">产品图片</th>
		  <th width="80">产品类别</th>
		  <th width="80">库存</th>
		  <th width="80">体积</th>
		  <th width="80">重量</th>
		  <th width="100">条形码</th>
		  <th>单价</th>
		  <th width="100"><?php echo trig_func_common::lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center"><?php echo $value['model']; ?></td>
		  <td align="center">
    		  <?php if ($value['image']) {?>
    		  <a href="<?php echo UPLOAD_URI.'/'.$value['image']; ?>" target="_blank">
    		  		<img src="<?php echo UPLOAD_URI.'/thumb/'.$value['image']; ?>" width="100" height="100"  /> 
    		  </a>
    		  <?php } ?>
		  </td>
		  <td align="center"><?php echo $producttype_list[$value['typeid']]; ?></td>
		  <td align="center"><?php echo $value['storage'];?></td>
		  <td align="center"><?php echo $value['volumn'];?></td>
		  <td align="center"><?php echo $value['weight'];?></td>
		  <td align="center"><?php echo $value['barcode'];?></td>
		  <td align="center"><?php echo $value['price'];?></td>
		  <td align="center">
				<a href="<?php echo trig_mvc_route::get_uri("product","edit","admin",array('productid'=>$value['productid']));?>" ><?php echo trig_func_common::lang("action","edit")?></a>
				<a href="<?php echo trig_mvc_route::get_uri("product","delete","admin",array('productid'=>$value['productid']));?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
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