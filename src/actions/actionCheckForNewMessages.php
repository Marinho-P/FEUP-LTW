<?php
// Assuming you have functions to connect to the database and retrieve chat data

declare(strict_types=1);
require_once (__DIR__ . '../../utils/session.php');
require_once (__DIR__ . '../../database/chat.class.php');
require_once (__DIR__ . '../../database/connection.db.php');
$session = new Session();
$DB = getDatabaseConnection();
$chatsArray = [];
$userArray = [];

if (isset($_GET['tab'])){
    $chatType = (int)$_GET['tab'];
    $userId = $session->getId();

    if($chatType === 0){
        $chats = Chat::getChatsWithBuyerId($DB, $userId); // Replace with your actual function to fetch chat data for buying tab
        foreach ($chats as $chat) {
            $mostRecentMessage = Message::getMostRecentMessageByChatId($DB, $chat->getChatId());
            if($mostRecentMessage){
                $chatsArray[] = $chat;
                $userArray[] = User::getUserWithId($DB, $mostRecentMessage->getSenderId());
            }
        }
    } else if($chatType === 1){
        $chats = Chat::getChatsWithSellerId($DB, $userId);
        foreach ($chats as $chat) {
            $mostRecentMessage = Message::getMostRecentMessageByChatId($DB, $chat->getChatId());
            if($mostRecentMessage){
                $chatsArray[] = $chat;
                $userArray[] = User::getUserWithId($DB, $mostRecentMessage->getSenderId());
            }
        } 
    } else {
        // If chatType is invalid, return an error response
        http_response_code(400); // Bad Request
        echo json_encode(array('error' => 'Invalid chat type'));
        exit;
    }
    echo json_encode(array('chats' => $chatsArray, 'users' => $userArray));
}