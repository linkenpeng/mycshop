<?php

/**
 * 验证码生成
 *
 */
class trig_checkcode {

	function __construct() {
	}

	function showcode($w = 50, $h = 25) {
		$str = trig_func_common::rands(4); // 随机生成的字符串
		$width = empty($w) ? 50 : $w; // 验证码图片的宽度
		$height = empty($h) ? 25 : $h; // 验证码图片的高度
		header("Content-Type:image/png");
		$_SESSION["checkcode"] = $str;
		$im = imagecreate($width, $height);
		// 背景色
		$back = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
		// 模糊点颜色
		$pix = imagecolorallocate($im, 149, 2221, 49);
		// 字体色
		$font = imagecolorallocate($im, 128, 0, 0);
		// 绘模糊作用的点
		mt_srand();
		for($i = 0; $i < 200; $i++) {
			imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $pix);
		}
		imagestring($im, 5, 7, 5, $str, $font);
		imagepng($im);
		imagedestroy($im);
		$_SESSION["checkcode"] = $str;
	}
}