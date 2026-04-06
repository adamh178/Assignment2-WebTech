<?php
session_start();
// clear the session and send the user back to the homepage
session_destroy();
header("Location: index.php");
exit();
?>
