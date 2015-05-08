<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
trig_mvc_template::$title = "添加修改用户分类";
?>
<script type="text/javascript"> 
<!--
	$(function(){
		$.formValidator.initConfig({formid:"dataForm",autotip:true,onerror:function(){}});
		$("#name").formValidator({onshow:"请输入分类名",onfocus:"请输入分类名",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"至少一个字符"});
	})
//-->
</script>
<form id="dataForm" method="post" class="form-x" enctype="multipart/form-data" action="<?php echo trig_mvc_route::get_uri();?>">
    <input type="hidden" name="ugid" value="<?php echo isset($value['ugid'])?$value['ugid']:''; ?>" />
    <input type="hidden" name="action" value="1" />
    <div class="doc-demoview doc-viewad0">
        <div class="view-body">
            <div class="form-group">
                <div class="label"><label for="name">用户分类名称</label></div>
                <div class="field">
                    <input type="text" size="30" name="name" id="name" class="input" placeholder="请输入用户分类名称" data-validate="required:请输入用户分类名称" value="<?php echo isset($value['name'])?$value['name']:''; ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="label"><label for="description">备注</label></div>
                <div class="field">
                    <textarea name="description" id="description"" cols="60" rows="6"><?php echo isset($value['description'])?$value['description']:''; ?></textarea>
                </div>
            </div>
            <div class="form-button"><button type="submit" class="button bg-main">提交</button></div>
        </div>
    </div>
</form>