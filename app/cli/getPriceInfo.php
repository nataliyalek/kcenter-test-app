<?php

namespace app\cli;

use app\cli\CliException;
use app\core\View;
use app\models\PriceInfo;
use app\models\User;
use app\models\Url;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class getPriceInfo
{
    /** @var array */
    //private $params;

    public function __construct(array $params)
    {
       // $this->params = $params;
       // $this->checkParams();
    }

    public function execute()
    {
        $url = new Url();

        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        $viewPost = new View(['controller' => 'priceinfoapi', 'action' => 'mailtemplate']);

        //debug($viewPost->render('Отправка почты'));
        $urls = $url->getСheckUrls();
        $emailinfo = [];
        foreach ($urls as $url) {
            $url->getPriceInfoByUrl();
            $emailinfo[] = ['url' => $url->url];
            if($url->priceInfo!==null) {
                //var_dump($url->priceInfo);
                $users = $url->getUserSubscribe();
                $url_link = $url->url;
                $old_price = $url->priceInfo->priceOld;
                $new_price = $url->priceInfo->price;
                $emailinfo[$url->id] = ['url' => $url->url,
                    'old_price' => $old_price,
                    'new_price' => $new_price,
                    ];
                if($new_price != $old_price) {

                    foreach ($users as $user) {
                        $mail = new PHPMailer();
                        $mail->setFrom('test@domain.ru', 'Иван Иванов');
                        $mail->addAddress($user->email, $user->email);
                        $mail->Subject = 'Cообщение об изменение цены на сайте';
                        $massage = $viewPost->render($mail->Subject, ['url' => $url_link,
                            'old_price' => $old_price,
                            'new_price' => $new_price]);

                        $mail->msgHTML($massage);
                        if ($mail->send()) {
                            $emailinfo[$url->id][] = [
                                'emailto' => $user->email,
                                'send' => 'success',
                            ];

                        } else {
                            $emailinfo[$url->id][] = [
                                'send' => 'error',
                                'errorInfo' => $mail->ErrorInf,
                            ];

                        }
                    }
                }
            }
        }
        if (count($emailinfo)){
            var_dump($emailinfo);
            echo json_encode($emailinfo);
        }else{
            echo 'Нет данных';
        }
    }

}