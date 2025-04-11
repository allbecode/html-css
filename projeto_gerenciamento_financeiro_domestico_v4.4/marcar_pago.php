<?php
include 'db_connection.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    
    $sql = "UPDATE transacoes SET pago = 1 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
