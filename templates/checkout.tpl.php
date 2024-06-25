<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/authentication.tpl.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/shoppingCart.class.php');    
    function drawCheckout($session){

        $db = getDatabaseConnection();
        $total = ShoppingCart::getTotal($db, $session->getId());
        echo "<section class='checkout'>
        <div class='checkoutInfo'>
        <h1>Checkout</h1>
        <h3>Total: {$total}€</h3>
        <h4>Bundle from seller:</h4>
        <input type='text' id='coupon' placeholder='Coupon code' maxlength='10'></input>
        <span class='error' id='invalid-coupon' display='none'>Invalid/expired coupon code</span>
        <button id='apply-coupon'>Apply</button>
        </div>
        <section id='deliveryAddress'>
        <h2>Delivery Address</h2>
        <ul>
        <li>
        <input type='text' maxlength='30' id='Address' pattern='[a-zA-Zºª0-9çÇ. ,\-]{3,50}'placeholder='Address'>
        <span class='error' id='AddressError' >Address must only include alphanumeric characters and be between 3 to 30 characters </span>
        </li>
        <li>
        <input type='text' id='City' maxlength = '20' pattern='[a-zA-Z ]{3,20}' placeholder='City'>
        <span class='error' id='CityError' >City must only include alphabetical characters and be between 3 to 20 characters </span>
        </li>
        <li>
        <input type='text' id='PostalCode' maxlenght ='8' pattern='[0-9]{4}-[0-9]{3}' placeholder='Postal Code'>
        <span class='error' id='PostalCodeError' >Postal code must be in format XXXX-XXX</span>
        </li>
        <li>
        <input type='text' id='Country' maxlenght='20' placeholder='Country' pattern='[a-zA-Z ]{3,20}'>
        <span class='error' id='CountryError' >Country must only include alphabetical characters and be between 3 to 20 characters </span>
        </li>
        <li>
        <button id='CancelAddress'>Cancel</button>
        <button id='Pay'>Go to payment</button>
        </li>
        </ul>
        
        
        
        
        
        </section>
        <section id='payment' class='hide'>
        <h2>Payment</h2>
        <ul>
        <li>
        <input type='text' id='CardNumber' maxlenght='16' pattern='[0-9]{13,16}'placeholder='Card Number'>
        <span class='error' id='CardNumberError' >Card number must be between 13 to 16 digits</span>
        </li>
        <li>
        <input type='text' id='ExpirationDate' maxlenght='5' pattern='[0-9]{2}/[0-9]{2}'placeholder='Expiration Date'>
        <span class='error' id='ExpirationDateError' >Expiration date must be in format MM/YY</span>
        </li>
        <li>   
        <input type='text' id='CVV' maxlength='3' pattern ='[0-9]{3}'placeholder='CVV'>
        <span class='error' id='CVVError' >CVV must be 3 digits</span> 
        </li>
        <li>
        <button id='CancelPayment'>Go back</button>
        <button id='Check'>Check info</button>
        </li>
        </ul>

        
        
        
        
        </section>
        <section id='info' class='hide'>
        <div class='deliveryInfo'>
        <h2>Delivery Address</h2>
        <ul>
        <li>
        <p id='AddressInfo'>Address:</p>
        </li>
        <li>
        <p id='CityInfo'>City:</p>
        </li>
        <li>
        <p id='PostalCodeInfo'>Postal Code:</p>
        </li>
        <li>
        <p id='CountryInfo'>Country:</p>
        </li>
        </div>
        <div class='paymentInfo'>
        <h2>Payment</h2>
        <ul>
        <li>
        <p id='CardNumberInfo'>Card Number:</p>
        </li>
        <li>
        <p id='ExpirationDateInfo'>Expiration Date:</p>
        </li>
        <li>
        <p id='CVVInfo'>CVV:</p>
        </li>
        </div>

        
        <button id='CancelInfo'>Go back</button>
        <button id='Confirm'>Confirm</button>
        </section>  
        </section>";



        
        

        
    }
    
    

?>