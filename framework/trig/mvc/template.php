<?php

/**
 * template.class.php 模板解析类
 *
 */
class trig_mvc_template {
	private $tpls = array();

	public function __construct() {
	}
	
	public static function view_file($view_name) {
		$view_file = TEMPLATE_PATH . DS . $view_name . '.php';
		return $view_file;
	}
	
	public static function render($view_file, $_data_ = null, $return = false)
	{
		if(is_array($_data_) && !empty($_data_)) {
			extract($_data_,EXTR_PREFIX_SAME,'data');
		} else {
			$data = $_data_;
		}
		
		if($return) {
			ob_start();
			ob_implicit_flush(false);
			require($view_file);
			return ob_get_clean();
		} else {
			require($view_file);
		}
	}
	
	/*
	 * 模板调用 $name 模板名称 $template 模板目录
	*/
	public static function template($name, $templatedir = '') {
		global $_CTCONFIG;
		if (empty($templatedir)) {
			$templatedir = $_CTCONFIG['template'];
		}
		$tpl = "templates/$templatedir/$name";
		$objdir = ROOT_PATH . DS . 'caches/tpl_cache/' . $templatedir;
		if (!is_dir($objdir)) {
			mkdir($objdir);
			chmod($objdir, 777);
		}
		$objfile = $objdir . DS . str_replace('/', '_', $tpl) . '.php';
		if (!file_exists($objfile) || filemtime($objfile) < filemtime(ROOT_PATH . DS . './' . $tpl . '.htm')) {
			self::parse_template($tpl, $templatedir);
		}
		return $objfile;
	}
	
	// 子模板更新检查
	public static function subtplcheck($subfiles, $mktime, $tpl, $templatedir) {
		$subfiles = explode('|', $subfiles);
		foreach ($subfiles as $subfile) {
			$tplfile = ROOT_PATH . DS . $subfile . '.htm';
			if (!file_exists($tplfile)) {
				$tplfile = str_replace('/' . $this->tpls['template'] . '/', '/default/', $tplfile);
			}
			$submktime = filemtime($tplfile);
			if ($submktime > $mktime) {
				self::parse_template($tpl, $templatedir);
				break;
			}
		}
	}
	
	function parse_template($tpl, $templatedir = "") {
		global $_G;
		// 包含模板
		$this->tpls['sub_tpls'] = array(
			$tpl 
		);
		if (!empty($templatedir)) {
			$this->tpls['template'] = $templatedir;
		} else {
			$this->tpls['template'] = $_G['system']['template'];
		}
		$tplfile = ROOT_PATH . DS . $tpl . '.htm';
		$objfile = ROOT_PATH . DS . 'caches' . DS . 'tpl_cache' . DS . $this->tpls['template'] . DS . str_replace('/', '_', $tpl) . '.php';
		// read
		if (!file_exists($tplfile)) {
			$tplfile = str_replace('/' . $this->tpls['template'] . '/', '/default/', $tplfile);
		}
		$template = trig_func_common::sreadfile($tplfile);
		if (empty($template)) {
			exit("Template file : $tplfile Not found or have no access!");
		}
		
		// 模板
		$template = preg_replace("/\<\!\-\-\{template\s+([a-z0-9_\/]+)\}\-\-\>/ie", "\$this->readtemplate('\\1')", $template);
		// 处理子页面中的代码
		$template = preg_replace("/\<\!\-\-\{template\s+([a-z0-9_\/]+)\}\-\-\>/ie", "\$this->readtemplate('\\1')", $template);
		// 解析模块调用
		$template = preg_replace("/\<\!\-\-\{block\/(.+?)\}\-\-\>/ie", "\$this->blocktags('\\1')", $template);
		// 时间处理
		$template = preg_replace("/\<\!\-\-\{date\((.+?)\)\}\-\-\>/ie", "\$this->datetags('\\1')", $template);
		// 字符串截取
		$template = preg_replace("/\<\!\-\-\{cnsubstr\((.+?)\)\}\-\-\>/ie", "\$this->cnsubstrtags('\\1')", $template);
		// 语言包处理
		$template = preg_replace("/\<\!\-\-\{lang\((.+?)\)\}\-\-\>/ie", "\$this->langtags('\\1')", $template);
		// URI处理
		$template = preg_replace("/\<\!\-\-\{geturi\((.+?)\)\}\-\-\>/ie", "\$this->geturitags('\\1')", $template);
		// PHP代码
		$template = preg_replace("/\<\!\-\-\{eval\s+(.+?)\s*\}\-\-\>/ies", "\$this->evaltags('\\1')", $template);
		
		// 开始处理
		// 变量
		$var_regexp = "((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";
		$template = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $template);
		$template = preg_replace("/([\n\r]+)\t+/s", "\\1", $template);
		$template = preg_replace("/(\\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\.([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/s", "\\1['\\2']", $template);
		$template = preg_replace("/\{(\\\$[a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?=\\1?>", $template);
		$template = preg_replace("/$var_regexp/es", "\$this->addquote('<?=\\1?>')", $template);
		$template = preg_replace("/\<\?\=\<\?\=$var_regexp\?\>\?\>/es", "\$this->addquote('<?=\\1?>')", $template);
		// 逻辑
		$template = preg_replace("/\{elseif\s+(.+?)\}/ies", "\$this->stripvtags('<?php } elseif(\\1) { ?>','')", $template);
		$template = preg_replace("/\{else\}/is", "<?php } else { ?>", $template);
		// 循环
		for($i = 0; $i < 5; $i++) {
			$template = preg_replace("/\{loop\s+(\S+)\s+(\S+)\}(.+?)\{\/loop\}/ies", "\$this->stripvtags('<?php if(is_array(\\1)) { foreach(\\1 as \\2) { ?>','\\3<?php } } ?>')", $template);
			$template = preg_replace("/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}(.+?)\{\/loop\}/ies", "\$this->stripvtags('<?php if(is_array(\\1)) { foreach(\\1 as \\2 => \\3) { ?>','\\4<?php } } ?>')", $template);
			$template = preg_replace("/\{if\s+(.+?)\}(.+?)\{\/if\}/ies", "\$this->stripvtags('<?php if(\\1) { ?>','\\2<?php } ?>')", $template);
			$template = preg_replace("/\{for\s+(.+?)\}(.+?)\{\/for\}/ies", "\$this->stripvtags('<?php for(\\1) { ?>','\\2<?php } ?>')", $template);
		}
		// 常量
		$template = preg_replace("/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1; ?>", $template);
		// 替换
		if (!empty($this->tpls['block_search'])) {
			$template = str_replace($this->tpls['block_search'], $this->tpls['block_replace'], $template);
		}
		// 换行
		$template = preg_replace("/ \?\>[\n\r]*\<\? /s", " ", $template);
		// 附加处理
		$template = "<?php if(!defined('SYS_IN')) exit('Access Denied');?><?php subtplcheck('" . implode('|', $this->tpls['sub_tpls']) . "', '$_G[system][timestamp]', '$tpl','$templatedir');?>$template";
		// write
		if (!trig_func_common::swritefile($objfile, $template)) {
			exit("File: $objfile can not be write!");
		}
	}

	function addquote($var) {
		return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
	}

	function striptagquotes($expr) {
		$expr = preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr);
		$expr = str_replace("\\\"", "\"", preg_replace("/\[\'([a-zA-Z0-9_\-\.\x7f-\xff]+)\'\]/s", "[\\1]", $expr));
		return $expr;
	}

	function evaltags($php) {
		$this->tpls['i']++;
		$search = "<!--EVAL_TAG_{$this->tpls['i']}-->";
		$this->tpls['block_search'][$this->tpls['i']] = $search;
		$this->tpls['block_replace'][$this->tpls['i']] = "<?php " . stripvtags($php) . " ?>";
		return $search;
	}

	function blocktags($parameter) {
		$this->tpls['i']++;
		$search = "<!--BLOCK_TAG_{$this->tpls['i']}-->";
		$this->tpls['block_search'][$this->tpls['i']] = $search;
		$this->tpls['block_replace'][$this->tpls['i']] = "<?php block(\"$parameter\"); ?>";
		return $search;
	}

	function datetags($parameter) {
		$this->tpls['i']++;
		$search = "<!--DATE_TAG_{$this->tpls['i']}-->";
		$this->tpls['block_search'][$this->tpls['i']] = $search;
		$this->tpls['block_replace'][$this->tpls['i']] = "<?php echo sgmdate($parameter); ?>";
		return $search;
	}

	function cnsubstrtags($parameter) {
		$this->tpls['i']++;
		$search = "<!--CNSUBSTR_TAG_{$this->tpls['i']}-->";
		$this->tpls['block_search'][$this->tpls['i']] = $search;
		$this->tpls['block_replace'][$this->tpls['i']] = "<?php echo cn_substr($parameter); ?>";
		return $search;
	}

	function geturitags($parameter) {
		$this->tpls['i']++;
		$search = "<!--GETURI_TAG_{$this->tpls['i']}-->";
		$this->tpls['block_search'][$this->tpls['i']] = $search;
		$this->tpls['block_replace'][$this->tpls['i']] = "<?php echo trig_mvc_route::get_uri($parameter); ?>";
		return $search;
	}

	function langtags($parameter) {
		$this->tpls['i']++;
		$search = "<!--LANG_TAG_{$this->tpls['i']}-->";
		$this->tpls['block_search'][$this->tpls['i']] = $search;
		$this->tpls['block_replace'][$this->tpls['i']] = "<?php echo trig_func_common::lang($parameter); ?>";
		return $search;
	}

	function stripvtags($expr, $statement = '') {
		$expr = str_replace("\\\"", "\"", preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr));
		$statement = str_replace("\\\"", "\"", $statement);
		return $expr . $statement;
	}

	function readtemplate($name) {
		$tpl = trig_func_common::strexists($name, '/') ? $name : "templates/" . $this->tpls['template'] . "/$name";
		$tplfile = ROOT_PATH . DS . $tpl . '.htm';
		$this->tpls['sub_tpls'][] = $tpl;
		
		if (!file_exists($tplfile)) {
			$tplfile = str_replace('/' . $this->tpls['template'] . '/', '/default/', $tplfile);
		}
		$content = trig_func_common::sreadfile($tplfile);
		return $content;
	}
}