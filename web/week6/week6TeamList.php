<?php

    try
    {
      $dbUrl = getenv('DATABASE_URL');

      $dbOpts = parse_url($dbUrl);

      $dbHost = $dbOpts["host"];
      $dbPort = $dbOpts["port"];
      $dbUser = $dbOpts["user"];
      $dbPassword = $dbOpts["pass"];
      $dbName = ltrim($dbOpts["path"],'/');

      $db = new PDO("pgsql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);

      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $ex)
    {
      echo 'Error!: ' . $ex->getMessage();
      die();
    }
    
    $book = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        error_log("Doing POST!");
        if (empty($_POST["book"])) {
            $bookErr = "Book is required";
            $good = FALSE;
        } else {
            $book = fix_input($_POST["book"]);
            // check if book only contains number, letters and whitespace
            if (!preg_match("/^[0-9a-zA-Z ]*$/",$book)) {
                $bookErr = "Only numbers, letters and white space allowed";
                $good = FALSE;
            }
        }
    } 
    
    function fix_input($data) {
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
                <div class="page-title">Add a new Scripture</div>
                <form action="week6TeamAdd.php" method="POST">
                    <div>
                        <div>Book <span class="error"><?=' - ' . $bookErr;?></span></div><div><input id='book' type="text"></div>
                    </div>
                    <div>
                        <div>Chapter</div><div><input id='chapter' type="text"></div>
                    </div>
                    <div>
                        <div>Verse</div><div><input id='verse' type="text"></div>
                    </div>
                    <div>
                        <div>Content</div><div><textarea id='content' type="text" cols="50" rows="5"></textarea></div>
                    </div>
                    <div>
                        <div>Topics</div>
                        <div>
                            <?php
                                foreach ($db->query('SELECT id, name FROM script_topics') as $row) {
                                    echo '<input type="checkbox" id="topics[]" value="' . $row['id'] . '">' . $row['name'] . ' ';                    
                                }
                            ?>
                        </div>
                    </div>
                    <div>
                        <button>Add</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>


