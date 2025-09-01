<?php
require_once '../includes/manutencoes_functions.php';
require_once '../includes/session.php';

$acao = $_POST['acao'] ?? '';

if ($acao === 'listar') {
    $carroId = (int) $_POST['carro_id'];
    $usuarioId = $_SESSION['usuario_id'];
    $manutencoes = getManutencoesPorCarro($pdo, $carroId, $usuarioId);
    echo json_encode(['status' => 'success', 'data' => $manutencoes]);
    exit;
}

if ($acao === 'adicionar') {
    $dados = [
        ':carro_id' => (int) $_POST['carro_id'],
        ':usuario_id' => $_SESSION['usuario_id'],
        ':tipo' => $_POST['tipo'],
        ':descricao' => $_POST['descricao'],
        ':data' => $_POST['data'],
        ':km' => $_POST['km'] ?: null,
        ':valor' => $_POST['valor'] ?: null,
        ':pago' => isset($_POST['pago']) ? 1 : 0,
        ':proxima_manut_data' => $_POST['proxima_manut_data'] ?: null,
        ':proxima_manut_km' => $_POST['proxima_manut_km'] ?: null,
    ];

    if (addManutencao($pdo, $dados)) {
        echo json_encode(['status' => 'success', 'mensagem' => 'Manutenção cadastrada com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'mensagem' => 'Erro ao cadastrar manutenção.']);
    }
    exit;
}
