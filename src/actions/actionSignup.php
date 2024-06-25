<?php

    declare(strict_types=1);

    require_once("../utils/session.php");
    $session = new Session();

    require_once("../database/user.class.php");
    require_once("../utils/inputVerification.php");

    $registerName = $_POST['name'];
    $registerEmail = $_POST['email'];
    $registerUsername = $_POST['username'];
    $registerPassword = $_POST['password'];
    $registerConfirmedPassword = $_POST['confirm_password'];

    if (empty($registerEmail) || empty($registerName)  || empty($registerUsername) || empty($registerPassword) || empty($registerConfirmedPassword)) {
        $session->addMessage("error", "Enter a value for all fields!");
        die(header("Location: ../pages/register.php"));
    } else if ($registerPassword !== $registerConfirmedPassword) {
        $session->addMessage("error", "Passwords don't match!");
        die(header("Location: ../pages/register.php"));
    } else if (!isPasswordValid($registerPassword) || !isUsernameValid($registerUsername) || !isEmailValid($registerEmail)) {
        die(header("Location: ../pages/register.php"));
    } else {
        User::createUser(getDatabaseConnection(), $registerName, $registerEmail, $registerUsername, password_hash($registerPassword, PASSWORD_DEFAULT));
        //User::createClient(getDatabaseConnection(), $signupEmail, $signupName, $signupUsername, $signupPassword);
        $session->addMessage("sucess", "Signup successful!");
        die(header('Location: ../pages/login.php'));
    }
?>