<?php
// require_once '../includes/db_connection.php';
// header('Content-Type: application/json');

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
//     $id = $_POST['id'];

//     $stmt = $pdo->prepare("DELETE FROM transacoes WHERE id = :id");
//     $stmt->bindParam(':id', $id, PDO::PARAM_INT);

//     if ($stmt->execute()) {
//         echo json_encode(['status' => 'ok']);
//     } else {
//         echo json_encode(['status' => 'erro', 'mensagem' => 'Falha ao excluir.']);
//     }
// } else {
//     echo json_encode(['status' => 'erro', 'mensagem' => 'Requisição inválida.']);
// }




require_once '../includes/db_connection.php';
require_once '../includes/session.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não autenticado.']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

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


