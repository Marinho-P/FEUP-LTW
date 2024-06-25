<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/userReview.class.php');



function drawProfileSettings($userId)
{
    $DB = getDatabaseConnection();
    $newuser = User::getUserWithId($DB,$userId);
    /*fazer o calculo das reviews */
    if($newuser){
        $userImage = "../images/user{$newuser->getId()}.png";
        $defaultImage = "../images/default.png";
        $imageSrc = file_exists($userImage) ? $userImage : $defaultImage;
        $address = $newuser->getAddress();
        $addressMessage = $address ? $address : "Address not provided";
        $current_phone_number = $newuser->getPhoneNumber() ? $newuser->getPhoneNumber() : "Phone number not provided";
        $description = $newuser->getDescription() ? $newuser->getDescription() : "No description provided";
        $reviews = UserReview::getReviewsByReviewedId($DB,$userId);
        $stars = 0;
        $reviewCount = count($reviews);
        if($reviewCount > 0){
            foreach($reviews as $review){
                $stars += $review->getStars();
            }
            $stars = $stars / $reviewCount;
        }
        echo "<section class='profile_info'> 
                    <img src='$imageSrc' alt='Profile Picture'>
                    <div class='user_details'>
                        <ol>
                            <li><strong>Name:   </strong>{$newuser->getName()}</li>
                            <li><strong>Username:   </strong>{$newuser->getUsername()}</li>
                            <li><strong>Average stars:   </strong>{$stars} </li>
                            <li><strong>Got reviewed:   </strong>{$reviewCount} </li>
                        </ol>
                        <ol>
                            <li><h2>About:</h2></li>
                            <li><i class='fa-solid fa-phone'></i><strong>Phone Number:   </strong>{$current_phone_number}</li>
                            <li><i class='fa-solid fa-location-dot'></i><strong> Location:   </strong>{$addressMessage}</li>
                            <li><i class='fa-solid fa-comment'></i><strong>Description:   </strong>{$description}</li>
                        </ol>
                    </div>
                <div class='buttons'>    
                    <button class='edit_profile' id='edit_profile'><i class='fa-solid fa-pencil'></i>EDIT PROFILE</button>
                    <button class='log_out' id='log_out'><i class='fa-solid fa-right-from-bracket'></i>LOG OUT</button>
                </div>
                </section>";
    }else{
        echo "User not found.";
    
    }
    
}

function drawProfileEditing($userId){
    $DB = getDatabaseConnection();
    $newuser = User::getUserWithId($DB,$userId);
    $userImage = "../images/user{$newuser->getId()}.png";
    $defaultImage = "../images/default.png";
    $imageSrc = file_exists($userImage) ? $userImage : $defaultImage;
    $current_name= $newuser->getName();
    $current_username = $newuser->getUsername();
    $current_email = $newuser->getEmail();
    $current_address =  $newuser->getAddress();
    $current_phone_number = $newuser->getPhoneNumber();
    $current_description = $newuser->getDescription();
    if($newuser){
        echo "<div class='edit-profile-container'>
        <h2>Edit Profile</h2>
        <form action='../actions/actionUpdateUserDetails.php' method='POST' enctype='multipart/form-data'>

            <img src='$imageSrc' id='profile-image'>

            <label for='name'>Name:</label>
            <input type='text' id='name' value='{$current_name}' name='nome' required>

            <label for='username'>Username:</label>
            <input type='text' id='username'value='$current_username' name='username' required>
            
            <label for='oldPassword'>Old Password:</label>
            <input type='password' id='oldPassword' name='oldPassword'>

            <label for='newPassword'>New Password:</label>
            <input type='password' id='newPassword' name='newPassword'>
            
            <label for='email'>Email:</label>
            <input type='email' id='email'value='$current_email' name='email' required>

            <label for='address'>Address:</label>
            <input type='text' id='address' placeholder='Address not provided' value='$current_address' name='address'>

            <label for='phone-number'>Phone Number:</label>
            <input type='text' id='phone-number' placeholder='Phone number not provided' value='$current_phone_number' name='phone-number'>
        
            <label for='description'>Description:</label>
            <textarea id='description' placeholder='Write a description about yourself.' name='description'>$current_description</textarea>
            
            <label for='profile-picture'>Profile Picture:</label>
            <input type='file' id='profile-picture' accept='image/*'>
            
            <button type='submit'>SAVE CHANGES</button>
        </form>
    </div>";
    }else{
        echo "User not found.";
    }
}


?>