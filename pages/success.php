<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/success.tpl.php');
    $session = new Session();
    if(!isset($_GET['orderId'])){
        header('Location: ../pages/home.php');
        exit();
    }
    else{
        $orderId = $_GET['orderId'];
    }
    // TODO change the css file
    drawHeader($session, "../css/sucess.css", "../scripts/success.js");
    
    drawSuccess($orderId);
    
    drawFooter();
?>