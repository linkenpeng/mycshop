<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
$pagetitle = "添加修改景点信息";
include admin_template("header");
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$.formValidator.initConfig({formid:"dataForm",autotip:true,onerror:function(){}});
		$("#scenespotname").formValidator({onshow:"请输入景点名称",onfocus:"请输入景点名称",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少1个字符"});
		$("#infocards").formValidator({onshow:"请输入6位数字编号",onfocus:"请输入6位数字编号",oncorrect:"输入正确"}).inputValidator({min:6,max:6,onerror:"请输入6位数字编号"});		

	})
//-->
</script>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> </div>
<div class="pageContent">
<form action="<?php echo get_uri();?>" method="post" id="dataForm" enctype="multipart/form-data">
    <input type="hidden" name="scenespotid" value="<?=$value['scenespotid']?>" />
	<input type="hidden" name="sceneid" value="<?=$value['sceneid']?>" />
    <input type="hidden" name="action" value="1" />
    <input type="hidden" name="oldimage" value="<?=$value['image']?>" />
	<input type="hidden" name="old_cn_audio" value="<?=$value['cn_audio']?>" />
	<input type="hidden" name="old_en_audio" value="<?=$value['en_audio']?>" />
  <table width="100%">
	
	<tr>
      <td width="20%">所属景区</td>
      <td width="80%">
		<h3><?=$value['scenename']?></h3>
	  </td>
    </tr>
	
	<tr>
      <td width="20%">景点名称</td>
      <td width="80%"><input type="text" name="scenespotname" id="scenespotname" value="<?=$value['scenespotname']?>" size="50" /> (*)</td>
    </tr>
	
	<tr>
      <td width="20%">Scene Spot English Name</td>
      <td width="80%"><input type="text" name="scenespot_enname" id="scenespot_enname" value="<?=$value['scenespot_enname']?>" size="50" /></td>
    </tr>
	
	<tr>
      <td width="20%">所属景点</td>
      <td width="80%">
		<select name="parent_scenespotid" id="parent_scenespotid">
			<option value="">选择所属景点</option>
			<?php foreach ($parent_scenespot_list as $k=>$v) { ?>
				<option value="<?php echo $v['scenespotid'] ?>" <?php if($v['scenespotid']==$value['parent_scenespotid']) echo "selected='selected'";?>><?php echo $v['scenespotname'] ?></option>
			<?php } ?>
		</select>
	  (*)</td>
    </tr>
	
	<tr>
      <td width="20%">景点编号</td>
      <td width="80%"><input type="text" name="infocards" id="infocards" value="<?=$value['infocards']?>" size="30" /></td>
    </tr>
    

	<tr>
      <td width="20%">景点缩略图</td>
      <td width="80%">
              <?php if ($value['image']) {?>
    		  <a href="<?=UPLOAD_URI.'/'.$value['image']?>" target="_blank">
    		  		<img src="<?=UPLOAD_URI.'/thumb/'.$value['image']?>" width="100" height="100"  /> 
    		  </a>
    		  <?php } ?>
      <input type="file" name="image" id="image"  /></td>
    </tr>
	
    <tr>
      <td width="20%">景点描述</td>
      <td width="80%"><textarea name="description" id="description"" cols="100" rows="30"><?=$value['description']?></textarea></td>
    </tr>
	
	<tr>
      <td width="20%">中文播报mp3</td>
      <td width="80%">
              <?php if ($value['cn_audio']) {?>
    		  <a href="<?=UPLOAD_URI.'/'.$value['cn_audio']?>" target="_blank">
    		  		<?=$value['cn_audio']?>
    		  </a>
    		  <?php } ?>
      <input type="file" name="cn_audio" id="cn_audio"  /></td>
    </tr>
	
	<tr>
      <td width="20%">English Play mp3</td>
      <td width="80%">
              <?php if ($value['en_audio']) {?>
    		  <a href="<?=UPLOAD_URI.'/'.$value['en_audio']?>" target="_blank">
    		  		<?=$value['en_audio']?>
    		  </a>
    		  <?php } ?>
      <input type="file" name="en_audio" id="en_audio"  /></td>
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