<?php
// Assuming you have functions to connect to the database and retrieve chat data
require_once (__DIR__ . '../../utils/session.php');
require_once (__DIR__ . '../../database/chat.class.php');
require_once (__DIR__ . '../../database/connection.db.php');
$session = new Session();
$DB = getDatabaseConnection();
if (isset($_GET['tab'])) {

    $chatType = (int)$_GET['tab'];
    $userId = $session->getId();
   
    if ($chatType === 0) {
        $chats = Chat::getChatsWithBuyerId($DB, $userId); // Replace with your actual function to fetch chat data for buying tab
    } else if ($chatType === 1) {
        $chats = Chat::getChatsWithSellerId($DB, $userId); // Replace with your actual function to fetch chat data for selling tab
    } else {
        // If chatType is invalid, return an error response
        http_response_code(400); // Bad Request
        echo json_encode(array('error' => 'Invalid chat type'));
        exit;
    }

    // Return the chat data as JSON response
    header('Content-Type: application/json');
    echo json_encode($chats);
} else {
    http_response_code(400); // Bad Request
    echo json_encode(array('error' => 'User ID and chat type are required'));
}
?>