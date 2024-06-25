<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/checkout.tpl.php');
    $session = new Session();
    if(!$session->isLoggedIn()){
        header('Location: ../pages/login.php');
        exit();
    }
    drawHeader($session, "../css/checkout.css", "../scripts/checkout.js");
    
    drawCheckout($session);
    
    drawFooter();
?>