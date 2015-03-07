<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
define('SYS_IN', true);
define('DS', '/');

// 定义路由路径名称
define('ROUTE', 'index.php');
define('M', 'mod');
define('C', 'c');
define('A', 'a');

// 框架路径
define('FRAME_PATH', str_replace("\\", "/", dirname(__FILE__)));

// 加载配置信息
$_G = include ROOT_PATH . DS . 'configs' . DS . 'config.php'; // 系统配置
$_CTCONFIG = include ROOT_PATH . DS . 'caches' . DS . 'customize_config.php'; // 自定义配置信息
global $_G, $_CTCONFIG;

// autoload
new AutoLoader($_G['autoload']);

// 加载公共函数
Base::load_sys_func("common");

// 设置时区
date_default_timezone_set($_CTCONFIG['time_zone']);
$_G['system']['timestamp'] = time();
define('START_TIME', mtime()); // 记录程序启动时间
                               
// 定义站点名称
define('SITE_NAME', $_CTCONFIG['site_name']);
// 定义站点url
define('SITE_URL', getsiteurl());

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

// 定义后台模板
$admin_tpl = 'templates' . DS . 'admin' . DS . $_CTCONFIG['admin_template'];
define('ADMIN_TEMPLATE_URL', SITE_URL . DS . $admin_tpl);
define('ADMIN_TEMPLATE_PATH', ROOT_PATH . DS . $admin_tpl);
// 定义前台模板
$front_tpl = 'templates' . DS . 'front' . DS . $_CTCONFIG['template'];
define('TEMPLATE_URL', SITE_URL . DS . $front_tpl);
define('TEMPLATE_PATH', ROOT_PATH . DS . $front_tpl);

// 定义站点gzip
define('GZIP', $_G['system']['gzip']);
// 来源
define('HTTP_REFERER', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');

// GPC过滤
$magic_quote = get_magic_quotes_gpc();
if (empty($magic_quote)) {
	$_GET = saddslashes($_GET);
	$_POST = saddslashes($_POST);
	$_COOKIE = saddslashes($_COOKIE);
	$_REQUEST = saddslashes($_REQUEST);
}

// 开启session
$session = Base::load_model("session_model");
$session->my_session_start();

// 定义管理员身份
define('ADMIN_USER_TYPE', 1);

// 获取用户组对应的菜单id
$usergroupdb = Base::load_model("usergroup_model");
$menuids = $usergroupdb->get_permission();
// 检查当前菜单是否被赋予该用户组
$menudb = Base::load_model("menu_model");
$menudb->check_permission($menuids);
// 获得用户组分配的主菜单
$check_modules = array('admin');
$topmenus = $menudb->get_top_menus($menuids, $check_modules);

class Base {

	public static function start_app() {
		return self::load_sys_class('application');
	}

	public static function load_sys_class($classname, $path = '', $initialize = 1) {
		return self::_load_class($classname, $path, $initialize);
	}

	public static function load_sys_func($func) {
		return self::_load_func($func);
	}

	public static function load_model($classname) {
		return self::_load_class($classname, 'models', 1);
	}

	private static function _load_class($classname, $path = '', $initialize) {
		static $classes = array();
		$path = empty($path) ? 'framework' . DS . 'class' : $path;
		$key = md5($classname);
		if (file_exists(FRAME_PATH . DS . $path . DS . $classname . '.php')) {
			include_once FRAME_PATH . DS . $path . DS . $classname . '.php';
			if ($initialize) {
				$classes[$key] = new $classname();
			} else {
				$classes[$key] = true;
			}
			return $classes[$key];
		} else {
			return false;
		}
	}

	private static function _load_func($func, $path = '') {
		$path = empty($path) ? 'framework' . DS . 'function' : $path;
		$path .= DS . $func . '.php';
		if (file_exists(FRAME_PATH . DS . $path)) {
			include FRAME_PATH . DS . $path;
		} else {
			return false;
		}
		return true;
	}
}

class AutoLoader {
	protected static $_autoloadPaths = array();

	public function __construct($autoPaths) {
		self::$_autoloadPaths = $autoPaths;
		spl_autoload_register(array($this,'autoLoad'));
	}

	public function autoLoad($class) {
		$classPath = $this->getClassPath($class);
		if (false !== $classPath) {
			return include $classPath;
		}
		return false;
	}

	public function getClassPath($class) {
		$class = strtolower($class);
		foreach (self::$_autoloadPaths as $val) {
			$classPath = ROOT_PATH . $val . DS . $class . '.php';
			if (is_readable($classPath)) {
				return $classPath;
			}
		}
		return false;
	}
}
