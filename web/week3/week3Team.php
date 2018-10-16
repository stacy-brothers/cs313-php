

<!DOCTYPE HTML>  
<html>
<head>
    <title>Team 7 Week 3</title>
    <style> 
        .error {
            color:red;
        }
    </style>
</head>
<body>  
    
<?php
// define variables and set to empty values
$nameErr = $emailErr = $majorErr = "";
$name = $email = $major = $comment = "";
$cb1 = [];
$countries = array("NA"=>"North America", "SA"=>"South America", "E"=>"Europe");
$majorList = array("Computer Science", "Web Design", "Computer IT", "Computer Engineer");
$good = TRUE;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
    $good = FALSE;
  } else {
    $name = test_input($_POST["name"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
      $nameErr = "Only letters and white space allowed";
      $good = FALSE;
    }
  }
  
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
    $good = FALSE;
  } else {
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format"; 
      $good = FALSE;
    }
  }

  if (empty($_POST["comment"])) {
    $comment = "";
  } else {
    $comment = test_input($_POST["comment"]);
  }

  if (empty($_POST["major"])) {
    $majorErr = "Major is required";
    $good = FALSE;
  } else {
    $major = test_input($_POST["major"]);
  }
  
  $cb1 = $_POST["cb1"];
  
} else {
    $good = FALSE;
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  Name: <input type="text" name="name" value="<?php echo $name;?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  E-mail: <input type="text" name="email" value="<?php echo $email;?>">
  <span class="error">* <?php echo $emailErr;?></span>
  <br><br>
  Major:<br>
  <?php foreach ( $majorList as $m ) { ?>
  <input type="radio" name="major" <?php if (isset($major) && $major==$m) echo "checked";?> value="<?=$m?>"><?=$m?>
  <?php } ?>
    <span class="error">* <?php echo $majorErr;?></span>
  <br><br>
  <?php foreach ($countries as $key=>$value) { ?>
  <input type="checkbox" value="<?=$key?>" name="cb1[]" <?php if (isset($cb1) && in_array($key, $cb1)) echo "checked";?> ><?=$value?><br>
  <?php } ?>

  <br><br>
  Comment:<br> <textarea name="comment" rows="5" cols="40"><?php echo $comment;?></textarea>
  <br><br>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>
<?php 
if ( $good ) {
    echo "<h2>Your Input:</h2>";
    echo "Name: $name";
    echo "<br>";
    echo "Email: <a href='mailto:$email'>$email</a>";
    echo "<br>";
    echo "Comments: $comment";
    echo "<br>";
    echo "Major: $major";
    echo "<br>";
    if ( $cb1 ) echo "Countries visited: ";
    $comma = "";
    foreach ($cb1 as $value) {
        echo $comma . $countries[$value];    
        $comma = ", ";
    }
}
?>

</body>
</html>
