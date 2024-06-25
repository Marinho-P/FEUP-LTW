<?php


declare(strict_types=1);

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/message.class.php');

$session = new Session();
$DB = getDatabaseConnection();

if (!$session->isLoggedIn())
    die(header('Location: /'));

$data = json_decode(file_get_contents("php://input"), true);

$userId = $data['userId'];

if(isset($userId)){
    $user = User::getUserWithId($DB, (int)$userId);
    echo json_encode(array('user' => $user));
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'User ID and chat ID are required'));
}