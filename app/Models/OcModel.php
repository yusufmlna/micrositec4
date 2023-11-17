<?php

namespace App\Models;

use CodeIgniter\Model;

class OcModel extends Model
{

    public function getProductId($isbn)
    {

        $db = \Config\Database::connect('oc');
        $query = $db->query('SELECT product_id FROM product WHERE isbn = :isbn:', [
            "isbn" => $isbn
        ]);

        $result = $query->getResult();

        if (count($result) > 0) {

            return $result[0];
        } else {

            return false;
        }
    }

    public function getJournalModule($module_id)
    {

        $db = \Config\Database::connect('oc');
        $query = $db->query('SELECT * FROM journal3_module WHERE module_id = :module_id:', [
            "module_id" => $module_id
        ]);

        $result = $query->getResult();

        if (count($result) > 0) {

            return $result[0];
        } else {

            return false;
        }
    }

    public function updateJournalModuleData($module_id, $module_data){
        $db = \Config\Database::connect('oc');
        
        $query = $db->query('UPDATE journal3_module SET module_data = :module_data: WHERE module_id = :module_id:', [
            "module_id" => $module_id,
            "module_data" => $module_data
        ]);

        return true;
    }

    public function categoryUpdate($category_id, $products){

        $db = \Config\Database::connect('oc');

        $queryClear = $db->query('DELETE FROM product_to_category WHERE category_id = :category_id:', [
            "category_id" => $category_id
        ]);

        foreach($products AS $p){
            $queryInsert = $db->query('INSERT INTO product_to_category VALUES(:product_id:, :category_id:)', [
                "product_id" => $p,
                "category_id" => $category_id
            ]);
        }

        return true;
    }

    public function categoryDiscount($category_id, $discount, $start_date, $end_date){

        $db = \Config\Database::connect('oc');
        
        $query = $db->query("INSERT INTO product_special(product_id, customer_group_id, priority, price, date_start, date_end) SELECT product_id, 1, 1, (price - (price * (:discount: / 100))), :start_date:, :end_date: FROM product WHERE product_id IN (SELECT product_id FROM product_to_category WHERE category_id = :category_id:)", [
            "discount" => $discount,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "category_id" => $category_id
        ]);

        return true;

    }

    public function manualDiscount($isbn, $discount, $start_date, $end_date){

        $db = \Config\Database::connect('oc');
        
        $query = $db->query("INSERT INTO product_special(product_id, customer_group_id, priority, price, date_start, date_end) SELECT product_id, 1, 1, (price - (price * (:discount: / 100))), :start_date:, :end_date: FROM product WHERE isbn = :isbn:", [
            "discount" => $discount,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "isbn" => $isbn
        ]);

        return true;

    }

}
