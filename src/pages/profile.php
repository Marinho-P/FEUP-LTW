<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/authentication.tpl.php');
    require_once(__DIR__ . '/../templates/profile.tpl.php');

    $session = new Session();
    if(!$session->isLoggedIn()){
        die(header('Location: /pages/login.php'));
    } 
    drawHeader($session, "../css/profile.css", "../scripts/profile.js");

    drawProfileSettings($session->getId());
    
    drawFooter();
?>