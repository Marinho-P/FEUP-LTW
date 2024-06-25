document.addEventListener('DOMContentLoaded', function() {
    const RemoveFromCartButtons = document.querySelectorAll('.RemoveFromCartButton');
    const QuantityInputs = document.querySelectorAll('.QuantityInput');
    const CheckoutButton = document.getElementById('Checkout');
    let itemsInCart = document.getElementById('shoppingCart').getAttribute('data-nItems');
    RemoveFromCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = button.getAttribute('data-item-id');
            RemoveFromCartAction(itemId);
        });
    } );
    QuantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const itemId = input.getAttribute('data-item-id');
            const quantity = input.value;
            UpdateCartAction(itemId, quantity);
        });
    });
    CheckoutButton.addEventListener('click', function() {
        if(itemsInCart > 0){
        window.location.href = '../pages/checkout.php';
        }
        else{
            alert('Your cart is empty');
        }
    });   
    function RemoveFromCartAction(itemId) {
        const xhr = new XMLHttpRequest();
        const url = '../actions/actionRemoveFromCart.php';
        const params = 'itemId=' + encodeURIComponent(itemId);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    console.log(xhr.responseText); // Handle successful response
                    const shoppingCartItem = document.getElementById('ShoppingCartItem'+itemId);
                    console.log(shoppingCartItem);
                    const Total = document.getElementById('Total');
                    Total.textContent = 'Total: ' + response.newTotal + '€';
                    
                    shoppingCartItem.remove();
                    console.log(itemsInCart);
                    itemsInCart--;
                    if(itemsInCart == 0){
                        console.log('Your cart is empty');
                        document.getElementById('shoppingCart').innerHTML = '<h1>Your cart is empty</h1>';
                    }
                } else {
                    console.error('Error:', xhr.status); // Handle request error
                }
            }
        };
    
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send(params);
    }
    function UpdateCartAction(itemId, quantity) {
        const xhr = new XMLHttpRequest();
        const url = '../actions/actionUpdateCart.php';
        const params = 'itemId=' + encodeURIComponent(itemId) + '&quantity=' + encodeURIComponent(quantity);
        console.log(params);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    console.log(response.message); 
                    const CombinedPrice = document.getElementById('CombinedPrice' + itemId);
                    const Total = document.getElementById('Total');
                    CombinedPrice.textContent = 'Combined price: ' + response.combinedPrice + '€';
                    Total.textContent = 'Total: ' + response.newTotal + '€';

                } else {
                    console.error('Error:', xhr.status); // Handle request error
                }
            }
        };
    
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send(params);
    }
});