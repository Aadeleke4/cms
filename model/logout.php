<?php
    session_start(); // Start the session to access session variables
    session_destroy(); // Destroy all data registered to a session
    header('Location: ../view/login.php'); // Redirect the user to the login page
    exit(); // Ensure no further code is executed after the redirect
?>
