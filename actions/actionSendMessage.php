<?php

declare(strict_types=1);

require_once (__DIR__ . '../../utils/session.php');
require_once (__DIR__ . '../../database/connection.db.php');
require_once (__DIR__ . '../../database/message.class.php');

$session = new Session();

if (!$session->isLoggedIn())
    die(header('Location: /'));

$DB = getDatabaseConnection();

$data = json_decode(file_get_contents("php://input"), true);

$chatId = $data['chatId'];
$content = $data['content'];

if (isset($chatId) && isset($content)) {
    $message = Message::addNewMessage($DB, $chatId, $session->getId(), $content);
    if($message){
        echo json_encode(array('message' => $message));
    } else {
        http_response_code(400);
        echo json_encode(array('error' => 'Failed to send message'));
    }
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Chat ID and content are required'));
}





?>
