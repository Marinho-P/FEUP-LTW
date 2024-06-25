<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/category.tpl.php');
require_once(__DIR__ . '/../database/items.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$session = new Session();
$db = getDatabaseConnection();

$priceFilter = isset($_GET['price']) ? floatval($_GET['price']) : null;
$conditionFilter = isset($_GET['conditionId']) && $_GET['conditionId'] !== '' ? intval($_GET['conditionId']) : null;

$category = Category::extractCategoryWithCategoryId($db, intval($_GET['categoryId']));
$items = Item::getProductsByCategoryIdWithFilters($db, intval($_GET['categoryId']), $priceFilter, $conditionFilter);

drawHeader($session, "../css/category.css");
drawCategory($db, $category, $items);
drawFooter();