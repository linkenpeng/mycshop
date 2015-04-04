<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
$pagetitle = "添加修改账目";
include trig_mvc_template::admin_template("header");
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$.formValidator.initConfig({formid:"dataForm",autotip:true,onerror:function(){}});
		$("#accountname").formValidator({onshow:"请输入账目名",onfocus:"请输入账目名",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少一个字符"});
	});
//-->
</script>
<div class="pageMain">
<div class="pageTitle">当前位置：<?php echo $pagetitle;?> </div>
<div class="pageContent">
  <table width="100%">
    <form action="<?php echo trig_mvc_route::get_uri();?>" method="post" id="dataForm">
    <input type="hidden" name="accountid" value="<?php echo $value['accountid']; ?>" />
    <input type="hidden" name="action" value="1" />
    <tr>
      <td width="20%">账目名</td>
      <td width="80%"><input type="text" name="accountname" id="accountname" value="<?php echo $value['accountname']; ?>" size="50" /> (*)</td>
    </tr>
	<tr>
      <td width="20%">>账目类别</td>
      <td width="80%">
		<select name="actypeid" id="actypeid">
			<?php foreach ($actype_list as $k=>$v) { ?>
				<option value="<?php echo $v['actypeid'] ?>" <?php if($v['actypeid']==$value['actypeid']) echo "selected='selected'";?>><?php echo $v['name'] ?></option>
			<?php } ?>
		</select>
	  (*)</td>
    </tr>
    <tr>
      <td width="20%">备注</td>
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
include trig_mvc_template::admin_template("footer");
?>