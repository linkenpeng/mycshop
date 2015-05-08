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
				<?php echo isset($user_info['username']) ? $user_info['username'] : ''; ?>
			</div>
			<div class="panel-foot bg-back border-back">您好，这是您第<?php echo isset($user_info['logins']) ? $user_info['logins'] : 1; ?>次登录，上次登录为<?php echo isset($user_info['last_login']) ?date('Y-m-d H:i:s', $user_info['last_login']) : date("Y-m-d H:i:s"); ?>。</div>
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
			<h4>广东花生居电子商务有限公司 介绍</h4>
			<p class="text-gray padding-top">广东花生居电子商务有限公司是联合创企旗下一间子公司，公司因行业快速发展，现在广纳人才。联合创企建筑装饰设计有限公司成立多年来公司并具有独特、成熟、优秀的室内设计团队，公司现有室内设计人员120人，由方案设计师、3D制作、CAD制作、软装配饰设计等人员组成了方位的设计架构。我们以演绎别人的梦想为设计宗旨，以生态、文化、科技、质量相结合为设计指导思想，为了打造更优质高效的设计产品，我们创下了行业内颇具名气的“22条工作流程”设计规范。</p>
			<a target="_blank" class="button border-main icon-file" href="http://erp.fashome.cn/">访问<?php echo SITE_NAME; ?></a>
		</div>
		<div class="panel">
			<div class="panel-head"><strong>系统信息</strong></div>
			<table class="table">				
				<tr>
				<td width="140" align="right">服务器软件：</td><td><?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
				<td width="160" align="right">系统开发：</td><td>peng.zhenxian collin_linken@qq.com</td>
				</tr>
				<tr>
				<td align="right">脚本超时时间：</td><td><?php echo get_cfg_var("max_execution_time")?>秒</td>
				<td align="right">文件最大上传大小：</td><td><?php echo get_cfg_var("upload_max_filesize")?></td>
				</tr>
				<tr>
				<td align="right">数据库：</td><td>MySQL</td>
				<td align="right">数据库版本：</td><td><?php echo $mysql_version; ?></td>
				</tr>
				<tr>
				<td align="right">当前数据库大小：</td><td><?php echo $dbsize; ?>M</td>
				<td align="right"></td><td></td>
				</tr>
			</table>
		</div>
	</div>
</div>