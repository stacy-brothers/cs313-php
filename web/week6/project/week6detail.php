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

$allEmpty = FALSE;
$topic = "New Topic";
$notes = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // editing or adding a new topic
    $id = $_GET['id'];
    if ( !isset($id) ) {
         // must be a new topic
        $allEmpty = TRUE;
    }
    error_log("doing GET");
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    error_log("doing POST");
    // updating a topic
    $id = $_POST['id'];
    if (empty($_POST["topic"])) {
        $topicErr = "Topic is required";
        $good = FALSE;
    } else {
        $topic = fix_input($_POST["topic"]);        
    }
    $notes = fix_input($_POST['notes']);
    if ( isset($id) && $id!=='' ) {
        error_log("updating an old topic");
        // updating an old topic
        $updateSql = "update topic set topic=:topic, notes=:notes where id=:id";
        try {
            $stmt = $db->prepare($updateSql);
            $stmt->execute(array($topic,$notes,$id));         
        } catch (PDOException $ex) {
            $addError = "Error updating topic: " . $ex->getMessage();
            $good = FALSE;
        }
    } else {
        error_log("adding a new topic");
        // adding a new topic
        $insertSql = "insert into topic (topic, notes) values (:topic,:notes)";
        try {
            $stmt = $db->prepare($insertSql);
            if ( $stmt->execute(array($topic,$notes)) === TRUE ) {
                $id = $db->lastInsertId();                             
                error_log("save was successful!");
            }
        } catch (PDOException $ex) {
            error_log("error adding: " . $ex->getMessage());
            $addError = "Error updating topic: " . $ex->getMessage();
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
        <link rel="stylesheet" type="text/css" href="../../index.css">
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
                <?php                    
                    if ( isset($id) ) {
                        error_log("----------id: " . $id);
                        // start with one of the keywords and then reduce the list by the others
                        $query = 'select id, topic, researcher_id, notes from topic ';
                        $query = $query . 'where id = :id';
                        $stmt = $db->prepare($query);
                        $stmt->bindParam(':id', $id);
                        $stmt->execute();
                        $stmt->setFetchMode(PDO::FETCH_ASSOC);
                        $row = $stmt->fetch();
                        error_log("-----------again:" . $row['id']);
                        $topic = $row['topic'];
                        $notes = $row['notes'];
                    }
                ?>
                <div class="page-title"><button onclick="goBack();"><</button><?=$topic?></div>
                <form action="week6detail.php" method="POST">                    
                <input type="hidden" name="id" value="<?=$id?>">
                <div>
                        <div>Topic</div><div><input type="text" name="topic" value="<?=$topic?>"><br></div>
                        <div>Notes</div><div><textarea name="notes" cols="80" rows="20"><?=$notes?></textarea><br></div>
                        <?php 
                            if ( !$allEmpty ) {
                        ?>
                        <div><button>save</button></div>
                        <div>Keywords:
                        <?php 
                                $keyQuery = 'select k.keyword from keyword k, topic_keyword tk where k.id = tk.keyword_id and tk.topic_id = :id';
                                $keyStmt = $db->prepare($keyQuery);
                                $keyStmt->bindParam(':id', $id);
                                $keyStmt->execute();
                                foreach( $keyStmt->fetchAll() as $keyRow ) {
                                    echo ' ' . $keyRow['keyword'];
                                }
                        ?>
                            <button onclick="addKeyword();">Add</button></div>
                <?php
                            }
                    
                ?>
                        <br>                    
                </div>
                </form>
            </div>
        </div>
        <script type="text/javascript">
            function goBack() { 
                window.location = 'week6.php';
            }
            function addKeyword() {
                
            }
        </script>
    </body>
</html>


