<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM transacoes WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        header('Location: list_transactions.php');
        exit;
    } else {
        echo "Erro ao excluir a transação.";
    }
} else {
    echo "ID não fornecido.";
}
