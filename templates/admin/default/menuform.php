<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
trig_mvc_template::$title = "添加修改菜单";
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
<div class="pageTitle_left"></div>当前位置：<?php echo trig_mvc_template::$title;?> </div>
<div class="pageContent">
  <table width="100%">
    <form action="<?php echo trig_mvc_route::get_uri();?>" method="post" id="dataForm">
    <input type="hidden" name="menuid" value="<?php echo $value['menuid']; ?>" />
    <input type="hidden" name="action" value="1" />
	<input type="hidden" name="parentid" value="<?php echo $value['parentid']; ?>" />	
	<?php 
	  if(!empty($up_menus)) {
	?>
	<tr>
      <td width="20%">父菜单名称</td>
      <td width="80%">
	  <select id="parentid" name="parentid">
	  <?php 
	  foreach($up_menus as $up_menu) { ?>
		<option value="<?php echo $up_menu['menuid']; ?>" <?php if($value['parentid'] == $up_menu['menuid']) echo 'selected'; ?>><?php echo $up_menu['name']; ?></option>
	  <?php } ?>
	  </select>
	  </td>
    </tr>
	<?php } ?>
    <tr>
      <td width="20%">菜单名称</td>
      <td width="80%"><input type="text" name="name" id="name" value="<?php echo $value['name']; ?>" size="20" /> (*)</td>
    </tr>
	<tr>
      <td width="20%">图标Icon</td>
      <td width="80%"><input type="text" name="icon" id="icon" value="<?php echo $value['icon']; ?>" size="20" /> (*)</td>
    </tr>
	<tr>
      <td width="20%">菜单级别</td>
      <td width="80%">
	  <select id="level" name="level">
	  <?php foreach($levels as $level) { ?>
		<option value="<?php echo $level; ?>" <?php if($value['level'] == $level) echo 'selected'; ?>><?php echo $level; ?></option>
	  <?php } ?>
	  </select>
	  (*)</td>
    </tr>
	<tr>
      <td width="20%">模型</td>
      <td width="80%"><input type="text" name="model" id="model" value="<?php echo $value['model']; ?>" size="20" /> (*)</td>
    </tr>
	<tr>
      <td width="20%">控制器</td>
      <td width="80%"><input type="text" name="ctrl" id="ctrl" value="<?php echo $value['ctrl']; ?>" size="20" /> (*)</td>
    </tr>
	<tr>
      <td width="20%">动作</td>
      <td width="80%"><input type="text" name="act" id="act" value="<?php echo $value['act']; ?>" size="20" /> (*)</td>
    </tr>
    <tr>
      <td width="100%" colspan="2" align="center"><input type="submit" value="提交" class="button" /></td>
    </tr>
    </form>
</table>
</div>
</div>