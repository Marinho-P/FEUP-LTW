<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$session = new Session();
if(!$session->isLoggedIn())
    die(header('Location: /'));

$DB = getDatabaseConnection();

$id = $session->getId();

// Create an associative array with the 'id' key and the integer value
$data = array('id' => $id);

// Encode the array into JSON
echo json_encode($data);

