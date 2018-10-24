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
                            $query = 'select t.id, t.topic, t.researcher_id, t.notes from topic t, topic_keyword tk ';
                            $query = $query . 'where t.id = :id';
                            $stmt = $db->prepare($query);
                            $stmt->bindParam(':id', $id);
                            $stmt->execute();
                            $rslt = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                            $row = $stmt->fetch();
                            error_log("-----------again:" . $row['id']);                                                        
                            echo '<div>' . $row['topic'] . "</div><div>" .  $row['notes'] . "</div>";
                        }
                    ?>
                </div>
            </div>
        </div>

        </script>
    </body>
</html>


