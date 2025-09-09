<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../acsses_control/includes/db.php';
require_once '../acsses_control/includes/session.php';
require_once '../acsses_control/includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

verificaUsuarioLogado();

$usuarioId = $_SESSION['usuario_id'] ?? 0;
$id = $_POST['id'] ?? null;
$carroId = $_POST['carro_id'] ?? null;

if (!$id || !$carroId) {
    echo json_encode(['status' => 'error', 'mensagem' => 'Parâmetros inválidos.']);
    exit;
}

try {
    // Só exclui se a manutenção for do usuário e do carro informado
    $stmt = $pdo->prepare("
        DELETE FROM manutencoes_carro
        WHERE id = :id AND carro_id = :carro_id AND usuario_id = :usuario_id");
    $stmt->execute([
        'id' => (int)$id,
        'carro_id' => (int)$carroId,
        'usuario_id' => $usuarioId
    ]);

    echo json_encode(['status' => 'success', 'mensagem' => 'Manutenção excluída com sucesso!']);
} catch (Throwable $e) {
   echo json_encode(['status' => 'error', 'mensagem' => 'Erro ao excluir manutenção: ' . $e->getMessage()]);
}
