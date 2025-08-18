<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/session.php';
require_once '../includes/log_functions.php';

registrarOperacao('logout', $_SESSION['usuario']);
session_unset();
session_destroy();
header('Location: login.php');
exit;
