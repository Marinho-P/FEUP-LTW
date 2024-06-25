<?php
    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/authentication.tpl.php');
    require_once(__DIR__ . '/../templates/shoppingCart.tpl.php');
    $session = new Session();
    if(!$session->isLoggedIn()){
        die(header('Location: /pages/login.php'));
    } 
    // TODO change the css file
    drawHeader($session, "../css/wishlist.css", "../scripts/shoppingCart.js");
    drawShoppingCart($session);
    drawFooter();
?>