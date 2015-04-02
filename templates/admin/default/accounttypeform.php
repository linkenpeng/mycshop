<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
$pagetitle = "添加修改账目分类";
include trig_func_common::admin_template("header");
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$.formValidator.initConfig({formid:"dataForm",autotip:true,onerror:function(){}});
		$("#name").formValidator({onshow:"请输入分类名",onfocus:"请输入分类名",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少一个字符"});
	})
//-->
</script>
<div class="pageMain">
<div class="pageTitle">当前位置：<?php echo $pagetitle;?> </div>
<div class="pageContent">
  <table width="100%">
    <form action="<?php echo trig_func_common::get_uri();?>" method="post" id="dataForm">
    <input type="hidden" name="actypeid" value="<?php echo $value['actypeid']; ?>" />
    <input type="hidden" name="action" value="1" />
    <tr>
      <td width="20%">分类名称</td>
      <td width="80%"><input type="text" name="name" id="name" value="<?php echo $value['name']; ?>" size="50" /> (*)</td>
    </tr>
    <tr>
      <td width="20%">分类说明</td>
      <td width="80%"><textarea name="description" id="description"" cols="60" rows="6"><?php echo $value['description']; ?></textarea></td>
    </tr>
    <tr>
      <td width="100%" colspan="2" align="center"><input type="submit" value="提交" class="button" /></td>
    </tr>
    </form>
</table>
</div>
</div>
<?php
include trig_func_common::admin_template("footer");
?>