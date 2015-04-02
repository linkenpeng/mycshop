<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
$pagetitle = "批量生成激活码";
include trig_func_common::admin_template("header");
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$.formValidator.initConfig({formid:"dataForm",autotip:true,onerror:function(){}});
		$("#batchname").formValidator({onshow:"请输入批号名称",onfocus:"请输入批号名称",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少1个字符"});
		$("#batchnum").formValidator({onshow:"请输入批号",onfocus:"请输入批号",oncorrect:"输入正确"}).inputValidator({min:3,max:3,onerror:"3位数字"});
		$("#number").formValidator({onshow:"请输入需要生成的数量",onfocus:"请输入需要生成的数量",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少1个字符"});		
	})
//-->
</script>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> </div>
<div class="pageContent">
<form action="<?php echo trig_func_common::get_uri();?>" method="post" id="dataForm" enctype="multipart/form-data">
    <input type="hidden" name="aid" value="<?php echo $value['aid']; ?>" />
	<input type="hidden" name="action" value="1" />
    <input type="hidden" name="oldimage" value="<?php echo $value['image']; ?>" />
  <table width="100%">
	
	<tr>
      <td width="20%">所属景区</td>
      <td width="80%">
		<select id="sceneid" name="sceneid">
		<?php if(!empty($scene_list)) {
				foreach ($scene_list as $k=>$v) { ?>
			<option value="<?php echo $v['sceneid'] ?>" 
			<?php if (!empty($sceneids) && in_array($v['sceneid'], $sceneids)) echo "checked";?> >
			<?php echo $v['scenename'] ?>
			</option>
		<?php 	}
			} ?>
		</select>
	  </td>
    </tr>
	
	<tr>
      <td width="20%">批号名称</td>
      <td width="80%"><input type="text" name="batchname" id="batchname" value="<?php echo $value['batchname']; ?>" size="30" /> (*)</td>
    </tr>
	
	<tr>
      <td width="20%">批号</td>
      <td width="80%"><input type="text" name="batchnum" id="batchnum" value="<?php echo $value['batchnum']; ?>" size="30" /> 3位数字，如：001(*)</td>
    </tr>
	
	<tr>
      <td width="20%">生成数量</td>
      <td width="80%"><input type="text" name="number" id="number" value="<?php echo $value['number']; ?>" size="10" /> (*)</td>
    </tr>
	
	<tr>
      <td width="20%">激活码长度</td>
      <td width="80%"><input type="text" name="length" id="length" value="6" size="3" /></td>
    </tr>

    <tr>
      <td width="100%" colspan="2" align="center"><input type="submit" value="提交" class="button" /></td>
    </tr>
   
</table>
 </form>
</div>
</div>
<?php
include trig_func_common::admin_template("footer");
?>