<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/inputVerification.php');

$session = new Session();

if(!$session->isLoggedIn()){
    header("Location: ../pages/login.php");
    exit();
}

$DB = getDatabaseConnection();


$name = $_POST['nome'];
$username = $_POST['username'];
$email = $_POST['email'];
$newPassword = $_POST['newPassword'];
$oldPassword = $_POST['oldPassword'];

$description = $_POST['description'];
$address = $_POST['address'];
$phone_number = $_POST['phone-number'];

$newUser = User::getUserWithId($DB,$session->getId());


if(!$newUser){
    header("Location: ../pages/editProfile.php?error=1");
    exit();
}

$changedUserName = $newUser->getUsername() != $username; 
$changedEmail = $newUser->getEmail() != $email;
$isEmpty = empty($oldPassword) && empty($newPassword);
$changedAddress = $newUser->getAddress() != $address;
$changedDescription = $newUser->getDescription() != $description;
$changedPhoneNumber = $newUser->getPhoneNumber() != $phone_number;

if(!$isEmpty){


    if(!password_verify($oldPassword, $newUser->getPassword())){
        header("Location: ../pages/editProfile.php?error=2");
        exit();
    }

    
    if(password_verify($newPassword, $newUser->getPassword())){
        header("Location: ../pages/editProfile.php?error=3");
        exit();
    }

    if(!isPasswordValid($newPassword)){
        header("Location: ../pages/editProfile.php?error=4");
        exit();
    }

    $newUser->alterPassword($DB, $newPassword);
}


if(!isUsernameValid($username) && $changedUserName){
    header("Location: ../pages/editProfile.php?error=5");
    exit();
}

if(!isEmailValid($email) && $changedEmail){
    header("Location: ../pages/editProfile.php?error=6");
    exit();
}
   
if($changedPhoneNumber){
    if(!isPhoneNumberValid($phone_number)){
        header("Location: ../pages/editProfile.php?error=7");
        exit();
    }
}


if($changedAddress){
    if(!isAddressValid($address)){
        header("Location: ../pages/editProfile.php?error=8");
        exit();
    }
}


if($changedDescription){
    if(!isDescriptionValid($description) ){
        header("Location: ../pages/editProfile.php?error=9");
        exit();
    }
}


if($changedDescription){
    $newUser->setDescription($description);
}

if($changedAddress){
    $newUser->setAddress($address);
}

if($changedPhoneNumber){
    $newUser->setPhoneNumber($phone_number);
}

$newUser->setName($name);
$newUser->setUsername($username);
$newUser->setEmail($email);




$tempFilename =  $session->getId() . '_temp.png';
$finalFilename = 'user' . $session->getId() . '.png';

$tempPath = '../images/' . $tempFilename;
$finalPath = '../images/' . $finalFilename;


if (file_exists($tempPath)) {
    rename($tempPath, $finalPath);
}

$newUser->updateUser($DB);

header("Location: ../pages/profile.php");
exit();

?>
