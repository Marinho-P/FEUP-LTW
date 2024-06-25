<?php
    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/authentication.tpl.php');

    $session = new Session();

    drawLoginHeader($session);
    drawSignUpForm($session);
    drawFooter();
?>