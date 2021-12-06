<?php
namespace app\models;

use app\core\Model;



class User extends Model {
    public $id;
    public $email;
    public $is_exist;
    public $tableName = 'users';

    public function __construct() {
        parent::__construct();
    }
    public function save(){
        if($this->id==NULL){
            $query = "INSERT INTO " . $this->tabelName .'(email, is_exist) VALUES(:email, :is_exist)';
            $param = ['email'=>$this->email, 'is_exist' => $this->is_exist];
            $result = self::$conn->query($query, $param);
            $this->id = self::$conn->gelLastInserId();

        }else{
            $query = "UPDATE  " . $this->tabelName.' SET  email =:email, is_exist =:is_exist WHERE id=:id';
            $param = ['email'=> $this->email, 'is_exist' => $this->is_exist, 'id' => $this->id];
            $result = self::$conn->query($query, $param);
        }

        return $result;
    }
    
    
    public function getByEmail($email){
        $query = 'SELECT * FROM users WHERE email=:email';
        $result = self::$conn->query($query, ['email' =>$email]);


        return $result->fetchObject(get_class($this));
    }

    public function setSubscribe($user_id, $url_id){
       // $urlObj = Url::getByUrl($url);
        $query = 'INSERT INTO subscribe (id_user, id_url, created) VALUES(:user_id, :url_id, :created)';
        $param = ['user_id'=>(int)$user_id, 'url_id' => (int)$url_id, 'created' => date('Y-m-d H:s:i')];
        $result = self::$conn->query($query, $param);
        return $result;
    }



}