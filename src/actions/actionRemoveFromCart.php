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

removeFromCart($itemId);



function removeFromCart($itemId) {
    $db = getDatabaseConnection();
    $session = new Session();
    if(!$session->isLoggedIn()){
        http_response_code(401);
        echo json_encode(array("error" => "User not logged in"));
        exit();
    }
    error_log($itemId,0);
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
    $UserShoppingCart = ShoppingCart::getShoppingCartByUserId($db, $session->getId());
    if($UserShoppingCart == null){
            http_response_code(500);
            echo json_encode(array("error" => "Item not in cart"));
        
            exit();
    }
    else{
        $success = ShoppingCart::removeItemFromCart($db, $session->getId(),$itemId);
        if($success){
            http_response_code(200);
            $newTotal = ShoppingCart::getTotal($db, $session->getId());
            echo json_encode(array("message" => "Item removed from cart","newTotal" => $newTotal));
            
        }else{
            http_response_code(500);
            echo json_encode(array("error" => "Error removing item from cart"));
        
        }
        exit();
    }
    
}
?>