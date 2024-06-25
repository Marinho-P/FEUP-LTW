document.addEventListener('DOMContentLoaded', function() {
    // sections
    const AddressSection = document.getElementById('deliveryAddress');
    const PaymentSection = document.getElementById('payment');
    const InfoSection = document.getElementById('info');
    // next step buttons
    const PaymentButton = document.getElementById('Pay');
    const CheckButton = document.getElementById('Check');
    const ConfirmButton = document.getElementById('Confirm');
    // go back buttons
    const CancelAddressButton = document.getElementById('CancelAddress');
    const CancelPaymentButton = document.getElementById('CancelPayment');
    const CancelInfoButton = document.getElementById('CancelInfo');
    // input fields
    const Address = document.getElementById('Address');
    const City = document.getElementById('City');
    const PostalCode = document.getElementById('PostalCode');
    const Country = document.getElementById('Country');
    const CardNumber = document.getElementById('CardNumber');
    const ExpirationDate = document.getElementById('ExpirationDate');
    const CVV = document.getElementById('CVV');
    const coupon = document.getElementById('apply-coupon');
    // info fields
    let AddressInfo = "";
    let CityInfo = "";
    let PostalCodeInfo = "";
    let CountryInfo = "";
    let CardNumberInfo = "";
    let ExpirationDateInfo = "";
    let CVVInfo = "";
    let newCode = "";
    // input field event listeners
    coupon.addEventListener('click', function(event) {
        const error = document.getElementById('invalid-coupon');
        let input = document.getElementById('coupon');
        input.addEventListener('input', function() {
            if (!input.value.trim()) { // Check if input field is empty or contains only whitespace
                error.style.display = 'none';
                return; 
            }
        });
        xhr = new XMLHttpRequest();
        xhr.open('POST', '../actions/actionApplyCoupon.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    response = JSON.parse(xhr.responseText);
                    error.style.color = 'green';
                    error.textContent = "Successfully applied coupon!";
                    const discount = response.discount;
                    const total = response.total;
                    newCode = response.code;

                    document.querySelector('.checkoutInfo h3').textContent = 'New Total: ' + total + '€' + ' (-' + discount + '€)';
                    
                } else {
                    response = JSON.parse(xhr.responseText);
                    console.log(response.error);
                    response = response.error;
                    error.textContent = response;
                    error.style.display = 'inline';
                    error.style.color = 'red';
                }
            }
        };
        xhr.send('coupon=' + input.value);
    });


    Address.addEventListener('input', function(event) {
        console.log("typing");
        let input = event.target;
        let error = document.getElementById('AddressError');
        if(input.validity.patternMismatch){
            error.style.display = 'inline';
            AddressInfo = "";
        }else{
            error.style.display = 'none';
            AddressInfo = input.value;

        }
        document.getElementById('AddressInfo').textContent = "Address: " + AddressInfo;
        
    });

    City.addEventListener('input', function(event) {
        let input = event.target;
        let error = document.getElementById('CityError');
        if(input.validity.patternMismatch){
            error.style.display = 'inline';
            CityInfo = "";      
            
        }else{
            error.style.display = 'none';
            CityInfo = input.value;
        }
        document.getElementById('CityInfo').textContent = "City: " + CityInfo;
    });

    PostalCode.addEventListener('input', function(event) {
        let input = event.target;
        console.log("typing");
        let error = document.getElementById('PostalCodeError');
        console.log(input.validity.patternMismatch);
        if(input.validity.patternMismatch){
            error.style.display = 'inline';
            PostalCodeInfo = "";
        }else{
            error.style.display = 'none';
            PostalCodeInfo = input.value;
        }
        document.getElementById('PostalCodeInfo').textContent = "Postal Code: " + PostalCodeInfo;

    });

    Country.addEventListener('input', function(event) {
        let input = event.target;
        let error = document.getElementById('CountryError');
        if(input.validity.patternMismatch){
            error.style.display = 'inline';
            CountryInfo = "";
        }else{
            error.style.display = 'none';
            CountryInfo = input.value;
        }
        document.getElementById('CountryInfo').textContent = "Country: " + CountryInfo;
    });

    CardNumber.addEventListener('input', function(event) {
        let input = event.target;
        let error = document.getElementById('CardNumberError');
        if(input.validity.patternMismatch){
            error.style.display = 'inline';
            CardNumberInfo = "";
        }else{
            error.style.display = 'none';
            CardNumberInfo = input.value;
        }
        document.getElementById('CardNumberInfo').textContent = "Card Number: " + CardNumberInfo;
    });

    ExpirationDate.addEventListener('input', function(event) {
        let input = event.target;
        let error = document.getElementById('ExpirationDateError');
        if(input.validity.patternMismatch){
            error.style.display = 'inline';
            ExpirationDateInfo = "";
        }else{
            error.style.display = 'none';
            ExpirationDateInfo = input.value;
        }
        document.getElementById('ExpirationDateInfo').textContent = "Expiration Date: " + ExpirationDateInfo;
    });

    CVV.addEventListener('input', function(event) {
        let input = event.target;
        let error = document.getElementById('CVVError');
        if(input.validity.patternMismatch){
            error.style.display = 'inline';
            CVVInfo = "";
        }else{
            error.style.display = 'none';
            CVVInfo = input.value;
        }
        document.getElementById('CVVInfo').textContent = "CVV: " + CVVInfo;
    });


    

    CancelAddressButton.addEventListener('click', function() {
        window.location.href = '../pages/shoppingCart.php';
    });
    CancelPaymentButton.addEventListener('click', function() {
        AddressSection.classList.remove('hide');
        PaymentSection.classList.add('hide');
    });
    CancelInfoButton.addEventListener('click', function() {
        PaymentSection.classList.remove('hide');
        InfoSection.classList.add('hide');
    });
    PaymentButton.addEventListener('click', function() {
        if(AddressInfo == "" || CityInfo == "" || PostalCodeInfo == "" || CountryInfo == ""){
            alert("Please fill out all fields");
            return;
        }
        console.log('PaymentButton');
        AddressSection.classList.add('hide');
        PaymentSection.classList.remove('hide');
    });
    CheckButton.addEventListener('click', function() {
        if(CardNumberInfo == "" || ExpirationDateInfo == "" || CVVInfo == ""){
            alert("Please fill out all fields");
            return;
        }
        PaymentSection.classList.add('hide');
        InfoSection.classList.remove('hide');
    });
    ConfirmButton.addEventListener('click', function() {
        CheckoutAction();
    });
    function CheckoutAction(){
        const xhr = new XMLHttpRequest();
        const url = '../actions/actionCheckout.php';
        const params = 'Address=' + encodeURIComponent(AddressInfo) + '&City=' + encodeURIComponent(CityInfo) + '&PostalCode=' + encodeURIComponent(PostalCodeInfo) + '&Country=' + encodeURIComponent(CountryInfo) + '&CardNumber=' + encodeURIComponent(CardNumberInfo) + '&ExpirationDate=' + encodeURIComponent(ExpirationDateInfo) + '&CVV=' + encodeURIComponent(CVVInfo)+ '&coupon=' + encodeURIComponent(newCode);
        console.log(params);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    jsonparsed = JSON.parse(xhr.responseText);

                    window.location.href = '../pages/success.php?orderId='+jsonparsed.orderId ;

                } else {
                    console.error('Error:', xhr.status); // Handle request error
                    console.error(xhr.responseText);
                }
            }
        };
    
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send(params);
    }
});