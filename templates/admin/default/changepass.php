<?php
$pagetitle = "修改密码";
include trig_mvc_template::view("header");
?>
<div class="pageMain">
<div class="pageTitle">
<div class="pageTitle_left"></div>当前位置：修改密码 </div>
<div class="pageContent">
  <table width="100%">
    <form action="<?php echo trig_mvc_route::get_uri("user","save_admin_pass","admin");?>" method="post" name="articleForm" id="articleForm">
    <input type="hidden" name="uid" value="<?php echo $user_info['uid']; ?>" />
    <tr>
      <td width="20%">用户名</td>
      <td width="80%"><input type="text" name="username" id="username" readonly value="<?php echo $user_info['username']; ?>" size="50" /></td>
    </tr>
	 <tr>
      <td width="20%">输入旧密码</td>
      <td width="80%"><input type="password" name="oldpassword" id="oldpassword" size="50" /></td>
    </tr>
    <tr>
      <td width="20%">输入新密码</td>
      <td width="80%"><input type="password" name="password" id="password" size="50" /></td>
    </tr>
    <tr>
      <td width="20%">确认新密码</td>
      <td width="80%"><input type="password" name="password1" id="password1" size="50" /></td>
    </tr>
    <tr>
      <td width="100%" colspan="2" align="center"><input type="submit" value="提交" class="button" /></td>
    </tr>
    </form>
</table>
</div>
</div>
<?php
include trig_mvc_template::view("footer");
?>