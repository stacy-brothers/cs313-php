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
        error_log("no id passed in to GET...");
        header('Location: ./search.php');
        die();
    }
    $keywordIds = loadKeywords($db, $id);
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    $id = $_POST['id'];
    if ( isset($id) && $id!=='' ) {
        // kind of a klugy way to implement but just blast away the old values and add the posted ones.
        $keywords = $_POST['keywords'];
        print_r($keywords);
        $keywordIds = loadKeywords($db, $id);
        // delete the old list 
        $delSql = "delete from topic_keyword where topic_id = :id";
        $delStmt = $db->prepare($delSql);
        $delStmt->bindParam(':id', $id);
        $delStmt->execute();
        // now add the new values
        $insSql = "insert into topic_keyword (topic_id,keyword_id) values (:topicId, :keyId)";
        $insStmt = $db->prepare($insSql);
        foreach ($keywords as $keyId) {
            $insStmt->bindParam(':topicId', $id);
            $insStmt->bindParam(':keyId', $keyId);
            $insStmt->execute();
        }
        // now add a new keyword if there is one.
        $newKey = $_POST['newKey'];
        if ( isset($newKey) && strlen(trim($newKey)) > 0 ) {
            $newSql = "insert into keyword (keyword) values (:newKey)";
            $newStmt = $db->prepare($newSql);
            $newStmt->bindParam("newKey", $newKey);
            if ($newStmt->execute()) {
                // add it to the 
                $newId = $db->lastInsertId();
                $insStmt->bindParam(':topicId', $id);
                $insStmt->bindParam(':keyId', $newId);
                $insStmt->execute();
            }
        }
        $keywordIds = loadKeywords($db, $id);        
    } else {
        error_log("no id passed in to POST...");
        header('Location: ./search.php');
        die();
    }
}

function loadKeywords($db, $tId) {
    $keyQuery = 'select k.id, k.keyword from keyword k, topic_keyword tk where k.id = tk.keyword_id and tk.topic_id = :id';
    $keyStmt = $db->prepare($keyQuery);
    $keyStmt->bindParam(':id', $tId);
    $keyStmt->execute();
    $comma="  ";                                
    foreach( $keyStmt->fetchAll() as $keyRow ) {
        $newId = $keyRow['id'];
        if ( !isset($keywordIds)) {
            $keywordIds = array("".$newId);
        } else {
            array_push($keywordIds,"".$keyRow['id']);
        }                                    
    }
    return $keywordIds;
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
        <link rel="stylesheet" type="text/css" href="./detail.css">
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
                        $query = 'select id, topic, researcher_id, notes from topic ';
                        $query = $query . 'where id = :id';
                        $stmt = $db->prepare($query);
                        $stmt->bindParam(':id', $id);
                        $stmt->execute();
                        $stmt->setFetchMode(PDO::FETCH_ASSOC);
                        $row = $stmt->fetch();
                        $topic = $row['topic'];
                        $notes = $row['notes'];
                    }
                ?>
                <div class="page-title"><span><span onclick="goBack();" style='float:left;'><i class='fas fa-chevron-left'></i></span>Adding keywords for topic: <?=$topic?></div>
                <form id="detailForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">                    
                <input type="hidden" name="id" value="<?=$id?>">
                <div>
                <div class="label-row"><span>New Keyword:</span><span class="topic-input"><input type="text" name="newKey"></span><button class="save-btn">ADD</button></div>
                <div class="spacer"></div>
                <div class="label-row">Keywords<button class="save-btn">SAVE</button></div>
                <div class="key-list">
                <?php 

                        $allQuery = " select id, keyword from keyword";
                        foreach ($db->query($allQuery) as $key) {
                ?>
                    <div class="key-item"><input type="checkbox" name="keywords[]" value="<?=$key['id']?>" <?php if (isset($keywordIds) && in_array($key['id'], $keywordIds)) echo "checked";?>><?=$key['keyword']?></div>
                <?php
                        }                    
                ?>
                        </div>
                        <br>                    
                </div>
                <input type="hidden" name="addKeys" id="addKeys">                
                </form>
            </div>
        </div>
        <script type="text/javascript">
            function goBack() { 
                window.location = 'detail.php?id=<?=$id?>';
            }
            function addKeyword() {
                document.getElementById("addKeys").value = "TRUE";
                document.getElementById("detailForm").submit();
            }
        </script>
    </body>
</html>


