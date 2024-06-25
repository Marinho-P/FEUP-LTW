
document.addEventListener('DOMContentLoaded', () => {
    const RemoveFromWishlistButtons = document.querySelectorAll('.RemoveFromWishlistButton');
    const AddToCartButtons = document.querySelectorAll('.AddToCartButton');
    RemoveFromWishlistButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Call a JavaScript function to trigger AJAX request
            const itemId = button.getAttribute('data-item-id');
            RemoveFromWishlistAction(itemId);
        });
    });
    AddToCartButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Call a JavaScript function to trigger AJAX request
            const itemId = button.getAttribute('data-item-id');
            const quantity = document.getElementById('Quantity'+itemId).value;
            AddToCartAction(itemId,quantity);
        });
    });

    function RemoveFromWishlistAction(itemId) {
        const xhr = new XMLHttpRequest();
        const url = '../actions/actionRemoveFromWishlist.php';
        const params = 'itemId=' + encodeURIComponent(itemId);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const wishlistItem = document.getElementById('wishlistItem'+itemId);
                    wishlistItem.remove();
                    if(document.getElementById('wishlist').childElementCount == 0){
                        document.getElementById('wishlist').innerHTML = '<h1>Wishlist is empty</h1>';
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
    function AddToCartAction(itemId,quantity) {
        const xhr = new XMLHttpRequest();
        const url = '../actions/actionAddToCart.php';
        const params = 'itemId=' + encodeURIComponent(itemId) + '&quantity=' + encodeURIComponent(quantity);
    
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    const QuantityInput = document.getElementById('Quantity'+itemId);
                    const AddToCartButton = document.getElementById('AddToCartButton'+itemId);
                    if(response.remainingStock <=0){
                        AddToCartButton.disabled = true;
                        AddToCartButton.textContent = 'Out of Stock';
                        QuantityInput.value = 1;
                        QuantityInput.disabled = true;

                    }
                    else{
                        QuantityInput.max = response.remainingStock;
                        if(QuantityInput.value > response.remainingStock){
                            QuantityInput.value = response.remainingStock;
                        }
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
    
});
