<?php
$pagetitle="评论管理";
include trig_mvc_template::admin_template("header");
?>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
</div>
<div class="pageContent">
  <form action="<?php echo trig_mvc_route::get_uri();?>" method="get">
	  <input name="<?php echo M;?>" type="hidden" value="<?php echo $_GET[M];?>" />
	  <input name="<?php echo C;?>" type="hidden" value="<?php echo $_GET[C];?>" />
	  <input name="<?php echo A;?>" type="hidden" value="<?php echo $_GET[A];?>" />	  
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
	  <tr>
		<td align="right">
		标题:<input name="keyword" type="text" size="20" value="<?php echo $keyword?>" />
		评论人:<input name="username" type="text" size="12" value="<?php echo $username?>" />
			评论时间从 
			<input name="startdate" id="startdate" type="text" size="20" value="<?php echo $startdate?>" class="Wdate" onClick="WdatePicker()" />
			到 <input name="enddate" id="enddate" type="text" size="20" value="<?php echo $enddate?>" class="Wdate" onClick="WdatePicker()" />
		<input type="submit" name="Submit" value="<?php echo trig_func_common::lang("action","search")?>" /></td>
	  </tr>
	 </table>
  </form>
	  <table width="100%">
		<tr>
		  <th>评论点</th>
		  <th width="100">评论人</th>
		  <th width="250">评论时间</th>
		  <th width="100"><?php echo trig_func_common::lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="left">
		  <font color="#CCCCCC">
		  <?php 
		  if ($value['comment_type']==1) { 
			echo '[景区]';
		  } else if($value['comment_type']==2) { 
			echo '[景点]';
		  }
          ?>
		  </font>
		  <?php echo $value['commented_title']; ?>
		  </td>  
		  <td align="center"><?php echo $value['username'];?></td>
		  <td align="center"><?php echo date("Y-m-d H:i:s",$value['dateline']);?></td>
		  <td align="center">
				<a href="<?php echo trig_mvc_route::get_uri("comment","delete","admin",array('commentid'=>$value['commentid']));?>" onclick="return confirm('<?php echo trig_func_common::lang("action","isdelete")?>?');"><?php echo trig_func_common::lang("action","delete")?></a>
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
include trig_mvc_template::admin_template("footer");
?>