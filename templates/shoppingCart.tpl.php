<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/items.class.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/category.class.php');
require_once(__DIR__ . '/../database/shoppingCart.class.php');
function drawShoppingCart($session)
{
    //TODO: Add currency
    $db = getDatabaseConnection();
    $total = 0;
    $shoppingCart = ShoppingCart::getShoppingCartByUserId($db,$session->getId());
    if($shoppingCart == null){

        echo "<section class='shoppingCart' id='shoppingCart' data-nItems='0'>";
        echo "<h1>Your cart is empty</h1>";
        echo "<section/>";
        
    }
    else{
        echo "<section class='shoppingCart' id='shoppingCart' data-nItems='".sizeof($shoppingCart)."'>";
        foreach($shoppingCart as $item){
            $product = Item::getProductById($db, $item->getProductId());
            $entryPrice =$item->getQuantity()* $product->getPrice();

            $total += $entryPrice;
            $category = Category::extractCategoryWithcategoryId($db, $product->getCategoryId()) ;
            echo "<div class='ShoppingCartItem' id='ShoppingCartItem{$item->getProductId()}'>
                <img src='../images/items/item{$product->getItemId()}-0.png' alt='mainImg'>
                <div class='item-info'>
                <h3>{$product->getName()}</h3>
                <p>Price per piece: {$product->getPrice()}</p>
                <p>Stock: {$product->getStock()}</p>
                <p>Quantity in cart: <input type='number' class='QuantityInput' id='Quantity{$product->getItemId()}' data-item-id='{$product->getItemId()}' min='1' max='{$product->getStock()}' value='{$item->getQuantity()}'></p>
                <p id='CombinedPrice{$product->getItemId()}'>Combined price: {$entryPrice}€</p>";
                if($category != null) echo "<p>Category: {$category->getName()}</p>";
                echo "</div>";

            echo "<button class='RemoveFromCartButton' id='RemoveFromCartButton{$product->getItemId()}'  data-item-id='{$product->getItemId()}'>Remove from cart</button>";
            echo "</div>";
        }
        echo "<section class='total'>
        <h3 id= 'Total'>Total: {$total}€</h3>
        <button class='Checkout' id='Checkout'>Checkout</button>
        </section>";
        echo "</section>";
    }
    
    
    
    
}
    
    


?>