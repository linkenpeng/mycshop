<?php

class trig_func_common {
	
	// 时间函数
	public static function mtime() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float) $usec + (float) $sec);
	}
	
	// SQL ADDSLASHES
	public static function saddslashes($string) {
		if (is_array($string)) {
			foreach ($string as $key => $val) {
				$string[$key] = self::saddslashes($val);
			}
		} else {
			$string = addslashes($string);
		}
		return $string;
	}
	
	// 获得用户IP
	public static function get_ip() {
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$user_ip = preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $user_ip) ? $user_ip : 'Unknown';
		return $user_ip;
	}

	public static function ShowMsg($msg, $gourl, $onlymsg = 0, $limittime = 0) {
		global $_G;
		$charset = $_G['system']['charset'];
		$htmlhead = "<html>\r\n<head>\r\n<title>" . SITE_NAME . self::lang("common", "sysnote") . "</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\" />\r\n";
		$htmlhead .= "<link rel='stylesheet' href='" . ADMIN_TEMPLATE_URL . "/css/style.css' type='text/css' />\r\n";
		$htmlhead .= "<base target='_self'/>\r\n</head>\r\n<body leftmargin='0' topmargin='0' bgcolor='#EBE9ED'>\r\n<center>\r\n<script>\r\n";
		$htmlfoot = "</script>\r\n</center>\r\n</body>\r\n</html>\r\n";
		
		if ($limittime == 0)
			$litime = 1000;
		else
			$litime = $limittime;
		
		if ($gourl == "-1") {
			if ($limittime == 0)
				$litime = 1000;
			$gourl = "javascript:history.go(-1);";
		}
		
		if ($gourl == "" || $onlymsg == 1) {
			$msg = "<script>alert(\"" . str_replace("\"", "“", $msg) . "\");</script>";
		} else {
			$func = " var pgo=0;
		  function JumpUrl(){
			if(pgo==0){ location='$gourl'; pgo=1; }
		  }\r\n";
			$rmsg = $func;
			$rmsg .= "document.write(\"<br/><div style='width:400px;padding-top:4px;height:24;font-size:10pt;border-left:1px solid E3E9F1;border-top:1px solid E3E9F1;border-right:1px solid E3E9F1;background-color:#1065ac;color:#ffffff;padding:5px 0 ;line-height:24px;font-weight:bold;'>" . SITE_NAME . self::lang("common", "sysnote") . "：</div>\");\r\n";
			$rmsg .= "document.write(\"<div style='width:400px;height:100;font-size:10pt;border:1px solid E3E9F1;background-color:#f9fcf3'><br/><br/>\");\r\n";
			$rmsg .= "document.write(\"" . str_replace("\"", "“", $msg) . "\");\r\n";
			$rmsg .= "document.write(\"";
			if ($onlymsg == 0) {
				if ($gourl != "javascript:;" && $gourl != "") {
					$rmsg .= "<br/><br/><a href='" . $gourl . "'>" . self::lang("message", "messagenote") . "</a>";
				}
				$rmsg .= "<br/><br/></div>\");\r\n";
				if ($gourl != "javascript:;" && $gourl != "") {
					$rmsg .= "setTimeout('JumpUrl()',$litime);";
				}
			} else {
				$rmsg .= "<br/><br/></div>\");\r\n";
			}
			$msg = $htmlhead . $rmsg . $htmlfoot;
		}
		echo $msg;
		exit();
	}
	
	// 产生随机数
	public static function rands($length = 6, $type = 3) {
		$hash = '';
		$randseq = array(
			1 => '0123456789',
			2 => 'abcdefghijklmnpqrstuvwxyz',
			3 => '123456789abcdefghijklmnpqrstuvwxyz' 
		);
		$chars = array_key_exists($type, $randseq) ? $randseq[$type] : $randseq[3];
		$max = strlen($chars) - 1;
		mt_srand((double) microtime() * 1000000);
		for($i = 0; $i < $length; $i ++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
		return $hash;
	}

	/**
	 * 对用户的密码进行加密
	 * 
	 * @param $password
	 * @param $encrypt //传入加密串，在修改密码时做认证        	
	 * @return array/password
	 */
	public static function password($password, $encrypt = '') {
		global $_G;
		$key = $encrypt ? $encrypt : $_G['system']['pass_key'];
		$pwd = md5(md5(md5(trim($password)) . $key) . strlen($key));
		return $pwd;
	}
	
	/*
	 * 获取语言包 @class 分类语言包 @key 语言包中的键 return 语音包中的值
	 */
	public static function lang($langfile, $key) {
		global $_CTCONFIG;
		if (empty($langfile) or empty($key)) {
			return "Undefined Language!";
		} else {
			if (file_exists(ROOT_PATH . DS . "source" . DS . "language" . DS . $_CTCONFIG['lang'] . DS . $langfile . ".php")) {
				include ROOT_PATH . DS . "source" . DS . "language" . DS . $_CTCONFIG['lang'] . DS . $langfile . ".php";
			}
			return $lang[$key];
		}
	}	
	
	// 判断字符串是否存在
	public static function strexists($haystack, $needle) {
		return !(strpos($haystack, $needle) === FALSE);
	}
	
	// 获取文件内容
	public static function sreadfile($filename) {
		$content = '';
		if (function_exists('file_get_contents')) {
			@$content = file_get_contents($filename);
		} else {
			if (@$fp = fopen($filename, 'r')) {
				@$content = fread($fp, filesize($filename));
				@fclose($fp);
			}
		}
		return $content;
	}
	
	// 写入文件
	public static function swritefile($filename, $writetext, $openmod = 'w') {
		if (@$fp = fopen($filename, $openmod)) {
			flock($fp, 2);
			fwrite($fp, $writetext);
			fclose($fp);
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 读取一个目录下的所有文件
	 * @param string $folder
	 * @param array $fileList
	 * @param array $fileType
	 */
	public static function get_all_files($folder, $fileList = array(), $fileType = array()) {
		$folder = empty($folder) ? dirname(__FILE__) : $folder;
		$folder = str_replace('\\','/',$folder);
		$fileList = empty($fileList) ? array() : $fileList;
		$d = dir($folder);
		while (false !== ($file = $d->read())) {
			if($file != '.' && $file != '..') {
				$f = $folder.'/'.$file;
				if(is_dir($f)) {
					self::_getAllFiles($f, $fileList ,$fileType);
				} else {
					if(!empty($fileType)) {
						if(in_array(pathinfo($f, PATHINFO_EXTENSION), $fileType)) {
							$fileList[] =  $f;
						}
					} else {
						$fileList[] =  $f;
					}
				}
			}
		}
		$d->close();
	
		return $fileList;
	}
	
	public static function make_dirs($folder) {
		if(!file_exists($folder)) {
			mkdir($folder, 0755, true);
		}		
	}
	
	// 连接字符
	public static function simplode($ids) {
		return "'" . implode("','", $ids) . "'";
	}
	
	// 获取文件名后缀
	public static function fileext($filename) {
		return strtolower(trim(substr(strrchr($filename, '.'), 1)));
	}
	
	// 时间格式化
	public static function sgmdate($dateformat, $timestamp = '', $format = 0) {
		global $_CTCONFIG;
		if (empty($timestamp)) {
			$timestamp = $_CTCONFIG['timestamp'];
		}
		$result = '';
		if ($format) {
			$time = $_CTCONFIG['timestamp'] - $timestamp;
			echo $time;
			if ($time > 24 * 3600) {
				$result = gmdate($dateformat, $timestamp + $_CTCONFIG['time_offset'] * 3600);
			} elseif ($time > 3600) {
				$result = intval($time / 3600) . self::lang('datetime', 'hour') . self::lang('datetime', 'before');
			} elseif ($time > 60) {
				$result = intval($time / 60) . self::lang('datetime', 'minute') . self::lang('datetime', 'before');
			} elseif ($time > 0) {
				$result = $time . self::lang('datetime', 'second') . self::lang('datetime', 'before');
			} else {
				$result = self::lang('datetime', 'now');
			}
		} else {
			$result = gmdate($dateformat, $timestamp + $_CTCONFIG['time_offset'] * 3600);
		}
		return $result;
	}
	
	// $cutlength 为截取的长度(即字数)
	public static function cn_substr($str, $length = 0, $append = false, $charset = '', $istrimhtml = 1) {
		global $_G;
		$str = trim($str);
		if (!empty($istrimhtml)) {
			$str = self::trimhtml($str);
		}
		if (empty($charset)) {
			$charset = $_G['system']['charset'];
		}
		$strlength = strlen($str);
		
		if ($length == 0 || $length >= $strlength) {
			return $str;
		} elseif ($length < 0) {
			$length = $strlength + $length;
			if ($length < 0) {
				$length = $strlength;
			}
		}
		if (function_exists('mb_substr')) {
			$newstr = mb_substr($str, 0, $length, $charset);
		} elseif (function_exists('iconv_substr')) {
			$newstr = iconv_substr($str, 0, $length, $charset);
		} else {
			$newstr = substr($str, 0, $length);
		}
		if ($append && $str != $newstr) {
			$newstr .= '...';
		}
		return $newstr;
	}
	
	// 过滤html代码
	public static function trimhtml($str) {
		$search = array(
			"'<script[^>]*?>.*?</script>'si", // 去掉 javascript
			"'<[\/\!]*?[^<>]*?>'si" 
		); // 去掉 HTML 标记
		$replace = array(
			"",
			"" 
		);
		$text = preg_replace($search, $replace, $str);
		return $text;
	}
	
	// 取消HTML代码
	public static function shtmlspecialchars($string) {
		if (is_array($string)) {
			foreach ($string as $key => $val) {
				$string[$key] = self::shtmlspecialchars($val);
			}
		} else {
			$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1', str_replace(array(
				'&',
				'"',
				'<',
				'>' 
			), array(
				'&amp;',
				'&quot;',
				'&lt;',
				'&gt;' 
			), $string));
		}
		return $string;
	}
	
	// 是否支持
	public static function showResult($v) {
		if ($v == 1) {
			echo '<b>√</b>&nbsp;<font class=green>' . self::lang('common', 'support') . '</font>';
		} else {
			echo '<font class=red><b>×</b></font>&nbsp;<font class=red>' . self::lang('common', 'unsupport') . '</font>';
		}
	}
	
	// 获取一串中文字符的拼音 ishead=0 时，输出全拼音 ishead=1时，输出拼音首字母
	public static function getPinyin($str, $ishead = 0, $isclose = 1) {
		global $pinyins;
		$str = strtolower(CHARSET) == 'utf-8' ? iconv('utf-8', 'gbk//ignore', $str) : $str;
		
		$restr = '';
		$str = trim($str);
		$slen = strlen($str);
		if ($slen < 2) {
			return $str;
		}
		
		if (count($pinyins) == 0) {
			$fp = fopen(ROOT_PATH . "/source/language/zh-cn/pinyin.dat", 'r');
			while (!feof($fp)) {
				$line = trim(fgets($fp));
				$pinyins[$line[0] . $line[1]] = substr($line, 3, strlen($line) - 3);
			}
			fclose($fp);
		}
		
		for($i = 0; $i < $slen; $i ++) {
			if (ord($str[$i]) > 0x80) {
				$c = $str[$i] . $str[$i + 1];
				$i ++;
				if (isset($pinyins[$c])) {
					if ($ishead == 0) {
						$restr .= $pinyins[$c];
					} else {
						$restr .= $pinyins[$c][0];
					}
				} else {
					$restr .= "_";
				}
			} else if (preg_match("/[a-z0-9]/i", $str[$i])) {
				$restr .= $str[$i];
			} else {
				$restr .= "_";
			}
		}
		
		if ($isclose == 0) {
			unset($pinyins);
		}
		
		return $restr;
	}
	
	// 加密函数
	public static function encode_text($txt, $key = FRAME_NAME) {
		$txt = $txt.$key;
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
		$nh = rand(0,64);
		$ch = $chars[$nh];
		$mdKey = md5($key.$ch);
		$mdKey = substr($mdKey,$nh%8, $nh%8+7);
		$txt = base64_encode($txt);
		$tmp = '';
		$i=0;$j=0;$k = 0;
		for ($i=0; $i<strlen($txt); $i++) {
			$k = $k == strlen($mdKey) ? 0 : $k;
			$j = ($nh+strpos($chars,$txt[$i])+ord($mdKey[$k++]))%64;
			$tmp .= $chars[$j];
		}
		return urlencode(base64_encode($ch.$tmp));
	}
	
	// 解密函数
	public static function decode_text($txt, $key = FRAME_NAME) {
		$txt = base64_decode(urldecode($txt));
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
		$ch = $txt[0];
		$nh = strpos($chars,$ch);
		$mdKey = md5($key.$ch);
		$mdKey = substr($mdKey,$nh%8, $nh%8+7);
		$txt = substr($txt,1);
		$tmp = '';
		$i=0;$j=0; $k = 0;
		for ($i=0; $i<strlen($txt); $i++) {
			$k = $k == strlen($mdKey) ? 0 : $k;
			$j = strpos($chars,$txt[$i])-$nh - ord($mdKey[$k++]);
			while ($j<0) $j+=64;
			$tmp .= $chars[$j];
		}
		return trim(base64_decode($tmp),$key);
	}

	public static function debug_arr($var, $isdump = false) {
		echo '<pre>';
		if ($isdump) {
			var_dump($var);
		} else {
			print_r($var);
		}
		echo '</pre>';
	}
}