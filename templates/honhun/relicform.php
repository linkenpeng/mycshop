<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
$pagetitle = "添加修改文物信息";
include admin_template("header");
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$.formValidator.initConfig({formid:"dataForm",autotip:true,onerror:function(){}});
		$("#relicname").formValidator({onshow:"请输入文物名称",onfocus:"请输入文物名称",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少1个字符"});
		$("#relicnum").formValidator({onshow:"请输入9位数字编号",onfocus:"请输入9位数字编号",oncorrect:"输入正确"}).inputValidator({min:9,max:9,onerror:"请输入9位数字编号"}).RegexValidator({regexp:"num",datatype:"enum",onerror:"编号格式不正确"});

	})
//-->
</script>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> </div>
<div class="pageContent">
<form action="<?php echo get_uri();?>" method="post" id="dataForm" enctype="multipart/form-data">
    <input type="hidden" name="relicid" value="<?=$value['relicid']?>" />
    <input type="hidden" name="action" value="1" />
    <input type="hidden" name="oldimage" value="<?=$value['image']?>" />
	<input type="hidden" name="old_cn_audio" value="<?=$value['cn_audio']?>" />
	<input type="hidden" name="old_en_audio" value="<?=$value['en_audio']?>" />
  <table width="100%">
	
	<tr>
      <td width="20%">所属景点</td>
      <td width="80%">
		<select name="scenespotid" id="scenespotid">
			<?php if(!empty($scenespot_list)) {
					foreach ($scenespot_list as $k=>$v) { ?>
				<option value="<?php echo $v['scenespotid'] ?>" <?php if($v['scenespotid']==$value['scenespotid']) echo "selected='selected'";?>><?php echo $v['scenespotname'] ?></option>
			<?php 	}
				} else { ?>
				<option value="<?=$value['scenespotid']?>" selected="selected"><?=$value['scenespotname']?></option>
			<?php } ?>	
		</select>
	  </td>
    </tr>
	
	<tr>
      <td width="20%">文物名称</td>
      <td width="80%"><input type="text" name="relicname" id="relicname" value="<?=$value['relicname']?>" size="50" /> (*)</td>
    </tr>
	<tr>
      <td width="20%">Relic English Name</td>
      <td width="80%"><input type="text" name="relic_enname" id="relic_enname" value="<?=$value['relic_enname']?>" size="50" /> (*)</td>
    </tr>
	<tr>
      <td width="20%">文物编号</td>
      <td width="80%"><input type="text" name="relicnum" id="relicnum" value="<?=$value['relicnum']?>" size="50" /></td>
    </tr>
    <tr>
      <td width="20%">文物级别</td>
      <td width="80%">
			<select name="level" id="level">
				<option value="1" <?php if($value['level']==1){ ?>selected="selected"<?php } ?>>一级</option>
				<option value="2" <?php if($value['level']==2){ ?>selected="selected"<?php } ?>>二级</option>
				<option value="3" <?php if($value['level']==3){ ?>selected="selected"<?php } ?>>三级</option>
			</select>
	  </td>
    </tr>

	<tr>
      <td width="20%">文物缩略图</td>
      <td width="80%">
              <?php if ($value['image']) {?>
    		  <a href="<?=UPLOAD_URI.'/'.$value['image']?>" target="_blank">
    		  		<img src="<?=UPLOAD_URI.'/thumb/'.$value['image']?>" width="100" height="100"  /> 
    		  </a>
    		  <?php } ?>
      <input type="file" name="image" id="image"  /></td>
    </tr>
	
    <tr>
      <td width="20%">文物描述</td>
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