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

$user = $pass = "";
$userError = $passError = "";
$good = TRUE;

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    error_log("doing POST");
    
    if (empty($_POST["user"])) {
        $userErr = "User is required";
        $good = FALSE;
    } else {
        $user = fix_input($_POST["user"]);        
    }
    if (empty($_POST["pass"])) {
        $passErr = "Pass is required";
        $good = FALSE;
    } else {
        $pass = fix_input($_POST["pass"]);        
    }
    
    if ( $good ) {
        error_log("logging user in.");
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $query = "insert into users (name, pass) values (:name, :pass)";
        try {
            error_log("starting add");
            $stmt = $db->prepare($query);
            if ($stmt->execute(array($user,$hash))) {
                error_log("Add success!");
                header('Location: ./week7TeamLogin.php');
                die();
            } else { 
                error_log("add fail!");
                $loginErr = "There was a problem logging in.  Check you user and pass and try again...";
            }
        } catch (PDOException $ex) {
            $loginError = "Error logging in.";
            error_log("Error logging in: " . $ex->getMessage());
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
    </head>
    <body>
        <div class="clearfix">
            <div class="header">
                <div class="header-container">
                    <div class="header-text">Stacy Brothers - CS 313</div>                        
                </div>
            </div>
            <div class="content">
                <div class="error"><?=$loginErr?></div>
                <div>Create Account.  Please enter user and password.</div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                    <div>user</div><div><input type="text" name="user" value="<?=$user?>"></div>
                    <div>pass</div><div><input type="password" name="pass" value="<?=$user?>"></div>
                    <div><button>submit</button></div>
                </form>
                
            </div>
        </div>
    </body>
</html>
