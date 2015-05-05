<?php
trig_mvc_template::$title = "后台首页";
trig_mvc_template::$keywords = "后台首页";
trig_mvc_template::$description = "后台首页";
?>
<div class="line-big">
	<div class="xm3">
		<div class="panel border-back">
			<div class="panel-body text-center">
				<img src="<?php echo TEMPLATE_URL?>/images/face.jpg" width="120" class="radius-circle" /><br />
				admin
			</div>
			<div class="panel-foot bg-back border-back">您好，admin，这是您第100次登录，上次登录为2014-10-1。</div>
		</div>
		<br />
		<div class="panel">
			<div class="panel-head"><strong>站点统计</strong></div>
			<ul class="list-group">
				<li><span class="float-right badge bg-red">88</span><span class="icon-user"></span> 会员</li>
				<li><span class="float-right badge bg-main">828</span><span class="icon-file"></span> 文件</li>
				<li><span class="float-right badge bg-main">828</span><span class="icon-shopping-cart"></span> 订单</li>
				<li><span class="float-right badge bg-main">828</span><span class="icon-file-text"></span> 内容</li>
				<li><span class="float-right badge bg-main">828</span><span class="icon-database"></span> 数据库</li>
			</ul>
		</div>
		<br />
	</div>
	<div class="xm9">
		<div class="alert alert-yellow"><span class="close"></span><strong>注意：</strong>您有5条未读信息，<a href="#">点击查看</a>。</div>
		<div class="alert">
			<h4>拼图前端框架介绍</h4>
			<p class="text-gray padding-top">拼图是优秀的响应式前端CSS框架，国内前端框架先驱及领导者，自动适应手机、平板、电脑等设备，让前端开发像游戏般快乐、简单、灵活、便捷。</p>
			<a target="_blank" class="button bg-dot icon-code" href="pintuer2.zip"> 下载示例代码</a> 
			<a target="_blank" class="button bg-main icon-download" href="http://www.pintuer.com/pintuer.zip"> 下载拼图框架</a> 
			<a target="_blank" class="button border-main icon-file" href="http://www.pintuer.com/"> 拼图使用教程</a>
		</div>
		<div class="panel">
			<div class="panel-head"><strong>系统信息</strong></div>
			<table class="table">
				<tr><th colspan="2">服务器信息</th><th colspan="2">系统信息</th></tr>
				<tr>
				<td width="110" align="right">Web服务器/操作系统：</td><td><?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
				<td width="90" align="right">系统开发：</td><td>peng.zhenxian collin_linken@qq.com</td>
				</tr>
				<tr>
				<td align="right">脚本超时时间：</td><td><?php echo get_cfg_var("max_execution_time")?></td>
				<td align="right">文件最大上传大小：</td><td><?php echo get_cfg_var("upload_max_filesize")?></td>
				</tr>
				<tr>
				<td align="right">程序语言：</td><td>PHP</td>
				<td align="right">版本：</td><td><?php echo PHP_VERSION;?></td>
				</tr>
				<tr>
				<td align="right">数据库：</td><td>MySQL</td>
				<td align="right">版本：</td><td><?php echo $mysql_version; ?>(<?php echo $dbsize; ?>M)</td>
				</tr>
			</table>
		</div>
	</div>
</div>