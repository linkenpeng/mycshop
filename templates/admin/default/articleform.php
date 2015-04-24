<?php
$pagetitle = "添加修改文章信息";
include trig_mvc_template::view_file("header");
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$.formValidator.initConfig({formid:"dataForm",autotip:true,onerror:function(){}});
		$("#title").formValidator({onshow:"请输入文章名称",onfocus:"请输入文章名称",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少1个字符"});

	})
//-->
</script>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> </div>
<div class="pageContent">
<form action="<?php echo trig_mvc_route::get_uri();?>" method="post" id="dataForm" enctype="multipart/form-data">
    <input type="hidden" name="aid" value="<?php echo $value['aid']; ?>" />
	<input type="hidden" name="action" value="1" />
    <input type="hidden" name="oldimage" value="<?php echo $value['image']; ?>" />
  <table width="100%">
	
	<tr>
      <td width="20%">所属景区</td>
      <td width="80%">
		<?php if(!empty($scene_list)) {
				foreach ($scene_list as $k=>$v) { ?>
			<input type="checkbox" name="sceneid[]" value="<?php echo $v['sceneid'] ?>" 
			<?php if (!empty($sceneids) && in_array($v['sceneid'], $sceneids)) echo "checked";?> />
			<?php echo $v['scenename'] ?>
		<?php 	}
			} ?>
	  </td>
    </tr>
	
	<tr>
      <td width="20%">文章标题</td>
      <td width="80%"><input type="text" name="title" id="title" value="<?php echo $value['title']; ?>" size="50" /> (*)</td>
    </tr>
	
	<tr>
      <td width="20%">所属分类</td>
      <td width="80%">
		<select name="catid" id="catid">
			<?php echo $category_options; ?>
		</select>
	  (*)</td>
    </tr>
	
	<tr>
      <td width="20%">文章缩略图</td>
      <td width="80%">
              <?php if ($value['image']) {?>
    		  <a href="<?php echo UPLOAD_URI.'/'.$value['image']; ?>" target="_blank">
    		  		<img src="<?php echo UPLOAD_URI.'/thumb/'.$value['image']; ?>" width="100" height="100"  /> 
    		  </a>
    		  <?php } ?>
      <input type="file" name="image" id="image"  /></td>
    </tr>
	
    <tr>
      <td width="20%">文章内容</td>
      <td width="80%"><textarea name="content" id="content"" cols="100" rows="30"><?php echo $value['content']; ?></textarea></td>
    </tr>
	<tr>
      <td width="20%">排序号</td>
      <td width="80%"><input type="text" name="ordernum" id="ordernum" value="<?php echo $value['ordernum']; ?>" size="10" /> (越大越靠前)</td>
    </tr>
    <tr>
      <td width="100%" colspan="2" align="center"><input type="submit" value="提交" class="button" /></td>
    </tr>
   
</table>
 </form>
</div>
</div>
<?php
include trig_mvc_template::view_file("footer");
?>