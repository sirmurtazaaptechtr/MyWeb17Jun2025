<?php
    
    session_start();

    // Unset all session variables
    $_SESSION['user_id'] = '';
    $_SESSION['user_email'] = '';
    $_SESSION['user_name'] = '';
    $_SESSION['user_role'] = '';
    $_SESSION['profile_pic'] = '';
    $_SESSION['logged_in'] = false;
    
    session_destroy();

    // Redirect to login page
    header("Location: login.php");
    exit();
?>