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

$keywords = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $keywords = $_POST['keywords'];
    $searchStr = $_POST['searchStr'];
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
                <div class="page-title">Research Topics<button onclick="addNew();">+</button></div>
                <div style="overflow: auto">
                    <form method="post" id="keysForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div>Search for <input type="text" name="searchStr" value="<?=$searchStr?>"></div>
                    <?php
                        foreach ($db->query('select id,keyword from keyword k order by keyword') as $row) {
                    ?>
                        <div><input type="checkbox" name="keywords[]" value="<?=$row['id']?>" onclick="didCheckBox();" <?php if (isset($keywords) && in_array($row['id'], $keywords)) echo "checked";?>/><?=$row['keyword']?></div>
                    <?php
                        }
                    ?>
                        <div><button>Search</button></div>
                    </form>
                </div>
                <div>
                    <?php
                        if ( (isset($keywords) && count($keywords) > 0) || isset($searchStr)) {
                            error_log("----------keywords[0]: " . $keywords[0]);
                            // start with one of the keywords and then reduce the list by the others
                            $query = 'select t.id, t.topic, t.researcher_id, t.notes from topic t, topic_keyword tk';
                            $word = ' where '; 
                            if (isset($keywords) && count($keywords) > 0) {
                                $query = $query . $word . 't.id = tk.topic_id and tk.keyword_id = :kw';
                                $word = ' and ';
                            }
                            if ( isset($searchStr) && $searchStr!=='') {
                                error_log("searchStr is set: " . $searchStr);
                                $searchStr = "%" . $searchStr . "%";
                                $query = $query . $word . " ( t.topic like :searchStr or t.notes like :searchStr )";
                            }
                            $stmt = $db->prepare($query);
                            if (isset($keywords) && count($keywords) > 0) {
                                $stmt->bindParam(':kw', $keywords[0]);
                            }
                            if ( isset($searchStr) && $searchStr!=='') {
                                $stmt->bindParam(":searchStr", $searchStr);
                            }
                            $stmt->execute();
                            $topics = array();
                            $rslt = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                            while ( $row = $stmt->fetch() ) { 
                                $topics[$row['id']] = $row;
                            }
                            
                            for ( $x = 1; $x < count($keywords); $x++ ) {
                                
                                $stmt->bindParam(':kw', $keywords[$x]);
                                $stmt->execute();
                                
                                $newTopics = array();
                                while ( $row = $stmt->fetch() ) { 
                                    if ( isset($topics[$row['id']]) ) {
                                        $newTopics[$row['id']] = $row;
                                    }
                                }
                                $topics = $newTopics;
                            }
                                                        
                            foreach ($topics as $topic) {
                                error_log($topic['topic']);
                                echo "<div onclick='gotoTopic(" . $topic['id'] . ");'>" . $topic['topic'] . "</div>";
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            function didCheckBox() { 
                document.getElementById("keysForm").submit();
            }
            
            function gotoTopic(id){
                window.location.href = 'week6detail.php?id=' + id;
            }
            function addNew() {
                window.location.href = 'week6detail.php';
            }
        </script>
    </body>
</html>


