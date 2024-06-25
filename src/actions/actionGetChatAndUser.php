<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database/items.class.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/chat.class.php');
require_once(__DIR__ . '/../database/user.class.php');
$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /');
    exit();
}

$DB = getDatabaseConnection();

$data = json_decode(file_get_contents("php://input"), true);

$ownerId = (int)$data['ownerId'];

if (isset($ownerId)) {
    $chat = Chat::getChatWithBuyerAndSellerId($DB, $session->getId(), $ownerId);
    if ($chat == null) {
        echo json_encode(array('error' => 'Could not create chat'));
        exit();
    }
    $user = User::getUserWithId($DB, $ownerId);
    if ($user == null) {
        echo json_encode(array('error' => 'Could not find user'));
        exit();
    }
    echo json_encode(array('user' => $user, 'chat' => $chat));
} else {
    echo json_encode(array('error' => 'No owner id provided'));
}