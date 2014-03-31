<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
$pagetitle = "添加修改订单";
include admin_template("header");
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$.formValidator.initConfig({formid:"dataForm",autotip:true,onerror:function(){}});
		$("#ordername").formValidator({onshow:"请输入订单名",onfocus:"请输入订单名",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少一个字符"});
	})
//-->
</script>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> </div>
<div class="pageContent">
  <table width="100%">
    <form action="<?php echo get_uri();?>" method="post" id="dataForm">
    <input type="hidden" name="orderid" value="<?=$value['orderid']?>" />
    <input type="hidden" name="action" value="1" />
    <tr>
      <td width="20%">订单名</td>
      <td width="80%"><input type="text" name="ordername" id="ordername" value="<?=$value['ordername']?>" size="50" /> (*)</td>
    </tr>
    <tr>
      <td width="20%">备注</td>
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