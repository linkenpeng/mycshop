<?php
$pagetitle="用户管理";
include admin_template("header");
?>
<div class="pageMain">
<div class="pageTitle">当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo get_uri("user","add");?>">添加</a>
</div>
<div class="pageContent">
  <form action="<?php echo get_uri();?>" method="get">
	  <input name="<?php echo M;?>" type="hidden" value="<?php echo $_GET[M];?>" />
	  <input name="<?php echo C;?>" type="hidden" value="<?php echo $_GET[C];?>" />
	  <input name="<?php echo A;?>" type="hidden" value="<?php echo $_GET[A];?>" />	  
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
	  <tr>
		<td align="right">
		用户名:<input name="keyword" type="text" size="20" value="<?=$keyword?>" />
			 <select id="usertype" name="usertype">
				<option value="">选择分类</option>
				<?php if(is_array($ugroup_list)) { 
					foreach ($ugroup_list as $k=>$v) {
				?>
				<option value="<?php echo $k;?>" <?php if($usertype==$k) { ?> selected="selected" <?php } ?>><?php echo $v;?></option>
				<?php }} ?>
			 </select>
		<input type="submit" name="Submit" value="<?=lang("action","search")?>" /></td>
	  </tr>
	 </table>
  </form>
  
	  <table width="100%">
		<tr>
		  <th width="100">账号</th>
		  <th width="80">用户类型</th>
		  <th width="80">用户姓名</th>
		  <th width="200">所在地</th>
		  <th>创建时间</th>
		  <?php if($_SESSION['usertype']==ADMIN_USER_TYPE) {?>
		  <th width="100"><?=lang("action","operation")?> </th>
		  <?php }?>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center"><?=$value['username']?></td>
		  <td align="center"><?=$ugroup_list[$value['usertype']]?></td>
		  <td align="center"><?php echo $value['realname'];?></td>
		  <td align="center"><?php echo $value['province'].$value['city'].$value['country'];?></td>
		  <td align="center"><?php echo date("Y-m-d H:i:s",$value['regtime']);?></td>
		  <?php if($_SESSION['usertype']==ADMIN_USER_TYPE) {?>
		  <td align="center">
				<a href="<?php echo get_uri("user","edit","admin");?>&uid=<?=$value['uid']?>" ><?=lang("action","edit")?></a>
				<?php if($value['uid']!=1) {?>
				<a href="<?php echo get_uri("user","delete","admin");?>&uid=<?=$value['uid']?>" onclick="return confirm('<?=lang("action","isdelete")?>?');"><?=lang("action","delete")?></a>
				<?php }?>
		  </td>
		  <?php }?>
		</tr>
		<?php }} ?>
		<tr>
			<td colspan=8 align="center">
				<div class="page">
					<?=lang("page","total")?><b><?=$count?></b><?=lang("page","item")?> <b><?=$nowpage?>/<?=$p->totalpage?></b><?=lang("page","page")?> <?php echo $p->show(); ?>
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