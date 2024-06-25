
document.addEventListener("DOMContentLoaded", function() {
    let editProfileButton = document.getElementById("edit_profile");
    if(editProfileButton) {
        editProfileButton.addEventListener("click", function() {
            window.location.href = "../pages/editProfile.php";
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    let reveal_button = document.getElementById("log_out");
    if (reveal_button) {
        reveal_button.addEventListener("click", function() {
            window.location.href = "../actions/actionLogout.php";
        });
    }
});