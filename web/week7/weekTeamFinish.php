<?php
    session_start();
    $user = $_SESSION['user'];
    
    if (!isset($user)) {
        header('Location: ./week7TeamLogin.php');
        die();
    }
?>

<html>
    <head>
        <title>CS 313 Assignments Portfolio</title>
        <link rel="stylesheet" type="text/css" href="../index.css">
    </head>
    <body>
        <div class="clearfix">
            <div class="header">
                <div class="header-container">
                    <div class="header-text">Stacy Brothers - CS 313</div>                        
                </div>
            </div>
            <div class="content">
                Welcome <?=$user?> 
            </div>
        </div>
    </body>
</html>