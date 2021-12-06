<?php
require_once 'app/lib/dev.php';
require_once 'app/lib/PHPMailer/src/PHPMailer.php';
//require_once 'app/lib/PHPMailer/src/SMTP.php';
require_once 'app/lib/PHPMailer/src/Exception.php';
use app\lib\Db;
try {
    unset($argv[0]);

    spl_autoload_register(function ($class_name) {
        $path = str_replace('\\','/', $class_name.'.php');
        if(file_exists($path)){
            require $path;
        }

    });



    // Составляем полное имя класса, добавив нэймспейс
    $className = 'app\\cli\\' . array_shift($argv);
    if (!class_exists($className)) {
        throw new app\cli\CliException('Class "' . $className . '" not found');
    }

    // Подготавливаем список аргументов
    $params = [];
    foreach ($argv as $argument) {
        preg_match('/^-(.+)=(.+)$/', $argument, $matches);
        if (!empty($matches)) {
            $paramName = $matches[1];
            $paramValue = $matches[2];

            $params[$paramName] = $paramValue;
        }
    }

    // Создаём экземпляр класса, передав параметры и вызываем метод execute()
    $class = new $className($params);
    $class->execute();
} catch (app\cli\CliException $e) {
    echo 'Error: ' . $e->getMessage();
}
