<?php
    session_start();
    
    $gameList = $_SESSION["gameList"];
    
    if ( !isset($gameList) ) {
        error_log("not it the session!");
        header('Location: week3.php', true, 303);
        die();
    } else { 
        error_log("in the session...");
    }
    
    $name = $_SESSION["name"];
    $addr = $_SESSION["addr"];
    $csz = $_SESSION["csz"];
    
    session_unset(); 
    session_destroy(); 
?>
<html>
    <head>
        <title>CS 313 Assignments Portfolio</title>
        <link rel="stylesheet" type="text/css" href="../index.css">
        <link rel="stylesheet" type="text/css" href="./week3.css">
        <link rel="stylesheet" type="text/css" href="./week3Cart.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    </head>
    <body>
        <div class="clearfix">
            <div class="header">
                <div class="header-container">
                    <div class="header-text">Stacy Brothers - CS 313</div>                        
                </div>
            </div>
            <div class="content">
                <div class="page-title"><img src="../images/Atari_2600_logo.svg">&nbsp;&nbsp;Game Store</div>
                <div class="shoppingCart">Your Order is being Processed!</div>
                <div class="clearfix cartHeader">
                    <div class="cartItem cartHeaderCol">Game Title</div>
                    <div class="cartRight cartHeaderCol">Total</div>
                    <div class="cartRight cartHeaderCol">Quantity</div>
                    <div class="cartRight cartHeaderCol">Price</div>
                </div>
                <div class="gamelist">
                    <?php 
                        error_log( " count: " . count($gameList));
                        $total = 0;
                        $totalCount = 0;
                        for ($i=0; $i<count($gameList); $i++) {
                            if ( $gameList[$i]["inCart"] > 0 ) {
                    ?>
                    <div class="clearfix cartLine">
                        <div class="cartItem"><?=$gameList[$i]["title"]?></div>
                        <div class="cartRight ">$<?=$gameList[$i]["price"]*$gameList[$i]["inCart"]?></div>
                        <div class="cartRight "><?=$gameList[$i]["inCart"]?></div>
                        <div class="cartRight ">$<?=$gameList[$i]["price"]?></div>
                    </div>
                    <?php
                                $totalCount += $gameList[$i]["inCart"];
                                $total += $gameList[$i]["price"]*$gameList[$i]["inCart"];
                            }
                        }
                    ?>
                    
                    <div class="clearfix"><div class="cartTotals"><?=$totalCount?></div><div class="cartTotals">Items in Cart:</div></div>
                    <div class="clearfix"><div class="cartTotals">$<?=$total?></div><div class="cartTotals">Total Cost:</div></div>                    
                </div>
                <div class="shoppingCart">Shipping Address</div>
                <div class="clearfix"><div class="addrLabel">Name</div><div class="addrInput"><?=$name?></div></div>
                <div class="clearfix"><div class="addrLabel">Address</div><div class="addrInput"><?=$addr?></div></div>
                <div class="clearfix"><div class="addrLabel">City, State Zip</div><div class="addrInput"><?=$csz?></div></div>
                <div><button onclick="continueShopping()">CONTINUE SHOPPING</button></div>
            </div>
            
        </div>
        <script type="text/javascript">
            function continueShopping() { 
                window.location.href = 'week3.php';
            }
        </script>
    </body>
</html>