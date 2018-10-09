<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>

<?php
error_log("Resetting the session!");
session_unset(); 
session_destroy(); 
?>
    The session should have been reset!

</body>
</html>