<?php
namespace app\controllers;
use app\core\Controller;
use app\core\View;
use app\models\PriceInfo;
use app\models\User;
use app\models\Url;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/*
 * Подписка на отслеживание цен
 */
class PriceinfoapiController extends Controller{

    function __construct($route)
    {
        $this->route = $route;
        //$this->view = new View($route);
        $this->model = $this->loadModel($route['controller']);
    }
    /*
     * Подписка на ссылку
     */
    public function subscribeAction(){

        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        //$data = $_POST;

        $data =$_POST;
        if(isset($data['email'])&&isset($data['url'])){
            if ($this->isValidEmail($data['email']) && $this->isValidUrl($data['url'])){
                //$user = User::getByEmail($data['email']);
                $user = new User();
                $user = $user->getByEmail($data['email']);
                //debug($user);
                if (!$user) {
                    $user = new User();
                    $user->email = $data['email'];
                    $user->is_exist = '0';
                    $user->save();

                }
                //$urlObj = new Url();
                //$urlObj = $urlObj->getByUrl($data['url'])? $urlObj->getByUrl($data['url']):$urlObj;
                $urlObj = Url::getByUrl($data['url']) ? Url::getByUrl($data['url']) : new Url();
                $urlObj->url = $data['url'];
                $urlObj->save();
                $urlObj->getPriceInfoByUrl();
                $result = $user->setSubscribe($user->id, $urlObj->id);
                if ($result) {
                    echo json_encode(['status' => $result, 'message' => 'Все ок!']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Ошибка при подписке']);
                }
            }else {
                echo  json_encode(['status' => 'error', 'message' => 'Не вернные данные']);
            }





        }


    }

    public function isValidEmail($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL)
                && preg_match('/@.+\./', $email);
    }
    public function isValidUrl($url){
        return filter_var($url, FILTER_VALIDATE_URL);
    }


}