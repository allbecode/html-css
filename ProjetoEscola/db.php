<?php 
$host =  'localhost';
$user = 'root';
$password = '';
$database = 'projeto_escola';

$conn = new mysqli($host, $user, $password, $database);

if($conn->connect_error){
    die("A Conexão Falhou!!! ". $conn->connect_error);
}
?>