<?php
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

set_include_path(APPLICATION_PATH . '/../library');

require_once 'Zend/Loader/Autoloader.php';

$autoloader = Zend_Loader_Autoloader::getInstance();

$paths    = array(APPLICATION_PATH . '/../plugins/', APPLICATION_PATH . '/../themes/');
$script   = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
$filename = str_replace($script, '', $_SERVER['REQUEST_URI']);

header("Content-Transfer-Encoding: binary");
header("Content-type: application/octet-stream");
header("Connection: close");
        
foreach ($paths as $path) {
    $fullpath = $path . $filename;
    if (file_exists($fullpath)) {
        $name = basename($fullpath);
        $size = filesize($fullpath);
        $ext = pathinfo($fullpath, PATHINFO_EXTENSION);
        
        switch ($ext) {
            case 'js':
                header("Content-type: application/x-javascript");
                break;
            case 'css':
                header("Content-type: text/css");
                break;
            default:
                break;
        }
        
        header("Content-disposition: inline; filename={$name}");
        ob_clean();
        flush();
        readfile($fullpath);
        exit;
    }
}

if (Zend_Auth::getInstance()->hasIdentity() || (!isset($_SERVER['HTTP_USER_AGENT'])) || $_SERVER['HTTP_USER_AGENT'] == 'none') {
    $fullpath = APPLICATION_PATH . '/../data/upload/' . $filename;
    if (file_exists($fullpath)) {
        $name = basename($fullpath);
        $size = filesize($fullpath);
        header("Content-disposition: inline; filename={$name}");
        ob_clean();
        flush();
        readfile($fullpath);
        exit;
    }
}

header('HTTP/1.0 204 No Content');