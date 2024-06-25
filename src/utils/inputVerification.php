<?php
    declare(strict_types=1);

    require_once("../utils/session.php");
    $session = new Session();

    require_once("../database/connection.db.php");
    require_once("../database/user.class.php");


    function isPasswordValid(string $password): bool {
        global $session;
        $password_validation_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-_]).{8,}$/";
        if (preg_match($password_validation_regex, $password))
            return true;
        else {
            $session->addMessage("error", "Password needs to be 8 characters long, contain a lowercase and uppercase letter, a number and a special character!");
            return false;
        }
    }

    function isUsernameValid(string $username): bool {
        global $session;
        $username_validation_regex = "/^[A-Za-z][A-Za-z0-9]{4,24}$/";
        $userDB = getDatabaseConnection();
        if (preg_match($username_validation_regex, $username)) {
            if (!User::isUsernameUsed($userDB, $username))
                return true;
            else {
                $session->addMessage("error", "This user already exists!");
                return false;
            }
        } else {
            $session->addMessage("error", "Username needs to be between 5 and 25 characters long, contain only alphanumeric characters and not begin with a digit!");
            return false;
        }
    }

    function isEmailValid(string $email): bool {
        global $session;
        $userDB = getDatabaseConnection();
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (!User::isEmailUsed($userDB, $email))
                return true;
            else {
                $session->addMessage("error", "This email is already in use!");
                return false;
            }
        } else {
            $session->addMessage("error", "E-mail format not valid!");
            return false;
        }
    }

    function isPhoneNumberValid(string $phone_number): bool {
        global $session;
        $phone_number_validation_regex = "/^\d{9}$/"; // Matches exactly 9 digits
        if (preg_match($phone_number_validation_regex, $phone_number)) {
            return true;
        } else {
            $session->addMessage("error", "Phone number must be exactly 9 digits long.");
            return false;
        }
    }

    function isAddressValid(string $address): bool {
        global $session;
        if (strlen($address) > 0 && strlen($address) <= 255){
            return true;
        } else {
            $session->addMessage("error", "Address cannot be empty.");
            return false;
        }
    }
    function isDescriptionValid(string $description): bool {
        global $session;
        if (strlen($description) > 0 && strlen($description) <= 255){
            return true;
        } else {
            $session->addMessage("error", "Description cannot be empty.");
            return false;
        }
    }
?>