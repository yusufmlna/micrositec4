<?php

$dbMicrosite = mysqli_connect("localhost", "root", "", "", "3306");
$dbOpencart = mysqli_connect("localhost", "root", "", "", "3306");
require_once "vendor/autoload.php";

use phpseclib3\Net\SFTP;

// $newProducts = mysqli_query($dbMicrosite, "SELECT * FROM products_upload WHERE isbn = '9781524763145' LIMIT 5");
$newProducts = mysqli_query($dbMicrosite, "SELECT * FROM products_upload LIMIT 2000");

while ($p = mysqli_fetch_assoc($newProducts)) {

    $isProductExist = checkProduct($dbOpencart, $p['isbn']);

    $model = $p['isbn'];
    $ean = $p['isbn'];
    $isbn = $p['isbn'];
    $quantity = ($p['stock'] != '' ? (int)$p['stock'] : 0);

    if ($p['preorder'] == "0") {
        $stock_status_id = ($quantity > 0 ? 2 : 1);
    } else {
        $stock_status_id = ($quantity > 0 ? 5 : 1);
    }

    $image = 'products/' . substr($model, -3) . '/' . $model . '.jpg';
    $manufacturer_id = 0;
    $shipping = 1;
    $price = ($p['price'] != '' ? (int)$p['price'] : 0);
    $points = 0;
    $tax_class_id = 0;
    $date_available = '2000-01-01';
    $weight = ($p['weight'] != '' ? (float)$p['weight'] / 1000 : 0);
    $weight_class_id = 1;
    $length = ($p['length'] != '' ? (float)$p['length'] : 0);
    $width = ($p['width'] != '' ? (float)$p['width'] : 0);
    $height = ($p['height'] != '' ? (float)$p['height'] : 0);
    $length_class_id = 1;
    $subtract = 1;
    $minimum = 1;
    $sort_order = 1;
    $status = 1;
    $viewed = 0;

    $mccode = $p['category'] . $p['subject'] . $p['subsubject'];
    $productCategory = categoryMapper(strtoupper($mccode));

    if (!$isProductExist) {

        if ($productCategory) {

            $productQuery = "INSERT INTO product(model, ean, isbn, quantity, stock_status_id, image, manufacturer_id, shipping, price, points, tax_class_id, date_available, weight, weight_class_id, length, width, height, length_class_id, subtract, minimum, sort_order, status, viewed, date_added, date_modified) VALUES('" . $model . "', '" . $ean . "', '" . $isbn . "', '" . $quantity . "', '" . $stock_status_id . "', '" . $image . "', '" . $manufacturer_id . "', '" . $shipping . "', '" . $price . "', '" . $points . "', '" . $tax_class_id . "', '" . $date_available . "', '" . $weight . "', '" . $weight_class_id . "', '" . $length . "', '" . $width . "', '" . $height . "', '" . $length_class_id . "', '" . $subtract . "', '" . $minimum . "', '" . $sort_order . "', '" . $status . "', '" . $viewed . "', NOW(), NOW())";

            if (mysqli_query($dbOpencart, $productQuery)) {

                $product_id = mysqli_insert_id($dbOpencart);
                logger("New product record created successfully (Model: " . $p['isbn'] . "). Last inserted ID is: " . $product_id);

                if ($p['preorder'] == "1") {
                    mysqli_query($dbOpencart, "INSERT IGNORE INTO product_to_category(product_id, category_id) VALUES ('" . $product_id . "', '7930')");
                }

                foreach ($productCategory as $pc) {

                    mysqli_query($dbOpencart, "INSERT IGNORE INTO product_to_category(product_id, category_id) VALUES ('" . $product_id . "', '" . $pc . "')");
                }

                mysqli_query($dbOpencart, "INSERT INTO product_to_store(product_id, store_id) VALUES('" . $product_id . "', '0')");

                if ($p['format'] != '') {
                    mysqli_query($dbOpencart, "INSERT INTO product_attribute(product_id, attribute_id, language_id, text) VALUES('" . $product_id . "', '5', 1, '" . $p["format"] . "')");
                }

                if ($p['pages'] != '') {
                    mysqli_query($dbOpencart, "INSERT INTO product_attribute(product_id, attribute_id, language_id, text) VALUES('" . $product_id . "', '4', 1, '" . $p["pages"] . "')");
                }

                if ($p['author'] != '') {

                    mysqli_query($dbOpencart, "INSERT INTO product_attribute(product_id, attribute_id, language_id, text) VALUES('" . $product_id . "', '16', 1, '" . $p["author"] . "')");

                    echo "Find author category : " . strtoupper($p['author']) . PHP_EOL;

                    $getAuthorCategory = mysqli_query($dbOpencart, "SELECT category_id FROM category_description WHERE UPPER(name) = '" . strtoupper($p['author']) . "' LIMIT 1");

                    $g = mysqli_fetch_assoc($getAuthorCategory);

                    if ($g) {
                        if (count($g) > 0) {

                            echo "Author category found (ID " . $g['category_id'] . ")" . PHP_EOL;
                            mysqli_query($dbOpencart, 'INSERT INTO product_to_category(product_id, category_id) VALUES("' . $product_id . '", "' . $g['category_id'] . '")');
                        }
                    } else {

                        echo "Author category NOT FOUND (" . $p['author'] . ")" . PHP_EOL;
                    }
                }

                if ($p['title'] != '') {

                    $description = mysqli_real_escape_string($dbOpencart, $p['description']);
                    $title = mysqli_real_escape_string($dbOpencart, $p['title']);
                    $metaTitle = str_replace("'", "", strtolower(str_replace(" ", "-", $title)));

                    mysqli_query($dbOpencart, "INSERT INTO product_description(product_id, language_id, name, description, tag, meta_title, meta_description, meta_keyword) VALUES('" . $product_id . "', 1, '" . $title . "', '" . $description . "', '', '" . $metaTitle . "', '', '')");
                }

                if ($p['image'] != '') {
                    imageProcessor($p['image'], $p['isbn']);
                }

                mysqli_query($dbMicrosite, "DELETE FROM products_upload WHERE isbn = '" . $model . "'");
            } else {
                logger("Error: " . $sql . "<br>" . mysqli_error($dbOpencart));
            }
        } else {

            echo "MCCODE " . $mccode . " is NOT DEFINED YET. Skipping product." . PHP_EOL;
        }
    } else {

        echo "Product " . $p['isbn'] . " exists" . PHP_EOL;

        if ($productCategory) {

            $image = 'products/' . substr($model, -3) . '/' . $model . '.jpg';

            $updateQuery = "UPDATE product SET quantity = '" . $quantity . "', stock_status_id = '" . $stock_status_id . "', image = '" . $image . "' , price = '" . $price . "', status = '" . $status . "' WHERE isbn = '" . $p['isbn'] . "';";

            mysqli_query($dbOpencart, $updateQuery);

            if ($p['title'] != '') {

                $description = mysqli_real_escape_string($dbOpencart, $p['description']);
                $title = mysqli_real_escape_string($dbOpencart, $p['title']);
                $metaTitle = str_replace("'", "", strtolower(str_replace(" ", "-", $title)));

                $updateDescQuery = "INSERT INTO product_description(product_id, language_id, name, description, tag, meta_title, meta_description, meta_keyword) SELECT product_id, 1, '" . $title . "', '" . $description . "', '', '" . $metaTitle . "', '', '' FROM product WHERE isbn = '" . $p['isbn'] . "' ON DUPLICATE KEY UPDATE name = '" . $title . "', description = '" . $description . "'";

                mysqli_query($dbOpencart, $updateDescQuery);
            }

            if ($p['image'] != '') {
                imageProcessor($p['image'], $p['isbn']);
            }

            if ($p['format'] != '') {
                mysqli_query($dbOpencart, "INSERT IGNORE INTO product_attribute(product_id, attribute_id, language_id, text) SELECT product_id, '5', 1, '" . $p["format"] . "' FROM product WHERE isbn = '" . $p['isbn'] . "'");
            }

            if ($p['pages'] != '') {

                mysqli_query($dbOpencart, "INSERT IGNORE INTO product_attribute(product_id, attribute_id, language_id, text) SELECT product_id, '4', 1, '" . $p["pages"] . "' FROM product WHERE isbn = '" . $p['isbn'] . "'");
            }

            if ($p['author'] != '') {
                mysqli_query($dbOpencart, "INSERT IGNORE INTO product_attribute(product_id, attribute_id, language_id, text) SELECT product_id, '16', 1, '" . $p["author"] . "' FROM product WHERE isbn = '" . $p['isbn'] . "'");
            }

            mysqli_query($dbMicrosite, "DELETE FROM products_upload WHERE isbn = '" . $p['isbn'] . "'");

            echo "Product " . $p['isbn'] . " updated successfully" . PHP_EOL;
        } else {

            echo "MCCODE " . $mccode . " is NOT DEFINED YET. Skipping product." . PHP_EOL;
        }
    }
}

function checkProduct($db, $model)
{

    $q = mysqli_query($db, "SELECT product_id FROM product WHERE model = '" . $model . "'");
    $d = mysqli_fetch_assoc($q);

    if ($d) {
        if (count($d) > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function logger($data)
{

    echo date('Y-m-d H:i:s') . ' - ' . $data . PHP_EOL;
}

function imageProcessor($url, $isbn)
{
    $directoryPath = "/var/www/microsite/cron/tempfile/";
    $localFile = $directoryPath . $isbn . '.jpg';
    file_put_contents($localFile, file_get_contents($url));


 
    if ($sftp->put('/var/www/bnbsite/htdocs/image/products/' . substr($isbn, -3) . '/' . $isbn . '.jpg', $localFile, SFTP::SOURCE_LOCAL_FILE)) {
        logger("IMAGE FOR " . $isbn . " UPLOADED SUCCESSFULLY");
    } else {
        logger("IMAGE UPLOAD FOR " . $isbn . " SUCCESSFULLY");
    }
    unlink($localFile);
}

function categoryMapper($mccode)
{

    if ($mccode == 'BKFICTGENER') {
        $cats = [5, 12, 196];
        return $cats;
    } else if ($mccode == 'BKFICTROMAN') {
        $cats = [5, 12, 248];
        return $cats;
    } else if ($mccode == 'BKFICTLITER') {
        $cats = [5, 12, 247];
        return $cats;
    } else if ($mccode == 'BKFICTTHRIL') {
        $cats = [5, 12, 249];
        return $cats;
    } else if ($mccode == 'BKFICTFANTA') {
        $cats = [5, 12, 7925];
        return $cats;
    } else if ($mccode == 'BKFICTCOMIC') {
        $cats = [5, 12, 255];
        return $cats;
    } else if ($mccode == 'BKFICTPOETR') {
        $cats = [5, 12, 197];
        return $cats;
    } else if ($mccode == 'BKFICTLITER') {
        $cats = [5, 12, 247];
        return $cats;
    } else if ($mccode == 'BKFICTGENFI') {
        $cats = [5, 12, 196];
        return $cats;
    } else if ($mccode == 'BKFICTCLASS') {
        $cats = [5, 12, 194];
        return $cats;
    } else if ($mccode == 'BKCHILGENFI') {
        $cats = [5, 11, 191];
        return $cats;
    } else if ($mccode == 'BKCHILTODDL') {
        $cats = [5, 11, 7900];
        return $cats;
    } else if ($mccode == 'BKCHILACTIV') {
        $cats = [5, 11, 190];
        return $cats;
    } else if ($mccode == 'BKCHILSNDBK') {
        $cats = [5, 11, 254];
        return $cats;
    } else if ($mccode == 'BKCHILPICTB') {
        $cats = [5, 11, 246];
        return $cats;
    } else if ($mccode == 'BKCHILREFER') {
        $cats = [5, 11, 7917];
        return $cats;
    } else if ($mccode == 'BKCHILRELIG') {
        $cats = [5, 11, 251];
        return $cats;
    } else if ($mccode == 'BKCHILCOMIC') {
        $cats = [5, 11, 192];
        return $cats;
    } else if ($mccode == 'BKCHILTEENS') {
        $cats = [5, 11, 193];
        return $cats;
    } else if ($mccode == 'BKCHILASSES' || $mccode == 'BKCHILBIBLE' || $mccode == 'BKCHILCLASS' || $mccode == 'BKCHILYOUAD') {
        $cats = [5, 11];
        return $cats;
    } else if ($mccode == 'BKNONFBIOGR') {
        $cats = [5, 15, 211];
        return $cats;
    } else if ($mccode == 'BKNONFHISTO') {
        $cats = [5, 15, 217];
        return $cats;
    } else if ($mccode == 'BKNONFSELFD') {
        $cats = [5, 15, 223];
        return $cats;
    } else if ($mccode == 'BKNONFBUSNS') {
        $cats = [5, 15, 212];
        return $cats;
    } else if ($mccode == 'BKNONFPHILO') {
        $cats = [5, 15, 219];
        return $cats;
    } else if ($mccode == 'BKNONFPSYCH') {
        $cats = [5, 15, 220];
        return $cats;
    } else if ($mccode == 'BKNONFFAMIL') {
        $cats = [5, 15, 215];
        return $cats;
    } else if ($mccode == 'BKNONFREFER') {
        $cats = [5, 15, 221];
        return $cats;
    } else if ($mccode == 'BKNONFCURAF') {
        $cats = [5, 15, 214];
        return $cats;
    } else if ($mccode == 'BKNONFHEFIT') {
        $cats = [5, 15, 216];
        return $cats;
    } else if ($mccode == 'BKNONFLANGD') {
        $cats = [5, 15, 218];
        return $cats;
    } else if ($mccode == 'BKILLUHOUME') {
        $cats = [5, 13, 202];
        return $cats;
    } else if ($mccode == 'BKILLUCRAFT') {
        $cats = [5, 13, 200];
        return $cats;
    } else if ($mccode == 'BKILLUARTSS') {
        $cats = [5, 13, 198];
        return $cats;
    } else if ($mccode == 'BKILLUBEFAS') {
        $cats = [5, 13, 253];
        return $cats;
    } else if ($mccode == 'BKILLUCOOKI') {
        $cats = [5, 13, 199];
        return $cats;
    } else if ($mccode == 'BKILLUTRAVE') {
        $cats = [5, 13, 209];
        return $cats;
    } else if ($mccode == 'BKILLUPHOTO') {
        $cats = [5, 13, 207];
        return $cats;
    } else if ($mccode == 'BKILLUARTDE') {
        $cats = [5, 13, 198];
        return $cats;
    } else if ($mccode == 'BKILLUREFER') {
        $cats = [5, 13, 7921];
        return $cats;
    } else if ($mccode == 'BKILLUARCHI') {
        $cats = [5, 13, 189];
        return $cats;
    } else if ($mccode == 'BKILLUPETSS') {
        $cats = [5, 13, 206];
        return $cats;
    } else if ($mccode == 'BKILLUMUSIC') {
        $cats = [5, 13, 203];
        return $cats;
    } else if ($mccode == 'BKILLUENTER' || $mccode == 'BKILLUMAPSS' || $mccode == 'BKILLUSPORT') {
        $cats = [5, 13];
        return $cats;
    } else if ($mccode == 'BKBHSAFIKSI' || $mccode == 'BKBHSABISNS' || $mccode == 'BKBHSAKOMIK' || $mccode == 'BKBHSAPDIRI' || $mccode == 'BKBHSAAGAMA' || $mccode == 'BKBHSANONFI' || $mccode == 'BKBHSAAANAK' || $mccode == 'BKBHSAPLJRN' || $mccode == 'BKBHSAKAMUS' || $mccode == 'BKBHSASEHAT' || $mccode == 'BKBHSAMASAK' || $mccode == 'BKBHSAISLAM' || $mccode == 'BKBHSAHOBBI' || $mccode == 'BKBHSABIOGF' || $mccode == 'BKBHSARUMAH') {
        $cats = [5, 7950];
        return $cats;
    } else if ($mccode == 'BKTEXTMEDIC') {
        $cats = [5, 17, 231];
        return $cats;
    } else if ($mccode == 'BKTEXTEDUCT') {
        $cats = [5, 17, 228];
        return $cats;
    } else if ($mccode == 'BKTEXTMATHS') {
        $cats = [5, 17, 230];
        return $cats;
    } else if ($mccode == 'BKTEXTLANGD') {
        $cats = [5, 17, 229];
        return $cats;
    } else if ($mccode == 'BKTEXTMARKT' || $mccode == 'BKTEXTBUSNS' || $mccode == 'BKTEXTMANAG' || $mccode == 'BKTEXTPSYCH' || $mccode == 'BKTEXTFINAN' || $mccode == 'BKTEXTTOURS' || $mccode == 'BKTEXTCOMPI' || $mccode == 'BKTEXTBIOLO' || $mccode == 'BKTEXTACCNT' || $mccode == 'BKTEXTENGIN' || $mccode == 'BKTEXTHUMRE' || $mccode == 'BKTEXTCOMMS' || $mccode == 'BKTEXTARCHI') {
        $cats = [5, 17];
        return $cats;
    } else if ($mccode == 'BKRELICHRIS') {
        $cats = [5, 16, 250];
        return $cats;
    } else if ($mccode == 'BKRELIISLAM') {
        $cats = [5, 16, 227];
        return $cats;
    } else if ($mccode == 'BKRELIBIBLE') {
        $cats = [5, 16, 226];
        return $cats;
    } else if ($mccode == 'BKASIAINDCL' || $mccode == 'BKASIAASNCO' || $mccode == 'BKASIABIOGF') {
        $cats = [5, 10];
        return $cats;
    } else if ($mccode == 'NBTOYSEDUTS') {
        $cats = [7, 7978];
        return $cats;
    } else if ($mccode == 'NBFANGGIFTS') {
        $cats = [7, 7979];
        return $cats;
    } else if ($mccode == 'NBTOYSBOYTS' || $mccode == 'NBTOYSGRLTS' || $mccode == 'NBTOYSBBYTS' || $mccode == 'NBMETOTLTRS' || $mccode == 'NBGAMEINTER' || $mccode == 'NBCARDMNYHD' || $mccode == 'NBSTATACCRS' || $mccode == 'NBTOYSEDUCT') {
        $cats = [7];
        return $cats;
    } else if ($mccode == 'NBFANGFANCY') {
        $cats = [7, 7979];
        return $cats;
    } else if ($mccode == 'NBELECACCRS') {
        $cats = [8050, 8051];
        return $cats;
    } else if ($mccode == 'NBELECBATRS' || $mccode == 'NBELECMLMDA') {
        $cats = [8050];
        return $cats;
    } else if ($mccode == 'NBGAMEBRDGM' || $mccode == 'NBGAMECRDGM') {
        $cats = [7, 8038];
        return $cats;
    } else if ($mccode == 'NBSTATWRINS') {
        $cats = [25, 182];
        return $cats;
    } else if ($mccode == 'NBMETOMDCNE') {
        $cats = [7962];
        return $cats;
    } else {
        return false;
    }
}
