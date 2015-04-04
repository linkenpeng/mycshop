<?php
/**
 * trig: the right framework
 * @version 1.0.0
 * @author collin.peng collin_linken@qq.com
 */
define('FRAME_NAME', 'trig');
define('FRAME_PATH',str_replace("\\","/",dirname(__FILE__)));

$autoload_paths['frame'] = FRAME_PATH;
include FRAME_PATH . DIRECTORY_SEPARATOR . FRAME_NAME . DIRECTORY_SEPARATOR . 'loader.php';
new trig_loader($autoload_paths);