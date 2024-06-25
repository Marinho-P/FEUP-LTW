<?php
    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/authentication.tpl.php');
    require_once(__DIR__ . '/../templates/wishlist.tpl.php');
    $session = new Session();
    if(!$session->isLoggedIn()){
        die(header('Location: /pages/login.php'));
    } 
    drawHeader($session, "../css/wishlist.css", "../scripts/wishlist.js");
    drawWishlist($session);
    drawFooter();
?>