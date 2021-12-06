<?php
namespace app\core;
 class View
{
    public $path;
    public $route;
    public $layout = 'default';

    public function __construct($route)
    {
        $this->route = $route;
        $this->path = $route['controller'] . '/' . $route['action'];
        //echo $this->path;
    }

    public function render($title, $vars = []){
        ob_start();
        extract($vars);
        if(file_exists('app/views/'.$this->path .'.php')){
            require 'app/views/'.$this->path .'.php';
            $content = ob_get_clean();
            ob_start();
            require 'app/views/layouts/'.$this->layout .'.php';
            return ob_get_clean();
         }else{
            echo 'Вид не найден';
        }
    }



    public function redirect($url){
        header('location: '.$url);
        exit();
    }

    public static  function errorCode($code){
        http_response_code($code);
        $path = 'app/views/errors/'.$code.'.php';
        if (file_exists($path)) {
            require $path;
        }
        exit;
    }

     public function message($status, $message) {
         exit(json_encode(['status' => $status, 'message' => $message]));
     }

     public function location($url) {
         exit(json_encode(['url' => $url]));
     }

}
