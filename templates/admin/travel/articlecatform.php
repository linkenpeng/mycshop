<?php
$pagetitle = "添加修改文章分类";
include trig_mvc_template::admin_template("header");
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$.formValidator.initConfig({formid:"dataForm",autotip:true,onerror:function(){}});
		$("#name").formValidator({onshow:"请输入分类名",onfocus:"请输入分类名",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少一个字符"});
	})
//-->
</script>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：<?php echo $pagetitle;?> </div>
<div class="pageContent">
  <table width="100%">
    <form action="<?php echo trig_mvc_route::get_uri();?>" method="post" id="dataForm" enctype="multipart/form-data">
    <input type="hidden" name="catid" value="<?php echo $value['catid']; ?>" />
    <input type="hidden" name="action" value="1" />
	<input type="hidden" name="oldimage" value="<?php echo $value['image']; ?>" />
	
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
      <td width="20%">分类名称</td>
      <td width="80%"><input type="text" name="name" id="name" value="<?php echo $value['name']; ?>" size="50" /> (*)</td>
    </tr>
	<tr>
      <td width="20%">信息类型</td>
      <td width="80%">
			<select id="cattype" name="cattype">
				<?php foreach($this->cattypes as $k=>$v) { ?>
				<option value="<?php echo $k;?>" <?php if($value['cattype']==$k) echo "selected" ?>><?php echo $v; ?></option>
				<?php } ?>				
			</select>
	  </td>
    </tr>
	<tr>
      <td width="20%">上级分类</td>
      <td width="80%">
			<select id="upid" name="upid">
				<?php echo $options; ?>
			</select>
	  </td>
    </tr>
	<tr>
      <td width="20%">分类缩略图</td>
      <td width="80%">
              <?php if ($value['image']) {?>
    		  <a href="<?php echo UPLOAD_URI.'/'.$value['image']; ?>" target="_blank">
    		  		<img src="<?php echo UPLOAD_URI.'/thumb/'.$value['image']; ?>" width="100" height="100"  /> 
    		  </a>
    		  <?php } ?>
      <input type="file" name="image" id="image"  /></td>
    </tr>
    <tr>
      <td width="20%">分类说明</td>
      <td width="80%"><textarea name="description" id="description"" cols="60" rows="6"><?php echo $value['description']; ?></textarea></td>
    </tr>
	<tr>
      <td width="20%">排序号</td>
      <td width="80%"><input type="text" name="ordernum" id="ordernum" value="<?php echo $value['ordernum']; ?>" size="10" /> (越大越靠前)</td>
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