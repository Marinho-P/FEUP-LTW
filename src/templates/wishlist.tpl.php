<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/items.class.php');
require_once(__DIR__ . '/../database/wishlist.class.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/category.class.php');
require_once(__DIR__ . '/../database/shoppingCart.class.php');
function drawWishlist($session)
{
    $db = getDatabaseConnection();

    $wishlist = Wishlist::getWishlistByUserId($db,$session->getId());
    if($wishlist == null){
        echo "<section class='wishlist'>";
        echo "<h1>Wishlist is empty</h1>";
        echo "</section>";
        
    }
    else{
        echo "<section class='wishlist' id='wishlist'>";
        foreach($wishlist as $item){
            $product = Item::getProductById($db, $item->getProductId());
            $category = Category::extractCategoryWithcategoryId($db, $product->getCategoryId()) ;
            $shoppingCart = ShoppingCart::getItemInUserShoppingCart($db, $session->getId(),$product->getItemId());
            $remainingStock = $product->getStock();
            echo "<div class='wishlistItem' id='wishlistItem{$item->getProductId()}'>
                <img src='../images/items/item{$product->getItemId()}-0.png' alt='mainImg'>
                <div class='item-info'>
                <h3>{$product->getName()}</h3>
                <p>Price: {$product->getPrice()}</p>
                <p>Stock: {$product->getStock()}</p>";
            echo "</div>";
            if($category != null) echo "<p>Category: {$category->getName()}</p>";
            echo "<p>Owner: {$product->getOwnerId()}</p>
                <div class='item-buttons'>
                <button class='RemoveFromWishlistButton' id='RemoveFromWishlistButton'  data-item-id='{$product->getItemId()}'>Remove from wishlist</button>";
            if($shoppingCart == null){
                echo "<input type='number' class='Quantity' id='Quantity{$product->getItemId()}' value='1' min='1' max='{$product->getStock()}'>";
            }else{
                $remainingStock = $product->getStock() - $shoppingCart->getQuantity();
                if($remainingStock <= 0){
                    echo "<input type='number' id='Quantity{$product->getItemId()}'  min='1' max='{$remainingStock}' value='1' disabled>";
                }
                else{
                    echo "<input type='number' id='Quantity{$product->getItemId()}'  min='1' max='{$remainingStock}' value='1'>";
                }
            }
            
            if($remainingStock <= 0){
                echo "<button class='errorButton' id='errorButton' disabled><i class='fa-solid fa-cart-plus'></i>Out of stock</button>";
            }
            elseif($product->getUnavailable() == 1){
                echo "<button class='errorButton' id='errorButton' disabled><i class='fa-solid fa-cart-plus'></i>Unavailable</button>";
            }
            else{
                
                echo "<button class='AddToCartButton' id='AddToCartButton{$product->getItemId()}' data-item-id={$product->getItemId()}><i class='fa-solid fa-cart-plus'></i>Add to cart</button>";
            }
            echo "</div>";
            
            echo "</div>";
            
        }
        echo "</section>";
    }
    
}
    
    


?>