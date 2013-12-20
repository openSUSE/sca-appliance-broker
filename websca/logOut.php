<?php
#Close session
session_start();
session_destroy();
header('Location: http://localhost/sca/login.php');
?>