<div id="content">
			<div id="content_left"><img src="<?php echo TEMPLATE_URL;?>/images/splash.jpg" width="179" height="229" /></div>
			<div id="content_right">
			<form action="<?php echo trig_mvc_route::get_uri("login","check_user_login","admin");?>" method="post" name="admin_login" onsubmit="return checkvalid(document.admin_login);">
				<input type="hidden" name="usertype" value="<?php echo ADMIN_USER_TYPE;?>" />
				<table cellpadding="2" cellspacing="0" border="0" width="100%">
				<tbody>
				<tr>
				<td colspan="2" height="30">请输登录帐号及密码：</td>
				</tr>
				<tr>
				<td align="right"><strong>用户名:</strong></td>
				<td><input type="text" name="username" class="text" id="username"/></td>
				</tr>
				<tr>
				<td align="right"><strong>密码:</strong></td>
				<td><input type="password" name="password" class="text" id="password"/></td>
				</tr>
				<tr>
				<td align="right"><strong>验证码:</strong></td>
				<td><input name="checkcode"  type="text" class="inp" id="checkcode" size="6" maxlength="8" />
				<img align="absbottom" src="<?php echo trig_mvc_route::get_uri("login","showcode","admin");?>"  onclick="this.src='<?php echo trig_mvc_route::get_uri("login","showcode","admin");?>&'+Math.random()" style="cursor: pointer;" title="看不清？点击更换验证码。"/>
				</td>
				</tr>
				<tr>
				<td align="right"><strong></strong></td>
				<td><input type="checkbox" value="2592000" id="ls_cookietime" name="cookietime"> 下次自动登录</td>
				</tr>
				<tr>
				<td></td>
				<td><input type="submit" value="登录" class="button"/></td>
				</tr>
				</tbody>
				</table>
			</form>
			</div>
	</div>