<?php
    $descr = array(
        "Week 1 - Hello World!",
        "Week 2 Team - Reviewing client side html constructs.",
        "Week 2 Assignment - Something that interests me...", 
        "Week 3 Team - PHP Forms."
    );
    
    $url = array(
        "hello.html",
        "week2.html",
        "something.php",
        "week3Team.php"
    );
    
    $notes = array(
        "This is the classic hello world assignment.  The big thing here was to figure out GitLab, Huroku.",
        "A basic review of what was done in the class before this one.  Basically just a web page that has some CSS applied to it and a little bit of JavaScript.  It was a supposed to be a \"Team\" assignment but we didn't have teams yet so I did it all myself.",
        "This assignment was to build this page and another page that was about something that interests me.  The toughest part of this assignment was deciding which of the too many things that interest me to use.  As I am typing this I still don't know what it is going to be.  Click the chevron at the right to find out!",
        "In this assignment we create an html form and then post it to the same page on submit.  Php code validates input and cleans it up to protect from injection attacks.  Majors are stored in an array.  Countries are stored in an associative array.  Inputs are generated dynamically off the arrays."
    );

?>
<html>
    <head>
        <title>CS 313 Assignments Portfolio</title>
        <link rel="stylesheet" type="text/css" href="./index.css">
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
                <div class="page-title">Assignment Portfolio</div>
                <div>
                    <?php
                        for($i = 0; $i < count($descr); $i++) {
                    ?>
                        <div>                        
                            <div class="list-header" onclick="location.href='<?=$url[$i]?>'">
                                <div class="list-header-text"><?=$descr[$i]?></div>
                                <div class="list-header-button"><i class="fas fa-chevron-right"></i></div>
                            </div>
                            <div class="list-body"><?=$notes[$i]?></div>
                        </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>