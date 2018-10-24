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

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
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
                <div class="page-title">Research Topics</div>
                <div>
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
                            echo '<div>Topic: ' . $row['topic'] . "</div>";
                            echo '<div>Notes:' .  $row['notes'] . "</div>";
                            // get the keywords...
                            echo '<div>Keywords:'  ;
                            $keyQuery = 'select k.keyword from keyword k, topic_keyword tk where k.id = tk.keyword_id and tk.topic_id = :id';
                            $keyStmt = $db->prepare($keyQuery);
                            $keyStmt->bindParam(':id', $id);
                            $keyStmt->execute();
                            foreach( $keyStmt->fetchAll() as $keyRow ) {
                                echo ' ' . $keyRow['keyword'];
                            }
                            // get the reference urls...
                            
                        }
                    ?>
                </div>
            </div>
        </div>

        </script>
    </body>
</html>


