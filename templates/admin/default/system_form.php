<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
trig_mvc_template::$title = "添加修改配置";
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$.formValidator.initConfig({formid:"dataForm",autotip:true,onerror:function(){}});
		$("#name").formValidator({onshow:"配置描述",onfocus:"配置描述",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少一个字符"});
		$("#config_key").formValidator({onshow:"配置键",onfocus:"配置键",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少一个字符"});
		$("#config_value").formValidator({onshow:"配置值",onfocus:"配置值",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少一个字符"});
	})
//-->
</script>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo trig_mvc_template::$title;?> </div>
<div class="pageContent">
  <table width="100%">
    <form action="<?php echo trig_mvc_route::get_uri();?>" method="post" id="dataForm">
    <input type="hidden" name="sid" value="<?php echo $value['sid']; ?>" />
    <input type="hidden" name="action" value="1" />    
	<tr>
      <td width="20%">配置键</td>
      <td width="80%"><input type="text" name="config_key" 
      id="config_key" value="<?php echo $value['config_key']; ?>" size="50" 
      <?php if(!empty($value['sid'])) echo 'readonly'; ?>
      /> (*)</td>
    </tr>
	<tr>
      <td width="20%">配置值</td>
      <td width="80%"><input type="text" name="config_value" id="config_value" value="<?php echo $value['config_value']; ?>" size="50" /> (*)</td>
    </tr>
    <tr>
      <td width="20%">配置描述</td>
      <td width="80%"><input type="text" name="name" id="name" value="<?php echo $value['name']; ?>" size="50" /> (*)</td>
    </tr>
    <tr>
      <td width="100%" colspan="2" align="center"><input type="submit" value="提交" class="button" /></td>
    </tr>
    </form>
</table>
</div>
</div>