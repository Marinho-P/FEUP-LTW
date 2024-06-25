<?php

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../images/';

    $filename = $session->getId() . '_' . "temp.png";
    
    $targetPath = $uploadDir . $filename;
    
    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetPath)) {
        echo $targetPath;
    }
}
?>
