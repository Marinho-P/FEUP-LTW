<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');

function drawFooter()
{
?>
    </main>
    <footer>&copy; Pre-Loved, 2024</footer>
    </body>
    </html>
<?php  }



function drawLoginHeader(Session $session, string $script = null) {
?>
    <!DOCTYPE html>
    <html lang="en-US">
        <head>
            <title>Login</title>
            <meta charset="utf-8">
            <link rel="stylesheet" href="../css/login.css">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400..800&display=swap" rel="stylesheet">
            <script src="https://kit.fontawesome.com/861da2e5c3.js" crossorigin="anonymous"></script>
        </head>
        <body>
            <header>
                <a href="../pages/login.php"><img src="../Logo.png" alt="Logo"></a>
            </header>
<?php } 


function drawHeader(Session $session, string $stylesheet, string $script = null) {
?>
    <!DOCTYPE html>
    <html lang="en-US">

    <head>
        <title>Main_Page</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="../css/common.css">
        <link rel="stylesheet" href=<?= $stylesheet ?>>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" />
        <script src="https://kit.fontawesome.com/861da2e5c3.js" crossorigin="anonymous"></script>
        <script src=<?= $script ?> defer></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400..800&display=swap" rel="stylesheet">
        </head>
    <body>
        <header>
        <a href="../pages/home.php"><img src="../Logo_inverted.png" alt="Logo"></a>
        <div>
            <a href= "../pages/chat.php?tab=0"><i class="fa-solid fa-message fa-xl"></i></a>
            <a href="../pages/profile.php"class= "icon-link"><i class="fa-solid fa-user fa-xl"></i></i></a>
            <a href="../pages/wishlist.php" class="icon-link"><i class="fa-solid fa-heart fa-xl"></i></a>
            <a href="../pages/shoppingCart.php" class="icon-link"><i class="fa-solid fa-cart-shopping fa-xl"></i></a>
            <a href="../pages/addItem.php" class= "icon-link"><i class="fa-solid fa-plus fa-xl"></i></a>       
        </div>
        </header>
        <main>
<?php }