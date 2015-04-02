<?php
/**
 * trig: the right framework
 * @version 1.0.0
 */
define('FRAME_NAME', 'trig');
define('FRAME_PATH',str_replace("\\","/",dirname(__FILE__)));

include FRAME_PATH . DIRECTORY_SEPARATOR . FRAME_NAME . DIRECTORY_SEPARATOR . 'loader.php';
$autoload_paths['frame'] = FRAME_PATH;
new trig_loader($autoload_paths);