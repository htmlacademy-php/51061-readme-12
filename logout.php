<?php

session_start();

$_SESSION = [];
unset($_SESSION['user']);
header('Location: /index.php');
