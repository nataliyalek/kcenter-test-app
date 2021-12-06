<?php
namespace app\core;

use app\lib\Db;
use PDO;

abstract class Model
{
    protected static $conn;
    protected  $tableName;


    public function __construct() {
        self::$conn = new Db;
    }

    public static function className(){
        return self::class;
    }

    public function delete($id) {
        return self::$conn->query("DELETE FROM " .$this->tableName ." WHERE id=:id", ['id'=>$id]);
    }

    public  function findMany() {
        $record   = self::$conn->query("SELECT * FROM " .$this->tableName);
        //var_dump(self::className()); die();
        return $record->fetchAll(PDO::FETCH_CLASS, get_class($this));
    }

    public function findONE($id) {
        $record   = self::$conn->query("SELECT * FROM " .$this->tableName ." WHERE id=:id", ['id'=>$id]);
        return $record->fetch(PDO::FETCH_ASSOC);
    }


}

