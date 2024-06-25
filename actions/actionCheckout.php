<?php
    declare(strict_types=1);

require_once(__DIR__ . '/../database/shoppingCart.class.php');
require_once(__DIR__ . '/../database/items.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/shippingOrder.php');
require_once(__DIR__ . '/../database/itemsToSend.class.php');
require_once(__DIR__ . '/../database/items.class.php');
if(isset($_POST['Address'])){
    $AddressInfo = $_POST['Address'];
}else{
    $AddressInfo = null;
}
if(isset($_POST['City'])){
    $CityInfo = $_POST['City'];
}else{
    $CityInfo = null;
}
if(isset($_POST['PostalCode'])){
    $PostalCodeInfo = $_POST['PostalCode'];
}else{
    $PostalCodeInfo = null;
}
if(isset($_POST['Country'])){
    $CountryInfo = $_POST['Country'];
}else{  
    $CountryInfo = null;
}   
if(isset($_POST['CardNumber'])){
    $CardNumberInfo = $_POST['CardNumber'];     
}else{
    $CardNumberInfo = null;
}
if(isset($_POST['ExpirationDate'])){
    $ExpirationDateInfo = $_POST['ExpirationDate'];
}else{
    $ExpirationDateInfo = null;
}
if(isset($_POST['CVV'])){
    $CVVInfo = $_POST['CVV'];
}else{
    $CVVInfo = null;
}

Checkout($AddressInfo, $CityInfo, $PostalCodeInfo, $CountryInfo, $CardNumberInfo, $ExpirationDateInfo, $CVVInfo);

$coupon = $_POST['coupon'];

if(!empty($coupon)){
    Coupon::deleteCouponIfExists($DB, $coupon);
}


function Checkout($AddressInfo, $CityInfo, $PostalCodeInfo, $CountryInfo, $CardNumberInfo, $ExpirationDateInfo, $CVVInfo) {
    $db = getDatabaseConnection();
    $session = new Session();
    if(!$session->isLoggedIn()){
        http_response_code(401);
        echo json_encode(array("error" => "User not logged in"));
        exit();
    }
    if($AddressInfo == null || $CityInfo == null || $PostalCodeInfo == null || $CountryInfo == null || $CardNumberInfo == null || $ExpirationDateInfo == null || $CVVInfo == null){
        http_response_code(400);
        echo json_encode(array("error" => "Missing parameter"));
        exit();
    }
    if(!preg_match('/[a-zA-Zºª0-9çÇ. ,\-]{3,50}/', $AddressInfo)){
        http_response_code(400);
        echo json_encode(array("error" => "Invalid Address"));
        exit();
    }
    if(!preg_match('/[a-zA-Zºª0-9çÇ. ,\-]{3,50}/', $CityInfo)){
        http_response_code(400);
        echo json_encode(array("error" => "Invalid City"));
        exit();
    }
    if(!preg_match('/[0-9]{4}-[0-9]{3}/', $PostalCodeInfo)){
        http_response_code(400);
        echo json_encode(array("error" => "Invalid Postal Code"));
        exit();
    }
    if(!preg_match('/[a-zA-Zºª0-9çÇ. ,\-]{3,50}/', $CountryInfo)){
        http_response_code(400);
        echo json_encode(array("error" => "Invalid Country"));
        exit();
    }
    if(!preg_match('/[0-9]{13,16}/', $CardNumberInfo)){
        http_response_code(400);
        echo json_encode(array("error" => "Invalid Card Number"));
        exit();
    }
    if(!preg_match('/[0-9]{2}\/[0-9]{2}/', $ExpirationDateInfo)){
        http_response_code(400);
        echo json_encode(array("error" => "Invalid Expiration Date"));
        exit();
    }
    if(!preg_match('/[0-9]{3}/', $CVVInfo)){
        http_response_code(400);
        echo json_encode(array("error" => "Invalid CVV"));
        exit();
    }
    $UserShoppingCart = ShoppingCart::getShoppingCartByUserId($db, $session->getId());
    // check stock
    foreach($UserShoppingCart as $item){
        $product = Item::getProductById($db, $item->getProductId());
        if($product->getStock() < $item->getQuantity()){
            http_response_code(400);
            echo json_encode(array("error" => "Not enough stock for item: ".$product->getName()));
            exit();
        }
    }
    // iterate through each item in the shopping cart
    foreach($UserShoppingCart as $item){
        // get item from db
        $item_db = Item::getProductById($db, $item->getProductId());
        // remove from stock
        $product = Item::UpdateStock($db, $item->getProductId(), $item->getQuantity());
        if($product == false){
            http_response_code(500);
            echo json_encode(array("error" => "Error updating stock for item: {$item->getProductId()}"));
            exit();
        }
        // create order if it doesn't exist
        $orderId = ShippingOrder::getOrderbyBuyerIdAndOwnerId($db, $session->getId(), $item_db->getOwnerId());
        if(!$orderId){
            $orderId = ShippingOrder::createShippingOrder($db, $item_db->getOwnerId(), $session->getId(), $AddressInfo, $CityInfo, $PostalCodeInfo, $CountryInfo);
            if($orderId == false){
                http_response_code(500);
                echo json_encode(array("error" => "Error creating order"));
                exit();
            }
        }
        else{
            $orderId = $orderId->getOrderId();
        }
        // create item to send if not exists, else update quantity
        $itemToSend = ItemsToSend::getItemsToSendByOrderIdAndItemId($db, $orderId, $item->getProductId());
        if($itemToSend){
            $itemToSend = ItemsToSend::UpdateQuantity($db,$orderId,$item->getProductId(),$itemToSend->getQuantity() + $item->getQuantity());
            if($itemToSend == false){
                http_response_code(500);
                echo json_encode(array("error" => "Error updating quantity for item: {$item->getName()}"));
                exit();
            }
        }
        else{
            $itemToSend = ItemsToSend::createItemToSend($db, $orderId, $item->getProductId(), $item->getQuantity());
            if(!$itemToSend){
            http_response_code(500);
            echo json_encode(array("error" => "Error creating item to send for item: {$item->getName()}"));
            exit();
            }
        }
        

    }
    // delete shopping cart
    $shoppingCart = ShoppingCart::deleteShoppingCart($db, $session->getId());
    if($shoppingCart == false){
        http_response_code(500);
        echo json_encode(array("error" => "Error deleting shopping cart"));
        
    }
    else{
        http_response_code(200);    
        echo json_encode(array("message" => "Order created successfully", "orderId" => $orderId));
    }
    exit();
    
    
    
}
?>