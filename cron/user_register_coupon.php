<?php

$db = mysqli_connect("localhost", "", "", "", "3306");
$sendgrid_key = "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";
$sendgrid_url = "https://api.sendgrid.com/v3/";

$users = mysqli_query($db, "SELECT customer_id, firstname, lastname, email FROM customer WHERE status = 1 AND customer_id NOT IN (SELECT customer_id FROM registration_coupon) AND date_added > '2021-05-01 00:00:00' ORDER BY date_added DESC");

$valid_until = date('Y-m-d');
$valid_until = date('Y-m-d', strtotime("$valid_until + 7 day"));

while ($user = mysqli_fetch_assoc($users)) {

    // print_r($user);

    $coupon_code = generateRandomString(10);
    $user_fullname = $user['firstname'] . ' ' . $user['lastname'];
    $customer_id = $user['customer_id'];

    mysqli_query($db, "INSERT INTO coupon(name, code, type, discount, logged, shipping, total, date_start, date_end, uses_total, uses_customer, status, date_added) VALUES('Welcome Voucher', '" . $coupon_code . "', 'P', 10, 1, 1, 300000, CURDATE(), CURDATE() + INTERVAL 7 DAY, 1, 1, 0, NOW())");

    mysqli_query($db, "INSERT INTO registration_coupon(coupon_id, customer_id) VALUES(0, '".$customer_id."')");
    
    
    // Sendgrid Mailer API
    $mail_body = "{\"personalizations\":[{\"to\":[{\"email\":\"" . $user['email'] . "\",\"name\":\"" . $user_fullname . "\"}]},{\"from\":{\"email\":\"customercare@booksbeyond.co.id\",\"name\":\"Books&Beyond Customer Care\"},\"to\":[{\"email\":\"" . $user['email'] . "\",\"name\":\"" . $user_fullname . "\"}]}],\"from\":{\"email\":\"customercare@booksbeyond.co.id\",\"name\":\"Books&Beyond Customer Care\"},\"reply_to\":{\"email\":\"customercare@booksbeyond.co.id\",\"name\":\"Books&Beyond Customer Care\"},\"subject\":\"Welcome Voucher for " . $user_fullname . "\",\"content\":[{\"type\":\"text/html\",\"value\":\"<p>Dear " . $user_fullname . "!</p><p>Thank you for registering at Books&Beyond online website. Enjoy 10% disc for your first purchase using by redeem the voucher below. Happy shopping!</p><br /><br /><h2>" . $coupon_code . "</h2><br /><br /><p>Voucher valid until " . $valid_until . " with minimum purchase of IDR 300.000</p><br /><br />Books&Beyond Customer Care<br /><br /><img src='cid:bnblogo'/>\"}], \"attachments\":[{\"type\":\"image/png\",\"filename\":\"bnblogo.png\",\"disposition\":\"inline\",\"content_id\":\"bnblogo\", \"content\":\"iVBORw0KGgoAAAANSUhEUgAAAQUAAAAoCAYAAAD30+/NAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA2JpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0idXVpZDo1RTcyRDQ0MEYyNjNERTExOENCMjlDMDMyQkUzRUNFMiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDozOUJBNjI3NEVEREUxMUUxODhFMUE4NDM2QTYzRTREQiIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDozOUJBNjI3M0VEREUxMUUxODhFMUE4NDM2QTYzRTREQiIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M2IChNYWNpbnRvc2gpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6Rjc3RjExNzQwNzIwNjgxMTgwODM4OEEyMzFGMTRCQ0UiIHN0UmVmOmRvY3VtZW50SUQ9InV1aWQ6NUU3MkQ0NDBGMjYzREUxMThDQjI5QzAzMkJFM0VDRTIiLz4gPC9yZGY6RGVzY3JpcHRpb24+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\"}]}";

    $ch = curl_init($sendgrid_url . 'mail/send');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $sendgrid_key));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $mail_body);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    curl_close($ch);

    print_r($result);

    echo "Email sent to " . $user['email'] . PHP_EOL;
}

function generateRandomString($length = 10)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
