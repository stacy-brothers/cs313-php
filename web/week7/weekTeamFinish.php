<?php
    session_start();
    $user = $_SESSION['user'];
    
    if (!isset($user)) {
        header('Location: ./week7TeamLogin.php');
        die();
    }
?>

<html>
    <body>
        Welcome <?=$user?> 
    </body>
</html>