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
    error_log("doing GET");
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    error_log("doing POST");
    // updating a topic
    $id = $_POST['id'];
    if ( isset($id) && $id!=='' ) {
        
    } else {
        error_log("no id passed in to POST...");
        header('Location: ./search.php');
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
                <div class="page-title"><span><span onclick="goBack();" style='float:left;'><i class='fas fa-chevron-left'></i></span>Adding keywords for <?=$topic?></div>
                <form id="detailForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">                    
                <input type="hidden" name="id" value="<?=$id?>">
                <div>
                        <?php 
                            if ( !$allEmpty ) {
                        ?>
                        <div class="label-row">Keywords<button class="save-btn">SAVE</button></div>
                        <div class="key-list">
                        <?php 
                                $keyQuery = 'select k.id, k.keyword from keyword k, topic_keyword tk where k.id = tk.keyword_id and tk.topic_id = :id';
                                $keyStmt = $db->prepare($keyQuery);
                                $keyStmt->bindParam(':id', $id);
                                $keyStmt->execute();
                                $comma="  ";                                
                                foreach( $keyStmt->fetchAll() as $keyRow ) {
                                    $newId = $keyRow['id'];
                                    error_log("------ got id: " . $newId);
                                    if ( !isset($keywordIds)) {
                                        $keywordIds = array("".$newId);
                                    } else {
                                        $keywordIds = array_push($keywordIds,"".$keyRow['id']);
                                    }
                                    error_log("---------- list:" . $keywordIds);
                                    
                                }
                                
                                $allQuery = " select id, keyword from keyword";
                                foreach ($db->query($allQuery) as $key) {
                        ?>
                            <div class="key-item"><input type="checkbox" name="keywords[]" <?php if (isset($keywordIds) && in_array($key['id'], $keywordIds)) echo "checked";?>><?=$key['keyword']?></div>
                        <?php
                                }
                        ?>
                        
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


