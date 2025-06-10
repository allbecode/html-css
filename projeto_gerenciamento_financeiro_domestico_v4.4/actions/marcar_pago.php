<?php
header('Content-Type: application/json');
require_once '../includes/db_connection.php';

$id = $_POST['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("UPDATE transacoes SET pago = 1 WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['status' => 'ok']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro no banco de dados']);
    }
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'ID inválido']);
}