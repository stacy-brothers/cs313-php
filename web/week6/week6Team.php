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

$book = $chapter = $verse = $content = "";
$bookErr = $chapterErr = $verseError = $contentErr = "";
$topics = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        error_log("Doing POST!");
        if (empty($_POST["book"])) {
            $bookErr = "Book is required";
            $good = FALSE;
        } else {
            $book = fix_input($_POST["book"]);
            if (!preg_match("/^[0-9a-zA-Z ]*$/",$book)) {
                $bookErr = "Only numbers, letters and white space allowed";
                $good = FALSE;
            }
        }
        if (empty($_POST["chapter"])) {
            $chapterErr = "Chapter is required";
            $good = FALSE;
        } else {
            $chapter = fix_input($_POST["chapter"]);
            if (!preg_match("/^[0-9]*$/",$chapter)) {
                $chapterErr = "Only numbers allowed";
                $good = FALSE;
            }
        }
        if (empty($_POST["verse"])) {
            $verseErr = "Verse is required";
            $good = FALSE;
        } else {
            $verse = fix_input($_POST["verse"]);
            if (!preg_match("/^[0-9]*$/",$verse)) {
                $verseErr = "Only numbers allowed";
                $good = FALSE;
            }
        }
        if (empty($_POST["content"])) {
            $contentErr = "Content is required";
            $good = FALSE;
        } else {
            $content = fix_input($_POST["content"]);
        }
        
        $topics = $_POST["topics"];
        
        error_log( "Adding: " . $book . " " . $chapter . ":" . $verse);
        error_log( "Content: " . $content);
        error_log("Topics: " . $topics);
        
        // add the scripture
        $insertSql = "insert into scripture ( book, chapter, verse, content ) values ( :book, :chapter, :verse, :content )";
        try {
            $stmt = $db->prepare($insertSql);
            if ( $stmt->execute(array($book, $chapter, $verse, $content)) === TRUE ) {
                $scriptId = $db->lastInsertId();
                // add the s_t_xref
                $insertXrefSql = "insert into s_t_xref ( topics_id, scripture_id ) values ( ?, ? ) ";
                $newInsert = $db->prepare($insertXrefSql);
                foreach($topics as $topicId) { 
                    $newInsert->execute(array($topicId,$scriptId));
                }                
            }
        } catch (PDOException $ex) {
            echo "--------- Error adding scripture: " . $ex->getMessage();
            $addError = "Error adding scripture: " . $ex->getMessage();
            $good = FALSE;
        }
        
        // if successful then go to the list
        if ( $good === TRUE ) { 
            header('Location: week6TeamList.php', true, 303);
            die();
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
                <div><span class="error"><?=$addErr?></span></div>
                <form action="week6Team.php" method="POST">
                    <div>
                        <div>Book <span class="error"><?=' - ' . $bookErr;?></span></div><div><input name='book' id='book' type="text" value="<?=$book?>"></div>
                    </div>
                    <div>
                        <div>Chapter <span class="error"><?=' - ' . $chapterErr;?></span></div><div><input name='chapter' id='chapter' type="text" value="<?=$chapter?>"></div>
                    </div>
                    <div>
                        <div>Verse <span class="error"><?=' - ' . $verseErr;?></span></div><div><input name='verse' id='verse' type="text" value="<?=$verse?>"></div>
                    </div>
                    <div>
                        <div>Content <span class="error"><?=' - ' . $contentErr;?></span></div><div><textarea name='content' id='content' type="text" cols="50" rows="5"><?=$content?></textarea></div>
                    </div>
                    <div>
                        <div>Topics</div>
                        <div>
                            <?php
                                foreach ($db->query('SELECT id, name FROM script_topics') as $row) {
                                    echo '<input type="checkbox" name="topics[]" value="' . $row['id'] . '" ' . ((isset($topics) && in_array($row['id'], $topics))?"checked":"") . '>' . $row['name'] . ' ';                    
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

