<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/userReview.class.php');
require_once(__DIR__ . '/../database/brand.class.php');
require_once(__DIR__ . '/../database/category.class.php');
require_once(__DIR__ . '/../database/size.class.php');
require_once(__DIR__ . '/../database/condition.class.php');
require_once(__DIR__ . '/../database/items.class.php');


function drawAddItem($userId)
{
    $DB = getDatabaseConnection();
    $newuser = User::getUserWithId($DB,$userId);
    if($newuser){
        $brands = Brand::getAllBrands($DB);
        $categories = Category::getAllCategories($DB);
        $sizes = Size::getSizes($DB);
        $conditions = Condition::getConditions($DB);
        echo "<section class='add_item'>
        <h2>Add Item</h2>
        <form action='../actions/actionAddItem.php' method='post' enctype='multipart/form-data'>

            <label for='name'>Name:</label>
            <input type='text' id='name' name='name' placeholder='Choose a name for your item' required><br>

            <label for='brand'>Brand:</label>
            <select id='brand' name='brand'><option selected value=''> -- select an option -- </option>";
            foreach($brands as $brand){
                echo "<option value='{$brand->getBrandId()}'>{$brand->getName()}</option>";
            }
            echo "</select><br>

            <label for='model'>Model:</label>
            <select id='model' name='model'><option disabled selected value> -- choose a brand first -- </option></select><br>
    
            <label for='category'>Category:</label>
            <select id='category' name='category' required><option selected value =''> -- select an option -- </option>";
            foreach($categories as $category){
                echo "<option value='{$category->getCategoryId()}'>{$category->getName()}</option>";
            }
            echo "</select><br>

            <label for='size'>Size:</label>
            <select id='size' name='size'><option selected value=''> -- select an option -- </option>";
            foreach($sizes as $size){
                echo "<option value='{$size->getSizeId()}'>{$size->getSizeName()}</option>";
            }
            echo "</select><br>

    
            <label for='condition'>Condition:</label>
            <select id='condition' name='condition'><option selected value=''> -- select an option -- </option>";
            foreach($conditions as $condition){
                echo "<option value='{$condition->getConditionId()}'>{$condition->getName()}</option>";
            }
            echo "</select><br>

            <label for='price'>One piece price:</label>
            <input type='number' id='price' name='price' required min='0.01' step='any'><br>
    
            <label for='quantity'>Quantity:</label>
            <input type='number' id='quantity' name='quantity' required min='1' step='1'><br>

            <label for='description'>Description:</label>
            <textarea id='description' name='description' placeholder='Write a description about your item.'></textarea><br>

            <label for='item_images'>Images:</label>
            <input type='file' name='files[]' accept='image/*' multiple required><br>

            <button type='submit'>Add Item</button>
        </form>
    </section>";
    }else{
        echo "User not found.";
    }
}


?>