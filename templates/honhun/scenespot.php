<?php
$pagetitle="景点管理";
include admin_template("header");
?>

<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
</div>
<div class="pageContent">
  <form action="<?php echo get_uri();?>" method="get">
	  <input name="<?php echo M;?>" type="hidden" value="<?php echo $_GET[M];?>" />
	  <input name="<?php echo C;?>" type="hidden" value="<?php echo $_GET[C];?>" />
	  <input name="<?php echo A;?>" type="hidden" value="<?php echo $_GET[A];?>" />	  
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
	  <tr>
		
		<td align="right" >
			 <span id="zone"></span>
			 <select id="typeid" name="typeid">
				<option value="">选择分类</option>
				<?php if(is_array($scenetype_list)) { 
					foreach ($scenetype_list as $k=>$v) {
				?>
				<option value="<?php echo $k;?>" <?php if($typeid==$k) { ?> selected="selected" <?php } ?>><?php echo $v;?></option>
				<?php }} ?>
			 </select>
			 
			 <select id="traveltopicid" name="traveltopicid">
				<option value="">选择游玩主题</option>
				<?php if(is_array($traveltopic_list)) { 
					foreach ($traveltopic_list as $k=>$v) {
				?>
				<option value="<?php echo $k;?>" <?php if($traveltopicid==$k) { ?> selected="selected" <?php } ?>><?php echo $v;?></option>
				<?php }} ?>
			 </select>
			 
			 <select id="level" name="level">
				<option value="">选择景点级别</option>
				<?php if(is_array($level_list)) { 
					foreach ($level_list as $k=>$v) {
				?>
				<option value="<?php echo $k;?>" <?php if($level==$k) { ?> selected="selected" <?php } ?>><?php echo $v;?></option>
				<?php }} ?>
			 </select>		
		景区名称:<input name="scenename" type="text" size="12" value="<?=$scenename?>" />
		景点编号:<input name="infocards" type="text" size="12" value="<?=$infocards?>" />
		景点名称:<input name="scenespotname" type="text" size="12" value="<?=$scenespotname?>" />
		<input type="submit" name="Submit" value="<?=lang("action","search")?>" /></td>
	  </tr>
	 </table>
  </form>
	  <table width="100%">
		<tr>
		  <th width="50">ID</th>
		  <th width="100">景点名称</th>
		  <th width="100">景点编号</th>
		  <th width="100">景点图片</th>
		  <th width="80">所属景区</th>
		  <th width="80">景区类别</th>
		  <th width="80">游玩主题</th>
		  <th width="80">景区级别</th>
		  <th width="100"><?=lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td align="center"><?=$value['scenespotid']?></td>
		  <td align="center">
		  <?=$value['scenespotname']?> <br />
		  <?php echo $value['scenespot_enname']?>
		  </td>
		  <td align="center"><?=$value['infocards']?></td>
		  <td align="center">
    		  <?php if ($value['image']) {?>
    		  <a href="<?=UPLOAD_URI.'/'.$value['image']?>" target="_blank">
    		  		<img src="<?=UPLOAD_URI.'/thumb/'.$value['image']?>" width="100" height="100"  /> 
    		  </a>
    		  <?php } ?>
		  </td>
		  <td align="center">
		  <?=$value['scenename']?> <br />
		  [<a href="<?php echo get_uri("scenespot","add","admin","sceneid=".$value['sceneid']);?>">添加景点</a>]
		  </td>
		  <td align="center"><?=$scenetype_list[$value['typeid']]?></td>
		  <td align="center"><?=$traveltopic_list[$value['traveltopicid']]?></td>
		  <td align="center"><?=$value['level']?>A</td>
		  <td align="center">
				<a href="<?php echo get_uri("scenespot","edit","admin");?>&scenespotid=<?=$value['scenespotid']?>" ><?=lang("action","edit")?></a>
				<a href="<?php echo get_uri("scenespot","delete","admin");?>&scenespotid=<?=$value['scenespotid']?>" onclick="return confirm('<?=lang("action","isdelete")?>?');"><?=lang("action","delete")?></a>
				 <br />
				[<a href="<?php echo get_uri("relic","add","admin","scenespotid=".$value['scenespotid']);?>">添加文物</a>]
		  </td>
		</tr>
		<?php }} ?>
		<tr>
			<td colspan=9 align="center">
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