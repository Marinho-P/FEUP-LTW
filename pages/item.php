<?php
    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/authentication.tpl.php');
    require_once(__DIR__ . '/../templates/item.tpl.php');
    

    $session = new Session();

    drawHeader($session, "../css/item.css", "../scripts/item.js");
    if (isset($_GET['id'])) {
        $stringValue = $_GET['id'];
        $itemId = intval($_GET['id']);
        if ($itemId === 0 && $stringValue !== "0") {
            echo "Conversion failed!";
            }else{
            drawItemImages($itemId);
            drawItemPage($session,$itemId);
            drawItemReviews($itemId);
            }
    }
    drawFooter(); 
?>