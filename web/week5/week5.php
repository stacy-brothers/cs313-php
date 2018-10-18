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
    error_log("----------keywords: " . $keywords);
    $comma = "";
    foreach ( $keywords as $keyId ) {
        $keys = $keys . $comma . $keyId;
        $comma = ",";
    }
    error_log('-----------keys list:' . $keys );
    
    $query = 'select t.id, t.topic, t.researcher_id, t.notes from topic t, topic_keyword tk ';
    $query = $query . 'where t.id = tk.topic_id and tk.keyword_id in (' . $keys . ')';
    
    $topics = $db->query($query);
    foreach ($topics as $topic) {
        error_log($topic['topic']);
    }
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
                <div style="overflow: auto">
                    <form method="post" id="keysForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <?php
                        foreach ($db->query('select id,keyword from keyword k order by keyword') as $row) {
                    ?>
                        <div><input type="checkbox" name="keywords[]" value="<?=$row['id']?>" onclick="didCheckBox();" <?php if (isset($keywords) && in_array($row['id'], $keywords)) echo "checked";?>/><?=$row['keyword']?></div>
                    <?php
                        }
                    ?>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            function didCheckBox() { 
                alert("submitting!");
                document.getElementById("keysForm").submit();
            }
        </script>
    </body>
</html>


