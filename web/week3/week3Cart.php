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
    
    $name = $addr = $csz = "";
    $nameErr = $addrError = $cszError = "";
    $good = true;
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        error_log("Doing POST!");
        if (empty($_POST["name"])) {
            $nameErr = "Name is required";
            $good = FALSE;
        } else {
            $name = test_input($_POST["name"]);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
                $nameErr = "Only letters and white space allowed";
                $good = FALSE;
            }
        }
        
        if (empty($_POST["addr"])) {
            $addrErr = "Address is required";
            $good = FALSE;
        } else {
            $addr = test_input($_POST["addr"]);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z0-9 ]*$/",$addr)) {
                $addrErr = "Only letters, numbers and white space allowed";
                $good = FALSE;
            }
        }
        if (empty($_POST["csz"])) {
            $cszErr = "Address is required";
            $good = FALSE;
        } else {
            $csz = test_input($_POST["csz"]);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z0-9 ]*$/",$csz)) {
                $cszErr = "Only letters, numbers and white space allowed";
                $good = FALSE;
            }
        }
        
        if ($good) { 
            error_log("It is good!");
            // save the values in the session and go to the checkout complete page
             $_SESSION["name"] = $name;
             $_SESSION["addr"] = $addr;
             $_SESSION["csz"] = $csz;
             header('Location: week3complete.php', true, 303);
             die();
        }
        
    }
    
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
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
                <div class="shoppingCart">Shopping Cart</div>
                <div class="clearfix cartHeader">
                    <div class="cartItem cartHeaderCol">Game Title</div>
                    <div class="cartRight cartHeaderCol">Total</div>
                    <div class="cartRight cartHeaderCol">Quantity</div>
                    <div class="cartRight cartHeaderCol">Price</div>
                </div>
                <div class="gamelist">
                    <?php 
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
                    <div class="clearfix"><div class="cartTotals"><button onclick="continueShopping()">CONTINUE SHOPPING</button></div>
                </div>
                <div class="shoppingCart">Shipping Address</div>
                <form action="week3Cart.php" method="POST">
                <div class="clearfix"><div class="addrLabel">Name</div><div class="addrInput"><input type="text" name="name" id="name" value="<?=$name?>"><span class="error"><?=$nameErr;?></span></div></div>
                <div class="clearfix"><div class="addrLabel">Address</div><div class="addrInput"><input type="text" name="addr" id="addr" value="<?=$addr?>"><span class="error">* <?=$addrErr;?></span></div></div>
                <div class="clearfix"><div class="addrLabel">City, State Zip</div><div class="addrInput"><input type="text" name="csz" id="csz" value="<?=$csz?>"><span class="error">* <?=$cszErr;?></span></div></div>
                <div class="clearfix"><div class="cartTotals"><button>CHECK OUT</button></div>
                </form>
            </div>
        </div>
        <script type="text/javascript">
            function continueShopping() { 
                window.location.href = 'week3.php';
            }
        </script>
    </body>
</html>