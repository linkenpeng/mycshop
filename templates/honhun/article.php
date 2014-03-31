<?php
$pagetitle="文章管理";
include admin_template("header");
?>

<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo get_uri("article","add");?>">添加</a>
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
			 <select id="catid" name="catid">
				<option value="">选择分类</option>
				<?php echo $category_options; ?>
			 </select>
			 
			 <select id="sceneid" name="sceneid">
				<option value="">选择景区</option>
				<?php if(is_array($sc_list)) { 
					foreach ($sc_list as $k=>$v) {
				?>
				<option value="<?php echo $k;?>" <?php if($sceneid==$k) { ?> selected="selected" <?php } ?>><?php echo $v;?></option>
				<?php }} ?>
			 </select>

		文章标题:<input name="title" type="text" size="12" value="<?=$title?>" />
		<input type="submit" name="Submit" value="<?=lang("action","search")?>" /></td>
	  </tr>
	 </table>
  </form>
	  <table width="100%">
		<tr>
		  <th width="100">文章标题</th>
		  <th width="100">文章图片</th>
		  <th width="80">文章类别</th>
		  <th width="100">所属景区</th>
		  <th width="100">添加时间</th>
		  <th width="60">排序号</th>
		  <th width="100"><?=lang("action","operation")?> </th>
		</tr>
		<?php if(is_array($list)) { 
			foreach ($list as $value) {
		?>
		<tr>
		  <td><?=$value['title']?></td>
		  <td align="center">
    		  <?php if ($value['image']) {?>
    		  <a href="<?=UPLOAD_URI.'/'.$value['image']?>" target="_blank">
    		  		<img src="<?=UPLOAD_URI.'/thumb/'.$value['image']?>" width="100" height="100"  /> 
    		  </a>
    		  <?php } ?>
		  </td>
		  <td align="center"><?=$cat_list[$value['catid']]?></td>
		  <td align="center">
			<?php 
			//所属景区
			if(!empty($value['sceneid'])) {
				$sceneids = explode(',', $value['sceneid']);
				$scenes = array();				
				foreach ($sceneids as $scid) {
					$scenes[] = $sc_list[$scid];
				}
				echo implode(',', $scenes);
			}			
			?>
		  </td>
		  <td align="center"><?php echo date("Y-m-d H:i:s",$value['dateline']);?></td>
		  <td align="center"><?php echo $value['ordernum'];?></td>
		  <td align="center">
				<a href="<?php echo get_uri("article","edit","admin");?>&aid=<?=$value['aid']?>" ><?=lang("action","edit")?></a>
				<a href="<?php echo get_uri("article","delete","admin");?>&aid=<?=$value['aid']?>" onclick="return confirm('<?=lang("action","isdelete")?>?');"><?=lang("action","delete")?></a>
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