<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
$pagetitle = "添加修改菜单";
include admin_template("header");
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$.formValidator.initConfig({formid:"dataForm",autotip:true,onerror:function(){}});
		$("#name").formValidator({onshow:"请输入菜单名",onfocus:"请输入菜单名",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少一个字符"});
		$("#model").formValidator({onshow:"请输模型",onfocus:"请输入模型",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少一个字符"});
		$("#ctrl").formValidator({onshow:"请输入控制器",onfocus:"请输入控制器",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少一个字符"});
		$("#act").formValidator({onshow:"请输入动作",onfocus:"请输入动作",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少一个字符"});
	})
//-->
</script>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> </div>
<div class="pageContent">
  <table width="100%">
    <form action="<?php echo get_uri();?>" method="post" id="dataForm">
    <input type="hidden" name="menuid" value="<?=$value['menuid']?>" />
    <input type="hidden" name="action" value="1" />
	<input type="hidden" name="parentid" value="<?=$value['parentid']?>" />
	<?php if ($value['parent_name']) {?>
	<tr>
      <td width="20%">父菜单名称</td>
      <td width="80%"><?=$value['parent_name']?></td>
    </tr>
	<?php } ?>
    <tr>
      <td width="20%">菜单名称</td>
      <td width="80%"><input type="text" name="name" id="name" value="<?=$value['name']?>" size="50" /> (*)</td>
    </tr>
	<tr>
      <td width="20%">模型</td>
      <td width="80%"><input type="text" name="model" id="model" value="<?=$value['model']?>" size="50" /> (*)</td>
    </tr>
	<tr>
      <td width="20%">控制器</td>
      <td width="80%"><input type="text" name="ctrl" id="ctrl" value="<?=$value['ctrl']?>" size="50" /> (*)</td>
    </tr>
	<tr>
      <td width="20%">动作</td>
      <td width="80%"><input type="text" name="act" id="act" value="<?=$value['act']?>" size="50" /> (*)</td>
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