<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
$pagetitle = "谈记录";
include admin_template("header");
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$.formValidator.initConfig({formid:"dataForm",autotip:true,onerror:function(){}});
		$("#content").formValidator({onshow:"请输入回复内容",onfocus:"请输入回复内容",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少一个字符"});
	});
	
//-->
</script>
<div class="pageMain">
<div class="pageTitle">当前位置：<?php echo $pagetitle;?> </div>
<div class="pageContent">
  <table width="100%">
	<tr>
      <td align="center"><h3><?=$value['title']?></h3></td>
    </tr>
	<tr>
      <td align="center"><?php echo date("Y-m-d H:i:s",$value['dateline']);?></td>
    </tr>
    <tr>
      <td><?=$value['content']?></td>
    </tr>
    <!--
	<tr>
      <td><?=$value['sendto']?></td>
    </tr>
	-->
	<?php if($value['attachment_name']) { ?>
	<tr>
      <td>附件： <a href="<?=UPLOAD_URI.'/'.$value['attachment']?>" target="_blank">
		    <?php echo $value['attachment_name'];?>
		  </a></td>
    </tr>
	<?php } ?>
	<?php if(is_array($talkreplylist)) {
	?>
	<tr>
      <td align="center"><h3>回复</h3></td>
    </tr>
	<?php
			foreach ($talkreplylist as $replyvalue) {
		?>
	<tr>
      <td>
	  <div><?=$replyvalue['content']?></div>
	  <?=$replyvalue['username']?> <?php echo date("Y-m-d H:i:s",$replyvalue['dateline']);?>
	  <?php if(($replyvalue['uid']==$_SESSION['admin_uid'])||($_SESSION['admin_uid']==1)) { ?>
	  <a href="<?php echo get_uri("talkreply","delete","admin");?>&talkreplyid=<?=$replyvalue['talkreplyid']?>&talkid=<?=$replyvalue['talkid']?>" onclick="return confirm('<?=lang("action","isdelete")?>?');"><?=lang("action","delete")?></a>
	  <?php } ?>
	  </td>
    </tr>
	<?php }} ?>
    <tr>
      <td>
			<form action="<?php echo get_uri("talkreply","add");?>" method="post" id="dataForm">
			<input type="hidden" name="talkid" value="<?=$value['talkid']?>" />
			<input type="hidden" name="action" value="1" />
			<table border="0" width="100%">
			<tr>
			<td>
				<textarea name="content" id="content"" cols="60" rows="10"></textarea>
			</td>
			</tr>
			<tr>
			<td>
				<input type="submit" value="回复" class="button" />
			</td>
			</tr>
			</table>
			</form>
	  </td>
    </tr>
</table>
</div>
</div>
<?php
include admin_template("footer");
?>