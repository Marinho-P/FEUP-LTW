<?php


declare(strict_types=1);

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/message.class.php');
require_once(__DIR__ . '/../utils/session.php');


$session = new Session();


if (!$session->isLoggedIn())
    die(header('Location: /'));


$data = json_decode(file_get_contents("php://input"), true);

$chatId = $data['chatId'];

if (isset($chatId)) {

    try {
        $db = getDatabaseConnection();
        $messages = Message::getAllMessagesByChatId($db, $chatId);
        
        $response = [];
        foreach ($messages as $message) {
            $response[] = [
                'id' => $message->id,
                'chatId' => $message->chatId,
                'senderId' => $message->senderId,
                'content' => $message->content,
                'timeSent' => $message->timeSent,
            ];
        }
        
        echo json_encode(array('messages' => $response));
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'chatId not set']);
}