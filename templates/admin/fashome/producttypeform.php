<?php
$op_name = empty($value['productid']) ? '添加' : '修改';
trig_mvc_template::$title = $op_name."产品分类";
trig_mvc_template::$keywords = $op_name."产品分类";
trig_mvc_template::$description = $op_name."产品分类";
?>
<form class="form-x form-auto" action="<?php echo trig_mvc_route::get_uri();?>" method="post" id="dataForm" enctype="multipart/form-data">
	<input type="hidden" name="typeid" value="<?php echo isset($value['typeid']) ? $value['typeid'] : '';  ?>" />
    <input type="hidden" name="action" value="1" />
	<input type="hidden" name="oldimage" value="<?php echo isset($value['image']) ? $value['image'] : '';  ?>" />
  <div class="form-group">
    <div class="label"><label for="name">分类名称</label></div>
    <div class="field">
      <input type="text" class="input" id="name" name="name" size="50" value="<?php echo isset($value['name']) ? $value['name'] : ''; ?>" placeholder="分类名称" data-validate="required:请填写分类名称" />
    </div>
  </div>
  <div class="form-group">
      <div class="label"><label for="image">分类图</label></div>
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
    <div class="label"><label for="description">分类说明</label></div>
    <div class="field">
      <textarea class="input" rows="20" cols=60 placeholder="产品描述" id="description" name="description"><?php echo isset($value['description']) ? $value['description'] : ''; ?></textarea>
    </div>
  </div>
  <div class="form-button"><button class="button bg-main" type="submit">提交</button></div>
</form>