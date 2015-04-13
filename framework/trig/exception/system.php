<?php

class trig_exception_system extends trig_exception_driver {
	public $codeList = array(
		1000 => '系统错误',
		1001 => '方法不存在',
		1002 => '没有定义应用目录',
		1003 => '模板视图文件不存在',
		2000 => '数据库配置不存在',
		2001 => '数据库连接不正常',
	);
}
