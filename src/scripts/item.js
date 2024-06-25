
document.addEventListener('DOMContentLoaded', () => {
    const WishlistButton = document.getElementById('WishlistButton');
    const AddToCartButton = document.getElementById('AddToCartButton');
    const QuantityInput = document.getElementById('Quantity');
    WishlistButton.addEventListener('click', () => {
        // Call a JavaScript function to trigger AJAX request
        const itemId =WishlistButton.getAttribute('data-item-id');
        const buttonText = WishlistButton.textContent.trim();
        if(buttonText == 'Remove from Wishlist'){
            RemoveFromWishlistAction(itemId);
        }else{
            AddToWishlistAction(itemId);
        }
    });
    AddToCartButton.addEventListener('click', () => {
        // Call a JavaScript function to trigger AJAX request
        const itemId =WishlistButton.getAttribute('data-item-id');
        const quantity = QuantityInput.value;
        AddToCartAction(itemId,quantity);
    });

    function AddToWishlistAction(itemId) {
        const xhr = new XMLHttpRequest();
        const url = '../actions/actionAddToWishlist.php';
        const params = 'itemId=' + encodeURIComponent(itemId);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log(xhr.responseText); // Handle successful response
                    WishlistButton.innerHTML="<i class='fa-regular fa-heart'></i>Remove from Wishlist";
                } else {
                    console.error('Error:', xhr.status); // Handle request error
                }
            }
        };
    
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send(params);
    }
    function RemoveFromWishlistAction(itemId) {
        const xhr = new XMLHttpRequest();
        const url = '../actions/actionRemoveFromWishlist.php';
        const params = 'itemId=' + encodeURIComponent(itemId);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log(xhr.responseText); // Handle successful response
                    WishlistButton.innerHTML = "<i class='fa-regular fa-heart'></i>Add to Wishlist";
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
                    console.log(response.message); 
                    console.log(response.remainingStock);// Handle successful response
                    QuantityInput.max = response.remainingStock;
                    if(response.remainingStock <=0){
                        AddToCartButton.disabled = true;
                        AddToCartButton.innerHTML= "<i class='fa-solid fa-cart-plus'></i>Out of stock";
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
    const notifyUserButton = document.getElementById('notifyButton');

    notifyUserButton.addEventListener('click', (event) => {
        xhr = new XMLHttpRequest();
        xhr.open("POST", "../actions/actionNotifyUser.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    const url = "../pages/chat.php?tab=0";

                    window.location.href = url;
                } else {
                    console.error('Error:', xhr.status); // Handle request error
                }
            }
        };
        xhr.send(JSON.stringify({itemId: notifyUserButton.getAttribute('data-item-id')}));

    });
    
});
function changeImage(thumbnail) {
    var mainImage = document.getElementById('mainImg');
    mainImage.src = thumbnail.src;
    mainImage.alt = thumbnail.alt;
}
