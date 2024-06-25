<?php

declare(strict_types=1);
function drawSuccess($orderId)
{
    
    echo "<section id='sucess'>
        <h1>Order placed successfully!</h1>
        <p>Thank you for shopping with us!</p>
        <p>Your order will be processed shortly.</p>
        <p>Check your email for more information.</p>
        <p>Order number: {$orderId}</p>
        <button id='backToHome'>Back to home</button>

    </section>";
}
?>