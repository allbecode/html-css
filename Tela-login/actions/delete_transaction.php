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

// 🔒 Verifica se a transação está vinculada a uma manutenção
    $stmtCheck = $pdo->prepare("
        SELECT m.id AS manutencao_id
        FROM transacoes t
        LEFT JOIN manutencoes_carro m ON m.transacao_id = t.id
        WHERE t.id = :id AND t.usuario_id = :usuario_id
    ");
    $stmtCheck->execute([
        ':id' => $id,
        ':usuario_id' => $usuario_id
    ]);
    $vinculo = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($vinculo && !empty($vinculo['manutencao_id'])) {
        echo json_encode([
            'status' => 'erro',
            'mensagem' => 'Esta transação está vinculada a uma manutenção e não pode ser excluída pelo módulo financeiro.'
        ]);
        exit;
    }

    // 🔹 Só executa se não tiver vínculo
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


