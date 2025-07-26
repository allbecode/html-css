<?php
require 'functions.php';

session_start();
registrarLog($_SESSION['usuario'], 'logout');
session_unset();
session_destroy();
header('Location: login.php');
exit;
