<?php
$pagetitle="公告管理";
include admin_template("header");
?>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo get_uri("note","add");?>">添加</a>
</div>
<div class="pageContent">
  <form action="<?php echo get_uri();?>" method="get">
	  <input name="<?php echo M;?>" type="hidden" value="<?php echo $_GET[M];?>" />
	  <input name="<?php echo C;?>" type="hidden" value="<?php echo $_GET[C];?>" />
	  <input name="<?php echo A;?>" type="hidden" value="<?php echo $_GET[A];?>" />	  
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
	  <tr>
		<td align="right">
		标题:<input name="keyword" type="text" size="20" value="<?php echo $keyword?>" />
			创建时间从 
			<input name="startdate" id="startdate" type="text" size="20" value="<?php echo $startdate?>" class="Wdate" onClick="WdatePicker()" />
			到 <input name="enddate" id="enddate" type="text" size="20" value="<?php echo $enddate?>" class="Wdate" onClick="WdatePicker()" />
			 <select id="notetypeid" name="notetypeid">
				<option value="">选择分类</option>
				<?php if(is_array($notetypes)) { 
					foreach ($notetypes as $k=>$v) {
				?>
				<option value="<?php echo $k;?>" <?php if($notetypeid==$k) { ?> selected="selected" <?php } ?>><?php echo $v;?></option>
				<?php }} ?>
			 </select>
		<input type="submit" name="Submit" value="<?php echo lang("action","search")?>" /></td>
	  </tr>
	 </table>
  </form>
	  <table width="100%">
		<tr>
		  <th width="200">标题</th>
		  <th width="150">所属分类</th>
		  <th width="60">创建人</th>
		  <th>添加时间</th>
		  <th width="100">附件</th>
		  <th width="100"><?php echo lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center"><?php echo $value['title']; ?></td>
		  <td align="center"><?php echo $notetypes[$value['notetypeid']]; ?></td>
		  <td align="center"><?php echo $value['username'];?></td>
		  <td align="center"><?php echo date("Y-m-d H:i:s",$value['dateline']);?></td>
		  <td align="center">
		  <a href="<?php echo UPLOAD_URI.'/'.$value['attachment']; ?>" target="_blank">
		    <?php echo $value['attachment_name'];?>
		  </a>
		</td>
		  <td align="center">
				<a href="<?php echo get_uri("note","edit","admin");?>&noteid=<?php echo $value['noteid']; ?>" ><?php echo lang("action","edit")?></a>
				<a href="<?php echo get_uri("note","delete","admin");?>&noteid=<?php echo $value['noteid']; ?>" onclick="return confirm('<?php echo lang("action","isdelete")?>?');"><?php echo lang("action","delete")?></a>
		  </td>
		</tr>
		<?php }} ?>
		<tr>
			<td colspan=8 align="center">
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