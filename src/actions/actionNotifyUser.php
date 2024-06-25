<?php

declare(strict_types=1);
require_once(__DIR__ . '/../database/items.class.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/chat.class.php');
require_once(__DIR__ . '/../database/message.class.php');
require_once(__DIR__ . '/../database/user.class.php');

$session = new Session();

if(!$session->isLoggedIn()){
    header('Location: /');
    exit();
}

$DB = getDatabaseConnection();

$data = json_decode(file_get_contents("php://input"), true);

$itemId = (int)$data['itemId'];

if(isset($itemId)){
    $item = Item::getProductById($DB, $itemId);
    $sellerId = $item->getOwnerId();
    $chat = Chat::createChatWithUserId($DB, $sellerId,$session->getId());
    if($chat == null){
        echo json_encode(array('error' => 'Could not create chat'));
        exit();
    }
    $url = '../pages/item.php' . '?id=' . $itemId;
    $clickableUrl = '<a href="' . $url . '">' . $item->getName() . '</a>';
    $messageContent = "Hello, I am interested in your item: {$clickableUrl}";
    $message = Message::addNewMessage($DB, $chat->getChatId(), $session->getId(), $messageContent);
    if(!$message){
        echo json_encode(array('error' => 'Could not send message'));
        exit();
    }
    echo json_encode(array('item' => $item));
}else{
    echo json_encode(array('error' => 'No item id provided'));
}

