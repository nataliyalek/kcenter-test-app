<?php
namespace app\lib;
use PDO;
class Db {
    protected $db;

    public function __construct()
    {
        $db_config = require 'app/config/db.php';
        $this->db  = new PDO('mysql:host='.$db_config['host'].'; dbname='.$db_config['dbname'], $db_config['user'], $db_config['password']);

    }

    public function query($sql, $params = []){
        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            foreach ($params as $key => $val) {
                $stmt->bindValue(':'.$key, $val);
            }
        }

        $stmt->execute();

        return $stmt;
    }

    public function getColumn($sql, $params = []){
        $result = $this->query($sql, $params);
        return $result->fetchColumn();

    }
    public function getAll($sql, $params = []){

        $result = $this->query($sql, $params);

        return $result->fetchAll(PDO::FETCH_CLASS);
    }

    public function gelLastInserId(){
        return $this->db->lastInsertId();
    }





}
