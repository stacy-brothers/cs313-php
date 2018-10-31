<?php
    $descr = array(
        "Week 1 - Hello World!",
        "Week 2 Team - Reviewing client side html constructs.",
        "Week 2 Assignment - Something that interests me...", 
        "Week 3 Team - PHP Forms.",
        "Week 3 - Store front.",
        "Week 4 Team - Conference SQL",
        "Week 4 - Project DB", 
        "Week 5 Team - Scripture DB",
        "Week 5 - Project DB query screens",
        "Week 6 Team - Add a scripture",
        "Week 6 - Project add data",
        "Week 7 Team - Login screen",
        "Completed Project"
    );
    
    $url = array(
        "hello.html",
        "week2/week2.html",
        "something.php",
        "week3/week3Team.php",
        "week3/week3.php", 
        "sql/week4Team.sql",
        "sql/researchDB.sql",
        "week5/week5Team.php",
        "week5/week5.php",
        "week6/week6Team.php",
        "week6/project/week6.php",
        "week7/week7TeamLogin.php",
        "project/search.php"
    );
    
    $notes = array(
        "This is the classic hello world assignment.  The big thing here was to figure out GitLab, Huroku.",
        "A basic review of what was done in the class before this one.  Basically just a web page that has some CSS applied to it and a little bit of JavaScript.  It was a supposed to be a \"Team\" assignment but we didn't have teams yet so I did it all myself. Code on <a href='https://github.com/stacy-brothers/cs313-php/blob/master/web/week2/week2.html'>GitHub</a>.",
        "This assignment was to build this page and another page that was about something that interests me.  The toughest part of this assignment was deciding which of the too many things that interest me to use.  As I am typing this I still don't know what it is going to be.  Click the chevron at the right to find out! Code on <a href='https://github.com/stacy-brothers/cs313-php/blob/master/web/something.php'>GitHub</a>.",
        "In this assignment we create an html form and then post it to the same page on submit.  Php code validates input and cleans it up to protect from injection attacks.  Majors are stored in an array.  Countries are stored in an associative array.  Inputs are generated dynamically off the arrays.  Code on <a href='https://github.com/stacy-brothers/cs313-php/blob/master/web/week3/week3Team.php'>GitHub</a>.",
        "Atari 2600 classic game cartridge store front. Code on <a href='https://github.com/stacy-brothers/cs313-php/blob/master/web/week3'>GitHub</a>.",
        "SQL for Conference DB.  <a href='https://github.com/stacy-brothers/cs313-php/blob/master/web/sql/week4Team.sql'>Github</a>",
        "SQL for term project. <a href='https://github.com/stacy-brothers/cs313-php/blob/master/web/sql/researchDB.sql'>Github</a>",
        "Listing of values from scripture postgres DB. <a href='https://github.com/stacy-brothers/cs313-php/blob/master/web/week5'>Github</a>",
        "Start of term project.  Search and detail screen.  <a href='https://github.com/stacy-brothers/cs313-php/blob/master/web/week5'>Github</a>",
        "Adding a scripture and topics to our team project. <a href='https://github.com/stacy-brothers/cs313-php/blob/master/web/week6'>Github</a>",
        "Adding the ability to add and edit on our project. <a href='https://github.com/stacy-brothers/cs313-php/blob/master/web/week6/project'>Github</a>",
        "Example of a login screen. <a href='https://github.com/stacy-brothers/cs313-php/blob/master/web/week7'>Github</a>",
        "Final version of the first project. <a href='https://github.com/stacy-brothers/cs313-php/blob/master/web/project'>Github</a>"
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