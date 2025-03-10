<?php 
include 'db.php';

$nome = $_POST['nome'];
$nota1 = $_POST['nota1'];
$nota2 = $_POST['nota2'];
$nota3 = $_POST['nota3'];
$nota4 = $_POST['nota4'];
$media = ($nota1 + $nota2 + $nota3 + $nota4) / 4;

$query = "INSERT INTO alunos (nome, nota1, nota2, nota3, nota4, media) VALUES ('$nome', '$nota1', '$nota2', '$nota3', '$nota4', '$media')";

if ($conn->query($query) === TRUE) {
    header('Location: index.php');
} else {
    echo "Erro: " . $query . "<br>" . $conn->error;
}

$conn->close();
?>