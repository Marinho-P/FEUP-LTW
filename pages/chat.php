<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/chat.tpl.php');

    $session = new Session();

    if(!$session->isLoggedIn()){
        die(header('Location: ../pages/login.php'));
    }

    drawHeader($session, "../css/chat.css", "../scripts/chat.js");

    drawChat($session->getId());

    drawFooter();
?>
