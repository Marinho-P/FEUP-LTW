<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/items.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/brand.class.php');
require_once(__DIR__ . '/../database/model.class.php');
require_once(__DIR__ . '/../database/category.class.php');
require_once(__DIR__ . '/../database/size.class.php');
require_once(__DIR__ . '/../database/condition.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/itemReview.class.php');
require_once(__DIR__ . '/../database/wishlist.class.php');
require_once(__DIR__ . '/../database/shoppingCart.class.php');

function drawItemPage($session,$itemId) {
    

    $DB = getDatabaseConnection();
    $newItem = Item::getProductById($DB, $itemId);
    // TODO: Change curency to be dynamic
    // TODO: Add hrefs where needed
    $currency = '€';

    if($newItem){
        $newBrand = ($newItem->getBrandId() == null) ? null :Brand:: extractBrandWithBrandId($DB, $newItem->getBrandId());
        $newModel =($newItem->getModelId() == null) ? null : Model:: extractModelWithModelId($DB, $newItem->getModelId());
        $newCategory = ($newItem->getCategoryId() == null) ? null : Category:: extractCategoryWithCategoryId($DB, $newItem->getCategoryId());
        $newSize = ($newItem->getSizeId() == null) ? null :  Size::getSizeById($DB, $newItem->getSizeId());
        $newCondition =($newItem->getConditionId() == null) ? null: Condition::extractConditionWithConditionId($DB, $newItem->getConditionId());
        $newWishlist = ($session->isLoggedIn() ) ? Wishlist::getItemInUserWishlist($DB, $session->getId(),$itemId) : null;
        $newShoppingCart = ($session->isLoggedIn() ) ? ShoppingCart::getItemInUserShoppingCart($DB, $session->getId(),$itemId) : null;
        $newOwner = User::getUserWithId($DB, $newItem->getOwnerId()); 
        echo "<h3>{$newItem->getName()}</h3>
        <section class='itemInfo'>
        <div class='item_info'>
            <div class='item_details'>
                <ol>

                
                    <li><strong>Name:   </strong> {$newItem->getName()}</li>
                    <li><strong>Price per piece:   </strong>{$newItem->getPrice()}{$currency}</li> 
                    <li><strong>Stock:   </strong>{$newItem->getStock()}</li> 
                    <li><strong>Owner:   </strong>{$newOwner->getName()}</li>";
                    if($newBrand != null){
                        echo "<li><strong>Brand:   </strong>{$newBrand->getName()}</li>";
                    }
                    if($newModel != null){
                        echo "<li><strong>Model:   </strong>{$newModel->getName()}</li>";
                    }
                    if($newSize != null){
                        echo "<li><strong>Size:   </strong>{$newSize->getSizeName()}</li>";
                    }
                    if($newCondition != null){
                        echo "<li><strong>Condition:   </strong>{$newCondition->getName()}</li>";
                    }
                    if($newCategory != null){
                        echo "<li><strong>Category:   </strong>{$newCategory->getName()}</li>";
                    }
                    echo "
                </ol>
                <ol>
                    <li><h2>Description:</h2></li>
                    <li>{$newItem->getDescription()}</li>
                </ol>
            </div>
        </div>
        <div class='buttons'>";
            // wishlist button logic
            if(!$session->isLoggedIn()){
            echo "<button class='errorButton' id='errorButton' disabled><i class='fa-regular fa-heart'></i>Log in to wishlist</button>";
            }
            elseif( $newItem->getOwnerId() == $session->getId()){
                echo "<button class='errorButton' id='errorButton' disabled><i class='fa-regular fa-heart'></i>Can't wishlist your own item</button>";
            }
            elseif($newWishlist == null){
                echo "<button class='WishlistButton' id='WishlistButton' data-item-id={$itemId}><i class='fa-regular fa-heart'></i>Add to Wishlist</button>"; 
            }else{
                echo "<button class='WishlistButton' id='WishlistButton'data-item-id={$itemId} ><i class='fa-regular fa-heart'></i>Remove from Wishlist</button>";
            }
            $remainingStock = $newItem->getStock();
            // input quantity logic
            if($newShoppingCart != null){
                $remainingStock = $newItem->getStock() - $newShoppingCart->getQuantity();
                if($remainingStock <= 0){
                    echo "<input type='number' id='Quantity' name='Quantity' min='1' max='{$remainingStock}' value='1' disabled>";
                }
                else{
                    echo "<input type='number' id='Quantity' name='Quantity' min='1' max='{$remainingStock}' value='1'>";
                }
            }
            else{
                echo "<input type='number' id='Quantity' name='Quantity' min='1' max='{$newItem->getStock()}' value='1'>";

            }
            // add to cart button logic
            if($remainingStock <= 0){
                echo "<button class='errorButton' id='errorButton' disabled><i class='fa-solid fa-cart-plus'></i>Out of stock</button>";
            }
            elseif($newItem->getUnavailable() == 1){
                echo "<button class='errorButton' id='errorButton' disabled><i class='fa-solid fa-cart-plus'></i>Unavailable</button>";
            }
            elseif(!$session->isLoggedIn()){
                echo "<button class='errorButton' id='errorButton' disabled><i class='fa-solid fa-cart-plus'></i>Log in to buy</button>";
            }
            elseif($newItem->getOwnerId() == $session->getId()){
                echo "<button class='errorButton' id='errorButton' disabled><i class='fa-solid fa-cart-plus'></i>Can't buy your own item</button>";
            }
            else{
                
                echo "<button class='AddToCartButton' id='AddToCartButton' data-item-id={$itemId}><i class='fa-solid fa-cart-plus'></i>Add to cart</button>";
            }


        echo"</div>";
        echo "<div class='notify-user' id='notify'>
        <button class='notifyButton' id='notifyButton' data-item-id={$itemId}'>
        <i class='fa-solid fa-bell'>
        </i>Notify User
        </button>
        </div>
        ";
        echo "</section>";
    }
    else{
        echo 'Item not found.';
    }
    
    

   
}
function drawItemReviews($itemId)  {
    $DB = getDatabaseConnection();
    $reviews = ItemReview::getReviewsByReviewedId($DB, $itemId);

    if($reviews){
        echo "<section class='reviews'>";

        echo "<h2>Reviews</h2>";

        echo "<div class='review-boxes'>";
        foreach($reviews as $review){
            $reviewer = User::getUserWithId($DB, $review->getReviewerId());
            echo "<div class='review'>
            <div class='reviewer'>
                <img src='../images/user{$reviewer->getId()}.png' alt='Profile Picture'>
                <p>{$reviewer->getName()}</p>
            </div>
            <div class='review_details'>
                <ol>
                    <li><strong>Stars:   </strong>{$review->getStarsNumber()}</li>
                    <li><strong>Review:   </strong>{$review->getDescription()}</li>
                </ol>
            </div>
        </div>";
        }
        echo "</div>";
        echo "</section>";
    }
    else{
        echo 'No reviews found.';
    }
    
}
function drawItemImages($itemId) {
    $DB = getDatabaseConnection();
    $newItem = Item::getProductById($DB, $itemId);
    if($newItem){
        echo "<section class='itemImages'>
        <div class='main-image'>
        <img id='mainImg' src='../images/items/item{$newItem->getItemId()}-0.png' alt='Main Image'>
        </div>
        <div class='thumbnails'>";

        for($i = 0; $i < $newItem->getImageCount() ; $i++){
            $imagePath = "../images/items/item{$newItem->getItemId()}-{$i}.png";
            if(file_exists($imagePath)){
                echo "<img src='$imagePath'onclick='changeImage(this)' alt='Thumbnail {$i}'>";
                

            }
        }
        echo "</div>
        </section>";
    }
}

?>