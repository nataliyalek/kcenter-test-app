<?php
 require_once 'app/lib/dev.php';
 require_once 'app/lib/PHPMailer/src/PHPMailer.php';
//require_once 'app/lib/PHPMailer/src/SMTP.php';
require_once 'app/lib/PHPMailer/src/Exception.php';
 use app\lib\Db;
 use app\core\Router;

spl_autoload_register(function ($class_name) {
    $path = str_replace('\\','/', $class_name.'.php');
    if(file_exists($path)){
        require $path;
    }

});

$route = new Router();
$route->run();