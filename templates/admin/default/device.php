<?php
$pagetitle="设备管理";
include trig_mvc_template::view_file("header");
?>

<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo trig_mvc_route::get_uri("device","add");?>">添加</a>
</div>
<div class="pageContent">
  <form action="<?php echo trig_mvc_route::get_uri();?>" method="get">
	  <input name="<?php echo M;?>" type="hidden" value="<?php echo $_GET[M];?>" />
	  <input name="<?php echo C;?>" type="hidden" value="<?php echo $_GET[C];?>" />
	  <input name="<?php echo A;?>" type="hidden" value="<?php echo $_GET[A];?>" />	  
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
	  <tr>		
		<td align="right" >
		品牌:<input name="brand" type="text" size="12" value="<?php echo $brand?>" />
		<input type="submit" name="Submit" value="<?php echo trig_func_common::lang("action","search")?>" /></td>
	  </tr>
	 </table>
  </form>
	  <table width="100%">
		<tr>
		  <th width="100">设备ID</th>
		  <th width="80">品牌</th>
		  <th width="80">型号</th>
		  <th width="70">Android版本</th>
		  <th width="80">分辨率</th>
		  <th width="60">登陆次数</th>		  
		  <th width="60">第一次登陆时间</th>
		  <th width="60">最后登陆时间</th>
		  <th width="60">最后登陆IP</th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td><?php echo $value['mac']; ?></td>
		  <td><?php echo $value['brand']; ?></td>
		  <td><?php echo $value['model']; ?></td>
		  <td align="center"><?php echo $value['android_version']; ?></td>
		  <td align="center"><?php echo $value['screen_height']; ?>*<?php echo $value['screen_width']; ?></td>
		  <td align="center"><?php echo $value['loginnum']; ?></td>
		  <td><?php echo $value['createtime']; ?></td>
		  <td><?php echo $value['lasttime']; ?></td>
		  <td><?php echo $value['lastip']; ?></td>
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
include trig_mvc_template::view_file("footer");
?>