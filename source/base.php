<?php
error_reporting(E_ALL ^ E_NOTICE);
define('SYS_IN',true);
define('DS','/');

//定义路由路径名称
define('ROUTE','index.php');
define('M','mod');
define('C','c');
define('A','a');

//框架路径
define('FRAME_PATH',str_replace("\\","/",dirname(__FILE__)));

//autoload
$autoLoadPaths = array(
	'model' 	 => array('path' => ROOT_PATH.'/source/models/', 'extension' => '.class.php'),
	'controller' => array('path' => ROOT_PATH.'/source/applications/admin/', 'extension' => '.php'),
	'library' 	 => array('path' => ROOT_PATH.'/source/utils/class/', 'extension' => '.class.php'),
);
new AutoLoader($autoLoadPaths);

//加载配置信息
$_G = Base::load_config("sys"); //系统配置
$_CTCONFIG = Base::load_config("customize"); //自定义配置信息

//加载公共函数
Base::load_sys_func("global");

//设置时区
date_default_timezone_set($_CTCONFIG['timezone']);
$_G['timestamp'] = time();
define('START_TIME',mtime()); //记录程序启动时间

//定义站点名称
define('SITE_NAME',$_CTCONFIG['site_name']);
//定义站点url
define('SITE_URL',getsiteurl());

//定义默认省份
define('DEFAULT_PROVINCE',$_CTCONFIG['province']);

//附件路径
define('UPLOAD_PATH',ROOT_PATH.'/uploadfiles');
define('UPLOAD_URI',SITE_URL.'/uploadfiles');
//允许上传的文件类型
define('UPLOAD_IMAGE_FILE_TYPES',$_CTCONFIG['upload_image_file_types']);
define('UPLOAD_AUDIO_FILE_TYPES',$_CTCONFIG['upload_audio_file_types']);
define('UPLOAD_VIDEO_FILE_TYPES',$_CTCONFIG['upload_video_file_types']);

//定义地图key
define('MAPKEY',$_CTCONFIG['mapkey']);
//定义站点字符集
define('CHARSET',$_G['charset']);
//输出页面字符集
header('Content-type: text/html; charset='.CHARSET);

//定义后台模板url
define('ADMIN_TEMPLATE_URL',SITE_URL.'/templates/'.$_CTCONFIG['admin_template']);
//定义前台模板url
define('TEMPLATE_URL',SITE_URL.'/templates/'.$_CTCONFIG['template']);

//定义站点gzip
define('GZIP',$_G['gzip']);
//来源
define('HTTP_REFERER',isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');

//GPC过滤
$magic_quote = get_magic_quotes_gpc();
if (empty($magic_quote)) {
    $_GET = saddslashes($_GET);
    $_POST = saddslashes($_POST);
    $_COOKIE = saddslashes($_COOKIE);
    $_REQUEST = saddslashes($_REQUEST);
}

//连接数据库
Base::load_sys_class("Mysql",'',0);
$db = Mysql::getInstance();

//开启session
$session = Base::load_model("session_model");
$session->my_session_start();

//定义管理员身份
define('ADMIN_USER_TYPE',1);

//获取用户组对应的菜单id
$usergroupdb = Base::load_model("usergroup_model");
$menuids = $usergroupdb->get_permission();
//检查当前菜单是否被赋予该用户组
$menudb = Base::load_model("menu_model");
$menudb->check_permission($menuids);
//获得用户组分配的主菜单
$check_modules = array('admin'); // 需要登录的模块
$topmenus = $menudb->get_top_menus($menuids, $check_modules);

/**
 * 
 * 基础类，用来载入一些公共函数，类，配置参数
 */
class Base {
    /**
     * 初始化应用程序
     */
    public static function start_app() {
        return self::load_sys_class('application');
    }
    /**
     * 加载数据模型
     * @param string $classname 类名
     */
    public static function load_model($classname) {
        return self::_load_class($classname,'models',1);
    }
    /**
     * 加载系统类方法
     * @param string $classname 类名
     * @param string $path 扩展地址
     * @param intger $initialize 是否初始化
     */
    public static function load_sys_class($classname, $path = '', $initialize = 1) {
        return self::_load_class($classname,$path,$initialize);
    }
    /**
     * 加载类文件函数
     * @param string $classname 类名
     * @param string $path 扩展地址,并判断加载的文件的前缀，
     * 1、$path为models时，则加载 models/中的类
     * 2、$path 为空时，则加载utils/class中的系统类
     * @param intger $initialize 是否初始化
     */
    private static function _load_class($classname, $path = '', $initialize) {
        static $classes = array();
        if (empty($path))
            $path = 'utils'.DS.'class';
        $key = md5($classname);
        if (file_exists(FRAME_PATH.DS.$path.DS.$classname.'.class.php')) {
            include_once FRAME_PATH.DS.$path.DS.$classname.'.class.php';
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
    /**
     * 加载系统的函数库
     * @param string $func 函数库名
     */
    public static function load_sys_func($func) {
        return self::_load_func($func);
    }
    /**
     * 加载函数库
     * @param string $func 函数库名
     * @param string $path 地址
     */
    private static function _load_func($func, $path = '') {
        if (empty($path))
            $path = 'utils'.DS.'function';
        $path .= DS.$func.'.func.php';
        if (file_exists(FRAME_PATH.DS.$path)) {
            include FRAME_PATH.DS.$path;
        } else {
            return false;
        }
        return true;
    }
    /**
     * 加载配置文件
     * @param string $file 文件名
     */
    public static function load_config($file, $key = '') {
        static $configs = array();
        $path = ROOT_PATH.DS.'configs'.DS.'config.'.$file.'.php';       
        if (file_exists($path)) {
            $configs[$file] = include $path;
        }
        if (empty($key)) {
            return $configs[$file];
        } elseif (isset($configs[$file][$key])) {
            return $configs[$file][$key];
        } else {
            return '';
        }
    }
}

class AutoLoader
{
	protected static $_autoloadPaths = array();
	
	public function __construct($autoPaths)
	{
		self::$_autoloadPaths = $autoPaths;
		spl_autoload_register(array($this, 'autoLoad'));
	}
	
	public function autoLoad($class) {
		$classPath = $this->getClassPath($class);
		if (false !== $classPath) {
			return include $classPath;
		}
		return false;
	}
	
	public function getClassPath($class) {
		foreach(self::$_autoloadPaths as $val) {
			$classPath = $val['path'].$class.$val['extension'];
			if(file_exists($classPath)) return $classPath;
		}
		return false;
	}	
	
}
