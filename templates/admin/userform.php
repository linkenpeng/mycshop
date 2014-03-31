<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
$pagetitle = "添加修改用户";
include admin_template("header");
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$.formValidator.initConfig({formid:"dataForm",autotip:true,onerror:function(){}});
		$("#username").formValidator({onshow:"请输入用户名",onfocus:"请输入用户名",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少1个字符"});
		$("#realname").formValidator({onshow:"请输入用户姓名",onfocus:"请输入用户姓名",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少1个字符"});
		showallzone(document.getElementById('zone'), 'province', 'city', 'country', '<?=$value[province]?>', '<?=$value[city]?>', '<?=$value[country]?>');
	})
//-->
</script>
<div class="pageMain">
<div class="pageTitle">当前位置：<?php echo $pagetitle;?> </div>
<div class="pageContent">
<form action="<?php echo get_uri();?>" method="post" id="dataForm">
    <input type="hidden" name="uid" value="<?=$value['uid']?>" />
    <input type="hidden" name="action" value="1" />
  <table width="100%">
	<tr>
      <td width="20%">用户名</td>
      <td width="80%"><input type="text" name="username" id="username" value="<?=$value['username']?>" <?php if(!empty($value['username'])){?>readonly<?php }?> size="50" /> (*)</td>
    </tr>
	<tr>
      <td width="20%">用户密码</td>
      <td width="80%"><input type="password" name="password" id="password" size="50" /> (*)</td>
    </tr>
	<tr>
      <td width="20%">用户姓名</td>
      <td width="80%"><input type="text" name="realname" id="realname" value="<?=$value['realname']?>" size="50" /> (*)</td>
    </tr>
    <tr>
      <td width="20%">用户类型</td>
      <td width="80%">
		<select name="usertype" id="usertype">
			<?php foreach ($ugroup_list as $k=>$v) { ?>
				<option value="<?php echo $v['ugid'] ?>" <?php if($v['ugid']==$value['usertype']) echo "selected='selected'";?>><?php echo $v['name'] ?></option>
			<?php } ?>
		</select>
	  (*)</td>
    </tr>
	<tr>
      <td width="20%">所在地区</td>
      <td width="80%">
		<div id="zone"></div>
	  </td>
    </tr>
	<tr>
      <td width="20%">地址</td>
      <td width="80%"><input type="text" name="address" id="address" value="<?=$value['address']?>" size="50" /></td>
    </tr>
	<tr>
      <td width="20%">创建时间</td>
      <td width="80%"><input type="text" name="regtime" id="regtime" value="<?php echo date("Y-m-d H:i:s",$value['regtime']);?>" size="50" /></td>
    </tr>
    <tr>
      <td width="20%">备注</td>
      <td width="80%"><textarea name="content" id="content"" cols="60" rows="6"><?=$value['content']?></textarea></td>
    </tr>
    <tr>
      <td width="100%" colspan="2" align="center"><input type="submit" value="提交" class="button" /></td>
    </tr>
   
</table>
 </form>
</div>
</div>
<?php
include admin_template("footer");
?>