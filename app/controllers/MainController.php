<?php
namespace app\controllers;

use app\core\Controller;
use app\models\User;

class MainController extends  Controller {


    public function indexAction(){


        echo $this->view->render('Главная страница');
        //echo 'Главная страница';
    }
}