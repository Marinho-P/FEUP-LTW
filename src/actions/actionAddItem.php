<?php
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../utils/inputVerification.php');
require_once(__DIR__ . '/../database/items.class.php');
require_once(__DIR__ . '/../database/brand.class.php');
require_once(__DIR__ . '/../database/category.class.php');
require_once(__DIR__ . '/../database/size.class.php');
require_once(__DIR__ . '/../database/condition.class.php');
require_once(__DIR__ . '/../database/model.class.php');
require_once(__DIR__ . '/../utils/uploadItemImages.php');

$session = new Session();

if(!$session->isLoggedIn()){
    header("Location: ../pages/login.php");
    exit();
}

$DB = getDatabaseConnection();
if (isset($_POST['category']) && !empty($_POST['category'])) {
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
    if (!Category::exists($DB, $category)) {
        echo "Error: Category does not exist";
        exit();
    }
} else {
    $category = null;
}

if (isset($_POST['brand']) && !empty($_POST['brand'])) {
    $brand = filter_input(INPUT_POST, 'brand', FILTER_SANITIZE_NUMBER_INT);
    if (!Brand::exists($DB, $brand)) {
        echo "Error: Brand does not exist";
        exit();
    }
} else {
    $brand = null;
}

if (isset($_POST['model']) && !empty($_POST['model'])) {
    $model = filter_input(INPUT_POST, 'model', FILTER_SANITIZE_NUMBER_INT);
    if (!Model::exists($DB, $model)) {
        echo "Error: Model does not exist";
        exit();
    }
} else {
    $model = null;
}

if (isset($_POST['size']) && !empty($_POST['size'])) {
    $size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_NUMBER_INT); // or FILTER_SANITIZE_STRING
    if (!Size::exists($DB, $size)) {
        echo "Error: Size does not exist";
        exit();
    }
} else {
    $size = null;
}

if (isset($_POST['condition']) && !empty($_POST['condition'])) {
    $condition = filter_input(INPUT_POST, 'condition', FILTER_SANITIZE_NUMBER_INT);    
    if (!Condition::existsConditionById($DB, $condition)) {
        echo "Error: Condition does not exist";
        exit();
    }
} else {
    $condition = null;
}

if($model != null && $brand == null){
    echo "Error: Model cannot be added without a brand";
    exit();
}

if($model != null && $brand != null){
    if (!Model::belongsToBrand($DB, $model, $brand)) {
        echo "Error: Model does not belong to the specified brand";
        exit();
    }
}

$price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
$description = htmlspecialchars($_POST['description']);
$name = htmlspecialchars($_POST['name']);
$files = $_FILES["files"];
    

$item = Item::addNewProduct($DB, $brand, $model, $price, false,$session->getId(),$category ,$size ,$condition , $name, $description, $quantity,count($files['name']));

if($item == null){
    echo "Error adding item";
    exit();
}

$targetDirectory = '../images/items/';
$uploadedFiles = storeFiles($files, $targetDirectory,$item);

header("Location: ../pages/home.php");
exit();

?>