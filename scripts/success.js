document.addEventListener('DOMContentLoaded', function() {
    const backButton = document.getElementById('backToHome');

    backButton.addEventListener('click', function() {
        window.location = '../pages/home.php';
    });
});