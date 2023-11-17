<?php

namespace App\Models;
use CodeIgniter\Model;

class UsersModel extends Model {

    public function userLogin($username, $password) {

        $db = \Config\Database::connect();
        $query = $db->query('SELECT * FROM users WHERE (username = "'.$username.'" OR email = "'.$username.'") AND password = SHA2(CONCAT("'.$password.'", salt), 256);');
        
        $result = $query->getResult();

        if(count($result) > 0){

            return $result[0];

        } else {

            return false;

        }

    }

    
}