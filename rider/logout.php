<?php

session_start();
session_unset();
session_status();
header("location:  ../index.php");
exit();

?>
