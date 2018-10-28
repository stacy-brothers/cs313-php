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
                <div class="page-title">Scripture List</div>
                    <div>
                        <?php
                            foreach ($db->query('select id, book, chapter, verse, content FROM scripture') as $row) {
                                $start = "Topics: ";
                                $topics = "";
                                $comma = "";
                                $xrefQuery = "select s.name from script_topics s, s_t_xref x where s.id = x.topics_id and x.scripture_id = ?";
                                $xrefStmt = $db->prepare($xrefQuery);
                                $xrefStmt->execute(array($row['id']));
                                $rslt = $xrefStmt->fetchAll();
                                foreach ( $rslt as $trow) {
                                    $topics = $start . $topics . $comma . $trow['name'];
                                    $comma = ", ";
                                    $start = "";
                                }
                        ?>
                        <div>
                            <h3><b><?=$row['book'] . ' ' . $row['chapter'] . ':' . $row['verse']?></b></h3>&nbsp;&nbsp;&nbsp;<?=$topics?>
                        </div>
                        <div><?=$row['content']?></div>
                        <div>&nbsp;</div>
                        <?php 
                            }
                        ?>
                        
                    </div>
                    <div>
                        <button onclick="gotoAdd();">Add Another Scripture</button>
                    </div>
                </form>
            </div>
        </div>
        <script type="text/javascript">
            function gotoAdd() {
                window.location = 'week6Team.php';
            }
        </script>
    </body>
</html>


