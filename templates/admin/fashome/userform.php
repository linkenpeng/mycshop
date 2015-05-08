<?php
if(!defined('SYS_IN')) {
	exit('Access Denied');
}
trig_mvc_template::$title = "添加修改用户";
?>
<script type="text/javascript">
	$(function(){
		showallzone(document.getElementById('zone'), 'province', 'city', 'country', '<?php echo isset($value['province'])?$value['province']:''; ?>', '<?php echo isset($value['city'])?$value['city']:''; ?>', '<?php echo isset($value['country'])?$value['country']:''; ?>');
	})
</script>
<form id="dataForm" method="post" class="form-x" enctype="multipart/form-data" action="<?php echo trig_mvc_route::get_uri();?>">
    <input type="hidden" name="uid" value="<?php echo e($value['uid']); ?>" />
    <input type="hidden" name="action" value="1" />
    <div class="doc-demoview doc-viewad0">
        <div class="view-body">
            <div class="form-group">
                <div class="label"><label for="username">用户名</label></div>
                <div class="field">
                    <input type="text" size="30" name="username" id="username" class="input" placeholder="请输入用户名" data-validate="required:请输入用户名" value="<?php echo isset($value['username'])?$value['username']:''; ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="label"><label for="password">用户密码</label></div>
                <div class="field">
                    <input type="password" size="30" name="password" id="password" class="input">
                </div>
            </div>
            <?php if(!isset($value['uid'])){ ?>
            <div class="form-group">
                <div class="label"><label for="password2">确认密码</label></div>
                <div class="field">
                    <input type="password" size="30" name="password2" id="password2" class="input">
                </div>
            </div>
            <?php }?>
            <div class="form-group">
                <div class="label"><label for="realname">用户姓名</label></div>
                <div class="field">
                    <input type="text" size="30" name="realname" id="realname" class="input" placeholder="请输入用户姓名" data-validate="required:请输入用户姓名" value="<?php echo isset($value['realname'])?$value['realname']:''; ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="label"><label for="usertype">用户类型</label></div>
                <div class="field">
                    <select name="usertype" class="input" id="usertype">
                        <?php foreach ($ugroup_list as $k=>$v) { ?>
                            <option value="<?php echo $v['ugid'] ?>" <?php if(isset($value['usertype'])&&$v['ugid']==$value['usertype']) echo "selected='selected'";?>><?php echo $v['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="label"><label for="zone">所在地区</label></div>
                <div class="field">
                    <div id="zone"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label"><label for="address">地址</label></div>
                <div class="field">
                    <input type="text" size="30" name="address" id="address" class="input" value="<?php echo isset($value['address'])?$value['address']:''; ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="label"><label for="regtime">创建时间</label></div>
                <div class="field">
                    <input type="text" size="30" name="regtime" id="regtime" class="input" value="<?php echo isset($value['regtime'])?date("Y-m-d H:i:s",$value['regtime']):'';?>">
                </div>
            </div>
            <div class="form-group">
                <div class="label"><label for="content">备注</label></div>
                <div class="field">
                    <textarea name="content" id="content"" cols="60" rows="6"><?php echo isset($value['content'])?$value['content']:''; ?></textarea>
                </div>
            </div>
            <div class="form-button"><button type="submit" class="button bg-main">提交</button></div>
        </div>
    </div>
</form>