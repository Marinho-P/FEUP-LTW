<?php


declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/coupon.class.php');
require_once(__DIR__ . '/../database/shoppingCart.class.php');
require_once(__DIR__ . '/../database/items.class.php');

$session = new Session();
if(!$session->isLoggedIn()){
    header('Location: /');
    exit;
}

$DB = getDatabaseConnection();

$couponCode = $_POST['coupon'];
$shoppingCart = ShoppingCart::getShoppingCartByUserId($DB, $session->getId());
$items = [];
$owners = [];
foreach ($shoppingCart as $item) {
    $items[] = [$item->getProductId(), $item->getQuantity()];
    $product = Item::getProductById($DB, $item->getProductId());
    if($product != null){
        $owners[] = $product->getOwnerId();
    }
}

foreach ($owners as $owner) {
    $checkIfCouponExists = Coupon::checkIfCouponExists($DB, $couponCode,(int)$owner, $session->getId());
   
    if($checkIfCouponExists != null){
        break;
    }
}

if($checkIfCouponExists == null){
    echo json_encode(['error' => 'Invalid coupon code']);
    http_response_code(300);
    exit;
}

$preço = 0.0;

foreach ($checkIfCouponExists->items as $id => $item) {
    $couponItemQuantity = $item['quantity'];
    $dinheiro = Item::getPriceById($DB, $id)*$couponItemQuantity;


    $found = false;
    $preço+= $dinheiro;

    foreach ($items as $cartItem) {
        $cartItemId = $cartItem[0];
        $cartItemQuantity = $cartItem[1];

        if ($id === $cartItemId && $cartItemQuantity < $couponItemQuantity) {
            echo json_encode(['error' => 'Coupon not applicable']);
            http_response_code(300);
            exit;
        }
    }
}


$discountedPrice = ((float)$checkIfCouponExists->discount / 100 )* $preço;


error_log((string)$discountedPrice);
$preço =ShoppingCart::getTotal($DB, $session->getId()) - $discountedPrice;


echo json_encode(['total' => round($preço,2), 'discount' =>round( $discountedPrice,2),'code' => $checkIfCouponExists->code]);

http_response_code(200);
