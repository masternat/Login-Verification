<?php 
    session_start();
    unset($_SESSION['authenticated']);
    unset($_SESSION['auth_user']);
    $_SESSION['status'] = "You've Logged out Successfully";
    header("Location: login.php");
?>