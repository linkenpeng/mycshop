<?php
$op_name = empty($value['productid']) ? '添加' : '修改';
trig_mvc_template::$title = $op_name."产品";
trig_mvc_template::$keywords = $op_name."产品";
trig_mvc_template::$description = $op_name."产品";
?>
<script type="text/javascript"> 
<!--
<?php if ($show_editor) { ?>
	$(function(){
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
	});
<?php } ?>
//-->
</script>
<form class="form-x form-auto" action="<?php echo trig_mvc_route::get_uri();?>" method="post" id="dataForm" enctype="multipart/form-data">
	<input type="hidden" name="productid" value="<?php echo isset($value['productid']) ? $value['productid'] : '';   ?>" />
    <input type="hidden" name="action" value="1" />
    <input type="hidden" name="oldimage" value="<?php echo isset($value['image']) ? $value['image'] : '';  ?>" />
  <div class="form-group">
    <div class="label"><label for="model">产品型号</label></div>
    <div class="field">
      <input type="text" class="input" id="model" name="model" size="50" value="<?php echo isset($value['model']) ? $value['model'] : ''; ?>" placeholder="产品型号" data-validate="required:请填写产品型号" />
    </div>
  </div>
  <div class="form-group">
    <div class="label"><label for="typeid">产品类别</label></div>
    <div class="field">
      <select class="input" name="typeid">
      		<?php 
      		if(!empty($producttype_list)) { foreach ($producttype_list as $k=>$v) { ?>
				<option value="<?php echo $v['typeid'] ?>" <?php if(isset($value['typeid']) && $v['typeid']==$value['typeid']) echo "selected='selected'";?>>
				<?php echo isset($v['name']) ? $v['name'] : '';  ?>
				</option>
			<?php } } ?>
      </select>
    </div>
  </div>  
  <div class="form-group">
      <div class="label"><label for="image">产品图片</label></div>
      <div class="field">
      		  <?php if (!empty($value['image'])) {?>
    		  <a href="<?php echo UPLOAD_URI.'/'.$value['image']; ?>" target="_blank">
    		  		<img src="<?php echo UPLOAD_URI.'/thumb/'.$value['image']; ?>" width="100" height="100"  /> 
    		  </a>
    		  <?php } ?>
      <a class="button input-file" href="javascript:void(0);">+ 浏览文件<input size="100" type="file" name="image" data-validate="regexp#.+.(jpg|jpeg|png|gif)$:只能上传jpg|gif|png格式文件" /></a>
      </div>
  </div>  
  <div class="form-group">
    <div class="label"><label for="mcolor">面料颜色</label></div>
    <div class="field">
      <input type="text" class="input" id="mcolor" name="mcolor" size="50" value="<?php echo isset($value['mcolor']) ? $value['mcolor'] : ''; ?>" placeholder="面料颜色" />
    </div>
  </div>
  <div class="form-group">
    <div class="label"><label for="mstyle">面料型号</label></div>
    <div class="field">
      <input type="text" class="input" id="mstyle" name="mstyle" size="50" value="<?php echo isset($value['mstyle']) ? $value['mstyle'] : ''; ?>" placeholder="面料型号" />
    </div>
  </div>
  <div class="form-group">
    <div class="label"><label for="bmethod">包装方法</label></div>
    <div class="field">
      <input type="text" class="input" id="bmethod" name="bmethod" size="50" value="<?php echo isset($value['bmethod']) ? $value['bmethod'] : ''; ?>" placeholder="包装方法" />
    </div>
  </div>
  <div class="form-group">
    <div class="label"><label for="bsize">包装尺寸</label></div>
    <div class="field">
      <input type="text" class="input" id="bsize" name="bsize" size="50" value="<?php echo isset($value['bsize']) ? $value['bsize'] : ''; ?>" placeholder="包装尺寸" />
    </div>
  </div>
  <div class="form-group">
    <div class="label"><label for="barcode">条形码</label></div>
    <div class="field">
      <input type="text" class="input" id="barcode" name="barcode" size="50" value="<?php echo isset($value['barcode']) ? $value['barcode'] : ''; ?>" placeholder="条形码" />
    </div>
  </div>
  <div class="form-group">
    <div class="label"><label for="volumn">体积</label></div>
    <div class="field">
      <input type="text" class="input" id="volumn" name="volumn" size="50" value="<?php echo isset($value['volumn']) ? $value['volumn'] : ''; ?>" placeholder="体积" />
    </div>
  </div>
  <div class="form-group">
    <div class="label"><label for="weight">重量</label></div>
    <div class="field">
      <input type="text" class="input" id="weight" name="weight" size="50" value="<?php echo isset($value['weight']) ? $value['weight'] : ''; ?>" placeholder="面料颜色" />
    </div>
  </div>
  <div class="form-group">
    <div class="label"><label for="price">单价</label></div>
    <div class="field">
      <input type="text" class="input" id="price" name="price" size="50" value="<?php echo isset($value['price']) ? $value['price'] : ''; ?>" placeholder="单价" />
    </div>
  </div>
  <div class="form-group">
    <div class="label"><label for="storage">库存</label></div>
    <div class="field">
      <input type="text" class="input" id="storage" name="storage" size="50" value="<?php echo isset($value['storage']) ? $value['storage'] : ''; ?>" placeholder="库存" />
    </div>
  </div>
  <div class="form-group">
    <div class="label"><label for="price">单价</label></div>
    <div class="field">
      <input type="text" class="input" id="price" name="price" size="50" value="<?php echo isset($value['price']) ? $value['price'] : ''; ?>" placeholder="单价" />
    </div>
  </div>  
  <div class="form-group">
    <div class="label"><label for="description">产品描述</label></div>
    <div class="field">
      <textarea class="input" rows="20" cols=60 placeholder="产品描述" id="description" name="description"><?php echo isset($value['description']) ? $value['description'] : ''; ?></textarea>
    </div>
  </div>
  <div class="form-button"><button class="button bg-main" type="submit">提交</button></div>
</form>