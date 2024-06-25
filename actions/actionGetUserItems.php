<?php


declare(strict_types=1);
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/userReview.class.php');
require_once(__DIR__ . '/../utils/session.php');

$session = new Session();

if(!$session->isLoggedIn()){
    header("Location: ../pages/login.php");
    exit();
}



$DB = getDatabaseConnection();

echo json_encode(User::getUserItems($DB, $session->getId()));