<?php
require_once 'src/auth.php';
session_start();
session_unset();
session_destroy();
header('Location: login.php');
exit;
?>
