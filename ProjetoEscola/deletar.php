<?php 
include 'db.php';

$id = $_GET['id'];

$query = "DELETE FROM alunos WHERE id = $id";

if($conn->query($query) === TRUE){
    header('Location: index.php');
}else{
    echo "Erro ao deletar: ", $conn->error;
}

$conn-> close();

?>