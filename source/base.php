<?php
define('SYS_IN', true);
define('DS', '/');

// 定义路由路径名称
define('ROUTE', 'index.php');
define('M', 'mod');
define('C', 'c');
define('A', 'a');

// 应用路径
define('SOURCE_PATH', str_replace("\\", "/", dirname(__FILE__)));

// 加载配置信息
$_G = include ROOT_PATH . DS . 'configs' . DS . 'config.php'; // 系统配置
$_CTCONFIG = include ROOT_PATH . DS . 'caches' . DS . 'customize_config.php'; // 自定义配置信息

// 日志存放路径
$_G['log']['path'] = ROOT_PATH . DS . 'caches'. DS .'logs';

// 设置时区
date_default_timezone_set($_CTCONFIG['time_zone']);
$_G['system']['timestamp'] = time();
define('START_TIME', trig_func_common::mtime()); // 记录程序启动时间
                               
// 定义站点名称
define('SITE_NAME', $_CTCONFIG['site_name']);
// 定义站点url
define('SITE_URL', trig_uri::getsiteurl());

// 定义默认省份
define('DEFAULT_PROVINCE', $_CTCONFIG['province']);

// 附件路径
define('UPLOAD_PATH', ROOT_PATH . DS . 'uploadfiles');
define('UPLOAD_URI', SITE_URL . DS . 'uploadfiles');
// 允许上传的文件类型
define('UPLOAD_IMAGE_FILE_TYPES', $_CTCONFIG['upload_image_file_types']);
define('UPLOAD_AUDIO_FILE_TYPES', $_CTCONFIG['upload_audio_file_types']);
define('UPLOAD_VIDEO_FILE_TYPES', $_CTCONFIG['upload_video_file_types']);

// 定义地图key
define('MAPABC_KEY', $_CTCONFIG['mapabc_key']);
// 定义站点字符集
define('CHARSET', $_G['system']['charset']);
// 输出页面字符集
header('Content-type: text/html; charset=' . CHARSET);
// 定义站点gzip
define('GZIP', $_G['system']['gzip']);
// 来源
define('HTTP_REFERER', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');

// GPC过滤
$magic_quote = get_magic_quotes_gpc();
if (empty($magic_quote)) {
	$_GET = trig_func_common::saddslashes($_GET);
	$_POST = trig_func_common::saddslashes($_POST);
	$_COOKIE = trig_func_common::saddslashes($_COOKIE);
	$_REQUEST = trig_func_common::saddslashes($_REQUEST);
}

// 开启session
$session = new model_session();
$session->my_session_start();

// 定义管理员身份
define('ADMIN_USER_TYPE', 1);

// 获取用户组对应的菜单id
$usergroupdb = new model_usergroup();
$menuids = $usergroupdb->get_permission();

// 检查当前菜单是否被赋予该用户组
$menudb = new model_menu();
$menudb->check_permission($menuids);

// 获得用户组分配的主菜单
$check_modules = array('admin');
$topmenus = $menudb->get_top_menus($menuids, $check_modules);