<?php
require_once '../acsses_control/includes/db.php';
require_once '../acsses_control/includes/session.php';
header('Content-Type: application/json');
require_once '../acsses_control/includes/auth.php';
require_once '../acsses_control/includes/functions.php';
require_once '../includes/functions.php';

// Garante que o usuário está logado
verificaUsuarioLogado();

$usuario_id = $_SESSION['usuario_id'] ?? $_SESSION['id'] ?? null; // Captura o ID do usuário logado

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $pdo->prepare("DELETE FROM transacoes WHERE id = :id AND usuario_id = :usuario_id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Transação não encontrada ou não autorizada.']);
    }
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Requisição inválida.']);
}


