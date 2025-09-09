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
    $stmt = $pdo->prepare("
        UPDATE manutencoes_carro
        SET data = :data,
            tipo_id = :tipo_id,
            descricao = :descricao,
            km = :km,
            valor = :valor,
            local = :local,
            pago = :pago,
            proxima_manut_data = :proxima_manut_data,
            proxima_manut_km = :proxima_manut_km
        WHERE id = :id AND carro_id = :carro_id AND usuario_id = :usuario_id
    ");

    // Normaliza valor opcionais para NULL
    $dadosNormalizados = [
        'id'                    => $manutencaoId,
        'carro_id'              => $carroId,
        'usuario_id'            => $usuarioId,
        'tipo_id'               => $dados['tipo_id'] ?? null,
        'data'                  => $dados['data'] ?? null,
        'descricao'             => !empty($dados['descricao']) ? $dados['descricao']: null,
        'km'                    => !empty($dados['km']) ? $dados['km'] : null,
        'valor'                 => !empty($dados['valor']) ? $dados['valor'] : null,
        'local'                 => !empty($dados['local']) ? $dados['local'] : null,
        'pago'                  => isset($dados['pago']) ? 1 : 0,
        'proxima_manut_data'    => !empty($dados['proxima_manut_data']) ? $dados['proxima_manut_data'] : null,
        'proxima_manut_km'      => !empty($dados['proxima_manut_km']) ? $dados['proxima_manut_km'] : null,
    ];

    $stmt->execute($dadosNormalizados);

    echo json_encode(['status' => 'success', 'mensagem' => 'Manutenção atualizada com sucesso!']);
} catch (Throwable $e) {
    echo json_encode(['status' => 'error', 'mensagem' => 'Erro ao atualizar manutenção: ' . $e->getMessage()]);
}
