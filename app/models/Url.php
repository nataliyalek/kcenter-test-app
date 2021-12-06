<?php
namespace app\models;
use app\core\Model;
use MongoDB\Driver\Exception\Exception;
use PHPMailer\PHPMailer\PHPMailer;




class Url extends Model
{
    public $id;
    public $url;
    public $priceInfo;
    public  $tableName = 'urls';

    public function __construct()
    {
        parent::__construct();
    }

    static public function getByUrl($url){
        $query = 'SELECT * FROM urls WHERE url=:url';
        $result = self::$conn->query($query, ['url' =>$url]);
        return $result->fetchObject(self::class);


    }
    /*
     * Получает цену на товар из базы на дату
     */
    public function getPriceOnDate($date){
        $query = 'SELECT * FROM price_info 
                  WHERE url_id=:url_id 
                    AND  date_check<=:date ORDER BY date_check DESC LIMIT 0,1';
        $result = self::$conn->query($query, ['url_id' => $this->id, 'date' => $date]);

        return $result->fetchObject(PriceInfo::class);
    }
    /*
     * Получает цену на товар из стороннего сайта  по ссылке
     */
    public function getPriceInfoByUrl(){
        include_once 'app/lib/phpquery/phpQuery/phpQuery.php';
        try {
            $content = @file_get_contents($this->url);

            if(strlen($content)) {
                $doc = \phpQuery::newDocument($content);

                //$data = $doc->find('div class="cardOrder"');
                $entry = $doc->find('.cardOrder');
                $priceNew = pq($entry)->attr('data-price');
                $date_now = date('Y-m-d H:s:i');
                //debug($this->getPriceOnDate($date_now));
                $priceInfoOld = $this->getPriceOnDate($date_now);
                //var_dump($this->url,$priceInfoOld, $priceNew);
                if (($priceInfoOld && $priceInfoOld->price == $priceNew) || $priceNew == 0) {
                    $this->priceInfo = $priceInfoOld;
                    $this->priceInfo->priceOld = $this->priceInfo->price;
                } else {

                    $priceInfo = new PriceInfo($this->id, $date_now, $priceNew);
                    $priceInfo->date_check = $date_now;
                    $priceInfo->url_id = $this->id;
                    $priceInfo->priceOld = $priceInfo->price;
                    $priceInfo->price = $priceNew;
                    $priceInfo->save();
                    $this->priceInfo = $priceInfo;
                }
            }
        }catch (Exception $errors){
        }



        return $this;
    }

    public   function getСheckUrls(){

        $urls = $this->findMany();

        return $urls;
    }

    public function isset_url($url){
        $query = 'SELECT id FROM urls WHERE url=:url';
        $result = self::$conn->getColumn($query, ['url' =>$url]);
        return $result;
    }
    public function save(){

        if($this->id==NULL){
            $query = "INSERT INTO " . self::$tableName .' (url)  VALUES(:url)';
            $param = ['url'=> $this->url];
            $result = self::$conn->query($query, $param);
            $this->id = self::$conn->gelLastInserId();
        }else{
            $query = "UPDATE  " . self::$tableName .' SET url =:url WHERE id=:id';
            $param = ['url'=>$this->url,  'id' => $this->id];
            $result = self::$conn->query($query, $param);
         }

        return $result;

    }


    public function getUserSubscribe(){
        $query = "SELECT u.id, u.email
                  FROM subscribe s 
                  LEFT JOIN users u ON u.id = s.id_user
                  WHERE s.id_url = :url_id";
       // var_dump($query, $this->id);
        $result = self::$conn->getAll($query, ['url_id' =>$this->id]);
        return $result;

    }



}