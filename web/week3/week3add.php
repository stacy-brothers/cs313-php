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
    
    $row = $_GET["rowNum"];
    error_log("row: " . $row);
    error_log($gameList[$row]["title"]);
?>
<html>
    <head>
        <title>CS 313 Assignments Portfolio</title>
        <link rel="stylesheet" type="text/css" href="../index.css">
        <link rel="stylesheet" type="text/css" href="./week3.css">
        <link rel="stylesheet" type="text/css" href="./week3add.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    </head>
    <body>
        <div class="clearfix">
            <div class="header">
                <div class="header-container">
                    <div class="header-text">Stacy Brothers - CS 313</div>                        
                </div>
            </div>
            <form action="week3.php">
            <div class="content">
                <div class="page-title"><img src="../images/Atari_2600_logo.svg">&nbsp;&nbsp;Game Store</div>
                <div class="gameDetail">
                    <div class="listing" >
                        <img class="gamebox" src="<?=$gameList[$row]["img"]?>">
                        <div class="gameinfo">
                            <div class="gameTitle"><?=$gameList[$row]["title"]?></div>
                            <div class="gameGenre">Genre: <?=$gameList[$row]["genre"]?><br></div>
                            <div class="gameGenre">Details:  A whole lot more details should go here.<br></div>
                            <div class="gamePrice">Price: $<?=$gameList[$row]["price"]?></div>
                            <div class="addBtnBox">Quantity in cart: <input type="number" id="inCart" name="inCart" value="<?=$gameList[$row]["inCart"]?>"></div>
                            <div class="addBtnBox">
                                <button class="addBtn" >SAVE CART</button>&nbsp;&nbsp;
                                <button class="addBtn" onclick="clearItem();">CLEAR</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <input type="hidden" name="rowNum" value="<?=$row?>">
            </form>
        </div>
        <script type="text/javascript">
            function clearItem() {
                document.getElementById('inCart').value = 0;
                document.getElementById("addForm").submit();
            }
        </script>
    </body>
</html>