<div class="xs6 xm4 xs3-move xm4-move">
	<br /><br />
	<form action="<?php echo trig_mvc_route::get_uri("login","check_user_login","admin");?>" method="post">
	<div class="panel">
		<div class="panel-head"><strong>登录拼图后台管理系统</strong></div>
		<div class="panel-body" style="padding:30px;">
			<div class="form-group">
				<div class="field field-icon-right">
					<input type="text" class="input" name="username" placeholder="登录账号" data-validate="required:请填写账号,length#>=5:账号长度不符合要求" />
					<span class="icon icon-user"></span>
				</div>
			</div>
			<div class="form-group">
				<div class="field field-icon-right">
					<input type="password" class="input" name="password" placeholder="登录密码" data-validate="required:请填写密码,length#>=5:密码长度不符合要求" />
					<span class="icon icon-key"></span>
				</div>
			</div>
			<div class="form-group">
				<div class="field">
					<input type="text" class="input" name="checkcode" placeholder="填写右侧的验证码" data-validate="required:请填写右侧的验证码" />
					<img src="<?php echo trig_mvc_route::get_uri("login","showcode","admin");?>"  onclick="this.src='<?php echo trig_mvc_route::get_uri("login","showcode","admin");?>&'+Math.random()" style="cursor: pointer;" width="80" height="32" class="passcode" />
				</div>
			</div>
		</div>
		<div class="panel-foot text-center"><button class="button button-block bg-main text-big">立即登录后台</button></div>
	</div>
	</form>
</div>