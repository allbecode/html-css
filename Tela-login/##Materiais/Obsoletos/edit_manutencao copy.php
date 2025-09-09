<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../acsses_control/includes/db.php';
require_once '../acsses_control/includes/session.php';
require_once '../acsses_control/includes/auth.php';

verificaUsuarioLogado();

$usuarioId = $_SESSION['usuario_id'] ?? 0;
$dados = $_POST;

if (!isset($dados['id'], $dados['carro_id'])) {
    die("Parâmetros inválidos.");
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
    $proximaData = !empty($dados['proxima_manut_data']) ? $dados['proxima_manut_data'] : null;
    $proximaKm = !empty($dados['proxima_manut_km']) ? $dados['proxima_manut_km'] : null;

    $stmt->execute([
        'data'       => $dados['data'],
        'tipo_id'    => $dados['tipo_id'],
        'descricao'  => $dados['descricao'] ?? null,
        'km'         => $dados['km'] ?? null,
        'valor'      => $dados['valor'] ?? null,
        'local'      => $dados['local'] ?? null,
        'pago'       => isset($dados['pago']) ? 1 : 0,
        // 'proxima_manut_data'  => $dados ['proxima_manut_data'] ?? null,
        // 'proxima_manut_km'    => $dados ['proxima_manut_km'] ?? null,
        'proxima_manut_data' => $proximaData,
        'proxima_manut_km' => $proximaKm,
        'id'         => $manutencaoId,
        'carro_id'   => $carroId,
        'usuario_id' => $usuarioId
    ]);

    header("Location: ../pages/list_manutencoes.php?carro_id=" . $carroId);
    exit;
} catch (Throwable $e) {
    die("Erro ao atualizar manutenção: " . $e->getMessage());
}
