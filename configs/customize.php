<?php
if (!defined('SYS_IN')) {
	exit('Access Denied');
}
return array(
	'site_name' => '网店管理系统',
	'timezone' => 'Asia/Shanghai',
	'timeoffset' => '8',
	'lang' => 'zh-cn', // 网站语言包
	'province' => '广东省', // 默认省份
	'city' => '广州市', // 默认城市
	'template' => 'default', // 默认模板
	'admin_template' => 'honhun', // 默认后台模板
	'onlinetime' => '1800', // 设置session活动时间
	'upload_image_file_types' => 'jpg,gif,bmp,png', // 允许上传的图片文件类型
	'upload_audio_file_types' => 'mp3', // 允许上传的音频文件类型
	'upload_video_file_types' => 'mp4,flv', // 允许上传的视频文件类型
	'mapkey' => '6bc0cc42fafe92c3d883c4d2a285ba2f77604755236e1043c93ad09f86833c7f2e921b6ea698c447'  // 高德地图密匙
);
?>