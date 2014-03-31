<?php
$pagetitle="设备管理";
include admin_template("header");
?>

<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> 
<a href="<?php echo get_uri("device","add");?>">添加</a>
</div>
<div class="pageContent">
  <form action="<?php echo get_uri();?>" method="get">
	  <input name="<?php echo M;?>" type="hidden" value="<?php echo $_GET[M];?>" />
	  <input name="<?php echo C;?>" type="hidden" value="<?php echo $_GET[C];?>" />
	  <input name="<?php echo A;?>" type="hidden" value="<?php echo $_GET[A];?>" />	  
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
	  <tr>
		
		<td align="right" >
		品牌:<input name="brand" type="text" size="12" value="<?=$brand?>" />
		<input type="submit" name="Submit" value="<?=lang("action","search")?>" /></td>
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
		  <td><?=$value['mac']?></td>
		  <td><?=$value['brand']?></td>
		  <td><?=$value['model']?></td>
		  <td align="center"><?=$value['android_version']?></td>
		  <td align="center"><?=$value['screen_height']?>*<?=$value['screen_width']?></td>
		  <td align="center"><?=$value['loginnum']?></td>
		  <td><?=$value['createtime']?></td>
		  <td><?=$value['lasttime']?></td>
		  <td><?=$value['lastip']?></td>
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