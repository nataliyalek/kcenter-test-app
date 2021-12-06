<?php
namespace app\models;

use app\core\Model;
use http\Url;


class PriceInfo extends Model {
    public $id;
    public $url_id;
    public $date_check;
    public $price;
    public $priceOld;
    public $tableName = 'price_info';

    public function __construct() {
        parent::__construct();
        $this->tableName = 'price_info';
    }





    public function save(){
        if($this->id==NULL){
            //var_dump($this,  $this->tabelName);
            $query = "INSERT INTO " . $this->tableName .'(url_id, price, date_check)  VALUES(:url_id, :price, :date)';
            $param = ['url_id'=>$this->url_id, 'price' => $this->price, 'date' => $this->date_check];
            $result = self::$conn->query($query, $param);
            $this->id = self::$conn->gelLastInserId();

        }else{
            $query = "UPDATE  " . $this->tabelName .' SET url_id =:url, price =:price, date_check = :date, id=:id WHERE id=:id';
            $param = ['url_id'=>$this->url_id, 'price' => $this->price, 'date' => $this->date_check, 'id' => $this->id];
            $result = self::$conn->query($query, $param);
        }

        return $result;
    }


}