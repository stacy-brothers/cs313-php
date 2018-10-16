<?php
    session_start();
    
    $gameList = $_SESSION["gameList"];
    
    if ( !isset($gameList) ) {
        resetGameList();
        $gameList = $_SESSION["gameList"];
        error_log("not it the session! Title is " . $gameList[0]["title"]);
    } else { 
        error_log("in the session...");
    }
    
    function resetGameList() {
        $gameList = array(
            array("title"=>"Adventure", "img"=>"../images/Adventure_Box_Front.jpg","genre"=>"Adventure","price"=>"48.55","inCart"=>"0"),
            array("title"=>"Berzerk", "img"=>"../images/Berzerk.jpg", "genre"=>"Action","price"=>"10.43","inCart"=>"0"),
            array("title"=>"Blackjack", "img"=>"../images/Blackjack.jpg", "genre"=>"Simulation","price"=>"12.00","inCart"=>"0"),
            array("title"=>"Dodge Em", "img"=>"../images/Dodge_Em.jpg", "genre"=>"Action/Racing","price"=>"8.65","inCart"=>"0"),
            array("title"=>"E.T.", "img"=>"../images/ET.jpg", "genre"=>"Adventure","price"=>"25.99","inCart"=>"0")
        );
        $_SESSION["gameList"] = $gameList;
    }
    
    $row = $_GET["rowNum"];
    $inCart = $_GET["inCart"];
    if ( isset($row) ) {
        $gameList[$row]["inCart"] = $inCart;
        $_SESSION["gameList"] = $gameList;
    }
    
?>
<html>
    <head>
        <title>CS 313 Assignments Portfolio</title>
        <link rel="stylesheet" type="text/css" href="../index.css">
        <link rel="stylesheet" type="text/css" href="./week3.css">
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
                <div class="page-title"><img src="../images/Atari_2600_logo.svg">&nbsp;&nbsp;Game Store&nbsp;&nbsp;&nbsp;<button onclick="gotoCart();">GOTO CART</button></div>
                <div class="gamelist">
                    <?php 
                        for ($i=0; $i<count($gameList); $i++) {
                    ?>
                    <div class="listing" >
                        <img class="gamebox" src="<?=$gameList[$i]["img"]?>">
                        <div class="gameinfo">
                            <div class="gameTitle"><?=$gameList[$i]["title"]?></div>
                            <div class="gameGenre">Genre: <?=$gameList[$i]["genre"]?></div>
                            <div class="gamePrice">Price: $<?=$gameList[$i]["price"]?></div>
                            <div class="addBtnBox">Quantity in cart: <?=$gameList[$i]["inCart"]?></div>
                            <div class="addBtnBox"><button class="addBtn" onclick="addItem('<?=$i?>')">ADD TO CART</button></div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>                    
                </div>
            </div>
        </div>
        <form id="addForm" action="week3add.php"><input type="hidden" name="rowNum" id="rowNum" value="-1"></form>
        <script type="text/javascript">
            function addItem(row) {
                document.getElementById("rowNum").value = row;
                document.getElementById("addForm").submit();
            }
            
            function gotoCart() { 
                console.log("going to cart?")
                window.location.href = 'week3Cart.php';
            }
        </script>
    </body>
</html>