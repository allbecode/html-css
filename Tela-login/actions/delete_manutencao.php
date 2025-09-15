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
$dados = $_POST;

if (!isset($dados['id'], $dados['carro_id'])) {
    echo json_encode(['status' => 'error', 'mensagem' => 'Parâmetros inválidos.']);
    exit;
}

$manutencaoId = (int)$dados['id'];
$carroId = (int)$dados['carro_id'];

try {
    // 🔎 Buscar transacao_id vinculado antes de excluir
    $stmt = $pdo->prepare("SELECT transacao_id FROM manutencoes_carro WHERE id = :id AND carro_id = :carro_id AND usuario_id = :usuario_id");
    $stmt->execute([
        'id'         => $manutencaoId,
        'carro_id'   => $carroId,
        'usuario_id' => $usuarioId
    ]);
    $manutencao = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$manutencao) {
        echo json_encode(['status' => 'error', 'mensagem' => 'Manutenção não encontrada.']);
        exit;
    }

    // 🗑️ Excluir manutenção
    $stmt = $pdo->prepare("DELETE FROM manutencoes_carro WHERE id = :id AND carro_id = :carro_id AND usuario_id = :usuario_id");
    $stmt->execute([
        'id'         => $manutencaoId,
        'carro_id'   => $carroId,
        'usuario_id' => $usuarioId
    ]);

    // 🗑️ Se houver transação vinculada, excluir também
    if (!empty($manutencao['transacao_id'])) {
        $stmt = $pdo->prepare("DELETE FROM transacoes WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->execute([
            'id'         => $manutencao['transacao_id'],
            'usuario_id' => $usuarioId
        ]);
    }

    echo json_encode(['status' => 'success', 'mensagem' => 'Manutenção (e transação vinculada) excluída com sucesso!']);
} catch (Throwable $e) {
    echo json_encode(['status' => 'error', 'mensagem' => 'Erro ao excluir manutenção: ' . $e->getMessage()]);
}
