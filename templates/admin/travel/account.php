<?php
$pagetitle="账目管理";
include trig_mvc_template::view("header");
?>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo trig_mvc_route::get_uri("account","add");?>">添加</a>
</div>
<div class="pageContent">
  
	  <table width="100%">
		<tr>
		  <th width="200">账目名</th>
		  <th width="80">账目类别</th>
		  <th width="150">备注</th>
		  <th>添加时间</th>
		  <th width="100"><?php echo trig_func_common::lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center"><?php echo $value['accountname']; ?></td>
		  <td align="center"><?php echo $accounttype_list[$value['actypeid']]; ?></td>
		  <td align="center"><?php echo $value['description']; ?></td>
		  <td align="center"><?php echo date("Y-m-d H:i:s",$value['dateline']);?></td>
		  <td align="center">
				<a href="<?php echo trig_mvc_route::get_uri("account","edit","admin",array('accountid'=>$value['accountid']));?>" ><?php echo trig_func_common::lang("action","edit")?></a>
				<a href="<?php echo trig_mvc_route::get_uri("account","delete","admin",array('accountid'=>$value['accountid']));?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
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
include trig_mvc_template::view("footer");
?>