<?php
require_once(__DIR__ . '/../database/wishlist.class.php');
require_once(__DIR__ . '/../database/items.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/session.php');

// Check if the itemId is received via POST
if (isset($_POST['itemId']) && filter_var($_POST['itemId'], FILTER_VALIDATE_INT)) {
    $itemId = $_POST['itemId'];
    
} else {
    $itemId = null;
}

addToWishlist($itemId);



function addToWishlist($itemId) {
    $db = getDatabaseConnection();
    $session = new Session();
    if(!$session->isLoggedIn()){
        http_response_code(401);
        echo json_encode(array("error" => "User not logged in"));
        exit();
    }
    if($itemId == null){
        http_response_code(400);
        echo json_encode(array("error" => "Missing itemId parameter"));
        exit();
    }
    $item = Item::getProductById($db, $itemId);
    if($item == null){
        http_response_code(400);
        echo json_encode(array("error" => "Invalid item"));
        exit();
    }
    if($item->getOwnerId() == $session->getId()){
        http_response_code(400);
        echo json_encode(array("error" => "Cannot add own item to wishlist"));
        exit();
    }
    $UserWishlist = Wishlist::getItemInUserWishlist($db, $session->getId(),$itemId);
    if($UserWishlist != null){
            http_response_code(500);
            echo json_encode(array("error" => "Item already in wishlist"));
        
            exit();
    }
    else{
        $success = Wishlist::addItemToWishlist($db, $session->getId(),$itemId);
        if($success){
            http_response_code(200);
            echo json_encode(array("message" => "Item added to wishlist"));
            
        }else{
            http_response_code(500);
            echo json_encode(array("error" => "Error adding item to wishlist"));
        
        }
        exit();
    }
    
}
?>