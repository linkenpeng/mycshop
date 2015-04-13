<?php
/**
 * trig: the right framework
 * @version 1.0.0
 * @author peng.zhenxian collin_linken@qq.com
 */
define('FRAME_NAME', 'trig');
define('FRAME_PATH',dirname(__FILE__));

$autoload_paths['frame'] = str_replace(DIRECTORY_SEPARATOR.FRAME_NAME, '', FRAME_PATH);
include FRAME_PATH . DIRECTORY_SEPARATOR . 'loader.php';
new trig_loader($autoload_paths);