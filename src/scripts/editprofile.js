

document.addEventListener('DOMContentLoaded', function() {

    const reveal_button = document.getElementById("profile-picture");
    if (reveal_button) {
        reveal_button.addEventListener("change", previewAndUploadImage);
    }

    setTimeout(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');
        
        if (error === '2') {
            alert("Incorrect old password. Please try again.");
            return;
        }
        if(error === '3') {
            alert("Password is already in use. Please try again.");
            return;
        }
        if(error === '4') {
            alert("Invalid Password Format. Please try again.");
            return;
        }
        if(error === '5') {
            alert("Username invalid. Please try again.");
            return;
        }
        if(error === '6') {
            alert("Email invalid. Please try again.");
            return;
        }
        if(error === '7') {
            alert("Invalid phone number. Please try again.");
            return;
        }
        if(error === '8') {
            alert("Invalid address. Please try again.");
            return;
        }
        if(error === '9') {
            alert("Invalid description. Please try again.");
            return;
        }
    }, 400);


});

function previewAndUploadImage(event) {
    let file = event.target.files[0];
    let formData = new FormData();
    formData.append('profile_image', file);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', '../actions/actionUploadImage.php');
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            let imageUrl = xhr.responseText + '?' + Math.random();
            document.getElementById('profile-image').src = imageUrl;
            console.log('Image uploaded successfully');
        } else {
            console.error(xhr.responseText);
        }
    };
    xhr.send(formData);
}
