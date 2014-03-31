<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
$pagetitle = "添加修改产品信息";
include admin_template("header");
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$.formValidator.initConfig({formid:"dataForm",autotip:true,onerror:function(){}});
		$("#model").formValidator({onshow:"请输入产品型号",onfocus:"请输入产品型号",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少1个字符"});

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
	})
//-->
</script>
<div class="pageMain">
<div class="pageTitle">当前位置：<?php echo $pagetitle;?> </div>
<div class="pageContent">
<form action="<?php echo get_uri();?>" method="post" id="dataForm" enctype="multipart/form-data">
    <input type="hidden" name="productid" value="<?=$value['productid']?>" />
    <input type="hidden" name="action" value="1" />
    <input type="hidden" name="oldimage" value="<?=$value['image']?>" />
  <table width="100%">
	<tr>
      <td width="20%">产品型号</td>
      <td width="80%"><input type="text" name="model" id="model" value="<?=$value['model']?>" size="50" /> (*)</td>
    </tr>
    <tr>
      <td width="20%">产品类别</td>
      <td width="80%">
		<select name="typeid" id="typeid">
			<?php foreach ($producttype_list as $k=>$v) { ?>
				<option value="<?php echo $v['typeid'] ?>" <?php if($v['typeid']==$value['typeid']) echo "selected='selected'";?>><?php echo $v['name'] ?></option>
			<?php } ?>
		</select>
	  (*)</td>
    </tr>
	<tr>
      <td width="20%">产品缩略图</td>
      <td width="80%">
              <?php if ($value['image']) {?>
    		  <a href="<?=UPLOAD_URI.'/'.$value['image']?>" target="_blank">
    		  		<img src="<?=UPLOAD_URI.'/thumb/'.$value['image']?>" width="100" height="100"  /> 
    		  </a>
    		  <?php } ?>
      <input type="file" name="image" id="image"  /></td>
    </tr>
	<tr>
      <td width="20%">面料颜色</td>
      <td width="80%"><input type="text" name="mcolor" id="mcolor" value="<?=$value['mcolor']?>" size="50" /></td>
    </tr>
    
	<tr>
      <td width="20%">面料型号</td>
      <td width="80%"><input type="text" name="mstyle" id="mstyle" value="<?=$value['mstyle']?>" size="50" /></td>
    </tr>
	<tr>
      <td width="20%">包装方法</td>
      <td width="80%"><input type="text" name="bmethod" id="bmethod" value="<?php echo $value['bmethod'];?>" size="50" /></td>
    </tr>
    <tr>
      <td width="20%">包装尺寸</td>
      <td width="80%"><input type="text" name="bsize" id="bsize" value="<?php echo $value['bsize'];?>" size="50" /></td>
    </tr>
	<tr>
      <td width="20%">条形码</td>
      <td width="80%"><input type="text" name="barcode" id="barcode" value="<?php echo $value['barcode'];?>" size="15" /></td>
    </tr>
    <tr>
      <td width="20%">体积</td>
      <td width="80%"><input type="text" name="volumn" id="volumn" value="<?php echo $value['volumn'];?>" size="15" /></td>
    </tr>
    <tr>
      <td width="20%">重量</td>
      <td width="80%"><input type="text" name="weight" id="weight" value="<?php echo $value['weight'];?>" size="15" /></td>
    </tr>
    <tr>
      <td width="20%">单价</td>
      <td width="80%"><input type="text" name="price" id="price" value="<?php echo $value['price'];?>" size="15" /></td>
    </tr>
    <tr>
      <td width="20%">库存</td>
      <td width="80%"><input type="text" name="storage" id="storage" value="<?php echo $value['storage'];?>" size="15" /></td>
    </tr>
    <tr>
      <td width="20%">产品描述</td>
      <td width="80%"><textarea name="description" id="description"" cols="60" rows="10"><?=$value['description']?></textarea></td>
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