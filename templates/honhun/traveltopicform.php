<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
$pagetitle = "添加修改游玩主题";
include admin_template("header");
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$.formValidator.initConfig({formid:"dataForm",autotip:true,onerror:function(){}});
		$("#name").formValidator({onshow:"请输入主题名",onfocus:"请输入主题名",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少一个字符"});
	})
//-->
</script>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> </div>
<div class="pageContent">
  <table width="100%">
    <form action="<?php echo get_uri();?>" method="post" id="dataForm" enctype="multipart/form-data">
    <input type="hidden" name="typeid" value="<?=$value['typeid']?>" />
    <input type="hidden" name="action" value="1" />
	<input type="hidden" name="oldimage" value="<?=$value['image']?>" />
    <tr>
      <td width="20%">主题名称</td>
      <td width="80%"><input type="text" name="name" id="name" value="<?=$value['name']?>" size="50" /> (*)</td>
    </tr>
	<tr>
      <td width="20%">主题缩略图</td>
      <td width="80%">
              <?php if ($value['image']) {?>
    		  <a href="<?=UPLOAD_URI.'/'.$value['image']?>" target="_blank">
    		  		<img src="<?=UPLOAD_URI.'/thumb/'.$value['image']?>" width="100" height="100"  /> 
    		  </a>
    		  <?php } ?>
      <input type="file" name="image" id="image"  /></td>
    </tr>
    <tr>
      <td width="20%">主题说明</td>
      <td width="80%"><textarea name="description" id="description"" cols="60" rows="6"><?=$value['description']?></textarea></td>
    </tr>
    <tr>
      <td width="100%" colspan="2" align="center"><input type="submit" value="提交" class="button" /></td>
    </tr>
    </form>
</table>
</div>
</div>
<?php
include admin_template("footer");
?>