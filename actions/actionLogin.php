<?php
    declare(strict_types=1);

    require_once("../utils/session.php");
    $session = new Session();

    require_once("../database/connection.db.php");
    require_once("../database/user.class.php");
    //require_once("../database/agent.class.php");
    
    $userDB = getDatabaseConnection();

    $loginUsername = $_POST['username'];
    $loginPassword = $_POST['password'];

    $user = User::verifyPassword($userDB, $loginUsername, $loginPassword);

    $user = User::getUserWithPassword($userDB, $loginUsername, $loginPassword);

    if ($user){
        $session->updateSessionOnAgent($user);
        $session->addMessage('success','Login successful!');
        die(header("Location: ../pages/home.php"));
    }
    else { 
        $user = User::getUserWithPassword($userDB, $loginUsername, $loginPassword);        
    }
    if ($user){
        $session->updateSessionOnUser($user);
        $session->addMessage('success', 'Login successful!');
        die(header("Location: ../pages/home.php"));
    }
    else{
        $session->addMessage('error','Wrong username or password!');
        die(header("Location: ../pages/login.php"));
    }
?>