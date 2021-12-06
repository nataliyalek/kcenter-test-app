<?php
namespace app\models;

use app\core\Model;

class Main extends Model
{
    public function getUsers(){
        $users = $this->db->getAll('SELECT  * FROM users', ['id' => '1']);
        return $users;
    }
}