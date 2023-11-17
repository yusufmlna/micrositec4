<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductsModel extends Model
{

    public function uploadProduct($d)
    {

        $db = \Config\Database::connect();
        $query = $db->query('INSERT INTO products_upload(isbn, title, author, price, description, category, subject, subsubject, stock, format, pages, image, weight, length, width, height, preorder, createtime, updatetime) VALUES("' . $d['A'] . '", "' . $d['B'] . '", "' . $d['C'] . '", "' . $d['D'] . '", "' . $d['E'] . '", "' . $d['F'] . '", "' . $d['G'] . '", "' . $d['H'] . '", "' . $d['I'] . '", "' . $d['J'] . '", "' . $d['K'] . '", "' . $d['L'] . '", "' . $d['M'] . '", "' . $d['N'] . '", "' . $d['O'] . '", "' . $d['P'] . '", "' . $d['Q'] . '", NOW(), NOW()) ON DUPLICATE KEY UPDATE title = "' . $d['B'] . '", author = "' . $d['C'] . '", price = "' . $d['D'] . '", description = "' . $d['E'] . '", category = "' . $d['F'] . '", subject = "' . $d['G'] . '", subsubject = "' . $d['H'] . '", stock = "' . $d['I'] . '", format = "' . $d['J'] . '", pages = "' . $d['K'] . '", image = "' . $d['L'] . '", weight = "' . $d['M'] . '", length = "' . $d['N'] . '", width = "' . $d['O'] . '", height = "' . $d['P'] . '", preorder = "' . $d['Q'] . '", updatetime = now()');

        return true;
    }

    public function createExcludeProduct($isbn, $stock, $stockStatusId)
    {

        $db = \Config\Database::connect();

        $query = $db->query("INSERT INTO products_excluded(isbn, stock, stock_status_id) VALUES(:isbn:, :stock:, :stock_status_id:)", [
            "isbn" => $isbn,
            "stock" => $stock,
            "stock_status_id" => $stockStatusId,
        ]);

        return true;
    }

    public function deleteExcludeProduct($isbn)
    {

        $db = \Config\Database::connect();

        $query = $db->query("DELETE FROM products_excluded WHERE isbn = :isbn:", [
            "isbn" => $isbn
        ]);

        return true;
    }

    public function getExcludedProduct()
    {

        $db = \Config\Database::connect();
        $query = $db->query('SELECT * FROM products_excluded');

        $result = $query->getResult();

        if (count($result) > 0) {

            return $result;
        } else {

            return false;
        }
    }
}
