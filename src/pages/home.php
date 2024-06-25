<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/home.tpl.php');
require_once(__DIR__ . '/../database/category.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$session = new Session();
$db = getDatabaseConnection();
$categories = Category::getAllCategories($db);


drawHeader($session, "../css/home.css");
drawCategories($categories);
drawFooter();