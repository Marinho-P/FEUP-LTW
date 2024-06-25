<?php
require_once(__DIR__ . '/../database/shoppingCart.class.php');
require_once(__DIR__ . '/../database/items.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/session.php');

// Check if the itemId is received via POST
if (isset($_POST['itemId']) && filter_var($_POST['itemId'], FILTER_VALIDATE_INT)) {
    $itemId = $_POST['itemId'];
    
} else {
    $itemId = null;
}
if(isset($_POST['quantity']) && filter_var($_POST['itemId'], FILTER_VALIDATE_INT)){
    $quantity= $_POST['quantity'];
}else{
    $quantity = null;
}
addToShoppingCart($itemId, $quantity);



function addToShoppingCart($itemId, $quantity) {
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
    if($quantity == null){
        http_response_code(400);
        echo json_encode(array("error" => "Missing quantity parameter"));
        exit();
    }
    if($quantity < 1 || $quantity > $item->getStock()){
        http_response_code(400);
        echo json_encode(array("error" => "Invalid quantity"));
        exit();
    }
    if($item->getOwnerId() == $session->getId()){
        http_response_code(400);
        echo json_encode(array("error" => "Cannot add own item to wishlist"));
        exit();
    }
    $UserShoppingCart = ShoppingCart::getItemInUserShoppingCart($db, $session->getId(),$itemId);
    if($UserShoppingCart != null ){
        if( $UserShoppingCart->getQuantity() + $quantity > $item->getStock()){
            http_response_code(400);
            echo json_encode(array("error" => "Quantity exceeds stock"));
            exit();
        }
        $success = ShoppingCart::updateQuantityInShoppingCartItem($db, $session->getId(),$itemId,$UserShoppingCart->getQuantity() + $quantity);
        if(!$success){
            http_response_code(500);
            echo json_encode(array("error" => "Error creating wishlist"));
            
        }
        else{
            http_response_code(200);
            http_response_code(200);
            echo json_encode(array("message" => "Item added to wishlist",
            "remainingStock" => $item->getStock() - $UserShoppingCart->getQuantity()- $quantity));

        }
        exit();
    }
    else{
        $success = ShoppingCart::addItemToShoppingCart($db, $session->getId(),$itemId, $quantity);
        if($success){
            http_response_code(200);
            echo json_encode(array("message" => "Item added to wishlist",
        "remainingStock" => $item->getStock() - $quantity));
            
        }else{
            http_response_code(500);
            echo json_encode(array("error" => "Error adding item to wishlist"));
        
        }
        exit();
    }
    
}
?>