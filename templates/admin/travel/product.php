<?php
$pagetitle="产品管理";
include admin_template("header");
?>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo get_uri("product","add");?>">添加</a>
</div>
<div class="pageContent">
  <form action="<?php echo get_uri();?>" method="get">
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
		<input type="submit" name="Submit" value="<?php echo lang("action","search")?>" /></td>
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
		  <th width="100"><?php echo lang("action","operation")?> </th>
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
				<a href="<?php echo get_uri("product","edit","admin");?>&productid=<?php echo $value['productid']; ?>" ><?php echo lang("action","edit")?></a>
				<a href="<?php echo get_uri("product","delete","admin");?>&productid=<?php echo $value['productid']; ?>" onclick="return confirm('<?php echo lang("action","isdelete")?>?');"><?php echo lang("action","delete")?></a>
		  </td>
		</tr>
		<?php }} ?>
		<tr>
			<td colspan=9 align="center">
				<div class="page">
					<?php echo lang("page","total")?><b><?php echo $count?></b><?php echo lang("page","item")?> <b><?php echo $nowpage?>/<?php echo $p->totalpage?></b><?php echo lang("page","page")?> <?php echo $p->show(); ?>
				</div>
			<?php
				$endTime = mtime();
				$totaltime = sprintf("%.3f",($endTime - START_TIME));
				echo lang("page","thispage").lang("common","excute").lang("common","time").($totaltime).lang("common","second");
			?>
			</td>
		</tr>
	</table>

</div>
</div>
<?php
include admin_template("footer");
?>