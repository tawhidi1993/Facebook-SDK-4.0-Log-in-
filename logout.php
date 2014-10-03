<?php
session_start();
session_destroy();
header('location:fb_login.php');
?>