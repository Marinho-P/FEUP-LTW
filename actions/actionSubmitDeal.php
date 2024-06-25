<?php


declare(strict_types=1);

require_once(__DIR__ . '/../database/coupon.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/inputVerification.php');
require_once(__DIR__ . '/../database/user.class.php');


$session = new Session();

if(!$session->isLoggedIn()){
    header("Location: ../pages/login.php");
    exit();
}

$DB = getDatabaseConnection();

$data = json_decode(file_get_contents('php://input'), true);

$sellerId = $session->getId();
$buyerId = (int)$data['buyerId'];
$items = $data['items'];
$discount = (float)$data['discount'];

$coupon = Coupon::addCouponToDB($DB, $sellerId, $buyerId, $discount, json_encode($items));

if($coupon != null){
    echo json_encode(array('success' => true, 'coupon' => $coupon));
}else{
    echo json_encode(['success' => false]);
}
