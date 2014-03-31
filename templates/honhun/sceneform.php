<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
$pagetitle = "添加修改景区信息";
include admin_template("header");
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$.formValidator.initConfig({formid:"dataForm",autotip:true,onerror:function(){}});
		$("#scenename").formValidator({onshow:"请输入景区名称",onfocus:"请输入景区名称",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少1个字符"});
		$("#scene_enname").formValidator({onshow:"Please enter scene english name",onfocus:"Please enter scene english name",oncorrect:"correct"}).inputValidator({min:1,onerror:"At least one character"});
		$("#scenenum").formValidator({onshow:"请输入3位数字景区编号",onfocus:"请输入3位数字景区编号",oncorrect:"输入正确"}).inputValidator({min:3,max:3,onerror:"请输入3位数字"});
		
		/*
		tinyMCE.init({
	        language:"zh-cn",
			mode:"exact",
			elements:"description",
	        plugins:"inlinepopups,preview,fullscreen,paste,media",
	            theme:"advanced",
	            theme_advanced_buttons1:"code,fullscreen,preview,removeformat,|,bold,italic,underline,strikethrough,|," +
	                "formatselect,fontsizeselect,|,forecolor,backcolor",
	            theme_advanced_buttons2:"bullist,numlist,|,outdent,indent,blockquote,|,justifyleft,justifycenter," +
	                "justifyright,justifyfull,|,link,unlink,charmap,|,pastetext,pasteword,|,undo,redo",
	            theme_advanced_buttons3 : "",
			relative_urls : false,
	        remove_script_host : false,
	        theme_advanced_toolbar_location:"top",
	        theme_advanced_toolbar_align:"left"
		});
		*/
		
		showallzone(document.getElementById('zone'), 'province', 'city', 'country', '<?=$value[province]?>', '<?=$value[city]?>', '<?=$value[country]?>');
	});
//-->
</script>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> </div>
<div class="pageContent">
<form action="<?php echo get_uri();?>" method="post" id="dataForm" enctype="multipart/form-data">
    <input type="hidden" name="sceneid" value="<?=$value['sceneid']?>" />
    <input type="hidden" name="action" value="1" />
    <input type="hidden" name="oldimage" value="<?=$value['image']?>" />
	<input type="hidden" name="old_description_cn_audio" value="<?=$value['description_cn_audio']?>" />
	<input type="hidden" name="old_description_en_audio" value="<?=$value['description_en_audio']?>" />
	<input type="hidden" name="old_note_cn_audio" value="<?=$value['note_cn_audio']?>" />
	<input type="hidden" name="old_note_en_audio" value="<?=$value['note_en_audio']?>" />
	
  <table width="100%">
	<tr>
      <td width="20%">景区名称</td>
      <td width="80%"><input type="text" name="scenename" id="scenename" value="<?=$value['scenename']?>" size="50" /> (*)</td>
    </tr>
	<tr>
      <td width="20%">Scene English Name</td>
      <td width="80%"><input type="text" name="scene_enname" id="scene_enname" value="<?=$value['scene_enname']?>" size="50" /> (*)</td>
    </tr>
	<tr>
      <td width="20%">景区编号</td>
      <td width="80%"><input type="text" name="scenenum" id="scenenum" value="<?=$value['scenenum']?>" size="20" /> (如：001) *</td>
    </tr>
    <tr>
      <td width="20%">景区类别</td>
      <td width="80%">
		<select name="typeid" id="typeid">
			<?php foreach ($scenetype_list as $k=>$v) { ?>
				<option value="<?php echo $v['typeid'] ?>" <?php if($v['typeid']==$value['typeid']) echo "selected='selected'";?>><?php echo $v['name'] ?></option>
			<?php } ?>
		</select>
	  (*)</td>
    </tr>
	<tr>
      <td width="20%">景区级别</td>
      <td width="80%">
		<input type="radio" name="level"  value="2" <?php if($value['level']==2){ ?>checked="checked"<?php } ?> size="50" />AAA以下 
		<input type="radio" name="level"  value="3" <?php if($value['level']==3){ ?>checked="checked"<?php } ?> size="50" />AAA 
		<input type="radio" name="level"  value="4" <?php if($value['level']==4){ ?>checked="checked"<?php } ?> size="50" />AAAA 
		<input type="radio" name="level"  value="5" <?php if($value['level']==5){ ?>checked="checked"<?php } ?> size="50" />AAAAA 
	  </td>
    </tr>
	<tr>
      <td width="20%">景区缩略图</td>
      <td width="80%">
              <?php if ($value['image']) {?>
    		  <a href="<?=UPLOAD_URI.'/'.$value['image']?>" target="_blank">
    		  		<img src="<?=UPLOAD_URI.'/thumb/'.$value['image']?>" width="100" height="100"  /> 
    		  </a>
    		  <?php } ?>
      <input type="file" name="image" id="image"  /></td>
    </tr>
	
	
	<tr>
      <td width="20%">游玩主题</td>
      <td width="80%">
		<?php foreach ($traveltopic_list as $k=>$v) { ?>
			<input type="radio" name="traveltopicid"  value="<?php echo $v['typeid'] ?>" <?php if($v['typeid']==$value['traveltopicid']) echo "checked='checked'";?>  /><?php echo $v['name'] ?>
		<?php } ?>		
	  </td>
    </tr>
	<tr>
      <td width="20%">所在地区</td>
      <td width="80%">
		<div id="zone"></div>
	  </td>
    </tr>
	<tr>
      <td width="20%">地址</td>
      <td width="80%">
	  <input type="text" name="address" id="address" value="<?=$value['address']?>" size="50" />
	  <input type="button" value="获取地图位置"  />
	  <div id="mapObj" class="view" style="width:600px;height:300px;display:none;"></div> 
	  </td>
    </tr>
    <tr>
      <td width="20%">景区简介</td>
      <td width="80%"><textarea name="description" id="description"" cols="100" rows="20"><?=$value['description']?></textarea></td>
    </tr>
	
	<tr>
      <td width="20%">简介中文mp3</td>
      <td width="80%">
              <?php if ($value['description_cn_audio']) {?>
    		  <a href="<?=UPLOAD_URI.'/'.$value['description_cn_audio']?>" target="_blank">
    		  		<?=$value['description_cn_audio']?>
    		  </a>
    		  <?php } ?>
      <input type="file" name="description_cn_audio" id="description_cn_audio"  /></td>
    </tr>
	
	<tr>
      <td width="20%">简介English mp3</td>
      <td width="80%">
              <?php if ($value['description_en_audio']) {?>
    		  <a href="<?=UPLOAD_URI.'/'.$value['description_en_audio']?>" target="_blank">
    		  		<?=$value['description_en_audio']?>
    		  </a>
    		  <?php } ?>
      <input type="file" name="description_en_audio" id="description_en_audio"  /></td>
    </tr>
	
	<tr>
      <td width="20%">温馨提示</td>
      <td width="80%"><textarea name="note" id="note"" cols="100" rows="20"><?=$value['note']?></textarea></td>
    </tr>
	
	<tr>
      <td width="20%">温馨提示中文mp3</td>
      <td width="80%">
              <?php if ($value['note_cn_audio']) {?>
    		  <a href="<?=UPLOAD_URI.'/'.$value['note_cn_audio']?>" target="_blank">
    		  		<?=$value['note_cn_audio']?>
    		  </a>
    		  <?php } ?>
      <input type="file" name="note_cn_audio" id="note_cn_audio"  /></td>
    </tr>
	
	<tr>
      <td width="20%">温馨提示English mp3</td>
      <td width="80%">
              <?php if ($value['note_en_audio']) {?>
    		  <a href="<?=UPLOAD_URI.'/'.$value['note_en_audio']?>" target="_blank">
    		  		<?=$value['note_en_audio']?>
    		  </a>
    		  <?php } ?>
      <input type="file" name="note_en_audio" id="note_en_audio"  /></td>
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